<?php
require dirname(__FILE__)."/session.php";

class BaseRequest{

	//local class variables
	protected $_session;	
	protected $_url;
	protected $_pdo;
	
	//constructor
	public function __construct($pdo){
		//get the url variable out of GET (posted by htaccess) and remove trailing slash
		$this->_url = rtrim(str_replace(SITE_PATH, '', $_SERVER['REQUEST_URI']),"/");
		//access the global mysql obj		
		$this->_pdo = $pdo;			
		
		$this->_session = new Session();
	}
	
	protected function run(){
	    //should be some kind of regular expression in here
	    $sql = "SELECT id, url_pattern, request, access, secure 
	    			FROM config_url_router 
	    			WHERE status = 1 
	    			ORDER BY parse_order";
	    $res = $this->_pdo->query($sql);

	    $matched = false;
	    while($request = $res->fetch()){

	    	$preg_pattern = $this->url_to_regex($request->url_pattern);
	    	
	    	if(preg_match($preg_pattern, $this->_url,$vars) === 1){	    		
	    		$matched = true;
	    		$url_vars = (object)$vars;
	    		break;
	    	}
	    }
	    
	    if($matched){
	     	
	     	//see if we need to verify the session
	     	//NOT SURE THIS IS IN THE RIGHT PLACE BECAUSE EASY TO FAKE OUT PROCESSORS
	     	//if($request->validate) $this->_session->validate($this->access);
	     	
	     	$request_data = array(	"url_vars" => $url_vars, 
	     							"request" => $request, 
	     							"pdo" => $this->_pdo, 
	     							"session" => $this->_session);
	     	
	     	switch($request->request){
	    	 	case "view":
	    	 		include_once dirname(__FILE__)."/../site/view.php";
	    	 		$view = new View($request_data);
	    	 		$view->render();
	    	 		break;
	    	 	case "process":				 	
	    	 	case "ajax":
	    	 		include_once dirname(__FILE__)."/../site/processor.php";
	    	 		$processor = new Processor($request_data);
	    	 		$processor->run();
	    	 		break;
	    	 	case "email":
	    	 		include_once dirname(__FILE__)."/../site/email.php";
	    	 		$email = new Email($request_data);
	    	 		$email->send();
	    	 		break;
	    	 	case "log":
	    	 		echo "too be built";
	    	 		break;
	    	 	default:
	    	 		echo "need to load 404 in request.php";
	     	}
	     	
	    }else{	    	
	        echo "need to load 404";
	    }
	    
	}
	
	protected function url_to_regex($url){
		$pieces = explode("/", $url);
				
		$preg_pattern = "~";
		foreach($pieces as $el){
		    if(substr($el, 0,1) == ":"){
		    	$var = substr(strstr($el, '[', true),1);
		    	$preg_pattern .= "(?<$var>";
				if(strpos($el, "[w]") > 0) $preg_pattern .= "[\w-]+)/";						    	
				else if(strpos($el, "[d]") > 0) $preg_pattern .= "[\d.-]+)/";
		    	else $preg_pattern .= ")/";
		    }else{
		    	$preg_pattern .= "$el/";
		    }
		}		
		$preg_pattern = rtrim($preg_pattern,"/")."~";

		//check to make sure we're not validating everything on empty string
		if($preg_pattern == "~~") $preg_pattern = "~^\s*$~";
		//echo "pattern is $preg_pattern"
		return $preg_pattern;
		    			
	}

}
?>