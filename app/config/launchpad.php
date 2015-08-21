<?php
return array(
    'login_required'    => 0,
    'client_id'         => 'c1435056532565575a2ef69eaf3a8e637bab5b4bea28bd',
    'client_secret'     => '64d421e7979bad18a67e54e431bb27c0',
    'access_token_url'  => 'https://launchpad.classlink.com/oauth2/token',
    'launcpad_url'      => 'https://launchpad.classlink.com/oauth2/auth/?',
    'scopes'            => 'profile',
    //'redirecturl'       => 'http://classlinkproxy.icreondemoserver.com:443/users/lunchpadtoken'
    'redirecturl'       => (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' ).$_SERVER['HTTP_HOST'].'/users/lunchpadtoken'
);