<?php

namespace App\Traits;

use App\MIDDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

trait Mid
{
	// ================================================
	/* method : midDefaultCurrencyCheck
	* @param  :
	* @description : check mid currency
	*/ // ==============================================
	public function midDefaultCurrencyCheck($payment_gateway_id, $currency, $amount)
	{
		$check = MIDDetail::select('converted_currency')
			->where('id', $payment_gateway_id)
			->first();

		// return false
		if ($check == null) {
			return false;
		}

		if ($check->converted_currency != '') {
			$selected = $check->converted_currency;

			$currency_api = 'https://apilayer.net/api/live?access_key=' . config('services.currency_layer.api_key') . '&currencies=' . $currency . '&source=' . $selected . '&format=1';

			// initialize CURL:
			$ch = curl_init($currency_api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$json = curl_exec($ch);
			curl_close($ch);

			$dd = json_decode($json);
			$selector = $selected . $currency;
			if (isset($dd->quotes->$selector)) {
				return ['amount' => (float) round(($amount / $dd->quotes->$selector), 2), 'currency' => $check->converted_currency];
			}
		}

		return false;
	}

	// ================================================
	/* method : amountInUSD
	* @param  :
	* @description : return USD amount
	*/ // ==============================================
	public function amountInUSD($input)
	{
		if ($input['currency'] == 'USD') {
			return $input['amount'];
		} else {
			$usd_rate = \DB::table('currency_rate')
				->where('currency', $input['currency'])
				->value('converted_amount');

			$usd_converted_amount = $input['amount'] / $usd_rate;

			return $usd_converted_amount;
		}
	}

	// ================================================
	/* method : perTransactionLimitCheck
	* @param  :
	* @description : per transaction maximum amount limit check
	*/ // ==============================================
	public function perTransactionLimitCheck($input, $check_assign_mid, $user)
	{
		if ($input['currency'] == 'USD') {
			$usd_converted_amount = $input['amount'];
		} else {
			if (isset($input['amount_in_usd']) && $input['amount_in_usd'] != null) {
				$usd_converted_amount = $input['amount_in_usd'];
			} else {
				$usd_rate = \DB::table('currency_rate')
					->where('currency', $input['currency'])
					->value('converted_amount');
				$usd_converted_amount = $input['amount'] / $usd_rate;
			}
		}

		if ($usd_converted_amount > $check_assign_mid->per_transaction_limit && $usd_converted_amount > $user->per_transaction_limit) {
			return [
				'status' => '5',
				'reason' => 'Per transaction amount should be less than ' . $check_assign_mid->per_transaction_limit . ' USD.'
			];
		}

		if ($usd_converted_amount < $check_assign_mid->min_transaction_limit) {
			return [
				'status' => '5',
				'reason' => 'Per transaction amount should be more than ' . $check_assign_mid->min_transaction_limit . ' USD'
			];
		}

		return false;
	}

	// ================================================
	/* method : perDayAmountLimitCheck
	* @param  :
	* @description : per day success amount limit check
	*/ // ==============================================
	public function perDayAmountLimitCheck($input, $check_assign_mid, $user)
	{
		$from_date = Carbon::now()->subDays(1)->toDateTimeString();
		$to_date = Carbon::now()->toDateTimeString();

		$daily_amount_processed = $this->successAmountBetweenDates($input['payment_gateway_id'], $from_date, $to_date);

		if ($daily_amount_processed > $check_assign_mid->per_day_limit) {
			return [
				'status' => '5',
				'reason' => 'Per day transaction amount limit exceeded.'
			];
		}

		return false;
	}

	// ================================================
	/* method : successAmountBetweenDates
	* @param  :
	* @description : total success amount between dates
	*/ // ==============================================
	public function successAmountBetweenDates($payment_gateway_id, $from_date, $to_date)
	{
		$sum = \DB::table('transactions')
			->whereNull('deleted_at')
			->where('payment_gateway_id', $payment_gateway_id)
			->where('status', '1')
			->whereBetween('created_at', [$from_date, $to_date])
			->sum('amount_in_usd');

		return $sum;
	}

	// ================================================
	/* method : cardTypeMIDBlocked
	* @param  : 
	* @description : check specific card type blocked or mid
	*/ // ===============================================
	public function cardTypeMIDBlocked($input, $user)
	{
		if (isset($input['card_type']) && $input['card_type'] == 2 && $user->visamid == "Block") {
			return [
				'status' => '5',
				'reason' => 'Card scheme(VISA) blocked.'
			];
		} elseif (isset($input['card_type']) && $input['card_type'] == 1 && $user->amexmid == "Block") {
			return [
				'status' => '5',
				'reason' => 'Card scheme(Amex) blocked.'
			];
		} elseif (isset($input['card_type']) && $input['card_type'] == 3 && $user->mastercardmid == "Block") {
			return [
				'status' => '5',
				'reason' => 'Card scheme(MasterCard) blocked.'
			];
		} elseif (isset($input['card_type']) && $input['card_type'] == 4 && $user->discovermid == "Block") {
			return [
				'status' => '5',
				'reason' => 'Card scheme(Discover) blocked.'
			];
		} else {
			// nothing for other card type
		}
		return false;
	}

	// ================================================
	/* method : userCardTypeMID
	* @param  : 
	* @description : check specific card type mid
	*/ // ===============================================
	public function userCardTypeMID($input, $user)
	{
		if (isset($input['card_type']) && $input['card_type'] == 1 && $user->amexmid != '' && $user->amexmid != 0) {
			return $user->amexmid;
		} elseif (isset($input['card_type']) && $input['card_type'] == 2 && $user->visamid != '' && $user->visamid != 0) {
			return $user->visamid;
		} elseif (isset($input['card_type']) && $input['card_type'] == 3 && $user->mastercardmid != '' && $user->mastercardmid != 0) {
			return $user->mastercardmid;
		} elseif (isset($input['card_type']) && $input['card_type'] == 4 && $user->discovermid != '' && $user->discovermid != 0) {
			return $user->discovermid;
		}
		return false;
	}

	// ================================================
	/* method : validateBlockedCountry
	* @param  : 
	* @description : get country blocks rulle
	*/ // ===============================================
	public function validateBlockedCountry($input, $check_assign_mid)
	{
		if (!empty($check_assign_mid->blocked_country)) {
			$blocked_country_array = json_decode($check_assign_mid->blocked_country);
			if (!empty($blocked_country_array) && in_array($input["country"], $blocked_country_array)) {
				return [
					'status' => '5',
					'reason' => 'The Country(' . $input["country"] . ')  blocked.'
				];
			}
		}
		return false;
	}
}
