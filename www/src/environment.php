<?php
// Environment variable settings


if (in_array(getenv("MY_ENV"), [false, 'development']))
{
    $dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
    $dotenv->overload();
    $dotenv->required('MY_ENV')->allowedValues(['development', 'staging', 'production']);
    $dotenv->required([
        'MY_API_HOST'
    ]);
}
