<?php
namespace App\Http\Controllers\Repo\PaymentGateway;


use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWKSet;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker;
use Exception;

class CryptoXamax extends Controller
{
    use StoreTransaction;

    const BASE_URL = "https://api.sandbox.xamax.io/v1/"; // SANDBOX
    // const BASE_URL = "https://api.xamax.io/v1/"; // Production
    const TOKEN_URL = "https://api.sandbox.xamax.io/.well-known/jwks.json";
    const XAMAX_EMAIL = "techadmin@finvert.io";



    public function checkout($input, $midDetails)
    {
        // uncommnet when currency convert api add
        // if(!isset($input['currency_type'])){
        //     return [
        //         'status' => '7',
        //         'reason' => '3DS link generated successful, please redirect.',
        //         'redirect_3ds_url' => route('api.v2.cryptoCurrency', [$input["order_id"]])

        //     ];
        // }
        
        $BtcToSatoshi = 100000000;
        $input['converted_amount'] = number_format((float) $input['converted_amount'], 2, '.', '');
         

        // $converted_currency = @$input['converted_currency'];
        // $selected_crypto_currency = $input['currency_type'];
        $btcAmount = $this->getUSDToBTC($input['converted_amount']);
        $input["gateway_id"] = $this->generateRandomNumber();
         
        $accessToken = $this->generateAccessToken($midDetails->api_key);   
       
        
        if ($btcAmount == null || $accessToken == null) {
            return [
                "status" => "0",
                "reason" => "There was some issue in bank API.please try again."
            ];
        }

        $amount = (int) ceil($btcAmount * $BtcToSatoshi);// need to chnage in live mode
       
        $payload = [
            "txId"                       =>  $input["gateway_id"],
            "code"                       =>  ["btc"],//["usdt"],// need to change in live mode
            "amount"                     =>  strval($amount),
            "urlRedirectSuccess"         =>  route('xamax.success', $input["session_id"]),//'http://localhost:8000/xamax/success/'.$input["session_id"],//
            "urlRedirectFail"            =>  route('xamax.failure', $input["session_id"]), //'http://localhost:8000/xamax/failure/'.$input["session_id"],
        ];
       \Log::info(['payload'=> $payload]);
        // $url = self::BASE_URL . 'transaction/invoice';
        $url = self::BASE_URL . 'payment-link';
        // Create invoice
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->post($url,$payload)->json();
        \Log::info(["data" => $response]);
        $this->storeMidPayload($input["session_id"], json_encode($payload));
        $this->updateGatewayResponseData($input, $response);

        if ($response == null || empty($response)) {
            return [
                "status" => "0",
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        } else if (!isset($response["href"])) {

            return [
                "status" => "0",
                'reason' => $response["message"] ?? "Transaction could not processed.",
            ];
        } else if (isset($response["href"]) && isset($response["title"])) {
            return [
                'status' => '7',
                'reason' => '3DS link generated successful, please redirect.',
                'redirect_3ds_url' => $response['href'],//route('xamax.show.wallet', [$input["session_id"]])

            ];
        } else {
            return [
                "status" => "0",
                "reason" => "Transaction could not processed."
            ];
        }


    }

    // * Display wallet
    public function showWallet(Request $request, $id)
    {
        $transaction = DB::table("transaction_session")->select("id", "response_data", "request_data")->where("transaction_id", $id)->first();
        if ($transaction == null) {
            abort(404, "Url is not correct");
        }

        $response = json_decode($transaction->response_data, true);
        $input = json_decode($transaction->request_data, true);

        $input["status"] = "2";
        $input["reason"] = "Transaction is under process.please wait for sometime.";

        $this->storeTransaction($input);

        return view("gateway.xamax.wallet", compact("response", "id"));

    }

    // * Redirect user to their site
    public function userRedirect($id)
    {
        $transaction = DB::table("transaction_session")->select("id", "request_data")->where("transaction_id", $id)->first();
        if ($transaction == null) {
            abort(404, "Url is not correct");
        }
        $input = json_decode($transaction->request_data, true);
        $input["status"] = "2";
        $input["reason"] = "Transaction is under process.please wait for sometime.";
        $store_transaction_link = $this->getRedirectLink($input);
        \Log::info(['trnasaction_link' =>   $store_transaction_link]);
        return redirect($store_transaction_link);
    }

    public function callback(Request $request)
    {
        $header = $request->header('Authorization');
        $bearerToken = null;
     
        if ($header) {
            $bearerToken = explode(' ', $header)[1];
        } else {
            throw new Exception("Bearer token not found");
        }
        
        $jwksToken = Http::withHeaders([
            // 'Authorization' => 'Bearer ' . $access_token
        ])->get(self::TOKEN_URL)->body();

        $jwk = JWKSet::createFromJson($jwksToken);
        $requestBody = $request->getContent();
        // $requestBody1 = "'".json_encode($request->all())."'";

        $requestAuthorizationHeader = $bearerToken;
     
        $serializerManager = new CompactSerializer();
        $jws = $serializerManager->unserialize($requestAuthorizationHeader);
        // initialize claim checker
        $claimCheckerManager = new ClaimCheckerManager(
            [
                new Checker\IssuedAtChecker(),
                new Checker\NotBeforeChecker(),
                // new Checker\ExpirationTimeChecker(), // in current example this checker always fall, because token already expired
                new Checker\IssuerChecker(["xamax.io"]),
                new Checker\AudienceChecker(self::XAMAX_EMAIL), // merchant email
            ]
        );

        $claims = json_decode($jws->getPayload(), true);
        // check JWT claims
        $claimCheckerManager->check($claims);

        // check body hash
        if ($claims['body_hash'] != hash($claims['body_hash_method'], $requestBody)) {
            throw new Exception("incorrect body checksum");
        }

        // checking available key
        $ok = false;
        $headers = $jws->getSignature(0)->getProtectedHeader();
        foreach ($jwk->all() as $k) {
            if ($k->get('kid') == $headers['kid']) {
                $ok = true;
                break;
            }
        }

        if(!$ok){
            throw new Exception("invalid signature1");
        }
        // check jwt signature
        $jwsVerifier = new JWSVerifier(new AlgorithmManager([new RS256()]));
        $isVerified = $jwsVerifier->verifyWithKeySet($jws, $jwk, 0);
        if (!$isVerified) {
            throw new Exception("invalid signature2");
        }

       if($isVerified){
        $response = $request->all();
        $transaction = DB::table("transaction_session")->select("id", "request_data", "gateway_id", "transaction_id")->where("gateway_id", $response["txId"])->first();
        if ($transaction == null) {
            exit();
        }

        // * Store MId Payload
        $this->storeMidWebhook($transaction->transaction_id, json_encode($response));
        $input = json_decode($transaction->request_data, true);

        if (isset($response["status"]) && $response["status"] == "transaction_status_pending") {
            $input["status"] = "2";
            $input["reason"] = "Transaction is under process.Please wait for sometime.";
        } else if (isset($response["status"]) && $response["status"] == "transaction_status_expired") {
            $input["status"] = "0";
            $input["reason"] = "Transaction got expired.";
        } else if (isset($response["status"]) && $response["status"] == "transaction_status_confirmed") {
            $input["status"] = "1";
            $input["reason"] = "Transaction processed successfully.";
        } else if (isset($response["status"]) && $response["status"] == "transaction_status_failed") {
            $input["status"] = "0";
            $input["reason"] = "Transaction could not processed.";
        } else if (isset($response["status"]) && $response["status"] == "transaction_status_canceled") {
            $input["status"] = "0";
            $input["reason"] = "User cancelled the transaction process.";
        }

        Log::info(['final_data' => $input]);
        $this->storeTransaction($input);

        exit();
       }
    }

    public function success(Request $request, $session_id)
    {
        \Log::info(['success_response' => $request->all()]);
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

    public function failure(Request $request, $session_id)
    {
        \Log::info(['failed_response' => $request->all()]);
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
    public function checkResponse(Request $request)
    {
        if($request->transaction_id){
            $data = DB::table("transaction_session")->select('id')->where("transaction_id", $request->transaction_id)->whereNotNull('webhook_response')->first();
            if($data){
                return response()->json(['status' => true,'message' => 'payment initiated'] ,200);
            }
        }
        return response()->json(['status' => false,'message' => 'waiting for payment response'] ,200); 
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
    public function generateAccessToken($token)
    {
        $response = Http::asForm()
            ->post(self::BASE_URL . "auth/refresh", [
                'refresh_token' => $token,
            ])->json();

        // Log::info(["access-token-res" => $response]);
        if (isset($response["access_token"]) && $response["access_token"] != "") {
            return $response["access_token"];
        }

        return null;

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

