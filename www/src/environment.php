<?php
// Environment variable settings


$dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
$dotenv->overload();
$dotenv->required('ENV')->allowedValues(['development', 'production']);
$dotenv->required([
    'DB_HOST',
    'DB_USER',
    'DB_PASS',
    'DB_NAME',
    'APP_API_URL',
    'APP_SERVER_URL',
]);