<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    
    <meta name="description" content="<?php echo $this->_page->meta_description?>" />
    <meta name="keywords" content="<?php echo $this->_page->meta_keywords?>" />
    <meta name="author" content="Shady Hill Studios - www.shadyhillstudios.com" />      
    
    <title><?php echo $this->_page->meta_title?></title>
    
    <link rel="stylesheet" href="<?php echo A_URL?>assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo A_URL?>assets/css/font-awesome-4.0.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo A_URL?>assets/css/ionicons/ionicons.min.css">
    
    <link href="<?php echo A_URL?>assets/css/site.css" rel="stylesheet" type="text/css" />
<?php
    foreach($this->_cssFiles as $css){
		if($css != "") echo '<link href="'.A_URL.'assets/css/'.$css.'" rel="stylesheet" type="text/css" />';
	}
?>
    
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,300,400,700" rel="stylesheet" type="text/css">


</head>
<?php flush();?>
<body>
