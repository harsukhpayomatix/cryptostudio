<?php

namespace App\Http\Controllers\Repo\PaymentGateway;

use Exception;
use App\Transaction;
use App\TransactionSession;
use App\Http\Controllers\Controller;
use App\Traits\StoreTransaction;
use Illuminate\Http\Request;

class NowPayments extends Controller
{
	use StoreTransaction;

	const BASE_URL = 'https://api-sandbox.nowpayments.io';
    const PAY_CURRENCY = 'btc';
    const PROCESSING_STATUSES = ['waiting', 'confirming', 'confirmed', 'sending', 'partially_paid'];
    const ERROR_STATUSES = ['failed', 'expired', 'refunded'];

	// ================================================
	/* method : __construct
	* @param  : 
	* @description : create new instance of the class
	*/// ===============================================
	public function __construct()
	{
		$this->transaction = new Transaction;
	}

	// ================================================
	/* method : checkout
	* @param  : 
	* @description : 
	*/// ===============================================
	public function checkout($input, $check_assign_mid)
	{
        // create payment method
        $payment_url = self::BASE_URL.'/v1/payment';
        $secret_key = $check_assign_mid->access_key;

        $payment_data = [
            'price_amount' => $input['amount'],
            'price_currency' => $input['currency'],
            'pay_currency' => self::PAY_CURRENCY,
            'case' => 'success',
            // 'ipn_callback_url' => "https://40fc-2405-201-5023-4810-b335-5675-98d2-41ad.ngrok-free.app/api/nowpayments-crypto-callback/".$input['session_id'] . '/' . base64_encode($check_assign_mid->ipn_secret),
            'ipn_callback_url' => route('nowpayments-crypto-callback', [$input['session_id'], base64_encode($check_assign_mid->ipn_secret)]),
        ];
        

        $payment_payload = json_encode($payment_data);
        
        $payment_headers = [
            'Content-Type: application/json',
            'x-api-key: '.$secret_key
        ];
        
        $payment_body = $this->curlPost($payment_url, $payment_payload, $payment_headers);
        
        $payment_response = json_decode($payment_body, true);
        \Log::info($payment_response);

        $this->storeMidPayload($input['session_id'], $payment_payload);

        // updating API response
        $input['gateway_id'] = $payment_response['payment_id'] ?? 1;
        $this->updateGatewayResponseData($input, $payment_response);

        if (isset($payment_response['status']) && $payment_response['status'] == false) {
            return [
                'status' => '0',
                'reason' => $payment_response['message'],
                'order_id' => $input['order_id'],
            ];
        }
        
        if ($payment_response == null || empty($payment_response) || !$payment_response['payment_status']) {
            return [
                'status' => '0',
                'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
                'order_id' => $input['order_id'],
            ];
        }

        // sending response
        if($payment_response['payment_status'] == 'finished') {
            return [
                'status' => '1',
                'reason' => 'Your transaction has been processed successfully.',
                'order_id' => $input['order_id'],
            ];
        }
        else if (in_array($payment_response['payment_status'], self::PROCESSING_STATUSES)) {
            return [
                'status' => '4',
                'reason' => 'Your transaction is waiting be processed.',
                'order_id' => $input['order_id'],
            ];
        }
        else if (in_array($payment_response['payment_status'], self::ERROR_STATUSES)) {
            return [
                'status' => '0',
                'reason' => 'We are facing temporary issue from the bank side. Please contact us for more detail.',
                'order_id' => $input['order_id'],
            ];
        }
        else {
            return [
                'status' => '0',
                'reason' => 'We are facing temporary issue from the bank side. Please contact us for more detail.',
                'order_id' => $input['order_id'],
            ];
        }

    }

    // ================================================
    /* method : return
    * @param  : 
    * @Description : return from stripe 3ds
    */// ==============================================
    // public function return(Request $request, $session_id)
    // {
    //     $request_data = $request->all();

    //     $transaction_session = \DB::table('transaction_session')
    //         ->where('transaction_id', $session_id)
    //         ->where('created_at', '>', \Carbon\Carbon::now()->subHour(2)->toDateTimeString())
    //         ->where('is_completed', 0)
    //         ->first();

    //     if (empty($transaction_session)) {
    //         abort(404);
    //     }

    //     $input = json_decode($transaction_session->request_data, true);

    //     $check_assign_mid = checkAssignMid($input['payment_gateway_id']);

    //     $secret_key = $check_assign_mid->secret_key;

    //     if (isset($request_data['payment_intent']) && $request_data['payment_intent'] != null) {

    //         $get_url = self::BASE_URL.'/v1/payment_intents/'.$request_data['payment_intent'];

    //         $get_headers = [
    //             'Authorization: Bearer '.$secret_key
    //         ];

    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $get_url);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $get_headers);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //         $get_response = curl_exec($ch);

    //         curl_close ($ch);

    //         $get_data = json_decode($get_response, 1);

    //         if (isset($get_data['status']) && $get_data['status'] == 'succeeded') {
                
    //             $input['status'] = '1';
    //             $input['reason'] = 'Your transaction was proccessed successfully.';

    //         } elseif (isset($get_data['error']['message']) && $get_data['error']['message'] != null) {
    //             $input['status'] = '0';
    //             $input['reason'] = $get_data['error']['message'];

    //         } else {
    //             $input['status'] = '0';
    //             $input['reason'] = 'Your transaction could not processed.';
    //         }

    //         // redirect back to $response_url
    //         $transaction_response = $this->storeTransaction($input);

    //         $store_transaction_link = $this->getRedirectLink($input);
    //         return redirect($store_transaction_link);
    //     } else {
    //         abort(404);
    //     }
    // }

    // ================================================
    /* method : curlPost
    * @param  : 
    * @description : curl request
    */// ===============================================
    private function curlPost($url, $data, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        // dd(curl_getinfo($ch), $response);
        curl_close ($ch);

        return $response;
    }

    public function callback(Request $request, $session_id, $ipn)
    {

        // \Log::info([
        //     'NowPaymentsCrypto_CallBack' => $request->all(),
        //     'session_id' => base64_decode($session_id),
        // ]);
        
        if(!$this->checkIpnRequestIsValid(base64_decode($ipn))) {
            return false;
        }
        
        $body = $request->all();
        $data = \DB::table('transaction_session')->where('transaction_id', $session_id)->first();

        if($data) {
            
            if ($body['payment_status'] == 'finished') {
                $input = json_decode($data->request_data, 1);
                $input['status'] = '1';
                $input['reason'] = 'Your transaction was proccess successfully.';
                
                \Log::info(['type' => 'webhook', 'body' => $session_id.' confirm.']);
            }
            else if (in_array($body['payment_status'], self::PROCESSING_STATUSES)) {
                $input['status'] = '4';
                $input['reason'] = 'Your transaction is pending.';

                \Log::info(['type' => 'webhook', 'body' => $session_id.' processing.']); 
            }
            else if (in_array($body['payment_status'], self::ERROR_STATUSES)) {
                $input['status'] = '0';
                $input['reason'] = 'Your transaction is failed.';

                \Log::info(['type' => 'webhook', 'body' => $session_id.' invalid.']); 
            }
            else {
                \Log::info(['type' => 'webhook', 'body' => $session_id.' still not confirm.']);
            }

            // storing webhook response
            unset($input["api_key"]);
            $this->transaction->storeData($input);
            $this->storeMidWebhook($session_id, json_encode($body));
            
        } else {
            \Log::info(['type' => 'webhook', 'body' => $session_id.' still not confirm.']);
            exit();
        }
    }

    function checkIpnRequestIsValid($ipn_secret) {
        $error_msg = "Unknown error";
        $auth_ok = false;
        $request_data = null;
        if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
            $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            ksort($request_data);
            $sorted_request_json = json_encode($request_data);
            if ($request_json !== false && !empty($request_json)) {
                $hmac = hash_hmac("sha512", $sorted_request_json, trim($ipn_secret));
                if ($hmac == $recived_hmac) {
                    $auth_ok = true;
                } else {
                    $error_msg = 'HMAC signature does not match';
                }
            } else {
                $error_msg = 'Error reading POST data';
            }
        } else {
            $error_msg = 'No HMAC signature sent.';
        }

        if(!$auth_ok) {
            \Log::error(['nowpayment-webhook-error' => $error_msg]);
            // return $error_msg;
        }
        return $auth_ok;
        
    }
}