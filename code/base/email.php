<?php
require dirname(__FILE__)."/lib/phpmailer/class.phpmailer.php";
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;

abstract class BaseEmail{

	//local class variables
	protected $_urlObj;		
	protected $_requestObj;
	protected $_pdo;
	protected $_session;
	protected $_emailData;
	protected $_mail;
	protected $_method = "AMAZON";
	protected $_fromEmail = "bp@shadyhillstudios.com";
	
	//constructor
	public function __construct($request_data){
		$this->_urlObj 		= $request_data["url_vars"];
		$this->_requestObj 	= $request_data["request"];
		$this->_pdo 		= $request_data["pdo"];
		$this->_session 	= $request_data["session"];		
	}	

	protected function sendEmail($to,$from,$subject,$body,$cc = array(),$bcc = array()){
		
		// for this project, we're going through Amazon. Need to clean this up moving forward
		if ($this->_method == "AMAZON"){
			$this->sendEmailAmazon($to, $from['email'], $subject, $body,$cc, $bcc);
		} else {
			
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
				echo "did not send email: ".$mail->ErrorInfo;
			}
		}
	}

	// sendEmailAmazon
	//
	// Uses Amazon SDK to send email via SES service
	// The input variables are the same as the sendEmail function, to maintain compatibility.
	// In future, it would be better to alter the format of these input variables to more closely match Amazon
	// (e.g. change $to from an array of arrays to an array of email addresses. 
	// 
	protected function sendEmailAmazon($to,$from,$subject,$body,$cc = array(),$bcc = array()){

		// peels all of the 'email' values out of the $to array
		$to_array = array_map(function($element){return $element['email'];}, $to);
		
		// creates Amazon SES client. 
		$client = SesClient::factory(array(
			'key' => AMAZON_KEY,
			'secret'  => AMAZON_SECRET,
			'region' => AMAZON_REGION
			));
			
		$sendArray = array(
			'Source' =>  $this->_fromEmail,
			'Destination' => array(
				'ToAddresses' => $to_array
			),
			'Message' => array(
				'Subject' => array(
					'Data' => $subject
				),
				'Body' => array(
					'Text' => array(
						'Data' => strip_tags($body)
					),
					'Html' => array(
						'Data' => $body
					)
				)
			)
		);
		
		try {
			$result = $client->sendEmail($sendArray);
		} catch (SesException $e) {
			echo "Error sending email via SES service. " . $e->getMessage();
		}
		//var_dump($result);
	
	
	}
	
	
	private function initializeMail(){
		$mail = new PHPMailer();

		$mail->IsSMTP(); // telling the class to use SMTP

		$mail->Host       = ""; // SMTP server
		$mail->SMTPDebug  = 4; 							//for debug
		//$mail->SMTPDebug  = 0;                     	// for production
		$mail->SMTPAuth   = true;                  		// enable SMTP authentication
		$mail->SMTPSecure = "tls"; 
		$mail->Port       = 587;                    		// set the SMTP port for the GMAIL server
		$mail->Username   = ""; 	// SMTP account username
		$mail->Password   = ""; 
		$mail->Debugoutput = "html";
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