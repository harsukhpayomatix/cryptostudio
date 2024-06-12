<?php
namespace App\Http\Controllers\Repo\PaymentGateway;

use App\Http\Controllers\Controller;
use DB;
use App\Traits\StoreTransaction;
use Illuminate\Http\Request;
use Log;
use Http;

class PayAgency extends Controller
{
    use StoreTransaction;

    // const BASE_URL = "https://api.pay.agency/v1/test/transaction"; // TEST
    const BASE_URL = "https://api.pay.agency/v1/live/transaction"; // LIVE

    // const STATUS_URL = "https://api.pay.agency/v1/test/get/transaction"; // TEST
    const STATUS_URL = "https://api.pay.agency/v1/live/get/transaction"; // LIVE

    public function checkout($input, $mid)
    {

        $input['converted_amount'] = number_format((float) $input['converted_amount'], 2, '.', '');
        $payload = [
            "first_name" => $input["first_name"],
            "last_name" => $input["last_name"],
            "email" => $input["email"],
            "currency" => $input["converted_currency"],
            "amount" => $input["converted_amount"],
            "ip_address" => $input["ip_address"],
            "order_id" => $input["session_id"],
            "phone_number" => $input["phone_no"],
            "zip" => $input["zip"],
            "address" => $input["address"],
            "city" => $input["city"],
            "state" => $input["state"],
            "country" => $input["country"],
            "card_number" => $input["card_no"],
            "card_expiry_month" => $input["ccExpiryMonth"],
            "card_expiry_year" => $input["ccExpiryYear"],
            "card_cvv" => $input["cvvNumber"],
            // "webhook_url" => "https://webhook.site/e74b851b-7344-4a38-96ab-ed96e8dc9fcc",
            "webhook_url" => route('payagency.webhook', [$input["session_id"]]),
            "response_url" => route('payagency.return', [$input["session_id"]])
            // "response_url" => "https://webhook.site/e74b851b-7344-4a38-96ab-ed96e8dc9fcc"
        ];

        $response = Http::withHeaders(["Content-Type" => "application/json", "Accept" => "application/json", "Authorization" => "Bearer " . $mid->secret_key])->post(self::BASE_URL, $payload)->json();

        $payload["card_number"] = cardMasking($payload["card_number"]);
        $payload["card_cvv"] = "XXX";
        $this->storeMidPayload($input["session_id"], json_encode($payload));

        $input["gateway_id"] = $response["transaction"]["transaction_id"] ?? "1";
        $this->updateGatewayResponseData($input, $response);

        if ($response == null || empty($response)) {
            return [
                "status" => "0",
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        }

        if (isset($response["transaction"]["result"]["status"]) && $response["transaction"]["result"]["status"] == "success") {
            return [
                "status" => '1',
                "reason" => "Transaction processed successfully"
            ];
        } elseif (isset($response["status"]) && $response["status"] == 300 && isset($response["auth_url"])) {
            return [
                'status' => '7',
                'reason' => '3DS link generated successful, please redirect.',
                'redirect_3ds_url' => $response["auth_url"]
            ];
        } elseif (isset($response["status"]) && $response["status"] == 303 && $response["transaction"]["result"]["status"] == "pending") {
            return [
                'status' => '2',
                'reason' => 'Transaction is pending state.please wait for sometime',
            ];
        } elseif (isset($response["status"]) && $response["status"] == 400 && $response["transaction"]["result"]["status"] == "failed") {
            return [
                'status' => '0',
                'reason' => $response["transaction"]["result"]["message"] ?? 'Transaction could not processed!',
            ];
        } elseif (isset($response["status"]) && $response["status"] == 403) {
            return [
                'status' => '5',
                'reason' => $response["message"] ?? $response["transaction"]["result"]["message"] ?? 'Transaction got blocked!',
            ];
        } elseif (isset($response["status"]) && $response["status"] == 401) {
            return [
                'status' => '5',
                'reason' => $response["message"] ?? $response["transaction"]["result"]["message"] ?? 'Transaction got blocked!',
            ];
        } else {
            return [
                "status" => "0",
                "message" => $response["message"] ?? "Transaction could not processed!"
            ];
        }
    }

    public function redirect(Request $request, $id)
    {
        $transaction = DB::table("transaction_session")
            ->select("request_data", "payment_gateway_id")
            ->where("transaction_id", $id)
            ->first();

        if ($transaction == null) {
            abort(404);
        }
        $input = json_decode($transaction->request_data, true);
        $mid = checkAssignMID($transaction->payment_gateway_id);
        $statusRes = $this->statusApi($mid, $id);

        if ($statusRes == null) {
            $input["status"] = "2";
            $input["reason"] = "Transaction is in pending state.please wait for sometime!";
        } else {
            $input = $this->getInputStatus($statusRes, $input);
        }

        $this->updateGatewayResponseData($input, $statusRes);
        $this->storeTransaction($input);
        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }


    public function webhook(Request $request, $id)
    {
        $response = $request->all();
        $transaction = DB::table("transaction_session")
            ->select("request_data", "payment_gateway_id")
            ->where("transaction_id", $id)
            ->first();

        if ($transaction == null) {
            exit();
        }

        http_response_code(200);

        $input = json_decode($transaction->request_data, true);
        $mid = checkAssignMID($transaction->payment_gateway_id);
        $statusRes = $this->statusApi($mid, $id);

        if ($statusRes == null) {
            $input["status"] = "2";
            $input["reason"] = "Transaction is in pending state.please wait for sometime!";
        } else {
            $input = $this->getInputStatus($statusRes, $input);
        }

        $this->storeMidWebhook($id, json_encode($response));
        $this->storeTransaction($input);
        exit();
    }

    public function getInputStatus($response, $input)
    {
        if (isset($response["status"]) && $response["status"] == 200) {
            if (isset($response["transaction"]["result"]["status"]) && $response["transaction"]["result"]["status"] == "success") {
                $input["status"] = "1";
                $input["reason"] = "Transaction processed successfully";
            } else if (isset($response["transaction"]["result"]["status"]) && $response["transaction"]["result"]["status"] == "pending") {
                $input["status"] = "2";
                $input["reason"] = "Transaction is pending state.please wait for sometime.";
            } else if (isset($response["transaction"]["result"]["status"]) && $response["transaction"]["result"]["status"] == "failed") {
                $input["status"] = "0";
                $input["reason"] = $response["transaction"]["result"]["message"] ?? 'Transaction could not processed!';
            } else if (isset($response["transaction"]["result"]["status"]) && $response["transaction"]["result"]["status"] == "blocked") {
                $input["status"] = "5";
                $input["reason"] = $response["transaction"]["result"]["message"] ?? 'Transaction could not processed!';
            }
        } else {
            $input["status"] = "0";
            $input["reason"] = $response["message"] ?? 'Transaction could not processed!';
        }

        return $input;
    }

    public function statusApi($mid, $id)
    {
        $response = Http::withHeaders(["Content-Type" => "application/json", "Accept" => "application/json", "Authorization" => "Bearer " . $mid->secret_key])->post(self::STATUS_URL, ["order_id" => $id])->json();

        if ($response == null || empty($response)) {
            return null;
        }
        if (isset($response["status"]) && $response["status"] == 404) {
            return null;
        }
        return $response;
    }
}
