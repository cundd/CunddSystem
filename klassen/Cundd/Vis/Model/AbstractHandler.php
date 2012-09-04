<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Vis_Model_AbstractHandler erweitert Cundd_Vis_Model_Abstract.
 * @package Cundd_Vis
 * @version 1.0
 * @since Feb 16, 2010
 * @author daniel
 */
abstract class Cundd_Vis_Model_AbstractHandler extends Cundd_Vis_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		// The controller passes the $argv-Variable or the request-data as $arguments
		$this->_unpackParameters($arguments[2]);
		
		
		if(!$this->_processId) $this->_processId = $this->parameters[self::PROCESS_DATA_PREFIX.'_processId'];
		CunddTools::log(__CLASS__.$this->_processId,var_export($this->parameters,true));
		$this->pd($_POST);
		$this->pd($_GET);
		$this->pd($arguments);
		
		
		$this->setState(self::STATE_WILL_CREATE_CONTROLLER);
		
		// Load the new Controller
		$controller = new CunddController($arguments[1]);
		if($controller){
			$this->setState(self::STATE_DID_CREATE_CONTROLLER);
			$this->setState(self::STATE_COMPLETE);
		} else {
			$this->setState(self::STATE_ERROR_CREATE_CONTROLLER);
		}
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	* @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isSingleton()
	*/
	protected function _isSingleton(){
		return true;
	}
}
?>