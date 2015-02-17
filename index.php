<?php 
require 'vendor/autoload.php'; 
require_once "recaptchalib.php";

include 'config.php';
include 'opendb.php';

// Configuración de Google captcha
$secret = "6LfbKQITAAAAALLCXUGHHh2nTWgt8gkNXFvUDfId";
$reCaptcha = new ReCaptcha($secret);

$app = new \Slim\Slim();

$app->config('pdo', $pdo);


// Obtener resultados asociados a un RUT
//
// Modo normal del tipo:
// /resultados/20654872?token=567YFH5GHFHG&callback=callbackFunction
//
// Modos test:
// /resultados/12345678?token=1234&mode=test&callback=callbackfunction
//
// Token corresponde a token de Google Captcha
// En modo test se valida token 1234 y sólo se permite consultar por RUT 12345678
//
$app->get('/resultados/:rut', function ($rut) use ($app,$reCaptcha) {
	// callback - función a retornar con JSONP
	$callback = $app->request()->get('callback');

	// token para evitar robots de carga masiva (por defuault utiliza Google captcha)
	$token = $app->request()->get('token');

	// test=true para pruebas (carga local y validación de c/q token)
	$mode = $app->request()->get('mode');
	
	// cóigo de estado para retorno (200=OK)
	$status = 200;

	// Consulta PDO
	$pdo = $app->config('pdo');

	function verificaToken($token, $mode, $reCaptcha) {
		// Modo test, no verifica token
		if ($mode == "test") {
			if ($token=="1234") {
				return true;
			} else {
				return false;
			}
			return true;
		} else {
			// Verifica que token corresponda a un captcha correcto
			$resp = $reCaptcha->verifyResponse(
        		$_SERVER["REMOTE_ADDR"],
        		$token
    		);

    		return ($resp != null && $resp->success);
		}
	}

	if (verificaToken($token,$mode, $reCaptcha)) {

		// Modo de test sólo permite consultar RUT 12345678
		if ($mode=="test" && $rut != "12345678" && $rut != "12345679") {
			$status = 404;
			$output = "En modo test sólo se permite consultar por RUT 12345678";
		} else {
			// Uso de prepared statements para prevenir injección de SQL
			$sentencia = $pdo->prepare("SELECT * FROM resultados WHERE rut=:rut");
			$sentencia->execute(array(':rut' => $rut));

			if ($sentencia->rowCount() > 0) {
				$fila = $sentencia->fetch(PDO::FETCH_ASSOC);
				$output = json_decode($fila['datos']);
			} else {
				$status = 404; // Error - recurso no encontrado
				$output = "Recurso no encontrado";
			}			
		}

	} else {
		$status = 401; // Error de autenticacion - token no valido
		$output = "Token invalido";
	}


	// Chequear si respuesta es vía JSONP (callback) o JSON
	if ($callback) {
		$respuesta = json_decode('{"status":200, "resultado":null}');
		$respuesta->status=$status;
		$respuesta->resultado=$output;
		header("Content-Type: application/javascript");
		echo sprintf("%s(%s)", $callback, json_encode($respuesta));
	} else {
		// Sin callback en caso de error
		if ($status != 200) {
			$app->response->setStatus($status);
			echo sprintf("%s",json_encode($output));
			return;
		}

		// Son callback status OK
		else {
			header("Content-Type: application/json");
			echo sprintf("%s",json_encode($output));
		}
		
	}

	exit;
});


$app->get('/test', function () {
	echo json_encode($_SERVER);
});


$app->run();
?>
