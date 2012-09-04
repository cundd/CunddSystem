<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_User_Model_Abstract erweitert Cundd_Core_Model_PersistentSingleton.
 * @package Cundd_User
 * @version 1.0
 * @since Dec 24, 2009
 * @author daniel
 */
abstract class Cundd_User_Model_Abstract extends Cundd_Core_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_name = '';
	protected $_password = '';
	protected $_title = '';
	protected $_firstname = '';
	protected $_lastname = '';
	protected $_company = '';
	protected $_department = '';
	protected $_email = '';
	protected $_telephone = '';
	protected $_mobile = '';
	protected $_address = '';
	protected $_zip = '';
	protected $_city = '';
	protected $_country = '';
	protected $_lang = '';
	protected $_birthday = '';
	protected $_homepage = '';
	protected $_chat = '';
	protected $_imagelink = '';
	protected $_active = '';
	protected $_maingroup = '';
	protected $_groups = '';
	protected $_numberOfEntries = '';
	protected $_attributes = '';
	protected $_id = '';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * @param array $arguments
	 * @return false|Cundd_User_Model_Session_Entity
	 */
	public function __construct(array $arguments = array()){
		parent::__construct($arguments);
		
		if(!$this->_id OR $this->_checkIfResetNeeded()){ // Check if the object was loaded
			if(!$this->init($arguments)){
				return false;
			}
		}
		
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode initialisiert das Objekt. */
	public function init(array $arguments = array()){
		$this->_setIfKeyExists('_id',$arguments);
		$this->_setIfKeyExists('_name',$arguments);
		
		return $this->_loadFromCunddBenutzer();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode lädt die Daten von CunddBenutzer. */
	protected function _loadFromCunddBenutzer(){
		$loadedData = NULL;
		if($this->_id){ // Load from given ID
			$loadedData = CunddUser::getDataById($this->_id); 
		} else if($this->_name){ // Load from given username
			$loadedData = CunddUser::getDataByName($this->_name);
		} else if(CunddUser::getSessionUser()){ // Load from session username
			$sessionUsername = CunddUser::getSessionUser();
			$this->pd($sessionUsername);
			$loadedData = CunddUser::getDataByName($sessionUsername);
		} else {
			
		}
		
		if($loadedData){
			$mappedData = $this->_mapValuesFromCunddBenutzer($loadedData);
			$this->_registerProperties($mappedData);
		}
		
		
		return $this->_id;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode mapped die Werte aus der Benutzer-Datenbank auf Array-Elemente deren Keys
	 * den Eigenschaften entsprechen.
	 * @param array $oldArray
	 * @return array
	 */
	protected function _mapValuesFromCunddBenutzer(array $oldArray){
		$newArray = array();
		$newArray['_name'] 			= $oldArray['benutzer'];
		$newArray['_password'] 		= $oldArray['passwort'];
		$newArray['_title'] 		= $oldArray['anrede'];
		$newArray['_firstname'] 	= $oldArray['vorname'];
		$newArray['_lastname'] 		= $oldArray['nachname'];
		$newArray['_company'] 		= $oldArray['firma'];
		$newArray['_department'] 	= $oldArray['abteilung'];
		$newArray['_email'] 		= $oldArray['email'];
		$newArray['_telephone'] 	= $oldArray['telefon'];
		$newArray['_mobile'] 		= $oldArray['handy'];
		$newArray['_address'] 		= $oldArray['adresse'];
		$newArray['_zip'] 			= $oldArray['plz'];
		$newArray['_city'] 			= $oldArray['ort'];
		$newArray['_country'] 		= $oldArray['staat'];
		$newArray['_lang'] 			= $oldArray['lang'];
		$newArray['_birthday'] 		= $oldArray['geburtstag'];
		$newArray['_homepage'] 		= $oldArray['homepage'];
		$newArray['_chat'] 			= $oldArray['chat'];
		$newArray['_imagelink'] 	= $oldArray['bildlink'];
		$newArray['_active'] 		= $oldArray['aktiv'];
		$newArray['_maingroup'] 	= $oldArray['hauptgruppe'];
		$newArray['_groups'] 		= $oldArray['gruppen'];
		$newArray['_numberOfEntries'] = $oldArray['anzahl_eintraege'];
		$newArray['_attributes'] 	= $oldArray['attribute'];
		$newArray['_id'] 			= $oldArray['schluessel'];
		
		return $newArray;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob das Objekt resetted werden muss.
	 * @return boolean
	 */
	protected function _checkIfResetNeeded(){
		$return = (bool) false;
		
		// Case 1: user logged in
			$key = '_cundd_user_userLoggedIn';
			if(Cundd::registry($key)){
				$return = (bool) true;
			}
			
		// Case 2: user logged out
			$key = '_cundd_user_userLoggedOut';
			if(Cundd::registry($key)){
				$return = (bool) true;
			}
		
		
		if($return){
			echo 'sd';
			$this->wp('reset','h1');
		} else {
			echo 'sd';
			$this->wp('no reset','h1');
		}
		
		// Return
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	protected function _managedMode(){
		return self::CUNDD_MANAGED_MODE_NONE;
	}
}
?>