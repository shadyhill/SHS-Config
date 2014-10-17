<?php	
	include_once dirname(__FILE__)."/../../base/lib/form.php";

	class ViewObj{

		public $page;
		public $urlObj;
		public $form;
		protected $_pdo;
		protected $_session;
		protected $_now;

		public function __construct($pdo, $session){
			$this->_pdo = $pdo;
			$this->_session = $session;			

			$this->form = new Form($pdo,$session);
			$this->_now = date("Y-m-d H:i:s");

			$this->getvars = $_GET;
		}

		public function setPage($page){
			$this->page = $page;
			//var_dump($this->page);
		}

		public function setUrlObj($url){
			$this->urlObj = $url;			
		}

		//TODO: Function to validate account
		//TODO: Function to check account with current object
		//TODO: Function with something to do about permissions?
		

		//need a way to return all public class variables as an array
		//this is just a placeholder, not ready for use
		public function toArray(){
			return array();
		}

		protected function returnJsonError($msg, $fields = array()){

    	}    

		protected function mapPostVars(){
			$pVars = new stdClass();
			foreach($_POST as $key => $val){
				if($key != "pre_process" &&  $key != "post_process" && $key != "ajax_url"){
					if(!is_array($val)){
						$pVars->$key = trim($val);
					}else{						
						$pVars->$key = $val;
					}
				}
			}
			return $pVars;
		}

		protected function mapGetVars(){
			return (object) $_GET;
			// $gVars = new stdClass();
			// foreach($_GET as $key => $val){
			// 	$gVars->$key = $val;
			// }
			// return $gVars;
		}

		//use this function to break apart a database result that uses group concat and concat_ws to assemble results
		//delimeter 1, deleter 2, db text, array of keys
		protected function mapDbGroupConcat($dl1,$dl2,$value,$keys){
			$data = array();

			//echo "in here with ".$value." and ".$keys;

			$groups = explode($dl1,$value);
			foreach($groups as $g){
				if($g != ""){
					$vals = explode($dl2, $g);
					// var_dump($keys);
					// var_dump($vals);
					// echo "break!!!\n\n\n\n";
					$data[] = array_combine($keys, $vals);
				}
			}
			return $data;
		}

		protected function pdoQuerySingleObj($sql, $data){
	        $stmt = $this->_pdo->prepare($sql);

	        try{
	            $stmt->execute($data);
	        }catch(PDOException $e){
	            //TODO: handle error in JSON
	            return $e->getMessage();
	            exit();
	        }        
	        return $stmt->fetch();

	    }

	    protected function pdoInsert($sql,$data = array()){
	        $stmt = $this->_pdo->prepare($sql);

	        try{
	            $stmt->execute($data);
	        }catch(PDOException $e){
	            //TODO: handle error in JSON
	            return $e->getMessage();
	            exit();
	        }

	        return $this->_pdo->lastInsertId();
	    }

		public function generateForm($name,$data=array()){
			$this->form->makeFromDB($name);
		
			//right now the form is echoing out content, so this will capture it's output
			//TODO: convert form functions to return form as string, not output html
			ob_start();
			$this->form->render($data);
			$formHTML = ob_get_contents();
			ob_end_clean();

			return $formHTML;
		}
	}
?>