<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Terminal_View_Terminal erweitert Cundd_Core_View_Cundd.
 * @package Cundd_Core_View_Cundd
 * @version 1.0
 * @since Feb 5, 2010
 * @author daniel
 */
class Cundd_Terminal_View_Terminal extends Cundd_Core_View_Cundd{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $model = NULL;
	
	protected $_tempOutput = '';
	protected $_inputPrefix = '';
	protected $_inputMode = NULL;
	
	
	
	const TERMINAL_MODE_COLOR_ENABLED = true;
	const TERMINAL_MODE_COLOR_PHP = '961890';
	const TERMINAL_MODE_COLOR_SHELL = '4C784C';
	
	
	
	const INPUT_SIZE = 100;
	const INPUT_MODE_AJAX = 'ajax';
	const INPUT_MODE_BLANK = 'blank';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		$this->_registerProperties($arguments);
		
		if(!$this->_inputMode) $this->_inputMode = self::INPUT_MODE_BLANK;
		
		if($this->model){
			$this->_createOutput();
		}
		
		$this->_createInput();
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erzeugt den Output der bisherigen Ergebnisse. */
	protected function _createOutput(){
		foreach($this->model->getHistory() as $historyEntry){
			$this->_tempOutput .= "<div class='CunddTerminal row'";
			
			if(self::TERMINAL_MODE_COLOR_ENABLED){
				if($this->model->mode == Cundd_Terminal_Model_Terminal::TERMINAL_MODE_PHP) $this->_tempOutput .= " style='color:#".self::TERMINAL_MODE_COLOR_PHP."' ";
				else if($this->model->mode == Cundd_Terminal_Model_Terminal::TERMINAL_MODE_SHELL) $this->_tempOutput .= " style='color:#".self::TERMINAL_MODE_COLOR_SHELL."' ";
			}
			
			$this->_tempOutput .= "><hr /><span class='call'";
			//$this->_tempOutput .= " style='float:left;padding-right:10px;'";
			$this->_tempOutput .= ">".$historyEntry['call']."</span><span class='result'><pre>".$historyEntry['result']."</pre></span></div>";
			
		}
		
		$this->addOutput($this->_tempOutput);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erzeugt den Input. */
	protected function _createInput(){
		$radioButtonCollection = array(
			array('php','php',),
			array('shell','shell'),
		);
		
		if($this->model->mode == Cundd_Terminal_Model_Terminal::TERMINAL_MODE_PHP) $radioButtonCollection[0][2] = 'true';
		else if($this->model->mode == Cundd_Terminal_Model_Terminal::TERMINAL_MODE_SHELL) $radioButtonCollection[1][2] = 'true';
		
		
		$inputs = array(
			array($this->_inputPrefix.'newCall', 'text',array('size' => self::INPUT_SIZE)),
			array($this->_inputPrefix.'mode', 'radio',array('radioButtonCollection' => $radioButtonCollection)),
		);
		$formname = 'CunddTerminal';
		$options = array('focus' => $this->_inputPrefix.'newCall');
		$right = 6;
		$class = 'CunddForm';
		
		if($this->_inputMode == self::INPUT_MODE_AJAX){
			$action = 'CunddTerminal';
			$targetDivId = 'CunddContent_div';
		} else if($this->_inputMode == self::INPUT_MODE_BLANK){
			$action = 'POST:SELF';
		}
		
		$form = new CunddForm($inputs,$action,$formname,$right,$class,$targetDivId,$options);
		
		$this->addOutput($form->getFormoutput());
		return;
	}
}
?>