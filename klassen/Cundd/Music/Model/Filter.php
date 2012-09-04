<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Music_Model_Filter erweitert Cundd_Core_Persistent.
 * @package Cundd_Core_Persistent
 * @version 1.0
 * @since Jan 23, 2010
 * @author daniel
 */
class Cundd_Music_Model_Filter extends Cundd_Core_Simple{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $filterString = '';
	public $filter = array();
	public $source = null; // Object
	
	protected $_propertySourcePair = array();
	protected $_filterMapping = array();
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	public function __construct(array $arguments = array()){		
		parent::__construct($arguments);
		$this->_init($arguments);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode initialisiert das Objekt.
	 * @WARNING: "+" in a GET-call is substituted with " " (whitespace) since that the 
	 * standard-delimiter is " " (whitespace) 
	 * @param array $arguments
	 */
	protected function _init(array $arguments){
		if(CunddConfig::__('Music/filter_delimiter')){
			$delimiter = CunddConfig::__('Music/filter_delimiter');
		} else {
			$delimiter = ' ';
		}
		
		
		$this->_registerProperties($arguments);
		if(array_key_exists('filterString',$arguments)){
			foreach(explode($delimiter,$arguments['filterString']) as $filter){
				$filter = $this->_mapFilter($filter);
				if($filter){
					$this->filter[] = $filter;
				}
			}
		} else if(array_key_exists('filter',$arguments)){
			foreach(explode($delimiter,$arguments['filter']) as $filter){
				$filter = $this->_mapFilter($filter);
				if($filter){
					$this->filter[] = $filter;
				}
			}
		}
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt ein Array mit einem Property/Value-Pair zurück.
	 * @param array $arguments[optional]
	 * @return array
	 */
	public function getFilter(array $arguments = array()){
		if(count($arguments) > 0){
			$this->_init($arguments);
		}
		if(!$this->_propertySourcePair){
			return $this->_createPropertySourcePair();
		} else {
			return $this->_propertySourcePair;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt ein Array mit einem Property/Value-Pair zurück.
	 * @return array
	 */
	protected function _createPropertySourcePair($propertyPrefix = '_'){
		$return = array();
		if($this->filter AND $this->source){
			foreach($this->filter as $filter){
				$return[$filter] = $this->source->getValue($propertyPrefix.$filter);
			}
		}
		$this->_propertySourcePair = $return;
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode merged das Property/Source-Pair mit dem Input-Array.
	 * @param array $inputArray
	 * @return array
	 */
	public function mergeWith(array $inputArray){
		$filter = $this->getFilter();
		
		// $this->pd($filter);
		// $this->pd($this->getReference());
		return array_merge($inputArray,$filter);
	}
		
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Reference-Singleton zurück.
	 * @return Cundd_Music_Model_Reference
	 */
	public function getReference(){
		return Cundd::getModel('Music/Reference');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht für den übergebenen Filter eine Entsprechung zu finden. Dies 
	 * ermöglicht beispielsweise, dass der GET-Parameter-Filter "color" den Filter 
	 * "mainColor" einsetzt. */
	protected function _mapFilter($filter){
		if(count($this->_filterMapping) < 1)$this->_filterMapping = CunddConfig::__('Music/filter_mapping');
		
		if(array_key_exists($filter,$this->_filterMapping)){
			return $this->_filterMapping[$filter];
		} else {
			return $filter;
		}
	}
}
?>