<?php namespace App\services;
use Psr\Container\ContainerInterface;
use App\services\JwtTokenSecurity;
use App\infraestructure\SeguridadMapper;

$container->set('container/seguridad/autenticar', function(ContainerInterface $containerInterface){
	$solicitud = json_decode(file_get_contents('php://input'), true);
	$response['status'] = 1;
	$response['text'] = null;
	try{
		$jwt = new JwtTokenSecurity();
		$seguridad = new SeguridadMapper($containerInterface);
		$login = $seguridad->login($solicitud);
		if(count($login) > 0){
			$solicitud['idUsuario'] = (int)$login[0]->id_usuario;
			$usuario['usuario'] = $seguridad->usuario($solicitud);
			$roles = $seguridad->perfil($solicitud);

			$array = array();
			foreach ($roles as $value) {
				$array[] = $value->descripcion;
			}

			$solicitud['perfil'] = $array;
			$token = $jwt->token($solicitud);
			$usuario['token'] = $token['token'];
			$response['data'] = $usuario;
			return $response;
		}else{
			$response['status'] = 0;
			$response['text'] = "No se encontró al usuario con las credenciales ingresadas";
			return $response;
		}
	}catch(Exception $ex){
		return json_decode('{"text": '.$ex->getMessage().', "status": "0"}');
	}
});