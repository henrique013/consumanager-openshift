<?php
// Application middlewares


$app->add(App\Middleware\Session::class);
$app->add(App\Middleware\Main::class);