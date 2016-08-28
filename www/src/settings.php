<?php
return [
    'settings' => [

        // Slim settings
        'displayErrorDetails' => getenv("MY_ENV") !== 'production' ? true : false,


        'APP' => [
            'api' => [
                'host' => getenv("OPENSHIFT_APP_DNS") . '/api-v1'
            ],
            'server' => [
                'host' => getenv("OPENSHIFT_APP_DNS")
            ]
        ],

        'PDO' => [
            'host' => getenv("OPENSHIFT_MYSQL_DB_HOST"),
            'user' => getenv("OPENSHIFT_MYSQL_DB_USERNAME"),
            'password' => getenv("OPENSHIFT_MYSQL_DB_PASSWORD"),
            'dbname' => 'clinica2',
        ],

        'JWT' => [
            'secret' => 'houfbufuobfdbdfubvufdyuvbdf'
        ],

        'Twig' => [
            'context' => [
                'login' => [
                    'api_host' => getenv("OPENSHIFT_APP_DNS") . '/api-v1'
                ],
                'sistema' => [
                    'api_host' => getenv("OPENSHIFT_APP_DNS") . '/api-v1'
                ],
            ]
        ],
    ],
];
