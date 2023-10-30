<?php

namespace App\Http\Controllers\Repo\PaymentGateway;

use App\Jobs\MilkyPayTxnJob;
use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;

class MilkyPay extends Controller
{
	use StoreTransaction;

	const BASE_URL = 'https://pay.pay-gate.io';
	const CARD_URL = "https://checkout.pay-gate.io/payment/sale";



	public function checkout($input, $check_assign_mid)
	{
		$payment_url = self::BASE_URL . '/payment-invoices';

		$payment_data = [
			'data' => $check_assign_mid->id == "60" ? [
				'type' => 'payment-invoices',
				'attributes' => [
					'reference_id' => $input['session_id'],
					'description' => 'Payment order',
					'currency' => $input['converted_currency'],
					'amount' => $input['converted_amount'],
					'service' => 'payment_card_eur_hpp',
					'return_url' => route('milkypay.return', $input['session_id']),
					'callback_url' => route('milkypay.callback', $input['session_id']),
					"customer" => [
						"reference_id" => $input["session_id"],
						"name" => $input["first_name"] . " " . $input["last_name"],
						"email" => $input["email"],
						"address" => [
							"country" => $input["country"]
						]
					]
				]
			] : [
				'type' => 'payment-invoices',
				'attributes' => [
					'reference_id' => $input['session_id'],
					'description' => 'Payment order',
					'currency' => $input['converted_currency'],
					'amount' => $input['converted_amount'],
					'service' => 'payment_card_eur_hpp',
					// 'test_mode' => true,
					// set this true for test transaction
					'return_url' => route('milkypay.return', $input['session_id']),
					// 'return_url' => "https://webhook.site/14414f53-7cdd-4b27-b28a-fbc478f5485d",
					'callback_url' => route('milkypay.callback', $input['session_id']),
					// 'callback_url' => "https://webhook.site/14414f53-7cdd-4b27-b28a-fbc478f5485d",
				],
			],
		];

		$response = Http::withBasicAuth($check_assign_mid->account_id, $check_assign_mid->password)->post($payment_url, $payment_data)->json();
		// Log::info(["input" => $input, "payment-invoice-res" => json_encode($response)]);

		$cardpayload = [
			"card_no" => $input["card_no"],
			"cvvNumber" => $input["cvvNumber"],
			"ccExpiryMonth" => $input["ccExpiryMonth"],
			"ccExpiryYear" => $input["ccExpiryYear"],
		];
		$encrypteCard = encrypt($cardpayload);

		$this->storeMidPayload($input["session_id"], json_encode($encrypteCard));
		// update session-data
		$input['gateway_id'] = $response['data']['id'] ?? "1";
		$this->updateGatewayResponseData($input, $response);

		if (empty($response) || $response == null) {
			return [
				'status' => '0',
				'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
				'order_id' => $input['order_id'],
			];
		} else {
			return [
				'status' => '7',
				'reason' => '3DS link generated successful, please redirect.',
				'redirect_3ds_url' => route('milkypay.getBrowser.info', [$input["session_id"]])
			];
		}

	}

	public function cardDataRequest($session_id, $browser_info)
	{

		$transaction = DB::table('transaction_session')->select("id", "request_data", "response_data", "payment_gateway_id", "mid_payload")->where("transaction_id", $session_id)->first();
		$decryptedData = json_decode($transaction->mid_payload, true);
		try {
			$ccDetails = decrypt($decryptedData);
		} catch (\Exception $err) {
			// $ccDetails = $decryptedData;
			exit();
		}

		$input = json_decode($transaction->request_data, true);
		$payment_array = json_decode($transaction->response_data, true);

		if (
			isset($payment_array['data']['attributes']['flow_data']['metadata']['token']) && !empty($payment_array['data']['attributes']['flow_data']['metadata']['token'])
		) {
			$card_data = [
				'data' => [
					'type' => 'sale-operation',
					'attributes' => [
						'card_number' => $ccDetails['card_no'],
						'card_holder' => $input['first_name'] . ' ' . $input['last_name'],
						'cvv' => $ccDetails['cvvNumber'],
						'exp_month' => $ccDetails['ccExpiryMonth'],
						'exp_year' => substr($ccDetails['ccExpiryYear'], -2),
						"browser_info" => $browser_info
					],

				],
			];
			$card_array = Http::withHeaders(["Authorization" => 'Bearer ' . $payment_array['data']['attributes']['flow_data']['metadata']['token']])->post(self::CARD_URL, $card_data)->json();

			// Log::info(["card-payload" => $card_data, "card-array-resonse" => $card_array]);

			$card_data["data"]["attributes"]["card_number"] = cardMasking($card_data["data"]["attributes"]["card_number"]);
			$card_data["data"]["attributes"]["cvv"] = "XXX";
			$this->storeMidPayload($input["session_id"], json_encode($card_data));
			// update session-data
			$this->updateGatewayResponseData($input, $card_array);

			if (isset($card_array['status']) && $card_array['status'] == 'processed') {
				$input['status'] = '1';
				$input['reason'] = 'Your transaction was processed successfully.';
			} elseif (isset($card_array['status']) && $card_array['status'] == 'process_pending') {
				return redirect()->route('milkypay.form', $input['session_id']);

			} elseif (isset($card_array['status']) && $card_array['status'] == 'process_failed') {
				$input['status'] = '0';
				$input['reason'] = $card_array['errors'][0]['title'] ?? $card_array['errors'][0]['status'] ?? 'Transaction authentication failed.';

			} else {
				$input['status'] = '0';
				$input['reason'] = $card_array['errors'][0]['title'] ?? $card_array['errors'][0]['status'] ?? 'Transaction authorization failed.';
			}
		} elseif (isset($payment_array['error']['message']) && !empty($payment_array['error']['message'])) {
			$input['status'] = '0';
			$input['reason'] = $payment_array['error']['message'] ?? $payment_array['errors'][0]['status'] ?? 'Transaction initialization failed.';
		} else {
			$input['status'] = '0';
			$input['reason'] = $payment_array['errors'][0]['title'] ?? $payment_array['errors'][0]['status'] ?? 'Transaction initialization failed.';
		}

		$this->storeTransaction($input);
		// convert response in query string
		$store_transaction_link = $this->getRedirectLink($input);
		return redirect($store_transaction_link);
	}

	// * MilkyPay browser info
	public function getBrowserInfo(Request $request, $id)
	{
		return view('gateway.honeypay.browserInfo', compact('id'));
	}

	// * Store the browser info
	public function storeBrowserInfo(Request $request)
	{
		$payload = $request->only(["session_id", "browser_info"]);
		$transaction = DB::table("transaction_session")->select("request_data")->where("transaction_id", $payload["session_id"])->first();
		$input = json_decode($transaction->request_data, true);
		$browser_info = json_decode($payload["browser_info"], true);
		$browser_info["browser_ip"] = $input["ip_address"];
		$browser_info["browser_accept_header"] = $request->header('Accept');
		$browser_info["window_width"] = strval($browser_info["window_width"]);
		$browser_info["window_height"] = strval($browser_info["window_height"]);
		$browser_info["browser_color_depth"] = strval($browser_info["browser_color_depth"]);
		$browser_info["browser_screen_width"] = strval($browser_info["browser_screen_width"]);
		$browser_info["browser_screen_height"] = strval($browser_info["browser_screen_height"]);
		return $this->cardDataRequest($payload["session_id"], $browser_info);
	}

	public function form($session_id)
	{
		$transaction_session = \DB::table('transaction_session')
			->where('transaction_id', $session_id)
			// ->where('created_at', '>', \Carbon\Carbon::now()->subHour(2)->toDateTimeString())
			->where('is_completed', 0)
			->first();

		if (empty($transaction_session)) {
			abort(404);
		}

		$input = json_decode($transaction_session->request_data, true);
		$response_data = json_decode($transaction_session->response_data, true);

		if (isset($response_data['auth_payload']['action'])) {

			$action = $response_data['auth_payload']['action'];
			$method = $response_data['auth_payload']['method'];
			$fields = $response_data['auth_payload']['params'];

			$check_assign_mid = checkAssignMid($input['payment_gateway_id']);

			return view('gateway.honeypay.form', compact('action', 'method', 'fields'));
		} else {
			$input['status'] = '0';
			$input['reason'] = 'Transaction declined in 3D secure verification.';
			$transaction_response = $this->storeTransaction($input);
			$store_transaction_link = $this->getRedirectLink($input);
			return redirect($store_transaction_link);
		}
	}


	public function return (Request $request, $session_id)
	{
		// $request_data = $request->all();

		$transaction_session = DB::table('transaction_session')
			->where('transaction_id', $session_id)
			->first();

		if (empty($transaction_session)) {
			abort(404);
		}

		$input = json_decode($transaction_session->request_data, true);

		$check_assign_mid = checkAssignMid($input['payment_gateway_id']);

		$verify_response = $this->verify($input, $check_assign_mid);

		if (isset($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'processed') {
			$input['status'] = '1';
			$input['reason'] = 'Your transaction was proccessed successfully.';
		} elseif (isset($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_pending') {
			$input['status'] = '2';
			$input['reason'] = 'Transaction pending for approval.';
		} elseif (isset($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_failed') {
			// Log::info(['milkypay_verify_failed' => $verify_response]);
			$input['status'] = '0';
			$input['reason'] = $verify_response['error'][0]['status'] ?? 'Transaction authentication failed.';
		} elseif (isset($verify_response['errors'][0]['status']) && !empty($verify_response['errors'][0]['status'])) {
			$input['status'] = '0';
			$input['reason'] = $verify_response['errors'][0]['status'];
		} else {
			// Log::info(['milkypay_verify_else' => $verify_response]);
			$input['status'] = '0';
			$input['reason'] = $verify_response['errors']['status'] ?? 'Transaction declined in 3D secure verification.';
		}

		// redirect back to $response_url
		$this->updateGatewayResponseData($input, $verify_response);
		$this->storeTransaction($input);

		$store_transaction_link = $this->getRedirectLink($input);
		return redirect($store_transaction_link);
	}


	public function callback(Request $request, $session_id)
	{
		// $request_data = $request->all();

		$transaction_session = DB::table('transaction_session')
			->where('transaction_id', $session_id)
			->first();

		if (empty($transaction_session)) {
			abort(404);
		}



		$input = json_decode($transaction_session->request_data, true);

		$check_assign_mid = checkAssignMid($input['payment_gateway_id']);

		$verify_response = $this->verify($input, $check_assign_mid);

		$this->storeMidWebhook($session_id, json_encode($verify_response));


		// * Get the transaction reason from MilkiPay
		$txnReason = isset($verify_response["data"]["attributes"]["original_data"]["original_resolution_message"]) && $verify_response["data"]["attributes"]["original_data"]["original_resolution_message"] != "" ? $verify_response["data"]["attributes"]["original_data"]["original_resolution_message"] : null;

		if (isset($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'processed') {
			$input['status'] = '1';
			$input['reason'] = $txnReason ?? 'Your transaction was proccessed successfully.';
		} elseif (isset($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_pending') {
			$input['status'] = '2';
			$input['reason'] = $txnReason ?? 'Transaction pending for approval.';
		} elseif (isset($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_failed') {
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? 'Transaction authentication failed.';
		} elseif (isset($verify_response['errors'][0]['status']) && !empty($verify_response['errors'][0]['status'])) {
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? "Your transaction could not processed!";
		} else {
			// Log::info(['milkypay_verify_else' => $verify_response]);
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? 'Transaction declined in 3D secure verification.';
		}

		// redirect back to $response_url
		// $this->updateGatewayResponseData($input, $verify_response);
		$this->storeTransaction($input);
		exit();
		// $store_transaction_link = $this->getRedirectLink($input);
		// return redirect($store_transaction_link);
	}

	public function verify($input, $check_assign_mid)
	{
		$verify_url = self::BASE_URL . '/payment-invoices/' . $input['gateway_id'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $verify_url);
		curl_setopt($ch, CURLOPT_USERPWD, $check_assign_mid->account_id . ':' . $check_assign_mid->password);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$verify_body = curl_exec($ch);

		curl_close($ch);

		$verify_array = json_decode($verify_body, 1);

		return $verify_array;
	}

	public function pendingTxn(Request $request)
	{
		try {
			if ($request->get("password") != "f8d3h5883e7e61hJ698a0184f3445545e3e56489") {
				abort(404);
			}
			$mid = checkAssignMID("30");
			MilkyPayTxnJob::dispatch($mid, self::BASE_URL . '/payment-invoices/');
			return response()->json(["status" => 200, "message" => "job added successfully!"]);
		} catch (\Exception $err) {
			return response()->json(["status" => 500, "message" => $err->getMessage()]);

		}
	}
}