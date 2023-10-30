<?php

use App\Mail\AdminRefundNotification;
use App\Mail\RefundTransactionMail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Transaction;
use Carbon\Carbon;


// Test DB Connection
Route::get('test-db-connection', function () {
	try {
		DB::connection()->getPdo();
		echo "DB connect successfully.";
	} catch (\Exception $e) {
		die("Could not connect to the database.  Please check your configuration. error:" . $e);
	}
});

// password generator
Route::get('get-pass', function (Request $request) {
	dd(Hash::make($request->password));
});

// test ip in live
Route::get('get-ip', function () {
	echo get_client_ip();
});

Route::get('encrypt-data', 'TestController@encryptData')->name('encrypt-data');
Route::get('test-webhook-url', 'TestController@testWebhookUrl')->name('test-webhook-url');
Route::get('microtime-to-second', 'TestController@microtimeToSecond')->name('microtime-to-second');

/*
|--------------------------------------------------------------------------
| Test Routes
|--------------------------------------------------------------------------
*/
Route::get('fill-test-transactions', 'API\TestController@fillTestTransactions')->name('fill-test-transactions');

Route::get('bill-transactions', 'API\TestController@getBillTransactions')->name('bill-transactions');

Route::get('auto-report', 'API\TestController@autoReport')->name('auto-report');

Route::get('onlinenaira', 'API\TestController@onlinenaira')->name('onlinenaira');

Route::get('invoice', 'API\TestController@invoice')->name('invoice');
/*
|--------------------------------------------------------------------------
| Test MID Routes
|--------------------------------------------------------------------------
*/
Route::get('facilitapay-form', 'TestController@facilitapayForm')->name('facilitapay-form');


Route::get('readlog', 'DebugController@logfileread')->name('readlog');
Route::get('/debug/{id}', 'DebugController@debug')->name('debug');

Route::any('testwyre', 'API\TestController@testwyre')->name('testwyre');
Route::get("testreport", 'API\TestController@testReport')->name('testreport');

Route::get("test", "API\TestController@test")->name('test');
Route::get("tests2s", "API\TestController@tests2s")->name('tests2s');
Route::get("testtran", "API\TestController@indextransaction")->name('testtran');
Route::get("checkTransaction", "API\TestController@checkTransaction")->name("checkTransaction");
Route::get("checkout_transaction", "API\TestController@checkout_transaction")->name("checkout_transaction");



Route::get("/test/opac/{id}", function ($id) {

	$mid = checkAssignMID("14");
	$url = "https://api.sandbox.openacquiring.com/v1/merchants/" . $mid->merchant_id . "/payment" . "/" . $id;
	$token = base64_encode($mid->client_id . ':' . $mid->client_secret);
	$response = Http::withHeaders(["authorization" => "Basic " . $token])->get($url)->json();
	return $response;
});

Route::get("/test/paycos/{id}", function ($id) {
	$mid = checkAssignMID("12");
	$response = Http::withHeaders(["Authorization" => 'Bearer ' . $mid->merchant_key])->get("https://business-api.paycos.com/api/v2/merchant/payment" . "/" . $id);
	return $response;
});

// * Testing Payo route
Route::get("/payomatix/callback/{id}", "Admin\AdminTestController@payoCallback");

Route::get("/kryptova/{id}", function ($id) {

	$payload = [
		"api_key" => "822|PiQoMv4qpQ42f3Jd6dkELxVdIXngnpb58O0mhJ8w",
		"customer_order_id" => $id
	];
	$response = Http::post("https://hello.kryptova.biz/api/get-transaction-details", $payload)->json();

	return $response;
});

Route::get("milkypay/status/{id}", function ($id) {
	$url = "https://pay.pay-gate.io/payment-invoices/" . $id;
	$response = Http::withBasicAuth("coma_IhYiSRKt02JyfOev", "aSQUGlSNgF-3SxTCCDX5eNcpY1mOFv8oED1amCT_Ubw")->get($url)->json();
	return $response;
});

