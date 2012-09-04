<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Flash_Model_Abstract erweitert Cundd_Core_Model_Abstract.
 * @package Cundd_Flash
 * @version 1.0
 * @since Jan 6, 2010
 * @author daniel
 */
abstract class Cundd_Flash_Model_Abstract extends Cundd_Core_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $type = '';
	public $x = 0;
	public $y = 0;
	public $width = NULL;
	public $height = NULL;
	public $rotation = NULL;
	public $alpha = NULL;
	public $name = NULL;
	public $id = 0;
	public $data = array();
	
	protected $_isVisible = true;
	protected $_debug = true;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Der Konstruktor speichert die per Argument übergebenen Eigenschaften. Die wichtigsten
	 * sind type, x, y, width, height und rotation. Die Eigenschaft type muss vor dem 
	 * Rendern gesetzt sein!
	 * @param array $arguments
	 * @return Cundd_Flash_Model_Abstract
	 */
	public function __construct(array $arguments = array()){
		parent::__construct();
		$this->_registerProperties($arguments);
		$this->_setEventlisteners($arguments);
		
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt zurück of das Objekt in Flash visible ist.
	 * @return boolean
	 */
	public function isVisible(){
		return (bool) $this->_isVisible;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Klasse des Objekts in Flash zurück.
	 * @return void
	 */
	public function getActionScriptObjectType(){
		return $this->type;
	}
	public function getType(){
		return $this->getActionScriptObjectType();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die zusätzlichen Daten des Objekts zurück.
	 * @return array
	 */
	public function getData(){
		return $this->_mutableProperties;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die zusätzlichen Daten des Objekts.
	 * @param array $data
	 * @return void
	 */
	public function setData(array $data){
		 $this->_mutableProperties = $data;
		 return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode fügt zu den zusätzlichen Daten des Objekts neue Daten hinzu.
	 * @param array $data array( name => value [ , name => value ] )
	 * @return void
	 */
	public function addData(array $data){
		foreach($data as $key => $value){
			$this->$key = $value;
			
			$this->data[$key] = $value; 
		}
		
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Eigenschaft $eventlisteners. Dabei ist die Übergabe mittels
	 * unterschiedlicher Argument-Keys möglich.
	 * @param array $arguments
	 */
	protected function _setEventlisteners($arguments){
		$this->_setIfKeyExists('eventListeners',$arguments,$this->eventlisteners);
		$this->_setIfKeyExists('eventlisteners',$arguments,$this->eventlisteners);
		$this->_setIfKeyExists('eventListener',$arguments,$this->eventlisteners);
		$this->_setIfKeyExists('eventlistener',$arguments,$this->eventlisteners);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt die View mittels Cundd_Flash_View_Flash, wenn die Eigenschaft 
	 * type gesetzt ist. */
	public function render(){
		if($this->type){
			$view = new Cundd_Flash_View_Flash($this);
			echo $view;
		} else if($this->_debug){
			throw new Exception("The type of the object hasn't been set.");
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überschreibt die magic __get()-Methode. */
	/*public function __get($name){
		if($name == 'data'){
			return $this->_mutableProperties;
		} else {
			return parent::__get($name);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#__set($name, $value)
	 */
	public function __set($name,$value){
		parent::__set($name,$value);
		
		
		$classVars = get_object_vars($this);
		// Überprüfen ob der aktuelle Name der Name einer Eigenschaft ist
		if(!array_key_exists($name,$classVars) AND $this->_isMutable()){
			$this->addData(array($name => $value));
		} else {
			// Nothing
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isMutable()
	 */
	protected function _isMutable(){
		return (bool) true;
	}
	
}
?>