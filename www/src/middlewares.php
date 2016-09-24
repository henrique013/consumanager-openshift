<?php
// Application middlewares


$app->add(App\Middleware\Layout::class);
$app->add(App\Middleware\Auth::class);
$app->add(App\Middleware\Router::class);
$app->add(App\Middleware\Main::class);