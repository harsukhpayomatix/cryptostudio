<?php 

namespace App\Transformers;

use App\PaymentAPI;
use Illuminate\Http\Request;

class ApiResponse
{
    // ============================================= 
    /* menthod : success
    * @param  : 
    * @Description : success json response
    */// ==============================================
    public static function success($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'] ?? 1,
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'] ?? 'Success',
        ];
        
        PaymentAPI::create($api_log);
        
        return response()->json($output);
    }

    // ================================================
    /* method : redirect
    * @param  : 
    * @description : json response for redirect
    */// ===============================================
    public static function redirect($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string) $input['status'] ?? 7,
            'responseMessage' => $input['reason'] ?? 'Please redirect to 3dsUrl.',
            '3dsUrl' => $input['redirect_3ds_url'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'] ?? 'Success',
        ];
        
        PaymentAPI::create($api_log);

        return response()->json($output);
    }

    // =============================================
    /* menthod : fail
    * @param  : 
    * @Description : fail json response
    */// ==============================================
    public static function fail($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'] ?? 0,
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'] ?? 'Success',
        ];
        
        PaymentAPI::create($api_log);

        return response()->json($output);
    }

    // ============================================= 
    /* menthod : pending
    * @param  : 
    * @Description : pending json response
    */// ==============================================
    public static function pending($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'] ?? 2,
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'] ?? 'Success',
        ];
        
        PaymentAPI::create($api_log);
        
        return response()->json($output);
    }

    // ================================================
    /* method : blocked
    * @param  : 
    * @description : json response for blocked request
    */// ===============================================
    public static function blocked($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'] ?? 5,
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'] ?? 'Unauthorized',
        ];
        
        PaymentAPI::create($api_log);

        return response()->json($output);
    }

    // ================================================
    /* method : unauthorised
    * @param  : 
    * @description : json response for unauthorised or invalid request
    */// ===============================================
    public static function unauthorised($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'] ?? 6,
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'] ?? 'Unauthorized',
        ];
        
        PaymentAPI::create($api_log);

        return response()->json($output);
    }

    // ================================================
    /* method : status
    * @param  : 
    * @description : json response for status api
    */// ===============================================
    public static function status($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'],
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => 'status',
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => 'Get transaction detail successfully',
        ];

        PaymentAPI::create($api_log);

        return response()->json($output);
    }

    // ================================================
    /* method : webhook
    * @param  : 
    * @description : json response for webhook api
    */// ===============================================
    public static function webhook($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'],
            'responseMessage' => $input['reason'],
            'data'=> self::getTransactionDetails($input)
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => 'webhook',
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => 'Webhook sent successfully',
        ];
        
        PaymentAPI::create($api_log);

        return $output;
    }

    // ================================================
    /* method : returnUrl
    * @param  : 
    * @description : response for return_url
    */// ===============================================
    public static function returnUrl($input=[])
    {
        $request = \Request::all();

        $order_id = $input['order_id'] ?? null;
        $customer_order_id = $input['customer_order_id'] ?? null;

        $output = 'responseCode='.$input['status'].'&responseMessage='.$input['reason'].'&order_id='.$order_id.'&customer_order_id='.$customer_order_id;

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => 'return',
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => $output,
            'message' => 'return_url sent successfully',
        ];

        PaymentAPI::create($api_log);
        if (isset($input['response_url']) && parse_url($input['response_url'] ?? null, PHP_URL_QUERY)) {
            return $input['response_url'] ?? null .'&'.$output;
        } else {
            return $input['response_url'] ?? null.'?'.$output;
        }
    }

    // ================================================
    /* method : notFound
    * @param  : 
    * @description : json response for not found status api
    */// ===============================================
    public static function notFound($input=[])
    {
        $request = \Request::all();

        $output = [
            'responseCode' => (string)$input['status'],
            'responseMessage' => $input['reason'],
        ];

        // log into database
        $api_log = [
            'user_id' => $input['user_id'] ?? null,
            'order_id' => $input['order_id'] ?? null,
            'session_id' => $input['session_id'] ?? null,
            'email' => $input['email'] ?? null,
            'type' => 'status',
            'type' => $input['is_request_from_vt'] ?? null,
            'method' => \Request::url(),
            'request' => json_encode($request),
            'ip' => \Request::ip(),
            'response' => json_encode($output),
            'message' => $input['reason'],
        ];
        
        PaymentAPI::create($api_log);

        return response()->json($output);
    }

    // ================================================
    /* method : getTransactionDetails
    * @param  : 
    * @description : get array of data which we are required
    */// ===============================================
    public static function getTransactionDetails($input)
    {
        return [
            'transaction' => [
                'order_id' => $input['order_id'] ?? null,
                'customer_order_id' => $input['customer_order_id'] ?? null,
                'amount' => $input['amount'] ?? null,
                'currency' => $input['currency'] ?? null,
            ],
            'client' => [
                'first_name' => $input['first_name'] ?? null,
                'last_name' => $input['last_name'] ?? null,
                'email' => $input['email'] ?? null,
                'phone_no' => $input['phone_no'] ?? null,
                'address' => $input['address'] ?? null,
                'zip' => $input['zip'] ?? null,
                'city' => $input['city'] ?? null,
                'state' => $input['state'] ?? null,
                'country' => $input['country'] ?? null,
            ],
            'card' => [
                'card_no' => $input['card_no'] ?? null,
                'ccExpiryMonth' => $input['ccExpiryMonth'] ?? null,
                'ccExpiryYear' => $input['ccExpiryYear'] ?? null,
                'cvvNumber' => $input['cvvNumber'] ?? null,
            ],
        ];
    }
}
