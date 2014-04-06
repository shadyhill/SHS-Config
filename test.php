<?php
	$connect1 = "127.0.0.1";
	$connect2 = "root";
	$connect3 = "tiger4";
	$db_name = "atx_thirdthird";
	
	try{	
		$pdo = new PDO("mysql:host=$connect1;dbname=$db_name",$connect2,$connect3,
    				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    					  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    					  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}catch(PDOException $e){  
    	echo $e->getMessage();
    	exit();
    }

    $sql = "SELECT * FROM ttt_articles";
    $res = $pdo->query($sql);

    while($obj = $res->fetch()){
    	$author = $obj->author;
        $title = $obj->title;
        $topic = $obj->topic;
        $body = $obj->article;
        $stamp = $obj->stamp;
        $aid = $obj->article_id;

        //create the slug
        $slug = strtolower($title);
        $slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
        $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
        $slug = preg_replace("/\s/", "-", $slug);

        //find the author id
        if($author == "An Sentilles") $author = "Ann Sentilles";
        $sql2 = "SELECT id FROM author WHERE name = :name";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(array("name"=>$author));
        $aObj = $stmt2->fetch();
        $authorID = $aObj->id;

        //create the markdown version of the body
        require 'vendor/autoload.php';
        $converter = new Markdownify\Converter;
        $mdBody = $converter->parseString($body);

        $sqlA = "INSERT INTO article 
                    (id, author_id, title, slug, body, status, publish_date, visibility, old_id)
                    values
                    (0, :aid, :title, :slug, :body, 'published', :publish, 'public', :old)";
        $stmtA = $pdo->prepare($sqlA);

        try{
            $stmtA->execute(array(
                                "aid" => $authorID,
                                "title" => $title,
                                "slug" => $slug,
                                "body" => $mdBody,
                                "publish" => $stamp,
                                "old" => $aid));
            $newID = $pdo->lastInsertId();        
        }catch(PDOExecption $e) {             
            print "Error!: " . $e->getMessage() . "</br>"; 
        }
        

        //assign the category
        switch($topic){
            case "AGING PARENTS":
                $newCat = 1;
                break;
            case "CURRENT":
                $newCat = 5;
                break;
            case "FINANCE":
                $newCat = 4;
                break;
            case "FINANCES":
                $newCat = 4;
                break;
            case "HEALTH":
                $newCat = 2;
                break;
            case "LIFESTYLE":
                $newCat = 4;
                break;
            case "RELATIONSHIPS":
                $newCat = 3;
                break;
            case "RETIREMENT":
                $newCat = 4;
                break;
            case "SPIRITUALITY":
                $newCat = 5;
                break;
        }

        $sqlC = "INSERT INTO article_tags 
                    (id, article_id, tag_id, tag_type)
                    values
                    (0, :aid, :tid, 'category')";
        $stmtC = $pdo->prepare($sqlC);
        $stmtC->execute(array(
                        "aid" => $newID,
                        "tid" => $newCat));
        echo ".";

    }
?>