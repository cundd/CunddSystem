<?php
@session_start();

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddSystem" lädt die vom System benötigten Modul-Klassen. */
class CunddSystem{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $version = '1.4';
	var $name = 'CunddSystem';
	var $controller;
	
	protected $_initOptions = array();
	protected $_initOptionsRegistryKey = '_initOptions';
	



	// Der System-Mode
	const CUNDD_SYSTEM_MODE = '_cundd_systemMode';
	const CUNDD_SYSTEM_MODE_APP = 'app';
	const CUNDD_SYSTEM_MODE_WEB = 'web';
	
	// Der View-Mode
	const CUNDD_VIEW_MODE = '_cundd_viewMode';
	const CUNDD_VIEW_MODE_HTML = 'html';
	const CUNDD_VIEW_MODE_XML = 'xml';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Konstruktor */
	function CunddSystem(array $initOptions = array()){
		if(array_key_exists('CunddSystem_initOptions',$GLOBALS)){
			$this->_initOptions = $GLOBALS['CunddSystem_initOptions'];
		}
		$this->_initOptions = array_merge($initOptions,$this->_initOptions);
		
		
		
		if(!class_exists('CunddConfig')) CunddSystem::loadConfig();
		self::setIncludePath();
		
//		ob_start();
		    // Den PHP-Klassen-Autoloader einstellen
		    //require(dirname(__FILE__).'/PHPAutoloader.php');
		    CunddSystem::setAutoLoader();
//
//		    $output = ob_get_contents();
//		ob_end_clean();
		
		CunddSystem::setErrorHandler();
		CunddSystem::setExceptionHandler();
		CunddSystem::setDefaultTimezone();
			
		
		ini_set("display_errors",1);
		ini_set("error_reporting",E_ALL ^ E_NOTICE);
		
		
		new CunddEvent('willRegisterInitOptions');
		$this->_registerInitOptions();
		new CunddEvent('didRegisterInitOptions');
		
		
		CunddSystem::setShutdownHandler();
		
		new CunddEvent('willInit5');
		
		$this->createController();
		
		/* Wenn eine Instanz von CunddCalender gerendert werden soll oder das Objekt über
		 * CunddAjax erstellt wurder, werden die JavaScript-Variablen nicht geschrieben und 
		 * die Bibliotheken nicht neu geladen. */
		if($_GET['aufruf'] == "CunddCalendar::render" OR isset($GLOBALS['CunddAjax'])){
			$this->module_laden(false);
		} else {
			$this->printHead();
			
			// Alle Module laden
			$this->module_laden();
			
			// JavaScript-Array "CunddSystemInstanzen" deklarieren
			$this->js_variablen();
			
			$this->printHeadEnd();
		}
		
		new CunddEvent('didInit5');
		
		//echo $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// JAVASCRIPT
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "module_laden' lädt alle Module im angegebenen Verzeichnis. Übergeben 
	wird ob JavaScript-Dateien geladen werden sollen. */
	function module_laden($noJavaScriptLoad = TRUE){
		$verzeichnis = $verzeichnis.CunddConfig::__('CunddBasePath').CunddConfig::__('Cundd_class_path');
		$opendir_ausgabe = opendir(CunddPath::getAbsoluteClassDir());
		
		if($opendir_ausgabe AND !$this->_checkIfNoJavaScript()){
			while ($file = readdir($opendir_ausgabe)){ // Alle Dateien der Reihe nach auslesen
				// JavaScript-Module laden
				if($noJavaScriptLoad AND fnmatch("*.js" ,basename($file))){ // Überprüfen ob der Dateiname auf ".js" endet
					// echo '<script type="text/javascript" src="'.CunddPath::getAbsBaseDir().$verzeichnis.$file.'"></script>
					echo '<script type="text/javascript" src="'.CunddPath::getAbsoluteClassUrl().$file.'"></script>
							';
				}
			}
		}
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Die Methode "js_variablen' deklariert das JavaScript-Array "CunddSystemInstanzen"
	function js_variablen(){
		if(!$this->_checkIfNoJavaScript()){
			echo '<script type="text/javascript">
						// Den MySQL-Prefix als JavaScript-Variable speichern
						var CunddAjaxPHP_verweis = "'.CunddConfig::get('CunddAjaxPHP_verweis').'";
						var CunddContent_div = "'.CunddConfig::get('CunddContent_div').'";
						var CunddPrefix = "'.CunddConfig::get('prefix').'";
						var Cundd_redirect_zeit = "'.CunddConfig::get('Cundd_redirect_zeit').'";
						var Cundd_auto_refresh = "'.CunddConfig::get('Cundd_auto_refresh').'";
						
						
						var CunddMediaPath = "'.CunddConfig::get('CunddMediaPath').'";
						var CunddPath_BasePath = "'.CunddConfig::get('BasePath').'"; 
						var CunddPath_CunddBasePath = "'.CunddConfig::__('CunddBasePath').'";
						var CunddPath_CunddBaseUrl = "'.CunddConfig::__('CunddBaseUrl').'";
						var CunddPath_Cundd_class_path = "'.CunddConfig::__('Cundd_class_path').'";
						
						
						var CunddFiles_upload_dir = "'.CunddConfig::get('CunddFiles_upload_dir').'";
						var CunddTinyMCE_enable = "'.CunddConfig::get('CunddTinyMCE_enable').'";
						var CunddTinyMCE_initForCSSClass = "'.CunddConfig::get('CunddTinyMCE_initForCSSClass').'";
						';
			if(CunddConfig::get('CunddTinyMCE_enable')){
				echo 'var tinyMCE;
					';
			}
						
			echo '		// Die Art des EventListeners für das Ändern der Einträge lesen
						var Cundd_eventlistener_eintrag_aendern = "'.CunddConfig::get('Cundd_eventlistener_eintrag_aendern').'";
						var CunddSystemInstanzen = new Array();
					</script>';
		}
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt ob der Init-Parameter zum nicht Rendern der JavaScript-Dateien
	 * gesetzt ist.
	 * @return boolean
	 */
	protected function _checkIfNoJavaScript(){
		$noJavaScriptLoad = false;
		if(array_key_exists('noJavaScriptLoad',$this->_initOptions)){
			if($this->_initOptions['noJavaScriptLoad']) $noJavaScriptLoad = true;
		} else if(array_key_exists('viewMode',$this->_initOptions)){
			if(strtolower($this->_initOptions['viewMode']) == 'xml' OR $this->getViewMode() == self::CUNDD_VIEW_MODE_XML) $noJavaScriptLoad = true;
		}
		return (bool) $noJavaScriptLoad;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// ENVIRONMENT SETTINGS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode fügt den Zend-Framework zum Include-Path hinzu.
	 * @return string
	 */
	public static function setIncludePath(){
	    $classDir = '';
	    
	    if(@(include_once 'CunddClassLoader.php') == 'OK'){
		// Global class files can be used
	    } else {
		$classDir = dirname(__FILE__).'/'.CunddConfig::__('Cundd_class_path');
	    }
	    
	    
	    // $zendDir = CunddPath::getAbsoluteClassDir().'/Zend/Layout.php';
	    // $zendDir = ini_get('include_path').'/Users/daniel/Sites/CunddSystem/Cundd/klassen';

	    if(file_exists('../Zend/')){
		if($classDir){
		    $classDir = $classDir . PATH_SEPARATOR . '../';
		} else {
		    $classDir = '../';
		}
		    
	    }

	    // Zend-Library-dir
	    $zendDir = CunddConfig::__('Zend_absolute_dir');


	    $result = set_include_path(get_include_path() . PATH_SEPARATOR . $classDir . PATH_SEPARATOR . $zendDir);
	    return $result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode bindet die CunddConfig-Datei ein. */ 
	public static function loadConfig(){
		// Gesamten Klassen-Ordner einlesen
		$verzeichnis = dirname(__FILE__);
		
		// Zuerst CunddConfig laden
		require($verzeichnis.'/CunddConfig.php');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt den Error-Handler für das CunddSystem. */
	private static function setErrorHandler(){
		require_once('CunddError.php');
		set_error_handler(array("CunddError", "error"), E_ALL);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt den Exception-Handler für das CunddSystem. */
	private static function setExceptionHandler(){
		require_once('CunddError.php');
		set_exception_handler(array("CunddError", "exception"));
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Autoloader-Methode.
	 * @return boolean
	 */
	public static function setAutoLoader(){
		$autoLoaderClassname = 'CunddClassLoader';
		if(!class_exists($autoLoaderClassname,false)){
			require_once($autoLoaderClassname.'.php');
		}
		//return call_user_func(array($autoLoaderClassname, 'checkIfClassExistsElseLoad'),$classname);
		
		// Den original-Autoloader setzen
		$return = spl_autoload_register(array($autoLoaderClassname,'checkIfClassExistsElseLoad'));
		
		// Evtl. einen zusätzlichen Autoloader setzen
		$customAutoLoaderCallback = CunddConfig::__('Custom_autoloader');
		if($customAutoLoaderCallback){
			$return = spl_autoload_register($customAutoLoaderCallback);
		}
		return $return; 
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wirft eine Exception.
	 * @param string $msg
	 * @param int $id
	 */
	public static function exception($msg,$id = NULL){
		throw new Exception($msg,$id);
	}
	public static function throwE($msg,$id = NULL){
		self::exception($msg,$id);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt den Shutdown-Handler für das CunddSystem. */
	private static function setShutdownHandler(){
		register_shutdown_function(array('CunddShutdown','handler'));
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt eine Instanz von CunddController. */
	public function createController(){
		$this->controller = new CunddController('initOnly');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Standard-Zeitzone entsprechend der Konfigurationsdatei. */
	public static function setDefaultTimezone(){
		if(CunddConfig::__('default_timezone')){
			$defaultTimezone = CunddConfig::__('default_timezone');
		} else {
			$defaultTimezone = 'Europe/Vienna';
		}
		date_default_timezone_set($defaultTimezone);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Returns the directory path to the system-file.
	 * @return string
	 */
	public static function getDir(){
	    return dirname(__FILE__);
	}


	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// HEAD
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt den Head-Code. */
	public function printHead(){
		return new CunddHead();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt den Head-Code. */
	public function printHeadEnd(){
		return CunddHead::printClosingTag();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// CLASS-GETTERS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liefert eine Instanz des übergebenen Models im relevanten Namespace. */
	public static function getModel($model,array $options = array()){
		return CunddClassLoader::getModel($model,$options);
	}
	

	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liefert eine Referenz auf das Singleton-Objekt.
	 * @param string $model
	 * @param array $options
	 * @return Model
	 */
	public static function getSingleton($model,array $options = array()){
		return CunddClassLoader::getSingleton($model,$options);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
	 * und gibt den Namen des Controllers zurück.
	 * @param string $moduleController Module/Controller::Action
	 * @param array $options
	 * @param string $controllerFileSuffix
	 * @return string
	 */
	public static function getController($moduleController,array $options = array(),$controllerFileSuffix = '.php'){
		return CunddClassLoader::getController($moduleController,$options,$controllerFileSuffix);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
	 * und gibt die Action zurück.
	 * @param string $moduleController Module/Controller::Action
	 * @param array $options
	 * @param string $controllerFileSuffix
	 * @return string
	 */
	public static function getControllerAction($moduleController,array $options = array(),$controllerFileSuffix = '.php'){
		return CunddClassLoader::getControllerAndAction($moduleController,$options,$controllerFileSuffix);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
	 * und gibt diese in einem assoziativen Array zurück.
	 * @param string $controllerDescription Module/Controller::Action|Module/Controller/Action
	 * @param array $options
	 * @param string $controllerFileSuffix
	 * @return array('module' => $module, 'controller' => $controller, 'action' => $action, 'controllerClass' => $controllerName, 'absControllerClassPath' => $absControllerPath)
	 */
	public static function getControllerAndAction($moduleController,array $options = array(),$controllerFileSuffix = '.php'){
		return CunddClassLoader::getControllerAndAction($moduleController,$options,$controllerFileSuffix);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt eine Instanz einer Collection zurück.
	 * @param string $collectionName
	 * @param array $options
	 * @return Cundd_Core_Model_Collection_Abstract
	 */
	public static function getCollection($collectionName,array $options = array()){
		return CunddClassLoader::getCollection($collectionName,$options);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt eine Instanz von Cundd_Vis_Model_Process zurück.
	 * @param array $arguments
	 * @return Cundd_Vis_Model_Process
	 */
	public static function process(array $arguments = array()){
		return self::getModel('Vis/Process',$arguments);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt den Modul-Namen des übergebenen Klassennamen.
	 * @param string $name
	 * @return string
	 */
	public static function getModuleFromClassName($className){
	    return CunddClassLoader::getModuleFromClassName($className);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt den Namespace des übergebenen Klassennamen.
	 * @param string $name
	 * @return string
	 */
	public static function getNamespaceFromClassName($className){
	    return CunddClassLoader::getNamespaceFromClassName($className);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// REGISTRY
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt einen Wert in Cundd_Registry.
	 * @param string $key
	 * @param mixed $value[optional]
	 * @return mixed
	 */
	public static function registry($key,$value = NULL){
		if(func_num_args() == 2){
			return Cundd_Registry::registry($key,$value);
		} else {
			return Cundd_Registry::registry($key);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// MODE-GETTERS & SETTERS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode registriert die Init-Options in einem Registry-Eintrag.
	 * @return void
	 */
	protected function _registerInitOptions(){
		// Den View-Mode speichern
		if(array_key_exists('viewMode',$this->_initOptions)){
			$mode = $this->_initOptions['viewMode'];
		} else {
			$mode = self::CUNDD_VIEW_MODE_HTML;
		}
		
		$this->registry(self::CUNDD_VIEW_MODE,$mode);
		
		
		// Den System-Mode speichern
		if(array_key_exists('systemMode',$this->_initOptions)){
			$sysmode = $this->_initOptions['systemMode'];
		} else if(array_key_exists('Cundd_system_mode',$this->_initOptions)){
			$sysmode = $this->_initOptions['Cundd_system_mode'];
		} else if(CunddConfig::__('Cundd_system_mode')){
			$sysmode = CunddConfig::__('Cundd_system_mode');
		} else {
			$sysmode = self::CUNDD_SYSTEM_MODE_WEB;
		}
		$this->registry(self::CUNDD_SYSTEM_MODE,$sysmode);
		
		
		$this->registry($this->_initOptionsRegistryKey,$this->_initOptions);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt zurück ob es im Applikations ('app') oder dem Website ('web') den System-Mode des Systems zurück.
	 * @return string 'website'|'app'
	 */
	public static function getSystemMode(){
		return self::registry(self::CUNDD_SYSTEM_MODE);
	}
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Mode des Systems zurück.
	 * @param string $mode
	 * @return mixed
	 */
	public static function setSystemMode($mode){
		return Cundd::registry(self::CUNDD_SYSTEM_MODE,$mode);
	}
	
	
	

	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den View-Mode des Systems zurück.
	 * @return string CUNDD_VIEW_MODE_HTML|CUNDD_VIEW_MODE_XML
	 */
	public static function getViewMode(){
		return self::registry(self::CUNDD_VIEW_MODE);
	}
	/**
	 * @deprecated
	 * @see getViewMode()
	 */
	public static function getMode(){
		return self::getViewMode();
	}
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt den View-Mode des Systems.
	 * @param string $mode
	 * @return string
	 */
	public static function setViewMode($mode){
		return Cundd::registry(self::CUNDD_SYSTEM_MODE,$mode);
	}
	public static function setMode($mode){
		return self::setViewMode($mode);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// VIEW GETTERS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * @see CunddClassLoader::getView()
	 */
	public static function getView($view,array $options = array()){
		return CunddClassLoader::getView($view,$options);
	}
}
class Cundd extends CunddSystem {
}



// Beim Laden dieser Datei automatisch eine Instanz von "CunddSystem" erstellen
$CunddSystem_instanz = new CunddSystem();


?>