<?php $link = mysql_connect('192.168.59.103', 'admin', 'xuFjyn9NPeff'); ?>
<?php 
require 'vendor/autoload.php'; 


$app = new \Slim\Slim();
$pdo = new PDO('mysql:host=192.168.59.103;dbname=datos', 'admin', 'xuFjyn9NPeff');

$app->get('/hello/:name', function ($name) {
    // echo "Hello, $name";

    // $c = mysql_connect('192.168.59.103', 'admin', 'xuFjyn9NPeff');
	mysql_select_db("datos");
	$query = "SELECT * FROM resultados WHERE id=".$name;
	$resultado = mysql_query($query);
	$fila = mysql_fetch_assoc($resultado);
	header("Content-Type: application/json");
	$output = json_decode($fila['datos']);
	echo json_encode($output);
	exit;
	//echo htmlentities($fila['datos']);
});




$app->run();
?>
