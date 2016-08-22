<?php
// Routes


// WEB

$app->get('[/]', 'App\Route\Login:get');
$app->get('/login', 'App\Route\Login:get');


// API

$app->post('/api-v1/auth/login', 'App\Route\API\Auth\Login:post');
