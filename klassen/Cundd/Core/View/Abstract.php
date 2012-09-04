<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Core_View_Abstract erweitert Cundd_Core_Simple.
 * @package Cundd_Core_Simple
 * @version 1.0
 * @since Jan 12, 2010
 * @author daniel
 */
abstract class Cundd_Core_View_Abstract extends Cundd_Core_Mutable{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $template = '';
	
	private $_output = '';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * @see init()
	 */
/*	public function __construct($type = 'cundd'){
		return Cundd_Core_View_Abstract::init($type);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt je nach Parameter eine Instanz der Zend_View-Subclass oder einer 
	 * Cundd-Template-Subclass zurück. Die Zend-Version ermöglicht das Rendern auf Basis der 
	 * Module-View, die Cundd-Version hingegen dient für die Verwendung des Cundd-Template-
	 * Systems und View-Blocks im System-Layout-Folder. 
	 * @param string $type 'cundd'|'zend'
	 * @return Cundd_Core_View_Cundd|Cundd_Core_View_Zend
	 */
	public static function getInstance($type = 'cundd'){
		if($type == 'cundd'){
			return Cundd::getView();
		} else if($type == 'zend'){
			return new Cundd_Core_View_Zend();
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ruft die Methode CunddTemplate::__() auf.
	 * @param array|string $para
	 * @return string
	 */
	public function __($para,$buffer = false){
		if(is_a($para,'array')){
			$this->_registerProperties($para);
		} else if(is_a($para,'string')){
			$this->template = $para;
		}
		
		/* Die Eigenschaften der Klasse werden mit $para kombiniert, wobei die Werte des 
		 * Arguments $para die der Eigenschaften überschreiben. */
		$paraToSend = array_merge($this->_mutableProperties,$para);
		
		$tag = $this->template;
		$right = '';
		if(!$this->_setIfKeyExists('recht',$paraToSend,$right)) $this->_setIfKeyExists('right',$paraToSend,$right,6664);
		
		$type = '';
		$this->_setIfKeyExists('type',$paraToSend,$type);
		
		$this->_setIfKeyExists('require',$paraToSend,$required,0);
		
		
		// Den Output erstellen
		$localOutput = CunddTemplate::__($paraToSend,$right,$tag,$type,$required);
		if(!$buffer) $this->add($localOutput);
		return $localOutput;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode fügt dem Output einen String hinzu.
	 * @param string $outputToAdd
	 * @return string
	 */
	public function addOutput($outputToAdd){
		$this->_output = $this->_output.$outputToAdd;
		return $this->_output;
	}
	/**
	 * @see addOutput()
	 */
	public function add($outputToAdd){
		return $this->addOutput($outputToAdd);
	}
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode leert den Output. */
	public function clearOutput(){
		$this->_output = '';
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Wert der Eigenschaft $_output zurück. */
	public function render(){
		return $this->_output;
	}
	/**
	 * @see render()
	 */
	public function __toString(){
		return $this->render();
	}
}
?>