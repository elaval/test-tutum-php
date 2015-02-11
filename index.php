<?php 
require 'vendor/autoload.php'; 

include 'config.php';
include 'opendb.php';

$app = new \Slim\Slim();

$app->config('pdo', $pdo);

$app->get('/resultados/:rut', function ($rut) use ($app) {
	$callback = $app->request()->get('callback');
	
	// Consulta PDO
	$pdo = $app->config('pdo');

	// Uso de prepared statements para prevenir injección de SQL
	$sentencia = $pdo->prepare("SELECT * FROM resultados WHERE rut=:rut");
	$sentencia->execute(array(':rut' => $rut));

	$fila = $sentencia->fetch(PDO::FETCH_ASSOC);

	$output = json_decode($fila['datos']);

	if ($callback) {
		header("Content-Type: application/javascript");
		echo sprintf("%s(%s)", $callback, json_encode($output));
	} else {
		header("Content-Type: application/json");
		echo sprintf("%s",json_encode($output));
	}

	exit;
});

$app->get('/test', function () {
	echo json_encode($_SERVER);
});

$app->run();
?>
