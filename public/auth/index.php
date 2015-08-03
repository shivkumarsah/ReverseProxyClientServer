<?php
date_default_timezone_set('America/Los_Angeles');
ini_set("display_errors", 1);
session_start();

$redis_ttl = 60*60*24;

header("gwstoken: ".time());

//----------Connecting to Redis server on localhost------//
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//var_dump($redis);


//------- Get Application Information ---------------------//
$request_params         = parse_url($_SERVER['REQUEST_URI']);
parse_str($request_params['query'], $requestArr);
$application_tenant_id  = (isset($requestArr['tenant_id'])) ? $requestArr['tenant_id'] : 0;

########## Check for existing cookie and validate ##############
if(isset($_COOKIE['gws']) && !empty($_COOKIE['gws'])) {
    echo "in cookie check<br>";
    $token      = $_COOKIE['gws'];
    $isValid    = $redis->exists($token);
    if($isValid) {
        header("gwstoken: ".$token);
        responseOK();
    } else {
        echo "in cookie - query check<br>";
        goto check_gwstoken;
    }
    response403();
} 
else {
    check_gwstoken:
    echo "in query check<br>";
    //--------- Get Client Information ----------------------//
    if(isset($_SERVER['HTTP_REFERER'])) {
        $query_params = $_SERVER['HTTP_REFERER'];
    } 
    else if(isset($_SERVER['HTTP_X_ORIGINAL_URI'])) {
        $query_params = $_SERVER['HTTP_X_ORIGINAL_URI'];
    }
    else {
        $query_params = $_SERVER['REQUEST_URI'];
    }
    $params = parse_url($query_params);
    parse_str($params['query'], $paramArr);

    $token = @$paramArr['gwstoken'];
    $tenant_id = 0;

    $tokenData = validateToken($token);
    if($tokenData){
        if($tokenData['status'] && $tokenData['response']['tenantid']!=$application_tenant_id) {
            header("gwstoken: ".$token);
            $redis->setex($token, $redis_ttl, 1);
            setcookie("gws", $token, time()+$redis_ttl);
            responseOK();
        }
        else if($tokenData['status'] && $tokenData['response']['tenantid']!=$application_tenant_id) {
            response401();
        }
    }
    response403();    
}

############### Function start here #############################################

function responseOK(){
    header('HTTP/1.0 200 OK');
    echo "OK";
    exit;
}

function response403(){
    header('HTTP/1.0 403 Forbidden');
    echo "Forbidden";
    exit;
}

function response401(){
    header('HTTP/1.0 401 Unauthorized');
    echo "Unauthorized";
    exit;
}

function validateToken($token){
    //$apiurl = "https://api.classlink.com/token/$token";
    $apiurl = "https://api.classlink.com/profile";
    $header = array("gwstoken: $token");
    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header
    );
    curl_setopt_array($ch, $optArray);
    $output = curl_exec($ch);
    if (!empty($output)) {
        curl_close($ch);
        $outputArr = json_decode($output, true);
        return $outputArr;
    }
    return false;
}
############### Function end here #############################################

echo "EOA"; exit;
?>
