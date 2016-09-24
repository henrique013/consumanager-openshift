<?php
// Routes


// AUTH

$app->get('/auth/login', 'App\Route\Auth\Login:get');
$app->post('/auth/login', 'App\Route\Auth\Login:post');

$app->get('/auth/logout', 'App\Route\Auth\Logout:get');


// AGENDA

$app->get('/agenda[/{data:\d\d\d\d-\d\d-\d\d}]', 'App\Route\Agenda:get');
$app->get('/agenda/{data:\d\d\d\d-\d\d-\d\d}/consultorio/{co_id:\d+}', 'App\Route\Agenda\Consultorio:get');


// BUSCA

$app->get('/busca/pacientes', 'App\Route\Busca\Pacientes:get');

$app->get('/busca/usuarios', 'App\Route\Busca\Usuarios:get');

$app->get('/busca/consultas', 'App\Route\Busca\Consultas:get');


// AJAX

$app->get('/ajax/cadastro/usuario[/{id:\d+}]', 'App\Route\Ajax\Cadastro\Usuario:get');
$app->post('/ajax/cadastro/usuario', 'App\Route\Ajax\Cadastro\Usuario:post');
$app->put('/ajax/cadastro/usuario/{id:\d+}', 'App\Route\Ajax\Cadastro\Usuario:put');
$app->delete('/ajax/cadastro/usuario/{id:\d+}', 'App\Route\Ajax\Cadastro\Usuario:delete');

$app->get('/ajax/cadastro/paciente[/{id:\d+}]', 'App\Route\Ajax\Cadastro\Paciente:get');
$app->post('/ajax/cadastro/paciente', 'App\Route\Ajax\Cadastro\Paciente:post');
$app->put('/ajax/cadastro/paciente/{id:\d+}', 'App\Route\Ajax\Cadastro\Paciente:put');
$app->delete('/ajax/cadastro/paciente/{id:\d+}', 'App\Route\Ajax\Cadastro\Paciente:delete');

$app->get('/ajax/cadastro/consulta/consultorio/{co_id:\d+}/{dt:\d\d\d\d-\d\d-\d\d}/{hr:\d\d-\d\d}', 'App\Route\Ajax\Cadastro\Consulta:get');
$app->post('/ajax/cadastro/consulta', 'App\Route\Ajax\Cadastro\Consulta:post');
$app->put('/ajax/cadastro/consulta/{id:\d+}', 'App\Route\Ajax\Cadastro\Consulta:put');
$app->delete('/ajax/cadastro/consulta/{id:\d+}', 'App\Route\Ajax\Cadastro\Consulta:delete');

$app->get('/ajax/cadastro/consulta/pacientes', 'App\Route\Ajax\Cadastro\Consulta\Pacientes:get');