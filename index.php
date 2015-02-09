<?php $link = mysql_connect('192.168.59.103', 'admin', '3HAc6NreaLdH'); ?>
<?php 
require 'vendor/autoload.php'; 


$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
    // echo "Hello, $name";

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

$app->get('/test', function ($name) {
	$output = json_decode("{estado:'ok'}");
	echo json_encode($output);
});




$app->run();
?>
