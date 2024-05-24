<?php
namespace App\Http\Controllers\Repo\PaymentGateway;


use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;


class SasaPay extends Controller
{
    use StoreTransaction;

    // const BASE_URL = "https://sandbox.sasapay.app/api/v1/"; // SANDBOX
    const BASE_URL = "https://api.sasapay.app/api/v1/"; // Production
   
    public function checkout($input, $midDetails)
    {
        $input['amount'] = number_format((float) $input['amount'], 2, '.', '');
        // $btcAmount = $this->getUSDToBTC($input['converted_amount']);

        $input["gateway_id"] = $this->generateRandomNumber();
        $accessToken = $this->generateAccessToken($midDetails->client_id, $midDetails->client_secret);
        if ($input['amount'] == null || $accessToken == null) {
            return [
                "status" => "0",
                "reason" => "There was some issue in bank API.please try again."
            ];
        }

        $payload = [
            'MerchantCode' => $midDetails->merchant_code,
            'Amount' => $input['amount'],
            'Reference' => $input["session_id"],//@$input['reference'],
            'Description' => 'Payment',//@$input['description'],
            'Currency' => $input['converted_currency'],//??'KES',
            'PayerEmail' => $input['email'],
            'CallbackUrl' => route('sasapay.callback', $input["session_id"]),
            'SuccessUrl' => route('sasapay.success', $input["session_id"]),
            'FailureUrl' => route('sasapay.failure', $input["session_id"]),
            'SasaPayWalletEnabled' => false,
            'MpesaEnabled' => false,
            'CardEnabled' => true,
            'AirtelEnabled' => false,
        ];
        $response = $this->initiateCardPayment($payload, $accessToken);
        $this->storeMidPayload($input["session_id"], json_encode($payload));
        $this->updateGatewayResponseData($input, $response);

        if ($response == null || empty($response)) {
            return [
                "status" => "0",
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        } else if (!$response->status || !isset($response->CheckoutUrl)) {
            return [
                "status" => "0",
                'reason' => $response->detail ?? "Transaction could not processed.",
            ];
        } else if ($response->status && isset($response->CheckoutUrl)) {
            return [
                'status' => '7',
                'reason' => '3DS link generated successful, please redirect.',
                'redirect_3ds_url' => $response->CheckoutUrl,//route('xamax.show.wallet', [$input["session_id"]])

            ];
        } else {
            return [
                "status" => "0",
                "reason" => "Transaction could not processed."
            ];
        }


    }

    public function success($session_id)
    {
        $transaction = DB::table("transaction_session")->select("id", "request_data", "webhook_response", "gateway_id", "transaction_id")->where("transaction_id", $session_id)->first();
        if ($transaction == null) {
            abort(404);
        }
        $input = json_decode($transaction->request_data, true);
        $input['status'] = '1';
        $input['reason'] = 'Your transaction was proccessed successfully.';

        // redirect back to $response_url
        $transaction_response = $this->storeTransaction($input);

        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);


    }

    public function failure($session_id)
    {
        $transaction = DB::table("transaction_session")->select("id", "request_data", "webhook_response", "gateway_id", "transaction_id")->where("transaction_id", $session_id)->first();
        if ($transaction == null) {
            abort(404);
        }
        $input = json_decode($transaction->request_data, true);
        $input['status'] = '0';
        $input['reason'] = 'Your transaction could not processed.';

        // redirect back to $response_url
        $transaction_response = $this->storeTransaction($input);

        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    public function callback($session_id, Request $request)
    {

        $response = $request->all();
        if (!isset($response['ResultCode']) || !isset($gateway_id)) {
            exit();
        }
        $transaction = DB::table("transaction_session")->select("id", "request_data", "gateway_id", "transaction_id")->where("transaction_id", $session_id)->first();
        if ($transaction == null) {
            exit();
        }

        // * Store MId Payload
        $this->storeMidWebhook($transaction->transaction_id, json_encode($response));
        $input = json_decode($transaction->request_data, true);

        if (isset($response["ResultCode"]) && $response["ResultCode"] == "0") {
            $input["status"] = "1";
            $input["reason"] = "Transaction processed successfully.";

        } else {
            $input["status"] = "0";
            $input["reason"] = "Transaction could not processed.";
        }
        Log::info(['final_data' => $input]);
        $this->storeTransaction($input);

        exit();
    }


    public function getUSDToBTC($amount)
    {
        $key = config("custom.currency_converter_access_key");
        $response = Http::get('https://apilayer.net/api/live?access_key=' . $key . "&currencies=BTC&source=USD")->json();

        if (isset($response["quotes"]) && isset($response["quotes"]["USDBTC"])) {
            return $response["quotes"]["USDBTC"] * $amount;
        }
        return null;
    }

    // * Generate Access Token
    public function generateAccessToken($client_id, $client_secret)
    {

        $response = Http::withBasicAuth($client_id, $client_secret)
            ->get(self::BASE_URL . 'auth/token')->json();
        if (isset($response["access_token"]) && $response["access_token"] != "") {
            return $response["access_token"];
        }

        return null;
    }



    public function initiateCardPayment($payload, $accessToken)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::BASE_URL . 'payments/card-payments/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ),
        )
        );

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }


    // * Generate random number
    public function generateRandomNumber()
    {
        $min = 1000000000; // Minimum 10-digit number
        $max = 9999999999; // Maximum 10-digit number

        $randomNumber = mt_rand($min, $max);

        return $randomNumber;
    }

}

