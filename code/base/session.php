<?php 
class Session{
	
	//local class variables
	protected $_prePost;
	protected $_dir;
	
	public function __construct(){
		$this->_sessionObjs = array();
	}
		
	public function setPrePost($prePost = "POST"){
		$this->_prePost = $prePost;
	}	
	
	//looks for a valid obj with the given field name
	public function verifySessionObj($name,$next = ''){		
		if($next != ""){
			$next = ltrim($next,"/");
			$path = LOGIN_PATH."?next=$next";
		}else $path = LOGIN_PATH;

		if($this->isSessionSet("$name")){
			$sObj = $this->getSessionObj("$name");
			
			//check to make sure that the obj is still in tact and active
			if(!$sObj->isValid()){
				$this->handleInvalidSession("Session expired for $name",$path);
				exit();
			}
		}else{
			$this->handleInvalidSession("No session set for $name",$path);
			exit();
		}
	}
	
	public function verifySessionValue($name,$value,$next = ''){
		if($this->isSessionSet($name)){
			$sVal = $this->getSessionValue($name);
			
			//check to make sure that the obj is still in tact and active
			if($sVal == "" || $sVal != $value){
				$this->handleInvalidSession("Session expired for $name",$this->_dir);
				exit();
			}
		}else{
			$this->handleInvalidSession("No session set for $name",$this->_dir);
			exit();
		}
	}
	
	//checks to see if the given name is set in the global session
	private function isSessionSet($sess){
		if(isset($_SESSION[$sess])) return true;
		else return false;
	}
	
	//base64_encodes and serializes the given obj into the given session name
	public function setSessionObj($sess,$obj){
		$_SESSION[$sess] = base64_encode(serialize($obj));
	}
	
	//returns an unserialized and base64_decoded obj from the given session name
	public function getSessionObj($sess){
		if(isset($_SESSION[$sess])) return unserialize(base64_decode($_SESSION[$sess]));
		else return new StdClass;
	}
	
	public function setSessionValue($field,$value){
		$_SESSION[$field] = $value;
	}
	
	public function getSessionValue($field){
		return $_SESSION[$field];
	}
	
	
	
	private function handleInvalidSession($e,$url){
		if($this->_prePost == "POST") $this->redirectJS($url);
		else $this->redirectHeader($url);
				//echo "Session Error: $e";
		//we may need to log this information
		return false;
	}
	
	private function redirectHeader($url){
		header("Location: ".CUR_URL."$url/");
		exit();
	}
	
	private function redirectJS($url){
		echo "<script>window.location.href = '".CUR_URL."$url/';</script>";
	}
	
}


?>