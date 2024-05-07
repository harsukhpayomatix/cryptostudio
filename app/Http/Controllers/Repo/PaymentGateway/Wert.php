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

    const origin = 'https://sandbox.wert.io';


    public function __construct()
    {
        $this->transaction = new Transaction;
    }

    public function checkout($input, $midDetails)
    {
        $partnerId = $midDetails->partner_id;
        $origin = self::origin;
        $clickId = Str::uuid()->toString();
        $currency = $input["currency"] ?: '';
        $currency_amount = $input["amount"] ?: '';   //input from user currency_amount $1.50 on sandbox and $5 on prod
        $full_name = $input["first_name"] . " " . $input["last_name"] ?: '';
        $email = $input["email"] ?: '';
        $country_of_residence = $input["country"] ?: '';
        $phone = "+".$input["country_code"].$input["phone_no"] ?: '';
        $payload = [
            "partnerId" => $partnerId,
            "origin" => $origin,
            "clickId" => $clickId,
            "currency" => $currency,
            "full_name" => $full_name,
            "email" => $email,
            "country_of_residence" => $country_of_residence,
            "currency_amount"=>$currency_amount,
            "phone" => $phone,
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
