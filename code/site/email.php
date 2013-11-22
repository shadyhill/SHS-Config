<?php
	require_once dirname(__FILE__)."/../base/email.php";
	
	class Email extends BaseEmail{
		
		public function __construct($request_data){
			parent::__construct($request_data);
			
			//build out private, public, and processor urls
			//either as arrays or from database
		}
		
		public function send(){			
			//need to verify the payload
			//call the function – which may just be a template?
			//verify to and from sender information – from payload or from db or hard coded?
			if(!$this->verifyPayload()){
				//query the database for information
				$this->retrieveDBEmail();
				$this->{$this->_urlObj->fx}();
					
			}else{
				//TODO: NEED TO LOG THIS SOMEWHERE
				echo "did not verify payload!";
			}
		    
		    //$this->renderTemplate();
		    //echo "need to load template: ".$this->_page->template;
		    //echo "<br />need to load file: ".$this->_page->include_file;
		    
		    
		}

		private function sendConfirmation(){			
			//TODO: NEED TO ADD TEMPLATE LOGIC
			//TODO: NEED TO ADD SIGING OF EMAILS
			//TODO: NEED TO LOG EMAIL TO ACCOUNT (CHECK TO, CC, AND BCC)
			//TODO: MAY NEED TO CHECK FROM AS WELL? WE WOULD KNOW IF IT WAS FROM AN ACCOUNT

			//maybe to add the variables to the body, we can use some kind of variable mapping with colons?
			$this->_requestObj->confirmation = "CONFIRMATIONCODE";

			//should this be in the initializeMail() function?
			$this->matchPostToBody();

			$from = array("name" => $this->_emailData->from_name, "email" => $this->_emailData->from_email);
			$to = array(array("name" => "Name Here", "email" => "user@domain.com"));

			$this->sendEmail($to,$from,$this->_emailData->subject,$this->_emailData->body);
		}


		
		
	}
?>