<?php
	session_start();
	
	//ini_set("memory_limit","256M");	
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	//db connection stuff
	$connect1 = "127.0.0.1";
	$connect2 = "user";
	$connect3 = "password";
	$db_name = "database";
	
	try{	
		$pdo = new PDO("mysql:host=$connect1;dbname=$db_name",$connect2,$connect3,
    				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    					  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    					  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}catch(PDOException $e){  
    	echo $e->getMessage();
    	exit();
    }
	
	//the MYSQLI way...moving on...
	//$mysqli = new mysqli($connect1, $connect2, $connect3, $db_name);
	//$mysqli->query("SET NAMES 'utf8'");
	//$mysqli->query("SET CHARACTER SET 'utf8'");

	//set the time
	date_default_timezone_set('America/Chicago');
	
	//for money formatting
	//setlocale(LC_MONETARY, 'en_US.utf8');  -- for live server
	setlocale(LC_MONETARY, 'en_US');
	
	//constants
	
	define('A_URL', '//local.domain/');
	define('CUR_URL', 'http://local.domain/');
	define('S_CUR_URL', 'http://local.domain/');
	define('FILE_PATH','/path/to/site/');

	define('SITE_PATH','/');
		
	define('SALT','PUT_SALT_HERE');
	
		
	//define('','');
?>