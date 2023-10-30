<?php

namespace App\Http\Controllers\Repo\PaymentGateway;

use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;

class Epsilon extends Controller
{
    use StoreTransaction;

    // const BASE_URL = "https://gateway.epsilonpayments.com/api/sandbox/payment"; // Test URL
    const BASE_URL = "https://gateway.epsilonpayments.com/api/live/payment"; // Live URL
    const STATUS_API = "https://gateway.epsilonpayments.com/api/status/payment";

    public function checkout($input, $check_assign_mid)
    {
        $payload = [
            "first_name" => $input["first_name"],
            "last_name" => $input["last_name"],
            "address" => $input["address"],
            "city" => $input["city"],
            "state" => $input["state"],
            "country" => $input["country"],
            "zip" => $input["zip"],
            "ip_address" => $input['ip_address'],
            'email' => $input['email'],
            "phone_no" => $input["phone_no"],
            "amount" => $input['converted_amount'],
            "currency" => $input["converted_currency"],
            "card_no" => $input["card_no"],
            "ccExpiryMonth" => $input["ccExpiryMonth"],
            "ccExpiryYear" => $input["ccExpiryYear"],
            "cvvNumber" => $input["cvvNumber"],
            "customer_order_id" => $input["order_id"],
            "response_url" => route("epsilon.redirect", $input["session_id"]),
            "webhook_url" => route("epsilon.webhook", $input["session_id"]),
        ];

        $response = Http::withHeaders(["Authorization" => "Bearer " . $check_assign_mid->api_key, "Accept" => "application/json"])->post(self::BASE_URL, $payload)->json();

        // Log::info(["epsilon-res" => $response]);
        $input["gateway_id"] = isset($response["data"]["transaction"]["order_id"]) ? $response["data"]["transaction"]["order_id"] : "1";

        // * Store mid payload
        $payload["caed_no"] = cardMasking($payload["card_no"]);
        $payload["cvvNumber"] = "XXX";
        $this->storeMidPayload($input["session_id"], json_encode($payload));

        $this->updateGatewayResponseData($input, $response);
        if (empty($response)) {
            return [
                'status' => '0',
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        } else if ($response["responseCode"] == "0") {
            return [
                'status' => '0',
                'reason' => isset($response["responseMessage"]) ? $response["responseMessage"] : "Your transaction could not processed.",
                'order_id' => $input['order_id'],
            ];
        } else if ($response["responseCode"] == "1") {
            return [
                'status' => '1',
                'reason' => isset($response["responseMessage"]) ? $response["responseMessage"] : "Your transaction has been processed successfully.",
                'order_id' => $input['order_id'],
            ];
        } else if ($response["responseCode"] == "7") {
            return [
                'status' => '7',
                'reason' => '3DS link generated successfully, please redirect to \'redirect_3ds_url\'.',
                'redirect_3ds_url' => $response["3dsUrl"]
            ];
        } else if ($response["responseCode"] == "5") {
            return [
                'status' => '5',
                'reason' => isset($response["responseMessage"]) ? $response["responseMessage"] : "Your transaction could not processed.",
                'order_id' => $input['order_id'],
            ];
        } else {
            return [
                'status' => '0',
                'reason' => isset($response["responseMessage"]) ? $response["responseMessage"] : "Your transaction could not processed.",
                'order_id' => $input['order_id'],
            ];
        }
    }

    // * Redirect method
    public function redirect(Request $request, $id)
    {
        $payload = $request->all();
        $transaction = DB::table("transaction_session")->where('transaction_id', $id)
            ->orderBy('id', 'desc')
            ->first();
        if ($transaction == null) {
            return abort(404);
        }
        $input = json_decode($transaction->request_data, true);

        // * Call status API
        $mid = checkAssignMID($transaction->payment_gateway_id);
        $response = $this->statusAPI($mid, $payload["order_id"], $payload["customer_order_id"]);
        if ($response != null && !empty($response)) {
            $payload["responseCode"] = $response["responseCode"];
            $payload["responseMessage"] = $response["responseMessage"];
        }

        if ($payload["responseCode"] == "0") {
            $input['status'] = '0';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction could not processed.";

        } else if ($payload["responseCode"] == "1") {

            $input['status'] = '1';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction has been processed successfully.";
        } else if ($payload["responseCode"] == "2") {
            $input['status'] = '2';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction is in pending state.please check it after sometime.";
        } else if ($payload["responseCode"] == "5") {
            $input['status'] = '5';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction could not processed.";
        } else {
            $input['status'] = '0';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction could not processed.";
        }

        $this->storeTransaction($input);
        $this->updateGatewayResponseData($input, $payload);
        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    // * Webhook method
    public function webhook(Request $request, $id)
    {
        $payload = $request->all();
        $input_json = DB::table("transaction_session")->where('transaction_id', $id)
            ->orderBy('id', 'desc')
            ->first();

        if ($input_json == null) {
            return abort(404);
        }
        $input = json_decode($input_json['request_data'], true);
        if ($payload["responseCode"] == "0") {
            $input['status'] = '0';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction could not processed.";

        } else if ($payload["responseCode"] == "1") {
            $input['status'] = '1';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction has been processed successfully.";
        } else if ($payload["responseCode"] == "2") {
            $input['status'] = '2';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction is in pending state.please check it after sometime.";
        } else if ($payload["responseCode"] == "5") {
            $input['status'] = '5';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction could not processed.";
        } else {
            $input['status'] = '0';
            $input['reason'] = isset($payload["responseMessage"]) ? $payload["responseMessage"] : "Your transaction could not processed.";
        }

        $this->storeTransaction($input);
        exit();
    }

    // * Status API
    public function statusAPI($mid, $gatewayId, $orderId)
    {
        $payload = [
            "order_id" => $gatewayId,
            "customer_order_id" => $orderId
        ];
        $response = Http::withHeaders(["Authorization" => "Bearer " . $mid->api_key, "Accept" => "application/json"])->post(self::STATUS_API, $payload)->json();
        // Log::info(["epsilon-status-res" => $response]);
        return $response;
    }
}