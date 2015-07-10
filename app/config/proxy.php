<?php
return array(
    'base_url' => 'betaproxyadmin.oneroster.com',
    'listen_port' => '8080',
    'config_path'=>'/var/www/html/proxyadmin/configs',
    //'nginx_service_path'=>'/var/www/html/php_root',
    'nginx_service_path'=>'systemctl restart nginx.service'
);