<?php
if(!class_exists('CunddGalerie')){
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddGalerie" ist Elternklasse der "Galerie-Klassen" wie z.B. CunddImages
 oder CunddAlbum. */
class CunddGalerie extends CunddAttribute {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $version = 0.1;
	var $pointerToCurrent = 1; // Die Variable speichert den Index des aktuellen Bildes
	var $currentDepth = 0; /* Speichert die aktuelle Tiefe einer Ausgabe in der Bild-Baum-
							Struktur */
	var $imageArray = array(); /* Die Variable speichert die Daten aller in das Objekt 
								gelesenen Bilder und Gruppen */
	var $classPrefix = 'galerie'; /* Speichert den Prefix der Klasse; dieser wird beispiels-
									weise für die Definition der CSS-Class verwendet */
	var $fileId = 0; // Speichert die ID dieses Objektes
	var $debug = false;
	
	private static $className = 'CunddGalerie'; // Speichert den Namen der Klasse
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * Der Konstruktor ruft die Methode init() auf. */
	function CunddGalerie($parameter = NULL){		
		$this->init($parameter);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode überprüft zuerst ob $_SESSION eine Instanz von CunddGalerie enthält 
	 und liest diese ein, wenn nicht, wird eine neue Instanz erstellt. */
	function init($parameter = NULL){
		$say = false;
		$this->className = get_class($this);
		
		if($parameter != NULL){ // Überprüfen ob Parameter übergeben wurde
			$sucharray = array(); /* Speichert das Array der Suchparameter die an CunddFiles 
								   übergeben werden */;
			if(gettype($parameter) != "array"){
				$sucharray['parent'] = $parameter;
			} else if(gettype($parameter) == "array"){
				foreach($parameter as $parameterName => $parameterWert){
					$sucharray[$parameterName] = $parameterWert;
				}
			}
			
			// Die ID des Files speichern
			if($sucharray['parent']){
				$this->fileId = $sucharray['parent'];
			} else if($sucharray['schluessel']){
				$this->fileId = $sucharray['schluessel'];
			}
			
			
			$sucharray['search'] = $suchanfrage;
			$sucharray['type'] = 'cundd/group';
			$sucharray['output'] = "return";
			
			$temp1 = CunddFiles::get($sucharray);
			
			$sucharray['type'] = 'image/%';
			$temp2 = CunddFiles::get($sucharray);
			
			// Überprüfen ob beide Ergebnisse Arrays sind
			if(gettype($temp1) == "array"){
				$this->imageArray = $temp1;
				
				// $temp2 prüfen
				if(gettype($temp2) == "array"){
					$this->imageArray = array_merge($temp1, $temp2);
				}
			} else if(gettype($temp2) == "array"){
				$this->imageArray = $temp2;
			}
			
			$this->pointerToCurrent = 0;
		
			/* wenn $parameter = NULL ist -> überprüfen ob $_SESSION eine Instanz 
			 von CunddGalerie enthält sonst eine neue Instanz ohne Parameter 
			 erstellen. */
		} else if(!$this->loadSession()){
			$temp1 = CunddFiles::getOfType(NULL,'cundd/group');
			$temp2 = CunddFiles::getOfType(NULL,'image/%');
			
			// Überprüfen ob beide Ergebnisse Arrays sind
			if(gettype($temp1) == "array"){
				$this->imageArray = $temp1;
				
				// $temp2 prüfen
				if(gettype($temp2) == "array"){
					$this->imageArray = array_merge($temp1, $temp2);
				}
			} else if(gettype($temp2) == "array"){
				$this->imageArray = $temp2;
			}
			
			$this->pointerToCurrent = 0;
		}
		
		// DEBUGGEN
		if($say){
			echo '<br />parameter = '.$parameter.'<br />';
			CunddTools::pd($this);

		}
		// DEBUGGEN --------------------------------------
		
		
		// In $_SESSION speichern
		$this->save();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt alle Variablen zurück. */
	function release(){
		$this->className = get_class($this);
		$this->pointerToCurrent = 0;
		$this->imageArray = NULL;
		$this->fileId = 0;
		
		unset($_SESSION['CunddGalerie']);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt alle Eigenschaften auf die Eigenschaften des übergebenen Objekts. */
	function overwrite(&$newObject){
		$this->version = $newObject->version;
		$this->className = $newObject->className;
		$this->pointerToCurrent = $newObject->pointerToCurrent;
		$this->imageArray = $newObject->imageArray;
		$this->classPrefix = $newObject->classPrefix;
		$this->fileId = $newObject->fileId;
		$this->currentDepth = $newObject->currentDepth;
		
		$this->save();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ruft die Methode overwrite() auf und setzt anschließend das übergebene 
	 Objekt auf NULL. */
	function overwriteAndRelease(&$newObject){
		$this->overwrite($newObject);
		$newObject->release();
		$newObject = NULL;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Informationen zu einem Bild aus. Ob das Bild ausgegeben werden 
	 soll wird durch die Klasse dieser Instanz bestimmt. Welche Zusatz-Informationen ange-
	 zeigt werden sollen wird in der Konfigurations-Datei definiert. */
	function show($thumbnailsOnly = NULL){
		/*
		if($this->className == "CunddImages"){
			$this->showImageWithPicture();
		} else {
			$this->showImageWithoutPicture();
		}
		/* */
		$this->debug();
		$this->showImageWithPicture($thumbnailsOnly);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt ein Bild aus. Zur Anzeige der dazugehörigen Informationen wird dann 
	 die Methode showImageWithoutPicture() aufgerufen. */
	function showImageWithPicture($thumbnailsOnly = NULL){
		// Einen div um das Bild und dessen Informationen legen
		$wert['schluessel'] = $this->imageArray[$this->pointerToCurrent]['schluessel'];
		$wert['currentDepth'] = 'depth'.$this->currentDepth;
		echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_'.$this->classPrefix.'_container', 'output');
		
		if(CunddConfig::get('CunddGalerie_show_picture_after_text')){
			// Die Methode showImageWithoutPicture() aufrufen
			$this->showImageWithoutPicture();
			
			// Das aktuelle Bild auslesen
			$this->showImagePicture($thumbnailsOnly);
		} else {
			// Das aktuelle Bild auslesen
			$this->showImagePicture($thumbnailsOnly);
			
			// Die Methode showImageWithoutPicture() aufrufen
			$this->showImageWithoutPicture();
		}
		
		// Den div schließen
		echo CunddTemplate::inhalte_einrichten(NULL, 4, 'universal_close_div', 'output');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt ein Bild aus. Zur Anzeige der dazugehörigen Informationen wird dann 
	 die Methode showImageWithoutPicture() aufgerufen. */
	function showImagePicture($thumbnailsOnly = NULL, $maxWidth = NULL, $maxHeight = NULL){
		// Das aktuelle Bild auslesen
		$currentImage = $this->imageArray[$this->pointerToCurrent];
		
		// Das Bild anzeigen
		$currentImagesAttributes = self::getAttributesFromString($currentImage['attribute']);
		$forceRemotePath = $currentImagesAttributes['forceRemotePath'];
		
		$currentImage[$this->classPrefix.'_show_'.$this->classPrefix] = CunddFiles::get_real_path($currentImage['dateiname'], $thumbnailsOnly, $forceRemotePath); // Pfad des Bildes
		$currentImage['aufruf'] = 'CunddImages::stepInto';
		$currentImage['data'] = $currentImage['schluessel'];
		$currentImage['currentDepth'] = 'depth'.$this->currentDepth;
		
		// Die Größe des Bildes ermitteln
		if($maxWidth){
			$currentImage['maxWidth'] = $maxWidth;
		} else if($thumbnailsOnly){
			$currentImage['maxWidth'] = CunddConfig::get('CunddGalerie_max_preview_image_width');
		} else {
			$currentImage['maxWidth'] = CunddConfig::get('CunddGalerie_max_detail_image_width');
		}
		
		if($maxHeight){
			$currentImage['maxHeight'] = $maxHeight;
		} else if($thumbnailsOnly){
			$currentImage['maxHeight'] = CunddConfig::get('CunddGalerie_max_preview_image_height');
		} else {
			$currentImage['maxHeight'] = CunddConfig::get('CunddGalerie_max_detail_image_height');
		}
				
		echo CunddTemplate::inhalte_einrichten($currentImage, 4, $this->classPrefix.'_show_'.$this->classPrefix, 'output');
	}
		
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt ein Bild und die dazugehörigen Informationen aus. Welche Informa-
	 tionen angezeigt werden sollen wird in der Konfigurations-Datei definiert. */
	function showImageWithoutPicture(){
		// Das aktuelle Bild auslesen
		$currentImage = $this->imageArray[$this->pointerToCurrent];
		
		// Alle Felder auslesen
		$felder = CunddFelder::get_files();
		
		foreach($felder as $feld){;
			if(CunddConfig::get($this->classPrefix."_show_".$feld['name'])){
				$wert['config_parameter'] = $this->classPrefix."_show_".$feld['name'];
				$wert['field_name'] = $feld['name'];
				$wert['field_value'] = $currentImage[$feld['name']];
				$wert['currentDepth'] = 'depth'.$this->currentDepth;
				
				echo CunddTemplate::inhalte_einrichten($wert, 4, 'galerie_images_information', 'output');
			}
		}
		
		// Die ID des Bildes ausgeben
		$feld['name'] = 'schluessel';
		
		if(CunddConfig::get($this->classPrefix."_show_".$feld['name']) AND 
		   CunddConfig::get('CunddGalerie_show_id_to_offline_users')){
			// Wenn die ID öffentlich sichtbar ist
			$wert['config_parameter'] = $this->classPrefix."_show_".$feld['name'];
			$wert['field_name'] = $feld['name'];
			$wert['field_value'] = $currentImage[$feld['name']];
			$wert['currentDepth'] = 'depth'.$this->currentDepth;
			
			echo CunddTemplate::inhalte_einrichten($wert, 4, 'galerie_images_information', 'output');
		
		/* Wenn die ID nicht öffentlich sichtbar ist, wird überprüft ob ein Benutzer ein-
		 geloggt ist. */
		} else if(CunddConfig::get($this->classPrefix."_show_".$feld['name']) AND 
				  $_SESSION["benutzer"] AND $_SESSION["gruppen"]){
			// Wenn die ID öffentlich sichtbar ist
			$wert['config_parameter'] = $this->classPrefix."_show_".$feld['name'];
			$wert['field_name'] = $feld['name'];
			$wert['field_value'] = $currentImage[$feld['name']];
			$wert['currentDepth'] = 'depth'.$this->currentDepth;
			
			echo CunddTemplate::inhalte_einrichten($wert, 4, 'galerie_images_information', 'output');
		} else {
			// Die ID nicht ausgeben
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode leert diese Instanz und initialisiert diese neu. Beim Aufruf von init() 
	 wird dabei nur ein bestimmtes Bild (definiert durch die $imageId) aus der Datenbank 
	 geladen. */
	function getSingle($imageId){
		$this->release();
		
		$sucharray = array();
		$sucharray['schluessel'] = $imageId;
		$this->init($sucharray);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen Link zum nächsten Bild im CunddGalerie-Objekt aus. */
	function nextLink(){
		if($this->pointerToCurrent < count($this->imageArray)-1){
			// Den Link erstellen
			$wert['aufruf']	= 'Cundd'.ucfirst($this->classPrefix).'::next';
			$wert['title']	= CunddLang::get("CunddGalerie_nextLinkTitle");
			$wert['classPrefix'] = $this->classPrefix;
			
			// Link ausgeben
			echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_next_'.$this->classPrefix.'_link', 'output');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Zeiger dieser CunddGalerie-Instanz auf das nächste Bild und gibt 
	 dieses aus. */
	function next($increment = 1){
		// Zeiger verschieben
		$this->pointerToCurrent = $this->pointerToCurrent + $increment;
		$this->save();
		
		// Das Objekt ausgeben
		//$this->show();
		$temp = $GLOBALS['CunddController']->init("CunddGalerie::printDetailOfSelf");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen Link zum vorherigen Bild im CunddGalerie-Objekt aus. */
	function previousLink(){
		if($this->pointerToCurrent > 0){
			// Den Link erstellen
			$wert['aufruf']	= 'Cundd'.ucfirst($this->classPrefix).'::previous';
			$wert['title']	= CunddLang::get("CunddGalerie_previousLinkTitle");
			$wert['classPrefix'] = $this->classPrefix;
			
			// Link ausgeben
			echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_previous_'.$this->classPrefix.'_link', 'output');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Zeiger dieser CunddGalerie-Instanz auf das nächste Bild und gibt 
	 dieses aus. */
	function previous($decrement = 1){
		// Zeiger verschieben
		$this->pointerToCurrent = $this->pointerToCurrent - $decrement;
		if($this->pointerToCurrent < 0){
			$this->pointerToCurrent = 0;
		}
		$this->save();
		
		// Das Objekt ausgeben
		//$this->show();
		$temp = $GLOBALS['CunddController']->init("CunddGalerie::printDetailOfSelf");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen Link zum ersten Bild im CunddGalerie-Objekt aus. */
	function firstLink(){
		if($this->pointerToCurrent > 0){
			// Den Link erstellen
			$wert['aufruf'] = 'Cundd'.ucfirst($this->classPrefix).'::first';
			$wert['title'] = CunddLang::get("CunddGalerie_firstLinkTitle");
			$wert['classPrefix'] = $this->classPrefix;
			
			// Link ausgeben
			echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_first_'.$this->classPrefix.'_link', 'output');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Zeiger dieser CunddGalerie-Instanz auf das erste Bild und gibt 
	 dieses aus. */
	function first(){
		// Zeiger verschieben
		$this->pointerToCurrent = 0;
		$this->save();
		
		// Das Objekt ausgeben
		//$this->show();
		$temp = $GLOBALS['CunddController']->init("CunddGalerie::printDetailOfSelf");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen Link zum letzten Bild im CunddGalerie-Objekt aus. */
	function lastLink(){
		if($this->pointerToCurrent < count($this->imageArray)-1){
			// Den Link erstellen
			$wert['aufruf'] = 'Cundd'.ucfirst($this->classPrefix).'::last';
			$wert['title'] = CunddLang::get("CunddGalerie_lastLinkTitle");
			$wert['classPrefix'] = $this->classPrefix;
			
			// Link ausgeben
			echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_last_'.$this->classPrefix.'_link', 'output');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Zeiger dieser CunddGalerie-Instanz auf das erste Bild und gibt 
	 dieses aus. */
	function last(){
		// Zeiger verschieben
		$this->pointerToCurrent = count($this->imageArray)-1;
		$this->save();
		
		// Das Bild ausgeben
		//$this->show();
		$temp = $GLOBALS['CunddController']->init("CunddGalerie::printDetailOfSelf");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen Link zum einem bestimmten Bild aus. */
	function showImageAtLink($newPointer){
		// Den Link erstellen
		$wert['aufruf'] = 'Cundd'.ucfirst($this->classPrefix).'::showImageAt';
		$wert['title'] = ucfirst($this->classPrefix).' showImageAt';
		$wert['classPrefix'] = $this->classPrefix;
		$wert['data'] = $newPointer;
		
		// Link ausgeben
		echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_image_at_'.$this->classPrefix.'_link', 'output');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Zeiger an die als Parameter übergebene Stelle und gibt das ent-
	 sprechende Bild aus. */
	function showImageAt($newPointer){
		// Zeiger verschieben
		$this->pointerToCurrent = $newPointer;
		$this->save();
		
		// Das Bild ausgeben
		$this->show();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt für jedes Objekt im imageArray einen "showImageAt"-Link. */
	function createShowImageAtLinksFromAToB($indexA = 0, $indexB = NULL){
		/* Wenn $indexB gleich NULL ist wird $indexB auf das letzte Element des Arrays ge-
		 setzt. */
		if(!$indexB){
			$indexB = count($this->imageArray)-1;
		}
		
		for($i = $indexA; $i <= $indexB; $i++){
			$this->showImageAtLink($key);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt für jedes Objekt im imageArray einen "showImageAt"-Link. */
	function createAllShowImageAtLinks(){
		foreach($this->imageArray as $key => $thisImage){
			$this->showImageAtLink($key);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode durchsucht $imageArray nach dem übergebenen Wert/Key-Paar. */
	function getImageWith($value, $key){
		$i = 0;
		
		if(count($this->imageArray) > 1){
			foreach($this->imageArray as $j => $image){
				if($image[$key] == $value){
					$this->pointerToCurrent = $i;
					$this->save();
					
					return true;
					break;
				} else {
					$i++;
				}
			}
		} else {
			$this->pointerToCurrent = 1;
			$this->save();
			return true;
		}
		
		return false;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode durchsucht $imageArray nach dem übergebenen Wert/Key-Paar. */
	function getImageWithId($imageId){
		return $this->getImageWith($imageId, 'schluessel');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt die Links für "next", "previous", "first" und "last". */
	function createAllSiblingLinks(){
		$this->firstLink();
		$this->previousLink();
		$this->nextLink();
		$this->lastLink();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt die Links für "next" und "previous". */
	function createNearestSiblingLinks(){
		$this->previousLink();
		$this->nextLink();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode serialisiert dieses Objekt und speichert es in einer Session-Variabel. */
	function serialize(){
		// Eine Variable für beide Klassen
		// Nicht $_SESSION[$this->className] = serialize($this);
		$_SESSION['CunddGalerie'] = serialize($this);
		
	}
	function save(){
		$this->serialize();
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode versucht die Instanz aus einer Session-Variable zu laden. */
	function loadSession(){
		if($_SESSION['CunddGalerie'] != ""){ // Wenn ja -> Daten übernehmen
			$temp = unserialize($_SESSION['CunddGalerie']);
			$this->overwrite($temp);
			
			if($this->imageArray){
				$fehler = false;
			} else {
				$fehler = true;
			}
		} else {
			$fehler =true;
		}

		if(!$fehler){
			return true;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode erzeugt für jedes Objekt im imageArray einen "stepInto"-Link.
	 * @return void
	 */
	function createAllStepIntoLinks(){
		foreach($this->imageArray as $key => $thisImage){
			$this->stepIntoLink($key);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode wählt zufällig ein Album und erstellt einen "stepInto"-Link.
	 * @param int $width
	 * @param int $height
	 * @return void
	 */
	public function createRandomStepIntoLink($width = NULL, $height = NULL){
		$randomPointer = mt_rand(0, count($this->imageArray));
		$randomPointer = floor($randomPointer);
		
		//$this->stepIntoLink($randomPointer);
		$this->pointerToCurrent = $randomPointer;
		$this->showImagePicture("thumb", $width, $height);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** Die Methode erzeugt einen Link der beim Klick den Inhalt des Albums anzeigt.
	 * @param int $pointerToImage
	 * @return string
	 */
	function stepIntoLink($pointerToImage = NULL){
		if($pointerToImage == NULL){
			$pointerToImage = $this->pointerToCurrent;
		}
		
		$wert['aufruf'] = 'Cundd'.ucfirst($this->classPrefix).'::stepInto';
		//$wert['data']	= 'object = {parentId:\''.$this->imageArray[$pointerToImage]['schluessel'].'\'}';
		$wert['data']	= $this->imageArray[$pointerToImage]['schluessel'];
		$wert['title']	= CunddLang::get("CunddGalerie_stepIntoLinkTitle");
		$wert['classPrefix'] = $this->classPrefix;
		
		// Link ausgeben
		$output = CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_step_into_'.$this->classPrefix.'_link', 'output');
		echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode löscht den Wert der zur Klasse gehörenden Session-Variable und 
	 * initialisiert eine neue Instanz dessen Ausgangspunkt das Bild/Album mit der 
	 * ID = $pointerToImage ist. Somit geht das Programm "in" ein Album hinein und zeigt die 
	 * Elemente, die sich in dem Album befinden.
	 * @param int $imageId
	 * @return void
	 */
	function stepInto($imageId = NULL){
		$say = false;
		
		if($imageId == NULL){
			$imageId = $this->pointerToCurrent;
		}
		
		// In das durch $pointerToImage definierte Album gehen
		$this->release();
		/*
		$tempCunddGalerie = new CunddGalerie($imageId);
		$this->overwriteAndRelease($tempCunddGalerie);
		 */
		$this->init($imageId);
		
		// Die aktuelle Tiefe in der Baumstruktur um 1 erhöhen
		$this->currentDepth = $this->currentDepth + 1;
		$this->save();
		
		// DEBUGGEN
		if($say){
			echo "step into pointerToImage".$imageId;
			echo $this->className;
		}
		
		
		if($this->imageArray){ // Wenn sich Elemente im Album befinden diese anzeigen
			$this->printOverview();
		} else { 
			/* wenn nicht die Details zum gerade betretenen Album abrufen und über-
			prüfen ob das Album ein Bild ist. */
			
			
			// Die Detailinformationen abrufen
			$this->getSingle($imageId);
			
			// Überprüfen ob das aktuelle Album vom Typ "image/" ist
			$type = $this->imageArray[0]['type'];
			$tempType = str_replace("image/","",$type);
			
			if($type == $tempType){ /* Das aktuelle Album ist kein Bild -> die Detail-
				Informationen zum Album werden angezeigt */
				$this->show();
			} else { /* Wenn das aktuelle Album ein Bild ist -> das Bild anzeigen */
				// Über den Controller aufrufen
				//$temp = $GLOBALS['CunddController']->init("CunddGalerie::printSingle");
				
				$this->pointerToCurrent = 0;
				$temp = $GLOBALS['CunddController']->init("CunddGalerie::printDetailOfSelf");
				//$this->printSingle(true);
				/* */
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode zeigt ein einzelnes Bild innerhalb eines Albums. */
	function printSingle($imageOnly = NULL){
		$oldFileId = $this->imageArray[0]['schluessel'];
		$this->pointerToCurrent = 0;
		
		$parentId = $this->getParentId();
		
		// Das Eltern-Album anzeigen
		$tempParentObject = new CunddImages($parentId);
		
		$this->overwriteAndRelease($tempParentObject);
		
		$this->getImageWithId($oldFileId);
		
		if($imageOnly){
			$this->showImagePicture();
		} else {
			$this->show();
		}
		
		return $tempParentObject;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt das Elternelement des aktuellen Albums. */
	function getParentId(){
		$say = false;
		
		// Die ID des aktuellen Albums ermitteln
		$initSuchArray = array();
		$initSuchArray['schluessel'] = $this->fileId;
		
		// Repräsentation des aktuellen Albums
		$tempObjectRepresentingThisAlbum = new CunddAlbum($initSuchArray);
		$parentId = $tempObjectRepresentingThisAlbum->imageArray[0]['parent'];
		
		// DEBUGGEN
		if($say){
			echo '$this->fileId = '.$this->fileId.'<br />';
			CunddTools::pd($initSuchArray);
			
		}
		// DEBUGGEN
		
		return $parentId;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode geht eine Ebene höher und zeigt alle Elemente des Elternelements des 
	 aktuellen Albums (= die Siblings des aktuellen Albums). Dazu wird diese Instanz mit der
	 Eltern-Instanz überschrieben. */
	function stepOut(){
		$parentId = $this->getParentId();
		
		// Das Eltern-Album anzeigen
		$tempParentObject = new CunddAlbum($parentId);
		
		$this->overwriteAndRelease($tempParentObject);
		
		$this->printOverview();
		
		// Die aktuelle Tiefe in der Baumstruktur um 1 verringern
		$this->currentDepth = $this->currentDepth - 1;
		
		return $tempParentObject;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt einen Link der beim Klick den Inhalt des Eltern-Albums zeigt. */
	function stepOutLink(){
		$wert['aufruf'] = 'Cundd'.ucfirst($this->classPrefix).'::stepOut';
		$wert['title']	= CunddLang::get("CunddGalerie_stepOutLinkTitle");
		//'stepOut '.ucfirst($this->classPrefix).' '.$this->imageArray[$pointerToImage]['schluessel'];
		
		$wert['classPrefix'] = $this->classPrefix;
		
		// Link ausgeben
		echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_step_out_'.$this->classPrefix.'_link', 'output');
	}
	

	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt das komplette Array in einer Übersicht aus. */
	function printOverview(){
		
		$this->debug();
		foreach($this->imageArray as $key => $currentImage){
			// Das einzelne Bild in einen div-Einschließen
			echo CunddTemplate::inhalte_einrichten($currentImage, 4, 'CunddGalerie_show_preview_begin', 'output');
			
			echo CunddTemplate::inhalte_einrichten($currentImage, 4, 'CunddGalerie_show_preview_information_begin', 'output');
			
			$this->pointerToCurrent = $key;
			$this->showImageWithoutPicture();
			
			echo CunddTemplate::inhalte_einrichten($currentImage, 4, 'CunddGalerie_show_preview_information_end', 'output');
			
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			/* Wenn das aktuelle Element ein Bild ist -> das Bild anzeigen; sonst das erste Bild des 
			 * Albums ermitteln und dieses anzeigen */
			
			// if($currentImage['type'] == 'cundd/group'){
			if(!$currentImage['dateiname']){
				$firstImageInAlbum = CunddGalerie::get_first_item_in_album($currentImage['schluessel']);
				$currentImage['dateiname'] = $firstImageInAlbum['dateiname'];
				$currentImage['attribute'] = $firstImageInAlbum['attribute'];
			}
			/* */
			// Das Array erstellen das an CunddTemplate übergeben wird
			$currentImagesAttributes = self::getAttributesFromString($currentImage['attribute']);
			$forceRemotePath = $currentImagesAttributes['forceRemotePath'];
		
			$currentImage[$this->classPrefix.'_show_'.$this->classPrefix] = CunddFiles::get_real_path($currentImage['dateiname'], "thumb", $forceRemotePath); // Pfad des Bildes
			
			$currentImage['className'] = $this->className;
			
			$currentImage['aufruf'] = $this->className.'::stepInto';
			$currentImage['data'] = $currentImage['schluessel'];
			$currentImage['currentDepth'] = 'depth'.$this->currentDepth;
			
			// Die Größe des Bildes ermitteln
			if($maxWidth){
				$currentImage['maxWidth'] = $maxWidth;
			} else {
				$currentImage['maxWidth'] = CunddConfig::get('CunddGalerie_max_preview_image_width');
			}
			
			if($maxHeight){
				$currentImage['maxHeight'] = $maxHeight;
			} else {
				$currentImage['maxHeight'] = CunddConfig::get('CunddGalerie_max_preview_image_height');
			}
			
			echo CunddTemplate::inhalte_einrichten($currentImage, 4, $this->classPrefix.'_show_'.$this->classPrefix, 'output');
			
			
			/*
			} else {
				$thumbnailsOnly = TRUE;
				CunddGalerie::print_first_item_in_album($currentImage['schluessel'], $thumbnailsOnly);
			}
			/* */
			
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			
			// Das einzelne Bild in einen div-Einschließen
			echo CunddTemplate::inhalte_einrichten($currentImage, 4, 'CunddGalerie_show_preview_end', 'output');
		}
	}


	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die Detail-Informationen eines Bildes. */
	function getDetail($imageId){
		/* Wenn das aktuelle Album ein Bild ist diese Instanz löschen und eine 
		 neue Instanz von CunddImages erstellen und ein passendes Array zur 
		 Ausgabe eines Einzelnen Bildes übergeben. */
		$initSuchArray = array();
		$initSuchArray['schluessel'] = $imageId;
		
		$newCunddImages = new CunddImages($initSuchArray);
		
		// Diese Instanz löschen
		$this->release();
		return $newCunddImages;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode zeigt die Detail-Informationen eines Bildes dessen File-Id als Parameter
	 übergeben wird. */
	function printDetail($imageId){
		$this->getSingle($imageId);
		$this->printDetailOfSelf();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Detail-Informationen eines Bildes aus. */
	function printDetailOfSelf($imageOnly = NULL){		
		// Das Bild dessen Details angezeigt werden sollen
		$currentImage = $this->imageArray[$this->pointerToCurrent];
		
		
		// Eine neue CunddImages-Instanz erstellen
		// TODO: den Pointer merken
		$imageId = $currentImage['schluessel'];
		
		$tempCunddImages = new CunddImages($currentImage['parent']);
		$this->overwriteAndRelease($tempCunddImages);
		
		$this->getImageWithId($imageId);
		
		//$currentImage = $this->imageArray[0];
		
		/* */
		
		// DEBUGGEN
		if($say OR $this->debug){
			CunddTools::pd($this);
		}
		// DEBUGGEN
		
		// Das Bild anzeigen
		// Einen div um das Bild und dessen Informationen legen
		$wert = $this->imageArray[$this->pointerToCurrent]['schluessel'];
		echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_show_'.$this->classPrefix.'_container', 'output');
		
		// Das Array erstellen das an CunddTemplate übergeben wird
		$currentImagesAttributes = self::getAttributesFromString($currentImage['attribute']);
		$forceRemotePath = $currentImagesAttributes['forceRemotePath'];
		
		$tag = $this->classPrefix.'_show_'.$this->classPrefix.'_detail';
		$currentImage[$tag] = CunddFiles::get_real_path($currentImage['dateiname'], NULL, $forceRemotePath); // Pfad des Bildes
		$currentImage['className'] = $this->className;
		
		// Die Größe des Bildes ermitteln
		if($maxWidth){
			$currentImage['maxWidth'] = $maxWidth;
		} else {
			$currentImage['maxWidth'] = CunddConfig::get('CunddGalerie_max_detail_image_width');
		}
		
		if($maxHeight){
			$currentImage['maxHeight'] = $maxHeight;
		} else {
			$currentImage['maxHeight'] = CunddConfig::get('CunddGalerie_max_detail_image_height');
		} 
		
		echo CunddTemplate::inhalte_einrichten($currentImage, 4, $tag, 'output');
		
		// Die Zusatzinformationen anzeigen wenn $imageOnly nicht gesetzt ist
		if(!$imageOnly){
			$this->showImageWithoutPicture();
		}
		
		// Den div schließen
		echo CunddTemplate::inhalte_einrichten(NULL, 4, 'universal_close_div', 'output');
		/* */
		
		return true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt einen Link der beim Klick die Detailinformationen zum Bild an-
	 zeigt. */
	function printDetailLink($pointerToImage = NULL){		
		if($pointerToImage == NULL){
			$pointerToImage = $this->pointerToCurrent;
		}
		
		$wert['aufruf'] = 'Cundd'.ucfirst($this->classPrefix).'::printDetail';
		//$wert['data']	= 'object = {parentId:\''.$this->imageArray[$pointerToImage]['schluessel'].'\'}';
		$wert['data']	= $this->imageArray[$pointerToImage]['schluessel'];
		$wert['title']	= 'printDetail '.ucfirst($this->classPrefix).' '.$this->imageArray[$pointerToImage]['schluessel'];
		$wert['classPrefix'] = $this->classPrefix;
		
		// Link ausgeben
		echo CunddTemplate::inhalte_einrichten($wert, 4, $this->classPrefix.'_print_detail_'.$this->classPrefix.'_link', 'output');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erzeugt für jedes Objekt im imageArray einen "stepInto"-Link. */
	function createAllPrintDetailLinks(){
		foreach($this->imageArray as $key => $thisImage){
			$this->printDetailLink($key);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt verschiedene Daten der Instanz im Firebug-Fenster aus. */
	function debug(){
		if($this->debug){
			$i = 0;
			foreach($this->imageArray as $key => $thisImage){
				echo '<script type="text/javascript">
				console.debug("# '.$i.'	title='.$thisImage['title'].'			beschreibung='.$thisImage['beschreibung'].'					originalname='.$thisImage['originalname'].'");
				console.debug("pointerToCurrent='.$this->pointerToCurrent.'");
				</script>
				';
				$i++;
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode skaliert das gegebene Bild und speichert es temporär. */
	function scale_image_for_save_with_temp_copy($filename, $filedata){
		$newImageArray = CunddGalerie::scale_image_for_save($filename, $filedata);
		$saved = 1;
		
		
		foreach($newImageArray as $key => &$image){
			// Das Bild temporär speichern
			$tempFilename = $filedata['files_dateiname'];
			$uploadTempPath = CunddFiles::get_real_path($tempFilename,'upload_temp_'.$key);
			CunddTools::log('CunddGalerie','scale_image_for_save_with_temp_copy $uploadTempPath:'.$uploadTempPath);
			echo $image;
			
			
			
			// Das Bild im Original-Datei-Format speichern
			switch($filedata['files_type']) {
				case 'image/jpeg':
				case 'image/pjpeg': //wegen IE
					$saved *= imagejpeg($image, $uploadTempPath);
					break;
				case 'image/png':
					$saved *= imagepng($image, $uploadTempPath);
					break;
				case 'image/gif':
					$saved *= imagegif($image, $uploadTempPath);
					break;
				default:
					$saved *= imagepng($image, $uploadTempPath);
					CunddTools::log('CunddGalerie','Saved image as PNG.');
			}
			
			// Das Bild dauerhaft speichern
			$uploadPath = CunddFiles::get_real_path($filedata['files_dateiname'],'upload_'.$key);
			$saved *= CunddFiles::save_file_to_filesystem($uploadTempPath, $uploadPath);
			
			
		}
		
		return $saved;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode skaliert das gegebene Bild zu einer in der Konfiguration definierten 
	 * Größe. Dies ist die Methode zum Erstellen der Thumbnails beim Hochladen. Die Methode 
	 * ruft die Methode scale_image() auf.
	 */
	function scale_image_for_save($filename, $filedata){
		$say = true;
		
		// Die Detail-Maße auslesen
		if(CunddConfig::get('maxDetailWidth')){
			$essentialSideLength = CunddConfig::get('maxDetailWidth');
			$scaleMode = 'maxLength';
			
		} else if(CunddConfig::get('maxDetailHeight')){
			$essentialSideLength = CunddConfig::get('maxDetailHeight');
			$scaleMode = 'maxLength';
			
		} else if(CunddConfig::get('shortSideDetailMinWidth')){
			$essentialSideLength = CunddConfig::get('shortSideDetailMinWidth');
			$scaleMode = 'shortSideMin';
			
		} else if(CunddConfig::get('shortSideDetailMinHeight')){
			$essentialSideLength = CunddConfig::get('shortSideDetailMinHeight');
			$scaleMode = 'shortSideMin';
			
		} else {
			// dont scale detail-image
		}
		
		if($essentialSideLength){
			$result = 'detail has changed';
			$detail = CunddGalerie::scale_image($filename, $filedata, $essentialSideLength, $scaleMode);
		} else {
			$result = 'detail is original';
			$detail = 'original';
		}
		
		
		// Die Thumbnail-Maße auslesen
		if(CunddConfig::get('maxThumbnailWidth')){
			$essentialSideLength = CunddConfig::get('maxThumbnailWidth');
			$scaleMode = 'maxLength';
			
		} else if(CunddConfig::get('maxThumbnailHeight')){
			$essentialSideLength = CunddConfig::get('maxThumbnailHeight');
			$scaleMode = 'maxLength';
			
		} else if(CunddConfig::get('shortSideThumbnailMinWidth')){
			$essentialSideLength = CunddConfig::get('shortSideThumbnailMinWidth');
			$scaleMode = 'shortSideMin';
			
		} else if(CunddConfig::get('shortSideDThumbnailMinHeight')){
			$essentialSideLength = CunddConfig::get('shortSideDThumbnailMinHeight');
			$scaleMode = 'shortSideMin';
			
		} else {
			// dont scale thumbnail-image
		}
		
		if($essentialSideLength){
			$result .= 'thumbnail has changed';
			$thumbnail = CunddGalerie::scale_image($filename, $filedata, $essentialSideLength, $scaleMode);
		} else {
			$result .= 'thumbnail is original';
			$thumbnail = 'original';
		}
		
		
		
		// Das Original-Bild einlesen
		switch($filedata['files_type']) {
			case 'image/jpeg':
			case 'image/pjpeg': //wegen IE
				if($say) echo 'jpeg';
				$original = imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				if($say) echo 'png';
				$original = imagecreatefrompng($filename);
				break;
			case 'image/gif':
				if($say) echo 'gif';
				$original = imagecreatefromgif($filename);
				break;
			default:
				if($say) echo 'Couldn\'t detect image type';
				CunddTools::log('CunddGalerie','Could not detect mime-type of new file. Mime-type detected as:'.$filedata['type'].'.');
		}
			
			
		if($detail == 'original'){
			$detail = $original;
		}
		if($thumbnail == 'original'){
			$thumbnail = $original;
		}
		
		
		$newImageArray = array();
		$newImageArray['original'] = $original;
		$newImageArray['thumbnail'] = $thumbnail;
		$newImageArray['detail'] = $detail;
		
		//if($say) echo $result; echo 'g'.$detail.'g'; echo 'b'.$thumbnail.'b';
		
		return $newImageArray;
	}
		
		
		
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode skaliert das gegebene Bild zu einer per Parameter definierten Größe. */
	function scale_image($filename, $filedata, $essentialSideLength, $scaleMode){
		$say = true;
		
		switch($filedata['files_type']) {
			case 'image/jpeg':
			case 'image/pjpeg': //wegen IE
				if($say) echo 'jpeg';
				$original = imagecreatefromjpeg($filename);
				break;
			case 'image/png':
				if($say) echo 'png';
				$original = imagecreatefrompng($filename);
				break;
			case 'image/gif':
				if($say) echo 'gif';
				$original = imagecreatefromgif($filename);
				break;
			default:
				if($say) echo 'Couldn\'t detect image type';
				CunddTools::log('CunddGalerie','Could not detect mime-type of new file. Mime-type detected as:'.$filedata['type'].'.');
		}
		
		
		
		if($original) {
			$originalWidth = imagesx($original);
			$originalHeight = imagesy($original);
			
			// Seitenverhältnis bestimmen
			$aspectRatio = $originalWidth/$originalHeight;
			
			// Neue Seitenmaße bestimmen
			switch($scaleMode) {
				case 'maxLength':
					if($originalWidth > $originalHeight){
						$newWidth = $essentialSideLength;
						$newHeight = $essentialSideLength / $aspectRatio;
					} else {
						$newWidth = $essentialSideLength * $aspectRatio;
						$newHeight = $essentialSideLength;
					}
					break;
					
				case 'shortSideMin':
					if($originalWidth < $originalHeight){
						$newWidth = $essentialSideLength;
						$newHeight = $essentialSideLength / $aspectRatio;
					} else {
						$newWidth = $essentialSideLength * $aspectRatio;
						$newHeight = $essentialSideLength;
					}
					
			}
			
			
			//Erstellen der Bühne
			$newStage = imagecreatetruecolor($newWidth, $newHeight);
			//$color = imagecolorallocate($newStage, 255, 0, 0); //Eine Farbe definieren die in Transparenz umgewandelt werden soll
			//imagefill($newStage,0,0,$color); //Hintergrund mit dieser Farbe füllen
			
			//Bild skalieren und auf Bühne aufbringen
			if(imagecopyresampled($newStage, $original, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight)){
				// Bild wurde skaliert
			} else {
				CunddTools::log('CunddGalerie','Cound not resample image');
				die('Cound not resample image');
			}
			
			
			/*
			$neu = imagepng($bilder_neu[0],$pfad.'/'.$filename);
			
			$bilder_neu = array();
			$bilder_neu[] = $neu;
			$bilder_neu[] = $thumbnail;
			*/
			return $newStage;
		} else {
			die('<h1>FEHLER kein Bild geladen</h1>');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt das erste Bild innerhalb eines Albums und gibt diese zurück. */
	static function get_first_item_in_album($imageId = NULL){
		/*$sucharray = array();
		$sucharray['schluessel'] = $imageId;
		
		$firstItemInAlbum = new CunddGalerie($sucharray);
		
		return $firstItemInAlbum; */
		
		$tempGalerieObject = new CunddGalerie($imageId);
		$firstItemInAlbum = $tempGalerieObject->imageArray[0];
		$tempGalerieObject->release();
		
		return $firstItemInAlbum;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt das erste Bild innerhalb eines Albums aus. */
	static function print_first_item_in_album($imageId = NULL, $thumbnailsOnly = NULL){
		$say = false;
		
		// $firstItemInAlbum = &CunddGalerie::get_first_item_in_album($imageId);
		
		$firstItemInAlbum = new CunddGalerie($imageId);
		$firstItemInAlbum->show($thumbnailsOnly);
		$firstItemInAlbum->release();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt das erste Bild innerhalb des aktuellen Albums oder des per Parameter 
	 * übergebenen aus. */
	static function printFirstItemInAlbum($imageId = NULL, $thumbnailsOnly = NULL){
		if(!$imageId){
			$imageId = $this->pointerToCurrent;
		}
		
		CunddGalerie::print_first_item_in_album($imageId, $thumbnailsOnly);
	}
}
}//END OF CLASS_EXISTS
?>