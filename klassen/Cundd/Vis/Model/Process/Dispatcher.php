<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Vis_Model_Process_Dispatcher erweitert Cundd_Vis_Model_AbstractDispatcher.
 * @package Cundd_Vis
 * @version 1.0
 * @since Feb 13, 2010
 * @author daniel
 */
class Cundd_Vis_Model_Process_Dispatcher extends Cundd_Vis_Model_AbstractDispatcher{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#dispatch($arguments)
	 */
	public function dispatch(array $arguments = array()){
		$this->_setIfKeyExists('controller',$arguments);
		$this->_setIfKeyExists('parameters',$arguments);
		
		if($this->_processId AND $this->controller){
			$this->_prepareParameters();
			return $this->_createProcess();
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ruft PHP mit den definierten Parametern auf.
	 * @return int
	 */
	protected function _createProcess(){
		// Der Aufruf wird zusammengefügt
		$command = "php ";
		if(CunddConfig::__('ini_file') AND self::USE_PHP_INI_FILE) $command .= '-c '.CunddConfig::__('ini_file');
		$command .= " ".CunddPath::getAbsoluteModulDir('Vis').$this->_handler.".php ";
		$command .= " ".$this->controller." ";
		if($this->_parameterString) $command .= $this->_parameterString;

		/**
		 * Redirect script-output to file:
		 * Eine Datei muss das Ergebnis aufnehmen damit das Skript nicht bis zur Beendigung wartet.
		 */
		$command .= ' > '.CunddPath::getAbsoluteTempDir().$this->_processId.self::PROCESS_OUTPUT_SUFFIX;

		// Return the PID
		$command .= ' & echo $!';


		$this->_command = $command;


		$returnArray = array();
		new CunddEvent('willCreateProcess');
		exec($this->_command,$returnArray);
		new CunddEvent('didCreateProcess');
		/* */


		/* PIPE TEST */
		/*
		 $pipes = array();
		 $descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "r")
			);
			$resource = proc_open($command,$descriptorspec,$pipes);

			$this->pd($resource);
			$this->pd($pipes);
			echo stream_get_contents($pipes[0]).'<br>';
			echo stream_get_contents($pipes[1]).'<br>';
			echo stream_get_contents($pipes[2]).'<br>';
			echo '<br>MWMWMWMWMWMWMWMWMWMWMWMW';
			/* */


		$this->_pid = $returnArray[0];
		return $this->_pid;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#setState($state)
	 */
	public function setState($state){
		return Cundd_Vis_Model_Process_Adapter::write($state,$this->_processId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#didPassState($state)
	 */
	public function didPassState($state){
		return Cundd_Vis_Model_Process_Adapter::didPassState($state,$this->_processId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#cleanup($processId)
	 */
	public function cleanup(){
		return Cundd_Vis_Model_Process_Adapter::cleanup($this->_processId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#getOutput()
	 */
	public function getOutput(){
		return Cundd_Vis_Model_Process_Adapter::getOutput($this->_processId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#isAlive()
	 */
	public function isAlive(){
		if(!$this->_pid) return (bool) false;
		$return = false;
		$returnArray = array();

		$command = 'ps -o pid | grep '.$this->_pid;
		exec($command,$returnArray);

		if($returnArray){
			if($returnArray[0] == $this->_pid){
				$return = true;
			}
		}
		return (bool) $return;
	}
	/**
	 * @see isAlive()
	 */
	public function exists(){
		return $this->isAlive();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Vis/Model/Cundd_Vis_Model_Abstract#kill($force)
	 */
	public function kill($force = false){
		if(!$this->_pid) return (bool) false;

		$command = 'kill ';
		if($force) $command .= '9 ';
		$command .= $this->_pid;
		exec($command);

		if(!$this->isAlive()) $this->writeState(self::STATE_CANCELED);

		return $this->isAlive();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// CONFIG
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	* @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_isSingleton()
	*/
	protected function _isSingleton(){
		return false;
	}
}
?>