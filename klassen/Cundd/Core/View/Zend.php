<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Core_View_Zend erweitert Zend_View_Abstract.
 * @package Zend_View_Abstract
 * @version 1.0
 * @since Jan 12, 2010
 * @author daniel
 */
class Cundd_Core_View_Zend extends Zend_View_Abstract{
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $placeholderCollection;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht alle key/value-Pairs des Arrays $source als Eigenschaft zu 
	 * speichern.
	 * @param array $source
	 * @param boolean $prepareSourceKeys
	 */
	public function registerProperties(array $source,$prepareSourceKeys = false,$prefix = ''){
		// if($prepareSourceKeys) $this->_prepareSourceKeysForPropertyRegistration($source);
		
		foreach($source as $key => $value){
			$key = $key.$prefix;
			$this->$key = $value;
		}
		return;
	}

	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ist ein Alias für placeholder() */
	public function getPlaceholder($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL){
		return $this->placeholder($key, $para1, $para2, $para3, $para4);
	}
	public function p($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL){
		return self::getPlaceholder($key, $para1, $para2, $para3, $para4);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Wert des Placeholders aus. */
	public function printPlaceholder($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL){
		echo self::getPlaceholder($key, $para1, $para2, $para3, $para4);
	}
	public function printP($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL){
		self::printPlaceholder($key, $para1, $para2, $para3, $para4);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ist ermittelt den Wert eines Placeholders und übersetzt diesen mittels 
	 * CunddLang. */
	public function getPlaceholderAndTranslate($key = NULL){
		$placeholderValue = self::p($key);
		$placeholderValue = $placeholderValue.''; // Parse to string
		return self::__($placeholderValue);
	}
	public function pt($key = NULL){
		return self::getPlaceholderAndTranslate($key);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode übersetzt den übergebenen String mittels CunddLang. */
	public function translate($msg = NULL){
		return CunddLang::get($msg);
	}
	public function __($msg = NULL){
		return self::translate($msg);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode bereitet das Abfragen der übergebenen Key-Value-Paare in der Template-
	 * Datei vor. */
	public function registerPlaceholdersFromArray(array $wert){
		foreach($wert as $key => $value){
			$this->placeholder($key)->set($value);
			$result = $this->placeholder($key)->set($value);
			$this->placeholderCollection[$key] = $value;
		}
		return $result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode registriert einen Placeholder. */
	public function registerPlaceholder($key, $value){
		$this->placeholderCollection[$key] = $value;
		return $this->placeholder($key)->set($value);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Eigenschaft $placeholderCollection zurück. */
	public function getAllPlaceholders(){
		return $this->placeholderCollection;
	}
	public function getPlaceholderCollection(){
		return self::getAllPlaceholders();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt alle registrierten Placeholder auf NULL. */
	public function clearAllPlaceholders($newValue = NULL){
		$allPlaceholders = $this->getAllPlaceholders();
		if(gettype($allPlaceholders) == 'array'){
			foreach($allPlaceholders as $key => $placeholder){
				$result = $this->placeholder($key)->set($newValue);
				$this->placeholderCollection[$key] = $newValue;
			}
			return $result;
		} else {
			return false;
		}
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
     * Includes the view script in a scope with only public $this variables.
     *
     * @param string The view script to execute.
     */
    protected function _run()
    {
    	if ($this->_useViewStream && $this->useStreamWrapper()) {
            include 'zend.view://' . func_get_arg(0);
        } else {
            include func_get_arg(0);
        }
    }
}
?>