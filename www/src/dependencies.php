<?php
// DIC configuration


$container = $app->getContainer();


// ATENÇÃO: ao usar essa dependência a sessão já deve ter sido startada.
$container['flash'] =
    function ()
    {
        return new \Slim\Flash\Messages();
    };


$container['twig'] =
    function ()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');

        $twig = new Twig_Environment($loader);

        return $twig;
    };


$container['API'] =
    function (Slim\Container $c)
    {
        $debug = $c->get('settings')['displayErrorDetails'];
        $api_settings = $c->get('settings')['API'];


        $options = [
            'base_uri' => $api_settings['host_url']
        ];

        if ($debug && isset($_COOKIE['XDEBUG_SESSION']))
        {
            $options['headers'] = [
                'Cookie' => 'XDEBUG_SESSION=XDEBUG_ECLIPSE'
            ];
        }

        $client = new GuzzleHttp\Client($options);

        return $client;
    };