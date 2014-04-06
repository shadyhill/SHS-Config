	
    <!-- BEGIN END HTML TEMPLATE -->
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script> -->
    <script src="/assets/js/jquery/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="/assets/js/transit/transit.min.js" type="text/javascript"></script>
    <script src="/assets/js/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/js/jScripts.js" type="text/javascript"></script>


<?php
	foreach($this->_jsFiles as $js){
		if($js != "") echo '<script src="/assets/js/'.$js.'" type="text/javascript"></script>';
	}
?>

</body>
</html>	