<?php
require_once dirname(__FILE__)."/../viewobj.php";

class Sample extends ViewObj{
	
	//$meta would be stuff like css includes, js includes, and meta tags
	public function __construct($view){
		parent::__construct($view);		
	}

	public function index(){							
		//do queries, etc. in here
		//any property of $this (e.g. $this->foo) will be available in twig template
	}

	

}

?>