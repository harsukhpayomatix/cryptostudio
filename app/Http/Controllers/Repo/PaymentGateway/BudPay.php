<?php

namespace App\Http\Controllers\Repo\PaymentGateway;

use DB;
use Session;
use App\Transaction;
use App\TransactionSession;
use App\Http\Controllers\Controller;
use App\Traits\StoreTransaction;
use Illuminate\Http\Request;

class BudPay extends Controller
{
    use StoreTransaction;
    
    const BASE_URL = 'https://budpay.ng/api/v1/';

    // ================================================
    /* method : transaction
     * @param  : 
     * @Description : wonderland api call
     */// ==============================================
    public function checkout($input, $check_assign_mid)
    {
        $dataCard = [
            "number" => $input["card_no"],
            "expiryMonth" => $input['ccExpiryMonth'],
            "expiryYear" => substr($input["ccExpiryYear"], -2),
            'cvv' => $input['cvvNumber']
        ];
        $dataCardArr["data"] = $dataCard;
        $dataCardArr["reference"] = $input["order_id"];
        $curlCard = curl_init();
        curl_setopt_array($curlCard, array(
            CURLOPT_URL => 'https://budpay.ng/api/s2s/test/encryption',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($dataCardArr,JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$check_assign_mid->secret_key,
                'Content-Type: application/json'
            )
        ));
        $responseCard = curl_exec($curlCard);
        curl_close($curlCard);
        $data = [
            "email" => $input["email"],
            "amount" => strval($input['converted_amount']),
            "currency" => $input["converted_currency"],
            //'callback' => route('budpay.callback',$input['session_id']),
            "reference" => $input["order_id"],
            "card" => $responseCard
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://budpay.ng/api/s2s/transaction/initialize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data,JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$check_assign_mid->secret_key,
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $response_data = json_decode($response, true);
        \Log::info([
            'budpay-response' => $response_data,
        ]);
        if(isset($response_data["status"]) && $response_data["status"] == true){
            try {
                $input['gateway_id'] = $input["order_id"] ?? null;
                $this->updateGatewayResponseData($input, $response_data);
                return [
                    'status' => '7',
                    'reason' => '3DS link generated successfully, please redirect to \'redirect_3ds_url\'.',
                    'redirect_3ds_url' => $response_data['alt'],
                ];
            } catch (Exception $e) {
                \Log::info([
                    'budpay-exception' => $e->getMessage()
                ]);
                return [
                    'status' => '0',
                    'reason' => $e->getMessage(),
                    'order_id' => $input['order_id']
                ];
            }
        }
    }

    public function callback(Request $request){
        $request_data = $request->all();
        \Log::info([
            'budpay_callback_data' => $request_data
        ]);
        $input_json = TransactionSession::where('order_id', $request_data["ref"])
            ->orderBy('id', 'desc')
            ->first();
        
        if ($input_json == null) {
            return abort(404);
        }        
        $input = json_decode($input_json['request_data'], true);
        $check_assign_mid = checkAssignMID($input["payment_gateway_id"]);
        
        if (isset($request_data['status']) && $request_data['status'] == 'success') {
            $input['status'] = '1';
            $input['reason'] = 'Your transaction has been processed successfully.';
        }
        elseif (isset($request_data['status']) && $request_data['status'] == 'failed') {
            $input['status'] = '0';
            $input['reason'] = 'Your transaction could not processed.';
        }else {
            $input['status'] = '2';
            $input['reason'] = 'Transaction is in pending.';
        }
        $transaction_response = $this->storeTransaction($input);
        $store_transaction_link = $this->getRedirectLink($input);
        return redirect($store_transaction_link);
    }

    public function webhook(Request $request){
        $request_data = $request->all();
        \Log::info([
            'budpay_webhook_data' => $request_data
        ]);
        $input_json = TransactionSession::where('order_id', $request_data["data"]["reference"])
            ->orderBy('id', 'desc')
            ->first();
        
        if ($input_json == null) {
            return abort(404);
        }        
        $input = json_decode($input_json['request_data'], true);
        if (isset($request_data["data"]['status']) && $request_data["data"]['status'] == 'success') {
            $input['status'] = '1';
            $input['reason'] = 'Your transaction has been processed successfully.';
            $transaction_response = $this->storeTransaction($input);
        }
        elseif (isset($request_data["data"]['status']) && $request_data["data"]['status'] == 'failed') {
            $input['status'] = '0';
            $input['reason'] = 'Your transaction could not processed.';
            $transaction_response = $this->storeTransaction($input);
        }
        
    }
}
