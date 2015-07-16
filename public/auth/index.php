<?php
ini_set("display_errors", 1);

$redis_ttl = 60*60*24;

// error_log('\n\n$_SERVER => '.print_r($_SERVER,true)."==\n\n",3,"/var/www/html/auth/log.txt");
// header("Auth-Status: OK");
// header('HTTP/1.0 403 Forbidden');
// header('HTTP/1.0 200 OK');
// echo "true";
// exit;

//Connecting to Redis server on localhost
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
//echo "Connection to server sucessfully";


if(isset($_SERVER['HTTP_X_ORIGINAL_URI'])) {
	$query_params = $_SERVER['HTTP_X_ORIGINAL_URI'];
} else {
	$query_params = $_SERVER['REQUEST_URI'];
}
$params = parse_url($query_params);
parse_str($params['query'], $paramArr);

$token = $paramArr['gwstoken'];
error_log(date("Y-m-d H:i:s") . " - Request called from => $token \n",3,"/var/www/html/auth/log.txt");

if (!empty($token)) {
    $apiurl = "https://api.classlink.com/token/$token";
    $header = array("token: $token");
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
        if($outputArr['status']) {
        	$redis->setex($token, $redis_ttl, 1);
        	header('HTTP/1.0 200 OK');
        	echo "OK";
        	exit;
        }
        //echo "<pre>";
	    //print_r($outputArr);
	    //exit;
    }
}
header('HTTP/1.0 403 Forbidden');
echo "Forbidden";
exit;





?>
