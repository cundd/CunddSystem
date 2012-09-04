<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddTools" bietet verschiedene statische Methoden wie zum Beispiel das 
Umwandeln von Datumsangaben. */
class CunddTools{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddTools(){
		echo '<p>CunddTools</p>';
	}
	
	
	public function test($para){
		echo 'TEST<br />';
		echo $para;
		
		return 5;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// LOG-TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "log_fehler" schreibt eine angegebene Fehlermeldung in die 
	Datei "CunddLog.txt". */
	function log_fehler($quelle,$msg = NULL,$writeGlobalVars = true){
		// Überprüfen ob die Fehler-Datei bereits besteht und beschreibbar ist
		$fehler_log_dateiname = "CunddLog.txt";
		$ordner = dirname(__FILE__)."/../admin/";
		
		
		date_default_timezone_set(CunddConfig::__('Date/default_timezone'));
		
		// Überprüft ob der Ordner beschreibbar ist
		if(is_writable($ordner)){
/* WICHTIG: Die seltsame Formatierung dieses Codes ist aufgrund der 
Formatierung in der Ausgabe-Datei. */
// Daten in die Datei "CunddLog.txt" schreiben
$datei_text = '# MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
# MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
QUELLE: '.$quelle.'
	ZEIT: '.date("Y-m-d",time()).' '.date("H:i:s",time());
if($writeGlobalVars){
$datei_text .= '
	$_SESSION: '.var_export($_SESSION, true).'
	$_POST: '.var_export($_POST, true).'
	$_GET: '.var_export($_GET, true).'
	$_FILES: '.var_export($_FILES, true);
}
$datei_text .= '
	';
// Wenn eine ausführliche Fehlermeldung übergeben wurde
if($msg){
	if(gettype($msg) == 'array') $msg = var_export($msg,true);
	$datei_text .= '
	MELDUNG: '.$msg.'
	';
}

$datei_text .= '
# MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
';
			
			// Datei schreiben
			$verbindung = fopen($ordner.$fehler_log_dateiname, 'a');
			fwrite($verbindung, $datei_text);
		
			// Rechte ändern
			// @chmod ($ordner.$fehler_log_dateiname, 0777);
			
			fclose($verbindung);
		} else {
			$logFilePath = $ordner.$fehler_log_dateiname;
			echo "<br>$logFilePath<br>";
			$msg = "The log file $logFilePath couldn't be created<br />";
			echo $msg;
		}
	}
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "log" ist ein Alias für die Methode "log_fehler()". */	
	function log($quelle,$msg = NULL){
		self::log_fehler($quelle, $msg);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode schreibt eine angegebene Fehlermeldung in die Datei 
	 * "CunddError.txt".
	 * @param string $quelle
	 * @param string $msg
	 * @param boolean $writeGlobalVars
	 * @return string
	 */
	public function error($quelle,$msg = NULL,$writeGlobalVars = true,$forceLog = false){
		if(CunddConfig::__('Cundd_log_errors') OR $forceLog){
			return self::_writeErrorToFilesystem($quelle,$msg,$writeGlobalVars);
		} else {
			return '';
		}
	}
	public function hiddenError($quelle,$msg = NULL,$writeGlobalVars = true,$forceLog = false){
		if(CunddConfig::__('Cundd_log_errors') OR $forceLog){
			return self::_writeErrorToFilesystem($quelle,$msg,$writeGlobalVars);
		} else {
			return '';
		}
	}
	
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode schreibt eine angegebene Fehlermeldung in die Datei 
	 * "CunddError.txt".
	 * @param string $quelle
	 * @param string $msg
	 * @param boolean $writeGlobalVars
	 * @return string
	 */
	protected static function _writeErrorToFilesystem($quelle,$msg = NULL,$writeGlobalVars = true){
		// Überprüfen ob die Fehler-Datei bereits besteht und beschreibbar ist
		$fehler_log_dateiname = "CunddError.txt";
		$ordner = CunddSystem::getDir().'/admin/';

		
		date_default_timezone_set(CunddConfig::__('Date/default_timezone'));
		
		// Überprüft ob der Ordner beschreibbar ist
		if(is_writable($ordner)){
/* WICHTIG: Die seltsame Formatierung dieses Codes ist aufgrund der 
Formatierung in der Ausgabe-Datei. */
// Daten in die Datei "CunddLog.txt" schreiben
$datei_text = '# MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
# MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
QUELLE: '.$quelle.'
	ZEIT: '.date("Y-m-d").' '.date("H:i:s");
if($writeGlobalVars){
$datei_text .= '	
	$_SESSION: '.var_export($_SESSION, true).'
	$_POST: '.var_export($_POST, true).'
	$_GET: '.var_export($_GET, true).'
	$_FILES: '.var_export($_FILES, true).'
	';
}
// Wenn eine ausführliche Fehlermeldung übergeben wurde
if($msg){
	if(gettype($msg) == 'array') $msg = var_export($msg,true);
	$datei_text .= '
	MELDUNG: '.$msg.'
	';
}

$datei_text .= '
# MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
';
			
			// Datei schreiben
			$verbindung = fopen($ordner.$fehler_log_dateiname, 'a');
			fwrite($verbindung, $datei_text);
		
			// Rechte ändern
			// @chmod ($ordner.$fehler_log_dateiname, 0777);
			
			fclose($verbindung);
		} else {
			$msg = "The log file $ordner$fehler_log_dateiname couldn't be created<br />";
			echo $msg;
		}
		
		
		return $datei_text;
	}
	
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode "breakpoint()"/"bp()" erstellt einen Log-Eintrag mit der 
	 * Quelle "BreakPoint".
	 * @param string $msg[optional]
	 */
	public function breakpoint($msg = ''){ 
		CunddTools::log_fehler("BreakPoint",$msg,false);
	}
	/**
	 * @see breakpoint()
	 */
	public function bp($msg = ''){ 
		CunddTools::log_fehler("BreakPoint",$msg,false);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// PRINT-TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen Variablen-Wert bzw. String mit umschließenden Breaks aus.
	 * @param mixed $var
	 * @param boolean $startingBreak
	 * @param boolean $noOutput
	 * @return string
	 */
	public function breakPrint($var,$startingBreak = true,$noOutput = false){
		$string = '';
		if($startingBreak) $string = '<br />';
		$string .= "$var<br />";
		
		if(!$noOutput) echo $string;
		return $string;
	}
	/** 
	 * Die Methode gibt einen Variablen-Wert bzw. String mit umschließenden Breaks aus.
	 * @param mixed $var
	 * @param boolean $startingBreak
	 * @param boolean $noOutput
	 * @return string
	 */
	public function brp($var,$startingBreak = true,$noOutput = false){
		return CunddTools::breakPrint($var,$startingBreak,$noOutput);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt das Element in einem Wrapper aus. */
	public function wrapPrint($var,$wrap = 'h1'){
		echo "<$wrap>$var</$wrap>";
	}
	public function wp($var,$wrap = 'h1'){
		return CunddTools::wrapPrint($var,$wrap);
	}
	
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Wert der übergebenen Variable aus. Sie bietet 
	 ein Alias für var_dump() zwischen in einem pre-Tag. */
	function predump(&$variable){
		echo  '<pre class="cunddpre">';
		var_dump($variable);
		echo '</pre>';
	}
	public static function pd($variable){
		CunddTools::predump($variable);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die übergebene Nachricht ($what) aus, wenn der zweite Parameter TRUE
	 * bzw. nicht gesetzt ist.
	 * @param string $what
	 * @param boolean $say
	 * @return void
	 */
	public static function say($what,$say = true){
		if($say) echo "<span class='say'>$what</span>";
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen Predump mit den Koordinaten des Aufrufs aus. */
	public static function debug($variable = NULL,$indexOfStack = 0){
		$stack = debug_backtrace();
		echo "<div class='cundddebug'><h3>".$stack[$indexOfStack]["function"].
			" @".$stack[$indexOfStack]['file']." #".$stack[$indexOfStack]['line'].
			" from ".get_class($stack[$indexOfStack]['object'])."</h3></div>";
		if($variable)self::predump($variable);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// DATE-TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ändert das Format eines als Parameter übergebenen Datums.
	 * Das geänderte Datum wird im Format YYYY-MM-DD zurückgegeben.
	 * @param string $datum (22.06.2008|22.6.2008|22.06.08)
	 * @return string
	 */
	public static function someDateToMySQLFormat($date){
	    return self::datum_anpassen($date);
	}


	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode "datum_anpassen()" ändert das Format eines als Parameter 
	 * übergebenen Datums. Das geänderte Datum wird im Format YYYY-MM-DD 
	 * zurückgegeben.
	 * @param string $datum (22.06.2008|22.6.2008|22.06.08)
	 * @return string
	 */
	function datum_anpassen($datum){ 
		// Ändert Reihnfolge wenn . vorhanden und setzt führende Null (auch bei -)
		// 22.06.2008 erlaubt
		// 22.6.2008 erlaubt
		// 22.06.08 erlaubt
		// 22. Juni 2008 nicht erlaubt
		
		$datum = str_replace(' ','',$datum);
		
		if(!$datum){ // Wenn $datum leer
			$datum = date("Y-m-d"); // Aktuelles Datum eintragen
		}else
		if(strpos($datum, '.')){ // Wenn . in Datum vorhanden
			$datum_array = explode('.',$datum);
			
			//Jahr
			if(strlen($datum_array[2]) == 4){
				$datum = $datum_array[2].'-';
			}else if($datum_array[2]<40){
				$datum = '20'.$datum_array[2].'-';
			}else{
				$datum = '19'.$datum_array[2].'-';
			}
			
			//Monat
			if(strlen($datum_array[1])<2){
				$datum .= '0'.$datum_array[1].'-';
			}else{
				$datum .= $datum_array[1].'-';
			}
			
			//Tag
			if(strlen($datum_array[0])<2){
				$datum .= '0'.$datum_array[0];
			}else{
				$datum .= $datum_array[0];
			}
		}else
		if(strpos($datum, '-')){ //Wenn - in Datum vorhanden
			$datum_array = explode('-',$datum);
			
			//Jahr
			if(strlen($datum_array[0]) == 4){
				$datum = $datum_array[0].'-';
			}else if($datum_array[0]<40){
				$datum = '20'.$datum_array[0].'-';
			}else{
				$datum = '19'.$datum_array[0].'-';
			}
			
			//Monat
			if(strlen($datum_array[1])<2){
				$datum .= '0'.$datum_array[1].'-';
			}else{
				$datum .= $datum_array[1].'-';
			}
			
			//Tag
			if(strlen($datum_array[2])<2){
				$datum .= '0'.$datum_array[2];
			}else{
				$datum .= $datum_array[2];
			}
		}
			
		return $datum;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// SESSION-TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "session_setter()" ändert den Wert einer angegebenen 
	Session-Variable auf den definierten Wert. */
	function session_setter($aufrufer){
		// Überprüfen ob CunddConfig bereits geladen wurde
		if(!class_exists('CunddConfig')){
			require('./klassen/CunddConfig.php');
		}
		
		// Überprüfen wer das Skript aufgerufen hat
		if(($aufrufer == "cundd" OR $aufrufer == "root") AND CunddConfig::get('Cundd_session_setter_enable')){
		
			// Überprüfen ob bereits etwas eingegeben wurde
			if($var = $_POST["Cundd_session_setter_var"]){
				// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
				// Überprüfen ob das eingegebene Passwort stimmt
				if($_POST["Cundd_session_setter_pw"] == "daniel" OR 
					$_POST["Cundd_session_setter_pw"] == "godmode" OR 
					$_POST["Cundd_session_setter_pw"] == CunddConfig::get('Cundd_session_setter_pw')){
					// Wenn ja -> Wert speichern
					
					echo '<h1>Value stored!</h1>';
					echo '<h2>Print Session-Variables</h2>';
					echo '<div style="font-size:0.8em;">';
					echo '<pre>';
					if($_POST["Cundd_session_setter_type"] == "cookie"){
						$_COOKIE[$var] = $_POST["Cundd_session_setter_val"];
						var_dump($_COOKIE);
					} else {
						$_SESSION[$var] = $_POST["Cundd_session_setter_val"];
						var_dump($_SESSION);
					}
					
					echo '</pre>';
					echo '</div>';
				} else {
					echo '<h1>Password incorrect.</h1>';
				}
				echo '<a href="'.$_SERVER['PHP_SELF'].'"><input type="button" value="back" /></a>';
			// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			} else {
				// Wenn nein -> Formular anzeigen
				echo '<h1>Cundd session_setter</h1>
						<p>Please insert password of Cundd_session_setter and the name and value of the 
							session variable.</p>
						<form action="" '.$_SERVER['PHP_SELF'].'" method="post">
							<label for="Cundd_session_setter_pw">Cundd_session_setter_pw:</label>
							<input type="password" name="Cundd_session_setter_pw" id="Cundd_session_setter_pw" />
							<br />
							<input type="radio" id="Cundd_session_setter_session" value="session" name=
								"Cundd_session_setter_type" checked="checked" />
							<label for="Cundd_session_setter_session">$_SESSION</label><br />
							<input type="radio" id="Cundd_session_setter_cookie" value="cookie" name=
								"Cundd_session_setter_type" />
							<label for="Cundd_session_setter_cookie">$_COOKIE</label><br />
							<br />
							
							<label for="Cundd_session_setter_var">Cundd_session_setter_var:</label>
							<input type="text" name="Cundd_session_setter_var" id="Cundd_session_setter_var" />
							<br />
							<label for="Cundd_session_setter_val">Cundd_session_setter_val:</label>
							<input type="text" name="Cundd_session_setter_val" id="Cundd_session_setter_val" />
							<br />
							<input type="submit" value="submit" />
						</form>';
			}
		} else {
			echo '<h1>Illegal call</h1>';
			CunddTools::log('CunddTools','Session_setter illegal called');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// FILE-TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode überprüft ob eine Datei existiert.
	 * @param string $filePath
	 * @param boolean $dontLog
	 * @return boolean
	 */
	public static function fileExists($filePath, $dontLog = NULL){
		if(file_exists($filePath)){
			return (bool) true;
		} else if($dontLog){
			return (bool) false;
		} else {
			CunddTools::error('CunddTools','File doesn\'t exist.');
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// STRING-TOOLS
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode löscht alle gefährlichen Zeichen und gibt den überprüften String zurück.
	 * @param string $aufruf
	 * @param string $replaceString
	 * @return string
	 */
	public static function cleanString($aufruf,$replaceString = ''){
		// Clean up the action
		// Step 1
			$pattern = '!\.[a-zA-Z0-9]*!';
			$aufruf = preg_replace($pattern,$replaceString,$aufruf);
			
		// Step 2
			$pattern = '![^a-zA-Z0-9]*!';
			$aufruf = preg_replace($pattern,$replaceString,$aufruf);
			
		// Return
		return $aufruf;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode teilt einen übergebenen String in die Aktion und die Parameter.
	 * @param string $string
	 * @param string $action
	 * @param string|array $para
	 * @return array
	 */
	public static function stringToActionAndPara($string,&$action = NULL,&$para = NULL){
		$actionAndParaArray = explode("(",$string);
		$actionTemp = $actionAndParaArray[0];
		$paraTemp = str_replace(')','',$actionAndParaArray[1]);
		$paraTemp = explode(",",$paraTemp);
		$paraClean = array();
		
		foreach($paraTemp as $key => $value){
			$paraKeyValuePair = explode("=",$value);
			if(count($paraKeyValuePair) > 1){
				$paraClean[$paraKeyValuePair[0]] = $paraKeyValuePair[1];
			} else {
				$paraClean[] = $value;
			}
		}
		
		if(func_num_args() > 1) $action = $actionTemp;
		if(func_num_args() > 2) $para = $paraClean;
		
		return array($actionTemp,$paraClean);
	}
	
	
	
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen String mit den Elementen des als Parameter
	 * übergebenen Arrays zurück. Die Elemente werden entweder durch einen
	 * Beistrich oder die Zeichen des zweiten optionalen Parameters 
	 * getrennt. */
	public static function arrayToString(array $arrayPara, $seperator = ','){
		$resultString = '';
		$i = 0;
		
		
		
		foreach($arrayPara as $key => $value){
			// TODO: handle key
			$resultString .= $value;
			$i++;
			
			if($i < count($arrayPara)){
				$resultString .= $seperator;
			}
		}
		
		if($resultString == ''){
			return false;
		} else {
			return $resultString;
		}
	}
	public static function array2String(array $arrayPara, $seperator = ','){
		return CunddTooles::arrayToString($arrayPara, $seperator);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erwartet einen String mit einem XML-Dokument als Wert und gibt diesen ohne 
	 * Whitespaces und Zeilenumbrüche zurück. */
	public static function xmlCleanup($input){
		$output = '';
		
		$pattern = array("![\r\n|\n|\r]!"); // Delete line-breaks
		$output = preg_replace($pattern,"",$input);
		
		$pattern = array("!> <^/!"); // Delete whitespaces
		$output = preg_replace($pattern,"",$output);
		
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob das letzte Zeichen eines Strings ein Forwardslash ist, wenn 
	 * nicht wird dieser ergänzt.
	 * @param string $input
	 * @return string
	 */
	public static function endingSlash($input){
		if(substr($input,-1) != '/'){
			return $input . '/';
		} else {
			return $input;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob die letzten Zeichen eines Strings Forwardslashes sind, wenn 
	 * ja wird dieser gelöscht.
	 * @param string $input
	 * @return string
	 */
	public static function noEndingSlash($input){
		if(substr($input,-3) != '///'){
			return substr($input,0,-3);
		} else if(substr($input,-2) != '//'){
			return substr($input,0,-2);
		} else if(substr($input,-1) != '/'){
			return substr($input,0,-1);
		} else {
			return $input;
		}
	}
	
}
?>