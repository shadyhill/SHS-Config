<?php
use Aws\S3\S3Client;

class BaseFile{

	protected $_pdo;
	protected $_session;
	protected $_amazonClient;
	protected $_errorMessage;

	//constructor
	public function __construct($pdo,$session){
		$this->_pdo = $pdo;
		$this->_session = $session;		
		$this->createAmazonClient();
		
	}
	
	// getErrorMessage
	//
	// returns last error message to calling function
	public function getErrorMessage(){
		return $this->_errorMessage;
	}
	
	
	// createAmazonClient
	// 
	// requires Amazon keys set in config.php
	protected function createAmazonClient() {
		$this->_amazonClient = S3Client::factory(array(
			'key' => AMAZON_KEY,
			'secret'  => AMAZON_SECRET,
			'region' => AMAZON_REGION,
			'scheme' => 'http'				// used this variable to avoid a CURL error.
			));	
	}
	
	// uploadFileAmazon
	//
	// Opens a file on the local server and sends it to the Amazon S3 service. 
	// These are now set to "private", and can only be downloaded by ShadyHill, or via a signed URL. (see getAmazonSignedURL())
	// Other option: "public-read" to allow anyone to directly download
	// Arguments
	//		file			path & file to be uploaded
	//		key				Unique identifier for the file in the S3 bucket. 
	public function uploadFileAmazon($file,$key) {
		//try {
			$this->_amazonClient->upload(AMAZON_BUCKET, $key, fopen($file, 'r+'), 'private');
		//} catch (SesException $e)
		// error handling for this function?
	}
	
	
	// getAmazonURL
	// 
	// Gets the URL of the file with the given key on the amazon server. 
	// Arguments
	//		key				Unique identifier for the file in the S3 .
	//
	//	Returns a string with the URL
	
	public function getAmazonURL($key) {
		$url = $this->_amazonClient->getObjectUrl(AMAZON_BUCKET, $key);
		return $url;
	}
	
	// getAmazonSignedURL
	// Gets a URL with security credentials and expiration date
	// Arguments:
	// 	$key			Unique Identifier for the file on S3
	// 	$expires 	The time at which the URL should expire. 
	//				This can be a Unix timestamp, a PHP DateTime object, or a string that can be evaluated by strtotime

	public function getAmazonSignedURL($key, $expires="+1 minute"){
		$signedUrl = $this->_amazonClient->getObjectUrl(AMAZON_BUCKET, $key, $expires);
		return $signedUrl; 
	}
	
	
	// getFileExtension
	// Not currently used, but useful if we end up needing to rename files
	public function getFileExtention($filename) {
		// find the file extention with strrchr, then strip off the leading '.' with substr
		$extension = substr(strrchr($filename, "."), 1);
	}
	
	// validatePostFile()
	//
	// Carries out preliminary validation on files received via POST
	// This is considered the minimum level of validation. Additional validation welcome, including
	// validating the filetype or extension as needed. 
	public function validatePostFile($filename, $filepath, $size){
		// Validation: File size > 0
		if ($size <= 0){
			$this->_errorMessage = "Invalid file size";
			return false;
		}
		
		// Validation: File was uploaded via POST
		if (!is_uploaded_file($filepath)){
			$this->_errorMessage = "Invalid file method";
			return false;
		}
		
		// Validation: Check filename length is less than 255 characters
		// Question: Do we need to use mb_strlen instead and handle unicode? Not doing that now
		if (strlen($filename)>255){
			$this->_errorMessage = "Invalid file name";
			return false;
		}
		
		return true;
	}

	//function to return the "type" of file we're dealing with
	//options are 'image','pdf','document','spreadsheet','drawing','other','video'
	public function findType($end){
		$images = array('jpg','jpeg','png','gif');
		$pdfs = array('pdf','pdfx');
		$docs = array('doc','docx','pages');
		$spreads = array('xls','xlsx','numbers');
		$drawings = array('dwg','eps','dxf','3ds','svg','blend','skp','vwx');
		$videos = array('mov','mpg','mpeg','wmv');

		$lend = strtolower(trim($end));

		if(in_array($lend, $images)) return 'image';
		else if(in_array($lend, $pdfs)) return 'pdf';
		else if(in_array($lend, $docs)) return 'document';
		else if(in_array($lend, $spreads)) return 'spreadsheet';
		else if(in_array($lend, $drawings)) return 'drawing';
		else if(in_array($lend, $videos)) return 'video';
		else return 'other';

	}
	
	
}



?>