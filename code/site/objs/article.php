<?php
	require_once dirname(__FILE__)."/../../base/magicobjs.php";
	
class Article extends MagicObjs{
    
    public function __construct($pdo,$session = array()){
    	parent::__construct($pdo,$session);
    	    	
    }

    
}

?>