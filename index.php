<?php 
	//include config file
	require dirname(__FILE__)."/config.php";
	
	//include layout file (handles pagedata
	require dirname(__FILE__)."/code/site/request.php";
	
	//create layout obj and render
	$site = new Request($pdo);
	$site->run();
?>