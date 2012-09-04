<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Session extends Cundd_Core_Singleton.
 * @package Cundd
 * @version 1.0
 * @since Dec 21, 2009
 * @author daniel
 */
class Cundd_Session extends Cundd_Core_Singleton {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private static $_debug = false;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die komplette $_SESSION-Variable innerhalb des Namespace zurück. 
	 * Wenn $name übergeben wurde wird nur dieser Wert zurückgegeben.
	 * @param string $name
	 * @return mixed
	 */
	public static function get($name = NULL){
		$relevantNamespace = 'Cundd';
		if(array_key_exists($relevantNamespace,$_SESSION)){
			if($name AND array_key_exists($name,$_SESSION[$relevantNamespace])){
				return $_SESSION[$relevantNamespace][$name];
			} else if($name){
				return NULL;
			} else {
				return $_SESSION[$relevantNamespace];
			}
		} else {
			return NULL;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt den Wert für eine Session-Variable. Wenn der übergebene Wert ein
	 * Objekt ist wird es vor dem Speichern serialisiert.
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	public static function set($name,$value){
		$oldValue = NULL;
		$relevantNamespace = 'Cundd';
		
		
		if(self::get($name)){
			$oldValue = self::get($name);
		}
		if(gettype($value) == 'object'){
			$parsedValue = serialize($value);
		} else {
			$parsedValue = $value;
		}
		$_SESSION[$relevantNamespace][$name] = $parsedValue;
		
		// Als Eigenschaft speichern
		// $this->$name = $value;
		
		return $oldValue;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode löscht die Werte innerhalb des Namespace. */
	public static function clear(){
		$relevantNamespace = 'Cundd';
		unset($_SESSION[$relevantNamespace]);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode lädt eine Session-Variable und führt den Befehl unserialize() aus.
	 * @param string $name
	 * @return mixed
	 */
	public function load($name = NULL){
		/* Wenn die Methode ohne Parameter aufgerufen wird wurde sie vom init-Skript aufgerufen */
		if($name == NULL) return NULL;
		
		$sessionVar = self::get($name);
		if($sessionVar){
			$object = unserialize($sessionVar);
			// $object->registerPersistent();
			/* DEBUGGEN */if(self::$_debug) echo 'loaded';/* DEBUGGEN */
			$refObject = &$object;
			return $refObject;
		} else {
			/* DEBUGGEN */if(self::$_debug) echo 'not loaded';/* DEBUGGEN */
			return NULL;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isPersistent()
	 */
	protected function _isPersistent(){
		return (bool) false;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isMutable()
	 */
	protected function _isMutable(){
		return (bool) true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_managedMode()
	 */
	protected function _managedMode(){
		return self::CUNDD_MANAGED_MODE_NONE;
	}
}
?>