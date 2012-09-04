<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Entity_Color erweitert Cundd_Core_Model_Color.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 15, 2009
 * @author daniel
 */
class Cundd_Music_Model_Entity_Color extends Cundd_Core_Model_Color{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode . */
	protected function _getScanSettings(){
		return array(
		CunddConfig::__('Music/Color__scanRows'),
		CunddConfig::__('Music/Color__scanColumns')
		);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode lädt eine Instanz anhand einer MbId.
	* @param string $mbid
	* @return Cundd_Music_Model_Entity_Color|false
	*/
	public function loadWithMbId($mbid){
		$cunddDb = Cundd::getModel('Music/Adapter_Cundd');

		$arguments = array('mbId' => $mbid);
		$record = $cunddDb->get($arguments);

		// $this->pd($record);

		if($record){
			$oldInstanceSaved = $record[0]['colorObject'];
			$oldInstance = unserialize($oldInstanceSaved);
			// $this->pd(($oldInstance));
			return $oldInstance;
		} else {
			return (bool) false;
		}
	}
}
?>