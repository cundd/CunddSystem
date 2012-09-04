<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * The Cundd_Core_Protocol extends Cundd_Core_Abstract. It is the second class of the 
 * core-base-inheritance-chain. It offers a way of defining protocols like in objective-c
 * (see http://en.wikipedia.org/wiki/Objective-C#Protocols for informations). 
 * @package Cundd_Core
 * @version 1.0
 * @since Dec 21, 2009
 * @author daniel
 */
abstract class Cundd_Core_Protocol extends 
//Cundd_Core_Beginofinheritancechain
Cundd_Core_Managed
{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_cundd_protocol_protocolClasses = array();
	protected $_cundd_protocol_protocolClassesToInit = array();
	protected $_cundd_protocol_handleCallErrorsStrict = fals;
	protected $_cundd_protocol_debug = false;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt alle registrierten Protokolle zurück. */
	public function __construct(array $arguments = array()){
		parent::__construct($arguments);
		
		$protocols = array();
		$key = '_cundd_protocol_protocolClasses';
		$this->_setIfKeyExists($key,$arguments,$protocols);
		
		$this->_cundd_protocol_registerBaseProtocols();
		
		//return $this->_cundd_protocol_protocolClasses;
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode registriert die Basis-Protokolle. Darunter zum Beispiel 'CunddTools' */
	protected function _cundd_protocol_registerBaseProtocols(){
		$baseProtocols = CunddConfig::__('Core/base_protocols');
		return $this->cundd_protocol_addProtocols($baseProtocols);
	}
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt alle registrierten Protokolle zurück.
	 * @return array
	 */
	final public function cundd_protocol_getProtocols(){
		return $this->_cundd_protocol_protocolClasses;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Protocol#_cundd_protocol_getProtocols()
	 */
	public function getProtocols(){
		return $this->_cundd_protocol_getProtocols();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Protokolle der Klasse.
	 * @param array $protocols
	 * @return void
	 */
	final public function cundd_protocol_setProtocols(array $protocols){
		$this->_cundd_protocol_protocolClasses = $protocols;
		$this->_cundd_protocol_protocolClassesToInit = $protocols;
		
		$this->_cundd_protocol_initProtocols();
		return;
	}
	public function setProtocols(array $protocols){
		return $this->cundd_protocol_setProtocols($protocols);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode startet ruft die _cundd_protocol_init()-Methode für jedes registrierte 
	 * Protokol auf.
	 * @return array
	 */
	protected function _cundd_protocol_initProtocols(){
		$returns = array();
		$keysToDelete = array();
		foreach($this->_cundd_protocol_protocolClassesToInit as $key => $protocolClass){
			$methodName = '_cundd_protocol_init'.$protocolClass;
			
			if(array_key_exists($methodName,get_class_methods($protocolClass))){
				//static -> $returns[$key] = call_user_func(array($protocolClass),'_cundd_protocol_init');
				$returns[$key] = $this->$methodName();
			}
			$keysToDelete[] = $key;
		}
		
		// Clean up
		foreach($keysToDelete as $key){
			unset($this->_cundd_protocol_protocolClassesToInit[$key]);
		}
		return $returns;
	}

	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode fügt die übergebenen Protokolle hinzu.
	 * @param string|array $protocols
	 * @return void
	 */
	final public function cundd_protocol_addProtocols($protocols){
		if(gettype($protocols) == 'string'){
			$protocols = array($protocols);
		}
		
		foreach($protocols as $protocol){
			$alreadyExists = false;
			foreach($this->_cundd_protocol_protocolClasses as $registeredProtocol){
				if($registeredProtocol == $protocol){
					$alreadyExists = true;
					break;
				}
			}
			
			if(!$alreadyExists){
				//$this->_cundd_protocol_protocolClasses[count($this->_cundd_protocol_protocolClasses)] = $protocol;
				$this->_cundd_protocol_protocolClasses[] = $protocol;
				$this->_cundd_protocol_protocolClassesToInit[] = $protocol;
			} else {
				// Do nothing
			}
		}
		
		$this->_cundd_protocol_initProtocols();
		return;
	}
	public function addProtocols($protocols){
		return $this->cundd_protocol_addProtocols($protocols);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt alle eingetragenen Protokolle aus.
	 * @param boolean $noOutput
	 * @return string
	 */
	final public function cundd_protocol_PrintAllProtocols($noOutput = NULL){
		$output = '';
		$protocols = $this->_cundd_protocol_protocolClasses;
		foreach($protocols as $key => $protocol){
			$output .= "$protocol<br />";
		}
		if(!$noOutput) echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wird aufgerufen wenn keine Zuordnung des Methodennamens erfolgen konnte.
	 * Hier wird versucht die Methode durch ein Protokol aufzurufen. */
	public function __call($methodName,$arguments = NULL){
		$say = false;
		$matchingProtocol = NULL;
		$protocolClasses = $this->cundd_protocol_getProtocols();
		
		
		// Die Protokolle durchsuchen und überprüfen ob eines die Methoden implementiert. 
		foreach($protocolClasses as $key => $protocolClass){
			if(in_array($methodName,get_class_methods($protocolClass))){
				$matchingProtocol = $protocolClass;
			}
		}
		
		
		/* DEBUGGEN */
		if($this->_cundd_protocol_debug OR $say){
			$this->pd($this->_cundd_protocol_protocolClasses);
			if($matchingProtocol){
				echo "Protocol $matchingProtocol should handle the method $methodName.<br />";
			} else {
				echo "<span style=\"color:#f00\">No protocol found to handle the method $methodName.</span><br />";
			}
		}
		/* DEBUGGEN */
		
		
		if($matchingProtocol){
			return call_user_func_array(array($matchingProtocol,$methodName),$arguments);
		} else {
			/*
			echo __LINE__ .'<br />'. 
				__FILE__ .'<br />'.
				__DIR__ .'<br />'.
				__FUNCTION__ .'<br />'.
				__CLASS__ .'<br />'.
				__METHOD__ .'<br />'.
				__NAMESPACE__.'<br />';
			
			//unset(self::$_cundd_protocol_debug);
			
			// CunddTools::pd(get_object_vars($this));
			echo get_called_class();
			/* */
			
			$className = get_class($this);
			$errorMsg = "Call to undefined method $className::$methodName() ".implode(', ', $arguments);
			if($this->_cundd_protocol_handleCallErrorsStrict){
				throw new Exception($errorMsg);
				trigger_error($errorMsg, E_USER_ERROR);
			} else {
				trigger_error($errorMsg, E_USER_NOTICE);
			}
			
			
			// throw new Exception('Division by zero.');
			// throw new Zend_Exception($errorMsg);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wird aufgerufen wenn keine Zuordnung des Methodennamens erfolgen konnte.
	 * Hier wird versucht die Methode durch ein Protokol aufzurufen. */
	public static function __callStatic($methodName,$arguments){
		$this->__call($methodName,$arguments);
	}
}
?>