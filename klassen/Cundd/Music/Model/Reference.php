<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Music_Model_Reference erweitert Cundd_Music_Model_Entity_Album.
 * @package Cundd_Music_Model_Entity_Album
 * @version 1.0
 * @since Jan 23, 2010
 * @author daniel
 */
class Cundd_Music_Model_Reference extends Cundd_Music_Model_Entity_Album{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	public function __construct(array $arguments = array()){
		$sourceEntity;
		if($this->_setIfKeyExists(0,$arguments,$sourceEntity)){
			// CunddTools::wp('save');
			$this->_loadFromObject($arguments[0]);
		} else if($this->_setIfKeyExists('reference',$arguments,$sourceEntity)){
			// CunddTools::wp('save');
			$this->_loadFromObject($arguments['reference']);
		} else {
			// CunddTools::wp('load not save');
		}
		return parent::__construct($arguments);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht die Werte der angegebenen Eigenschaften ($properties) aus dem 
	 * übergebenen Reference-Objekt zu lesen.
	 * @param Cundd_Music_Model_Entity_Abstract $referenceObject
	 */
	protected function _loadFromObject(Cundd_Music_Model_Entity_Abstract $referenceObject){
		$this->_overwriteThis($referenceObject);
		
		
		/*
		$properties = array('_artist','_album','_label','_designer','_origin','_genre','_tracksCol','_trackcount',
				'_date','_title','_rating','_catalogId','_barcode','_format','_diskId','_asin');
		foreach($properties as $property){
			if($referenceObject->getValue($property)){
				$this->$property = $referenceObject->getValue($property);
				echo $property.' '.$referenceObject->getValue($property).'<br>';
			}
		}
		$this->save();
		
		/*
		$this->pd($this->load());
		$this->pd();
		/* */
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt zurück ob das Objekt leer ist, also keine Referenz definiert wurde.
	 * @return boolean
	 */
	public function isEmpty(){
		if(!$this->_title AND !$this->_artist){
			$return = (bool) true;
		} else {
			$return = (bool) false;
		}
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Music/Model/Cundd_Music_Model_Abstract#_isMutable()
	 */
	protected function _isMutable(){
		return (bool) true;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Music/Model/Cundd_Music_Model_Abstract#_isPersistent()
	 */
	protected function _isPersistent(){
		return (bool) true;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Music/Model/Cundd_Music_Model_Abstract#_isSingleton()
	 */
	protected function _isSingleton(){
		return (bool) true;
	}
}
?>