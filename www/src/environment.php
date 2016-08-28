<?php
// Environment variable settings


if (in_array(getenv("MY_ENV"), [false, 'development'])) {

    $dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
    $dotenv->overload();
    $dotenv->required('MY_ENV')->allowedValues(['development', 'staging', 'production']);
    $dotenv->required([
        'OPENSHIFT_MYSQL_DB_HOST',
        'OPENSHIFT_MYSQL_DB_USERNAME',
        'OPENSHIFT_MYSQL_DB_PASSWORD',
        'OPENSHIFT_APP_DNS',
    ]);
}
