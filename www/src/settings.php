<?php
return [
    'settings' => [

        'displayErrorDetails' => getenv("ENV") === 'development' ? true : false,
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Application settings
        'APP' => [
            'api' => [
                'url' => getenv("APP_API_URL")
            ],
            'server' => [
                'url' => getenv("APP_SERVER_URL")
            ]
        ],

        // Database settings
        'PDO' => [
            'host' => getenv("DB_HOST"),
            'user' => getenv("DB_USER"),
            'password' => getenv("DB_PASS"),
            'dbname' => getenv("DB_NAME"),
        ],
    ],
];