<?php

namespace App\Http\Controllers\Repo\PaymentGateway;


use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;

class Bitpace extends Controller
{

    use StoreTransaction;

    // const BASE_URL = "https://api-sandbox.bitpace.com/api/v1/";
    const BASE_URL = "https://api.bitpace.com/api/v1/"; // Production URL



    public function checkout($input, $midDetails)
    {
        // * Get token 
        $token = $this->generateToken($midDetails);
        // Log::info(["the-token-is" => $token]);

        if ($token == null) {
            return [
                'status' => '0',
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        }

        $input['converted_amount'] = number_format((float) $input['converted_amount'], 2, '.', '');

        $payload = [
            "order_amount" => $input["converted_amount"],
            "currency" => $input["currency"],
            "merchant_name" => $input["first_name"] . " " . $input["last_name"],
            "description" => "Crypto Transaction " . $input["order_id"],
            "ip_address" => $input["ip_address"],
            "customer" => [
                "reference_id" => $input["session_id"],
                "first_name" => $input["first_name"],
                "last_name" => $input["last_name"],
                "email" => $input["email"],
            ],
            "return_url" => route("bitpace.callback", [$input["session_id"]]),
            "failure_url" => route("bitpace.error.callback", [$input["session_id"]]),
        ];

        $this->storeMidPayload($input["session_id"], json_encode($payload));

        $response = Http::withHeaders(["Authorization" => $token])->post(self::BASE_URL . "fixed-deposit/url", $payload)->json();

        // Log::info(["bitpace-response" => $response]);

        if ($response == null || empty($response)) {
            return [
                'status' => '0',
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        }

        // * Update the gateway Id
        $input["gateway_id"] = $response["data"]["order_id"] ?? "1";
        $this->updateGatewayResponseData($input, $response);


        if (isset($response["status"]) && $response["status"] == "DECLINED") {
            return [
                "status" => "0",
                "reason" => $response["message"] ?? "Transaction could not processed."
            ];
        } else if (isset($response["code"]) && $response["code"] == "00") {
            return [
                'status' => '7',
                'reason' => '3DS link generated successful, please redirect.',
                'redirect_3ds_url' => $response["data"]["payment_url"]
            ];
        } else {
            return [
                "status" => "0",
                "reason" => "Transaction could not processed."
            ];
        }


    }

    // * Generate the Auth Token
    public function generateToken($mid)
    {
        $url = self::BASE_URL . "auth/token";
        $response = Http::post($url, ["merchant_code" => $mid->merchant_code, "password" => $mid->password])->json();
        // Log::info(["bitpace-auth-token-res" => $response]);

        if ($response != null && isset($response["code"]) == "00" && isset($response["data"]["token"])) {
            return $response["data"]["token"];
        }
        return null;

    }

    // * callback method
    public function callback(Request $request, $id)
    {
        $transaction = DB::table("transaction_session")->select("id", "request_data")->where("transaction_id", $id)->first();

        if ($transaction == null || empty($transaction)) {
            abort(404, "URL is not correct");
        }

        $input = json_decode($transaction->request_data, true);

        $input["status"] = "1";
        $input["reason"] = "Transaction processed successfully!.";

        $this->storeTransaction($input);
        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    // * Error callback method
    public function errorCallback(Request $request, $id)
    {
        $transaction = DB::table("transaction_session")->select("id", "request_data")->where("transaction_id", $id)->first();

        if ($transaction == null || empty($transaction)) {
            abort(404, "URL is not correct");
        }

        $input = json_decode($transaction->request_data, true);

        $input["status"] = "0";
        $input["reason"] = "User cancel the transaction process.";

        $this->storeTransaction($input);
        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    // * Bitpace webhook
    public function webhook(Request $request)
    {
        $response = $request->all();
        Log::info(["the-bitpace-webhook" => $response]);

        $transaction = DB::table("transaction_session")->select("request_data")->where("transaction_id", $response["customer_reference_id"])->first();
        if ($transaction == null) {
            exit();
        }

        $this->storeMidWebhook($response["customer_reference_id"], json_encode($response));

        $input = json_decode($transaction->request_data, true);
        if (isset($response["status"]) && $response["status"] == "COMPLETED") {
            $input["status"] = "1";
            $input["reason"] = "Transaction processed successfully!";
        } else if (isset($response["status"]) && $response["status"] == "EXPIRED") {
            $input["status"] = "0";
            $input["reason"] = "Transaction could not processed!";
        }
        if (isset($input["status"])) {
            $this->storeTransaction($input);
        }

        exit();

    }
}