<?php

/*
 * |--------------------------------------------------------------------------
 * | Application & Route Filters
 * |--------------------------------------------------------------------------
 * |
 * | Below you will find the "before" and "after" events for the application
 * | which may be used to do any work before or after a request into your
 * | application. Here you may also register your custom route filters.
 * |
 */

App::before(function($request) {
    if (Session::has('access_token')) {
        $request->headers->set('access_token', Session::get('access_token'));
    }
    $data = file_get_contents ("../public/install/installationStatus.json");
    $json = json_decode($data, true);
    if(isset($json)){
        if($json['Installation']['status']==0){

            if( file_exists ( "../public/install/install.php" ) ) {//die('file exist');
                header('Location: /install/install.php');
                exit;
            }

        }
    }
});

App::after(function ($request, $response) {
    //
});

/*
 * |--------------------------------------------------------------------------
 * | Authentication Filters
 * |--------------------------------------------------------------------------
 * |
 * | The following filters are used to verify that the user of the current
 * | session is logged into this application. The "basic" filter easily
 * | integrates HTTP Basic authentication for quick, simple checking.
 * |
 */

Route::filter('auth', function () {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            asd("ok here Route::filter('auth')");
            return Redirect::guest('users/login');
        }
    }
});

Route::filter('admin-auth', function () {
    
    $headerinfo = Request::header();
    $sessioninfo = Session::all();
    $sessionid = Session::getId();
    if (! isset($headerinfo['access-token'][0]) || ! isset($sessioninfo[$sessionid]) || ($sessioninfo[$sessionid] != $headerinfo['access-token'][0])) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return Redirect::guest('users/login');
        }
    }
});

Route::filter('rest-api-endpoints-auth', function () {
    $responseCodeDetails = \Config::get("appvals.api_response_code");
    $returnData = array(
        'status' => 0,
        'response_code' => 401,
        'response_message' => 'Failed from Authorization',
        'errors' => $responseCodeDetails[401]
    );
    $headerinfo = Request::header();
    $sessioninfo = Session::all();
    $sessionid = Session::getId();
    $timestamp = time();
    
    if (empty($headerinfo['apikey'][0])) {
        $returnData['response_message'] = "Empty Api-Key";
        return Response::json($returnData, $returnData['response_code']);
    }
    if (empty($headerinfo['nounce'][0])) {
        $returnData['response_message'] = "Empty nounce";
        return Response::json($returnData, $returnData['response_code']);
    }
    if (empty($headerinfo['signature'][0])) {
        $returnData['response_message'] = "Empty signature";
        return Response::json($returnData, $returnData['response_code']);
    }
    if (empty($headerinfo['timestamp'][0])) {
        $returnData['response_message'] = "Empty timestamp";
        return Response::json($returnData, $returnData['response_code']);
    }
    
    if ($timestamp - $headerinfo['timestamp'][0] > 60) {
        
        $returnData['response_message'] = "Empty timestamp expired";
        return Response::json($returnData, $returnData['response_code']);
    }
    if (empty($headerinfo['calluri'][0])) {
        $returnData['response_message'] = "Empty calling uri";
        return Response::json($returnData, $returnData['response_code']);
    }
    
    $url = $headerinfo['calluri'][0];
    $method = Request::method();
    $developer = new Openroster\Storage\Developer\EloquentDeveloperRepository(new Developer());
    $apiDetails = $developer->getFirstBy('api_key', $headerinfo['apikey'][0]);
    
    $base_string = $method . urlencode($url) . "nounce" . $headerinfo['nounce'][0] . "timestamp" . $headerinfo['timestamp'][0];
    
    // echo "base=".$base_string;echo "base=".$base_string;echo "base=".$base_string;
    // create key
    $key = urlencode($headerinfo['apikey'][0]) . "" . urlencode($apiDetails['api_secret']);
    
    // create signature
    $signature = urlencode(base64_encode(hash_hmac("sha1", $base_string, $key, true)));
    
    if ($headerinfo['signature'][0] != $signature) {
        $returnData['response_message'] = "Invalid Signature";
        return Response::json($returnData, $returnData['response_code']);
    }
});

Route::filter('admin-api-auth', function () {
    $responseCodeDetails = \Config::get("appvals.api_response_code");
    $apiKey = \Config::get("domainapikey.domain_api_key");
    $returnData = array(
        'status' => 0,
        'response_code' => 401,
        'response_message' => 'Failed from Authorization',
        'errors' => $responseCodeDetails[401]
    );
    $headerinfo = getallheaders();
    // $headerinfo = Request::header();
    
    // $headerApiKey = explode("ADMIN_API_KEY", $headerinfo);
    
    if (empty($headerinfo['ADMIN_API_KEY'])) {
        $returnData['response_message'] = "Empty Api-Key";
        return Response::json($returnData, $returnData['response_code']);
    }
    
    if ($headerinfo['ADMIN_API_KEY'] != $apiKey) {
        $returnData['response_message'] = "Invalid Api-Key";
        return Response::json($returnData, $returnData['response_code']);
    }
});

Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
 * |--------------------------------------------------------------------------
 * | Guest Filter
 * |--------------------------------------------------------------------------
 * |
 * | The "guest" filter is the counterpart of the authentication filters as
 * | it simply checks that the current user is not logged in. A redirect
 * | response will be issued if they are, which you may freely change.
 * |
 */

Route::filter('guest', function () {
    
    if (Auth::check())
        return Redirect::to('/');
});

/*
 * |--------------------------------------------------------------------------
 * | CSRF Protection Filter
 * |--------------------------------------------------------------------------
 * |
 * | The CSRF filter is responsible for protecting your application against
 * | cross-site request forgery attacks. If this special token in a user
 * | session does not match the one given in this request, we'll bail.
 * |
 */

Route::filter('csrf', function () {
    if (Session::token() !== Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException();
    }
});
