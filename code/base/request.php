<?php
require dirname(__FILE__)."/session.php";

class BaseRequest{

	//local class variables
	protected $_session;	
	protected $_url;
	protected $_pdo;
	
	//constructor
	public function __construct($pdo){
		//grab the request URI
		$this->_url = rtrim(str_replace(SITE_PATH, '', $_SERVER['REQUEST_URI']),"/");				
		
		//access the global pdo obj		
		$this->_pdo = $pdo;			
		
		//need a session obj
		$this->_session = new Session();
	}
	
	protected function matchRouter(){
		//should be some kind of regular expression in here
	    $sql = "SELECT cur.*, group_concat(concat_ws('~',ps.script,ps.script_type) ORDER BY ps.load_order) AS scripts
				FROM config_url_router cur
					LEFT JOIN config_page_scripts ps ON cur.id = ps.router_id
				WHERE cur.status = 1
				GROUP BY cur.id
				ORDER BY cur.parse_order";
	    $res = $this->_pdo->query($sql);

	    //TODO: Does any of this need to be in a try/catch?

	    $matched = false;
	    while($request = $res->fetch()){

	    	$preg_pattern = $this->url_to_regex($request->url_pattern);
	    
	    	if(preg_match($preg_pattern, $this->_url,$vars) === 1){	    		
	    		$matched = true;
	    		$url_vars = (object)$vars;
	    		$url_vars->path = $this->_url;					//map the path	    		
	    		$url_vars->path_array = explode("/", trim($this->_url,"/"));	//map the exploded els of path
	    		break;
	    	}
	    }
	    
	    if($matched){   
	    	$scripts = $this->makeScripts($request->scripts);
	    	$request->jsFiles = $scripts["js"];
	    	$request->cssFiles = $scripts["css"];
	    	
	     	$request_data = array(	"url_vars" => $url_vars, 
	     							"request" => $request, 
	     							"pdo" => $this->_pdo, 
	     							"session" => $this->_session);
	    }else{
	    	$request_data = array();
	    }

	    return $request_data;
	    
	}

	private function makeScripts($dbScripts){
		$cssFiles = array();
		$jsFiles = array();

		$scripts = explode(',',$dbScripts);
		foreach($scripts as $s){
			$parts = explode('~', $s);
			if(count($parts) >= 2){
				if($parts[1] == "css"){
					if(file_exists(FILE_PATH."assets/css/".$parts[0])) $cssFiles[] = $parts[0];
				}else if($parts[1] == "js"){				
					if(file_exists(FILE_PATH."assets/js/".$parts[0])) $jsFiles[] = $parts[0];
				}
			}
		}

		return array("css" => $cssFiles, "js" => $jsFiles);
	}

	private function url_to_regex($url){
		$pieces = explode("/", $url);
				
		$preg_pattern = "~^/";
		foreach($pieces as $el){
		    if(substr($el, 0,1) == ":"){
		    	$var = substr(strstr($el, '[', true),1);
		    	$preg_pattern .= "(?<$var>";
		    	if(strpos($el, "[w]") > 0) $preg_pattern .= "[\w-]+)/";
				else if(strpos($el, "[d]") > 0) $preg_pattern .= "[\d.-]+)/";
		    	else $preg_pattern .= ")/";
		    }else if($el == ""){
		    	//do nothing
		    }else{
		    	$preg_pattern .= "$el/";
		    }
		}
		
		$preg_pattern = rtrim($preg_pattern,"/")."~";

		//check to make sure we're not validating everything on empty string
		if($preg_pattern == "~^~") $preg_pattern = "~^\s*$~";
		return $preg_pattern;
		    			
	}

}
?>