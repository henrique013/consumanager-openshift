<?php
return [
    'settings' => [

        // Slim settings
        'displayErrorDetails' => getenv("ENV") === 'development' ? true : false,
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header


        'APP' => [
            'api' => [
                'host' => getenv("APP_API_HOST")
            ],
            'server' => [
                'host' => getenv("APP_SERVER_HOST")
            ]
        ],

        'PDO' => [
            'host' => getenv("DB_HOST"),
            'user' => getenv("DB_USER"),
            'password' => getenv("DB_PASS"),
            'dbname' => getenv("DB_NAME"),
        ],

        'JWT' => [
            'secret' => getenv("JWT_SECRET")
        ],

        'Twig' => [
            'context' => [
                'login' => [
                    'api_host' => getenv("APP_API_HOST")
                ],
                'sistema' => [
                    'api_host' => getenv("APP_API_HOST")
                ],
            ]
        ],
    ],
];
