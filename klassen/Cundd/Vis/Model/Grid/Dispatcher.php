<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Vis_Model_Grid_Dispatcher erweitert Cundd_Vis_Model_AbstractDispatcher.
 * @package Cundd_Vis
 * @version 1.0
 * @since Feb 16, 2010
 * @author daniel
 */
class Cundd_Vis_Model_Grid_Dispatcher extends Cundd_Vis_Model_AbstractDispatcher{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_childProcess = NULL;
	
	
	const GRID_LOCAL_CONTROLLER_NAME = 'Vis/Grid';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		$this->_autoDispatch = false;
		parent::_construct($arguments);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#dispatch($arguments)
	 */
	public function dispatch(array $arguments = array()){
		$this->_setIfKeyExists('controller',$arguments);
		$this->_setIfKeyExists('parameters',$arguments);
		
		$this->_childProcess = Cundd::process($arguments);
		$this->_processId = $this->_childProcess->getProcessId();
		
		$this->_prepareExtraDataToBeSent();
		
		if($this->_processId AND $this->controller){
			return $this->_childProcess->dispatch();
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Eigenschaften aus dem übergebenen Array und gibt sie an den
	 * Kindprozess weiter.
	 * @param array $arguments
	 */
	protected function _setArgumentsAndCopyToChildProcess(array $arguments = array()){
		$this->_setIfKeyExists('controller',$arguments);
		$this->_setIfKeyExists('parameters',$arguments);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode bereitet die speziellen Daten zum Senden vor. Beispielsweise der Controller 
	 * des Kindprozess. */
	protected function _prepareExtraDataToBeSent(){
		$this->_childProcess->controller = self::GRID_LOCAL_CONTROLLER_NAME;
		
		$this->_childProcess->parameters = $this->parameters;
		$this->_childProcess->parameters[Cundd_Vis_Model_Abstract::GRID_REMOTE_CONTROLLER_VAR_PREFIX] = $this->controller;
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Kind-Prozess zurück.
	 * @return Cundd_Vis_Model_Process_Dispatcher
	 */
	public function getChild(){
		return $this->_childProcess;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#setState($state)
	 */
	public function setState($state){
		return $this->_childProcess->setState($state);
	}
	/**
	 * (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#cleanup($processId)
	 */
	public function cleanup(){
		return $this->_childProcess->cleanup();
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#getCommand()
	 */
	public function getCommand(){
		return $this->_childProcess->getCommand();
	}
	/**
	 * (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#getOutput()
	 */
	public function getOutput(){
		return $this->_childProcess->getOutput();
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#isAlive()
	 */
	public function isAlive(){
		return $this->_childProcess->isAlive();
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#kill($force)
	 */
	public function kill($force = false){
		return $this->_childProcess->kill($force);
	}
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#didPassState($state)
	 */
	public function didPassState($state){
		return $this->_childProcess->didPassState($state);
	}
}
?>