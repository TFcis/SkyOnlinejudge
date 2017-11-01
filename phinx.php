<?php

require 'SkyOJ/Core/Database/DB.php';
require 'config/config.php';

$db = $_config['db'];
\SkyOJ\Core\Database\DB::$prefix = $db['tablepre'];
\SkyOJ\Core\Database\DB::initialize($db['query_string'], $db['dbuser'], $db['dbpassword'],$db['dbname']);
\SkyOJ\Core\Database\DB::query('SET NAMES UTF8');
$pdo = \SkyOJ\Core\Database\DB::$pdo;

return ['environments' =>
    [
        'default_database' => 'development',
        'development' => [
            'name' => $db['dbname'],
            'table_prefix' => $db['tablepre'],
            'connection' => $pdo,
        ]
    ]
];