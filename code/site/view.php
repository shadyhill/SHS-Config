<?php
	require_once dirname(__FILE__)."/../base/view.php";
	
	class View extends BaseView{
		
		public function __construct($request_data){
			parent::__construct($request_data);
			
			//build out private, public, and processor urls
			//either as arrays or from database
		}
		
		public function render(){
			$vObj = $this->generateView();
		    $this->renderTemplate($vObj);		    		    
		}
		
		protected function generateView(){			
<<<<<<< HEAD
			include_once "views/".$this->_page->view_path."/".$this->_page->view_class.".php";						

			$class_els = explode("_",$this->_page->view_class);
			$obj = "";
			foreach($class_els as $el) $obj .= ucfirst($el);

			$vObj = new $obj($this);
			//$vObj = new $obj($toArray["_page"],$this->_pdo, $this->_session);					
=======
			include_once "views/".$this->_page->view_path."/".$this->_page->view_class.".php";			
			$toArray = get_object_vars($this);
			$vObj = new $this->_page->view_class($toArray["_page"]);					
>>>>>>> d4a80637a0066a2bee46ee1adf2f62abe3148141
			$vObj->{$this->_page->view_fx}();

			//must return the object
			return $vObj;

		}

		//CAN THIS FUNCTION HAVE CONDITIONALS FOR LOGGED IN OR NOT?
		protected function renderTemplate($vObj){

			$loader = new Twig_Loader_Filesystem(dirname(__FILE__)."/templates");
			$twig = new Twig_Environment($loader, array(
    			//'cache' => dirname(__FILE__)."/templates/cache",
			));	
			$aData = get_object_vars($vObj);
<<<<<<< HEAD

			// echo "<pre>";
			// var_dump($aData);
			// echo "</pre>";
=======
>>>>>>> d4a80637a0066a2bee46ee1adf2f62abe3148141
			
			//this calls the actual template			
			echo $twig->render($this->_page->template, $aData);
	
		}
		
	}
?>