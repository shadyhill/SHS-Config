<?php

abstract class BaseView{

	//local class variables
	protected $_urlObj;		
	protected $_requestObj;
	protected $_page;
	protected $_jsFiles;	
	protected $_cssFiles;		
	protected $_pdo;
	protected $_session;
	
	//constructor
	public function __construct($request_data){
		$this->_urlObj 		= $request_data["url_vars"];
		$this->_requestObj 	= $request_data["request"];
		$this->_pdo 		= $request_data["pdo"];
		$this->_session 	= $request_data["session"];
		
		$this->_template = $this->_includeFile = "";
		$this->_jsFiles = $this->_cssFiles = array();			
	}	
	
	public function run(){
		//call the appropriate function based on the request type
		//TODO: probably need to verify/handle an incorrect request type
		$this->{$this->_requestObj->request}();						
	}

	public function ajax(){
		$vObj = $this->generateViewObj("views/models",$this->_urlObj->model);
		$ajax = $vObj->{$this->_urlObj->method}();
		echo $ajax;
		exit();
	}

	public function process(){
		$vObj = $this->generateViewObj("views/models",$this->_urlObj->model);
		$process = $vObj->{$this->_urlObj->method}();
		header("Location: ".$process);
		exit();
	}

	public function render(){		
		//$this->getDBPageData();	
		$vObj = $this->generateViewObj($this->_requestObj->view_path,$this->_requestObj->view_class);
		$vObj->setPage($this->_requestObj);
		$vObj->setUrlObj($this->_urlObj);
		$vObj->{$this->_requestObj->view_method}();
	    $this->renderTemplate($vObj);		    		    
	}

	//this is a special function for return just content for an ajax request
	public function content(){
		$vObj = $this->generateViewObj($this->_requestObj->view_path, $this->_urlObj->model);
		$vObj->setPage($this->_requestObj);
		$vObj->setUrlObj($this->_urlObj);
		$vObj->{$this->_urlObj->method}();
		//need to dynamically set the template from the url variables
		$this->_requestObj->template = $this->_urlObj->model."/".$this->_urlObj->template.".html";
		$this->renderTemplate($vObj);
	}
	
	protected function generateViewObj($path,$class){			
		include_once FILE_PATH."code/site/$path/$class".".php";			
		$toArray = get_object_vars($this);

		//routine for generating camelcase ClassName
		$obj = $this->underscoreToCamelCase($class);		
		
		//add the "View" namespace
		$obj .= "View";		

		//make the view object
		$vObj = new $obj($this->_pdo, $this->_session);								

		$methods = get_class_methods($vObj);

		//return the view obj
		return $vObj;
	}

	private function underscoreToCamelCase($word){
		$word_els = explode("_",$word);
		$obj = "";
		foreach($word_els as $el) $obj .= ucfirst($el);
		return $obj;
	}

	//CAN THIS FUNCTION HAVE CONDITIONALS FOR LOGGED IN OR NOT?
	protected function renderTemplate($vObj){

		$loader = new Twig_Loader_Filesystem(FILE_PATH."code/site/templates");
		$twig = new Twig_Environment($loader, array(
			//'cache' => dirname(__FILE__)."/templates/cache",
		));	
		$aData = get_object_vars($vObj);
		
		//this calls the actual template			
		echo $twig->render($this->_requestObj->template, $aData);

	}
	
}

?>