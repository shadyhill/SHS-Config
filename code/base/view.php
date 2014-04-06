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
		
		$this->getDBPageData();	
	}	

	public function returnPDO(){
		return $this->_pdo;
	}

	public function returnSession(){
		return $this->_session;
	}

	public function returnPage(){
		return $this->_page;
	}

	public function returnRequest(){
		return $this->_requestObj;
	}

	public function returnURLObj(){
		return $this->_urlObj;
	}
	
	//EVENTUALLY NEED TO CACHE THIS CALL IN A SESSION OR MEMCACHE OR REDIS
	protected function getDBPageData(){
		$rid = $this->_requestObj->id;
		$sql = "SELECT pd.*, group_concat(concat_ws('~',ps.script,ps.script_type) ORDER BY ps.load_order) AS scripts
					FROM config_page_data pd 
					LEFT JOIN config_page_scripts ps ON pd.router_id = ps.router_id
					WHERE pd.router_id = $rid
					GROUP BY router_id";
				
		$res = $this->_pdo->query($sql);
		
		try{
			$this->_page = $res->fetch();
		}catch(PDOException $e){  
    		echo $e->getMessage();
    		exit();
    	}
		
		//need to check that _page isn't empty
    	if(empty($this->_page)){
    		echo "no results in page_data. need to load 404.";
    		exit();
    	}

		$scripts = explode(',',$this->_page->scripts);
		foreach($scripts as $s){
			$parts = explode('~', $s);
			if(count($parts) >= 2){
				if($parts[1] == "css"){
					if(file_exists(FILE_PATH."assets/css/".$parts[0])) $this->_page->cssFiles[] = $parts[0];
				}else if($parts[1] == "js"){				
					if(file_exists(FILE_PATH."assets/js/".$parts[0])) $this->_page->jsFiles[] = $parts[0];
				}
			}
		}
		
		

		//WE CAN COME BACK AND VERIFY THE VIEW AND TEMPLATE EXIST, FOR NOW, WE KNOW?
		// if($this->_page->include_file == "" || !file_exists(FILE_PATH."code/site/views/".$this->_page->include_file)){
		// 	//need to serve up a 404
		// 	//$this->_includeFile = "STATUS-CODES/404.php";
		// 	echo "we could not find the include file. need to load custom 404 for: ".$this->_page->include_file;
		// 	exit();
		// }
	}
	
}

?>