<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Collection_Abstract erweitert Cundd_Music_Model_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 14, 2009
 * @author daniel
 */
abstract class Cundd_Music_Model_Collection_Abstract extends
Cundd_Core_Model_Collection_Abstract{
//	Cundd_Music_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_children = array();
	protected $_fetchFromMusicbrainz = false;
	


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Konstruktor
	*/
	public function __construct(array $arguments = array()){
		if(count($arguments) == 0){
			return $this;
		} else {
			if(!array_key_exists('mode',$arguments)) $arguments['mode'] = $this->_getAdapterMode();
			
			$col = $this->_getCol($arguments);
			
			// Laden der Entity-Instanzen
			if($col){
				foreach($col as $entityKey => $entityArguments){
					/*
					echo "
					ENTITY KEY $entityKey MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM
					";
					CunddTools::pd($entityArguments);
					/* */
					
					if(is_numeric($entityKey)){
						$this->_children[$entityKey] = Cundd::getModel('Music/Entity_'.$arguments['mode']);
						$this->_children[$entityKey]->loadFromArray($entityArguments);
						$this->collection[$entityKey] = $this->_children[$entityKey];
					}
				}
			}
			return $this;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt die Anzahl der Kind-Entity-Objekte zurück. */
	public function numChildren(){
		return count($this->_children);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt ein/alle Kind-Element zurück. */
	public function getChildren($childIndex = NULL){
		if(is_numeric($childIndex) AND $this->_children){
			return $this->_children[$childIndex];
		} else if($this->_children){
			return $this->_children;
		} else {
			return (bool) false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt alle Tracks anhand der übergebenen Argumente. */
	/**
	 * 
	 * @param array $arguments
	 * @return unknown_type
	 */
	protected function _getCol($arguments){
		$say = false;

		if(!array_key_exists('mode',$arguments)) $arguments['mode'] = $this->_getAdapterMode();
		$request = Cundd::getModel('Music/Adapter_Cundd',$arguments);
		
		//if(count($request->getResult()) == 0){
		if(!$request->getResult()){
			/* DEBUGGEN */if($say) echo 'Fetch from Musicbrainz';/* DEBUGGEN */
			$this->_fetchFromMusicbrainz = true;
			$request = Cundd::getModel('Music/Adapter_Musicbrainz',$arguments);
			/* DEBUGGEN */if($say) {$this->pd($request->getResult()); echo '<br>';}/* DEBUGGEN */
		} else {
			/* DEBUGGEN */if($say) {$this->pd($request->getResult()); echo '<br>';}/* DEBUGGEN */
		}
		
		new CunddEvent('gotReleases');
		
		return $request->getResult();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt den Methoden-Modus des Adapters zurück. */
	protected function _getAdapterMode(){
		$classname = get_class($this);
		$classnameArray = explode('_',$classname);
		return (string) strtolower(end($classnameArray));
	}



	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isSingleton()
	 */
	protected function _isSingleton(){
		return (bool) true;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isPersistent()
	 */
	protected function _isPersistent(){
		return (bool) false;
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isMutable()
	 */
	protected function _isMutable(){
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