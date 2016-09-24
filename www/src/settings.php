<?php
return [
    'settings' => [

        // Slim settings
        'displayErrorDetails' => getenv('MY_ENV') !== 'production' ? true : false,


        'PDO' => [
            'host' => getenv("OPENSHIFT_MYSQL_DB_HOST"),
            'user' => getenv("OPENSHIFT_MYSQL_DB_USERNAME"),
            'password' => getenv("OPENSHIFT_MYSQL_DB_PASSWORD"),
            'dbname' => 'clinica',
        ],


        'JWT' => [
            'secret' => getenv("JWT_SECRET"),
        ],
    ],
];
