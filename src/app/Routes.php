<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/api/seguridad', function(RouteCollectorProxy $group){
	$group->post('/autenticar', 'App\controller\SeguridadController:autenticar');
});

$app->group('/api/parametro', function(RouteCollectorProxy $group){
	$group->post('/listar', 'App\controller\ParametroController:listar');
});