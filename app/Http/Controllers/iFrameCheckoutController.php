<?php

namespace App\Http\Controllers;

use DB;
use URL;
use Mail;
use View;
use Input;
use Session;
use Redirect;
use Validator;
use App\User;
use App\WebsiteUrl;
use App\Admin;
use App\Gateway;
use App\ImageUpload;
use App\MainMID;
use App\Transaction;
use App\Merchantapplication;
use App\Http\Controllers\Controller;
use App\Mail\TransactionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\StoreTransaction;
use App\TransactionHostedSession;
use Illuminate\Support\Facades\Http;

class iFrameCheckoutController extends Controller
{
    use StoreTransaction;

    protected $user, $Transaction;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = new User;
        $this->Transaction = new Transaction;
    }

    // ================================================
    /* method : index
    * @param  : 
    * @description : Show the iframe form view.
    */ // ===============================================
    public function index($token, Request $request)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'dsflkIZxusugQdpMyjqTSE3sadjL5vsd';
        $secret_iv = '7sad4vdsJjas87saMLmlNi9x63MRAFLgk';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        // decrypt token
        $iframe_json = openssl_decrypt(base64_decode($token), $encrypt_method, $key, 0, $iv);

        if ($iframe_json == false) {
            return view('gateway.response')->with('responseMessage', 'Invalid payment link.');
        }

        $iframe_array = json_decode($iframe_json, 1);

        $userData = User::where('id', $iframe_array['user_id'])
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->first();

        if (empty($userData)) {
            return view('gateway.response')->with('responseMessage', 'Merchant account disabled or deleted.');
        }

        if ($userData->mid == '0') {
            return view('gateway.response')->with('responseMessage', 'Merchant account is temporarily disabled.');
        }

        $check_assign_mid = checkAssignMid($iframe_array['mid']);

        if ($check_assign_mid == false) {
            return view('gateway.response')->with('responseMessage', 'Merchant account is temporarily disabled.');
        }
        $required_fields = json_decode($check_assign_mid->required_fields, 1);

        return view('gateway.iframe', compact('token', 'required_fields', 'iframe_array', 'userData'));
    }

    // ================================================
    /* method : store
    * @param  : 
    * @description : submit iframe
    */ // ===============================================
    public function store(Request $request, $token)
    {
        $this->validate($request, [
            'first_name' => 'required|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
            'address' => 'required|min:2|max:250',
            'country' => 'required|max:2|min:2|regex:(\b[A-Z]+\b)',
            'state' => 'required|min:2|max:250',
            'city' => 'required|min:2|max:250',
            'zip' => 'required|min:2|max:250',
            'email' => 'required|email',
            'phone_no' => 'required|min:5|max:20',
            'amount' => 'required|regex:/^\d+(\.\d{1,9})?$/',
            'currency' => 'required|max:3|min:3|regex:(\b[A-Z]+\b)',
        ]);

        if (!empty($token)) {
            $encrypt_method = "AES-256-CBC";
            $secret_key = 'dsflkIZxusugQdpMyjqTSE3sadjL5vsd';
            $secret_iv = '7sad4vdsJjas87saMLmlNi9x63MRAFLgk';

            // hash
            $key = hash('sha256', $secret_key);

            // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            // decrypt token
            $iframe_json = openssl_decrypt(base64_decode($token), $encrypt_method, $key, 0, $iv);

            if ($iframe_json == false) {
                return view('gateway.response')->with('responseMessage', 'Invalid payment link.');
            }

            $iframe_array = json_decode($iframe_json, 1);
        } else {
            abort(404);
        }

        $input = \Arr::except($request->all(), array('_token'));

        // amount and currency assign
        if (isset($iframe_array['amount']) && !empty($iframe_array['amount'])) {
            $input['amount'] = $iframe_array['amount'];
        }
        if (isset($iframe_array['currency']) && !empty($iframe_array['currency'])) {
            $input['currency'] = $iframe_array['currency'];
        }

        if (isset($iframe_array['type']) && $iframe_array['type'] == 'Card') {
            $url = env('APP_URL') . '/api/hosted/transaction';
        } elseif (isset($iframe_array['type']) && $iframe_array['type'] == 'Crypto') {
            $url = env('APP_URL') . '/api/crypto/transaction';
        } elseif (isset($iframe_array['type']) && $iframe_array['type'] == 'Bank') {
            $url = env('APP_URL') . '/api/bank/transaction';
        } else {
            return view('gateway.response')->with('responseMessage', 'Invalid payment link.');
        }

        $paramsArray = [
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'address' => isset($input['address']) ? $input['address'] : '',
            'country' => isset($input['country']) ? $input['country'] : '',
            'state' => isset($input['state']) ? $input['state'] : '',
            'city' => isset($input['city']) ? $input['city'] : '',
            'zip' => isset($input['zip']) ? $input['zip'] : '',
            'email' => $input['email'],
            'phone_no' => isset($input['phone_no']) ? $input['phone_no'] : '',
            'country_code' => isset($input['country_code']) ? $input['country_code'] : '',
            'amount' => $input['amount'],
            'currency' => $input['currency'],
            'response_url' => route('hosted-checkout-response', $token),
            'ip_address' => $this->getClientIP(),
            'token' => $token,
        ];

        $requestBody = json_encode($paramsArray);
        $headers = [
            'Authorization: Bearer '.$input['api_key'],
            'Content-Type: application/json',
            'Accept: application/json',
        ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($curl, CURLOPT_TIMEOUT, 90);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($response, true);

        $input['response_url'] = route('hosted-checkout-response', $token);

        if (isset($responseData['responseCode']) && $responseData['responseCode'] == '7') {
            return redirect($responseData['3dsUrl']);
        } elseif (isset($responseData['responseCode']) && $responseData['responseCode'] == '1') {
            $input['status'] = $responseData['responseCode'];
            $input['reason'] = $responseData['responseMessage'];
            $store_transaction_link = $this->getRedirectLink($input);

            return redirect($store_transaction_link);
        } elseif (isset($responseData['responseCode']) && $responseData['responseCode'] == '2') {
            $input['status'] = $responseData['responseCode'];
            $input['reason'] = $responseData['responseMessage'];

            $store_transaction_link = $this->getRedirectLink($input);

            return redirect($store_transaction_link);
        } elseif (isset($responseData['responseCode']) && $responseData['responseCode'] == '0') {
            $input['status'] = $responseData['responseCode'];
            $input['reason'] = $responseData['responseMessage'];

            $store_transaction_link = $this->getRedirectLink($input);

            return redirect($store_transaction_link);
        } elseif (isset($responseData['responseCode']) && in_array($responseData['responseCode'], ['5', '6'])) {
            $input['status'] = $responseData['responseCode'];
            $input['reason'] = $responseData['responseMessage'];

            $store_transaction_link = $this->getRedirectLink($input);

            return redirect($store_transaction_link);
        } else {
            \Log::info(['iframe_response'=>$responseData]);
            return view('gateway.response')->with('responseMessage', 'Something went wrong, please try again later.');
        }
    }

    // ================================================
    /* method : hostedCheckoutResponse
    * @param  : 
    * @description : hosted and iframe response page
    */ // ===============================================
    public function hostedCheckoutResponse(Request $request, $token)
    {
        $response = $request->all();

        return view('gateway.iframecheckoutresponse', compact('response', 'token'));
    }

    // ================================================
    /* method : checkoutCancel
    * @param  : 
    * @description : cancel transaction
    */ // ===============================================
    public function checkoutCancel(Request $request, $id)
    {
        $session_data = TransactionHostedSession::where('transaction_id', $id)
            ->first();

        if (empty($session_data)) {
            abort(404);
        }

        $input = json_decode($session_data['request_data'], 1);
        unset($input["api_key"]);

        $session_data->is_completed = '1';
        $session_data->save();

        $input['status'] = '0';
        $input["reason"] = "Transaction canceled by client.";

        $count = \DB::table("transactions")->where("session_id", $id)->count();
        if ($count == 0) {
            $transaction_response = $this->Transaction->storeData($input);
        }

        if (empty($input['response_url'])) {
            $input['response_url'] = route('hosted-checkout-response', 'test');
        }

        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    // ================================================
    /* method : testRequest
    * @param  : 
    * @description : test iframe call and print hosted response
    */ // ===============================================
    public function testRequest(Request $request)
    {
        $url = 'https://portal.finvert.io/api/hosted/transaction';

        $api_key = '44|uyoUyNaEOgUdz8XoYxDGx8Ws8FScUBCkQCZ6Ee8K';

        $paramsArray = [
            'api_key' => $api_key,
            'first_name' => 'testing',
            'last_name' => 'testing',
            'address' => 'testing',
            'country' => 'US',
            'state' => 'state',
            'city' => 'city',
            'zip' => '123456',
            'email' => 'email@gmail.com',
            'phone_no' => '91989898998',
            'amount' => '10',
            'currency' => 'USD',
            'response_url' => route('hosted-checkout-response'),
            'ip_address' => $this->getClientIP(),
        ];

        $requestBody = json_encode($paramsArray);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($curl, CURLOPT_TIMEOUT, 90);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
            )
        );

        $response = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($response, true);
    }

    // ================================================
    /* method : getClientIP
    * @param  : 
    * @description : return client ip address
    */ // ===============================================
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
}
