<?php

namespace App\Http\Controllers\Repo\PaymentGateway;

use App\Jobs\MilkyPayTxnJob;
use DB;
use App\Traits\StoreTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;

class MidsPay extends Controller
{
	use StoreTransaction;

	const BASE_URL = 'https://api.mids.com';
	const CARD_URL = "https://checkout.mids.com/payment/sale";
	// const WL_URL = 'https://secure.mids.com/api/v1/wl/item?token=iauPLuigfQ2kH059CufFxCtImbYlb7CiCLUQQlWEVMvkp9wZlb0uJX251sqGoAlA';

	public function checkout($input, $check_assign_mid)
	{
        // dd("success");
		// * Check if card is not in Visa and Master then block it
		$supportCards = ["2", "3"];
		if (!in_array($input["card_type"], $supportCards)) {
			return [
				"status" => "5",
				"reason" => "At the moment we do support only Visa and Master cards.",
				"card_type" => $input["card_type"]
			];
		}

		$payment_url = self::BASE_URL . '/payment-invoices';

		$payment_data = [
			'data' => [
				'type' => 'payment-invoices',
				'attributes' => [
					'reference_id' => $input['session_id'],
					'description' => 'Payment order',
					'currency' => $input['converted_currency'],
					'amount' => $input['converted_amount'],
					// "test_mode" => false,
                    "test_mode" => true,
					'service' => 'payment_card_eur_hpp',
                    // 'return_url' => "https://4824-122-160-255-233.ngrok-free.app/mid/return/".$input['session_id'],
					// 'callback_url' => "https://4824-122-160-255-233.ngrok-free.app/mid/callback/".$input['session_id'],
					'return_url' => route('midsPay.return', $input['session_id']),
					'callback_url' => route('midsPay.callback', $input['session_id']),
					// 'return_url' => "https://webhook.site/157e06c7-afb4-4094-8874-b78607450a77?type=return",
					// 'callback_url' => "https://webhook.site/157e06c7-afb4-4094-8874-b78607450a77?type=call",
                    //   'return_url' => "https://1ba4-122-160-255-233.ngrok-free.app/mid/return/".$input['session_id'],
					// 'callback_url' => "https://1ba4-122-160-255-233.ngrok-free.app/mid/callback/".$input['session_id'],
					"customer" => [
						"reference_id" => $input["session_id"],
						"name" => $input["first_name"] . " " . $input["last_name"],
						"email" => $input["email"],
						"phone" => $input["phone_no"],
						"date_of_birth" => generateRandomDob(),
						"address" => [
							"country" => $input["country"],
							"city" => $input["city"],
							"street" => $input["address"],
							"full_address" => $input["address"],
							"post_code" => $input["zip"]
						]
					]
				]
			]
		];

		$response = Http::withBasicAuth($check_assign_mid->account_id, $check_assign_mid->password)->post($payment_url, $payment_data)->json();
        // $response = Http::withBasicAuth('coma_WbyZpgkzjNDZWPNN', 'eWH5SLKNuwuCUuXIl4vF64Keg6k1yRk3w1KyYRXMKek')->post($payment_url, $payment_data)->json();

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

		if (empty ($response) || $response == null) {
			return [
				'status' => '0',
				'reason' => "We are facing temporary issue from the bank side. Please contact us for more detail.",
				'order_id' => $input['order_id'],
			];
		} elseif (isset($response["status"]) && $response["status"] == "process_failed") {
			return [
				"status" => "0",
				"reason" => "Transaction could not processed!"
			];
		} elseif (isset($response["status"]) && $response["status"] == "processed") {
			return [
				"status" => "1",
				"reason" => "Transaction processed successfully!"
			];
		} else {
			return [
				'status' => '7',
				'reason' => '3DS link generated successful, please redirect.',
				'redirect_3ds_url' => route('midsPay.getBrowser.info', [$input["session_id"]])
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
			isset ($payment_array['data']['attributes']['flow_data']['metadata']['token']) && !empty ($payment_array['data']['attributes']['flow_data']['metadata']['token'])
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

			$card_data["data"]["attributes"]["card_number"] = cardMasking($card_data["data"]["attributes"]["card_number"]);
			$card_data["data"]["attributes"]["cvv"] = "XXX";
			$this->storeMidPayload($input["session_id"], json_encode($card_data));
			// update session-data
			$this->updateGatewayResponseData($input, $card_array);

			// card whitelist api
			// try {
			// 	if (isset($input['is_white_label']) && $input['is_white_label'] == 0) {
			// 		$wl_array = [
			// 			'item' => cardMasking($card_data["data"]["attributes"]["card_number"])
			// 		];
			// 		$ch = curl_init();
			//         curl_setopt($ch, CURLOPT_POST, 1);
			//         curl_setopt($ch, CURLOPT_URL, self::WL_URL);
			//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($wl_array));
			//         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			//         	'Content-Type: application/json',
			//         	'Accept: application/json'
			//         ));
			//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//         $wl_body = curl_exec($ch);
			//         curl_close ($ch);

			//         $wl_data = json_decode($wl_body, 1);

			//         if (isset($wl_data['data']['status']) && $wl_data['data']['status'] != 'ok') {
			//         	\Log::info(['message' => 'mids.com card wl api error', 'data' => json_encode($wl_array), 'response' => $wl_body]);
			//         }
			// 	}
			// } catch (\Exception $e) {
	        // 	\Log::info(['message' => 'mids.com card wl api catch', 'e' => $e->getMessage()]);
			// }

			if (isset ($card_array['status']) && $card_array['status'] == 'processed') {
				$input['status'] = '1';
				$input['reason'] = 'Your transaction was proccessed successfully.';
			} elseif (isset ($card_array['status']) && $card_array['status'] == 'process_pending') {
				return redirect()->route('midsPay.form', $input['session_id']);

			} elseif (isset ($card_array['status']) && $card_array['status'] == 'process_failed') {
				$input['status'] = '0';
				$input['reason'] = $card_array['errors'][0]['title'] ?? $card_array['errors'][0]['status'] ?? 'Transaction authentication failed.';
				if ($input['reason'] == 'Unprocessable Content') {
					$input['status'] = '2';
					\Log::info(['mids_decline_one:' => $input['order_id']]);
					\Log::info(['mids_response_one:' => $card_array]);
				}
			} elseif (isset($card_array['errors']) && !empty($card_array['errors'])) {
				$input['status'] = '0';
				$input['reason'] = $card_array['errors'][0]['title'] ?? $card_array['errors'][0]['status'] ?? 'Transaction authentication failed.';
				if ($input['reason'] == 'Unprocessable Content') {
					$input['status'] = '2';
					\Log::info(['mids_decline_two:' => $input['order_id']]);
					\Log::info(['mids_response_two:' => $card_array]);
				}
			} else {
				\Log::info(['mids_else_first:' => $input['order_id']]);
				\Log::info(['mids_response_first:' => $card_array]);
				$input['status'] = '2';
				$input['reason'] = $card_array['errors'][0]['title'] ?? $card_array['errors'][0]['status'] ?? 'Transaction is in pending state.';
			}
		} elseif (isset($payment_array["status"]) && $payment_array["status"] == "processed") {
			$input["status"] = "1";
			$input["reason"] = "Transaction processed successfully!";
		} elseif (isset($payment_array["status"]) && $payment_array["status"] == "process_failed") {
			$input["status"] = "0";
			$input["reason"] = "Transaction could not processed!";
		} elseif (isset ($payment_array['error']['message']) && !empty ($payment_array['error']['message'])) {
			$input['status'] = '0';
			$input['reason'] = $payment_array['error']['message'] ?? $payment_array['errors'][0]['title'] ?? $payment_array['errors'][0]['status'] ?? 'Transaction initialization failed.';
		} elseif (isset($payment_array['errors']) && !empty($payment_array['errors'])) {
			$input['status'] = '0';
			$input['reason'] = $payment_array['error']['message'] ?? $payment_array['errors'][0]['title'] ?? $payment_array['errors'][0]['status'] ?? 'Transaction initialization failed.';
			if ($input['reason'] == 'Unprocessable Content') {
				$input['status'] = '2';
				\Log::info(['mids_decline_three:' => $input['order_id']]);
				\Log::info(['mids_response_three:' => $payment_array]);
			}
		} else {
			\Log::info(['mids_else_second:' => $input['order_id']]);
			\Log::info(['mids_response_second:' => $payment_array]);
			$input['status'] = '2';
			$input['reason'] = $payment_array['errors'][0]['title'] ?? $payment_array['errors'][0]['status'] ?? 'Transaction initialization failed.';
		}

		$this->storeTransaction($input);
		// convert response in query string
		$store_transaction_link = $this->getRedirectLink($input);
		return redirect($store_transaction_link);
	}

	// * MidsPay browser info
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

		if (empty ($transaction_session)) {
			abort(404);
		}

		$input = json_decode($transaction_session->request_data, true);
		$response_data = json_decode($transaction_session->response_data, true);

		if (isset ($response_data['auth_payload']['action'])) {

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


	public function return(Request $request, $session_id)
	{
		$transaction_session = DB::table('transaction_session')
			->where('transaction_id', $session_id)
			->first();

		if (empty ($transaction_session)) {
			abort(404);
		}

		$input = json_decode($transaction_session->request_data, true);

		$check_assign_mid = checkAssignMid($input['payment_gateway_id']);

		$verify_response = $this->verify($input, $check_assign_mid);

		// * Get the transaction reason from MilkiPay
		$txnReason = isset ($verify_response["data"]["attributes"]["original_data"]["original_resolution_message"]) && $verify_response["data"]["attributes"]["original_data"]["original_resolution_message"] != "" ? $verify_response["data"]["attributes"]["original_data"]["original_resolution_message"] : null;

		if (isset ($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'processed') {
			$input['status'] = '1';
			$input['reason'] = 'Your transaction was proccessed successfully.';
		} elseif (isset ($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_pending') {
			$input['status'] = '2';
			$input['reason'] = 'Transaction pending for approval.';
		} elseif (isset ($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_failed') {
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? 'Transaction authentication failed.';
			if ($input['reason'] == 'Unprocessable Content') {
				$input['status'] = '2';
				\Log::info(['mids_decline_four:' => $input['order_id']]);
				\Log::info(['mids_response_four:' => $verify_response]);
			}
		} elseif (isset ($verify_response['errors'][0]['status']) && !empty ($verify_response['errors'][0]['status'])) {
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? "Transaction could not processed.";
			if ($input['reason'] == 'Unprocessable Content') {
				$input['status'] = '2';
				\Log::info(['mids_decline_five:' => $input['order_id']]);
				\Log::info(['mids_response_five:' => $verify_response]);
			}
		} else {
			$input['status'] = '2';
			$input['reason'] = $txnReason ?? 'Transaction is in pending state.please wait for sometime.';
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

		if (empty ($transaction_session)) {
			abort(404);
		}

		$input = json_decode($transaction_session->request_data, true);

		$check_assign_mid = checkAssignMid($input['payment_gateway_id']);

		$verify_response = $this->verify($input, $check_assign_mid);

		$this->storeMidWebhook($session_id, json_encode($verify_response));

		// * Get the transaction reason from MilkiPay
		$txnReason = isset ($verify_response["data"]["attributes"]["original_data"]["original_resolution_message"]) && $verify_response["data"]["attributes"]["original_data"]["original_resolution_message"] != "" ? $verify_response["data"]["attributes"]["original_data"]["original_resolution_message"] : null;

		if (isset ($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'processed') {
			$input['status'] = '1';
			$input['reason'] = 'Your transaction was proccessed successfully.';
		} elseif (isset ($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_pending') {
			$input['status'] = '2';
			$input['reason'] = $txnReason ?? 'Transaction pending for approval.';
		} elseif (isset ($verify_response['data']['attributes']['status']) && $verify_response['data']['attributes']['status'] == 'process_failed') {
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? 'Transaction authentication failed.';
			if ($input['reason'] == 'Unprocessable Content') {
				\Log::info(['mids_decline_six:' => $input['order_id']]);
				\Log::info(['mids_response_six:' => $verify_response]);
			}
		} elseif (isset ($verify_response['errors'][0]['status']) && !empty ($verify_response['errors'][0]['status'])) {
			$input['status'] = '0';
			$input['reason'] = $txnReason ?? "Your transaction could not processed!";
			if ($input['reason'] == 'Unprocessable Content') {
				\Log::info(['mids_decline_seven:' => $input['order_id']]);
				\Log::info(['mids_response_seven:' => $verify_response]);
			}
		} else {
			// Log::info(['milkypay_verify_else' => $verify_response]);
			$input['status'] = '2';
			$input['reason'] = $txnReason ?? 'Transaction is in Pending state.please wait for sometime.';
		}

		// redirect back to $response_url
		$this->storeTransaction($input);
		exit();
	}

	public function verify($input, $check_assign_mid)
	{
		$input["gateway_id"] = $input["gateway_id"] ?? "1";
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
