<?php
// Application middlewares


$app->add(App\Middleware\Auth::class);
$app->add(App\Middleware\Main::class);
