<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Release erweitert Cundd_Music_Model_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 9, 2009
 * @author daniel
 */
class Cundd_Music_Model_Entity_Release extends Cundd_Music_Model_Entity_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	/*
	protected $_artist; // Cundd_Music_Model_Artist
	// protected $_album; // Cundd_Music_Model_Album
	protected $_label; // Cundd_Music_Model_Label
	protected $_designer; // Cundd_Music_Model_Designer
	protected $_origin; // Cundd_Music_Model_Origin
	protected $_genre; // Cundd_Music_Model_Genre
	/* */
	
	protected $_tracksCol = array(); // Cundd_Music_Model_Track
	protected $_trackcount; // int
	protected $_date = 0; // date
	protected $_title = ''; // string
	protected $_rating = 0; // int
	protected $_catalogId = 0; // int
	protected $_barcode = ''; // string
	protected $_format = ''; // string
	protected $_diskId = ''; // string
	protected $_asin = ''; // string
	protected $_mainColor = ''; // string



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode überschreibt die Methode der Superclass und feuert einen Event. */
	protected function _getCol($arguments){
		$result = parent::_getCol($arguments);

		// Fire the Event
		new CunddEvent('gotReleases',$result);

		return $result;
	}
}
?>