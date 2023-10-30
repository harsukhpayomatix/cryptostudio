<?php

namespace App\Http\Controllers\Repo;

use App\BlockData;
use App\Transaction;
use App\TransactionSession;
use App\Traits\Mid;
use App\Traits\StoreTransaction;
use App\Transformers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Repo\PaymentGateway\TestGateway;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestTransactionRepo extends Controller
{
    use Mid, StoreTransaction;

    protected $transaction, $transactionSession;

    // ================================================
    /* method : __construct
    * @param  :
    * @Description : Create a new controller instance.
    */ // ==============================================
    public function __construct()
    {
        $this->transaction = new Transaction;
        $this->transactionSession = new TransactionSession;
    }

    // ================================================
    /* method : store
    * @param  :
    * @Description : send $input details to gateway class
    */ // ==============================================
    public function store($input, $user, $check_assign_mid)
    {
        $input['session_id'] = $input['session_id'] ?? 'SD'. strtoupper(\Str::random(4)) . time();
        $input['order_id'] = $input['order_id'] ?? 'ODR'. strtoupper(\Str::random(4)) . time() . strtoupper(\Str::random(6));
        $input['state'] = $input['state'] ?? 'NA';
        $input['amount_in_usd'] = $this->amountInUSD($input);
        $input['converted_amount'] = $input['amount'];
        $input['converted_currency'] = $input['currency'];
        $input['phone_no'] = preg_replace('/[^0-9.]+/', '', $input['phone_no']);
        if (strlen($input['phone_no']) > 10) {
            $input['phone_no'] = substr($input['phone_no'], -10);
        }

        $block_data = BlockData::pluck('field_value')->toArray();
        if (!empty($block_data)) {
            if (in_array($input['email'], $block_data)) {
                $input['status'] = '5';
                $input['reason'] = 'This email address(' . $input['email'] . ') is blocked for transaction.';
                return $input;
            }
        }

        $input = $this->secureCardInputs($input);

        // if card_no is included into request
        if (isset($input['card_no']) && !empty($input['card_no'])) {
            $card_no = substr($input['card_no'], 0, 6);
            $card_no .= 'XXXXXX';
            $card_no .= substr($input['card_no'], -4);
            if (!empty($block_data)) {
                if (in_array($card_no, $block_data)) {
                    $input['status'] = '5';
                    $input['reason'] = 'This card(' . $card_no . ') This card(' . $card_no . ') is is for transaction.';
                    return $input;
                }
            }

            $expires = strtotime($input['ccExpiryYear'] . '-' . $input['ccExpiryMonth']);
            $now = strtotime(date('Y-m'));
            if ($expires < $now) {
                $input['status'] = '5';
                $input['reason'] = 'This card(' . $card_no . ') is Expired.';
                return $input;
            }
        }

        $this->transactionSession->storeData($input);
        
        try {
            $gateway_class = new TestGateway;
            $gateway_return_data = $gateway_class->checkout($input, $check_assign_mid);
        } catch (\Exception $exception) {
            $gateway_return_data['status'] = '0';
            $gateway_return_data['reason'] = 'Error in the gateway.';
        }

        $input = array_merge($input, $gateway_return_data);

        if ($input['status'] != '7') {
            $this->storeTransaction($input);
        }
        return $input;
    }

    // ================================================
    /* method : secureCardInputs
    * @param  : 
    * @description : functions on credit card
    */ // ===============================================
    private function secureCardInputs($input)
    {
        // change expiry year to 4 digit
        if (!empty($input['ccExpiryYear'])) {
            $input['ccExpiryYear'] = trim($input['ccExpiryYear']);
            if (strlen($input['ccExpiryYear']) == '2') {
                $input['ccExpiryYear'] = '20' . $input['ccExpiryYear'];
            }
        }

        // change expiry month to 2 digit
        if (!empty($input['ccExpiryMonth'])) {
            $input['ccExpiryMonth'] = trim($input['ccExpiryMonth']);
            if (strlen($input['ccExpiryMonth']) == '1') {
                $input['ccExpiryMonth'] = '0' . $input['ccExpiryMonth'];
            }
        }

        if (!empty($input['cvvNumber'])) {
            $input['cvvNumber'] = trim($input['cvvNumber']);
        }

        if (!empty($input['card_no'])) {
            $input['card_no'] = str_replace(' ', '', $input['card_no']);
        }

        return $input;
    }

    // ================================================
    /* method : getCardType
    * @param  : 
    * @description : get card type
    */ // ===============================================
    private function getCardType($card_no)
    {
        if (empty($card_no)) {
            return false;
        }
        $cardtype = array(
            "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex" => "/^3[47]\d{13,14}$/",
            "jcb" => "/^(?:2131|1800|35\d{3})\d{11}$/",
            "solo" => "/^(6334|6767)[0-9]{12}|(6334|6767)[0-9]{14}|(6334|6767)[0-9]{15}$/",
            "maestro" => "/^(5018|5020|5038|6304|6759|6761|6763|6768)[0-9]{8,15}$/",
            "discover" => "/^65[4-9][0-9]{13}|64[4-9][0-9]{13}|6011[0-9]{12}|(622(?:12[6-9]|1[3-9][0-9]|[2-8][0-9][0-9]|9[01][0-9]|92[0-5])[0-9]{10})$/",
            "switch" => "/^(4903|4905|4911|4936|6333|6759)[0-9]{12}|(4903|4905|4911|4936|6333|6759)[0-9]{14}|(4903|4905|4911|4936|6333|6759)[0-9]{15}|564182[0-9]{10}|564182[0-9]{12}|564182[0-9]{13}|633110[0-9]{10}|633110[0-9]{12}|633110[0-9]{13}$/",
            "unionpay" => "/^(62[0-9]{14,17})$/",
        );

        if (preg_match($cardtype['visa'], $card_no)) {
            return '2';
        } else if (preg_match($cardtype['mastercard'], $card_no)) {
            return '3';
        } else if (preg_match($cardtype['amex'], $card_no)) {
            return '1';
        } else if (preg_match($cardtype['discover'], $card_no)) {
            return '4';
        } else if (preg_match($cardtype['jcb'], $card_no)) {
            return '5';
        } else if (preg_match($cardtype['maestro'], $card_no)) {
            return '6';
        } else if (preg_match($cardtype['switch'], $card_no)) {
            return '7';
        } else if (preg_match($cardtype['solo'], $card_no)) {
            return '8';
        } else if (preg_match($cardtype['unionpay'], $card_no)) {
            return '9';
        } else {
            return '0';
        }
    }
}
