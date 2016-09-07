<?php
// Routes


// WEB

$app->get('[/]', 'App\Route\Auth\Login:get');
$app->get('/auth/login', 'App\Route\Auth\Login:get');
$app->post('/auth/login', 'App\Route\Auth\Login:post');

$app->get('/auth/logout', 'App\Route\Auth\Logout:get');

$app->get('/agenda[/{data:\d\d\d\d-\d\d-\d\d}]', 'App\Route\Agenda:get');
$app->get('/agenda/{data:\d\d\d\d-\d\d-\d\d}/consultorio/{co_id:\d+}', 'App\Route\Agenda\Consultorio:get');

$app->get('/cadastro/usuario[/{id:\d+}]', 'App\Route\Cadastro\Usuario:get');
$app->post('/cadastro/usuario', 'App\Route\Cadastro\Usuario:post');
$app->put('/cadastro/usuario/{id:\d+}', 'App\Route\Cadastro\Usuario:put');
$app->delete('/cadastro/usuario/{id:\d+}', 'App\Route\Cadastro\Usuario:delete');

$app->get('/cadastro/paciente[/{id:\d+}]', 'App\Route\Cadastro\Paciente:get');
$app->post('/cadastro/paciente', 'App\Route\Cadastro\Paciente:post');
$app->put('/cadastro/paciente/{id:\d+}', 'App\Route\Cadastro\Paciente:put');
$app->delete('/cadastro/paciente/{id:\d+}', 'App\Route\Cadastro\Paciente:delete');

$app->get('/cadastro/consulta/consultorio/{co_id:\d+}/{data:\d\d\d\d-\d\d-\d\d}/{horario:\d\d-\d\d}', 'App\Route\Cadastro\Consulta:get');
$app->get('/cadastro/consulta[/{id:\d+}]', 'App\Route\Cadastro\Consulta:get');
$app->post('/cadastro/consulta', 'App\Route\Cadastro\Consulta:post');
$app->put('/cadastro/consulta/{id:\d+}', 'App\Route\Cadastro\Consulta:put');
$app->delete('/cadastro/consulta/{id:\d+}', 'App\Route\Cadastro\Consulta:delete');

$app->get('/cadastro/consulta/pacientes', 'App\Route\Cadastro\Consulta\Pacientes:get');

$app->get('/buscas', 'App\Route\Buscas:get');