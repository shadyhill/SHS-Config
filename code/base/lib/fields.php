<?php
include_once dirname(__FILE__)."/../magicobjs.php";

class FormField extends MagicObjs{
    
    public function __construct($pdo,$session){
    	parent::__construct($pdo,$session);
    }
    
    public function renderField($value = ""){
    	$this->value = $value;
	    switch($this->type){
		    case "text":
		    case "email":
		    case "tel":
		    case "password":
		    	$this->renderTextField();
		    	break;
		    case "textarea":
		    	$this->renderTextArea();
		    	break;
		    case "selectmenu":
		    	$this->renderSelectMenu();
		    	break;
		    case "checkbox":
		    	$this->renderCheckBox();
		    	break;
		    case "hidden":
		    	$this->renderHiddenField();
		    	break;
		    
	    }
    }
    
    protected function formatMadeData(){
	    $this->placeholder = htmlentities(stripslashes($this->placeholder), ENT_QUOTES);
    }
    
    
    private function renderLabel(){
	    echo "<label class='control-label' for='$this->name_id'>$this->label</label>";
    }
    
    private function renderTextField(){
    
	    echo "<div class='form-group $this->class_override'>";
		$this->renderLabel();
			echo '<div class="controls">';
				echo "<input type='$this->type' name='$this->name_id' id='$this->name_id' value='$this->value' placeholder='$this->placeholder' tabindex=$this->tab_order class='form-control' style='$this->style_override'  />";
			echo '</div>';
		echo '</div>';
    }
    
    private function renderTextArea(){
	    echo "<div class='form-group $this->class_override'>";
		$this->renderLabel();
			echo '<div class="controls">';
				echo "<textarea id='$this->name_id' name='$this->name_id' placeholder='$this->placeholder' rows='8' tabindex=$this->tab_order class='form-control' style='$this->style_override'>$this->value</textarea>";
			echo '</div>';
		echo '</div>';
    }
    
    private function renderSelectMenu(){		
		$displays 	= explode("|",$this->dd_displays);
		$values 	= explode("|",$this->dd_values);
		
		echo "<div class='form-group $this->class_override'>";
		$this->renderLabel();
			echo '<div class="controls">';
				echo "<select id='$this->name_id' name='$this->name_id' class='' style='$this->style_override'>";
		foreach($displays as $key => $d){
			echo '<option value="'.$values[$key].'"';
			if($values[$key] == $select) echo 'selected="selected"';
			echo '>'.$d.'</option>';
		}
				echo '</select>';
			echo '</div>';
		echo '</div>';

	}
	
	public function renderCheckBox(){
		
		echo "<div class='form-group $this->class_override'>";
		$this->renderLabel();
			echo '<div class="controls">';
				echo "<input type='checkbox' id='$this->name_id' name='$this->name_id' value='1' ";
				if($value == 1) echo "checked='checked' ";
				echo " /> $this->dd_displays";
			echo '</div>';
		echo '</div>';
	}
	
	protected function renderHiddenField(){
		echo "<input type='hidden' name='$this->name_id' id='$this->name_id' value='$this->value' />";
	}
    
    
}
?>