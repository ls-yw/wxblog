<?php
return [
    'modules'    => [
        'index',
    ],
    'csrf'       => false,
    'debug'      => false,
    'suffix'     => 'html', // url后缀
    'db'         => require_once APP_PATH . '/config/database.php',
    'uploadUrl'  => 'https://img.woodlsy.com/upload/img?project=' . APP_NAME,
    'uploadPath' => 'https://img.woodlsy.com/'
];