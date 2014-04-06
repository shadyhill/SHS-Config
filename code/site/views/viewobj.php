<?php	
	include_once dirname(__FILE__)."/../../base/lib/form.php";

	class ViewObj{

		protected $page;
		protected $urlObj;
		protected $form;
		protected $_pdo;
		protected $_session;

		public function __construct($view){
			$this->_pdo = $view->returnPDO();
			$this->_session = $view->returnSession();
			$this->page = $view->returnPage();
			$this->urlObj = $view->returnURLObj();

			$this->form = new Form($this->_pdo,$this->_session);
		}

		//need a way to return all public class variables as an array
		//this is just a placeholder, not ready for use
		public function toArray(){
			return array();
		}

		public function generateForm($name,$data = array()){
			$this->form->makeFromDB($name);
		
			//right now the form is echoing out content, so this will capture it's output
			ob_start();
			$this->form->render($data);
			$formHTML = ob_get_contents();
			ob_end_clean();

			return $formHTML;
		}
	}
?>