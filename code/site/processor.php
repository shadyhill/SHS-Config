<?php
	require_once dirname(__FILE__)."/../base/processor.php";
	
class Processor extends BaseProcessor{
    
    public function __construct($request_data){
    	parent::__construct($request_data);
    	
    	//build out private, public, and processor urls
    	//either as arrays or from database
    }
    
}
?>