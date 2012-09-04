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
abstract class Cundd_Core_Model_Abstract extends Cundd_Core_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt alle Eigenschaften definiert durch ein SourceArray-Key und 
	 * Property-Name Array.
	 * @param array $sourceArray
	 * @param array $srcPropPairs array($sourceArrayKey => $propertyName)
	 */
	protected function _setPropertiesFromArray(array $sourceArray, array $srcPropPairs = array(),$variablePrefix = ''){
		foreach($sourceArray as $key => $value){
			$propertyName = $variablePrefix.$key;
			$this->_setIfKeyExists($key,$sourceArray,$this->$propertyName);
		}
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt den Mime-Type anhand eines Dateinamens.
	 * @param array|string $file
	 * @return false|string
	 */
	function getMimeTypeFromFilename($file,$forceFilenameMode = NULL){
		$fileObject = array();
		$fileObject['name'] = '';
		
		if(gettype($file) == 'array'){
			$this->_setIfKeyExists('name',$file,$fileObject['name']);
			if(!$fileObject['name']) $this->_setIfKeyExists('filename',$file,$fileObject['name']);
			if(!$fileObject['name']) $this->_setIfKeyExists('fileName',$file,$fileObject['name']);
			
			if(!$fileObject['name']) return (bool) false;
		} else if(gettype($file) == 'string'){
			$fileObject['name'] = $file;
		} else {
			return (bool) false;
		}
		return CunddFiles::get_mime_type($fileObject,$forceFilenameMode);
	}
}
?>