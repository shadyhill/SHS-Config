	
    <!-- BEGIN END HTML TEMPLATE -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
    <!-- <script src="<?php echo A_URL?>assets/js/jquery/jquery-1.7.1.min.js" type="text/javascript"></script> -->
    <script src="<?php echo A_URL?>assets/js/transit/transit.min.js" type="text/javascript"></script>
    <script src="<?php echo A_URL?>assets/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo A_URL?>assets/js/jScripts.js" type="text/javascript"></script>


<?php
	foreach($this->_jsFiles as $js){
		if($js != "") echo '<script src="'.A_URL.'assets/js/'.$js.'" type="text/javascript"></script>';
	}
?>

</body>
</html>	