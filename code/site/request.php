<?php
	require_once dirname(__FILE__)."/../base/request.php";
	
	class Request extends BaseRequest{
		
		protected $_subdomain;

		public function __construct($pdo){
			parent::__construct($pdo);

			//find the subdomain from the SERVER variable
			$this->parseSubDomain();			
		}
		
		//TODO: part of this function should probably live in the parent
		public function run(){
			$request_data = $this->matchRouter();
	     	
	     	if(!empty($request_data)){			     		
	     		
	     		//special case for signing out
	     		if($request_data["request"]->url_pattern == "signout/"){
	     			//kill the session
	     			session_destroy();
	     			//TODO: kill the auth token

	     			//redirect to signin
	     			header("Location: ".A_URL."signin/");
	     			exit();
	     		}

	     		//at this point, we need to know we we need to verify the current user
	     		//this is a global check for whether or not we have a valid user in the session, not whether or not they have permissions
	     		if($request_data["request"]->validate){
	     			//since we're checking on the contact obj, let's make sure it's included at this point
	     			include_once dirname(__FILE__)."/objs/contact.php";
	     			$this->_session->verifySessionObj('sContact',$request_data['url_vars']->path);
	     		}

		     	switch($request_data["request"]->request){
		    	 	case "render":
		    	 	case "process":				 	
		    	 	case "ajax":
		    	 	case "content":
		    	 		// include_once dirname(__FILE__)."/../site/processor.php";
		    	 		// $processor = new Processor($request_data);
		    	 		// $processor->run();
		    	 		// break;
		    	 		include_once dirname(__FILE__)."/../site/view.php";
		    	 		$view = new View($request_data);
		    	 		$view->run();
		    	 		break;
		    	 	case "api":	    	 		
		    	 		include_once dirname(__FILE__)."/../api/api.php";
		    	 		$api = new API();
		    	 		$api->handle_request();
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
		        echo "Unable to locate page in router. 404 please.";
		    }
	    
	}

		private function parseSubDomain(){
			$sName = $_SERVER['SERVER_NAME'];
			if($sName == MAIN_SERVER_NAME){
				define("BP_SUBDOMAIN", '');				
			}else{
				$els = explode(".", $sName);
				define("BP_SUBDOMAIN", $els[0]);				
			}			

		}
	}
?>