<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddTinyMCE" initialisiert den TinyMCE-Editor mit den system-weiten Ein-
 * stellungen.
 */ 
class CunddTinyMCE {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $tinyMCELibraryName = 'jquery.tinymce.js';
	private $pathToLibraries;
	private $state;
	private $settingsString; // Speichert das Einstellungs-Javascript-Objekt
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	/**
	 * @param array $overwriteSettings
	 * @return unknown_type
	 */
	public function CunddTinyMCE(array $overwriteSettings = NULL){
		if(CunddConfig::get('CunddTinyMCE_enable')){
			$noErrors = $this->printLoadLibraries();
			$noErrors *= $this->loadSetting($overwriteSettings);
			
			$noErrors *= $this->printOpenScriptTag();
			
			$noErrors *= $this->printSettings();
			$noErrors *= $this->initTinyMCE();
			
			$noErrors *= $this->printCloseScriptTag();
			
			if($noErrors){
				$this->setState(1);
			} else {
				$this->setState(2);
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode bindet die TinyMCE-Bibliotheken ein. */
	private function printLoadLibraries(){
		$this->pathToLibraries = '.'.CunddConfig::get('CunddBasePath').CunddConfig::get('Cundd_class_path').'CunddTinyMCE/';
		$this->pathToLibraries = CunddPath::getAbsoluteClassUrl().'CunddTinyMCE/';
		
		$pathToFile = $this->pathToLibraries.$this->tinyMCELibraryName;
		$linkLibrary = '<script type="text/javascript" src="'.$pathToFile.'"></script>';
		
		// Das normale TinyMCE-File einbinden
		$pathToFile = $this->pathToLibraries.'tiny_mce.js';
		//$linkLibrary .= '<script type="text/javascript" src="'.$pathToFile.'"></script>';
		
		echo $linkLibrary;
		
		return true;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt die TinyMCE-Einstellungen. */
	private function loadSetting($overwriteSettings = NULL){
		$settingsPara = CunddConfig::get('CunddTinyMCE_settings');
		
		// Variable Parameter einlesen
		/* Beispiele: 	mode : "textareas",
						theme : "advanced",
						plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
						theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
						theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
						theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
						theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
						theme_advanced_toolbar_location : "external",
						theme_advanced_toolbar_align : "left",
						theme_advanced_resizing : true,
		*/
		foreach($settingsPara as $key => $para){
			// Die Overwrite-Settings anwenden wenn passende gesetzt sind
			if($overwriteSettings){
				if($overwriteSettings[$key]){
					$para = $overwriteSettings[$key];
				}
			}
			
			$keys[] = $key;
			$paras[] = $para;
			$keysAndParas[] = $key.' : "'.$para.'"';
		}
		
		// Fixe Parameter einlesen
			// Das Custom-Stylesheet
			if(CunddConfig::get('CunddMainStylesheet')){
				$keysAndParas[] = 'content_css : "'.CunddConfig::get('CunddMainStylesheet').'"';
			}
			
			// Den Mode des Plugins einlesen
			switch(CunddConfig::get('CunddTinyMCE_mode')){
				case 'textareas':
					$keysAndParas[] = 'mode : "textareas"';
					break;
				case 'onclick':
					$keysAndParas[] = 'mode : "none"';
					break;
				default:
					$keysAndParas[] = 'mode : "textareas"';
					break;
			}
			
			// Der Pfad zum Skript
			$keysAndParas[] = 'script_url : "'.$this->pathToLibraries.'tiny_mce.js"';
			
			// Den Save-Handler
			if($overwriteSettings){
				if(!array_key_exists('save_onsavecallback',$overwriteSettings)) $keysAndParas[] = 'save_onsavecallback : "CunddTinyMCE.onSave"';
			} else {
				$keysAndParas[] = 'save_onsavecallback : "CunddTinyMCE.onSave"';
			}
			//$keysAndParas[] = 'save_oncancelcallback : "oncancel"';
			
			// Die Sprache laden und überprüfen ob ein entsprechendes Sprachpaket installiert ist
			$langCode = CunddLang::get();
			$defaultLangCode = CunddConfig::get('cunddsystem_multilanguage_default_lang');
			$langFileOfCurrentLang = '.'.CunddConfig::get('CunddBasePath').CunddConfig::get('Cundd_class_path').'CunddTinyMCE/langs/'.$langCode.'.js';
			$langFileOfDefaultLang = '.'.CunddConfig::get('CunddBasePath').CunddConfig::get('Cundd_class_path').'CunddTinyMCE/langs/'.$defaultLangCode.'.js';
			$langFileOfEnglishLang = '.'.CunddConfig::get('CunddBasePath').CunddConfig::get('Cundd_class_path').'CunddTinyMCE/langs/en.js';
			
			if(file_exists($langFileOfCurrentLang)){ // Wenn die Sprachdatei der aktuellen Sprache existiert
				$keysAndParas[] = 'language : "'.$langCode.'"';
			} else if(file_exists($langFileOfDefaultLang)){ // Wenn die Sprachdatei der Standard-Sprache existiert
				$keysAndParas[] = 'language : "'.$defaultLangCode.'"';
			} else if(file_exists($langFileOfEnglishLang)){ // Wenn die englische Standardsprachdatei existiert
				$keysAndParas[] = 'language : "en"';
			} else { // Wenn keine Sprachdatei gefunden wurde
				CunddTools::error('CunddTinyMCE','No matching language-pack-file found.');
			}
			
		
		$this->settingsString = 'var CunddTinyMCE_settings = {'.CunddTools::arrayToString($keysAndParas,',
		').'};';
		
		if($this->settingsString){
			return true;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Javascript-Settings aus. */
	private function printSettings(){
		echo $this->settingsString;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode öffnet den Script-HTML-Tag. */
	private function printOpenScriptTag(){
		echo '<script type="text/javascript">';
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode schließt den Script-HTML-Tag. */
	private function printCloseScriptTag(){
		echo '</script>';
	}
	
	
		
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt den Javascript-Code zum Initiieren von TinyMCE. */
	private function initTinyMCE(){
		//echo 'tinymce.init(CunddTinyMCE_settings);';
		
		/*
		if(CunddConfig::get('CunddTinyMCE_initForCSSClass')){
			echo "$('textarea.".CunddConfig::get('CunddTinyMCE_initForCSSClass')."')";
		} else {
			echo "$('textarea')";
		}
		
		echo '.tinymce(CunddTinyMCE_settings);
			}
		);';
		/* */
		
		
		/* echo '$().ready(
			function() {
			';
		
		
		if(CunddConfig::get('CunddTinyMCE_initForCSSClass')){
			echo "$('textarea.".CunddConfig::get('CunddTinyMCE_initForCSSClass')."')";
		} else {
			echo "$('textarea')";
		}
		
		echo '.tinymce(CunddTinyMCE_settings);
			}
		);';
		/*
		*
		*/
		return true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt $this->state. */
	public function setState($newState){
		$this->state = $newState;
		return $this->state;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Status als String zurück */
	public function getState(){
		$stateCaptions = array('Not init','Is active','An error occured');
		
		return $stateCaptions[$this->state]; 
	}
}