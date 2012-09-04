<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/** 
 * Die Klasse Cundd_Lang erweitert Cundd_Tools_Abstract.
 * Der Konstruktor der Klasse "CunddLang" liest die in der Konfigurationsdatei 
 * definierte Sprachbibliothek. Eine Instanz der Klasse wird in dieser Datei erstellt. Die 
 * Methode "get" holt den im ersten Parameter übergebenen Sprachbaustein und ersetzt die 
 * dort definierten Tags durch einen optionalen zweiten Parameter.
 * Beispiel: Cundd_Lang wird mit den Parametern "msg_neuer_benutzer" und "benutzername" 
 * aufgerufen. Das Skript lädt nun zuerst die in config.php angegebene Sprachbibliothek 
 * und liest das dort definierte Array "CunddLangLib" ein. Nun dient der erste an 
 * übergebene Parameter als Key für die Abfrage des in der Sprachbibliotek gespeicherten 
 * Wertes. In diesem Beispiel würde der Wert für CunddLangLib("msg_neuer_benutzer") 
 * abgefragt. Das Ergebnis wäre ähnlich dem Satz "Der Benutzer {1} wurde neu erstellt. 
 * Bitte prüfen Sie den Benutzer und aktivieren den Account, wenn der Benutzer die Rechte 
 * erhalten soll."
 * In der endgültigen Ausgabe, soll der String "{1}" durch den Namen des neuen Benutzer 
 * ersetzt werden. Also durch das Element des Parameter-Arrays mit dem Index 1. 
 * Demzufolge würde ein Tag {2} mit dem dritten Element des Parameter-Arrays ersetzt.
 * @license 
 * @copyright
 * @package Cundd_Tools
 * @version 1.2
 * @since Jan 12, 2010
 * @author daniel 
 */
class Cundd_Lang extends Cundd_Tools_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $verion = 1.3;
	
	protected static $_cunddLangLib = array();
	
	const CUNDD_LANG_LIB_REGISTRY_KEY = '_cundd_lang_lib';
	const CUNDD_LANG_CURRENT_LANG_REGISTRY_KEY = '_cundd_lang_currentLanguage';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	public function init(){
		// Die globale Variable $cunddLangLib lokal lesbar machen
		
		
		// Sprachbibliothek entsprechend der Konfigurationsdatei lesen
		$cunddLocalLangPath = CunddPath::getAbsoluteBaseDir().'/lang/'.CunddConfig::get("CunddLangLib").".php";
		$cunddGlobalLangPath = CunddPath::getAbsoluteGlobalBaseDir().'/lang/'.CunddConfig::get("CunddLangLib").".php";
		if(file_exists($cunddLocalLangPath)){
		    require($cunddLocalLangPath);
		} else if(file_exists($cunddGlobalLangPath)){
		    require($cunddGlobalLangPath);
		} else {
		    throw new Exception('Cundd_Lang: Language library '.CunddConfig::get("CunddLangLib").' not found.');
		}
		
		
		self::$_cunddLangLib = $cunddLangLib;
		return $cunddLangLib;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die statische Eigenschaft $_cunddLangLib zurück. Wenn die 
	 * Eigenschaft NULL ist wird die statische Methode init() aufgerufen.
	 * @return array
	 */
	protected function _getLib(){
		if(self::$_cunddLangLib){
			return self::$_cunddLangLib;
		} else {
			return self::init();
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode "get" liest den als Parameter übergebenen Sprachbaustein und ersetzt die 
	 * im Sprachbaustein angegebenen Tags mit den weiteren Parametern. Wenn kein Parameter
	 * übergeben wurde, wird die aktuelle Sprachwahl zurückgegeben.
	 * @param string $msg
	 * @param string|array $para
	 * @return string
	 */
	public static function get($msg = NULL,$para = NULL){
		if($msg !== NULL){
			$text = CunddLang::get_msg($msg,$para);
		} else {
			$text = CunddLang::get_lang();
		}
			
		return $text;
	}
	public static function __($msg = NULL,$para = NULL){
		return self::get($msg,$para);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode "get_msg" liest den als Parameter übergebenen Sprachbaustein und ersetzt 
	 * die im Sprachbaustein angegebenen Tags mit den weiteren Parametern. Wenn kein Para-
	 * meter übergeben wurde, wird die aktuelle Sprachwahl zurückgegeben.
	 * @param string $msg
	 * @param string|array $para
	 * @return string
	 */
	public static function get_msg($msg,$para = NULL){
		$cunddLangLib = self::_getLib();
		$text = NULL;

		// Überprüfen ob der Sprachbaustein existiert
		if(array_key_exists($msg, $cunddLangLib)){ // Wenn ja -> Baustein lesen und parsen
			// Array lesen
			$text = $cunddLangLib[$msg];
		}
		
		// Wenn ein Array als $para übergeben wurde alle tags ersetzen
		if(gettype($para) == 'array'){
			foreach($para as $key => $value){
				$suche = "{".$key."}";
				$text = str_replace($suche, $value, $text);
			}
		} else if(gettype($para) == 'string'){
			$suche = array("{0}","{1}");
			$text = str_replace($suche, $para, $text);
		}
		/*
		for($i = 1; $i < func_num_args(); $i++){
			$ersetzung = func_get_arg($i);
			
			$suche = "{".$i."}";
			
			$text = str_replace($suche, $ersetzung, $text);
		}
		/* */

		/* If no translation was found the lib should check for an slash in the message
		 * and check again with the latter part of the message. If still no translation
		 * can be found the latter part is returned.
		 */
		if($text === NULL && $para === NULL){
		    $tempText = $text;
		    $transTempText = NULL;
		    $tempTextArray = array();
		    if(strpos($msg, '/')){ // Check for slash
			$tempTextArray = explode('/', $msg);
			$tempText = $tempTextArray[1];
			$transTempText = self::get_msg($tempText); // Search for a translation for the latter part
			if($transTempText !== NULL){
			    $text = $transTempText; // Assign the translated text
			} else {
			    $text = $tempText; // Assign the latter part
			}
		    }
		}
		
		
		// If the translation is NULL after all, return the sent message
		if($text === NULL){
		    $text = $msg;
		}
		
		return $text;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode update() überprüft ob eine neu Spracheinstellung per $_POST oder $_GET
	 * übergeben wurde, wenn nicht wird versucht der Wert aus $_SESSION zu lesen. Ist 'lang'
	 * in der Session nicht gesetzt (=NULL) wird falls definiert die Standardeinstellung aus
	 * der Konfigurationsdatei gelesen.
	 * @param string $newLang
	 * @return string
	 */
	public static function update($newLang = NULL){
		new CunddEvent('willUpdateLang');
		if($newLang){
			$relevantNewLang = $newLang;
		} else if(array_key_exists('lang',$_GET)){
			$relevantNewLang = $_GET['lang'];
		} else if(array_key_exists('lang',$_POST)){
			$relevantNewLang = $_POST['lang'];
		} else if(array_key_exists('lang',$_SESSION)){
			$relevantNewLang = $_SESSION['lang'];
		} else if(CunddConfig::get('cunddsystem_multilanguage_default_lang')){
			$relevantNewLang = CunddConfig::get('cunddsystem_multilanguage_default_lang');
		}
		
		
		$_SESSION['lang'] = $relevantNewLang;
		Cundd::registry(self::CUNDD_LANG_CURRENT_LANG_REGISTRY_KEY,$relevantNewLang);
		
		new CunddEvent('didUpdateLang');
		
		return $_SESSION['lang'];
		
		
		
		/*
		if($newLang){
			$_SESSION['lang'] = $newLang;
		} else if($_GET['lang']){
			$_SESSION['lang'] = $_GET['lang'];
		} else if($_POST){
			$_SESSION['lang'] = $_POST['lang'];
		} else if($_SESSION['lang']){
			// Do nothing
		} else if(CunddConfig::get('cunddsystem_multilanguage_default_lang')){
			$_SESSION['lang'] = CunddConfig::get('cunddsystem_multilanguage_default_lang');
		}
		
		return $_SESSION['lang'];
		/* */
	}
	public static function set_lang($newLang = NULL){
		CunddLang::update($newLang);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die aktuelle Spracheinstellung zurück.
	 * @return string
	 */
	public static function get_lang(){
		if(Cundd::registry(self::CUNDD_LANG_CURRENT_LANG_REGISTRY_KEY)){
			return Cundd::registry(self::CUNDD_LANG_CURRENT_LANG_REGISTRY_KEY);
		} else {
			return CunddLang::update();
		}
	}
	/**
	 * @see get_lang()
	 */
	public static function getLang(){
		self::get_lang();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode durchläuft das Language-Library-Verzeichnis und überprüft welche 
	 * Bibliotheken möglich sind.
	 * @return array
	 */
	public static function getAvailableLangLibs(){
		$temp = self::getAvailableLangLibsAndFiles();
		return $temp['libs'];
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode durchläuft das Language-Library-Verzeichnis und überprüft welche 
	 * Bibliotheken-Files möglich sind.
	 * @return array
	 */
	public static function getAvailableLangFiles(){
		$temp = self::getAvailableLangLibsAndFiles();
		return $temp['files'];
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode durchläuft das Language-Library-Verzeichnis und überprüft welche 
	 * Bibliotheken möglich sind.
	 * @return array array( 'libs' => array(...) , 'files' => array(...) )
	 */
	public static function getAvailableLangLibsAndFiles(){
		//array scandir ( string $directory [, int $sorting_order = 0 [, resource $context ]] )
		$cunddLangPath = CunddPath::getAbsoluteBaseDir().'/lang/';
		$allFiles = scandir($cunddLangPath);
		$allLangFiles = array();
		$allLangLibs = array();
		
		
		foreach($allFiles as $key => $langFile){
			if($langFile !== '.' AND $langFile !== '..' AND !preg_match('!^\.+[a-zA-Z0-9]!',$langFile)
			){
				$allLangFiles[] = $langFile;
				$replace = array('CunddLangLib_','.php','.phtml','.txt');
				$allLangLibs[]	= str_replace($replace,'',$langFile);
			}
		}
		
		return array('libs' => $allLangLibs,'files' => $allLangFiles);
	}
}
?>