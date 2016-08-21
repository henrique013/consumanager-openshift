<?php
// DIC configuration


$container = $app->getContainer();


// database
$container['PDO'] =
    function (\Slim\Container $c)
    {
        $settings = $c->get('settings')['PDO'];
        $dsn = "mysql:host={$settings['host']};dbname={$settings['dbname']};charset=utf8";
        $conn = new PDO($dsn, $settings['user'], $settings['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        return $conn;
    };
