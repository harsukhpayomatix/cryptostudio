<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TxTry extends Model
{
    protected $table = 'tx_tries';
    protected $guarded = array();

    // ================================================
    /* method : storeData
    * @param  :
    * @description : store method
    */// ==============================================
    public function storeData($input)
    {
        // mask input
        if (isset($input['card_no']) && $input['card_no'] != null) {
            $input['card_no'] = substr($input['card_no'], 0, 6).'XXXXXX'.substr($input['card_no'], -4);
            $input['cvvNumber'] = 'XXX';
        }

        $data['try_id'] = 'TRY'.strtoupper(\Str::random(8)) . time() . strtoupper(\Str::random(6));
        $data['user_id'] = $input['user_id'] ?? null;
        $data['payment_gateway_id'] = $input['payment_gateway_id'] ?? null;
        $data['transaction_id'] = $input['session_id'] ?? null;
        $data['order_id'] = $input['order_id'] ?? null;
        $data['request_data'] = json_encode($input) ?? null;
        $data['amount'] = $input['amount'] ?? null;
        $data['email'] = $input['email'] ?? null;
        $data['is_completed'] = 0;

        return static::create($data);
    }
}
