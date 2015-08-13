<?php
return array(
    'base_url'          => 'betaproxyadmin.oneroster.com',
    'base_port'         => '8080',
    'listen_port'       => '8080',
    'config_path'       => '/var/www/html/proxyadmin/configs',
    'nginx_service_path'=> 'systemctl restart nginx.service',
    'auth_url'          => 'http://localhost:80/auth/'
);