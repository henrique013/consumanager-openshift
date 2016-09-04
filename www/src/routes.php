<?php
// Routes


// WEB

$app->get('[/]', 'App\Route\Auth\Login:get');
$app->get('/auth/login', 'App\Route\Auth\Login:get');
$app->post('/auth/login', 'App\Route\Auth\Login:post');

$app->get('/auth/logout', 'App\Route\Auth\Logout:get');

$app->get('/agenda[/{data:\d\d\d\d-\d\d-\d\d}]', 'App\Route\Agenda:get');
$app->get('/agenda/{data:\d\d\d\d-\d\d-\d\d}/consultorio/{co_id:\d+}', 'App\Route\Agenda\Consultorio:get');
//
//$app->get('/usuario[/{id:\d+}]', 'App\Route\Usuario:get');
//
//$app->get('/paciente[/{id:\d+}]', 'App\Route\Paciente:get');
//
//$app->get('/consulta[/{id:\d+}]', 'App\Route\Consulta:get');
//
//$app->get('/buscas', 'App\Route\Buscas:get');
