<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Registry erweitert Zend_Registry.
 * @package Cundd
 * @version 1.0
 * @since Dec 9, 2009
 * @author daniel
 */
class Cundd_Registry extends Zend_Registry
{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	//private static $_registry = array();
	
	
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode bietet ein Interface zum Ermitteln oder Setzen eine Registry-Eintrags. 
	 * Wenn $value übergeben wurde wird der Registry-Eintrag gesetzt, ansonsten wird die 
	 * Methode getIfRegistered() mit $name aufgerufen und der Registry-Eintrag zurückgegeben.
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	public static function registry($name,$value = NULL){
		if(func_num_args() == 2){
			return self::set($name,$value);
		} else {
			return self::getIfRegistered($name);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt ein Element des Registry-Arrays mit dem übergebenen Key zurück. */
	/*public static function get($key){
		if(array_key_exists($key,self::$_registry)){
			return self::$_registry[$key];
		} else {
			return (bool) false;
		} 
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt eine Referenz auf ein Element des Registry-Arrays mit dem über-
	 * gebenen Key zurück. */
/*	public static function getReference($key){
		if(array_key_exists($key,self::$_registry)){
			$ref =& self::$_registry[$key];
			return $ref;
		} else {
			return (bool) false;
		} 
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt ein Element mit dem übergebenen Key und Value.
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	/*public static function set($key,$value){
		self::$_registry[$key] = $value;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt eine Referenz als Element des Registry-Arrays.
	 * @param string $key
	 * @param mixed $target
	 * @return void
	 */
/*	public static function setReference($key,&$target){
		self::$_registry[$key] =& $target;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob ein Registry-Eintrag mit dem übergebenen Key existiert. */
/*	public function isRegistered($key){
		return array_key_exists($key,self::$_registry);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob eine registry-Eintrag existiert und gibt entweder den ent-
	 * sprechenden Wert oder FALSE zurück.
	 * @param string $key
	 * @return mixed|false
	 */
	public static function getIfRegistered($key){
		if(Cundd_Registry::isRegistered($key)){
    		return Cundd_Registry::get($key);
		} else {
			return (bool) false;
		}
	}
}
?>