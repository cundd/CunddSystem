<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddCalendar" verwaltet die Ausgabe des Kalenders. Als Kalender-Software 
 wird der Google Kalender verwendet. CunddCalender lädt den angegebenen Kalender und 
 ermöglicht die Verwendung eines eigenen Stylesheets. */
class CunddCalendar{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $version = 0.1;
	var $name = "CunddCalendar";
	var $url; // Speichert die URL des Ziel-Kalenders
	var $bgcolor; // Die Hintergrund-Farbe des Kalenders (google lässt diese Einstellung zu)
	var $baseurl = "http://www.google.com/calendar/"; /* Definiert die Ausgangs-URL der 
														Google-Kalender */
	var $customStylesheetLink = ''; /* Speichert den Link zum zur Instanz gehörenden Style-
									sheet */
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	/* Der Konstruktor überprüft zuerst ob $_SESSION eine Instanz von CunddCalender enthält 
	 und liest diese ein, wenn nicht, wird eine neue Instanz erstellt. Außerdem dient die 
	 Klasse als Router für die einzelnen Methoden, welche im Parameter definiert sind. 
	 Entspricht der Parameter keiner der Routen wird versucht ein neuer Kalender mit dem 
	 übergebenen Parameter zu öffnen. */
	function CunddCalendar($route, $width="800", $height="600", $bgcolor=NULL, $standardMode=NULL, $customStylesheetLink=NULL){
		$say = false;
		
		// Standards verarbeiten
		if(!$bgcolor){
			$this->bgcolor = "FF7300";
		} else {
			$this->bgcolor = $bgcolor;
		}
		if(!$standardMode){
			$this->standardMode = "WEEK";
		} else {
			$this->standardMode = $standardMode;
		}
		if(!$customStylesheetLink){
			// Den Pfad zum eigenen Stylesheet mitsenden
			if(CunddConfig::get('calendarStylesheet')){
				$this->customStylesheetLink = '&stylesheet='.CunddConfig::get('calendarStylesheet');
			} else {
				$this->customStylesheetLink = '';
			}
		} else {
			$this->customStylesheetLink = $customStylesheetLink;
		}
		
		switch($route){
			case "render":
				if($this->sessionLoad()){ // Versuchen die Session zu laden
					$this->render();
				} else {
					echo "NO SESSION";
					CunddTools::pd($_SESSION);
				}
				break;
				
			case "close":
				if($this->sessionLoad()){ // Versuchen die Session zu laden
					$this->close();
				}
				break;
				
			default:
				if($route){ // Wenn der Parameter nicht leer ist
					$this->url = $route;
					$this->init($width, $height);
				}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode versucht eine bestehende Session und die entsprechenden CunddCalender-
	 Daten zu laden. */
	function sessionLoad(){
		if($_SESSION['CunddCalendar'] != ""){ // Wenn ja -> Daten übernehmen
			$temp = unserialize($_SESSION['CunddCalendar']);
			$this->version = $temp->version;
			$this->name = $temp->name;
			$this->url = $temp->url;
			$this->baseurl = $temp->baseurl;
			return true;
		} else {
			CunddTools::log_fehler("CunddCalendar","Couldn't load session");
		}
	}
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt einen iframe mit den benötigten Parametern als src des iframe 
	 * wird die Datei restylegc.php definiert. Das Plugin "restylegc" ermöglicht das Ein-
	 * binden einer eigenen CSS-Datei in den Google-Kalender. */
	function init($width, $height){
		$pathToRestylegc = '.'.CunddConfig::get('CunddBasePath').'/klassen/CunddCalendar/restylegc/restylegc.php';
		$pathToRestylegc = CunddPath::getAbsoluteClassUrl().'CunddCalendar/restylegc/restylegc.php';
		$calendarSource = '';
		
		// Die zu inkludierenden Quellen vorbereiten
		if(gettype($this->url) == 'array'){
			foreach($this->url as $singleUrl){
				$calendarSource .= 'src='.$singleUrl.'&amp;';
			}
		} else {
			$calendarSource .= 'src='.$this->url.'&amp;';
		}
		
		if($width < 400 AND $heigth < 400){ // Mini-Ansicht
			echo '<iframe width="'.$width.'" height="'.$height.'" src="'.$pathToRestylegc.'?mode=MONTH&amp;showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=185&amp;wkst=2&amp;bgcolor=%23'.$this->bgcolor.'&amp;'.$calendarSource.'color=%232952A3&amp;ctz=Europe%2FVienna'.$this->customStylesheetLink.'" style=" 
				border-width:0 " frameborder="0" scrolling="no"></iframe>';
		} else { // Normale Ansicht
			echo '<iframe width="'.$width.'" height="'.$height.'" src="'.$pathToRestylegc.'?mode='.$this->standardMode.'&amp;showTitle=0&amp;showCalendars=0&amp;height=185&amp;wkst=2&amp;bgcolor=%23'.$this->bgcolor.'&amp;'.$calendarSource.'color=%232952A3&amp;ctz=Europe%2FVienna'.$this->customStylesheetLink.'" style=" 
			border-width:0 " frameborder="0" scrolling="no"></iframe>';
		}
		
		/*
		if($width < 400 AND $heigth < 400){ // Mini-Ansicht
			echo '<iframe width="'.$width.'" height="'.$height.'" src="http://www.google.com/calendar/embed?mode=MONTH&amp;showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=185&amp;wkst=1&amp;bgcolor=%23'.$this->bgcolor.'&amp;src='.$this->url.'&amp;color=%232952A3&amp;ctz=Europe%2FVienna" style=" 
				border-width:0 " frameborder="0" scrolling="no"></iframe>';
		} else { // Normale Ansicht
			echo '<iframe width="'.$width.'" height="'.$height.'" src="http://www.google.com/calendar/embed?mode='.$this->standardMode.'&amp;showTitle=0&amp;showCalendars=0&amp;height=185&amp;wkst=1&amp;bgcolor=%23'.$this->bgcolor.'&amp;src='.$this->url.'&amp;color=%232952A3&amp;ctz=Europe%2FVienna" style=" 
			border-width:0 " frameborder="0" scrolling="no"></iframe>';
		}
		/* */
			
		/*
		embed?showTitle=0&amp;showPrint=0&amp;showCalendars=0&amp;showTz=0&amp;height=185&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=d%40cundd.net&amp;color=%232952A3&amp;ctz=Europe%2FVienna",
		/*
		// Den iframe erstellen
		echo '<iframe width="'.$width.'" height="'.$height.'" src="./'.CunddConfig::get("CunddBasePath").
		'/klassen/CunddCalendar?aufruf=CunddCalendar::render';
//		echo '&PHPSESSID='.session_id();
		echo '" style=" border-width:0 " width="800" height="600" frameborder="0" scrolling="no"></iframe>';
		 
		 /* */
		
		// In $_SESSION speichern
		$_SESSION['CunddCalendar'] = serialize($this);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode rendert den übergebenen Kalender. */
	function render(){
		$changeStyleSheet = true;
		//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		ini_set('display_errors','On'); 
		
		$fileStream = fopen($this->url,"r");
		$contentOriginal = fread($fileStream,8192);
		$content = $contentOriginal;
		
		//echo $this->url;
		//echo "<textarea>";
	//	echo "".$content."";
		//echo "</textarea>";
		//$content = escapeshellarg($content);
		
		/* Da die Verweise des Google-Kalenders relativ definiert sind muss dies durch einen 
		absoluten Pfad ersetzt werden. */
		// Stylesheets
			/* Überprüfen ob ein spezielles Stylesheet eingebunden werden soll und dieses 
			ggfl. vorbereiten. */
			if(CunddConfig::get("calendarStylesheet") AND $changeStyleSheet){
				$customStylesheetLink = '

				<style type="text/css">
				*{
				font-size:10px;
				line-height:normal;
				}
				body{
				font-size:1px;
				height:100%;
				}
				
				.st-dtitle, .mv-dayname{
				font-size:0.5em; /* */
				line-height:normal;
				}
				</style>
				
			
<link type="text/css" rel="stylesheet" href="'.CunddConfig::get("calendarStylesheet").'">
';
				$stylesheetSearch1 = '<link type="text/css" rel="stylesheet" href="';
				$stylesheetSearch2 = "<link type='text/css' rel='stylesheet' href='";
				
				$contentTemp = str_replace($stylesheetSearch1, $customStylesheetLink.$stylesheetSearch1.$this->baseurl, $content);
				if($contentTemp != $content){
					$content = $contentTemp;
				} else {
					$content = str_replace($stylesheetSearch2, $customStylesheetLink.$stylesheetSearch2.$this->baseurl, $content);
				}
			}
		
		// Javascript
		$content = $this->editJavaScript($content);
		/*
			$javascriptSearch1 = '<script type="text/javascript" src="!(a-zA-Z0-9)!embedcompiled.js';
			$javascriptSearch2 = "<script type='text/javascript' src='";
			
			$contentTemp = preg_replace($javascriptSearch1, $javascriptSearch1.$this->baseurl, $content);
			if($contentTemp != $content){
				$content = $contentTemp;
			} else {
				$content = preg_replace($javascriptSearch2, $javascriptSearch2.$this->baseurl, $content);
			}
		 /* */
		
		
		// head-, body-, meta-Tags usw. löschen
		$htmlSearch = array('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>Google Kalender</title>',
							'</body>',
							'<html>',
							'</html>',
							'<head>',
							'</head>',
							);
		 /* */
			$content = str_replace($htmlSearch, "", $content);
		
		
		// Pfade
		$pathSearch = '"/';
		$content = str_replace($pathSearch, '"'.$this->baseurl, $content);
		
		echo $content;
		return $content;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode rendert das bearbeitete JavaScript */
	function editJavaScript($content){
		// Die originale JavaScript-Datei einlesen
		$javascriptSearch = '![a-zA-Z0-9]*embedcompiled__de.js!';
		$foundArray = array(); // Speichert die gefunden Strings
		
		if(!preg_match_all($javascriptSearch, $content, $foundArray)){
			echo 'NO MATCH';
		}
		
		$javaScriptUrl = $this->baseurl.$foundArray[0][0];
		
		$fileStream = fopen($javaScriptUrl,"r");
		$javaScriptOriginal = fread($fileStream,'5388000');
		$javaScriptData = $javaScriptOriginal;
		//echo 'B'.CunddConfig::get('mysql_host').'B';
		
//		require(CunddConfig::get('CunddBasePath').'/klassen/
		require('../CunddCalendar/substitution_js.php');
		
		// Den Inhalt der original JavaScript-Datei bearbeiten
		$search1 = 'this.xd=this.a+"calendar"+(b?"/hosted/"+this.b:"");this.c=this.a+"calendar/feeds"};';
		$javaScriptData = str_replace($search1, $search1.' alert(this.a)', $javaScriptOriginal);
		if($javaScriptData == $javaScriptOriginal){
			echo 'NO SUBSTITUTION';
		}
		/* */
		
		// Das neue JavaScript einfügen
		$javascriptSearch1 = '!<script type="text/javascript" src="[a-zA-Z0-9]*embedcompiled__de.js"></script>!';
		$javascriptSearch2 = "<script type='text/javascript' src='";
		
		$contentTemp = preg_replace($javascriptSearch1, '<script type="text/javascript" src="'.$javaScriptUrl.'"></script>
									<script>'.$javaScriptData.'</script>', $content);
		if($contentTemp != $content){
			$content = $contentTemp;
		} else {
			echo 'NO SUBSTITUTION';
		}
		
		//echo $javaScriptData;
		return $content;
		
		
		
	}
	
	


	
	
	
	
	
}
?>