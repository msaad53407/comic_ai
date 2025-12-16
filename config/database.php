<?php

use App\Core\Env;

return [
    'driver' => 'mysql',
    'host' => Env::get('DB_HOST', '127.0.0.1'),
    'dbname' => Env::get('DB_NAME', 'comic_ai'),
    'username' => Env::get('DB_USER', 'root'),
    'password' => Env::get('DB_PASS', ''),
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
