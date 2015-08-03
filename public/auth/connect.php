<?php
ini_set("display_errors", 1);
   //Connecting to Redis server on localhost
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   echo "Connection to server sucessfully";
   //store data in redis list
   $redis->lpush("tutorial-list", "Redis");
   $redis->lpush("tutorial-list", "Mongodb");
   $redis->lpush("tutorial-list", "Mysql");
   // Get the stored data and print it
   $arList = $redis->lrange("tutorial-list", 0 ,5);
   echo "Stored string in redis:: ";
   print_r($arList);
?>
