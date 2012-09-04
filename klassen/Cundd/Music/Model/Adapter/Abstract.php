<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Adapter_Abstract erweitert Cundd_Music_Model_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 9, 2009
 * @author daniel
 */
abstract class Cundd_Music_Model_Adapter_Abstract extends Cundd_Music_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_requestParameters = array();
	protected $_resultCount = 0;
	protected $_resultObject = 0;
	protected $_result;
	protected $_adapterMode = '';
	protected $_profile = false;






	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode kann zum Profilieren der Datenbankverbindungen verwendet werden. */
	public function __construct($arguments){
		/* DEBUGGEN */
		if($this->_profile){
			echo date('H:i:s,u').'<br />';
		}
		/* DEBUGGEN */
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode bereitet den übergebenen Wert zum Anhängen an einen Request vor.
	* @param string $para
	* @return string
	*/
	protected function _cleanUpParameter($para){
		return urlencode($para);
		$this->getResult();
	}


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt das geparste Resultat zurück.
	* @return false|array(array(["mbId"],["origin"],["color01"],...,["color11"],["colorSpectrumPosition"],
	* ["colorObject"],["mainColor"],["asin"],["id"],["artist"],["artistId"],["label"],["date"],["title"],
	* ["catalogId"],["barcode"],["format"],["trackcount"]) [ , array(...) ] )
	*/
	public function getResult(){
		if($this->_result){
			return $this->_result;
		} else {
			return (bool) false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode fügt dem $client die übergebenen Parameter hinzu. Wenn $validFields
	* übergeben wurde, werden nur Argumente angehängten deren Name einem Element in
	* $validFields entspricht.
	* @param array $arguments
	* @param Zend_Service_Abstract $client
	* @param array $validFields
	* @return Zend_Service_Abstract
	*/
	protected function _setArguments(array $arguments, Zend_Service_Abstract &$client,$validFields = NULL){
		foreach($arguments as $key => $value){
			if($validFields){
				if(array_search($key,$validFields)){
					$client->$key($value);
				} else {
					// Argument ist nicht erlaubt
				}
			} else {
				$client->$key($value);
			}
		}
		return $client;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode parsed ein SimpleXML-Element in ein assoziatives Array.
	* @param SimpleXMLElement|array $object
	* @param array $array
	*/
	protected function _simpleXmlToArray($object,&$array){
		$say = false;

		// DEBUGGEN

		if($say){
			$_POST['i'] = $_POST['i'] + 1;
			echo $_POST['i'];
		}
		// DEBUGGEN

		if($object instanceof SimpleXMLElement){ // Wenn es ein Node mit Attributen ist
			foreach($object->attributes() as $attributeName => $attributeValue){
				$array[$childName][$attributeName] = (string) $attributeValue;
				$array[$childName][$childName.'_'.$attributeName] = (string) $attributeValue;
			}
			/* DEBUGGEN */ if($say){echo 'instanceof SimpleXMLElement<br />';} /* DEBUGGEN */
				
				
				
			 
			foreach($object->children() as $childName => $childValue){
				if($childValue instanceof SimpleXMLElement){ // Wenn es ein Node mit Attributen ist
					foreach($childValue->attributes() as $attributeName => $attributeValue){
						$array[$childName][$attributeName] = (string) $attributeValue;
						// $array[$childName][$childName.'_'.$attributeName] = (string) $attributeValue;
					}
						
					$text = (string) $childValue;
					$text = trim($text);
					if(strlen($text) > 0){
						$array[$childName] = $text;
					}
						
					$this->_simpleXmlToArray($childValue,$array[]);
				} else if(gettype($childValue) == 'array'){
					foreach($childValue as $arrayKey => $arrayValue){
						$this->_simpleXmlToArray($childValue,$array[]);
					}
				} else {
					$array[$childName] = (string) $childValue;
				}
			}
		} else if(gettype($object) == 'array'){
			foreach($object as $arrayKey => $arrayValue){
				/* DEBUGGEN */ if($say){echo 'array<br />';} /* DEBUGGEN */
				$this->_simpleXmlToArray($arrayValue,$array[]);
			}
		} else {
			/* DEBUGGEN */ if($say){$type = get_class($object);echo "Type is $type<br />";} /* DEBUGGEN */
			$array = $object;
		}
		return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode parsed ein SimpleXML-Objekt in ein Array und vereinfacht dieses auf eine
	* Ebene.
	* @param SimpleXMLElement|array $object
	* @param array $array
	* @param int $toLevel
	* @param boolean $withoutOldKey
	* @return array
	*/
	protected function _simpleXmlToFlatArray($object,&$array,$toLevel = 1,$withoutOldKey = NULL){
		$this->_simpleXmlToArray($object,$array);

		$newArray = array();
		$this->_flattenArray($array,$newArray,$toLevel,$withoutOldKey);
		$array = $newArray;
		return $array;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode reduziert die Elemente eines mehrdimensionalen Arrays auf eine Ebene.
	* @param array $source
	* @param array $newArray
	* @param int $toLevel
	* @param boolean $withoutOldKey
	* @return void
	*/
	protected function _flattenArray(array $source,array &$newArray,$toLevel = 1,$withoutOldKey = NULL){
		foreach($source as $key => $value){
			if(!$withoutOldKey){
				$oldKey = $key;
				$oldKey = '';
			} else {
				$oldKey = NULL;
			}
				
			$newArray[$key] = array();
			$this->_flattenToThis($value,$newArray[$key],$oldKey);
		}
		return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode wird von der Methode flattenArray() aufgerufen.
	* @param array $source
	* @param array $newArray
	* @param string $oldKey
	*/
	protected function _flattenToThis(array $source,array &$newArray,$oldKey = NULL){
		foreach($source as $key => $value){
			if($oldKey !== NULL AND $oldKey !== '_' AND $oldKey !== ''){
				$key = $oldKey.'_'.$key;
				$newSendKey = $key;
			} else if($oldKey == '_'){
				$key = NULL;
				$newSendKey = '';
			} else if($oldKey == ''){
				$newSendKey = $key;
			} else if($oldKey == NULL){
				$newSendKey = NULL;
			}
			if(gettype($value) == 'array'){
				$this->_flattenToThis($value,$newArray,$newSendKey);
			} else {
				$newArray[$key] = $value;
			}
		}
		return;
	}
	/*
		object(SimpleXMLElement)#20 (2) {
		["@attributes"]=>
		array(2) {
		["offset"]=>
		string(1) "0"
		["count"]=>
		string(2) "42"
		}
		["release"]=>
		array(25) {
		[0]=>
		object(SimpleXMLElement)#21 (8) {
		["@attributes"]=>
		array(2) {
		["type"]=>
		string(14) "Album Official"
		["id"]=>
		string(36) "320e6f9c-a990-4e7c-bc9c-807d30519536"
		}

		}
		/* */






	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Implementation the abstract Method _getSourcePropertyPairs().
	* (non-PHPdoc)
	* @see Cundd/klassen/Cundd/Music/Model/Cundd_Music_Model_Abstract#_getSourcePropertyPairs()
	*/
	protected function _getSourcePropertyPairs(){
		return false;
	}



	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_managedMode()
	 */
	protected function _managedMode(){
		return self::CUNDD_MANAGED_MODE_NONE;
	}
}
?>