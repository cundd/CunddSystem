<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Terminal_Model_Plugin_Preg.
 * @package Cundd_Terminal
 * @version 1.0
 * @since Feb 8, 2010
 * @author daniel
 */
class Cundd_Terminal_Model_Plugin_Preg extends Cundd_Terminal_Model_Plugin_Abstract {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	/**
	 * @see replaceAction()
	 */
	public static function indexAction(array $arguments = array()){
		return self::replaceAction($arguments);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode füht preg_replace mittels der übergebenen Parameter aus.
	 * @param string $pattern
	 * @param string $replace
	 * @param string $string
	 * @return string
	 */
	public static function replaceAction(array $arguments = array()){
		if(count($arguments) == 0) return '';
		
		$pattern = $arguments[0];
		$replace = $arguments[1];
		$string = $arguments[2];
		
		$return = '';
		try{
			$return = preg_replace($pattern,$replace,$string);
		} catch(Error $e){
			$return = $e->getMessage();
		} catch(Exception $e){
			$return = $e->getMessage();
		}
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode füht preg_replace mittels der übergebenen Parameter aus.
	 * @param string $pattern
	 * @param string $replace
	 * @param string $string
	 * @return string
	 */
	public static function matchAction(array $arguments = array()){
		if(count($arguments) == 0) return '';
		
		$pattern = $arguments[0];
		$subject = $arguments[1];
		$flags = $arguments[2];
		if(!$flags) $flags = PREG_PATTERN_ORDER;
		
		CunddTools::pd($arguments);
		
		$returnArray = array();
		$return = '';
		try{
			preg_match_all($pattern,$subject,$returnArray,$flags);
			$return = var_export($returnArray,true);
		} catch(Error $e){
			$return = $e->getMessage();
		} catch(Exception $e){
			$return = $e->getMessage();
		}
		
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * @param string $actionName
	 * @return string
	 */
	public static function infoAction($actionName = NULL){
		$info = '';
		switch($actionName){
			default:
				$info = Cundd_Lang::get('The PREG-plugin gives an interface to PHP-functions for string-manipulation with regular expressions.
For example use "T:PREG/?!g!,h,go for it" to replace "g" inside "go for it" with an "h".');
				break;
		}
		return $info;
	}
}
?>