<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Terminal_Model_Abstract erweitert Cundd_Core_Persistent.
 * @package Cundd_Core_Persistent
 * @version 1.0
 * @since Feb 5, 2010
 * @author daniel
 */
/**
 * @author daniel
 *
 */
class Cundd_Terminal_Model_Abstract extends Cundd_Core_Persistent{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_history = array();
	protected $_result = '';
	protected $_newCall = '';
	protected $_maxExecutionTime = 10;
	protected $_debug = false;
	
	
	
	private static $__userMustBeInGroup = NULL;
	public static $__userMustBeInGroupInitValue = 1;
	
	
	
	public $mode = '';
	
	
	
	const TERMINAL_MODE_PHP = 'php';
	const TERMINAL_MODE_SHELL = 'shell';
	const TERMINAL_MODE_SPECIAL = 'terminal_special';
	const CUNDDTERMINAL_POST_VAR_PREFIX = 'CunddTerminal_';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		// Das Setzen der Eigenschaft $__userMustBeInGroup von außen ist nicht erlaubt.
		$arguments['__userMustBeInGroup'] = NULL;
		
		if(!$this->_isPermitted()) return NULL;
		
		
		$this->_registerProperties($arguments);
		
		if(!$this->mode) $this->mode = self::TERMINAL_MODE_SHELL;
		
		if($this->_newCall){
			$result = $this->execute();
		} else {
			return NULL;
		}
		
		
		/* DEBUGGEN */
		if($this->_debug){
			$this->pd($this->_result);
		}
		/* DEBUGGEN */
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode verteilt die Befehle entsprechend dem aktuellen Modus.
	 * @return mixed
	 */
	public function execute(){
		if(!$this->_isPermitted()) return NULL;
		
		set_time_limit($this->_maxExecutionTime);
		
		$result = NULL;
		
		// Check if the new call is a special terminal-call
		if(substr($this->_newCall,0,2) == 'T:'){
			$this->mode = self::TERMINAL_MODE_SPECIAL;
			$this->_newCall = substr($this->_newCall,2);
		}
		
		/*
		// Special-Wörter abfangen
		switch($this->_newCall){
			case 'T:CLEAR':
			case 'T:C':
				$this->clearHistory();
				$this->_result = '';
				break;
				
			case 'T:DEVELOPER':
				$this->_result = 'Daniel';
				break;
				
			case 'T:WEBSITE':
				$this->_result = CunddLink::newHardLink('http://cundd.net');
				break;
				
			case 'T:INFO':
				$this->_result = var_export(CunddConfig::getAll('superman'),true);
				break;
				
			case 'T:TIME':
				$this->_result = date('H:i:s');
				break;
			
			case 'T:DATE':
				$this->_result = date('F d Y');
				break;
				
			case 'T:CLIENT':
				$this->_result = Cundd_Client::getClientString();
				break;
				
			case 'T:EXIT':
			case 'T:LOGOUT':
				CunddLogin::logout();
				break;

				
			// LAST BEFORE default
			case 'T:REDO':
			case 'T:R':
				$lastHistoryEntry = end($this->_history);
				$this->_newCall = $lastHistoryEntry['call'];
			
			default:
			*/
		switch($this->mode){
			case self::TERMINAL_MODE_SPECIAL:
				$result = $this->_executeSpecial();
				break;
			
			case self::TERMINAL_MODE_SHELL:
				$result = $this->_executeShell();
				break;
			
			case self::TERMINAL_MODE_PHP:
				$result = $this->_executePhp();
				break;
			
			default:
				break;
		}
		
		if($this->_newCall != 'T:CLEAR') $this->addToHistory();
		
		return $result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode führt die in der Eigenschaft $_newCall definierten Befehle aus. */
	protected function _executePhp(){
		$this->_prepareCall();
		$tempNewCall = $this->_newCall;
		if(substr($tempNewCall, -1) != ';'){
			$tempNewCall .= ';';
		}
		
		try{
			ob_start();
				$last_line = eval($tempNewCall);
				$resultTemp = ob_get_contents();
			ob_end_clean();
		} catch(Error $e){
			
		} catch(Exception $e){
			
		}
		
		$this->_result = $resultTemp;
		return $this->_result;
		/*
		$newCallTemp = 'return '.$this->_newCall;
		$newCallTemp = $this->_newCall;
		$this->_result = eval($newCallTemp);
		return $this->_result;
		/* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode führt die in der Eigenschaft $_newCall definierten Befehle aus. */
	protected function _executeShell(){
		$this->_prepareCall();
		ob_start();
			$last_line = passthru($this->_newCall,$resultTemp);
		$resultTemp = ob_get_contents();
		ob_end_clean();
		
		$this->_result = $resultTemp;
		return $this->_result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode führt spezielle Cundd-spezifische Methoden aus. */
	protected function _executeSpecial(){
		/* Wenn der neue Aufruf einen Fowardslash (/) enthält wird versucht ein passendes 
		 * Plugin zu laden. Plugins befinden sich im Unterordner plugins des Model-Verzeichnisses.
		 * Plugin-Calls müssen im Format "pluginName/staticPluginMethod?para1=value1+para2=value2+..." vorliegen. Der Name des 
		 * Plugins entspricht dabei dem Namen der Klassen-Datei.
		 */
		if(strpos($this->_newCall,'/')){
			$this->_dispatchToPlugin();
		} else { // Es ist ein Aufruf ohne Plugin
			// Special-Wörter abfangen
			switch($this->_newCall){
				case 'T:CLEAR':
				case 			'CLEAR':
				case 'T:C':
				case 			'C':
					$this->clearHistory();
					$this->_result = '';
					break;
					
				case 'T:DEVELOPER':
				case 			'DEVELOPER':
					$this->_result = 'Daniel';
					break;
					
				case 'T:WEBSITE':
				case 			'WEBSITE':
					$this->_result = CunddLink::newHardLink('http://cundd.net');
					break;
					
				case 'T:INFO':
				case 			'INFO':
					$this->_result = var_export(CunddConfig::getAll('superman'),true);
					break;
					
				case 'T:TIME':
				case 			'TIME':
					$this->_result = date('H:i:s');
					break;
				
				case 'T:DATE':
				case 			'DATE':
					$this->_result = date('F d Y');
					break;
					
				case 'T:CLIENT':
				case 			'CLIENT':
					$this->_result = Cundd_Client::getClientString();
					break;
					
				case 'T:EXIT':
				case 			'EXIT':
				case 'T:LOGOUT':
				case 			'LOGOUT':
					CunddLogin::logout();
					break;
	
					
				case 'T:REDO':
				case 			'REDO':
				case 'T:R':
				case 			'R':
					$lastHistoryEntry = end($this->_history);
					$this->_newCall = $lastHistoryEntry['call'];
					$this->mode = $lastHistoryEntry['mode'];
					$this->_result = $this->execute();
				
				default:
					$this->_result = $this->_t("The special call couldn't be processed.");
					break;
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode löscht überflüssige Zeichen aus dem Aufruf.
	 * @return string
	 */
	protected function _prepareCall(){
		$tempString = $this->_newCall;
		
		/*
		$tempString = str_replace("\\'","'",$tempString);
		$tempString = str_replace('\\"','"',$tempString);
		$tempString = str_replace('\\\\','\\',$tempString);
		/* */
		$tempString = stripslashes($tempString);
		
		$this->_newCall = $tempString;
		return $this->_newCall;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt das Resultat zurück.
	 * @return string
	 */
	public function getResult(){
		return $this->_result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die History zurück.
	 * @return array
	 */
	public function getHistory(){
		return $this->_history;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode löscht die History. */
	public function clearHistory(){
		$this->_history = array();
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Cundd_Core_Abstract#_beforeSave()
	 */
	protected function _beforeSave(){
		$this->_newCall = NULL;
		return NULL;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode hängt den aktuellen Aufruf und dessen Ergebnis an die History. */
	public function addToHistory(){
		$this->_history[] = array('call' => $this->_newCall,'result' => $this->_result,'mode' => $this->mode);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * @see isPermitted() */
	protected function _isPermitted(){
		return self::isPermitted();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob der aktuelle Benutzer die Berechtigung zum Verwenden des 
	 * Terminal hat.
	 * @return boolean
	 */
	public static function isPermitted(){
		$isAllowed = (bool) false;
		// Read config
		if(!self::$__userMustBeInGroup AND CunddConfig::__('Security/terminal_userMustBeInGroup')) self::$__userMustBeInGroup = CunddConfig::__('Security/terminal_userMustBeInGroup');
		if(!self::$__userMustBeInGroup) self::$__userMustBeInGroup = self::$__userMustBeInGroupInitValue;
		
		if(is_numeric(self::$__userMustBeInGroup)){ // A group-id has been chosen
			$isAllowed = CunddUser::isIn(self::$__userMustBeInGroup);
		} else if(self::$__userMustBeInGroup == 'n'){
			$isAllowed = CunddUser::isLoggedIn();
		} else if(self::$__userMustBeInGroup == CunddGroup::getId(CunddGroup::NAME_OF_PUBLIC_GROUP)){
			$isAllowed = (bool) true;
		}
		
		return $isAllowed;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ruft ein Plugin auf das mittels Special-call (Aufruf der mit "T:" beginnen) 
	 * auf und übergibt dabei die beim Aufruf definierten Parameter. */
	protected function _dispatchToPlugin(){
		$say = false;
		
		$callAndPara = explode('?',$this->_newCall);
		$para = explode(',',$callAndPara[1]);
		
		
		$newCallArray = explode('/',$callAndPara[0]);
		$pluginClass = "Terminal/Plugin_".ucfirst(strtolower($newCallArray[0]));
		$pluginClass = CunddClassLoader::getModelName($pluginClass);
		
		
		if($newCallArray[1]){
			/* DEBUGGEN */if($say) echo 'do'.$newCallArray[1].'<br>';/* DEBUGGEN */
			$action = $newCallArray[1].'Action';
		} else {
			$action = 'indexAction';
		}
		
		$classDir = CunddPath::getAbsoluteClassDir();
		
		
		// DEBUGGEN
		if($say){
			echo $this->_newCall.'<br>';
			echo $classDir.CunddClassLoader::parseClassname($pluginClass).'::'.$action;
		}
		// DEBUGGEN
		
		if(file_exists($classDir.CunddClassLoader::parseClassname($pluginClass)) OR strpos($relClassPath,'Zend') !== false){
			$this->_result = call_user_func(array($pluginClass,$action),$para);
		} else {
			$this->_result = $this->_t("The plugin you called doesn't seem to exist.");
		}
		
	}
}
?>