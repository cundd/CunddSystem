<?php
if(!class_exists('CunddForm')){
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * CunddForm kann zum Erstellen von (einfachen) Formularen verwendet werden. An den 
 * Konstruktor wird ein Array mit den gewünschten Inputs, die Aktion die ausgeführt 
 * werden soll (per POST, GET, oder mittels Ajax verarbeitet). Zusätzliche optionale 
 * Parameter sind der Name des Formulars, das Recht des aktuellen Benutzers, die ID des 
 * Target-DIVs bei Ajax-Aufrufen, sowie ein Array mit weiteren Optionen für das Formular.
 */
class CunddForm {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $formname;
	private $formoutput;
	private $debug = fals;
	
	protected $_method = '';
	protected $_headAction = '';
	
	
	const GET = 'get';
	const AJAX = 'ajax';
	const POST = 'post';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * Aus dem übergebenen Array $inputs werden automatisch die Felder eines Formulars 
	 * erstellt das ausgefüllte Formular wird an den Ajax-Controller gesendet, 
	 * CunddController übergibt dann im Weiteren die Daten an das durch $action definierten 
	 * Modul.
	 * @param array $inputs array( array('name' => inputname,'type' => inputtype [,'options' => mixed] ) [,array(...)] );
	 * @param string $action
	 * @param string $formname[optional]
	 * @param int $right[optional]
	 * @param string $class[optional]
	 * @param string $targetDivId[optional]
	 * @param array $formoptions[optional]
	 * @return string
	 */
	function CunddForm(array $inputs,$action,$formname = 'CunddForm',$right = 6,$class = 'CunddForm',$targetDivId = 'CunddContent_div',array $formoptions = array()){
		/*
		$inputs = array(
			array('name' => 'showVC','type' => 'text',),
			array('showVC','text',),
		);
			/* */
		
		
		
		// Überprüfen ob es kein Ajax-Aufruf sein soll
		if(strpos($action,'POST:') !== false){
			$this->_method = self::POST;
			$this->_headAction = str_replace('POST:','',$action);
		} else if(strpos($action,'GET:') !== false){
			$this->_method = self::GET;
			$this->_headAction = str_replace('GET:','',$action);
		} else {
			$this->_method = self::AJAX;
			$this->_headAction = '#';
		}
		
		// Die Head-Action richtig ausfüllen wenn es kein AJAX-Aufruf ist und die Action 'SELF' lautet
		if($this->_method != self::AJAX AND $this->_headAction == 'SELF'){
			$this->_headAction = Cundd_Request::getUrl();
		}
		
		
		
		$wert['formname'] = $formname;
		$this->formname = $formname;
		
		$wert['formid'] = $formname.time();
		$wert['action'] = $action;
		$wert['class'] = $class;
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Der Kopf des Formulars
		switch($this->_method){
			case self::AJAX:
				$wert['method'] = 'post';
				$wert['headaction'] = $this->_headAction;
				break;
				
			case self::POST:
			case self::GET:
				$wert['method'] = $this->_method;
				$wert['headaction'] = $this->_headAction;
				break;
				
			default:
				$wert['method'] = 'post';
				$wert['headaction'] = $this->_headAction;
				break;
		}
		
		$tag = 'Cundd_Form_Standard_Head';
		$type = 'special'; 
		$output .= CunddTemplate::__($wert,$right,$tag,$type);
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Die einzelnen Formularfelder
		foreach($inputs as $key => $input){
			// Den Namen und Typ bestimmen
			if(array_key_exists('name',$input)){
				$inputname = $input['name']; 
			} else {
				$inputname = $input[0];
			}
			if(array_key_exists('type',$input)){
				$inputtype = $input['type']; 
			} else {
				$inputtype = $input[1];
			}
			if(array_key_exists('options',$input)){
				$options = $input['options'];
			} else if(count($input) > 2){
				$options = $input[2];
			} else {
				$options = NULL;
			}

			switch(strtolower($inputtype)){
				case 'textarea':
					$output .= $this->textarea($inputname,$inputtype,$options,$right);
					break;
				
				case 'rte':
				case 'tinymce':
					$output .= $this->tinymce($inputname,$inputtype,$options,$right);
					break;
					
				case 'radiobutton':
					$output .= $this->radiobutton($inputname,$inputtype,$options,$right);
					break;
					
				case 'checkonoff':
				case 'checkbox':
				case 'checkboxonoff':
				case 'checkboxbool':
				case 'checkboxboolean':
				case 'checkbool':
				case 'checkboolean':
					$output .= $this->checkboxOnOff($inputname,$inputtype,$options,$right);
					break;
					
				case 'radioonoff':
				case 'radiobuttononoff':
					$output .= $this->radioOnOff($inputname,$inputtype,$options,$right);
					break;
					
				case 'radio':
				case 'radiobutton':
					$output .= $this->radiobutton($inputname,$inputtype,$options,$right);
					break;
					
				case 'text':
				case 'textbox':
					$output .= $this->textbox($inputname,$inputtype,$options,$right);
					break;
				
				case 'output':
					$output .= $this->output($inputname,$inputtype,$options,$right);
					break;
					
				case 'hidden':
					$output .= $this->hidden($inputname,$inputtype,$options,$right);
					break;
					
				case 'file':
					$output .= $this->file($inputname,$inputtype,$options,$right);
					
				default:
					$error = "<br />Couldn't generate Input for field $inputname with the type $inputtype<br />";
					
					/* DEBUGGEN */if($this->debug) $output .= $error;/* DEBUGGEN */
					break;
			}
		}
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Der Submit-Button
		$tag = "Cundd_Form_Standard_Submit";
		$wert['label'] = CunddLang::get('submit');
		$type = 'special';
		$output .= CunddTemplate::__($wert,$right,$tag,$type);
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Der Fuß des Formulars
		$tag = "Cundd_Form_Standard_Foot";
		$type = 'output';
		$output .= CunddTemplate::__($wert,$right,$tag,$type);
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Der zum Versenden benötigte JavaScript-Code
		if($this->_method == self::AJAX){
			$wert['aufruf'] = $action;
			$wert['action'] &= $wert['aufruf'];
			$wert['targetDivId'] = $targetDivId;
			
			
			$tag = "Cundd_Form_Standard_JavaScriptCode";
			$type = 'special';
			$output .= CunddTemplate::__($wert,$right,$tag,$type);
		}
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Den Fokus setzen
		if(array_key_exists('focus',$formoptions)){
			$wert['id'] = $formoptions['focus'];
			
			$tag = "Cundd_Form_Standard_Setfocus";
			$type = 'special';
			$output .= CunddTemplate::__($wert,$right,$tag,$type);
		}
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Den Output zurückgeben
		$this->formoutput = $output;
		return $output;
	}
	/*
	 * var inhalt = [];
		inhalt = $('#'+aufrufer.id).serializeArray();
		
		var aufruf = {};
		// Der passende Aufruf, der an "CunddAjax.php" weitergeleitet wird
		aufruf = {name: "aufruf", value: "CunddBenutzer"};
		
		inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
		
		new CunddUpdate({
						datei: CunddAjaxPHP_verweis,
						data: inhalt,
						targetId: CunddContent_div
						}
						);
	 */
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode gibt das bei der Initialisierung erstellte Formular aus.
	  * @return string|false
	  */
	 public function render(){
	 	if(isset($this->formoutput)){
		 	echo $this->formoutput;
		 	return $this->formoutput;
	 	} else {
		 	return false;
	 	} 
	 }
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode gibt den Output des Objekts zurück.
	  * @return string
	  */
	 public function getFormoutput(){
	 	return $this->formoutput;
	 }
	 public function getOutput(){
	 	return $this->getFormoutput();
	 }
	 
	 
	
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function checkboxOnOff($inputname,$inputtype,$options,$right){
	 	// TODO: Handle checked
		$value = 1;
		if($options){
			if(array_key_exists('onchecked',$options)) $onChecked = $options['onchecked'];
		}
		
		$checkboxInput = $inputname;
		$output .= CunddTemplate::createCheckboxes($checkboxInput,$inputname,$checked,$value,$otherData);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function radioOnOff($inputname,$inputtype,$options = array(),$right = 6){
	 	/* array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] ); */
		// TODO: Handle checked
		if(array_key_exists('onchecked',$options)) $onChecked = $options['onchecked'];
		if($onChecked){
			$offChecked = NULL;
		} else {
			$onChecked = NULL;
			$offChecked = true;
		}
		
		$radioButtonCollection = array(
			array('on', 1, $onChecked),
			array('off', 0, $offChecked),
			);
		
		$output .= CunddTemplate::createRadioButtons($radioButtonCollection,$inputname,$otherData);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function radiobutton($inputname,$inputtype,$options = array(),$right = 6){
	 	/* array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] ); */
	 	if(array_key_exists('radioButtonCollection',$options)) $radioButtonCollection = $options['radioButtonCollection'];
		$output .= CunddTemplate::createRadioButtons($radioButtonCollection,$inputname,$options);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function textbox($inputname,$inputtype,$options = NULL,$right = 6){
	 	if($options){
	 		if(array_key_exists('required',$options)) 	$wert['required'] 	= $options['required'];
	 		if(array_key_exists('value',$options)) 		$wert['value'] 		= $options['value'];
	 		if(array_key_exists('size',$options)) 		$wert['size'] 		= $options['size'];
	 		if(array_key_exists('class',$options)) 		$wert['class'] 		= $options['class'];
	 		if(array_key_exists('label',$options)) 		$wert['label'] 		= $options['label'];
	 	}
	 	
		$tag = 'Cundd_Form_Standard_Text';
		$wert['class'] = $wert['class'].' '.$this->formname.' '.$inputname;
		$wert['name'] = $inputname;
		if(!$wert['label']) $wert['label'] = $inputname;
		
		$output .= CunddTemplate::__($wert,$right,$tag,'special',$required);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function textarea($inputname,$inputtype,$options = NULL,$right = 6){
	 	if($options){
	 		if(array_key_exists('required',$options)) 	$wert['required'] 	= $options['required'];
	 		if(array_key_exists('value',$options)) 		$wert['value'] 		= $options['value'];
	 		if(array_key_exists('class',$options)) 		$wert['class'] 		= $options['class'];
	 		if(array_key_exists('label',$options)) 		$wert['label'] 		= $options['label'];
	 	}
	 	
	 	
	 	$tag = 'Cundd_Form_Standard_Textarea';
		$wert['class'] = $wert['class'].' '.$this->formname.' '.$inputname;
		$wert['name'] = $inputname;
		if(!$wert['label']) $wert['label'] = $inputname;
		
		$output .= CunddTemplate::__($wert,$right,$tag,'special',$required);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function tinymce($inputname,$inputtype,$options = NULL,$right = 6){
	 	if($options){
	 		if(array_key_exists('required',$options)) 	$wert['required'] 	= $options['required'];
	 		if(array_key_exists('value',$options)) 		$wert['value'] 		= $options['value'];
	 		if(array_key_exists('class',$options)) 		$wert['class'] 		= $options['class'];
	 	}
	 	
	 	// Der tag wird als Input-Name ausgegeben, deshalb wird der Input-Name als tag definiert
	 	// $tag = 'Cundd_Form_Standard_Rte';
	 	$tag = $inputname;
		$wert['label'] = $inputname;
		$wert['name'] = $inputname;
		$wert['class'] = $wert['class'].' '.$this->formname.' '.$inputname;
		if($wert['value']){
			$wert[$tag] = $wert['value'];
		} else {
			$wert[$tag] = CunddLang::get('Click here to insert text');
		}
		
		$output .= CunddTemplate::__($wert,$right,$tag,'rte',$required);
		$output .= CunddTemplate::__($wert,$right,'Cundd_Form_Standard_RTE_Javascript','special',$required);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function output($inputname,$inputtype,$options = NULL,$right = 6){
	 	if($options){
	 		if(array_key_exists('required',$options)) 	$wert['required'] 	= $options['required'];
	 		if(array_key_exists('value',$options)) 		$wert['value'] 		= $options['value'];
	 		if(array_key_exists('class',$options)) 		$wert['class'] 		= $options['class'];
	 		if(array_key_exists('output',$options)) 	$wert['output'] 	= $options['output'];
	 	}
	 	
	 	$tag = 'Cundd_Form_Standard_Output';
		$wert['class'] = $wert['class'].' '.$this->formname.' '.$inputname;
		$wert['label'] = $inputname;
		$wert['name'] = $inputname;
		
		$output .= CunddTemplate::__($wert,$right,$tag,'output',$required);
		return $output;
	 }
	 
	 
	 
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	 /** 
	  * Die Methode . */
	 private function hidden($inputname,$inputtype,$value,$right = 6){
	 	$tag = 'Cundd_Form_Standard_Hidden';
		$wert['class'] = $wert['class'].' '.$this->formname.' '.$inputname;
		$wert['label'] = $inputname;
		$wert['name'] = $inputname;
		$wert['value'] = $value;
		
		$output .= CunddTemplate::__($wert,$right,$tag,'output',$required);
		return $output;
	 }
	 
	 
	 
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode . */
	private function file($inputname,$inputtype,$options = NULL,$right = 6){
		if($options){
			if(array_key_exists('required',$options)) 	$wert['required'] 	= $options['required'];
			if(array_key_exists('value',$options)) 		$wert['value'] 		= $options['value'];
			if(array_key_exists('class',$options)) 		$wert['class'] 		= $options['class'];
			if(array_key_exists('label',$options)) 		$wert['label'] 		= $options['label'];			
		}
		
		$tag = 'Cundd_Form_Standard_File';
		$wert['class'] = $wert['class'].' '.$this->formname.' '.$inputname;
		
		if(!array_key_exists('label',$wert))	$wert['label'] = $inputname;
		$wert['name'] = $inputname;
		
		$output .= CunddTemplate::__($wert,$right,$tag,'output',$required);
		return $output;
	}
}
} // END OF CLASS_EXISTS