<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddAlbum" bietet verschiedene Methoden zur Handhabung von Bilder-
 Gruppen (Alben). */
class CunddAlbum extends CunddGalerie {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren	
	var $classPrefix = 'album'; /* Speichert den Prefix der Klasse; dieser wird beispiels-
								weise für die Definition der CSS-Class verwendet */
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	/* Der Konstruktor überprüft zuerst ob $_SESSION eine Instanz von CunddImages enthält 
	 und liest diese ein, wenn nicht, wird eine neue Instanz erstellt. */
	function CunddAlbum($parameter = NULL){
		$this->init($parameter);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode überprüft zuerst ob $_SESSION eine Instanz von CunddGalerie enthält 
	 und liest diese ein, wenn nicht, wird eine neue Instanz erstellt. */
/*	function init($parameter = NULL){
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
								   übergeben werden */
/*			if(gettype($parameter) != "array"){
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
			$sucharray['type'] = 'cundd/group';
			$sucharray['output'] = "return";
			
			// DEBUGGEN
			if($say){
				echo '$sucharray = ';
				var_dump($sucharray);
			}
			// DEBUGGEN
			
			$this->imageArray = CunddFiles::get($sucharray);
			
			$this->pointerToCurrent = 0;
			
			/* wenn $parameter = NULL ist -> überprüfen ob $_SESSION eine Instanz 
			 von CunddGalerie enthält sonst eine neue Instanz ohne Parameter 
			 erstellen. */
/*		} else if(!$this->loadSession()){
			$this->imageArray = CunddFiles::getOfType(NULL,'cundd/group');
			
			$this->pointerToCurrent = 0;
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
	
	
	
	/* */
	
}
?>