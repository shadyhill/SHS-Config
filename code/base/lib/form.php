<?php 
include_once dirname(__FILE__)."/../magicobjs.php";
include_once dirname(__FILE__)."/fields.php";

class Form extends MagicObjs{
	
	//local class variables
	protected $_formIDs;
	protected $_defaultWidth;
	protected $_class;
	
	
	protected $_formName;
	protected $_formAction;
	protected $_formMethod;
	protected $_formType;
	protected $_formOnSubmit;
	protected $_formClass;
	protected $_formEncoding;
	protected $_formDisplayError;
	
	protected $_dbSelectMenus;
	
	protected $_submitTxt;
	
	protected $_formFields;
	
	
	public function __construct($pdo,$session){
		parent::__construct($pdo,$session);
		
		$this->_formIDs = array();
		$this->_defaultWidth = 300;

		$this->_dbSelectMenus = array();
		$this->_formFields = array();
	}
	
	public function makeFromDB($name){
		
		$this->_formFields = array();
		$first = TRUE;
		
		$sql = "SELECT *
					FROM config_forms f 
					LEFT JOIN config_form_fields ff on f.id = ff.form_id 
					WHERE f.form_name = :form_name
					ORDER BY ff.f_order ASC";
		
		$stmt = $this->_pdo->prepare($sql);		
		$stmt->execute(array("form_name" => $name));
				
		while($obj = $stmt->fetch()){			
			//grab the form details
			if($first){
				$this->_formName 			= $obj->form_name;
				$this->_formAction 			= $obj->action;
				$this->_formMethod 			= $obj->method;
				$this->_formType 			= $obj->form_type;
				$this->_formOnSubmit 		= $obj->onsubmit;
				$this->_formClass 			= $obj->form_class;
				$this->_formDisplayError 	= $obj->has_error_field;
				$this->_formEncoding		= $obj->encoding;
				$this->_submitTxt 			= $obj->button_txt;
				$this->_preProcess			= $obj->pre_process;
				$this->_postProcess			= $obj->post_process;
				$this->_ajaxURL				= $obj->ajax_url;				
				
				$first = FALSE;
				
				if($this->_formClass == "") $this->_formClass = "defaultForm";
			}
			
			//probably need to add a check to see if the fields are actually populated before adding them to the array
			$field = new FormField($this->_pdo,$this->_session);
			$field->makeFromData($obj);
			$this->_formFields[] = $field;
		}
	}
	
	public function addDBSelectMenu($index,$values,$displays){		
		$this->_dbSelectMenu[$index] = array("values" => implode(",",$values),"displays" => implode(",",$displays));
	}
	
	//render will accept values as an associative array to handle existin values
	public function render($values = array()){
		
		$this->renderStartForm();
		foreach($this->_formFields as $field){
			if(isset($values[$field->name_id])) $field->renderField($values[$field->name_id]);
			else $field->renderField('');
		}
		
		$this->renderSubmit($this->_submitTxt);
		$this->renderEnd();
		if($this->_formDisplayError) $this->renderFormE();
	}
	
	//yeah, it just calls helper functions
	protected function renderStartForm(){
		echo "<form ";
		$this->renderFormID();
		$this->renderMethod();
		$this->renderAction();
		$this->renderOnSubmit();
		$this->renderEncoding();
		$this->renderClass();
		echo ">";
		$this->renderHiddenFormSettings();
	}
	
	private function renderHiddenFormSettings(){
		if($this->_preProcess != "") $this->renderHiddenField("pre_process",$this->_preProcess);
		if($this->_postProcess != "") $this->renderHiddenField("post_process",$this->_postProcess);
		if($this->_ajaxURL != "") $this->renderHiddenField("ajax_url",$this->_ajaxURL);
	}
	
	private function renderFormID(){
		$name = strtolower(str_replace(" ", "_", $this->_formName));
		echo "id='$name' ";
	}
	
	private function renderMethod(){
		echo "method='$this->_formMethod' ";
	}
	
	private function renderAction(){
		if($this->_formAction != ""){
			if($this->_formType == "AJAX") echo "action='$this->_formAction' ";
			else echo "action='".S_CUR_URL."$this->_formAction'";
		}
	}
	
	private function renderOnSubmit(){
		if($this->_formOnSubmit != "") echo "onsubmit='return $this->_formOnSubmit(this);' ";
	}
	
	private function renderEncoding(){
		if($this->_formEncoding != "") echo "enctype='$this->_formEncoding' ";
	}
	
	private function renderClass(){
		echo "class='$this->_formClass col-sm-12' ";
	}
	
	protected function renderStart($action,$isFile = false){
		echo "<form role='form' action='".S_CUR_URL."$action' method='post' id='".$this->_class."Form' ";
		if($isFile) echo 'enctype="multipart/form-data" ';
		echo ">";
	}
	
	protected function renderAJAXStart($action,$onsubmit){
		echo "<form method='post' action='#$action' onsubmit='return $onsubmit;' id='".$this->_class."Form'>";
	}
	
	protected function renderEnd(){
		echo "</form>";
	}
	
	protected function renderFormE(){
		echo "<div class='formE'>";
			if(isset($_GET['e'])){
				switch($_GET['e']){
					case "failed-creation":	echo "Failed to create entry in database. Please try again.";		break;
					case "failed-update":	echo "Failed to update entry in the database. Please try again.";	break;
					default: echo "&nbsp;";
				}
			}else echo "&nbsp;";
		echo "</div>";
	}
	
	protected function renderSubmit($label = "Submit"){
		echo "<span class='clearfix'></span>";
		echo '<div class="form-group col-sm-12 clearfix">';
		echo '<div class="controls">';
		echo "<button type='submit' class='submit btn'>$label</button>";
		//echo "<input type='submit' value='$label' class='submit btn' />";
		echo '</div>';
		echo '</div>';
	}
	
	
	protected function renderMultiSelect($id,$label,$displays,$values,$select = "",$width = ""){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;
		
		
		$displays 	= explode(",",$displays);
		$values 	= explode(",",$values);
		
		$vals = array();

		if($select != ""){
			$vals = explode("~", $select);
		}
		
		?>
		<div class="control-group">
		<?php 
		$this->renderLabel($label,$id);
		echo '<div class="controls">';
		echo '<select multiple="multiple" id="'.$id.'[]" name="'.$id.'[]" style="width: '.$width.'px; height: 70px;">';
		foreach($displays as $key => $d){
			echo '<option value="'.$values[$key].'"';
			if(in_array($values[$key], $vals)) echo 'selected="selected"';
			echo '>'.$d.'</option>';
		}
		echo '</select>';
		echo '</div>';
		echo '</div>';

	}
	
	protected function renderHiddenField($id,$value){
		$this->_formIDs[] = $id;
		echo "<input type='hidden' name='$id' id='$id' value='$value' />";
	}
		
	
	protected function renderFileField($id,$label,$value = ""){
		$this->_formIDs[] = $id;
		$this->renderLabel($label,$id);
		echo '<div class="'.$this->_class.'Div">';
		echo "<input type='file' name='$id' style='margin-bottom:20px;' id='$id' />";
		if($value != "") echo "<span> Current File: $value</span>";
		echo '</div>';
	}
	
	public function renderDateField($type,$id,$label, $value ="", $width = "", $right = false){
		if($width == "") $width = $this->_defaultWidth;
		$this->_formIDs[] = $id;				
		$this->renderLabel($label,$id);
		if($value == '0000-00-00'){
			$value = "";
		}
		if(($value!='')&&($value!='0000-00-00')){
			$value = date('m/d/Y',strtotime($value));
		}
		if($this->_multiObjs){
			$id .= '-'.$this->_multiCount;	
		}
		?>
		<div class="<?php echo $this->_class?>Div">
			<input type="text" name="<?php echo $id?>" id="<?php echo $id?>" value="<?php echo $value;?>" class="datepicker <?php echo $this->_class?>Txt <?php if($right) echo "aRight"; ?>" style="width: <?php echo $width?>px;"  />
		</div>
		<?php 
	}
	
		
}

?>