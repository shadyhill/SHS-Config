<?php
	$connect1 = "127.0.0.1";
	$connect2 = "root";
	$connect3 = "tiger4";
	$db_name = "atx_thirdthird";
	
	try{	
		$pdo = new PDO("mysql:host=$connect1;dbname=$db_name",$connect2,$connect3,
    				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    					  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    					  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}catch(PDOException $e){  
    	echo $e->getMessage();
    	exit();
    }

	$tags = array("Aging","Healthcare","Health","After 50","Friends","Family","Adult children","Caretaking","Retirement","Politics","Medicare","Longevity","Baby Boomers","Back pain","Cancer","The Brain ","Sleep","Estrogen","Exercise","Alzheimer’s","Dementia","Ageism","Gun Control","Faith","Belief","Women","Economics","Finance","Investments","Media","Movies","Books","Education","Back to School","Purpose","Grandparenting","Holidays","Death and Dying","Grief","Widows","Wisdom","Travel","Sports","Websites");

	foreach($tags as $t){
		$today = date('Ymd H:i:s');
		$sql = "INSERT INTO tag (id,name,created) values (0, :name, :created)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array("name"=>$t,"created"=>$today));
		echo ".";

	}
?>