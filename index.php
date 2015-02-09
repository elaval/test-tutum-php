<?php 
require 'vendor/autoload.php'; 


$app = new \Slim\Slim();
$app->get('/foo', function () use ($app) {
    $callback = $app->request()->get('callback');
    $data = array('foo' => 'bar');
    $app->contentType('application/javascript');
    echo sprintf("%s(%s)", $callback, json_encode($data));
});

$app->get('/resultados/:rut', function ($rut) use ($app) {
	$callback = $app->request()->get('callback');

    // echo "Hello, $name";
    // echo($_SERVER['MYSQL_SERVER']);

	// This check prevents access to debug front controllers that are deployed by accident to production servers.
	// Feel free to remove this, extend it, or make something more sophisticated.
	if (isset($_SERVER['MYSQL_SERVER']) && !empty($_SERVER['MYSQL_SERVER'])) { 
		$mysqlServer = $_SERVER['MYSQL_SERVER'];
	} else {
		echo("DATABASE SERVER NOT SPECIFIED");
	}

	// This check prevents access to debug front controllers that are deployed by accident to production servers.
	// Feel free to remove this, extend it, or make something more sophisticated.
	if (isset($_SERVER['MYSQL_USER']) && !empty($_SERVER['MYSQL_USER'])) { 
		$mysqlUser = $_SERVER['MYSQL_USER'];
	} else {
		echo("Database access not configured (user)");
	}

	// This check prevents access to debug front controllers that are deployed by accident to production servers.
	// Feel free to remove this, extend it, or make something more sophisticated.
	if (isset($_SERVER['MYSQL_PASS']) && !empty($_SERVER['MYSQL_PASS'])) { 
		$mysqlPass = $_SERVER['MYSQL_PASS'];
	} else {
		echo("Database access not configured (pass)");
	}

	$link = mysql_connect($mysqlServer, $mysqlUser, $mysqlPass);
	mysql_select_db("resultados");
	$query = "SELECT * FROM resultados WHERE rut=".$name;
	$resultado = mysql_query($query);
	$fila = mysql_fetch_assoc($resultado);
	header("Content-Type: application/javascript");
	$output = json_decode($fila['datos']);
	echo sprintf("%s(%s)", $callback, json_encode($output));
	exit;
});

$app->get('/test', function () {
	$output = json_decode("{estado:'ok'}");
	echo json_encode($_SERVER);
});




$app->run();
?>
