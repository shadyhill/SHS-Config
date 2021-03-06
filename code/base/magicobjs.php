<?php
require "objs.php";
//include_once dirname(__FILE__)."/../LIBRARY/MARKDOWN/markdown.php";

class MagicObjs extends Objs {

	protected $_table;
	protected $_row;

	public function __construct($pdo,$session) {
		parent::__construct($pdo,$session);
		$this->_row = array();
	}
	
	public function makeFromID($id) {
		$qData = array("id" => $id);
		$sql = "SELECT * FROM $this->_table WHERE id = :id LIMIT 1;";
		$stmt = $this->_pdo->prepare($sql);
		$stmt->execute($qData);		
		$this->makeFromData($stmt->fetch());
	}
	
	public function makeFromData($obj) {
		if(!empty($obj)){
			$this->_row = get_object_vars($obj);
			$this->formatMadeData();
		}
	}

	public function assignData($data){
		$this->_row = $data;
	}

	public function makeFromQuery($data){
		$sql = "SELECT * FROM $this->_table WHERE ";
		$first = true;
		foreach($data as $key => $val){
			if($first){
				$first = false;
			}else{
				$sql .= "AND ";
			}
			$sql .= "$key = :$key ";
		}	
		$stmt = $this->_pdo->prepare($sql);
		try{
            $stmt->execute($data);
        }catch(PDOException $e){
            //TODO: handle error in JSON
            return $e->getMessage();
            exit();
        }
        $this->makeFromData($stmt->fetch());
	}

	
	protected function formatMadeData(){
		//this can be overriden
	}
	
	protected function setTable($table){
		//use htmlentities to ensure no funny business on sql injection
		$this->_table = htmlentities($table);
	}
	
	public function mapPostVars(){
		foreach($_POST as $key => $val){
			if($key != "pre_process" &&  $key != "post_process" && $key != "ajax_url"){
				$this->$key = $val;
			}
		}
	}
	
	//magic method for accessing an unassigned variable
	public function __get($what) {
		if (array_key_exists($what,$this->_row)) {
			return $this->_row[$what];
		}
		else {
			return null;
		}
	}
	
	//magic method for setting an unassigned variable
	public function __set($name,$value) {
		$this->_row[$name] = $value;
		return true;
	}
	
	//returns the protected row data
	public function returnRow(){
		return $this->_row;
	}
	
	//$mapID will save the ID on creating a new obj
	public function save($mapID = true) {
		// build the INSERT statement
		$sqlA = $sqlB = "";	
		
		//$types 	= "";		//stores the types for binding
		$params = array();	//stores parameters in order (same as $this->_row, but may need it)
		
		//if we have an id, then it is an update
		if(isset($this->_row['id']) && $this->_row['id'] != "") {

			//need a string for the types			
			$sql = "UPDATE $this->_table SET";

			foreach ($this->_row as $key => $value) {				
				//skip the id field
				if($key != "id"){
					$sql .= " $key = :$key,";				
				}
			}

			//remove trailing comma
			$sql = rtrim($sql,",");
			
			//build the where
			$sql .= " WHERE id = :id";						
			
		}
		else {
			$sql = "INSERT INTO $this->_table (";			
			
			foreach ($this->_row as $key => $value) {
				$sqlA .= " $key,";
				$sqlB .= " :$key,";								
			}
			$sqlA = rtrim($sqlA,",");
			$sqlB = rtrim($sqlB,",");

			$sql .= "$sqlA) VALUES ($sqlB)";			
		}		
		
		//do we need to do a try/catch here?
		//prepare the statement
		$stmt = $this->_pdo->prepare($sql);

		if($stmt === false){
			return (object)array("status"=>false,"type" => "pdo_error", "error"=>$this->_pdo->errorInfo());
		}

		try{
			$res = $stmt->execute($this->_row);
		}catch(PDOException $e){
    		return (object)array("status"=>false,"type" => "pdo_exception", "error"=>$e);
    		// echo "Code: ".$e->getCode();
    		// echo "Line: ".$e->getLine();
    		// echo "Trace: ".$e->getTraceAsString();
    	}
		
    	//if we created an object, then we should add the id to the object
    	if(empty($this->_row['id']) && $mapID){
    		$this->id = $this->_pdo->lastInsertId();
    	}

		//just return boolean and let objects handle response
		return (object)array("status"=>true);				
	}
	
	
}

?>