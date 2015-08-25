<?php
return array(
    'base_url'          => '{{baseUrl}}', //'classlinkproxy.icreondemoserver.com',
    'base_port'         => '{{baseUrlPort}}', //'443',
    'listen_port'       => '{{listenPort}}', //'443',
    'config_path'       => '{{confPath}}', //'/var/www/html/classlinkproxy/configs',
    'nginx_service_path'=> '{{nginxPath}}', //'/var/www/html/php_root',
    'nginx_protocol'    => '{{baseProtocol}}',
    'auth_path'         => '{{authPath}}', //realpath($_SERVER['DOCUMENT_ROOT'].'/auth/nginx-auth.lua'),
    'auth_url'          => (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST'].'/auth/'
//  'auth_url'          => 'http://localhost:9001/'
);
