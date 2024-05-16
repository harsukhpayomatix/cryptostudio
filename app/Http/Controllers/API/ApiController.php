<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\TxTry;
use App\BlockData;
use App\Transaction;
use App\TransactionSession;
use App\WebsiteUrl;
use App\Http\Controllers\Controller;
use App\Traits\Mid;
use App\Traits\RuleCheck;
use App\Traits\BinChecker;
use App\Traits\StoreTransaction;
use App\Transformers\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use Mid, RuleCheck, StoreTransaction, BinChecker;

    protected $user, $tx_try, $transaction, $transactionSession;

    // ================================================
    /* method : __construct
     * @param  : 
     * @description : create new instance of the class
     */// ===============================================

    public function __construct()
    {
        $this->user = new User;
        $this->tx_try = new TxTry;
        $this->transaction = new Transaction;
        $this->transactionSession = new TransactionSession;
    }

    // ================================================
    /* method : store
     * @param  : $request Request
     * @description : receive api v2 request data
     */// ===============================================
    public function store(Request $request)
    {
        $input = $request->only(['first_name', 'last_name', 'email', 'phone_no', 'amount', 'currency', 'address', 'country', 'state', 'city', 'zip', 'customer_order_id', 'response_url', 'webhook_url']);
        $api_key = $request->bearerToken();

        if (empty($api_key)) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, please pass API Key in Header';
            return ApiResponse::unauthorised($input);
        }

        // validate api_key
        $user = DB::table('users')
            ->select('id', 'mid', 'is_ip_remove')
            ->where('api_key', $api_key)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->first();

        // if api_key is not valid or user deleted
        if (empty($user)) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, Invalid API Key or merchant deleted';
            return ApiResponse::unauthorised($input);
        }

        // if merchant on test mode
        if (in_array($user->mid, [1, 2])) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, Only test mode available.';
            return ApiResponse::unauthorised($input);
        }

        // gateway object
        $check_assign_mid = checkAssignMID($user->mid);
        if ($check_assign_mid == false) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, Your account is disabled.';
            return ApiResponse::unauthorised($input);
        }

        // validation checks
        $validator = Validator::make($input, [
            'first_name' => 'required|min:3|max:100|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'amount' => 'required|regex:/^\d+(\.\d{1,9})?$/',
            'currency' => 'required|max:3|min:3|regex:(\b[A-Z]+\b)',
            'address' => 'nullable|min:2|max:250',
            'country' => 'nullable|max:2|min:2|regex:(\b[A-Z]+\b)',
            'state' => 'nullable|min:2|max:250',
            'city' => 'nullable|min:2|max:250',
            'zip' => 'nullable|min:2|max:250',
            'phone_no' => 'nullable|min:5|max:20',
            'customer_order_id' => 'nullable|max:100',
            'response_url' => 'required|url',
            'webhook_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $input['status'] = '6';
            $input['reason'] = $errors[0] ?? 'Unauthorised request, please check your request payload.';
            return ApiResponse::unauthorised($input);
        }

        // check ip_restriction
        if ($user->is_ip_remove == '0') {
            $getIPData = WebsiteUrl::where('user_id', $user->id)
                ->where('ip_address', $this->getClientIP())
                ->first();

            // if IP is not added on the IP whitelist
            if (empty($getIPData)) {
                $input['status'] = '6';
                $input['reason'] = 'Unauthorised request, please whitelist this IP address(' . $this->getClientIP() . ') in your dashboard.';
                return ApiResponse::unauthorised($input);
            }

            // if IP is not approved
            if ($getIPData->is_active == '0') {
                $input['status'] = '6';
                $input['reason'] = 'Unauthorised request, IP address(' . $this->getClientIP() . ') approval pending.';
                return ApiResponse::unauthorised($input);
            }
            $input["website_url_id"] = $getIPData->id;
        }

        $block_email = BlockData::where('type', 'Email')
            ->where('field_value', $input['email'])
            ->exists();
        if (!empty($block_email)) {
            $input['status'] = '5';
            $input['reason'] = 'This email address(' . $input['email'] . ') is blocked for transaction.';
            return ApiResponse::blocked($input);
        }

        // order and session id
        $input['session_id'] = 'SD' . strtoupper(\Str::random(4)) . time();
        $input['order_id'] = 'ODR' . strtoupper(\Str::random(4)) . time() . strtoupper(\Str::random(6));


        // user IP and domain and request from API
        $input['request_from_ip'] = $request->ip();
        $input['payment_type'] = 'card';
        $input['card_type'] = '2';
        $input['request_origin'] = $_SERVER['HTTP_HOST'];
        $input['is_request_from_vt'] = 'API V2';
        $input['user_id'] = $user->id;
        $input['payment_gateway_id'] = $user->mid;
        $input['amount_in_usd'] = $this->amountInUSD($input);

        $this->transactionSession->storeData($input);

        $input['status'] = '7';
        $input['redirect_3ds_url'] = route('api.v2.checkout', $input['order_id']);
        $input['reason'] = 'Please redirect to 3dsUrl.';

        return ApiResponse::redirect($input);
    }

    // ================================================
    /* method : checkout
     * @param  : $id string
     * @description : payment mode selection view card/crypto/bank
     */// ===============================================
    public function checkout($order_id, Request $request)
    {
        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return abort(404);
        }

        $user = User::select('id', 'crypto_mid', 'bank_mid', 'upi_mid', 'mid')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', '1')
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNull('deleted_at')
            ->first();

        if (empty($user)) {
            return abort(404);
        }

        // reset input_details field
        if ($request->retry) {
            $request_data = json_decode($transaction_session->input_details, true);
            $request_data['session_id'] = 'SD' . strtoupper(\Str::random(4)) . time();
            $request_data['ip_address'] = $request_data['ip_address'] ?? $this->getClientIP();
            TransactionSession::where('order_id', $order_id)
                ->where('is_completed', 0)
                ->update([
                    'request_data' => json_encode($request_data),
                    'input_details' => json_encode($request_data),
                    'transaction_id' => $request_data['session_id']
                ]);
        } else {
            $request_data = json_decode($transaction_session->input_details, true);
            $request_data['ip_address'] = $request_data['ip_address'] ?? $this->getClientIP();
            TransactionSession::where('order_id', $order_id)
                ->where('is_completed', 0)
                ->update([
                    'request_data' => json_encode($request_data),
                    'input_details' => json_encode($request_data)
                ]);
        }

        return view('gateway.apiv2.index', compact('transaction_session', 'user'));
    }

    // ================================================
    /* method : card
     * @param  : 
     * @description : card validation and rules apply on card method select
     */// ===============================================
    public function card($order_id)
    {
        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return abort(404);
        }

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNull('deleted_at')
            ->first();

        if (empty($user)) {
            return abort(404);
        }

        $input_data = json_decode($transaction_session['input_details'], true);

        $input = array_filter($input_data, function ($a) {
            return ($a !== null) && $a !== '';
        });

        // user last mid
        $user_mid_response = $this->checkUserLastMID($input, $user);

        // if all validation fails
        if (isset($user_mid_response['status']) && $user_mid_response['status'] == 0) {
            $input['status'] = $user_mid_response['mid']['status'];
            $input['reason'] = $user_mid_response['mid']['reason'];
        } else {
            $input['payment_gateway_id'] = $user_mid_response['mid'];
        }

        if (isset($input['payment_type']) && !empty($input['payment_type']) && $input['payment_type'] != '') {
            // 
        } else {
            $input['payment_type'] = 'Card';
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input_data[$field])) {
                $data[] = $field;
            }
        }

        // redirect to gateway if all requierd fields are available
        if (empty($data)) {
            // mid default currency
            $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);
            if ($check_selected_currency) {
                $input['is_converted'] = '1';
                $input['converted_amount'] = $check_selected_currency['amount'];
                $input['converted_currency'] = $check_selected_currency['currency'];
            } else {
                $input['converted_amount'] = $input['amount'];
                $input['converted_currency'] = $input['currency'];
            }
            $input['card_type'] = '2';

            // update payment_gateway_id in session data
            TransactionSession::where('order_id', $order_id)
                ->where('is_completed', 0)
                ->update([
                    'request_data' => json_encode($input),
                    'payment_gateway_id' => $input['payment_gateway_id']
                ]);

            // gateway curl response
            $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

            // transaction requires 3ds verification
            if ($gateway_curl_response['status'] == '7') {
                return redirect()->away($gateway_curl_response['redirect_3ds_url']);
            }

            $input['status'] = $gateway_curl_response['status'];
            $input['reason'] = $gateway_curl_response['reason'];

            $store_tx_try = $this->tx_try->storeData($input);

            // transaction success
            if ($gateway_curl_response['status'] == '1') {
                $store_transaction_link = $this->storeTransactionAPIVTwo($input);
                return redirect()->away($store_transaction_link);
            } else {
                return redirect()->route('api.v2.decline', $input['order_id']);
            }
        }

        // update payment_gateway_id in session data
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update([
                'request_data' => json_encode($input),
                'payment_gateway_id' => $input['payment_gateway_id']
            ]);

        return view('gateway.apiv2.card', compact('order_id'));
    }

    // ================================================
    /* method : cardSelect
     * @param  : 
     * @description : ajax request on card type select
     */// ===============================================
    public function cardSelect(Request $request, $order_id)
    {
        $card_data = $request->only([
            'address',
            'country',
            'city',
            'state',
            'zip',
            'phone_no',
            'card_no',
            'ccExpiryMonth',
            'ccExpiryYear',
            'cvvNumber',
            'card_type'
        ]);

        // validation checks
        $validator = Validator::make($card_data, [
            'address' => 'nullable|min:2|max:250',
            'country' => 'nullable|max:2|min:2|regex:(\b[A-Z]+\b)',
            'city' => 'nullable|min:2|max:250',
            'state' => 'nullable|min:2|max:250',
            'zip' => 'nullable|min:2|max:250',
            'phone_no' => 'nullable|min:5|max:20',
            'card_no' => 'nullable|min:12|max:24',
            'ccExpiryMonth' => 'nullable|numeric|min:1|max:12',
            'ccExpiryYear' => 'nullable|numeric|min:2023|max:2045',
            'cvvNumber' => 'nullable|numeric|min:0|max:9999',
            'card_type' => 'required|in:1,2,3,4,5,6,7,8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unsupported card type selected.',
            ]);
        }

        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNull('deleted_at')
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        $input_data = json_decode($transaction_session['input_details'], true);

        $input_data = array_filter($input_data, function ($a) {
            return ($a !== null) && $a !== '';
        });
        $card_data = array_filter($card_data, function ($a) {
            return ($a !== null) && $a !== '';
        });

        $input = array_merge($input_data, $card_data);

        // user specific card_type blocked
        $mid_blocked = $this->cardTypeMIDBlocked($input, $user);
        if ($mid_blocked) {
            $input['status'] = $mid_blocked['status'];
            $input['reason'] = $mid_blocked['reason'];

            $store_tx_try = $this->tx_try->storeData($input);
            $html = view('gateway.apiv2.abortForm')->render();
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
                'html' => $html
            ]);
        }

        // user last mid
        $user_mid_response = $this->checkUserLastMID($input, $user);

        // if all validation fails
        if (isset($user_mid_response['status']) && $user_mid_response['status'] == 0) {
            $input['status'] = $user_mid_response['mid']['status'];
            $input['reason'] = $user_mid_response['mid']['reason'];

            $store_tx_try = $this->tx_try->storeData($input);
            $html = view('gateway.apiv2.abortForm')->render();
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
                'html' => $html,
            ]);
        } else {
            $input['payment_gateway_id'] = $user_mid_response['mid'];
        }

        // new payment gateway
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input_data[$field])) {
                $data[] = $field;
            }
        }

        // update input_details field
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update([
                'request_data' => json_encode($input),
                'payment_gateway_id' => $input['payment_gateway_id']
            ]);

        $html = view('gateway.apiv2.detailsForm', compact('data', 'input', 'card_data'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'ok.',
            'html' => $html,
        ]);
    }

    // ================================================
    /* method : liveAjaxValidation
     * @param  : 
     * @description : submit extra details form
     */// ===============================================
    public function liveAjaxValidation(Request $request, $order_id)
    {
        $card_data = $request->only([
            'address',
            'country',
            'city',
            'state',
            'zip',
            'phone_no',
            'card_no',
            'ccExpiryMonth',
            'ccExpiryYear',
            'cvvNumber'
        ]);

        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        if (empty($user->mid)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // required card_details
        if (isset($card_data['card_no']) && strlen($card_data['card_no']) >= 16) {
            $card_data['card_no'] = str_replace(' ', '', trim($card_data['card_no']));
            $card_data['card_type'] = $this->getCreditCardType($card_data['card_no']);

            $card_data['ccExpiryMonth'] = trim($card_data['ccExpiryMonth']);
            $card_data['ccExpiryYear'] = trim($card_data['ccExpiryYear']);
            $card_data['cvvNumber'] = trim($card_data['cvvNumber']);
        }

        $input_data = json_decode($transaction_session['input_details'], true);

        $input_data = array_filter($input_data, function ($a) {
            return ($a !== null) && $a !== '';
        });
        $card_data = array_filter($card_data, function ($a) {
            return ($a !== null) && $a !== '';
        });

        $input = array_merge($input_data, $card_data);

        // card block decline
        if (isset($input['card_no']) && $input['card_no'] != null) {
            $card_no = substr($input["card_no"], 0, 6) . 'XXXXXX' . substr($input["card_no"], -4);
            $block_card = BlockData::where('type', 'Card')
                ->where('field_value', $card_no)
                ->exists();
            if ($block_card) {
                $input['status'] = '5';
                $input['reason'] = 'This card(' . $card_no . ') is blocked for transaction.';
                $store_tx_try = $this->tx_try->storeData($input);
                return response()->json([
                    'status' => 'fail',
                    'message' => $input['reason'],
                ]);
            }

            try {
                $bin_response = $this->binChecking($input);

                $input['card_type'] = config('card.bin_response.' . $bin_response['card-brand']);
            } catch (\Exception $e) {
                $bin_response = false;
            }

            // card type by card_no
            $input['card_type'] = $input['card_type'] ?? $this->getCreditCardType($input['card_no']);
        }

        // user specific card_type blocked
        $mid_blocked = $this->cardTypeMIDBlocked($input, $user);
        if ($mid_blocked) {
            return response()->json([
                'status' => 'fail',
                'message' => $mid_blocked['reason'],
            ]);
        }

        // bin checker only to run if country and card exists
        if (
            isset($input["card_no"]) && $input["card_no"] != null &&
            isset($input["country"]) && $input["country"] != null && false
        ) {
            $bin_response = $bin_response ?? false;

            // bin checker api
            if ($bin_response != false && isset($bin_response['country-code'])) {
                $input['bin_country_code'] = $bin_response['country-code'];
                $input['bin_details'] = json_encode($bin_response);

                if ($input["country"] == 'UK') {
                    $input["country"] = 'GB';
                }
                if ($bin_response["country-code"] != $input["country"]) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'The card issuing country is different than the country selected.',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'The card issuing country is different than the country selected.',
                ]);
            }
        }

        // user last mid
        $user_mid_response = $this->checkUserLastMID($input, $user);

        // if all validation fails
        if (isset($user_mid_response['status']) && $user_mid_response['status'] == 0) {
            return response()->json([
                'status' => 'fail',
                'message' => $user_mid_response['mid']['reason'],
            ]);
        } else {
            $input['payment_gateway_id'] = $user_mid_response['mid'];
        }

        // new payment gateway
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input_data[$field])) {
                $data[] = $field;
            }
        }

        if (!empty($data)) {
            $html = view('gateway.apiv2.detailsForm', compact('data', 'input', 'card_data'))->render();

            return response()->json([
                'status' => 'success',
                'message' => 'ok.',
                'html' => $html,
                'cardType' => $input['card_type'] ?? 2,
            ]);
        }

        return response()->json([
            'status' => 'hold',
            'message' => 'ok.'
        ]);
    }

    // ================================================
    /* method : extraDetailsFormSubmit
     * @param  : 
     * @description : submit extra details form
     */// ===============================================
    public function extraDetailsFormSubmit(Request $request, $order_id)
    {
        $card_data = $request->only([
            'address',
            'country',
            'city',
            'state',
            'zip',
            'phone_no',
            'card_no',
            'ccExpiryMonth',
            'ccExpiryYear',
            'cvvNumber'
        ]);

        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        if (empty($user->mid)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // required card_details
        if (isset($card_data['card_no']) && strlen($card_data['card_no']) >= 16) {
            $card_data['card_no'] = str_replace(' ', '', trim($card_data['card_no']));
        }
        if (isset($card_data['ccExpiryMonth']) && ($card_data['ccExpiryMonth'] >= 01 && $card_data['ccExpiryMonth'] <= 12)) {
            $card_data['ccExpiryMonth'] = trim($card_data['ccExpiryMonth']);
        }
        if (isset($card_data['ccExpiryYear']) && ($card_data['ccExpiryYear'] >= 2023 && $card_data['ccExpiryYear'] <= 2050)) {
            $card_data['ccExpiryYear'] = trim($card_data['ccExpiryYear']);
        }
        if (isset($card_data['cvvNumber']) && ($card_data['cvvNumber'] >= 0000 && $card_data['cvvNumber'] <= 9999)) {
            $card_data['cvvNumber'] = trim($card_data['cvvNumber']);
        }

        $input_data = json_decode($transaction_session['input_details'], true);

        $input_data = array_filter($input_data, function ($a) {
            return ($a !== null) && $a !== '';
        });
        $card_data = array_filter($card_data, function ($a) {
            return ($a !== null) && $a !== '';
        });

        $input = array_merge($input_data, $card_data);

        // card block or expired
        if (isset($input['card_no']) && !empty($input['card_no'])) {
            $card_no = substr($input["card_no"], 0, 6) . 'XXXXXX' . substr($input["card_no"], -4);
            $block_card = BlockData::where('type', 'Card')
                ->where('field_value', $card_no)
                ->exists();
            if ($block_card) {
                $input['status'] = '5';
                $input['reason'] = 'This card(' . $card_no . ') is blocked for transaction.';
                $store_tx_try = $this->tx_try->storeData($input);
                return response()->json([
                    'status' => 'fail',
                    'message' => $input['reason'],
                ]);
            }
            if (isset($card_data['ccExpiryMonth']) && isset($card_data['ccExpiryYear'])) {
                if (strtotime($card_data['ccExpiryYear'] . '-' . $card_data['ccExpiryMonth']) < strtotime(date('Y-m'))) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'This card(' . $card_no . ') is Expired.',
                    ]);
                }
            }

            try {
                $bin_response = $this->binChecking($input);
                if (isset($bin_response['card-brand']) && !empty($bin_response['card-brand'])) {
                    $card_type = config('card.bin_response.' . $bin_response['card-brand']);
                }
            } catch (\Exception $e) {
                $bin_response = false;
                \Log::info(['bin_response_error' => $e->getMessage()]);
            }

            // card type by card_no
            $input['card_type'] = $card_type ?? $this->getCreditCardType($input['card_no']);
        }

        // user specific card_type blocked
        $mid_blocked = $this->cardTypeMIDBlocked($input, $user);
        if ($mid_blocked) {
            $input['status'] = $mid_blocked['status'];
            $input['reason'] = $mid_blocked['reason'];
            $store_tx_try = $this->tx_try->storeData($input);
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
            ]);
        }

        // bin checker only to run if country and card exists
        if (
            isset($input["card_no"]) && !empty($input["card_no"]) &&
            isset($input["country"]) && !empty($input["country"])
        ) {
            $input['bin_details'] = json_encode($bin_response);
            if ($user->is_bin_remove == '0') {
                $bin_response = $bin_response ?? false;

                // bin checker api
                if ($bin_response != false && isset($bin_response['country-code'])) {
                    $input['bin_country_code'] = $bin_response['country-code'];

                    if ($input["country"] == 'UK') {
                        $input["country"] = 'GB';
                    }
                    if ($bin_response["country-code"] != $input["country"]) {
                        $input['status'] = '5';
                        $input['reason'] = 'The card issuing country is different than the country selected.';
                        $store_tx_try = $this->tx_try->storeData($input);
                        return response()->json([
                            'status' => 'fail',
                            'message' => $input['reason'],
                        ]);
                    }
                } else {
                    $input['status'] = '5';
                    $input['reason'] = 'The card issuing country is different than the country selected.';
                    $store_tx_try = $this->tx_try->storeData($input);
                    return response()->json([
                        'status' => 'fail',
                        'message' => $input['reason'],
                    ]);
                }
            }
        }

        // user last mid
        $user_mid_response = $this->checkUserLastMID($input, $user);

        // if all validation fails
        if (isset($user_mid_response['status']) && $user_mid_response['status'] == 0) {
            $input['status'] = $user_mid_response['mid']['status'];
            $input['reason'] = $user_mid_response['mid']['reason'];

            $store_tx_try = $this->tx_try->storeData($input);
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
            ]);
        } else {
            $input['payment_gateway_id'] = $user_mid_response['mid'];
        }

        // new payment gateway
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);
        $input['mid_type'] = $check_assign_mid->mid_type;

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        // create validations array
        foreach ($required_fields as $value) {
            if (in_array($value, array_keys(config('required_v2.validate')))) {
                $new_validations[$value] = config('required_v2.validate.' . $value);
            }
        }

        $validator = Validator::make($input, $new_validations);
        if ($validator->fails()) {
            $errors = $validator->errors()->messages();

            $error_array = [];
            foreach ($errors as $error) {
                array_push($error_array, $error[0]);
            }

            $input['status'] = '5';
            $input['reason'] = 'Some parameters are missing or invalid request data, please check \'errors\' parameter for more details.';
            $store_tx_try = $this->tx_try->storeData($input);
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
                'errors' => $error_array,
            ]);
        }

        // mid default currency
        $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);
        if ($check_selected_currency) {
            $input['is_converted'] = '1';
            $input['converted_amount'] = $check_selected_currency['amount'];
            $input['converted_currency'] = $check_selected_currency['currency'];
        } else {
            $input['converted_amount'] = $input['amount'];
            $input['converted_currency'] = $input['currency'];
        }

        // update transaction_session
        $input_data = array_merge($input_data, $card_data);
        $input_data["card_no"] = $card_no ?? null;
        $input_data["card_type"] = $card_type ?? '2';
        $input_data["cvvNumber"] = isset($input['cvvNumber']) ? 'XXX' : null;
        $input_data['payment_gateway_id'] = $input['payment_gateway_id'];
        $input_data['mid_type'] = $input['mid_type'];

        $input_data['is_converted'] = $input['is_converted'] ?? '0';
        $input_data['converted_amount'] = $input['converted_amount'];
        $input_data['converted_currency'] = $input['converted_currency'];

        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update([
                'request_data' => json_encode($input_data),
                'payment_gateway_id' => $input_data['payment_gateway_id']
            ]);

        // gateway curl response
        $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

        // transaction requires 3ds verification
        if ($gateway_curl_response['status'] == '7') {
            return response()->json([
                'status' => 'success',
                'message' => '3DS Link generated successfully.',
                'url' => $gateway_curl_response['redirect_3ds_url'],
            ]);
        }

        $input['status'] = $gateway_curl_response['status'];
        $input['reason'] = $gateway_curl_response['reason'];

        // transaction success
        if ($gateway_curl_response['status'] == '1') {
            $store_transaction_link = $this->storeTransactionAPIVTwo($input);

            return response()->json([
                'status' => 'success',
                'message' => $input['reason'],
                'url' => route('api.v2.success', $input['order_id'])
            ]);
        }

        $store_tx_try = $this->tx_try->storeData($input);

        return response()->json([
            'status' => 'success',
            'message' => $input['reason'],
            'url' => route('api.v2.decline', $order_id)
        ]);
    }

    // ================================================
    /* method : checkUserLastMID
     * @param  : 
     * @description : return user mid after limits and validation
     */// ===============================================
    private function checkUserLastMID($input, $user)
    {
        $mid_resume = true;
        $mid_validations = false;

        // don't change payment_gateway_id if iframe generated by admin
        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'Card'
        ) {
            // payment gateway object
            $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

            if ($check_assign_mid !== false) {
                $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);
            }
        } else {
            // check general card rules and user card rules
            if (isset($user->is_disable_rule) && $user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userCardRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {

                    $mid_resume = false;
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->cardRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {

                        $mid_resume = false;
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }

            // user specific card_type mid for 1/2/3/4 only
            if ($mid_resume == true) {
                $user_specific_mid = $this->userCardTypeMID($input, $user);

                if ($user_specific_mid != false) {
                    $input['payment_gateway_id'] = $user_specific_mid;

                    // payment gateway object
                    $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

                    if ($check_assign_mid !== false) {
                        $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);

                        if ($mid_validations == false) {
                            $mid_resume = false;
                        }
                    }
                }
            }

            // user default mid
            if ($mid_resume == true) {
                $input['payment_gateway_id'] = $user->mid;

                // payment gateway object
                $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

                if ($check_assign_mid !== false) {
                    $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);

                    if ($mid_validations == false) {
                        $mid_resume = false;
                    }
                }
            }

            // if user has selected visa and merchant has multiple visa mid
            if (
                isset($input['card_type']) && $input['card_type'] == '2' &&
                !empty($user->multiple_mid) && $mid_resume == true
            ) {
                $visa_mid = $this->multipleVisa($input, $user);

                if ($visa_mid) {
                    $input['payment_gateway_id'] = $visa_mid;

                    $mid_resume = false;
                    $mid_validations = false;
                }
            }

            // if user has selected mastercard and merchant has multiple master mid
            if (
                isset($input['card_type']) && $input['card_type'] == '3' &&
                !empty($user->multiple_mid_master) && $mid_resume == true
            ) {
                $master_mid = $this->multipleMaster($input, $user);

                if ($master_mid) {
                    $input['payment_gateway_id'] = $master_mid;

                    $mid_resume = false;
                    $mid_validations = false;
                }
            }
        }

        // if mid limits reached
        if ($mid_validations) {
            return [
                'status' => 0,
                'mid' => $mid_validations
            ];
        } else {
            return [
                'status' => 1,
                'mid' => $input['payment_gateway_id']
            ];
        }
    }

    // ================================================
    /* method : multipleVisa
     * @param  : 
     * @description : multiple visa
     */// ===============================================
    public function multipleVisa($input, $user)
    {
        if ($input['card_type'] == '2' && !empty($user->multiple_mid)) {
            $multiple_mid = json_decode($user->multiple_mid);

            foreach ($multiple_mid as $value) {
                $input['payment_gateway_id'] = $value;
                $check_assign_mid = checkAssignMID($input['payment_gateway_id']);
                if ($check_assign_mid == false) {
                    continue;
                }

                // mid validation
                $mid_limit_response = $this->getMIDLimitResponse($input, $check_assign_mid, $user);
                if ($mid_limit_response != false) {
                    continue;
                }

                return $value;
            }
        }
        return false;
    }

    // ================================================
    /* method : multipleMaster
     * @param  : 
     * @description : multiple master mid
     */// ===============================================
    public function multipleMaster($input, $user)
    {
        if ($input['card_type'] == '3' && !empty($user->multiple_mid_master)) {
            $multiple_mid = json_decode($user->multiple_mid_master);

            foreach ($multiple_mid as $value) {
                $input['payment_gateway_id'] = $value;
                $check_assign_mid = checkAssignMID($input['payment_gateway_id']);
                if ($check_assign_mid == false) {
                    continue;
                }

                // mid validation
                $mid_limit_response = $this->getMIDLimitResponse($input, $check_assign_mid, $user);
                if ($mid_limit_response != false) {
                    continue;
                }

                return $value;
            }
        }
        return false;
    }

    // ================================================
    /* method : bank
     * @param  : 
     * @description : bank selected view
     */// ===============================================
    public function bank($order_id)
    {
        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return abort(404);
        }

        $input_data = json_decode($transaction_session['input_details'], true);
        $input = array_filter($input_data, function ($a) {
            return ($a !== null) && $a !== '';
        });

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('users.mid')
            ->where('users.mid', '!=', '0')
            ->whereNotNull('users.bank_mid')
            ->where('users.bank_mid', '!=', '0')
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // user last mid
        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'Bank'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->bank_mid;
        } else {
            $input['payment_gateway_id'] = $user->bank_mid;

            // apply rules and get payment_gateway_id
            if (isset($user->is_disable_rule) && $user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userBankRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->bankRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        if ($check_assign_mid == false) {
            return abort(404);
        }

        $input['mid_type'] = $check_assign_mid->mid_type;

        $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);

        if ($mid_validations != false) {
            $input['status'] = $mid_validations['status'];
            $input['reason'] = $mid_validations['reason'];
            $store_tx_try = $this->tx_try->storeData($input);
            return redirect()->route('api.v2.decline', $input['order_id']);
        }

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input_data[$field])) {
                $data[] = $field;
            }
        }

        // redirect to gateway if all requierd fields are available
        if (empty($data)) {
            // mid default currency
            $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);
            if ($check_selected_currency) {
                $input['is_converted'] = '1';
                $input['converted_amount'] = $check_selected_currency['amount'];
                $input['converted_currency'] = $check_selected_currency['currency'];
            } else {
                $input['converted_amount'] = $input['amount'];
                $input['converted_currency'] = $input['currency'];
            }
            $input['payment_type'] = 'bank';

            // update payment_gateway_id in session data
            TransactionSession::where('order_id', $order_id)
                ->where('is_completed', 0)
                ->update([
                    'request_data' => json_encode($input),
                    'payment_gateway_id' => $input['payment_gateway_id']
                ]);

            // gateway curl response
            $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

            // transaction requires 3ds verification
            if ($gateway_curl_response['status'] == '7') {
                return redirect()->away($gateway_curl_response['redirect_3ds_url']);
            }

            $input['status'] = $gateway_curl_response['status'];
            $input['reason'] = $gateway_curl_response['reason'];

            $store_tx_try = $this->tx_try->storeData($input);

            // transaction success
            if ($gateway_curl_response['status'] == '1') {
                $store_transaction_link = $this->storeTransactionAPIVTwo($input);

                return redirect()->away($store_transaction_link);
            } else {
                return redirect()->route('api.v2.decline', $input['order_id']);
            }
        }

        // update payment_gateway_id in session data
        TransactionSession::where('order_id', $order_id)
            ->update(['request_data' => json_encode($input)]);

        // send request to paymentgateway
        return view('gateway.apiv2.bank', compact('order_id', 'data', 'input'));
    }

    // ================================================
    /* method : bankSubmit
     * @param  : 
     * @description : bank submit page
     */// ===============================================
    public function bankSubmit(Request $request, $order_id)
    {
        $card_data = $request->only([
            'address',
            'country',
            'city',
            'state',
            'zip',
            'phone_no'
        ]);

        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('users.mid')
            ->where('users.mid', '!=', '0')
            ->whereNotNull('users.bank_mid')
            ->where('users.bank_mid', '!=', '0')
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Your Bank MID not available.',
            ]);
        }

        $input_data = json_decode($transaction_session['request_data'], true);

        $input_data = array_filter($input_data, function ($a) {
            return ($a !== null) && $a !== '';
        });
        $card_data = array_filter($card_data, function ($a) {
            return ($a !== null) && $a !== '';
        });

        $input = array_merge($input_data, $card_data);

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'Bank'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->bank_mid;
        } else {
            $input['payment_gateway_id'] = $user->bank_mid;

            // apply rules and get payment_gateway_id
            if (isset($user->is_disable_rule) && $user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userBankRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->bankRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);
        $input['mid_type'] = $check_assign_mid->mid_type;
        $input['payment_type'] = 'bank';

        $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);

        if ($mid_validations != false) {
            $input['status'] = $mid_validations['status'];
            $input['reason'] = $mid_validations['reason'];
            $store_tx_try = $this->tx_try->storeData($input);
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
            ]);
        }

        // mid default currency
        $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);
        if ($check_selected_currency) {
            $input['is_converted'] = '1';
            $input['converted_amount'] = $check_selected_currency['amount'];
            $input['converted_currency'] = $check_selected_currency['currency'];
        } else {
            $input['converted_amount'] = $input['amount'];
            $input['converted_currency'] = $input['currency'];
        }

        // update request_data field
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update([
                'request_data' => json_encode($input),
                'payment_gateway_id' => $input['payment_gateway_id']
            ]);

        // gateway curl response
        $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

        // transaction requires 3ds verification
        if ($gateway_curl_response['status'] == '7') {
            return response()->json([
                'status' => 'success',
                'message' => '3DS Link generated successfully.',
                'url' => $gateway_curl_response['redirect_3ds_url'],
            ]);
        }

        $input['status'] = $gateway_curl_response['status'];
        $input['reason'] = $gateway_curl_response['reason'];

        // transaction success
        if ($gateway_curl_response['status'] == '1') {
            $store_transaction_link = $this->storeTransactionAPIVTwo($input);

            return response()->json([
                'status' => 'success',
                'message' => $input['reason'],
                'url' => $store_transaction_link
            ]);
        }

        $store_tx_try = $this->tx_try->storeData($input);

        return response()->json([
            'status' => 'success',
            'message' => $input['reason'],
            'url' => route('api.v2.decline', $order_id)
        ]);
    }

    // ================================================
    /* method : crypto
     * @param  : 
     * @description : crypto select view
     */// ===============================================
    public function crypto($order_id)
    {
        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return abort(404);
        }

        $input = json_decode($transaction_session['input_details'], true);

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNotNull('crypto_mid')
            ->whereNotIn('crypto_mid', [0, 1, 2])
            ->first();

        if (empty($user)) {
            return abort(404);
        }

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'Crypto'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->crypto_mid;
        } else {
            $input['payment_gateway_id'] = $user->crypto_mid;

            // apply rules and get payment_gateway_id
            if ($user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userCryptoRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->cryptoRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        if ($check_assign_mid == false) {
            return abort(404);
        }

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input[$field]) || $input[$field] == null) {
                $data[] = $field;
            }
        }

        // update request_data field
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update(['request_data' => json_encode($input)]);

        // send request to paymentgateway
        return view('gateway.apiv2.crypto', compact('order_id', 'data', 'input'));
    }

    // ================================================
    /* method : cryptoSubmit
     * @param  : 
     * @description : bank submit page
     */// ===============================================
    public function cryptoSubmit(Request $request, $order_id)
    {
        $card_data = $request->only([
            'address',
            'country',
            'city',
            'state',
            'zip',
            'phone_no',
            'currency_type'
        ]);
       
        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNotNull('crypto_mid')
            ->whereNotIn('crypto_mid', [0, 1, 2])
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Crypto MID not available.',
            ]);
        }

        $input_data = json_decode($transaction_session['request_data'], true);
        $input_data = array_filter($input_data, function ($a) {
            return $a !== null;
        });

        $input = array_merge($card_data, $input_data);

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'Crypto'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->crypto_mid;
        } else {
            $input['payment_gateway_id'] = $user->crypto_mid;

            // apply rules and get payment_gateway_id
            if ($user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userCryptoRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->cryptoRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);
        $input['mid_type'] = $check_assign_mid->mid_type;
        $input['payment_type'] = 'crypto';

        $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);

        if ($mid_validations != false) {
            $input['status'] = $mid_validations['status'];
            $input['reason'] = $mid_validations['reason'];
            $store_tx_try = $this->tx_try->storeData($input);
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
            ]);
        }

        // mid default currency
        $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);
        if ($check_selected_currency) {
            $input['is_converted'] = '1';
            $input['converted_amount'] = $check_selected_currency['amount'];
            $input['converted_currency'] = $check_selected_currency['currency'];
        } else {
            $input['converted_amount'] = $input['amount'];
            $input['converted_currency'] = $input['currency'];
        }

        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 1)
            ->update([
                'request_data' => json_encode($input),
                'payment_gateway_id' => $input['payment_gateway_id']
            ]);
        // gateway curl response
        $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

        // transaction requires 3ds verification
        if ($gateway_curl_response['status'] == '7') {
            return response()->json([
                'status' => 'success',
                'message' => '3DS Link generated successfully.',
                'url' => $gateway_curl_response['redirect_3ds_url'],
            ]);
        }

        $input['status'] = $gateway_curl_response['status'];
        $input['reason'] = $gateway_curl_response['reason'];

        // transaction success
        if ($gateway_curl_response['status'] == '1') {
            $store_transaction_link = $this->storeTransactionAPIVTwo($input);

            return response()->json([
                'status' => 'success',
                'message' => $input['reason'],
                'url' => $store_transaction_link
            ]);
        }

        $store_tx_try = $this->tx_try->storeData($input);

        return response()->json([
            'status' => 'success',
            'message' => $input['reason'],
            'url' => route('api.v2.decline', $order_id)
        ]);
    }

    // ================================================
    /* method : upi
     * @param  : 
     * @description : upi select view
     */// ===============================================
    public function upi($order_id)
    {
        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return abort(404);
        }

        $input = json_decode($transaction_session['input_details'], true);

        // validate user and payment_gateway_id
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNotNull('upi_mid')
            ->whereNotIn('upi_mid', [0, 1, 2])
            ->first();

        if (empty($user)) {
            return abort(404);
        }

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'UPI'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->upi_mid;
        } else {
            $input['payment_gateway_id'] = $user->upi_mid;

            // apply rules and get payment_gateway_id
            if (isset($user->is_disable_rule) && $user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userUPIRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->upiRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        if ($check_assign_mid == false) {
            return abort(404);
        }

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input[$field]) || $input[$field] == null) {
                $data[] = $field;
            }
        }

        // update request_data field
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update([
                'request_data' => json_encode($input),
                'payment_gateway_id' => $input['payment_gateway_id']
            ]);

        // send request to paymentgateway
        return view('gateway.apiv2.upi', compact('order_id', 'data', 'input'));
    }

    // ================================================
    /* method : upiSubmit
     * @param  : 
     * @description : bank submit page
     */// ===============================================
    public function upiSubmit(Request $request, $order_id)
    {
        $card_data = $request->only([
            'address',
            'country',
            'city',
            'state',
            'zip',
            'phone_no',
            'upi'
        ]);

        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The link is expired, please try again.',
            ]);
        }

        // validate user and payment_gateway_id
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNotNull('upi_mid')
            ->whereNotIn('upi_mid', [0, 1, 2])
            ->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Your UPI MID is not available.',
            ]);
        }

        $input_data = json_decode($transaction_session['request_data'], true);
        $input_data = array_filter($input_data, function ($a) {
            return $a !== null;
        });

        $input = array_merge($card_data, $input_data);

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'UPI'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->upi_mid;
        } else {
            $input['payment_gateway_id'] = $user->upi_mid;

            // apply rules and get payment_gateway_id
            if (isset($user->is_disable_rule) && $user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userUPIRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->upiRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);
        $input['mid_type'] = $check_assign_mid->mid_type;
        $input['payment_type'] = 'upi';

        $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $user);

        if ($mid_validations != false) {
            $input['status'] = $mid_validations['status'];
            $input['reason'] = $mid_validations['reason'];
            $store_tx_try = $this->tx_try->storeData($input);
            return response()->json([
                'status' => 'fail',
                'message' => $input['reason'],
            ]);
        }

        // mid default currency
        $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);
        if ($check_selected_currency) {
            $input['is_converted'] = '1';
            $input['converted_amount'] = $check_selected_currency['amount'];
            $input['converted_currency'] = $check_selected_currency['currency'];
        } else {
            $input['converted_amount'] = $input['amount'];
            $input['converted_currency'] = $input['currency'];
        }

        // update request_data field
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update([
                'request_data' => json_encode($input),
                'payment_gateway_id' => $input['payment_gateway_id']
            ]);

        // gateway curl response
        $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

        // transaction requires 3ds verification
        if ($gateway_curl_response['status'] == '7') {
            return response()->json([
                'status' => 'success',
                'message' => '3DS Link generated successfully.',
                'url' => $gateway_curl_response['redirect_3ds_url'],
            ]);
        }

        $input['status'] = $gateway_curl_response['status'];
        $input['reason'] = $gateway_curl_response['reason'];

        // transaction success
        if ($gateway_curl_response['status'] == '1') {
            $store_transaction_link = $this->storeTransactionAPIVTwo($input);

            return response()->json([
                'status' => 'success',
                'message' => $input['reason'],
                'url' => $store_transaction_link
            ]);
        }

        $store_tx_try = $this->tx_try->storeData($input);

        return response()->json([
            'status' => 'success',
            'message' => $input['reason'],
            'url' => route('api.v2.decline', $order_id)
        ]);
    }

    // ================================================
    /* method : success
     * @param  : 
     * @description : success page after transaction success
     */// ===============================================
    public function success($order_id)
    {
        $input = Transaction::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($input)) {
            return abort(404);
        }

        $redirect_url = $this->getRedirectLink($input);

        // send declined message
        return view('gateway.apiv2.success', compact('input', 'redirect_url'));
    }

    // ================================================
    /* method : decline
     * @param  : 
     * @description : decline page after transaction decline
     */// ===============================================
    public function decline($order_id)
    {
        $tx = TxTry::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->orderBy('id', 'desc')
            ->first();

        if (empty($tx)) {
            $tx = TransactionSession::where('order_id', $order_id)
                ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
                ->whereNotIn('payment_gateway_id', [0, 1, 2])
                ->whereNotNull('payment_gateway_id')
                ->where('is_completed', 0)
                ->orderBy('id', 'desc')
                ->first();
        }

        if (empty($tx)) {
            return abort(404);
        }

        $input = json_decode($tx['request_data'], true);

        $input['status'] = $input['status'] ?? '3';
        $input['reason'] = $input['reason'] ?? 'Transaction canceled by the user.';

        // send declined message
        return view('gateway.apiv2.decline', compact('input'));
    }

    // ================================================
    /* method : redirect
     * @param  : 
     * @description : redirect to merchant website after transaction decline
     */// ===============================================
    public function redirect($order_id)
    {
        $tx = TxTry::where('order_id', $order_id)
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->orderBy('id', 'desc')
            ->first();

        if (empty($tx)) {
            $tx = TransactionSession::where('order_id', $order_id)
                ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
                ->whereNotIn('payment_gateway_id', [0, 1, 2])
                ->whereNotNull('payment_gateway_id')
                ->where('is_completed', 0)
                ->orderBy('id', 'desc')
                ->first();
        }

        if (empty($tx)) {
            return abort(404);
        }

        $input = json_decode($tx['request_data'], true);

        $input['status'] = $input['status'] ?? '3';
        $input['reason'] = $input['reason'] ?? 'Transaction canceled by the user.';

        // transaction success
        $store_transaction_link = $this->storeTransactionAPIVTwo($input);

        return redirect()->away($store_transaction_link);
    }

    // ================================================
    /* method : gatewayCurlResponse
     * @param  :
     * @description : get first response from gateway
     */// ==============================================
    public function gatewayCurlResponse($input, $check_assign_mid)
    {
        try {
            $class_name = 'App\\Http\\Controllers\\Repo\\PaymentGateway\\' . $check_assign_mid->title;
            if (class_exists($class_name)) {
                $gateway_class = new $class_name;
                $gateway_return_data = $gateway_class->checkout($input, $check_assign_mid);
            } else {
                $gateway_return_data['status'] = '0';
                $gateway_return_data['reason'] = 'Payment gateway not available.';
            }
        } catch (\Exception $exception) {
            \Log::info([
                'CardPaymentException' => $exception->getMessage()
            ]);
            $gateway_return_data['status'] = '0';
            $gateway_return_data['reason'] = 'Problem with your transaction data or may be transaction timeout from the bank.';
        }

        return $gateway_return_data;
    }

    // ================================================
    /* method : getMIDLimitResponse
     * @param  :
     * @description : validate mid all limits
     * @description : all methods in this method are extended from Mid trait
     */// ==============================================
    public function getMIDLimitResponse($input, $check_assign_mid, $user)
    {
        // per transaction maximum and minimum amount limit
        $per_transaction_limit_response = $this->perTransactionLimitCheck($input, $check_assign_mid, $user);
        if ($per_transaction_limit_response != false) {
            return $per_transaction_limit_response;
        }

        // mid daily limit
        $mid_daily_limit = $this->perDayAmountLimitCheck($input, $check_assign_mid, $user);
        if ($mid_daily_limit != false) {
            return $mid_daily_limit;
        }

        $transactions_check = \DB::table('transactions')
            ->whereNull('deleted_at')
            ->where('status', '<>', '5')
            ->where('user_id', $input['user_id'])
            ->where('payment_gateway_id', $input['payment_gateway_id']);

        // if there is card_no
        if (isset($input['card_no']) && $input['card_no'] != null) {

            $daily_card_decline_check = \DB::table('transactions')
                ->whereNull('deleted_at')
                ->where('status', '0')
                ->where('user_id', $input['user_id'])
                ->where('card_no', substr($input['card_no'], 0, 6) . 'XXXXXX' . substr($input['card_no'], -4))
                ->whereNotIn('payment_gateway_id', [0, 1, 2])
                ->whereBetween('created_at', [Carbon::now()->subMinutes(30)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($daily_card_decline_check >= $user->daily_card_decline_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per day card declined limit exceeded.'
                ];
            }

            $card_transactions_check = $transactions_check->where('card_no', substr($input['card_no'], 0, 6) . 'XXXXXX' . substr($input['card_no'], -4));

            // daily card limit check
            $card_daily_transactions = $card_transactions_check->whereBetween('created_at', [Carbon::now()->subDays(1)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($card_daily_transactions >= $check_assign_mid->per_day_card && $card_daily_transactions >= $user->one_day_card_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per day transactions by card limit exceeded.'
                ];
            }

            // card per-week limit
            $card_weekly_transactions = $card_transactions_check->whereBetween('created_at', [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($card_weekly_transactions >= $check_assign_mid->per_week_card && $card_weekly_transactions >= $user->one_week_card_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per week transactions by card limit exceeded.'
                ];
            }

            // card per-month limit
            $card_monthly_transactions = $card_transactions_check->whereBetween('created_at', [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($card_monthly_transactions >= $check_assign_mid->per_month_card && $card_monthly_transactions >= $user->one_month_card_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per month transactions by card limit exceeded.'
                ];
            }
        }

        // if there is email
        if (isset($input['email']) && $input['email'] != null) {

            $email_transactions_check = $transactions_check->where('email', $input['email']);

            // email per-day limit
            $email_daily_transactions = $email_transactions_check->whereBetween('created_at', [Carbon::now()->subDays(1)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($email_daily_transactions >= $check_assign_mid->per_day_email && $email_daily_transactions >= $user->one_day_email_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per day transactions by email limit exceeded.'
                ];
            }

            // email per-week limit
            $email_weekly_transactions = $email_transactions_check->whereBetween('created_at', [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($email_weekly_transactions >= $check_assign_mid->per_week_email && $email_weekly_transactions >= $user->one_week_email_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per week transactions by email limit exceeded.'
                ];
            }

            // email per-month limit
            $email_monthly_transactions = $email_transactions_check->whereBetween('created_at', [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($email_monthly_transactions >= $check_assign_mid->per_month_card && $email_monthly_transactions >= $user->one_month_email_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per month transactions by email limit exceeded.'
                ];
            }
        }

        // blocked country validation
        if (isset($input['country']) && $input['country'] != null) {
            $blocked_country_response = $this->validateBlockedCountry($input, $check_assign_mid);
            if ($blocked_country_response != false) {
                return $blocked_country_response;
            }
        }

        return false;
    }

    // ================================================
    /* method : getClientIP
     * @param  : 
     * @description : get client ip address perfectly
     */// ===============================================
    public function getClientIP()
    {
        $ip_address = '';

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = 'UNKNOWN';
        }

        return $ip_address;
    }

    // ================================================
    /* method : getCreditCardType
     * @param  :
     * @Description : return card_type
     */// ==============================================
    public function getCreditCardType($card_no)
    {
        if (empty($card_no)) {
            return false;
        }
        $cardtype = array(
            "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex" => "/^3[47]\d{13,14}$/",
            "jcb" => "/^(?:2131|1800|35\d{3})\d{11}$/",
            "solo" => "/^(6334|6767)[0-9]{12}|(6334|6767)[0-9]{14}|(6334|6767)[0-9]{15}$/",
            "maestro" => "/^(5018|5020|5038|6304|6759|6761|6763|6768)[0-9]{8,15}$/",
            "discover" => "/^65[4-9][0-9]{13}|64[4-9][0-9]{13}|6011[0-9]{12}|(622(?:12[6-9]|1[3-9][0-9]|[2-8][0-9][0-9]|9[01][0-9]|92[0-5])[0-9]{10})$/",
            "switch" => "/^(4903|4905|4911|4936|6333|6759)[0-9]{12}|(4903|4905|4911|4936|6333|6759)[0-9]{14}|(4903|4905|4911|4936|6333|6759)[0-9]{15}|564182[0-9]{10}|564182[0-9]{12}|564182[0-9]{13}|633110[0-9]{10}|633110[0-9]{12}|633110[0-9]{13}$/",
            "unionpay" => "/^(62[0-9]{14,17})$/",
        );

        if (preg_match($cardtype['visa'], $card_no)) {
            return '2';
        } else if (preg_match($cardtype['mastercard'], $card_no)) {
            return '3';
        } else if (preg_match($cardtype['amex'], $card_no)) {
            return '1';
        } else if (preg_match($cardtype['discover'], $card_no)) {
            return '4';
        } else if (preg_match($cardtype['jcb'], $card_no)) {
            return '5';
        } else if (preg_match($cardtype['maestro'], $card_no)) {
            return '6';
        } else if (preg_match($cardtype['switch'], $card_no)) {
            return '7';
        } else if (preg_match($cardtype['solo'], $card_no)) {
            return '8';
        } else if (preg_match($cardtype['unionpay'], $card_no)) {
            return '9';
        } else {
            return '0';
        }
    }
}