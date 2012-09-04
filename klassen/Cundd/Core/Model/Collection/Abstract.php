<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Core_Model_Collection_Abstract erweitert Cundd_Core_Model_Abstract.
 * @package Cundd_Core_Model_Abstract
 * @version 1.0
 * @since Jan 18, 2010
 * @author daniel
 */
abstract class Cundd_Core_Model_Collection_Abstract extends Cundd_Core_Model_Abstract 
// implements Traversable 
{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $collection = array();
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	public function __construct($objects = null){
		if(!$objects){
			$objects = array();
		} else if(!gettype($objects) == 'array'){
			$objects = array($objects);
		}	
		parent::__construct($objects);
		$this->_registerCollection($objects);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Eigenschaft $collection anhand der übergebenen Objekte. */
	protected function _registerCollection($objects){
		if($objects){
			if(gettype($objects) == 'array'){
				$this->collection = $objects;
			} else if(gettype($objects) == 'object'){
				$this->collection[] = $objects;
			}
		}
	}
}
?>