<?php
	require_once dirname(__FILE__)."/../../base/magicobjs.php";
	
class Account extends MagicObjs{
    
    public function __construct($pdo,$session = array()){
    	parent::__construct($pdo,$session);
    	    	
    }
    
    public function authenticate(){
    	
	    echo "in here!";
    }
    
    public function register(){
        $this->setTable('account');
	 	$this->mapPostVars();
	 	
        //should now have an email address, but verify anyway
        if(!filter_var($this->username,FILTER_VALIDATE_EMAIL) ){
            return json_encode(array("status" => "error","msg" => "Invalid email address."));
        }    

        //trim and lowercase email
        $this->username = strtolower(trim($this->username));
        $this->salt = $this->randID(12);
        $this->created = date("Y-m-d H:i:s");
        $this->uuid = uniqid('',true);
        $this->status_id = 1;
        $this->confirmation = uniqid('rc_');

        //try to create an account
        $saved = $this->save();

        if(!$saved->status){
            if($saved->type == "pdo_exception"){
                switch($saved->error->getCode()){
                    //duplicate key
                    case "23000":
                        return json_encode(array("status" => "error", "msg" => "Duplicate entry in database"));
                    default:
                        return json_encode(array("status" => "error", "msg" => "Unkown PDO Exception: ".$saved->error->getMessage()." [".$saved->error->getCode()."}"));
                }
                return json_encode(array("status" => "error", "msg" => "pdo_error: ".$saved->error));            
            }else if($saved->type == "data_integrity"){
                if(substr($saved->error,0,15) == "Duplicate entry"){
                    //let's figure out if it's the email or the id
                    $kPos = strpos($saved->error, "for key");
                    $col = trim(substr($saved->error, $kPos + 8),"'");
                    if($col == "username_2"){
                        //email already exists in database
                        return json_encode(array("status" => "error","msg" => "It appears this email has already registered. Log in or resend confirmation."));
                    }else{
                        return json_encode(array("status" => "error","msg" => "A database error occured. Please try again. #1001"));
                    }
                }                
            }            
        }

        //no errors on mysql, so let's send the confirmation link
        $e_url = S_CUR_URL."email/send-confirmation/";
        $params = array("email" => $this->username,"confirmation" => $this->confirmation);        
        $this->fsocksend($e_url,$params);


        return json_encode(array("status" => "success", "action" => "redirect", "url" => "please-confirm/"));
    }
    
}
?>