<?php

namespace App\Http\Controllers\Repo\PaymentGateway;

use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;

class StartButton extends Controller
{
    use StoreTransaction;

    // const BASE_URL = "https://api.startbutton.tech/transaction/initialize"; // production url
    const BASE_URL = "https://api.startbutton.builditdigital.co/transaction/initialize";

    public function checkout($input, $mid_details)
    {
        $input['converted_amount'] = number_format((float) $input['converted_amount'], 2, '.', '');
        $payload = [
            "email" => $input["email"],
            "amount" => $input["converted_amount"],
            "currency" => $input["converted_currency"],
            "reference" => $input["session_id"]
        ];

        $response = Http::withHeaders(["Authorization" => "Bearer " . $mid_details->api_key, "Content-type" => "application/json"])->post(self::BASE_URL, $payload)->json();

        Log::info(["payload" => $payload, "startbutton-res" => $response]);

        if ($response == null || empty($response)) {
            return [
                "status" => "0",
                "reason" => "We are facing temporary issue from the bank side. Please contact us for more detail."
            ];
        } else if (isset($response["success"]) && $response["success"] == true) {
            return [
                'status' => '7',
                'reason' => '3DS link generated successful, please redirect.',
                'redirect_3ds_url' => $response["data"]
            ];
        } else {
            return [
                "status" => "0",
                "reason" => "Transaction could not processed!"
            ];
        }
    }

    public function callback(Request $request)
    {
        $response = $request->all();
        Log::info(["startbutton-callback" => $response]);
    }

    public function webhook(Request $request)
    {
        $response = $request->all();
        Log::info(["startbutton-webhook" => $response]);
        return response()->json($response);
    }
}