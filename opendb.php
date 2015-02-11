<?php

	// Override config if parameters are set by environment variables
	if (isset($_SERVER['MYSQL_SERVER']) && !empty($_SERVER['MYSQL_SERVER'])) { 
		$dbhost = $_SERVER['MYSQL_SERVER'];
	} 

	if (isset($_SERVER['MYSQL_USER']) && !empty($_SERVER['MYSQL_USER'])) { 
		$dbuser = $_SERVER['MYSQL_USER'];
	}

	if (isset($_SERVER['MYSQL_PASS']) && !empty($_SERVER['MYSQL_PASS'])) { 
		$dbpass = $_SERVER['MYSQL_PASS'];
	} 

	if (isset($_SERVER['MYSQL_DB']) && !empty($_SERVER['MYSQL_DB'])) { 
		$dbname = $_SERVER['MYSQL_DB'];
	} 

	// PDO - Mysql connecion
	try {
	    $pdo = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);
	} catch (PDOException $e) {
	    print "¡Error!: " . $e->getMessage() . "<br/>";
	    die();
	}

?>