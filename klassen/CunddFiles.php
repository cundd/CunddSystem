<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddFiles" bietet verschiedene Methoden zum handhaben von Dateien, wie 
 zum Beispiel Up- und Downloads. */
/* WICHTIG: Das Attribut "name" im Input-Tag für die Datei muss "userfile" + eine Nummer 
 bei Mehrfach-Uploads lauten. */
class CunddFiles extends CunddAttribute {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $version = 0.1;
	var $allowed_types = array("image/*","audio/*","video/*","text/css","text/html","text/rtf");
	
	private static $className = 'CunddFiles';
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	/**
	 * Der Konstruktor überprüft ob eine Datei verarbeitet, oder das Formular
	 * zum Datei-Upload angezeigt werden soll.
	 */
	function CunddFiles(){
		$say = false;
		
		// Überprüfen ob die benötigten Verzeichnisse schreibbar sind
		$pathError = false;
		if(!CunddPath::checkIfUploadDirIsWritable()){
			$pathError = 'upload';
		} else if(!CunddPath::checkIfThumbnailDirIsWritable()){
			$pathError = 'thumbnail';
		} else if(!CunddPath::checkIfOriginalDirIsWritable()){
			$pathError = 'original';
		}
		if($pathError){
			$msg = "The $pathError directory is not writable.";
			die($msg);
		}
		
		
		
		// DEBUGGEN
		if($say){
			CunddTools::log("SESSION");
			echo '<pre>'; echo var_dump($_POST); echo '</pre>';
			echo '<pre>'; echo var_dump($_FILES); echo '</pre>';
		}
		
		/* überprüfen ob eine, mehrere, oder eine bereits bestehende Datei verarbeitet werden 
		 * sollen oder das Input-Formular angezeigt werden soll. */
		if($_POST['old_file_id'] AND $_POST['daten_bearbeitet'] == 'edit'){
			if($say) echo 'Edit file';
			
			$input =& $_POST;
			$edited = $this->edit($input);
			
			// Das Ergebnis ausgeben
			if($edited){
				echo CunddTemplate::inhalte_einrichten($wert, NULL, "files_old_file_success", "output");
			} else {
				echo CunddTemplate::inhalte_einrichten($wert, NULL, "files_old_file_error", "output");
			}
		} else if($_FILES["userfile0"] OR $_FILES["userfile1"]){ // Mehrere Files verarbeiten
			if($say) echo 'Multiple files ';
			// Für jedes gesendetet File
			for($i = 0; $i < count($_FILES); $i++){
				$filename = "userfile".$i;
				
				// Den Datentyp prüfen
				if($this->check_type($filename)){
					/* Wenn der File-Type erlaubt ist werden die Daten aus $_GET in $_POST 
					 kopiert. */
					$this->sync_global();
					$resultat = $this->save_file($filename);
				}
			}
		} else if($_FILES["userfile"]){ // Ein File verarbeiten
			if($say) echo 'Single file ';
			$filename = "userfile";
			
			// Den Datentyp prüfen
			if($this->check_type($filename)){
				/* Wenn der File-Type erlaubt ist werden die Daten aus $_GET in $_POST 
				 kopiert. */
				$this->sync_global();
				$resultat = $this->save_file($filename);
			}
		} else if($_POST["type"] == "group"){ /* Die Instanz ist im "group"-Modus -> eine 
												neue Gruppe soll erstellt werden. */
			if($say) echo 'New group ';
		
			$this->sync_global();
			$resultat = $this->save_file($filename);
		} else { // Das Input-Formular anzeigen
			if($say) echo 'Display input-form ';
			CunddTemplate::file_input_form("files");
		}
		
		// Ergebnis-Meldung ausgeben
		if($resultat == 1){
			echo CunddTemplate::inhalte_einrichten($wert, NULL, "files_success", "output");
		}
		
		
		/*
		$tmpfile = tempnam("dummy","");
		$path = dirname($tmpfile);
		echo $path;
		
		echo '<pre>'; echo var_dump($_POST); echo '</pre>';
		echo '<pre>'; echo var_dump($_FILES); echo '</pre>';
		echo $_FILES[$path.'/userfile']['size'];
		echo $_FILES[$path.'/userfile']['tmp_name'];
		$x = $_FILES['userfile']['tmp_name'];
		echo $x;
		$filename = basename($_FILES['userfile']['name']);
		echo $_FILES['file'.$file_nummer]['tmp_name'];
		echo $filename."R";
		//$filename = dateiname($filename,'png');
		
		$uploaddir = '/Users/daniel/Desktop';
		$uploadfile = $uploaddir. $_FILES['userfile']['name'];
		
		
		
		$tmpfile = tempnam("dummy","");
		$path = dirname($tmpfile);
		echo $path;
		unlink($tmpfile);
		 */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode bietet verschiedene Möglichkeiten zum Abrufen von Informationen. Wird als
	 * Parameter "%" übergeben, liest die Methode alle Dateien aus der Files-Tabelle. Wird ein
	 * Begriff als Parameter übergeben werden alle Felder der Tabelle nach diesem durchsucht,
	 * wird ein Array übergeben, wird eine Detailsuche initiiert.
	 * @param array|string $search
	 * @param Boolean $hideDeleted
	 * @param string $outputPara
	 * @return array
	 */
	function get($search, $hideDeleted = true, $outputPara = NULL){
		$say = false;
		$sayAnfrage = false;

		$file_infos = array();
		
		$defaultOutput = "print"; // Definiert die Standardausgabe wenn keine übergeben wurde
		
		$felder = CunddFelder::get_files();
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
					  CunddConfig::get('mysql_passwort'));
		
		
		
		// Überprüfen ob nur ein Suchbegriff oder ein Sucharray übergeben wurde
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		if(gettype($search) != "array"){ // Einfache Suche
			// Beginn der Anfrage
			$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."files` WHERE ";
			
			// Suche für jedes Feld in der Tabelle
			for($i = 0; $i < count($felder); $i++){
				$anfrage .= $felder[$i]["name"]." LIKE '%".$search."%' AND ";
			}
			
			// Gelüschte Dateien nicht anzeigen
			if($hideDeleted){
				$anfrage .= "geloescht='0000-00-00' AND ";
			}
			
			// Suche beenden
			$anfrage .= "schluessel LIKE '%';";
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		} else if(gettype($search) == "array"){ // Erweiterte Suche
			
			// Beginn der Anfrage
			$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."files` WHERE ";
			
			// Suche für jedes Feld in der Tabelle
			for($i = 0; $i < count($felder); $i++){
				$aktuellerFeldname = $felder[$i]["name"];
				
				// Überprüfen ob das aktuelle Feld "parent" ist, wenn ja genaue Suche
				if($search[$felder[$i]["name"]] AND $aktuellerFeldname == 'parent'){
					$anfrage .= $aktuellerFeldname." LIKE '".$search[$aktuellerFeldname]."' AND ";
				} else if($search[$aktuellerFeldname]){
					if($say) echo '<br />Not Skipped '.$aktuellerFeldname.'<br />';
					$anfrage .= $aktuellerFeldname." LIKE '%".$search[$aktuellerFeldname]."%' AND ";
				} else if($say){
					echo '<br />Skipped '.$aktuellerFeldname.'<br />';
					//CunddTools::pd($search);
				}
			}
			
		
			// Gelüschte Dateien nicht anzeigen
			if($hideDeleted){
				$anfrage .= "geloescht='0000-00-00' AND ";
			}
			
			// Suche beenden
			if($search['schluessel']){
				$anfrage .= "schluessel LIKE '".$search['schluessel']."';";
			} else {
				$anfrage .= "schluessel LIKE '%';";
			}
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW	
		} else { // Die Methode wurde unerlaubt aufgerufen
			CunddTools::log_fehler("CunddFiles::get","Type of $search-variable not allowed");
		}
		
		// Anfrage senden
		$resultat = mysql_query($anfrage);
		
		// Die Informationen aus dem Byte-Array auslesen und in einem Array speichern
		$i = 0;
		while($aktuelle_file_infos = mysql_fetch_array($resultat)){
			// Die Rechte überprüfen
			if(CunddRechte::get($aktuelle_file_infos)){
				$file_infos[$i] = $aktuelle_file_infos;
				$i++;
			}
		}
		
		// DEBUGGEN
		if($say){
			echo 'mysql_anfrage = '.$anfrage.'<br />';
			echo 'mysql_result = '.$resultat.'<br />';
			/*echo '<pre>FELDER: ';
			var_dump($felder);
			echo '</pre>'; /* */
			echo '<pre>FILE INFOS: ';
			var_dump($file_infos);
			echo '</pre>';
			echo '<pre>SEARCH: ';
			var_dump($search);
			echo '</pre>';
		}
		if($sayAnfrage){
			echo 'mysql_anfrage = '.$anfrage.'<br />';
		}
		// DEBUGGEN --------------------------------------
		
		
		// Ausgabe des Ergebnis
		// Art der Ausgabe ermitteln
			if(gettype($search) == "array"){
				if($search['output']){
					$outputType = $search['output'];
				} else {
					$outputType = $defaultOutput;
				}
			} else if($outputPara){
				$outputType = $outputPara;
			} else {
				$outputType = $defaultOutput;
			}
		
		// Ausgeben
		if($outputType == "print"){
			// Ergebnis in Form einer Tabelle ausgeben
			$j = 0;
			while($felder[$j]){
				$table_cols[$j] = $felder[$j]["name"];
				$j++;
			}
			
			$table_name = "Files";
			$kopf_zeile = true;
			CunddTemplate::show_table($file_infos,$table_name,$table_cols,$kopf_zeile);
		}
		
		// Das Ergebnis wird standardmäßig zurückgegeben
		return $file_infos;
	}

	

	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Static version of get()
	 * @see get()
	 * @param array|string $search
	 * @param Boolean $hideDeleted
	 * @param string $outputPara
	 * @return array
	 */
	public static function sGet($search = NULL, $hideDeleted = true, $outputPara = NULL){
	    if(!$search)    $search = "";
	    if(!$outputPara)$outputPara = "output";
	    return self::get($search, $hideDeleted, $outputPara);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode löscht eine Datei im System indem sie ein Datum für das MySQL-Feld 
	 * "geloescht" angibt. */
	function delete($fileId){
		$eingabe['old_file_id'] = $fileId;
		$eingabe['files_geloescht'] = date('Y-m-d');
		$resultat = CunddFiles::edit($eingabe);
		
		if($resultat){
			// TODO: CunddFiles::delete_from_filesystem()
			// $resultat = CunddFiles::delete_from_filesystem();
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die eine zu einer Datei gehörenden Attribute und gibt sie in einem
	 * assoziativen Array zurück. */
	public static function getAttributesOfFile($fileId){
		// Die Daten auslesen
		$sucharray['schluessel'] = $fileId;
		$sucharray['output'] = 'return';
		$file = CunddFiles::get($sucharray);
		
		
		// Die Daten parsen
		$parsedAttributes = array();
		
		
		$allAttributes = $file[0]['attribute'];
		$allAttributesInArray = explode(';',$allAttributes);
		
		foreach($allAttributesInArray as $key => $currentAttribute){
			$currentArributePair = explode('=',$currentAttribute);
			$parsedAttributes[$currentArributePair[0]] = $currentArributePair[1]; 
		}
		
		return $parsedAttributes;
	}

	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode berarbeitet die Daten eines Files in der MySQL-Tabelle. */
	function edit($eingabe){
		$say = false;
		$allowAnonymEdit = true;
		
		// überprüfen "wer" die Methode aufgerufen hat.
		if($_SESSION["hauptgruppe"]){ // Ein eingeloggter Benutzer
			$eingabe["bearbeiter"] = $_SESSION["benutzer"];
			$ersteller_gruppe = $_SESSION["hauptgruppe"];
		} else if($allowAnonymEdit){
			$eingabe["bearbeiter"] = 'oeffentlich';
			$ersteller_gruppe = CunddGruppen::get_id('oeffentlich');
		} else { // Ein unbekannter Nutzer hat das Skript aufgerufen. Es wird deshalb nicht ausgeführt
			echo "Nicht berechtigt";
			$eingabe["ersteller"] = NULL;
			$ersteller_gruppe = NULL;
		}
		
		
		if($ersteller_gruppe){
			/* Wenn alle Gruppen-Checkboxen deaktiviert sind wird der Wert von $eingabe["gruppen"] 
			auf den Wert der gewählten Hauptgruppe gesetzt. */
			if(!$eingabe["gruppen"][0]){
				/* Die Werte aller Checkboxen mit dem Namen "gruppen[]" werden in einem Array übergeben. 
				Alle Werte der Elemente werden miteinander addiert. */
				for($i = 0; $i < count($eingabe["gruppen"]); $i++){
					$gruppen += $eingabe["gruppen"][$i];
				}
				// $eingabe["gruppen"] mit der Summe überschreiben 
				$eingabe["gruppen"] = $gruppen;
			} else { // $eingabe["gruppen"] mit 2^Hauptgruppe überschreiben
				$eingabe["gruppen"] = pow(2,$eingabe["hauptgruppe"]);
			}
			
						
			
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			
			// Bearbeiter ausfüllen
			$eingabe["bearbeitungsdatum"] = date("Y-m-d");
			$eingabe["bearbeiter"] = $_SESSION["benutzer"].'+'.$eingabe["bearbeiter"];
			
			
			// Die BenutzerInfoFelder-Sichtbarkeit für die angegebene Gruppe auslesen
			$felder = CunddFelder::get_files();
			
			
			mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
				CunddConfig::get('mysql_passwort'));
		
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"files` SET ";
			// Jedes Element aus $felder an die MySQL-Anfrage anhüngen
			if($felder){
				$name_des_elements = $felder[0]['name'];
				$wert = $eingabe['files_'.$name_des_elements];
				if($wert){
					$anfrage .= '`'.$name_des_elements.'`=';
					
					// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
					if(is_string($wert)){
						$anfrage .= "'".$wert."' ";
					} else {
						$anfrage .= $wert." ";
					}
				} else {
					$anfrage .= " title = CONCAT(title,'')";
				}
			}
			for($i = 1; $i < count($felder); $i++){
				/* Die Reihnfolge wird durch die Elemente in $felder bestimmt. Die entsprechenden 
				Daten werden aus dem assoziativen Array $eingabe abgerufen. */
				$name_des_elements = $felder[$i]['name'];
				$wert = $eingabe['files_'.$name_des_elements];
				if($wert){
					$anfrage .= ', `'.$name_des_elements.'`=';
					
					// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
					if(is_string($wert)){
						$anfrage .= "'".$wert."' ";
					} else {
						$anfrage .= $wert." ";
					}
				}
			}
			
			$anfrage .= " WHERE schluessel='".$eingabe["old_file_id"]."';";
			
			// DEBUGGEN
			if($say){
				echo '$anfrage='.$anfrage.'<br />';
				echo '$resultat='.$resultat.'<br />';
				CunddTools::log("CunddFiles","Edited old file with query:".$anfrage);
			}
			// DEBUGGEN------------------------------------------
			
			
			$resultat = mysql_query($anfrage);
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode bildet ein Alias für die Suche in der File-Datenbank nach einer Datei die 
	 dem angegebenen Datei-Typ entspricht */
	function getOfType($suchbegriff,$type){
		$say = false;
		$sucharray = array();
		$sucharray[$suchbegriff[0]] = $suchbegriff[1];
		$sucharray['type'] = $type;
		$sucharray['output'] = "return";
		
		$file_infos = CunddFiles::get($sucharray);
		
		// DEBUGGEN
		if($say){
			echo '<pre>';
			var_dump($file_infos);
			echo '</pre>';
		}
		// DEBUGGEN --------------------------------------
		
		return $file_infos;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt das Ergebnis von getOfType() in einer Tabelle aus. */
	function printOfType($suchbegriff, $type){
		$say = false;
		
		$file_infos = CunddFiles::getOfType($suchbegriff,$type);
		$felder = CunddFelder::get_files();
		$j = 0;
		while($felder[$j]){
			$table_cols[$j] = $felder[$j]["name"];
			$j++;
		}
		
		// DEBUGGEN
		if($say){
			CunddTools::pd($file_infos);
		}
		// DEBUGGEN
		
		$table_name = "Files";
		$kopf_zeile = true;
		$edit_call = 'new CunddFiles_js(this)';
		CunddTemplate::show_table($file_infos,$table_name,$table_cols,$kopf_zeile, $edit_call);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt den Mime-Typ des übergebenen Datei-Array.
	 * @param array $file_referenz
	 * @return string
	 */
	function get_mime_type($file_referenz,$forceFilenameMode = NULL){
		/*
		if(new finfo()){
			CunddTools::bp();
		}
		/* */
		/*
		//mime_content_type($file_referenz["tmp_name"]);
		CunddTools::bp();
		magic_open();
		$con = finfo_open(FILEINFO_MIME, $file_referenz["tmp_name"]);
		CunddTools::bp();
		$finfo_objekt = new finfo(FILEINFO_MIME); /* "FILEINFO_MIME" ist eine von "finfo" 
													definierte Konstante */
		/*
		CunddTools::bp();
		// Die Datei übergeben
		$fresultat = $finfo_objekt->file($file_referenz["tmp_name"]);
		CunddTools::bp();
		// Das Ergebnis überprüfen
		if (is_string($fresultat) && !empty($fresultat)) {
			$ftype = $fresultat;
			
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/* Wenn die Erkennung per "finfo" kein Ergebnis lieferte wird der Mime-Type per 
		 Datei-Erweiterung ermittelt wenn dies in der Konfigurationsdatei erlaubt wird. */
//		} else 
		
		if(CunddConfig::get("allow_detect_mime_via_suffix") OR $forceFilenameMode){
			// Die Dateiendung ermitteln
			$filename_parts = explode('.',$file_referenz["name"]);
			$file_extension = $filename_parts[count($filename_parts)-1];
			$file_extension= strtolower($file_extension);
			
			/* Die Auflistung der Mime-Types mit entsprechender Extension sind in der 
			Konfigurationsdatei definiert. */
			$mime_type_library = CunddConfig::get("mime_type_library");
			$ftype = $mime_type_library[$file_extension];
		}
		return $ftype;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die Daten eines Files, dessen ID (feld=schluessel) als Parameter 
	 übergeben wurde, aus und gibt sie in einem assoziativen Array zurück. */
	function get_daten($file_id){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
					  CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
		CunddConfig::get('prefix')."files` WHERE schluessel = '".$file_id."';";
		
		$resultat = mysql_query($anfrage);
		$file_infos = mysql_fetch_array($resultat);
		
		return $file_infos;
	}
	function get_data($fileId){
		return CunddFiles::get_daten($fileId);
	}
	function getData($fileId){
		return CunddFiles::get_daten($fileId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "check_type" überprüft ob der Mime-Type der Datei einem der im Array 
	 $allowed_types eingetragenen entspricht und gibt true, oder false zurück. */
	public function check_type($filename){
		// Überprüfen ob Datentyp-Reglementierungen angegeben wurden
		if($this->allowed_types){
			// Wenn ja, den Datentyp prüfen
			if(!eregi($this->allowed_types, $_FILES[$filename]['type'])) {
				$validfile = true;
			} else {
				$validfile = false;
			}
		} else {
			// sonst wird $validfile auf true gesetzt
			$validfile = true;
		}
		
		return $validfile;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "save_file" legt die entsprechenden Daten in der MySQL-Tabelle "_files" 
	 ab und speichert die Dateien im in der Konfigurations-Datei angegebenen Pfad. */
	function save_file($filename){	
		$say = true;
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Daten vorbereiten
		if($_POST["type"] == "group"){ // Eine neue Gruppe eintragen
			//$_POST["files_dateiname"] = time()."group_".$_POST["files_title"];
			$_POST["files_dateiname"] = "";
			$_POST["files_originalname"] = $_POST["files_title"];
			$_POST["files_type"] = "cundd/group";
			$_POST["files_size"] = 0;
			
			$_POST["files_ersteller"] = $_SESSION["benutzer"];
			$_POST["files_erstellungsdatum"] = date("Y-m-d");
			$_POST["files_erstellungszeit"] = date("H:i");
			
			$_POST["files_bearbeiter"] = $_SESSION["benutzer"];
			$_POST["files_bearbeitungsdatum"] = date("Y-m-d");
			$_POST["files_bearbeitungszeit"] = date("H:i");
			
			if(!$_POST["files_rechte"]){
				$_POST["files_rechte"] = 6664;
			}
			
			$_POST["files_gruppe"] = $_SESSION["gruppe"];
			$_POST["files_geloescht"] = "0";
			
			$_POST["files_parent"] = CunddConfig::get("CunddFiles_root_group");
			
		} else { // Ein neues File eintragen
			// Den Namen im Filesystem vorbereiten
			$charsToDelete = array(' ','/','|','\\','"',"'","ä","ö","ü","Ä","Ö","Ü");
			$file_dateiname = $_FILES[$filename]['name'];
			$file_dateiname = str_replace($charsToDelete,'',$file_dateiname);
			$file_dateiname = time().$file_dateiname;
			
			$_POST["files_dateiname"] = $file_dateiname;//time().$_FILES[$filename]['name'];
			$_POST["files_originalname"] = $_FILES[$filename]['name'];
			
			/* uploadify übergibt die Datei mit dem Mime-Type "application/octet-stream". 
			 Deshalb wird der Typ mit der Methode get_mime_type() ermittelt. */
			// $_POST["files_type"] = $_FILES[$filename]['type']; // = "application/octet-stream"
			$_POST["files_type"] = CunddFiles::get_mime_type($_FILES[$filename]);
			
			$_POST["files_size"] = $_FILES[$filename]['size'];
			
			$_POST["files_ersteller"] = $_SESSION["benutzer"];
			$_POST["files_erstellungsdatum"] = date("Y-m-d");
			$_POST["files_erstellungszeit"] = date("H:i");
			
			$_POST["files_bearbeiter"] = $_SESSION["benutzer"];
			$_POST["files_bearbeitungsdatum"] = date("Y-m-d");
			$_POST["files_bearbeitungszeit"] = date("H:i");
			
			if(!$_POST["files_rechte"]){
				$_POST["files_rechte"] = 6664;
			}
			
			$_POST["files_gruppe"] = $_SESSION["gruppe"];
			$_POST["files_geloescht"] = "0";
		}
		/* Parent auf 0 setzen wenn kein Parent definiert wurde und der Wert "autoParent" in 
		der Konfigurationsdatei gleich TRUE ist. */
		if(!$_POST["files_parent"] AND CunddConfig::get('autoParent')){
			$_POST["files_parent"] = 0;
		}
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// File in der MySQL-Tabelle speichern
			mysql_connect(CunddConfig::get("mysql_host"), CunddConfig::get("mysql_benutzer"), 
						  CunddConfig::get("mysql_passwort"));
			
			$anfrage = "INSERT INTO `".CunddConfig::get("mysql_database")."`.`".
			CunddConfig::get('prefix')."files` () VALUES (";
			
			$mysql_infos = CunddFelder::get_files();
			
			for($i=0; $i < count($mysql_infos); $i++){
				$feld = "files_".$mysql_infos[$i]["name"];
				$anfrage .= "'".$_POST[$feld]."', ";
			}
			
			// Anfrage schlieüen
			$anfrage .= "NULL);";
			
			$mysql_resultat = mysql_query($anfrage);
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// File im Dateisystem speichern wenn der Typ/Modue der Instanz nicht "group" ist
		if($_POST["type"] != "group"){
			if($say) echo 'Current file is no group. File-type:'.$_POST["files_type"].' ';
			$type = $_POST["files_type"];
			$substitutedType = str_replace('image','',$type);
			
			// Überprüfen ob die Datei ein Bild ist und der GD-Support aktiviert ist 
			if($type != $substitutedType AND CunddConfig::get('enableGDSupport')){
				if($say) echo 'Current file is a picture ';
				$file = $_FILES[$filename];
				
				$file['files_dateiname'] = $_POST["files_dateiname"];
				$file['files_type'] = $type;
				$fileTempPath = $file["tmp_name"];
				echo '<br />'.$fileTempPath.'<br />';
				// Wenn die Datei ein Bild ist
				$moved = CunddGalerie::scale_image_for_save_with_temp_copy($fileTempPath, $file);
			} else {
				if($say) echo 'Current file is no picture ';
				$file = $_FILES[$filename];
				$fileTempPath = $file["tmp_name"];
				$moved = CunddFiles::save_file_to_filesystem($fileTempPath);
			}
		}
		
		// DEBUGGEN
		if($say){
			echo 'mysql_anfrage = '.$anfrage.'<br />';
			echo 'mysql_result = '.$mysql_resultat.'<br />';
			echo 'move-Resultat = '.$moved.'<br />';
			CunddTools::log_fehler("CunddFiles",$anfrage);
		}
		// DEBUGGEN --------------------------------------
		
		$resultat = $mysql_resultat * $moved;
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "sync_global" synchronisiert die Werte von $_POST und $_GET. */
	function sync_global(){
		// $_POST schreiben
		while ($aktueller_wert = current($_GET)) {
			$_POST[key($_GET)] = $aktueller_wert;
			next($_GET);
		}
		
		// $_GET schreiben
		while ($aktueller_wert = current($_POST)) {
			$_GET[key($_POST)] = $aktueller_wert;
			next($_POST);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt den Pfad zu einer Datei und gibt diesen zurück. Es können ver-
	 schiedene Modi für die Methode angegeben werden: "thumb" (oder "thumbnail"), "download",
	 "upload". Der Standardwert ist "download".
	 * @param string $filename
	 * @param string $mode
	 * @param boolean $forceRemotePath
	 * @return string
	 */
	function get_real_path($filename, $mode = NULL, $forceRemotePath = NULL){
		$say = false;
		
		if(!$mode){
			$mode = "download";
		}
		
		if(CunddConfig::get('CunddFiles_use_ftp')){
			$useRemote = true;
		} else if($forceRemotePath){
			$useRemote = true;
		} else {
			$useRemote = false;
		}
		
		// DEBUGGEN
		if($say) echo "useRemote $useRemote useRemote";
		// DEBUGGEN
		
		switch($mode){
			case "download":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_server_web_representation').CunddConfig::get('CunddFiles_upload_dir').$filename;
					return $url;
				} else {
					$url = CunddPath::getAbsoluteFileUploadUrl().$filename;
					return $url;
				}
				
				break;
				
			case "thumb":
			case "thumbnail":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_server_web_representation').CunddConfig::get('CunddFiles_upload_dir').CunddConfig::get('CunddFiles_thumbnail_subdir').$filename;
					return $url;
				} else {
					$url = CunddConfig::get('CunddFiles_upload_dir').CunddConfig::get('CunddFiles_thumbnail_subdir').$filename;
					return $url;
				}
				
				break;
				
			case "upload":
			case "upload_detail":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_base_path').CunddConfig::get('CunddFiles_upload_dir').$filename;
					return $url;
				} else {
					$url = '.'.CunddConfig::get('CunddFiles_upload_dir').$filename;
					return $url;
				}
				
				break;
				
			case "upload_thumb":
			case "upload_thumbnail":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_base_path').CunddConfig::get('CunddFiles_upload_dir').'thumbnails/'.$filename;
					return $url;
				} else {
					$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'thumbnails/'.$filename;
					return $url;
				}
				
				break;
				
			case "upload_original":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_base_path').CunddConfig::get('CunddFiles_upload_dir').'original/'.$filename;
					return $url;
				} else {
					$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'original/'.$filename;
					return $url;
				}
				
				break;
				
			case "upload_temp":
			case "upload_temp_detail":
				$url = '.'.CunddConfig::get('CunddFiles_upload_dir').$filename;
				return $url;
				break;
				
			case "upload_temp_original":
				$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'original/'.$filename;
				return $url;
				break;
				
			case "upload_temp_thumbnail":
			case "upload_temp_thumb":
				$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'thumbnails/'.$filename;
				return $url;
				break;
		}
		/*
		CunddFiles_use_ftp = 1
		CunddFiles_ftp_server = http://ftp.brutzel.net
		CunddFiles_ftp_server_web_representation = http://www.vbc-feldkirch.at
		CunddFiles_ftp_user = webusr17
		CunddFiles_ftp_password = Dup2gunu
		
		$currentImage[$this->classPrefix.'_show_'.$this->classPrefix] = CunddConfig::get('CunddFiles_upload_dir').
		CunddConfig::get('CunddFiles_thumbnail_subdir').$currentImage['dateiname']; // Pfad des Bildes
		 /* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt den Pfad zu einer Datei und gibt diesen zurück. Es können ver-
	 * schiedene Modi für die Methode angegeben werden: "thumb" (oder "thumbnail"), 
	 * "download", "upload". Der Standardwert ist "download".
	 * @param string $filename
	 * @param string $mode
	 * @param boolean $forceRemotePath
	 * @return string
	 */
	public static function getRealPath($filename, $mode = NULL, $forceRemotePath = NULL){
		return self::get_real_path($filename, $mode, $forceRemotePath);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die Daten einer Datei und ermittelt den Pfad zu ihr und gibt diesen 
	 zurück. Es können verschiedene Modi für die Methode angegeben werden: "thumb" (oder "
	 thumbnail"), "download". Der Standardwert ist "download". */
	function get_real_path_by_fileId($fileId, $mode = NULL, $forceRemotePath = NULL){
		$fileInfos = CunddFiles::get_daten($fileId);
		return CunddFiles::get_real_path($fileInfos['dateiname'], $mode, $forceRemotePath);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode schreibt die Datei in das lokale oder entfernte Dateisystem.
	 * @param string $fileTempPath
	 * @param string $uploadPath[optional] Path the file should be saved at
	 * @return boolean
	 */
	function save_file_to_filesystem($fileTempPath, $uploadPath = NULL){
		$moved = false;
		$say = true;
		
		// Den echten Upload-Path überprüfen
		if(!$uploadPath){
			$uploadPath = CunddFiles::get_real_path($_POST["files_dateiname"],'upload');
		}
		
		/* Überprüfen ob die Datei lokal oder auf einem entfernten Server gespeichert werden 
		 soll. */
		if(CunddConfig::get('CunddFiles_use_ftp')){ // Entfernt speichern
			// Den Port bestimmen
			if(CunddConfig::get('CunddFiles_ftp_port')){
				$port = CunddConfig::get('CunddFiles_ftp_port');
			} else {
				$port = 21;
			}
			
			
			$ftpConnection = ftp_connect(CunddConfig::get('CunddFiles_ftp_server'), $port);
			
			$ftpLogin = ftp_login($ftpConnection, CunddConfig::get('CunddFiles_ftp_user'), CunddConfig::get('CunddFiles_ftp_password')) or die("<h1>You do not have access to this ftp server!</h1>");
			
			
			if(ftp_put($ftpConnection, $uploadPath, $fileTempPath, FTP_BINARY)) {
				//CunddTools::log("CunddFiles","b".CunddFiles::get_real_path($_POST["files_dateiname"])."b");
				$moved = true;
			}
			
			
			if (ftp_chmod($ftpConnection, 0666, $uploadPath) !== false) {
				CunddTools::log("CunddFiles","ftp_chmod changed");
			} else {
				CunddTools::log("CunddFiles","ftp_chmod could not be changed");
			}
			
			
			// DEBUGGEN
			if($say){
				echo 'Entfernt speichern<br />';
				echo 'Erfolg '.$moved.'<br />';
				echo '$ftpConnection = '.$ftpConnection.'<br />';
				echo '$ftpLogin = '.$ftpLogin.'<br />';
				echo '$uploadPath = '.$uploadPath.'<br />';
				CunddTools::log('$ftpConnection','$ftpConnection='.$ftpConnection.' upload-path:'.$uploadPath.'; fileTempPath'.$fileTempPath);
			}
			// DEBUGGEN
			
			
		} else { // Lokal speichern
			//$moved = move_uploaded_file($fileTempPath, $uploadPath);
			$moved = rename($fileTempPath, $uploadPath);
			
			// DEBUGGEN
			if($say){
				echo 'Lokal speichern<br />';
				echo 'Erfolg='.$moved.' $fileTempPath:'.$fileTempPath.'<br />$uploadPath:'.$uploadPath.'<br />';
			}
			// DEBUGGEN
		}
		
		
		
		if($moved){
			CunddTools::log_fehler("CunddFiles","File-copy ok");
			return true;
		} else {
			CunddTools::log_fehler("CunddFiles","File-copy failed. Target-Folder ".$uploadPath);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode schreibt die Datei in das lokale oder entfernte Dateisystem.
	 * @param string $fileTempPath
	 * @param string $uploadPath[optional] Path the file should be saved at
	 * @return boolean
	 */
	public static function saveFileToFileSystem($fileTempPath, $uploadPath = NULL){
		return self::save_file_to_filesystem($fileTempPath,$uploadPath);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine neue Gruppe nach dem Schema eines neuen Files. */
	function newGroup(){
		CunddTemplate::file_input_form("group");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest alle Datei-Gruppen (Mime-Type=cundd/group) aus der MySQL-Tabelle. */
	function getGroups(){
		return CunddFiles::getOfType("","cundd/group");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ruft die Methode getGroups() auf und gibt das Ergebnis aus. Zur Ausgabe 
	 stehen mehrere Methoden zur Auswahl: ohne Angabe von Parametern, oder dem Wert "list" 
	 wird eine Drop-Down-Liste ausgegeben. Ist der Parameter "table" wird eine detaillierte 
	 Tabelle angezeigt. Bei "helper" wird nur die Liste der Optionen ausgegeben. */
	function printGroups($output = "list", $selected = false){
		$liste = CunddFiles::getGroups("","cundd/group");
		
		if($output == "list"){
			// CunddTemplate übernimmt die weiteren Aufrufe
			$contentOutput = CunddTemplate::inhalte_einrichten(NULL, 6, "files_parent", "spezial");
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		} else if($output == "table"){ // Ergebnis in Form einer Tabelle ausgeben
			$felder = CunddFelder::get_files();
			$j = 0;
			while($felder[$j]){
				$table_cols[$j] = $felder[$j]["name"];
				$j++;
			}
			
			$table_name = "Groups";
			$kopf_zeile = true;
			$contentOutput .= CunddTemplate::show_table($liste,$table_name,$table_cols,$kopf_zeile);
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		} else if($output == "helper"){
			
			// Ein Element für "nichts" hinzufügen
			$temp_liste = $liste;
			$i = 1; // Alle Elemente des Arrays auf den nächsten Index verschieben
			foreach($temp_liste as $element){
				$liste[$i] = $element;
				$i++;
			}
			/*
			$liste[0] = array("title" => CunddLang::get("files_parent_please_select"),
							  "schluessel" => '',
							  "disabled" => 'disabled="disabled"',
							  );
			/* */
			$liste[0] = array("title" => CunddLang::get("files_parent_please_select"),
							  "schluessel" => '0',
							  );
			
			/* */
			// Für jedes Element des Arrays eine Select-Option erstellen
			foreach($liste as $groupArray){
				if(is_string($groupArray["title"]) AND $groupArray["title"] != " "){
					// Den alten Wert selektieren
					if($selected){
						if($groupArray['schluessel'] == $selected){
							$groupArray['selected'] = 'selected="selected"';
						} else {
							$groupArray['selected'] = NULL;
						}
					} else {
						$groupArray['selected'] = NULL;
					}
					
					// Ausgabe
					$contentOutput .= CunddTemplate::inhalte_einrichten($groupArray, 6, "files_print_groups", "output");
				}
			}
		}
		
		return $contentOutput;
	}
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest alle Dateien mit dem übergebenen Parent-ID aus. */
	function getOfParent($parentId, $suchbegriff = NULL){
		$search = array();
		$search["parent"] = $parentId;
		$search["output"] = "output";
		$search["search"] = $suchbegriff;
		return CunddFiles::get($search);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt alle Dateien aus. */
	function printAll($suchbegriff = NULL){
		$sucharray = array();
		$sucharray[$suchbegriff[0]] = $suchbegriff[1];
		$sucharray['output'] = "return";
		
		$allFiles = CunddFiles::get($sucharray);
		
		$felder = CunddFelder::get_files();
		$j = 0;
		while($felder[$j]){
			$table_cols[$j] = $felder[$j]["name"];
			$j++;
		}
		$table_cols[] = 'schluessel';
		
		// Die Anzahl der gefundenen Dateien anzeigen
		$tag = 'files_all_files_count';
		$wert[$tag] = count($allFiles);
		echo CunddTemplate::inhalte_einrichten($wert, 6664, $tag, 'special');
		
		$table_name = "Files";
		$kopf_zeile = true;
		$edit_call = 'new CunddFiles_js(this)';
		CunddTemplate::show_table($allFiles,$table_name,$table_cols,$kopf_zeile,$edit_call);
		
		//CunddTools::pd($allInGroup);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt alle Dateien inklusive einem Lüschen-Button aus. */
	function printAllWithDelete($suchbegriff = NULL){
		$sucharray = array();
		$sucharray[$suchbegriff[0]] = $suchbegriff[1];
		$sucharray['output'] = "return";
		
		$allFiles = CunddFiles::get($sucharray);
		
		$felder = CunddFelder::get_files();
		$j = 0;
		
		$deleteCaption = "Loeschen";
		$table_cols[$j] = $deleteCaption;
		$j++;
		
		while($felder[$j]){
			$table_cols[$j] = $felder[$j-1]["name"];
			$j++;
		}
		$table_cols[] = 'schluessel';
		
		foreach($allFiles as &$currentFile){
			$currentFile[$deleteCaption] = CunddFiles::create_delete_link($currentFile['schluessel']);
		}
		
		// Die Anzahl der gefundenen Dateien anzeigen
		$tag = 'files_all_files_count';
		$wert[$tag] = count($allFiles);
		echo CunddTemplate::inhalte_einrichten($wert, 6664, $tag, 'special');
		
		/* */
		$table_name = "Files";
		$kopf_zeile = true;
		$edit_call = 'new CunddFiles_js(this)';
		CunddTemplate::show_table($allFiles,$table_name,$table_cols,$kopf_zeile,$edit_call);
		/* echo '<div class="image_show_previous_img_link">
					<a href="#" class="CunddNewLink '.$wert['classPrefix'].' next" ';
				echo CunddLink::newLinkAction($wert['aufruf']);
		//CunddTools::pd($allInGroup);
		 * 
		 */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt einen Link zum Löschen des Files. */
	function create_delete_link($fileId){
		$title = CunddLang::get('files_delete_link_title');
		$newLink = CunddLink::newLink($title,'CunddFiles::delete',NULL,$fileId,'delete link');
		
		return $newLink;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt einen Link zum Download des Files. */
	function createDownloadLink($fileId){
		$fileData = CunddFiles::getData($fileId);
		
		$title = $fileData["title"];
		
		if($title){
			$url = CunddPath::getRelativeDownloadDir().'?fileId='.$fileId;
			$newLink = CunddLink::newHardLink($title,$url,'_blank');
		} else {
			$newLink = false;
		}
		
		return $newLink;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt einen Link zum Download des Files aus. */
	public static function printDownloadLink($fileId){
		$tag = 'links_hardlink';
		$wert[$tag] = CunddFiles::createDownloadLink($fileId);
		
		if($wert[$tag]) $result = CunddTemplate::__($wert,6,$tag,'output');
		echo $result;
		return $result;
	}
	
		
		
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt alle Dateien in einer definierten Gruppe aus. */
	function printOfGroup($group, $suchbegriff = NULL){
		$parentId = $group;
		$allInGroup = CunddFiles::getOfParent($parentId);
		
		$felder = CunddFelder::get_files();
		$j = 0;
		while($felder[$j]){
			$table_cols[$j] = $felder[$j]["name"];
			$j++;
		}
		
		$table_name = "Files";
		$kopf_zeile = true;
		$edit_call = 'new CunddFiles_js(this)';
		CunddTemplate::show_table($allInGroup,$table_name,$table_cols,$kopf_zeile,$edit_call);
		
		//CunddTools::pd($allInGroup);
	}
	
		
		
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liefert die Daten einer Datei ohne ihren eigentlichen Speicherort bekannt 
	 * zu geben. */
	public static function provide_download($fileId){
		$say = false;
		$encoding = 'gzip';
		
		// Die Datei-Daten ermitteln
		$fileInfos = CunddFiles::get_data($fileId);
		
		// Wenn die Datei nicht existiert wird eine entsprechende Nachricht ausgegeben
		if(!$fileInfos){
			self::fileNotExists($fileId);
			return false;
		}
		
		$currentFileAttributes = CunddFiles::getAttributesFromString($fileInfos['attribute']);
		$forceRemotePath = $currentFileAttributes['forceRemotePath'];
		
		$filepath = CunddFiles::get_real_path($fileInfos['dateiname'], $thumbnailsOnly, $forceRemotePath);
		$filetype = $fileInfos['type'];
		$filetitle = $fileInfos['title'];
		$filesize = $fileInfos['size'];
		
		
		// Die Rechte ermitteln
		$right = CunddRechte::get($fileInfos);
		
		
		// DEBUGGEN
		if($say){
			echo 'right='.$right;
			echo '<pre>';var_dump($fileInfos);echo '</pre>';
		}
		// DEBUGGEN
		
		
		// Die Rechte anwenden
		if($right > 0){
			//echo 'DEBUG';
			header("Content-Type: application/octet-stream; ");
			header("Content-Transfer-Encoding: binary"); 
			header('Content-type: '.$filetype);
			header('Content-length: '.$filesize);
			header('Content-Disposition: attachment; filename="'.$filetitle.'"');
			header('Content-Description: File Transfer');
			readfile($filepath);
		} else {
			header('HTTP/1.1 403 Forbidden');
		}
		/* */
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wird aufgerufen wenn eine Datei nicht gefunden wird.
	 * @param int $data
	 * @return string
	 */
	protected static function fileNotExists($data = NULL){
		// header('HTTP/1.1 403 Forbidden');
		if($data){
			$msg = "The file with the ID $data could not be found";
		} else {
			$msg = "The file could not be found";
		}
		CunddTools::error('CunddFiles',$msg);
		echo $msg;
		
		return $msg;
	}
}
?>