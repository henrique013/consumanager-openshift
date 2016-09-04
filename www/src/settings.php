<?php
return [
    'settings' => [

        // Slim settings
        'displayErrorDetails' => getenv('MY_ENV') !== 'production' ? true : false,


        'API' => [
            'host' => getenv('MY_API_HOST'),
            'host_url' => 'http://' . getenv('MY_API_HOST')
        ],


        'SERVER' => [
            'host' => getenv('OPENSHIFT_APP_DNS'),
            'host_url' => 'http://' . getenv('OPENSHIFT_APP_DNS')
        ],
    ],
];
