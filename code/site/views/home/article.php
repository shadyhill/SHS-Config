<?php
use \Michelf\Markdown;
require_once dirname(__FILE__)."/../viewobj.php";

class Article extends ViewObj{
	
	//$meta would be stuff like css includes, js includes, and meta tags
	public function __construct($view){
		parent::__construct($view);
		//nothing for now
		//but could have default include paths?
	}

	public function view(){				
		$slug = $this->urlObj->slug;		
		$sql = "SELECT a.*, au.name as author
					FROM article a 
					LEFT JOIN author au on a.author_id = au.id
					WHERE a.slug = :slug";
		$stmt = $this->_pdo->prepare($sql);
		$stmt->execute(array('slug'=>$slug));
		$this->article = $stmt->fetch();

		//convert the body into HTML, then generate special blocks		
		
		$this->body = Markdown::defaultTransform($this->article->body);

	}

	public function test(){
		//nothing for nows
	}

}

?>