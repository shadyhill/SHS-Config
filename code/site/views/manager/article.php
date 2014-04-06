<?php
use \Michelf\Markdown;
require_once dirname(__FILE__)."/../viewobj.php";

class Article extends ViewObj{
	
	//$meta would be stuff like css includes, js includes, and meta tags
	public function __construct($view){
		parent::__construct($view);		
	}

	public function index(){							
		$sql = "SELECT a.*, au.name as author
					FROM article a 
					LEFT JOIN author au on a.author_id = au.id
					ORDER BY publish_date DESC";
		$res = $this->_pdo->query($sql);	
		$this->articles = $res->fetchAll(PDO::FETCH_ASSOC);							
	}

	public function edit(){
		//get the id of the article
		$aid = $this->urlObj->id;

		//make the query to get the article info
		$sql = "SELECT a.*, au.name as author
					FROM article a 
					LEFT JOIN author au on a.author_id = au.id
					WHERE a.id = :id";
		$stmt = $this->_pdo->prepare($sql);
		$stmt->execute(array('id'=>$aid));
		$this->article = $stmt->fetch();

		//generate the form
		$this->myform = $this->generateForm("Article Edit",get_object_vars($this->article));
	}

}

?>