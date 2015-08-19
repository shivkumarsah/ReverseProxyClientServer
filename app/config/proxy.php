<?php
return array(
    'base_url'              => 'classlinkproxy.icreondemoserver.com',
    'base_port'             => '443',
    'listen_port'           => '443',
    'config_path'           => '/var/www/html/classlinkproxy/configs',
    'nginx_service_path'    => '/var/www/html/php_root',
    'auth_url'              => (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST'].'/auth/'
//  'auth_url'              => 'http://localhost:9001/'
);
