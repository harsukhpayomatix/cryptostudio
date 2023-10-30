<?php

namespace App\Http\Controllers\Repo\PaymentGateway;


use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;

class CryptoXamax extends Controller
{
    use StoreTransaction;

    const SANDBOX_URL = "https://api.sandbox.xamax.io/fiat/v1/transaction";
    const PRODUCTION_URL = "https://api.xamax.io/fiat/v1/transaction";

    const PAYMENT_METHODS_URL = "https://api.sandbox.xamax.io/fiat/v1/payment/methods";


    public function checkout($input, $midDetails)
    {
        return [
            'status' => '7',
            'reason' => '3DS link generated successful, please redirect.',
            'redirect_3ds_url' => route('xamax.select.payment.method', [$input["session_id"]])
        ];
    }

    public function selectPaymentMethod($id)
    {

        $transaction = DB::table("transaction_session")->select("request_data")->where("transaction_id", $id)->first();

        if ($transaction == null) {
            exit(404);
        }

        $input = json_decode($transaction->request_data, true);
        $mid = checkAssignMID($input['payment_gateway_id']);
        $paymentMethods = Http::withHeaders(["Authorization" => "Bearer " . $mid->api_key])->post(self::PAYMENT_METHODS_URL, ["country" => "GBR", "currency" => "GBP"])->json();
        dd($paymentMethods);
        return $paymentMethods;

    }
}