<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Core_Model_Abstract erweitert Cundd_Core_Endofinheritancechain.
 * @package Cundd_Core
 * @version 1.0
 * @since Dec 9, 2009
 * @author daniel
 */
abstract class Cundd_Core_Abstract extends Cundd_Core_Endofinheritancechain{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_mutableProperties = array();
	protected $_cundd_core_object_was_loaded = false;


	/**
	 * @var string 'strict'|'liberal'
	 */
	public $cundd_core_overwriteRule = 'strict';


	protected static $_registryKeyForPersistentObjects = '_persistent';
	protected static $_registryKeyForSingletonObjects = '_singleton';
	
	
	/**
	 * @var string This const represents the prefix of readonly-properties
	 */
	const CUNDD_READONLY_PROPERTY_PREFIX = '__readonyl__';


	/*
	 const CUNDD_MANAGED_MODE_NONE 		= 0;
	 const CUNDD_MANAGED_MODE_USER 		= 1;
	 const CUNDD_MANAGED_MODE_PUBLIC 	= 2;
	 const CUNDD_MANAGED_MODE_SYSTEM 	= 3;
	 /* */






	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode wird bei der Initialisierung aufgerufen. */
	public function __construct(array $arguments = array()){
		$say = false;
		$noAutomaticCoreMethodCalls = false;
		if(array_key_exists('_cundd_core_no_automatic_core_method_calls',$arguments)){
			if($arguments['_cundd_core_no_automatic_core_method_calls']){
				$noAutomaticCoreMethodCalls = true;
			}
		}
		
		
		if(!$noAutomaticCoreMethodCalls){
			parent::__construct($arguments);
			
			$this->_cundd_core_load();
			$this->_cundd_core_register();
			$this->_cundd_core_save();
		}
		
		// $this->debug();
		
		// Das init-Skript der Subclass aufrufen.
		return $this->_construct($arguments);
	}


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ruft die Magic-Method __construct() auf.
	* @param array $arguments
	* @return Cundd_Core_Abstract
	*/
	public function init(array $arguments){
		return $this->__construct($arguments);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wird nach dem Aufruf aller Core-Initialisierungsmethoden aufgerufen und 
	 * bietet dadurch die Init-Methode für alle Sub-Klassen. Die PHP-eigene Konstruktor-Methode 
	 * __construct() sollte aus Gründen der Einfachheit nicht überschrieben werden. Wenn 
	 * aber die Core-Funktionen manuell aufgerufen werden sollen, besteht die Möglichkeit 
	 * die __construct()-Methode zu überschreiben, oder den Konstruktor mit dem Argument
	 * "_cundd_core_no_automatic_core_method_calls" = TRUE aufzurufen. */
	protected function _construct(array $arguments = array()){
		
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode führt den Befehl CunddTools::preDump aus.
	 * @param mixed $value
	 * @return unknown_type
	 */
	protected function pd($value = NULL){
		if(func_num_args() > 1){
			foreach(func_get_args() as $key => $arg){
				CunddTools::preDump($arg);
			}
		} else if(func_num_args() == 0){
			$value = $this;
		}
		return CunddTools::preDump($value);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode führt den Befehle CunddTools::debug aus. */
	public function debug($value = NULL){
		if(func_num_args() > 0){
			foreach(func_get_args() as $key => $arg){
				CunddTools::debug($arg,1);
			}
		} else if(func_num_args() == 0){
			$value = $this;
		}
		return CunddTools::debug($value,1);
	}
	


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode führt den Befehl CunddTools::log aus.
	* @param string $msg
	* @return unknown_type
	*/
	protected function log($msg = ''){
		$source = get_class($this);
		return CunddTools::log($source,$msg);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt die übergebene Nachricht ($what) aus, wenn der zweite Parameter TRUE
	* bzw. nicht gesetzt ist.
	* @param string $what
	* @param boolean $say
	* @return mixed
	*/
	protected function say($what,$say = NULL){
		if($say OR $this->_debug OR $this->debug){
			return CunddTools::say($what,true);
		}
	}
	


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode schreibt eine angegebene Fehlermeldung in die Datei
	* "CunddError.txt".
	* @param string $quelle
	* @param string $msg
	* @param boolean $writeGlobalVars
	* @return string
	*/
	protected function hiddenError($msg = NULL,$writeGlobalVars = true,$forceLog = false){
		$source = get_class($this);
		return CunddTools::hiddenError($source,$msg,$writeGlobalVars,$forceLog);
	}




	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// MUTABLE
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt zurück ob das Model eine erweiterbare Eigenschaft besitzt.
	* @return boolean
	*/
	abstract protected function _isMutable();







	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// SINGLETON
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt zurück ob das Model ein Singleton-Model ist. */
	abstract protected function _isSingleton();



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode lädt eine Singleton-Instanz und gibt diese zurück, wenn noch keine
	* Instanz existiert wird FALSE zurückgegeben wodurch die Initialisierung weiter erfolgt. */
	protected function _loadSingleton(){
		$say = false;

		$className = get_class($this);

		$registeredSingleton = $this->_getRegisteredSingleton();
		if($registeredSingleton){
			/* DEBUGGEN */if($say) echo 'is registered<br />';/* DEBUGGEN */
			return $registeredSingleton;
		} else {
			return (bool) false;
		}
		
		/*
		} else if(Cundd_Session::load($className) AND false){
			/* DEBUGGEN */
	//		if($say) echo 'loaded<br />';
			/* DEBUGGEN */
	/*		
			$object =& Cundd_Session::load($className);
				
			$object->_registerPersistent();
			$object->loaded = 1;
			return $object;
		} else {
			return (bool) false;
		}
		/* */
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* TODO: doesnt work
	* Die Methode gibt eine Instanz der Klasse zurück wenn die Klasse eine Singleton-Klasse
	* ist, ansonsten wird FALSE zurückgegeben.
	* TODO It only works, if the getInstance-method is implemented in each
	* singleton-subclass.
	*/
	public static function getInstance($className = '',array $arguments = array()){
		$bt = debug_backtrace();
		$className = $bt[count($bt)-1]['class'];
		$classNameArray = explode('_',$className);
		unset($classNameArray[0]);
		$className = implode('_',$classNameArray);
		CunddTools::pd($classNameArray);
		echo $className;
		return Cundd::getSingleton($className);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt zurück ob das Objekt ein Singleton ist oder nicht. */
	public function checkIfSingleton(){
		return $this->_isSingleton();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode registriert dieses Objekt als Singleton-Objekt. */
	protected function _registerSingleton(){
		if($this->_isSingleton()){
			/* Überprüfen ob ein Registry-Eintrag für persistente Objekte angelegt ist, wenn
			 * ja den Wert laden
			 */
			if(Cundd_Registry::isRegistered($this->_getRegistryKeyForSingletonObjects())){
				$singletonObjects = Cundd_Registry::get($this->_getRegistryKeyForSingletonObjects());
			}
				
			// TODO: register not Singleton
			// OLD: $persistentObjects[] =& $this;
			// $this->time = date('H:i:s');
			$singletonObjects[get_class($this)] = &$this;
				
				
			// Das aktualisierte Array registrieren
			Cundd_Registry::set($this->_getRegistryKeyForSingletonObjects(),$singletonObjects);
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	protected function _updateSingleton(){
		return $this->_registerSingleton();
	}
	public function saveSingleton(){
		return $this->_registerSingleton();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode löscht den Eintrag dieses Objekts. */
	protected function _unregisterSingleton(){
		if($this->_isSingleton()){
			/* Überprüfen ob ein Registry-Eintrag für persistente Objekte angelegt ist, wenn
			 * ja den Wert laden
			 */
			if(Cundd_Registry::isRegistered($this->_getRegistryKeyForSingletonObjects())){
				$singletonObjects = Cundd_Registry::get($this->_getRegistryKeyForSingletonObjects());
			}
				
			// TODO: register not Singleton
			// OLD: $persistentObjects[] =& $this;
			// $this->time = date('H:i:s');
			unset($singletonObjects[get_class($this)]);
				
				
			// Das aktualisierte Array registrieren
			Cundd_Registry::set($this->_getRegistryKeyForSingletonObjects(),$singletonObjects);
			return (bool) true;
		} else {
			return (bool) false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt ein registriertes Singleton-Objekt zurück. */
	protected function _getRegisteredSingleton(){
		if($this->_isSingleton() AND Cundd_Registry::isRegistered($this->_getRegistryKeyForSingletonObjects())){
			$singletonObjects = Cundd_Registry::get($this->_getRegistryKeyForSingletonObjects());
			if(array_key_exists(get_class($this),$singletonObjects)){
				return $singletonObjects[get_class($this)];
			} else {
				return (bool) false;
			}
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* The method returns the registry key for persistent objects.
	* @return string
	*/
	public static function getRegistryKeyForSingletonObjects(){
		return self::$_registryKeyForSingletonObjects;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* The method returns the registry key for persistent objects.
	* @return string
	*/
	protected function _getRegistryKeyForSingletonObjects(){
		return Cundd_Core_Model_Abstract::getRegistryKeyForSingletonObjects();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// PERSISTENT
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt zuürck ob ein Objekt automatisch in einer Session gespeichert werden
	* soll oder nicht.
	* @return boolean
	*/
	abstract protected function _isPersistent();



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode löscht den Registry-Eintrag dieses Objekts.
	* @return boolean
	*/
	protected function _unregisterPersistent(){
		if($this->_isPersistent()){
			$persistentObjects = array();
			$className = get_class($this);
				
			/* Überprüfen ob ein Registry-Eintrag für persistente Objekte angelegt ist, wenn
			 * ja den Wert laden
			 */
			if(Cundd_Registry::isRegistered($this->_getRegistryKeyForPersistentObjects())){
				$persistentObjects = Cundd_Registry::get($this->_getRegistryKeyForPersistentObjects());
			}
				
			// TODO: register not Singleton
			// OLD: $persistentObjects[] =& $this;
			// $this->time = date('H:i:s');
			unset($persistentObjects[$className]);
				
			// Das aktualisierte Array registrieren
			Cundd_Registry::set($this->_getRegistryKeyForPersistentObjects(),$persistentObjects);
			return (bool) true;
		} else {
			return (bool) false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode registriert das Objekt wenn es als persistent definiert ist.
	* @return boolean
	*/
	protected function _registerPersistent(){
		if($this->_isPersistent()){
			$persistentObjects = array();
			$className = get_class($this);
				
			/* Überprüfen ob ein Registry-Eintrag für persistente Objekte angelegt ist, wenn
			 * ja den Wert laden
			 */
			if(Cundd_Registry::isRegistered($this->_getRegistryKeyForPersistentObjects())){
				$persistentObjects = Cundd_Registry::get($this->_getRegistryKeyForPersistentObjects());
			}
				
			// TODO: register not Singleton
			// OLD: $persistentObjects[] =& $this;
			// $this->time = date('H:i:s');
			$persistentObjects[$className] = $this;
				
			// Das aktualisierte Array registrieren
			Cundd_Registry::set($this->_getRegistryKeyForPersistentObjects(),$persistentObjects);
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	protected function _updatePersistent(){
		return $this->_registerPersistent();
	}
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode registriert das Objekt wenn es als persistent definiert ist.
	* @return boolean
	*/
	/*	public function registerPersistent(){
		return $this->_registerPersistent();
		}




		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/**
	 * The method returns the registry key for persistent objects.
	 * @return string
	 */
	public static function getRegistryKeyForPersistentObjects(){
		return self::$_registryKeyForPersistentObjects;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* The method returns the registry key for persistent objects.
	* @return string
	*/
	protected function _getRegistryKeyForPersistentObjects(){
		return Cundd_Core_Model_Abstract::getRegistryKeyForPersistentObjects();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode speichert die Instanz in einer Session-Variable. */
	public function savePersistent($forceSave = false){
		if($this->_isSingleton() OR $forceSave){
			$className = get_class($this);
			return Cundd_Session::set($className,$this);
		} else {
			// TODO: speichern wenn es kein Singleton ist
			$msg = "The object is declared as persistent but not singleton. This is currently not supported";
			throw new Exception($msg);
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode versucht ein persistentes Objekt zu laden und gibt diese zurück.
	* @return boolean
	*/
	protected function _loadPersistent(){
		$say = false;

		$className = get_class($this);

		if($this->_isPersistent()){
			if(Cundd_Session::load($className)){
				/* DEBUGGEN */if($say) echo 'loaded<br />';/* DEBUGGEN */
				$object = Cundd_Session::load($className);
				// $this->_overwriteThis($object,'noSpecialTargetSet',true);
				return $object;
			} else {
				return (bool) false;
			}
		} else {
			return (bool) false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// MANAGED
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt zurück ob das Objekt ein Managed-Object ist.
	* @return boolean
	*/
	protected function _isManaged(){
		if($this->_managedMode()){
			$return = (bool) true;
		} else {
			$return = (bool) false;
		}
		return $return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt den Managed-Modus des Objekts als Integer zurück. Wenn der Wert
	* CUNDD_MANAGED_MODE_NONE bzw 0 ist, ist das Objekt als nicht managed definiert.
	* Possible values:
	* CUNDD_MANAGED_MODE_NONE
	* CUNDD_MANAGED_MODE_USER
	* CUNDD_MANAGED_MODE_PUBLIC
	* CUNDD_MANAGED_MODE_SYSTEM
	* @return integer
	*/
	abstract protected function _managedMode();



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt den Managed-Modus des Objekts als Integer zurück. Wenn der Wert
	* CUNDD_MANAGED_MODE_NONE bzw 0 ist, ist das Objekt als nicht managed definiert.
	* Possible values:
	* CUNDD_MANAGED_MODE_NONE
	* CUNDD_MANAGED_MODE_USER
	* CUNDD_MANAGED_MODE_PUBLIC
	* CUNDD_MANAGED_MODE_SYSTEM
	* @return integer
	*/
	protected function _getManagedMode(){
		return $this->_managedMode();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode versucht ein Managed-Object zu laden. */
/*	protected function _loadManaged(){
	$say = false;

	$className = get_class($this);

	$registeredSingleton = $this->_getRegisteredSingleton();
	if($registeredSingleton){
	return $registeredSingleton;
	} else if(Cundd_Session::load($className)){
	$object =& Cundd_Session::load($className);
		
	$object->_registerPersistent();
	$object->loaded = 1;
	return $object;
	} else {
	return (bool) false;
	}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// MAIN
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode überschreibt die Standard Get-Methode und ermöglicht das Lesen von
	 * mutable Properties. */
	public function __get($name){
		$classVars = get_class_vars(get_class($this));
		// Überprüfen ob der aktuelle Name der Name einer Eigenschaft ist
		if(array_key_exists($name,$classVars)){
			parent::__get($name);
				
			// Wenn nicht überprüfen ob die Klasse/Instanz mutable ist
		} else if($this->_isMutable()){
			return $this->_mutableProperties[$name];
	//	} else if(strpos($name,self::CUNDD_READONLY_PROPERTY_PREFIX) != 0){
	//		return $this->_getReadonlyProperty($name);
		} else {
			// Nothing
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht eine Eigenschaft inklusive dem readonly-prefix zu lesen.
	 * @param string $name
	 * @return mixed
	 */
	protected function _getReadonlyProperty($name){
		return $this->__get(self::CUNDD_READONLY_PROPERTY_PREFIX.$name);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode überschreibt die Standard Set-Methode und ermöglicht das Setzten von
	* mutable Properties.
	* @param string $name
	* @param mixed $value
	*/
	public function __set($name,$value){
		$classVars = get_object_vars($this);
		// Überprüfen ob der aktuelle Name der Name einer Eigenschaft ist
		if(array_key_exists($name,$classVars)){
			$this->$name = $value;
				
			// Wenn nicht überprüfen ob die Klasse/Instanz mutable ist
		} else if($this->_isMutable()){
			$this->_mutableProperties[$name] = $value;
		} else {
			// Nothing
		}

		// Wenn das Objekt persistent ist muss der Registry-Eintrag erneuert werden
		if($this->_isPersistent()){
			$this->_updatePersistent();
		}
		// Wenn das Objekt ein Singleton ist muss der Registry-Eintrag erneuert werden
		$this->_updateSingleton();

		// Wenn das Objekt managed ist muss der Registry-Eintrag erneuert werden
		$this->_updateManaged();
		// $this->_cundd_managed_update();

		return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode führt Methoden zum Aufräumen des Objekts vor dem Speichern aus.
	* @return array
	*/
	final public function __sleep(){
		new CunddEvent('Cundd_Core_object_will_save',array($this));
		$propertiesToSave = $this->_beforeSave();
		
		
		if(!$propertiesToSave){
			$propertiesToSave = array_keys(get_object_vars($this));
		}
		return $propertiesToSave;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode wird vor dem Speichern des Objekts aufgerufen. Anstelle von NULL kann ein
	* Array mit den Namen der Eigenschaften zurückgegeben werden die gespeichert werden
	* sollen.
	* @return NULL|array
	*/
	protected function _beforeSave(){
		return NULL;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt den Namen des Moduls zu dem eine Klasse gehört.
	* @param string $className[optional]
	* @return string
	*/
	protected function _getModule($className = NULL){
		if(!$className){
			$className = get_class($this);
		}

		$classPathArray = explode('_',$className);
		return $classPathArray[1];
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode überschreibt die eigene Instanz mit den Werten des übergebenen Objekts.
	* @param object $newObject
	* @param ref $target ['noSpecialTargetSet']
	* @param boolean $required
	* @return boolean
	*/
	protected function _overwriteThis($newObject,&$target = 'noSpecialTargetSet',$required = false){
		$say = false;
		$return = (bool) false;


		// Get values from $newObject
		$propertiesAndValues = $newObject->cundd_core_get_properties_and_values();
		

		if(func_num_args() < 2 OR $target == 'noSpecialTargetSet'){ // No special target is set
			$targetClassProperties = get_object_vars($this);
			$target =& $this;
		} else {
			$targetClassProperties = get_object_vars($target);
		}

		// Überprüfen ob die Objekte in passenden/genehmigten Klassen vorliegen
		if(
		( $target->cundd_core_overwriteRule == 'strict' AND is_a($target,'Cundd_Core_Abstract') AND is_a($newObject,'Cundd_Core_Abstract') ) OR
		( $target->cundd_core_overwriteRule == 'liberal' AND is_a($target,'Cundd_Core_Abstract') )
		){
			foreach($targetClassProperties as $classProperty => $value){
				/* DEBUGGEN */if($say){$this->pd($classProperty);echo $propertiesAndValues[$classProperty];}/* DEBUGGEN */
				$target->$classProperty = $propertiesAndValues[$classProperty];
				//$target->$classProperty = $newObject->$classProperty;
			}
				
			$target->register();
			$target->save();
				
			$return = (bool) true;
				
				
			// Error message or return value = false
		} else if($target->cundd_core_overwriteRule == 'strict'){
			if($required){
				$msg = "The object of class ".get_class($target)." couldn't be overwritten by an object of class ".get_class($newObject)." in strict mode.";
				throw new Exception($msg);
			}
			$return = (bool) false;
		} else if($target->cundd_core_overwriteRule == 'liberal'){
			if($required){
				$msg = "The object of class ".get_class($target)." couldn't be overwritten by an object of class ".get_class($newObject)." in liberal mode.";
				throw new Exception($msg);
			}
			$return = (bool) false;
		}

		return $return;
	}
	/*	protected function _overwriteThis($newObject,&$target = 'noSpecialTargetSet',$required = false){
		$return = (bool) false;

		if(func_num_args() < 2 OR $target == 'noSpecialTargetSet'){ // No special target is set
		$target =& $this;
		}

		// Überprüfen ob die Objekte in passenden/genehmigten Klassen vorliegen
		if(
		( $target->cundd_core_overwriteRule == 'strict' AND is_a($target,'Cundd_Core_Abstract') AND is_a($newObject,'Cundd_Core_Abstract') ) OR
		( $target->cundd_core_overwriteRule == 'liberal' AND is_a($target,'Cundd_Core_Abstract') )
		){
		$targetClassProperties = get_class_vars(get_class($target));
		foreach($targetClassProperties as $classProperty){
		$target->$classProperty = $newObject->$classProperty;
		}
		$return = (bool) true;
		} else if($target->cundd_core_overwriteRule == 'strict'){
		if($required){
		$msg = "The object of class ".get_class($target)." couldn't be overwritten by an object of class ".get_class($newObject)." in strict mode.";
		throw new Exception($msg);
		}
		$return = (bool) false;
		} else if($target->cundd_core_overwriteRule == 'liberal'){
		if($required){
		$msg = "The object of class ".get_class($target)." couldn't be overwritten by an object of class ".get_class($newObject)." in liberal mode.";
		throw new Exception($msg);
		}
		$return = (bool) false;
		}

		return $return;
		}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode wird für das Laden und die _overwriteThis-Methode benötigt. Sie
	 * ermöglicht das Lesen aller Eigenschaften des Objekts und deren Werte.
         * @return array
         */
	final public function cundd_core_get_properties_and_values(){
		//		$returnArray = array();
		//		$objectvars = get_object_vars($this);
		//		foreach($objectvars as $propertyName){
		//			$returnArray[] = $this->$propertyName;
		//		}
		//		return $returnArray;
		return get_object_vars($this);
	}



        // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
        // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
        /**
         * @see cundd_core_get_properties_and_values()
         * @return array
         */
        protected function  _getPropertiesAsDictionary(){
            return $this->cundd_core_get_properties_and_values();
        }



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode setzt die Instanz zurück und leert alle Eigenschaften. */
	public function destroy(){
		// Get all properties
		$allProperties = get_object_vars($this);

		foreach($allProperties as $key => $property){
			unset($this->$property);
		}
		return $this->unregister();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode setzt die Instanz zurück und erstellt eine neue. */
	public function reset(array $arguments = array()){
		if($this->destroy()){
			return $this->__construct($arguments);
		} else {
			$msg = "The object couldn't be destroyed.";
			throw new Exception($msg);
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ruft die Methoden zum Speichern der Objekte.
	 * @param boolean $forceSave
	 * @return string
	 */
	protected function _cundd_core_save($forceSave = false){
		$return = false;

		if($this->_isSingleton()){
			$return = $this->saveSingleton($forceSave);
		}
		
		if($this->_isManaged()){
			$return = $this->saveManaged($forceSave);
		}
		
		if($this->_isPersistent()){
			$return = $this->savePersistent($forceSave);
		}
		

		return $return;
	}
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ruft die Methoden zum Speichern der Objekte.
	 * @param boolean $forceSave
	 * @return string
	 */
	public function save($forceSave = false){
		return $this->_cundd_core_save($forceSave);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ruft die verschiedenen Methoden zum Laden des Objekts auf.
	* @param boolean $required
	* @return boolean
	*/
	public function _cundd_core_load($required = false){
		$say = false;
		$object = NULL;


		$forceLoadManaged = $this->_cundd_managed_checkIfForceLoad();


		if($this->_isSingleton() AND !$forceLoadManaged){ // Try to load the singleton
			/* DEBUGGEN */if($say) echo 'Try to load Singleton.<br />';/* DEBUGGEN */
			$object = $this->_loadSingleton();
			/* DEBUGGEN */if($say AND $object) echo 'Successfully loaded Singleton.<br />';/* DEBUGGEN */
		}
		if(!$object AND $this->_isPersistent() AND !$forceLoadManaged){
			/* DEBUGGEN */if($say) echo 'Try to load persistent.<br />';/* DEBUGGEN */
			$object = $this->_loadPersistent();
			/* DEBUGGEN */if($say AND $object) echo 'Successfully loaded persistent.<br />';/* DEBUGGEN */
		}
		if(!$object AND $this->_isManaged()){
			/* DEBUGGEN */if($say) echo 'Try to load managed.<br />';/* DEBUGGEN */
			$object = $this->_loadManaged();
			/* DEBUGGEN */if($say AND $object) echo 'Successfully loaded managed.<br />';/* DEBUGGEN */
		}

		/*
		 $this->pd($object);
		 /*
		 $_SESSION['counter'] = $_SESSION['counter'] + 1;
		 echo $_SESSION['counter'];
		 /* */

		if(!$object){
			$this->_cundd_core_object_was_loaded = false;
				
			if($required
			// AND ( $this->_isSingleton() OR $this->_isPersistent() OR $this->_isManaged() )
			){
				$msg = "Object ".get_class($this)." couldn't be loaded. Object is ";

				if($this->_isSingleton()) $msg .= "a Singleton ";
				if($this->_isPersistent()) $msg .= "persitent ";
				if($this->_isManaged()) $msg .= "managed";
				$msg .= '.';

				throw new Exception($msg);
			} else {
				/* DEBUGGEN */if($say) echo "Object ".get_class($this)." couldn't be loaded.";/* DEBUGGEN */
			}
			return (bool) false;
		} else {
			$this->_cundd_core_object_was_loaded = true;
				
			// DEBUGGEN
			if($say){
				$msg = "Object ".get_class($this)." was loaded. Object is ";
				if($this->_isSingleton()) $msg .= "a Singleton ";
				if($this->_isPersistent()) $msg .= "persitent ";
				if($this->_isManaged()) $msg .= "managed";
				$msg .= '.';
				echo $msg;
			}
			// DEBUGGEN
				
				
			$this->_overwriteThis($object);
			return $object;
			// return $this->_overwriteThis($object);
		}
	}
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ruft die verschiedenen Methoden zum Laden des Objekts auf.
	* @param boolean $required
	* @return boolean
	*/
	public function load($required = false){
		return $this->_cundd_core_load($required);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt zurück ob das Objekt geladen wurde.
	* @return boolean
	*/
	public function wasLoaded(){
		return (bool) $this->_cundd_core_object_was_loaded;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode die Eigenschaft $_loaded zurück auf false.
	* @return boolean
	*/
	public function resetLoaded(){
		$this->_cundd_core_object_was_loaded = false;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ruft die verschiedenen Methoden zum Registrieren des Objekts auf.
	* @return int
	*/
	protected function _cundd_core_register(){
		$return  = $this->_registerPersistent();
		$return *= $this->_registerSingleton();
		$return *= $this->_registerManaged();
		return $return;
	}
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ruft die verschiedenen Methoden zum Registrieren des Objekts auf.
	* @return int
	*/
	public function register(){
		return $this->_cundd_core_register();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ruft die verschiedenen Methoden zum unregistrieren des Objekts auf.
	* @return int
	*/
	public function unregister(){
		//$return  = $this->_registerBaseProtocols();
		$return  = $this->_unregisterPersistent();
		$return *= $this->_unregisterSingleton();
		$return *= $this->_unregisterManaged();
		return $return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// PROPERTIES
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode versucht alle key/value-Pairs des Arrays $source als Eigenschaft zu
	* speichern.
	* @param array $source
	* @param boolean $prepareSourceKeys
	*/
	protected function _registerProperties(array $source,$prepareSourceKeys = false,$prefix = ''){
		$classVars = get_object_vars($this);

		if($prepareSourceKeys) $this->_prepareSourceKeysForPropertyRegistration($source);

		foreach($source as $key => $value){
			// Überprüfen ob der aktuelle key der Name einer Eigenschaft ist
			if(array_key_exists($key,$classVars)){
				$key = $prefix.$key;
				$this->__set($key,$value);

				// Wenn nicht überprüfen ob die Klasse/Instanz mutable ist
			} else if($this->_isMutable()){
				$key = $prefix.$key;
				$this->__set($key,$value);
			} else {
				// Nothing
			}
		}
		return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode löscht alle Sonderzeichen aus den Keys des Source-Arrays und setzt den
	* ersten Buchstaben auf Lower-Case.
	* @param array $source
	* @return array
	*/
	protected function _prepareSourceKeysForPropertyRegistration(array &$source){
		$newArray = array();


		foreach($source as $key => $value){
			// Prepare Key
			$newKey = '';
			$UCKeyWordArray = array();
				
			$deletePattern = '![^a-zA-Z0-9\-]!';
			$newKey = preg_replace($deletePattern,'',$key);
			$newKeyWordArray = explode('-',$newKey);
			foreach($newKeyWordArray as $newKeyWord){
				$UCKeyWordArray[] = ucfirst($newKeyWord);
			}
			$newKey = implode('',$UCKeyWordArray);
				
				
			if(function_exists('lcfirst')){
				$newKey = lcfirst($newKey);
			} else {
				// Treat string as character-array
				$newKey[0] = strtolower($newKey[0]);
			}
				
			// Feed new key and original value to $newArray
			$newArray[$newKey] = $value;
		}

		$source = $newArray;
		return $newArray;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode überprüft ob ein Element mit dem Key = $key existiert und setzt die per
	* Argument referenzierte Variable $target wenn diese angegeben ist, wenn nicht wird
	* versucht die Eigenschaft mit dem Namen $key zu setzen.
	* @param string $key
	* @param array $source
	* @param mixed $target
	* @param mixed $elseSetToThisValue
	* @return mixed|false
	*/
	protected function _setIfKeyExists($key, array $source,&$target = 'esWurdeKeinTargetAn_setIfKeyExistsUebergeben',$elseSetToThisValue = NULL){
		$say = false;
		if(array_key_exists($key,$source)){
			if($target == 'esWurdeKeinTargetAn_setIfKeyExistsUebergeben'){
				/* DEBUGGEN */if($say) echo 'No target';/* DEBUGGEN */
				return $this->__set($key,$source[$key]);
			} else {
				/* DEBUGGEN */if($say) echo 'Has target';/* DEBUGGEN */
				$target = $source[$key];
				return (bool) true;
			}
		} else if($elseSetToThisValue){ // Überprüfen ob ein Ersatzwert angegeben wurde
			if($target == 'esWurdeKeinTargetAn_setIfKeyExistsUebergeben'){
				/* DEBUGGEN */if($say) echo 'No target';/* DEBUGGEN */
				return $this->__set($key,$elseSetToThisValue);
			} else {
				/* DEBUGGEN */if($say) echo 'Has target';/* DEBUGGEN */
				$target = $elseSetToThisValue;
				return (bool) true;
			}
		} else {
			/* DEBUGGEN */if($say) echo 'Key not found';/* DEBUGGEN */
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// CONFIG
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen Eintrag in der Konfigurationsdatei des Moduls zurück.
	 * @param string $key
	 * @return mixed
	 */
	protected function _config($key){
		if($this->_getModule()){
			return CunddConfig::__($this->_getModule().'/'.$key);
		} else {
			return CunddConfig::__($key);
		}
	}
	/**
	 * @see _config()
	 */
	protected function _conf($key){
		return $this->_config($key);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen Eintrag in der Konfigurationsdatei des Moduls zurück.
	 * @param string $key
	 * @return mixed
	 */
	public function config($key){
		return $this->_config($key);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// COLLECTION
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt das Collection-Model dieses Models zurück.
	* @return Cundd_Core_Abstract|NULL
	*/
	public function getCollection(){
		$thisClassName = get_class($this);
		$thisClassNameArray = explode('_',$thisClassName);
		unset($thisClassNameArray[0]);
		unset($thisClassNameArray[1]);
		unset($thisClassNameArray[2]);

		$collectionName = $this->_getModule().'/'.str_replace('/','_',CunddConfig::__('Cundd_collection_dir')).implode('_',$thisClassNameArray);

		if(CunddPath::checkIfModelFileExists($collectionName)){
			$modelName = CunddClassLoader::getModelName($collectionName);
			//$temp =& new $modelName();
			$temp = new $modelName();
			return $temp;
		} else {
			return NULL;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// TRANSLATE
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode "get" liest den als Parameter übergebenen Sprachbaustein und ersetzt die 
	 * im Sprachbaustein angegebenen Tags mit den weiteren Parametern. Wenn kein Parameter
	 * übergeben wurde, wird die aktuelle Sprachwahl zurückgegeben.
	 * @param string $msg
	 * @param string|array $para
	 * @return string
	 */
	public function translate($msg,$para = NULL){
		return Cundd_Lang::__($msg,$para);
	}
	protected function _t($msg,$para = NULL){
		return $this->translate($msg,$para);
	}
}
?>