<?php
ini_set("display_errors", 1);

$redis_ttl = 60*60*24;

// error_log('\n\n$_SERVER => '.print_r($_SERVER,true)."==\n\n",3,"/var/www/html/auth/log.txt");
// header("Auth-Status: OK");
// // header('HTTP/1.0 403 Forbidden');
// // header('HTTP/1.0 200 OK');
// echo "true";
// exit;

//Connecting to Redis server on localhost
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

//------- Get Client Information ---------------------//
if(isset($_SERVER['HTTP_X_ORIGINAL_URI'])) {
    $query_params = $_SERVER['HTTP_X_ORIGINAL_URI'];
}
$params = parse_url($query_params);
parse_str($params['query'], $paramArr);

$token = $paramArr['gwstoken'];
$tenant_id = 0;

//------- Get Application Information ---------------------//
$request_params = $_SERVER['REQUEST_URI'];
$request_params = parse_url($request_params);
parse_str($request_params['query'], $requestArr);
$application_tenant_id = $requestArr['tenant_id']; 

error_log(date("Y-m-d H:i:s") . " - Request called Application Tenant-ID = $application_tenant_id, User Tenant-ID = $tenant_id, gwstoken = $token \n",3,"/var/www/html/auth/log.txt");


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
        if($outputArr['status'] && $outputArr['response']['tenantid']==$application_tenant_id) {
            $redis->setex($token, $redis_ttl, 1);
            header('HTTP/1.0 200 OK');
            echo "OK";
            exit;
        }
    }
}
header('HTTP/1.0 403 Forbidden');
echo "Forbidden";
exit;

?>
