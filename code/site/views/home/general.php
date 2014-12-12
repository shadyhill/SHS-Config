<?php
require_once dirname(__FILE__)."/../viewobj.php";

class GeneralView extends ViewObj{
	
	//$meta would be stuff like css includes, js includes, and meta tags
	public function __construct($page,$session){
		parent::__construct($page,$session);
		//nothing for now
		//but could have default include paths?
	}

	public function index(){		
		$this->context = "Hello World!";
		$this->sample = array("first","second","third");
	}

	public function test(){
		//nothing for nows
	}

}

?>