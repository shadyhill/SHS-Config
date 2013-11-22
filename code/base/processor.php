<?php 

//require_once dirname(__FILE__)."/../library/fx/fx.php";
//include_once dirname(__FILE__)."/../LIBRARY/LOGS/log.php";

class BaseProcessor{
	
	//local class variables
	protected $_fxObj;
	protected $_httpVars;
	protected $_logObj;
	protected $_accountObj;		//this is set by the child class depending on account type
	protected $_sessionObj;		//this is set by the child class depending on the session type
	
	protected $_urlVars;
	protected $_url;
		
	protected $_objIndex;		//this variable keeps track of what index to find the obj
	protected $_fxIndex;		//this variable keeps track of what index to find the function
	
	protected $_pdo;
		
	public function __construct($request_data){
		$this->_urlObj 		= $request_data["url_vars"];
		$this->_requestObj 	= $request_data["request"];
		$this->_pdo 		= $request_data["pdo"];
		$this->_session 	= $request_data["session"];
		
		//$this->_fxObj 		= new FX();
		//$this->_logObj 		= new Log();
		//$this->_hVars 		= array();
		
		//$this->_urlVars 	= $urlVars;
		//$this->_url 		= implode("/",$this->_urlVars);
		
		//$this->_objIndex 	= 1;
		//$this->_fxIndex 	= 2;
		
		//$this->cleanHTTPVariables();
	}
	
	public function run(){
		$obj = $this->_urlObj->model;
		$fx	 = $this->_urlObj->fx;
		
		//not a dynamic function 
		//not sure we need this though, if we do "decorators" on objects
		/*
if($obj == ""){
			$urls = explode("/",current($this->_urlObj));
			$aIndex = array_search($this->_requestObj->request, $urls);
			if($aIndex !== false && $aIndex <= count($urls) - 2){
				$obj = $urls[$aIndex+1];
				$fx = $urls[$aIndex+2];
			}
		}
*/
		
		if(file_exists(FILE_PATH."code/site/objs/$obj.php")){
			include_once FILE_PATH."code/site/objs/$obj.php";
			$ref = new ReflectionClass(ucfirst($obj));
			$object = $ref->newInstance($this->_pdo,$this->_session);
			$processed = $object->{$fx}();
			
			if($this->_requestObj->request == "process"){
				header("Location: ".$processed);
				exit();
			}else if($this->_requestObj->request == "ajax"){
				echo $processed;
				exit();
			}
			
		}else{
			echo "could not find the model class with ".FILE_PATH."code/site/objs/$obj.php";
			//need to redirect?
		}
	}
}


?>