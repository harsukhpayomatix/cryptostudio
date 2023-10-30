<?php

namespace App\Http\Controllers\API;

use App\WebsiteUrl;
use App\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Repo\TestTransactionRepo;
use App\Transformers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestDirectApiController extends Controller
{
    protected $test_transaction_repo;

    // ================================================
    /* method : __construct
     * @param  :
     * @Description : Create a new controller instance.
     */// ==============================================
    public function __construct()
    {
        $this->test_transaction_repo = new TestTransactionRepo;
    }

    // ================================================
    /* method : store
     * @param  :
     * @Description : create transaction API $request
     */// ==============================================
    public function store(Request $request)
    {
        // only accept parameters that are available
        $request_only = config('required_field.fields');

        $input = $request->only($request_only);
        $api_key = $request->bearerToken();

        // if api_key is not included in request
        if (empty($api_key)) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, please pass API Key in Header';
            return ApiResponse::unauthorised($input);
        }

        // validate api_key
        $user = DB::table('users')
            ->where('api_key', $api_key)
            ->where('is_active', '1')
            ->whereNull('deleted_at')
            ->first();

        // if api_key is not valid or user deleted
        if (empty($user)) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, Invalid API Key or merchant deleted';
            return ApiResponse::unauthorised($input);
        }

        // user IP and domain and request from API
        $input['payment_type'] = $request->payment_type ?? 'card';
        $input['request_from_ip'] = $request->ip();
        $input['request_origin'] = $_SERVER['HTTP_HOST'];
        $input['is_request_from_vt'] = 'TESTAPI';
        $input['user_id'] = $user->id;
        $input['payment_gateway_id'] = 1;

        // check only user assigned gateway is active
        $check_assign_mid = checkAssignMID($user->mid);

        if ($check_assign_mid == false) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, Your account is disabled.';
            return ApiResponse::unauthorised($input);
        }

        $validator = Validator::make($input, [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:2|max:100',
            'address' => 'required|min:2|max:250',
            'country' => 'required|max:2|min:2|regex:(\b[A-Z]+\b)',
            'state' => 'required|min:2|max:250',
            'city' => 'required|min:2|max:250',
            'zip' => 'required|min:2|max:250',
            'ip_address' => 'required|ip',
            'email' => 'required|email',
            'phone_no' => 'required|min:5|max:20',
            'amount' => 'required',
            'currency' => 'required|max:3|min:3|regex:(\b[A-Z]+\b)',
            'card_no' => 'required|min:12|max:24',
            'ccExpiryMonth' => 'required|numeric|min:1|max:12',
            'ccExpiryYear' => 'required|numeric|min:2022|max:2045',
            'cvvNumber' => 'required|numeric|min:0|max:9999',
            'response_url' => 'required|url',
            'webhook_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $input['status'] = '6';
            $input['reason'] = $errors[0] ?? 'Unauthorised request, please check your request payload.';
            return ApiResponse::unauthorised($input);
        }

        // check ip_restriction
        if ($user->is_ip_remove == '0') {
            $getIPData = WebsiteUrl::where('user_id', $user->id)
                ->where('ip_address', $this->getClientIP())
                ->first();

            // if IP is not added on the IP whitelist
            if (empty($getIPData)) {
                $input['status'] = '6';
                $input['reason'] = 'Unauthorised request, please whitelist this IP address(' . $this->getClientIP() . ') in your dashboard.';
                return ApiResponse::unauthorised($input);
            }

            // if IP is not approved
            if ($getIPData->is_active == '0') {
                $input['status'] = '6';
                $input['reason'] = 'Unauthorised request, IP address(' . $this->getClientIP() . ') approval pending.';
                return ApiResponse::unauthorised($input);
            }
            $input["website_url_id"] = $getIPData->id;
        }

        // send request to transaction repo class
        $return_input = $this->test_transaction_repo->store($input, $user, $check_assign_mid);

        // if return_input is null
        if (empty($return_input)) {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, request validation failed.';
            return ApiResponse::unauthorised($input);
        }

        $input = array_merge($input, $return_input);

        // transaction requires 3ds redirect
        if ($return_input['status'] == '7') {
            return ApiResponse::redirect($input);
            // transaction success
        } elseif ($return_input['status'] == '1') {
            return ApiResponse::success($input);
            // transaction pending
        } elseif ($return_input['status'] == '2') {
            return ApiResponse::pending($input);
            // transaction fail
        } elseif ($return_input['status'] == '0') {
            return ApiResponse::fail($input);
            // transaction blocked
        } elseif ($return_input['status'] == '5') {
            return ApiResponse::unauthorised($return_input);
            // no response
        } else {
            $input['status'] = '6';
            $input['reason'] = 'Unauthorised request, request validation failed.';
            return ApiResponse::unauthorised($input);
        }
    }

    // ================================================
    /* method : getClientIP
     * @param  : 
     * @description : get client ip address perfectly
     */// ===============================================
    public function getClientIP()
    {
        $ip_address = '';

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = 'UNKNOWN';
        }

        return $ip_address;
    }


    public function demoApi(Request $request)
    {
        $payload = $request->validate([
            "name" => "required|min:2|max:60",
            "city" => "required|min:2"
        ]);

        return [
            "name" => $payload["name"],
            "city" => $payload["city"],
            "Request Url" => $request->fullUrl(),
            "Request Domain" => $request->getHost(),
            "Request IP" => $request->ip(),
            "Http Method" => $request->method(),
            "headers meta" => $request->headers->all(),
        ];
    }
}