<?php
	require_once dirname(__FILE__)."/../base/request.php";
	
	class Request extends BaseRequest{
		
		public function __construct($pdo){
			parent::__construct($pdo);		
		}
		
		public function run(){
			parent::run();
		}
	}
?>