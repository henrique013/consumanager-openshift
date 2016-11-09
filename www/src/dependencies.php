<?php
// DIC configuration


$container = $app->getContainer();


$container['twig'] =
    function ()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../public');

        $twig = new Twig_Environment($loader);

        return $twig;
    };


$container['PDO'] =
    function (\Slim\Container $c)
    {
        $settings = $c->get('settings')['PDO'];
        $dsn = "pgsql:host={$settings['host']};dbname={$settings['dbname']};";
        $conn = new PDO($dsn, $settings['user'], $settings['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    };


$container['jwt_token'] =
    function ()
    {
        if (!isset($_COOKIE['AUTH_TOKEN']) || !$_COOKIE['AUTH_TOKEN'])
        {
            return false;
        }

        try
        {
            $token = (new Lcobucci\JWT\Parser())->parse($_COOKIE['AUTH_TOKEN']);
        }
        catch (Exception $e)
        {
            $token = false;
        }

        return $token;
    };