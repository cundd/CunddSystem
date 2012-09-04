<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Vis_Model_Grid_Handler erweitert Cundd_Vis_Model_AbstractHandler.
 * @package Cundd_Vis
 * @version 1.0
 * @since Feb 16, 2010
 * @author daniel
 */
class Cundd_Vis_Model_Grid_Handler extends Cundd_Vis_Model_AbstractHandler{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		$allowed = false;
		
		$this->_processId = $arguments[self::PROCESS_DATA_PREFIX.'_processId'];
		
		// $arguments[3] ist der Account-Name, $arguments[4] des Passwort
		$allowedClients = $this->_config('grid_allowed_clients');
		$account = $arguments[3];
		$password = $arguments[4];
		foreach($allowedClients as $client){
			if($client[0] == $account AND $client[1] == $password){
				$allowed = true;
				break;
			}
		}
		
		if($allowed){
			if($log) CunddTools::log(__CLASS__,"Account $account did connect.");
			return parent::_construct($arguments);
		} else {
			CunddTools::log(__CLASS__,"Unauthorized account $account tried to connect with $password.");
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die meisten Methode zur Statusbestimmung, zum Abbruch, etc. sind aktuell nicht 
	 * wirksam da der Server hier nur in Form des Outputs antwortet. */
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#setState($state)
	 */
	public function setState($state){
		return Cundd_Vis_Model_Process_Adapter::write($state,$this->_processId);
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#kill($force)
	 */
	public function kill($force = NULL){
	}	
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#isAlive()
	 */
	public function isAlive($force = NULL){
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#cleanup()
	 */
	public function cleanup(){
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#getOutput()
	 */
	public function getOutput(){
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#didPassState($state)
	 */
	public function didPassState($state){
	}
	
}
?>