<?php
date_default_timezone_set('America/Los_Angeles');
ini_set("display_errors", 0);
session_start();

$redis_ttl = 60*60*24;

header("SESS: SHIV-TEST".rand(111,999));
header("x-user: 1");
//header("gwstoken: ".time());

//----------Connecting to Redis server on localhost------//
//$redis = new Redis();
//$redis->connect('127.0.0.1', 6379);


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
$token = (isset($paramArr['gwstoken'])) ? $paramArr['gwstoken'] : $_COOKIE['gws'];
$tenant_id = 0;

//------- Get Application Information ---------------------//
$request_params = $_SERVER['REQUEST_URI'];
$request_params = parse_url($request_params);
parse_str($request_params['query'], $requestArr);
$application_tenant_id = (isset($requestArr['tenant_id'])) ? $requestArr['tenant_id'] : 0; 

error_log(date("Y-m-d H:i:s") . " - Request called Application Tenant-ID = $application_tenant_id, User Tenant-ID = $tenant_id, gwstoken = $token \n",3,"/var/www/html/classlinkproxy/public/auth/log.txt");

//error_log(date("Y-m-d H:i:s") . " - SERVER => \n".print_r($_SERVER,true),3,"/var/www/html/classlinkproxy/public/auth/log.txt");
error_log(date("Y-m-d H:i:s") . " - COOKIE => \n".print_r($_COOKIE,true),3,"/var/www/html/classlinkproxy/public/auth/log.txt");
#error_log(date("Y-m-d H:i:s") . " - SESSION => \n".print_r($_SESSION,true),3,"/var/www/html/classlinkproxy/public/auth/log.txt");
//error_log(date("Y-m-d H:i:s") . " - apache_request_headers => \n".print_r(apache_request_headers(),true),3,"/var/www/html/classlinkproxy/public/auth/log.txt");
//error_log(date("Y-m-d H:i:s") . " - apache_response_headers => \n".print_r(apache_response_headers(),true),3,"/var/www/html/classlinkproxy/public/auth/log.txt");

header("gwstoken: ".$token);

if (!empty($token)) {
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
        //error_log(date("Y-m-d H:i:s") . " - outputArr => \n".print_r($outputArr,true),3,"log.txt");
        if($outputArr['status'] && $outputArr['response']['tenantid']==$application_tenant_id) {
            //$redis->setex($token, $redis_ttl, 1);
            $_SESSION["token"] = $token;
            $_SESSION["valid"] = 1;
            header('HTTP/1.0 200 OK');
            echo "OK";
            exit;
        }
        else if($outputArr['status'] && $outputArr['response']['tenantid']!=$application_tenant_id) {
            $_SESSION['token'] = $token;
            $_SESSION['valid'] = 2;
            header('HTTP/1.0 401 Unauthorized');
            echo "Unauthorized";
            exit;
        }
    }
} 
else if(empty($token)) {
    $_SESSION['token'] = "";
    $_SESSION['valid'] = 0;
    header('HTTP/1.0 401 Unauthorized');
    echo "No Token";
    exit;
}
//header('HTTP/1.0 200 OK');
header('HTTP/1.0 403 Forbidden');
echo "Forbidden";
exit;



?>