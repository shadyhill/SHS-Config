<?php
require dirname(__FILE__)."/lib/phpmailer/class.phpmailer.php";

abstract class BaseEmail{

	//local class variables
	protected $_urlObj;		
	protected $_requestObj;
	protected $_pdo;
	protected $_session;
	protected $_emailData;
	protected $_mail;
	
	//constructor
	public function __construct($request_data){
		$this->_urlObj 		= $request_data["url_vars"];
		$this->_requestObj 	= $request_data["request"];
		$this->_pdo 		= $request_data["pdo"];
		$this->_session 	= $request_data["session"];		
	}	
	

	protected function sendEmail($to,$from,$subject,$body,$cc = array(),$bcc = array()){
		
		//constructs default mail obj
		$mail = $this->initializeMail();	

		//create the unique id for signing the email
		$email_id = uniqid('ec_',true);


		//set who the email is coming from 		
		$mail->From = $from['email'];
		$mail->FromName = $from['name'];

		foreach($to as $r){
			$mail->addAddress($r['email'],$r['name']);
		}

		foreach($cc as $rc){
			$mail->addCC($rc['email'],$rc['name']);	
		}

		foreach($bcc as $rb){
			$this->_mail->addBCC($bc['email'],$bc['name']);	
		}		
		
		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $this->generateHTMLTemplate($body);
		$mail->AltBody = strip_tags($body);
		
		if(!$mail->send()){
			//generate log info
			echo "did not send email: ".$this->_mail->ErrorInfo;
		}
	}

	private function initializeMail(){
		$mail = new PHPMailer();

		$mail->IsSMTP(); // telling the class to use SMTP

		$mail->Host       = "smtp.gmail.com"; 			// SMTP server
		$mail->SMTPDebug  = 0;                     		// enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  		// enable SMTP authentication
		$mail->SMTPSecure = "tls"; 
		$mail->Port       = 587;                    		// set the SMTP port for the GMAIL server
		$mail->Username   = "user@domain.com"; 	// SMTP account username
		$mail->Password   = "PASSWORD-HERE"; 

		return $mail;
	}

	private function signEmail(){
		//generate a random id for the email message
	}

	private function generateHTMLTemplate($msg){
		return $msg;
	}

	protected function matchPostToBody(){
		$reserved = array("id","url_pattern","request","access","secure");
		
		//replace site level variables (like URL)
		$this->_emailData->body = str_replace(":URL", S_CUR_URL, $this->_emailData->body);

		//replace names variables
		foreach($this->_requestObj as $key => $val){
			if(!in_array($key, $reserved)){
				$body_needle = ":$key";				
				$this->_emailData->body = str_replace($body_needle, $val, $this->_emailData->body);				
			}			
		}
	}

	protected function verifyPayload(){
		ksort($_POST);	//do we need to sort if we're sending the data to ourselves?
		$toVerify = "";
		foreach($_POST as $key => $val){
			if($key = "verify") $pVerify = $val;
			else $toVerify .= $val;
		}
		$toVerify = md5($toVerify.SALT);

		if($pVerify != "" && $toVerify == $pVerify){
			return true;
		}else{
			//probably need to log this data
			return false;
		}
	}

	protected function retrieveDBEmail(){
		$qData = array("email_name" => $this->_urlObj->fx);
		$sql = "SELECT * FROM email_data WHERE email_name = :email_name";
		$stmt = $this->_pdo->prepare($sql);
		$stmt->execute($qData);
		$this->_emailData = $stmt->fetch();
	}
	
}

?>