<?php

class API{
		
	protected $_db;					//database instance
	protected $_obj;				//api defined object
	//protected $_table;				//database table
	//protected $_methods;			//allowed methods
	//protected $_required;			//reqruied fields
	protected $_payload;			//incoming data
	protected $_filters;			//incoming GET data
	//protected $_model;				//map the database obj to model
	protected $_response_data;		//the data response
	protected $_response_header;	//the header response
	protected $_pri_obj;
	protected $_sec_obj;
	protected $_pk1;				//primary key of root obj
	protected $_pk2;				//primary key of second obj
	protected $_db_engine;
	//protected $_url_len;			//number of items in url before first obj
	protected $_tables;
	protected $_endpoints;
	protected $_active_endpoint;	//the substring array of the current url acting as an endpoint
	protected $_pagination;
	protected $_page;
	
	public function __construct(){
	    //$this->_model = array();
	    $this->_response_data = array();
	    $this->_filters = array();
	    $this->_payload = array();		
	    $this->_tables = array();
	    $this->_endpoints = array();
		$this->_active_endpoint = array();

	    $this->initAPI();
	    $this->initObj();
	    $this->initRequest();

	}
	
	//if links are going to come back in the payload, the payload might need to 
	//be split up into response data and supporting data (i.e. links, etc)

	private function initAPI(){
	    //find out what system classes are defined
	    $std_classes = get_declared_classes();
	    
	    //required for settings
	    require dirname(__FILE__)."/../../api_config.php";

	    //set the db enginer
	    //TODO: check that one exists?
	    $this->_db_engine = $db_engine;
	    
	    //get all classes that have been defined from config
	    $all_classes = get_declared_classes();
	    
	    //take the diff to find the user classes
	    $user_classes = array_diff($all_classes, $std_classes);
	    
	    //map endpoint to classes	    
	    //see if we have an endpoint swap
	    foreach($user_classes as $cl){	    	
			if(!property_exists($cl, "table")){
				return "You must specifiy a table";
			}else{
				$rc = new ReflectionClass($cl);	
				$table = $rc->getProperty('table')->getValue(new $cl);
			}
			
			//the endpoint is either defined or defaults to the classname
			if(property_exists($cl, 'endpoint')){
				$ep = $rc->getProperty('endpoint')->getValue(new $cl);
			}else{
				$ep = strtolower($cl);
			}

			$this->_tables[$table]["model"] = $cl;
			$this->_endpoints[$ep]["model"] = $cl;
						
	    }
	    
	    //$gURL = rtrim($_GET['url'],"/"); 	   	    
	    $gURL = trim(str_replace($sitedir, '', strtok($_SERVER["REQUEST_URI"],'?')),"/");
	    
	    $urls = explode("/", $gURL);

	    //need to add ability to have a specific api url
	    //but for now just check for blanks
	    if($gURL == ""){
	    	$this->renderResponse(404,"Bad Request","json",array("message" => "You must provide a valid endpoint."));
	    }
	    
	    //calculate the api_url length
	    $pre_urls = explode("/", trim($api_url,"/"));
	    $pre_urls_count = count($pre_urls);	    	    

	    //get the beginning of the current url to see if it matches
	    $url_start = array_slice($urls, 0,$pre_urls_count);

	    if($pre_urls == $url_start){	    	
	    	$this->_active_endpoint = array_slice($urls, $pre_urls_count);	    	
	    }else{
	    	$this->renderResponse(404,"Bad Request","json",array("message" => "Invalid API path."));
	    }

	    //find the length of the urls	    
	    $url_len = count($this->_active_endpoint);

	    //NEED TO REWRITE LOGIC TO MAKE THE _url_len ACTUALLY JUST USE THE ENDPOINT
	    //AND THEN BUILD THE BASE OBJ, PK, REF OBJ, PK LOGIC
	    switch($url_len){
	    	case 4:
	    		$this->_pk2 = $this->_active_endpoint[3];
	    	case 3:
	    		$this->_sec_obj = $this->_active_endpoint[2];
	    	case 2:
	    		$this->_pk1 = $this->_active_endpoint[1];
	    	case 1:
	    		$this->_pri_obj = $this->_active_endpoint[0];
	    		break;
	    	default:
	    		$this->renderResponse(404,"Bad Request","json",array("message" => "Invalid endpoint length."));
	    }
	 	
	 	//TODO: look for realted objs and their pks as well	    
	    //check for endpoint
	    if(isset($this->_endpoints[$this->_pri_obj])) $model = $this->_endpoints[$this->_pri_obj]["model"];
	    else $model = NULL;   
	    	    	    
	    //then look for the right class
	    if(!class_exists($model)){
	    	$this->renderResponse(404,"Not found","json",array("message" => "No existing class definition for $gURL"));
	    }
	    
	    $ref = new ReflectionClass($model);
	    $this->_obj = $ref->newInstance();

	    //this probably isn't good practice, but set defaults for missing params
	    $this->makeValidClass($this->_obj);	    
	    
	    
	    
	    //this should dynamically include the correct database server
	    switch ($this->_db_engine){
	    	case 'mysql':
	    		require "db/mysql.php";
	    		$this->_db = $mysqli;
	    		break;
	    	case 'pdo':
	    		require "db/pdo.php";
	    		$this->_db = new DB($db_vars);
	    		break;
	    	default:
	    		$this->renderResponse(400,"Bad Request","json",array("message" => "No database engine found."));
	    		break;
	    }	    

	    //set pagination info
	    $this->_pagination = $pagination;	    
	}

	//this probably isn't good practice, but set defaults for missing params
	private function makeValidClass(&$obj){
		if(!isset($obj->depth)) $obj->depth = 1;
		if(empty($obj->reqruied)) $obj->required = array();
	}
	
	public function handle_request(){
	    switch($_SERVER['REQUEST_METHOD']){
	    	case "GET":
	    		$this->get_request();
	    		break;
	    	case "POST":
	    		$this->post_request();
	    		break;
	    	case "PUT":
	    	case "PATCH":
	    		$this->put_request();
	    		break;
	    	case "DELETE":
	    		$this->delete_request();
	    		break;
	    	default:
	    		echo "Invalid request method!";
	    }
	    echo json_encode($this->_response_data);
	}
	
	public function get_request(){		
		//if the primary key is set, add it to the qData
		//hanlde an override case, otherwise set to "id"
		if(isset($this->_pk1)){
			if(!empty($this->_obj->pk)) $qData = array($this->_obj->pk => $this->_pk1);
			else $qData = array("id" => $this->_pk1);
		}else $qData = array();

		//TODO allow ranges, partial search, etc.
		//add filters
		$qData = array_merge($qData,$this->_filters);		

	    $this->_response_data = $this->_db->get_request($this->_obj, $qData);

	    //TODO: need to store primary key in temp value so it can be referenced here instead of just looking for id
	    if(!empty($this->_sec_obj)){
	    	foreach($this->_response_data as $data){
	    		if($data["id"] == $this->_pk1){
	    			$this->_response_data = $data[$this->_sec_obj];
	    			break;
	    		}
	    	}
	    }
	    //check to see if we should truncate the response data to only the second obj
	    //if(!empty($this->_sec_obj)) $this->_response_data = $this->_response_data[$this->_sec_obj];
	}
	
	public function post_request(){
		//TODO: firgue out how to return location of post		
	    $response = $this->_db->post_request($this->_obj,$this->_payload);

	    if($response->id > 0){
	    	$this->_response_data = array("Location" => $this->_pri_obj."/".$response->id);
	    }
	}
	
	//TODO: PUT SHOULD GET THE EXISTING OBJ AND ONLY UPDATE NEW FIELDS
	//PATCH SHOULD ONLY UPDATE NEW FIELDS
	public function put_request(){
		//TODO: need to verify which pk to add!
		//try for now just forcing an id on to the end of the payload
		$this->_payload["id"] = $this->_pk1;

		$this->_db->put_request($this->_obj,$this->_payload);			

	}
	
	public function delete_request(){
	    //need to have a pk
	    //match the pk against the object's declared pk
	    //return appopriate response
	}
	
	private function initObj(){
	    //need to check for existence of table property
	    //TODO: should this check really happen this late?
	    if(empty($this->_obj->table)){
	    	//TODO: make sure this errors out correctly
	    	echo "YOU NEED TO SPECIFY A TABLE";
	    }
	    
	    //inspect the db for additional information and realted objs
	    $dbData = $this->_db->inspectDB($this->_obj->table,$this->_obj->depth);

	    //parse dbData for fields and related objs	    
	    $this->buildFields($dbData["fields"],$this->_obj);	    

	    //parse data for related objects and inspect as well
	    foreach($dbData["related"] as $r){	    	
	    	//TODO: check the table is a reference for an endpoint
	    	$rTable = $r['table'];
	    	if(isset($this->_tables[$rTable])){	    		
	    		$this->_obj->_related[$rTable] = $this->buildRelated($r);
	    	}
	    	//TODO: NEED TO BUILD RELATED RELATIONSHIP MAPPINGS
	    	//CHECK THAT THESE WILL WORK IN THE BUILD RELATED FUNCTIONS	    	
	    }	    
	}

	private function buildRelated($related){		
		$rels = array();
		$rTable = $related['table'];			
		if(class_exists($this->_tables[$rTable]['model'])){
	    	$ref = new ReflectionClass($this->_tables[$rTable]['model']);		
	    	$rObj = $ref->newInstance();
	    	$this->makeValidClass($rObj);
	    	$this->buildFields($related["fields"],$rObj);

	    	//need to recursively check for related objs
	    	if(count($related["related"]) > 0){
	    		foreach($related["related"] as $rel){	    			
	    			$rTable = $rel['table'];	    			
	    			if(isset($this->_tables[$rTable])){	    			
	    				$rels[$rTable] = $this->buildRelated($rel);
	    			}
	    		}
	    	}
	    	$rObj->_related = $rels;

	    	return array("obj" => $rObj,
	    			 "column" => $related["column"],
	    			 "host_column" => $related["host_column"]);
	    			 //"_related" => $rels);
	    }  
	}

	//TODO: handle "type" and "key" to be smarter about set up
	private function buildFields($fields,&$obj){
		foreach($fields as $field => $props){
	    	$obj->_fields[] = $field;
	    	if($props["allow_null"] == "NO"){
	    		if(is_array($obj->required) && !in_array($field, $obj->required)){
	    			$obj->required[] = $field;
	    		}else{
	    			$obj->required = array($field);
	    		}	    		
			}
	    }
	}
	
	private function initRequest(){

		//TODO: just run request off of model. no need to map it to local variable
		$allowed_methods = array("GET","POST","PUT","PATCH","DELETE");

	    //check for request methods and only allow those specified in $allowed_methods
	    if(!empty($this->_obj->methods)){
	    	foreach($this->_obj->methods as $m){
	    		if(in_array($m, $allowed_methods)) $this->_methods[] = $m;
	    	}
	    }else $this->_methods = $allowed_methods;

	    //check if request method is supported
	    if(!in_array($_SERVER['REQUEST_METHOD'], $this->_methods)){
	    	header('HTTP/1.1 400 Bad Request'); 
	        header('Content-Type: application/json');
	        echo json_encode(array("message" => "Invalid request method."));
	        exit();
	    }

	    //get any SERVER data
	    switch($_SERVER['REQUEST_METHOD']){
	    	case "POST":
	    		foreach($_POST as $key => $value){				
	    			$this->_payload[$key] = $value;
	    		}
	    		break;
	    	case "PUT":
	    	case "PATCH":
	    		parse_str(file_get_contents("php://input"),$put_vars);
	    		foreach($put_vars as $key => $value){				
	    			$this->_payload[$key] = $value;
	    		}
	    		break;
	    }
	    
	    //use an array of reserver namespaces
	    //TODO: don't use an if beleow, use in_array
	    $reserved_gets = array("_","page");

	    //get any get variables and treat as filters
	    foreach($_GET as $key => $value){
	    	if($key != "_") $this->_filters[$key] = $value;
	    }
	}

    private function renderResponse($code,$code_txt,$format,$response_data){
		header("HTTP/1.1 $code $code_txt");
		switch($format){
			case "json":
				header('Content-Type: application/json');
	    		echo json_encode($response_data);
	    		exit();
				break;
		}	    
	}
    
}
?>