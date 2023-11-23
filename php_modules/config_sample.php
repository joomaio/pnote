<?php  defined('APP_PATH') or die('Invalid config');

return [ 
    'subpath' => '',
    'defaultTheme' => 'blue',
    'adminTheme' => 'admin',
    'secrect' => 'sid',
    'expireSessionDuration' => 60,
    'homeEndpoint' => [
        'fnc' => [
            'get' => 'note.note.list'
        ],
        'filter' => 'my-note',
    ],
    'db' => [
        'host' => '',
        'username' => '',
        'password' => '',
        'database' => '',
        'prefix' => '',
    ],
    'db_test' => [
        'host' => '',
        'username' => '',
        'password' => '',
        'database' => '',
        'prefix' => '',
    ],
    'redirectAfterLogin' => 'milestones',
];
