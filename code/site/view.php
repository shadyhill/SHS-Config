<?php
	require_once dirname(__FILE__)."/../base/view.php";
	
	class View extends BaseView{
		
		public function __construct($request_data){
			parent::__construct($request_data);
			
			//build out private, public, and processor urls
			//either as arrays or from database
		}
		
		public function render(){
		    $this->renderTemplate();
		    //echo "need to load template: ".$this->_page->template;
		    //echo "<br />need to load file: ".$this->_page->include_file;
		    
		    
		}
		
		//CAN THIS FUNCTION HAVE CONDITIONALS FOR LOGGED IN OR NOT?
		protected function renderTemplate(){
			//every page will use the start HTML template
			include dirname(__FILE__)."/templates/site/start-html.php";
			
			switch($this->_page->template){
				case "home":
					
					$this->loadView();
					break;
				case "app":
					$this->loadView();
					break;
				case "one-column":
					
					$this->loadView();
					
					break;
			}
			
			//and most will also use the end HTML template
			include dirname(__FILE__)."/templates/site/end-html.php";
		}
		
		
		protected function loadView(){
			include dirname(__FILE__)."/views/".$this->_page->include_file;
		}
	}
?>