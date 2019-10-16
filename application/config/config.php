<?php
return [
    'modules'    => [
        'index',
        'yadmin'
    ],
    'csrf'       => false,
    'debug'      => false,
    'db'         => require_once APP_PATH . '/config/database.php',
    'uploadUrl'  => 'http://img.woodlsy.com/upload/img?project=' . APP_NAME,
    'uploadPath' => 'http://img.woodlsy.com/'
];