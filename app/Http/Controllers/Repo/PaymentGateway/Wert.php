<?php

namespace App\Http\Controllers\Repo\PaymentGateway;


use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Transaction;


class Wert extends Controller
{
    use StoreTransaction;

    /****
     *
     * Configuration Properties
     *
     ****/
    const X_API_KEY = "wert-sbox-2477e44ef9d95339ec73f140f9e3c4cc3ed29fd8";
    const X_Partner_ID = "01HT7WK5QF6FZFJEXEH9PHE4MG";
    const origin = 'https://sandbox.wert.io';
    const X_PRIVATE_KEY = '0x57466afb5491ee372b3b30d82ef7e7a0583c9e36aef0f02435bd164fe172b1d3';

    public function __construct()
    {
        $this->transaction = new Transaction;
    }

    public function checkout($input, $midDetails)
    {
        $partnerId = self::X_Partner_ID;
        $origin = self::origin;
        $clickId = Str::uuid()->toString();
        $currency = $input["currency"] ?: '';
        //$currency_amount = '';    //input from user currency_amount $1.50 on sandbox and $5 on prod
        $full_name = $input["first_name"] . " " . $input["last_name"] ?: '';
        $email = $input["email"] ?: '';
        $country_of_residence = $input["country"] ?: '';
        // $phone = "+919319710012";
        $payload = [
            "partnerId" => $partnerId,
            "origin" => $origin,
            "clickId" => $clickId,
            "currency" => $currency,
            "full_name" => $full_name,
            "email" => $email,
            "country_of_residence" => $country_of_residence,
            // "phone" => $phone,
            "webhook_url" => route('Wert.webhook'),
        ];
        $this->storeMidPayload($input["session_id"], json_encode($payload));
        return [
            'status' => '7',
            'reason' => '3DS link generated successful, please redirect.',
            'redirect_3ds_url' => route('Wert.showWallet', [$input["session_id"]])
        ];
    }

    public function callback($session_id, Request $request)
    {
        $transaction_session = DB::table('transaction_session')
            ->where('transaction_id', $session_id)
            ->first();
        if ($transaction_session == null) {
            return abort(404);
        }
        $input = json_decode($transaction_session->request_data, true);
        Log::info(['wert-webhook' => $request->all()]);
        $transactions = DB::table('transactions')
            ->where('order_id', $transaction_session->order_id)
            ->first();

        $input['status'] = $transactions->status;
        $input['reason'] = $transactions->reason;
        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    public function index(Request $request, $input = '')
    {
        $partnerId = self::X_Partner_ID;
        $origin = self::origin;
        $clickId = Str::uuid()->toString();
        $currency = "USD";          //input from user currency
        //$currency = $input["currency"] ?: '';
        $currency_amount = '';    //input from user currency_amount $1.50 on sandbox and $5 on prod
        //$currency_amount = $input["amount"] ?: '';
        // $full_name=$input["first_name"]." ".$input["last_name"] ?: '';
        $full_name = "mohan san";
        // $email=$input["email"] ?: '';
        $email = "itmohan2016@gmail.com";
        $country_of_residence = "BT";
        // $country_of_residence=$input["country"] ?: '';
        $phone = "+9759319710012";
        // $phone=$input["phone"] ?: '';
        return view('wert.wertPay', compact('partnerId', 'origin', 'clickId', 'currency', 'currency_amount', 'full_name', 'email', 'country_of_residence', 'phone'));
    }

    private function getMessageForStatus($status)
    {
        switch ($status) {
            case 'success':
                return 'The order was successful and has been sent on the blockchain.';
            case 'failed':
                return 'The order has failed and will not be sent on the blockchain.';
            case 'cancelled':
                return 'The payment for the order was processed but the order was later cancelled.';
            case 'pending':
                return 'The order is being processed but it hasn\'t been sent on the blockchain yet.';
            case 'progress':
                return 'The order is being processed but payment has not yet been completed.';
            case 'created':
                return 'The order has been created but payment has not yet been processed.';
            default:
                return 'Unknown status.';
        }
    }

    private function getStatusCodeForStatus($status)
    {
        switch ($status) {
            case 'success':
                return 200;
            case 'failed':
                return 400;
            case 'cancelled':
                return 410;
            case 'pending':
            case 'progress':
            case 'created':
                return 202;
            default:
                return 500;
        }
    }

    public function orders(Request $request)
    {
        $endpoint = 'https://partner-sandbox.wert.io/api/external/orders';
        $client = new Client();
        try {
            $response = $client->get($endpoint, [
                'headers' => [
                    'X-API-KEY' => self::X_API_KEY,
                    'Content-Type' => 'application/json',
                ],
                'data' => [
                    'limit' => 20,
                    'offset' => 10,
                    'order_by' => 'asc',
                ]
            ]);
            $ordersResponse = json_decode($response->getBody(), true);
            $orders = $ordersResponse['data'];
            $totalOrders = $ordersResponse['total'];
            $result = [];
            foreach ($orders as $order) {
                $status = $order['status'];
                $message = $this->getMessageForStatus($status);
                $statusCode = $this->getStatusCodeForStatus($status);
                $result[] = [
                    'order_id' => $order['order_id'],
                    'status' => $status,
                    'message' => $message,
                    'status_code' => $statusCode,

                ];
            }
            return response()->json([
                'success' => true,
                'total_orders' => $totalOrders,
                'orders' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function users(Request $request)
    {
        $endpoint = 'https://partner-sandbox.wert.io/api/external/users';
        $client = new Client();
        try {
            $response = $client->get($endpoint, [
                'headers' => [
                    'X-API-KEY' => self::X_API_KEY, // Replace with your actual API key
                    'Content-Type' => 'application/json',
                ],
                'data' => [
                    'order_by' => 'asc',
                ]
            ]);
            $users = json_decode($response->getBody(), true);
            var_dump($users);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function converter(Request $request)
    {
        $endpoint = 'https://sandbox.wert.io/api/v3/partners/convert';
        $client = new Client();
        $from = $request->input('from');
        $network = $request->input('network');
        $to = $request->input('to');
        $amount = $request->input('amount');

        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Partner-ID' => self::X_Partner_ID,
                ],
                'json' => [
                    "from" => $from,
                    "network" => $network,
                    "to" => $to,
                    "amount" => floatval($amount)
                ]
            ]);
            $conversionResult = json_decode($response->getBody(), true);
            var_dump($conversionResult);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function handleWebhook(Request $request)
    {
        $response = $request->all();
        Log::info(["wert-webhook" => $response]);
        $type = $response['type'];
        $clickId = $response['click_id'];
        if (isset($clickId) && !empty($clickId)) {
            $transactionId = DB::table('transaction_session')
                ->whereJsonContains('mid_payload->clickId', $clickId)
                ->value('transaction_id');
            if ($transactionId) {
                // If a transaction_id is found, retrieve the corresponding record
                $transaction = DB::table('transaction_session')
                    ->select('id', 'request_data')
                    ->where('transaction_id', $transactionId)
                    ->first();
                $this->storeMidWebhook($transactionId, json_encode($response));
                if ($transaction == null) {
                    exit();
                }
                $input = json_decode($transaction->request_data, true);
                if (isset($type) && $type === "order_complete") {
                    $input["status"] = "1";
                    $input["reason"] = "Transaction processed successfully!";
                } else if (isset($type) && $type === "order_failed") {
                    $input["status"] = "0";
                    $input["reason"] = "Your Transaction could not processed!";
                } else if (isset($type) && $type === "order_canceled") {
                    $input["status"] = "3";
                    $input["reason"] = "Your Transaction has cancelled!";
                }
                else
                {
                    $input["status"] = "2";
                    $input["reason"] = "Your Transaction could be in pending state!";
                }
                $this->storeTransaction($input);
                $order = $response['order'];
                if (isset($order) && !empty($order)) {
                    $transactionIdWert = $order['transaction_id'];
                    if (isset($transactionIdWert) && !empty($transactionIdWert)) {
                        $input["gateway_id"] = $transactionIdWert;
                        $this->updateGatewayResponseData($input, $response);
                    }
                }
            } else {
            }
        }

        $type = $response['type'];
        if (isset($type) && !empty($type)) {
            $clickId = $response['click_id'];
            switch ($type) {
                case 'registration':
                    // Registration
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'login':
                    // Registration
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'logout':
                    // Registration
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'card_added':
                    // Card added
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'verify_start':
                    // Verify start
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'verify_success':
                    // Verify success
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'verify_retry':
                    // Verify success
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'verify_failed':
                    // Verify success
                    Log::info("Type: $type, Click ID: $clickId");
                    break;
                case 'payment_started':
                    // Payment started
                    $order = $response['order'];
                    $orderId = $order['id'];
                    $base = $order['base'];
                    $baseAmount = $order['base_amount'];
                    $quote = $order['quote'];
                    $quoteAmount = $order['quote_amount'];
                    $address = $order['address'];
                    Log::info("Type: $type, Click ID: $clickId, Order ID: $orderId, Base: $base, Base Amount: $baseAmount, Quote: $quote, Quote Amount: $quoteAmount, Address: $address");
                    break;
                case 'transfer_started':
                    // Transfer started
                    $order = $response['order'];
                    $orderId = $order['id'];
                    $base = $order['base'];
                    $baseAmount = $order['base_amount'];
                    $quote = $order['quote'];
                    $quoteAmount = $order['quote_amount'];
                    $address = $order['address'];
                    $transactionId = $order['transaction_id'];
                    Log::info("Type: $type, Click ID: $clickId, Order ID: $orderId, Base: $base, Base Amount: $baseAmount, Quote: $quote, Quote Amount: $quoteAmount, Address: $address, Transaction ID: $transactionId");
                    break;
                case 'order_complete':
                    // Order complete
                    $order = $response['order'];
                    $orderId = $order['id'];
                    $base = $order['base'];
                    $baseAmount = $order['base_amount'];
                    $quote = $order['quote'];
                    $quoteAmount = $order['quote_amount'];
                    $address = $order['address'];
                    $transactionId = $order['transaction_id'];
                    Log::info("Type: $type, Click ID: $clickId, Order ID: $orderId, Base: $base, Base Amount: $baseAmount, Quote: $quote, Quote Amount: $quoteAmount, Address: $address, Transaction ID: $transactionId");
                    break;
                default:
                    Log::info("Unknown type: $type, Click ID: $clickId");
                    break;
            }
        }
    }


    public function showWallet(Request $request, $id)
    {
        // Fetch transaction data based on the provided ID
        $transaction = DB::table("transaction_session")
            ->select("id", "response_data", "request_data", "mid_payload")
            ->where("transaction_id", $id)
            ->first();

        // If no transaction found, return a 404 error
        if (!$transaction) {
            abort(404, "URL is not correct");
        }

        // Decode response and request data
        $response = json_decode($transaction->response_data, true);
        $input = json_decode($transaction->request_data, true);
        $wallet_input = json_decode($transaction->mid_payload, true);

        // Update input status and reason
        $input["status"] = "2";
        $input["reason"] = "Transaction is under process. Please wait for sometime.";

        // Store the updated transaction
        $this->storeTransaction($input);

        // Pass response and ID to the view
        return view("gateway.wert.wertPay", compact("response", "id", "wallet_input"));
    }

}
