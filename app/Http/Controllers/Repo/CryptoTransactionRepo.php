<?php

namespace App\Http\Controllers\Repo;

use App\User;
use App\Transaction;
use App\TransactionSession;
use App\Traits\Mid;
use App\Traits\RuleCheck;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\PaymentResponse;
use App\PaymentGateways\PaymentGatewayContract;
use Carbon\Carbon;

class CryptoTransactionRepo extends Controller
{
    use Mid, RuleCheck, StoreTransaction;

    // ================================================
    /* method : __construct
    * @param  :
    * @Description : Create a new controller instance.
    */// ==============================================
    public function __construct()
    {
        $this->transaction = new Transaction;
        $this->transactionSession = new TransactionSession;
    }

    // ================================================
    /* method : store
    * @param  :
    * @Description : send $input details to gateway class
    */// ==============================================
    public function store($input, $payment_gateway_id)
    {
        $input['session_id'] = 'SD'. strtoupper(\Str::random(4)) . time();
        $input['order_id'] = 'ODR'. strtoupper(\Str::random(4)) . time() . strtoupper(\Str::random(6));
        
        // add default state value 'NA' if not provided
        $input['state'] = $input['state'] ?? 'NA';

        // gateway default currency
        $check_selected_currency = $this->midDefaultCurrencyCheck($input['payment_gateway_id'], $input['currency'], $input['amount']);

        if($check_selected_currency) {
            $input['is_converted'] = '1';
            $input['converted_amount'] = $check_selected_currency['amount'];
            $input['converted_currency'] = $check_selected_currency['currency'];
        } else {
            $input['converted_amount'] = $input['amount'];
            $input['converted_currency'] = $input['currency'];
        }

        $input['amount_in_usd'] = $this->amountInUSD($input);

        if (
            isset($input['is_request_from_vt']) && $input['is_request_from_vt'] == 'IFRAMEAV1'
        ) {            
            // payment gateway object
            $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

            $mid_validations = $this->getMIDLimitResponse($input, $check_assign_mid, $payment_gateway_id);
        } else {

            // check rules and return payment_gateway_id
            if ($input["is_disable_rule"] != 1) {
                $rule_gateway_user_id = $this->userCryptoRulesCheck($input, $payment_gateway_id);
                if ($rule_gateway_user_id != false) {
                    $input['payment_gateway_id'] = $rule_gateway_user_id;
                } else {
                    $rule_gateway_id = $this->cryptoRulesCheck($input, $payment_gateway_id);
                    if ($rule_gateway_id != false) {
                        $input['payment_gateway_id'] = $rule_gateway_id;
                    }
                }
            }
        }

        // saving to transaction_session
        $this->transactionSession->storeData($input);

        // @TODO ways to get payment gateway id
        $check_assign_mid = checkAssignMID($input['payment_gateway_id']);

        // return mid limit response
        $mid_limit_response = $this->getMIDLimitResponse($input, $check_assign_mid, $payment_gateway_id);
        if ($mid_limit_response != false) {
            $input['status'] = $mid_limit_response['status'];
            $input['reason'] = $mid_limit_response['reason'];

            // store transaction
            $store_transaction_link = $this->storeTransaction($input);

            $input['redirect_3ds_url'] = $store_transaction_link;

            return $input;
        }

        // gateway curl response
        $gateway_curl_response = $this->gatewayCurlResponse($input, $check_assign_mid);

        $input['status'] = $gateway_curl_response['status'];
        $input['reason'] = $gateway_curl_response['reason'];
        
        // store transaction
        if($gateway_curl_response['status'] != '7') {
            $store_transaction_link = $this->storeTransaction($input);
        }

        return $gateway_curl_response;
    }

    // ================================================
    /* method : getMIDLimitResponse
    * @param  :
    * @description : return data from mid
    */// ==============================================
    public function getMIDLimitResponse($input, $check_assign_mid, $payment_gateway_id)
    {
        // per transaction limit
        $per_transaction_limit_response = $this->perTransactionLimitCheck($input, $check_assign_mid, $payment_gateway_id);
        if ($per_transaction_limit_response != false) {
            return $per_transaction_limit_response;
        }

        // mid daily limit
        $mid_daily_limit = $this->perDayAmountLimitCheck($input, $check_assign_mid, $payment_gateway_id);
        if ($mid_daily_limit != false) {
            return $mid_daily_limit;
        }

        $transactions_check = \DB::table('transactions')
            ->whereNull('deleted_at')
            ->where('status', '<>', '5')
            ->where('user_id', $input['user_id'])
            ->where('payment_gateway_id', $input['payment_gateway_id']);

        // if there is card_no
        if (isset($input['card_no']) && $input['card_no'] != null) {

            $card_transactions_check = clone $transactions_check;
            $card_transactions_check = $card_transactions_check->where('card_no', substr($input['card_no'], 0, 6) . 'XXXXXX' . substr($input['card_no'], -4));

            // daily card limit check
            $card_daily_transactions = clone $card_transactions_check;
            $card_daily_transactions = $card_daily_transactions->whereBetween('created_at', [Carbon::now()->subDays(1)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($card_daily_transactions >= $check_assign_mid->per_day_card && $card_daily_transactions >= $user->one_day_card_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per day transactions by card limit exceeded.'
                ];
            }

            // card per-week limit
            $card_weekly_transactions = clone $card_transactions_check;
            $card_weekly_transactions = $card_weekly_transactions->whereBetween('created_at', [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($card_weekly_transactions >= $check_assign_mid->per_week_card && $card_weekly_transactions >= $user->one_week_card_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per week transactions by card limit exceeded.'
                ];
            }

            // card per-month limit
            $card_monthly_transactions = clone $card_transactions_check;
            $card_monthly_transactions = $card_monthly_transactions->whereBetween('created_at', [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($card_monthly_transactions >= $check_assign_mid->per_month_card && $card_monthly_transactions >= $user->one_month_card_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per month transactions by card limit exceeded.'
                ];
            }
        }

        // if there is email
        if (isset($input['email']) && $input['email'] != null) {

            $email_transactions_check = clone $transactions_check;
            $email_transactions_check = $email_transactions_check->where('email', $input['email']);

            // email per-day limit
            $email_daily_transactions = clone $email_transactions_check;
            $email_daily_transactions = $email_daily_transactions->whereBetween('created_at', [Carbon::now()->subDays(1)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($email_daily_transactions >= $check_assign_mid->per_day_email && $email_daily_transactions >= $user->one_day_email_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per day transactions by email limit exceeded.'
                ];
            }

            // email per-week limit
            $email_weekly_transactions = clone $email_transactions_check;
            $email_weekly_transactions = $email_weekly_transactions->whereBetween('created_at', [Carbon::now()->subDays(7)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($email_weekly_transactions >= $check_assign_mid->per_week_email && $email_weekly_transactions >= $user->one_week_email_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per week transactions by email limit exceeded.'
                ];
            }

            // email per-month limit
            $email_monthly_transactions = clone $email_transactions_check;
            $email_monthly_transactions = $email_monthly_transactions->whereBetween('created_at', [Carbon::now()->subDays(30)->toDateTimeString(), Carbon::now()->toDateTimeString()])
                ->count();
            if ($email_monthly_transactions >= $check_assign_mid->per_month_card && $email_monthly_transactions >= $user->one_month_email_limit) {
                return [
                    'status' => '5',
                    'reason' => 'Per month transactions by email limit exceeded.'
                ];
            }
        }

        // blocked country validation
        if (isset($input['country']) && $input['country'] != null) {
            $blocked_country_response = $this->validateBlockedCountry($input, $check_assign_mid);
            if ($blocked_country_response != false) {
                return $blocked_country_response;
            }
        }

        return false;
    }

    // ================================================
    /* method : gatewayCurlResponse
    * @param  :
    * @description : get first response from gateway
    */// ==============================================
    public function gatewayCurlResponse($input, $check_assign_mid)
    {
        try {
            $class_name = 'App\\Http\\Controllers\\Repo\\PaymentGateway\\' . $check_assign_mid->title;
            $gateway_class = new $class_name;
            $gateway_return_data = $gateway_class->checkout($input, $check_assign_mid);
        } catch (\Exception $exception) {
            $gateway_return_data['status'] = '0';
            $gateway_return_data['reason'] = 'Problem with your transaction data or may be transaction timeout from the acquirer.';
        }

        return $gateway_return_data;
    }
}
