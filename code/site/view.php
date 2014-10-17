<?php
	require_once dirname(__FILE__)."/../base/view.php";
	
	class View extends BaseView{
		
		public function __construct($request_data){
			parent::__construct($request_data);		
			//$this->_subdomain = $request_data['subdomain'];
		}

		public function run(){
			//unless there are any customizations to do...
			parent::run();
		}		

	}
?>