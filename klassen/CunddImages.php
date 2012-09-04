<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddImages" bietet verschiedene Methoden zur Handhabung von Bildern, wie 
 zum Beispiel Methoden zur Bildbearbeitung und -Ausgabe. */
class CunddImages extends CunddGalerie {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	/*
	var $version = 0.1;
	var $pointerToCurrent = 1; // Die Variable speichert den Index des aktuellen Bildes
	var $imageArray = array(); // Die Variable speichert die Daten aller gelesenen Bilder
	/* */
	var $classPrefix = 'images'; /* Speichert den Prefix der Klasse; dieser wird beispiels-
								  weise für die Definition der CSS-Class verwendet */
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	/* Der Konstruktor überprüft zuerst ob $_SESSION eine Instanz von CunddImages enthält 
	 und liest diese ein, wenn nicht, wird eine neue Instanz erstellt. */
	function CunddImages($parameter = NULL){
		$this->init($parameter);
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode überprüft zuerst ob $_SESSION eine Instanz von CunddGalerie enthält 
	 und liest diese ein, wenn nicht, wird eine neue Instanz erstellt. */
	function init($parameter = NULL){
		$say = false;
		
		// DEBUGGEN
		if($say){
			echo '<h1>init';
			CunddTools::pd($parameter);
			echo '</h1>';
		}
		// DEBUGGEN
		
		$this->className = get_class($this);
		
		if($parameter){ // Überprüfen ob Parameter übergeben wurde
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
			} else {
				echo '<h1>NO FILEID</h1>';
			}
			
			$sucharray['search'] = $suchanfrage;
			$sucharray['type'] = 'image/';
			$sucharray['output'] = "return";
			
			// DEBUGGEN
			if($say){
				echo '$sucharray = ';
				var_dump($sucharray);
			}
			// DEBUGGEN
			
			$this->imageArray = CunddFiles::get($sucharray);
			
			$this->pointerToCurrent = $sucharray['pointerToCurrent'];
			
			/* wenn $parameter = NULL ist -> überprüfen ob $_SESSION eine Instanz 
			 von CunddGalerie enthält sonst eine neue Instanz ohne Parameter 
			 erstellen. */
		} else if(!$this->loadSession()){
			$this->imageArray = CunddFiles::getOfType(NULL,'image/%');
			
			$this->pointerToCurrent = $sucharray['pointerToCurrent'];
		}
		
		// DEBUGGEN
		if($say){
			echo 'parameter = '.$parameter;
			CunddTools::pd($this);
		}
		// DEBUGGEN --------------------------------------
		
		
		// In $_SESSION speichern
		$this->save();
	}
	






}
?>