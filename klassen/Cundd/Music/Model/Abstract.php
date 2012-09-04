<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Abstract erweitert Cundd_Core_Model_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 9, 2009
 * @author daniel
 */
abstract class Cundd_Music_Model_Abstract extends Cundd_Core_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_artist; // Cundd_Music_Model_Artist
	protected $_album; // Cundd_Music_Model_Album
	protected $_label; // Cundd_Music_Model_Label
	protected $_designer; // Cundd_Music_Model_Designer
	protected $_origin; // Cundd_Music_Model_Origin
	protected $_genre; // Cundd_Music_Model_Genre
	protected $_artwork; // Cundd_Music_Model_Artwork
	protected $_mbId = ''; // string



	// -> Cundd_Music_Model_Album protected $_color; // Cundd_Music_Model_Color


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Konstruktor
	* @param array $arguments
	* @return Cundd_Music_Model_Abstract|false
	*/
/*	public function __construct(array $arguments = array()){
		parent::__construct($arguments);
	}






	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt ein Album anhand der übergebenen Argumente.
	* @param array $arguments
	* @return array
	*/
	protected function _getSingle(array $arguments){
		$resultCol = $this->_getCol($arguments);

		if($resultCol != NULL){
			$this->_setPropertiesFromArray($resultCol[0],array(),'_');
			return $resultCol[0];
		} else {
			return false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt alle Tracks anhand der übergebenen Argumente. */
	protected function _getCol($arguments){
		$say = false;

		if(!array_key_exists('mode',$arguments)) $arguments['mode'] = $this->_getAdapterMode();
		$request = Cundd::getModel('Music/Adapter_Cundd',$arguments);
		
		//if(count($request->getResult()) == 0){
		if(!$request->getResult()){
			/* DEBUGGEN */if($say) echo 'Fetch from Musicbrainz';/* DEBUGGEN */
			$request = Cundd::getModel('Music/Adapter_Musicbrainz',$arguments);
			/* DEBUGGEN */if($say) $this->pd($request->getResult());/* DEBUGGEN */
		} else {
			/* DEBUGGEN */if($say) $this->pd($request->getResult());/* DEBUGGEN */
		}
		return $request->getResult();
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
	* Die Methode gibt das Key-Value-Paar zurück das die Verbindung zwischen Flat-Array-Key
	* (Source-Name) und der Eigenschaft repräsentiert.
	* @return array array('Source-Name' => 'Property-Name')
	*/
	//abstract protected function _getSourcePropertyPairs();



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt den Methoden-Modus des Adapters zurück. */
	protected function _getAdapterMode(){
		$classname = get_class($this);
		$classnameArray = explode('_',$classname);
		return (string) strtolower(end($classnameArray));
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	* @see Cundd/klassen/Cundd/Core/Model/Cundd_Core_Model_Abstract#_isSingleton()
	*/
	protected function _isSingleton(){
		return (bool) false;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Model/Cundd_Core_Model_Abstract#_isMutable()
	 */
	protected function _isMutable(){
		return (bool) false;
	}

	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isPersistent()
	 */
	protected function _isPersistent(){
		return (bool) false;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_managedMode()
	 */
	protected function _managedMode(){
		return self::CUNDD_MANAGED_MODE_NONE;
	}
}
?>