<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\TxTry;
use App\Transaction;
use App\TransactionSession;
use App\Http\Controllers\Controller;
use App\Traits\Mid;
use App\Traits\RuleCheck;
use App\Traits\BinChecker;
use App\Traits\StoreTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
   


    // ================================================
    /* method : __construct
     * @param  : 
     * @description : create new instance of the class
     */// ===============================================

    public function __construct()
    {
        
    }

    // ================================================
    /* method : select Crypto currently
     * @param  : 
     * @description : 
     */// ===============================================

    public function selectCryptoCurrency($order_id)
    {

        $transaction_session = TransactionSession::where('order_id', $order_id)
            ->where('created_at', '>', Carbon::now()->subHour(2)->toDateTimeString())
            ->whereNotIn('payment_gateway_id', [0, 1, 2])
            ->whereNotNull('payment_gateway_id')
            ->where('is_completed', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($transaction_session)) {
            return abort(404);
        }

        $input = json_decode($transaction_session['input_details'], true);

        // validate user
        $user = DB::table('users')
            ->where('id', $transaction_session->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->whereNotNull('mid')
            ->whereNotIn('mid', [0, 1, 2])
            ->whereNotNull('crypto_mid')
            ->whereNotIn('crypto_mid', [0, 1, 2])
            ->first();

        if (empty($user)) {
            return abort(404);
        }

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV2' &&
            isset($input['payment_type']) && $input['payment_type'] == 'Crypto'
        ) {
            $input['payment_gateway_id'] = $input['payment_gateway_id'] ?? $user->crypto_mid;
        } else {
            $input['payment_gateway_id'] = $user->crypto_mid;

            // apply rules and get payment_gateway_id
            if ($user->is_disable_rule == '0') {
                $user_rule_gateway_id = $this->userCryptoRulesCheck($input, $user);
                if ($user_rule_gateway_id != false) {
                    $input['payment_gateway_id'] = $user_rule_gateway_id;
                } else {
                    $rule_gateway_id = $this->cryptoRulesCheck($input, $user);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // payment gateway object
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        if ($check_assign_mid == false) {
            return abort(404);
        }

        $required_fields = json_decode($check_assign_mid->required_fields, true);

        $data = [];
        foreach ($required_fields as $field) {
            if (empty($input[$field]) || $input[$field] == null) {
                $data[] = $field;
            }
        }

        // update request_data field
        TransactionSession::where('order_id', $order_id)
            ->where('is_completed', 0)
            ->update(['request_data' => json_encode($input)]);
        // send request to paymentgateway
        return view('gateway.apiv2.select_crypto_currency', compact('order_id', 'data', 'input'));
    }



}