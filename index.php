<?php 
require 'vendor/autoload.php'; 


$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
    // echo "Hello, $name";

	// This check prevents access to debug front controllers that are deployed by accident to production servers.
	// Feel free to remove this, extend it, or make something more sophisticated.
	if (isset($_SERVER['MYSQL_SERVER']) {
		$mysqlserver = $_SERVER['MYSQL_SERVER'];
	} else {
		$mysqlserver = '192.168.59.103';
	}


	$link = mysql_connect($mysqlserver, 'admin', '3HAc6NreaLdH');
    // $c = mysql_connect('192.168.59.103', 'admin', 'xuFjyn9NPeff');
	mysql_select_db("resultados");
	$query = "SELECT * FROM resultados WHERE rut=".$name;
	$resultado = mysql_query($query);
	$fila = mysql_fetch_assoc($resultado);
	header("Content-Type: application/json");
	$output = json_decode($fila['datos']);
	echo json_encode($output);
	exit;
	//echo htmlentities($fila['datos']);
});

$app->get('/test', function () {
	$output = json_decode("{estado:'ok'}");
	echo json_encode($output);
});




$app->run();
?>
