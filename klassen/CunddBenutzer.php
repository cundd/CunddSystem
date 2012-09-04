<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddBenutzer" bietet verschiedene Methoden zum handhaben der Benutzer */
class CunddBenutzer extends CunddAttribute {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	static $sessionUser = array(); // Speichert die Daten des aktuell eingeloggten Benutzers
	private static $className = 'CunddBenutzer';
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor: Ruft das Benutzer-Formular auf
	function CunddBenutzer(){
		CunddTemplate::benutzer_formular();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode zeigt eine Liste aller Benutzer an. */
	function show(){
		/* Überprüfen wer die Benutzerliste ansehen darf. */
		$sicht = CunddConfig::get(benutzerliste_sichtbarkeit);
		if($sicht == 8 AND CunddGruppen::ist_in(1)){ // Nur "root"
			$benutzer = CunddBenutzer::get();
		} else if($sicht == 4 AND (CunddGruppen::ist_in(2) OR CunddGruppen::ist_in(1))){
			// Alle "verwalter" und "root"
			$benutzer = CunddBenutzer::get();
		} else if($sicht == 2 AND $_SESSION["benutzer"]){ // Alle eingeloggten Benutzer
			$benutzer = CunddBenutzer::get();
		} else if($sicht == 1){ // Alle
			$benutzer = CunddBenutzer::get();
		} else { // Nur den eigenen Benutzer anzeigen
			/* Das Ergebnis von "CunddBenutzer::get_daten()" in ein mehrdimensionales Array 
			ändern. */
			$benutzer = array();
			$benutzer[0] = CunddBenutzer::get_daten($_SESSION["benutzer"]);
			//$benutzer[1] = CunddBenutzer::get_daten($_SESSION["benutzer"]);
		}
		
		if($benutzer){
			// Ergebnis an "CunddTemplate" weiterleiten
			CunddTemplate::show_benutzer($benutzer);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt ALLE Benutzer und gibt sie in einem mehrdimensionalen Array 
	 * zurück.
	 * @return array
	 */
	function getAllUsers(){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),
			CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
			"benutzer`;";
		
		$resultat = mysql_query($anfrage);
		
		$benutzer = array();
		
		$i = 0;
		while($wert = mysql_fetch_array($resultat)){
			// Alle möglichen Felder auslesen
			$felder = CunddFelder::get_benutzer("root");
			
			for($j = 0; $j < count($felder[0]); $j++){
				$benutzer[floor($i)][$felder[0][$j]] = $wert[$felder[0][$j]];
			}
			$i++;
		}
		
		
		return $benutzer;
	}
	function get(){
		return self::getAllUsers();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest die Daten eines Benutzers, dessen Name (feld=benutzer) als Para-
	 * meter übergeben wurde, aus und gibt sie in einem assoziativen Array zurück.
	 * Hinweis: "CunddBenuter::get()" gibt alle Benutzer zurück.
	 * @param string $benutzerName
	 * @return array
	 */
	function get_daten($benutzerName){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."benutzer` WHERE benutzer = '".$benutzerName."';";
		
		$resultat = mysql_query($anfrage);
		$benutzer_daten = mysql_fetch_array($resultat);
		
		return $benutzer_daten;
	}
	public static function getDataByName($userName){
		return self::get_daten($userName);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest die Daten eines Benutzers, dessen Name (feld=benutzer) als Para-
	 * meter übergeben wurde, aus und gibt sie in einem assoziativen Array zurück.
	 * Hinweis: "CunddBenuter::get()" gibt alle Benutzer zurück.
	 * @param int $id
	 * @return array
	 */
	public static function getDataById($id){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."benutzer` WHERE schluessel = '".$id."';";
		
		$resultat = mysql_query($anfrage);
		$benutzer_daten = mysql_fetch_array($resultat);
		
		return $benutzer_daten;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Daten des aktuell eingeloggten Benutzers zurück und speichert 
	 * sie in der Klassen-Variable $sessionUser. */
	/*static function get_session_user(){
		if(isset(self::$sessionUser)){
			return self::$sessionUser;
		} else {
			// TODO: CunddBenutzer session[benutzer]
			$sessionUserName = $_SESSION['benutzer'];
			$data = CunddBenutzer::get_daten($sessionUserName);
			if(CunddBenutzer::set_session_user($data)){
				return self::get_session_user();
			} else {
				return false;
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt die Klassen-Variable $sessionUser. */
	/*private static function set_session_user($data){
		if(isset(self::$sessionUser)){
			$msg = 'Error on storing $sessionUser';
			CunddTools::error('CunddBenutzer',$msg);
			return false;
		} else {
			self::$sessionUser = $data;
			return self::$sessionUser;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode berarbeitet die Daten eines Benutzers in der MySQL-Tabelle.
	 * @param array $eingabe
	 * @return resource
	 */
	function edit($eingabe){
		$say = false;
		// Überprüfen "wer" die Methode aufgerufen hat.
		if($_SESSION["hauptgruppe"]){ // Ein eingeloggter Benutzer
			$eingabe["bearbeiter"] = $_SESSION["benutzer"];
			$ersteller_gruppe = $_SESSION["hauptgruppe"];
		}else{ // Ein unbekannter Nutzer hat das Skript aufgerufen. Es wird deshalb nicht ausgeführt
			echo "Nicht berechtigt";
			$eingabe["ersteller"] = NULL;
			$ersteller_gruppe = NULL;
		}
		
		
		if($ersteller_gruppe){
			/* Wenn alle Gruppen-Checkboxen deaktiviert sind wird der Wert von $eingabe["gruppen"] 
			auf den Wert der gewählten Hauptgruppe gesetzt. */
			if($eingabe["gruppen"]){
				// TODO: Es werden seltsame Werte übergeben
				
				/* Die Werte aller Checkboxen mit dem Namen "gruppen[]" werden in einem Array übergeben. 
				Alle Werte der Elemente werden miteinander addiert. */
				for($i = 0; $i < count($eingabe["gruppen"]); $i++){
					$gruppen += $eingabe["gruppen"][$i];
				}
				
				if($say){
					echo 'group defined'.$gruppen;
					CunddTools::pd($eingabe['gruppen']);
				}
				
				// $eingabe["gruppen"] mit der Summe überschreiben 
				$eingabe["gruppen"] = $gruppen;
			} else { // $eingabe["gruppen"] mit 2^Hauptgruppe überschreiben
				$eingabe["gruppen"] = pow(2,$eingabe["hauptgruppe"]);
				echo '$eingabe["gruppen"]'.$eingabe["gruppen"];
				
				if($say) echo 'group not defined';
			}
			
						
			
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			
			// Bearbeiter ausfüllen
			$eingabe["bearbeitungsdatum"] = date("Y-m-d");
			$eingabe["bearbeiter"] = $_SESSION["benutzer"].'+'.$eingabe["bearbeiter"];
			
			
			// Die Attribute verarbeiten
			
			$oldAttributes = $eingabe["oldAttribute"];
			$eingabe["attribute"] = self::handleAttributeInput($eingabe, $oldAttributes);
			
			
			// Die BenutzerInfoFelder-Sichtbarkeit für die angegebene Gruppe auslesen
			$felder = CunddFelder::get_benutzer("root");
			
			mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
				CunddConfig::get('mysql_passwort'));
		
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer` SET ";
			// Jedes Element aus $felder an die MySQL-Anfrage anhängen
			if($felder[0]){
				$name_des_elements = $felder[0][0];
				$wert = $eingabe[$name_des_elements];
				if($wert){
					$anfrage .= '`'.$name_des_elements.'`=';
					
					// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
					if(is_string($wert)){
						$anfrage .= "'".$wert."' ";
					} else {
						$anfrage .= $wert." ";
					}
				}
			}
			for($i = 1; $i < count($felder[0]); $i++){
				/* Die Reihnfolge wird durch die Elemente in $felder bestimmt. Die entsprechenden 
				Daten werden aus dem assoziativen Array $eingabe abgerufen. */
				$name_des_elements = $felder[0][$i];
				$wert = $eingabe[$name_des_elements];
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
			// "aktiv" wird auch eingetragen wenn es 0 ist.
			$anfrage .= " , `aktiv`='".$eingabe["aktiv"]."' ";
			
			$anfrage .= " WHERE benutzer='".$eingabe["benutzer"]."';";
			
			// DEBUGGEN
			if($say){
				echo '$anfrage='.$anfrage.'<br />';
				echo '$resultat='.$resultat.'<br />';
			}
			// DEBUGGEN------------------------------------------
			
			
			$resultat = mysql_query($anfrage);
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode erstellt einen neuen Benutzer und füllt sie in entsprechenden Tabellen 
	 * ein. Sie gibt das Ergebnis der MySQL-Anfrage zurück.
	 * @param array $eingabe
	 * @return resource
	 */
	function neu($eingabe){
		$say = false;
		// überprüfen "wer" die Methode aufgerufen hat.
		if(CunddGruppen::ist_in(CunddConfig::get("bedingung_neuer_benutzer")) OR
			(CunddConfig::get("bedingung_neuer_benutzer") == 'n' AND $_SESSION["benutzer"] != '') OR 
			CunddGruppen::ist_in(1)){ // Ein eingeloggter Benutzer
			$eingabe["ersteller"] = $_SESSION["benutzer"];
			$eingabe["bearbeiter"] = $_SESSION["benutzer"];
			$ersteller_gruppe = $_SESSION["hauptgruppe"];
			
		}else if($eingabe["ersteller"] == "installer_skript"){ // Das Installationsskript
			$eingabe["ersteller"] = "installer_skript";
			$ersteller_gruppe = "root";
			
		}else if(CunddConfig::get('bedingung_neuer_benutzer') == 'n'){
			$eingabe["ersteller"] = "installer_skript";
			$ersteller_gruppe = "root";
		} else { // Ein unbekannter Nutzer hat das Skript aufgerufen. Es wird deshalb nicht ausgeführt
			echo "Nicht berechtigt";
			$eingabe["ersteller"] = NULL;
			$ersteller_gruppe = NULL;
		}
		
		
		if($ersteller_gruppe){
			/* Die Werte aller Checkboxen mit dem Namen "gruppen[]" werden in einem Array übergeben. 
			Alle Werte der Elemente werden miteinander addiert. Wenn die Methode allerdings vom 
			Installer */
			if(count($eingabe["gruppen"]) > 1){
				for($i = 0; $i < count($eingabe["gruppen"]); $i++){
					$gruppen += pow(2,(int)($eingabe["gruppen"][$i]));
				}
				// $eingabe["gruppen"] mit der Summe überschreiben 
				$eingabe["gruppen"] = $gruppen;
			}
			
			// Die Attribute verarbeiten
			$oldAttributes = '';
			$eingabe["attribute"] = self::handleAttributeInput($eingabe, $oldAttributes);
			
			/* Wenn keine Hauptgruppe angegeben wurde -> die Gruppe des Erstellers ein-
			tragen (Kann passieren wenn "zeige_gruppen" = 0) */
			if(!$eingabe["hauptgruppe"] AND $_SESSION["hauptgruppe"]){
				$eingabe["hauptgruppe"] = $_SESSION["hauptgruppe"];
			} else if(!$eingabe["hauptgruppe"]){
				$eingabe["hauptgruppe"] = CunddConfig::get('CunddBenutzer_group_public_register');
			}
			
			// Wenn keine zusätzlichen Gruppen angegeben wurden die Hauptgruppe eintragen
			if(!$eingabe["gruppen"]){
				$eingabe["gruppen"] = pow(2, $eingabe["hauptgruppe"]);
			}
			
			
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			
			// Datum ausfüllen
			$eingabe["erstellungsdatum"] = date("Y-m-d");
			$eingabe["bearbeitungsdatum"] = date("Y-m-d");
			
			
			// Die BenutzerInfoFelder-Sichtbarkeit für die angegebene Gruppe auslesen
			$felder = CunddFelder::get_benutzer("root");
			
			/* DEBUGGEN */if($say) CunddTools::pd($felder);/* DEBUGGEN */
			
			mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
				CunddConfig::get('mysql_passwort'));
		
			$anfrage = "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer` VALUES (";
			// Jedes Element aus $felder an die MySQL-Anfrage anhängen
			for($i = 0; $i < count($felder[0]) - 1; $i++){
				/* Die Reihnfolge wird durch die Elemente in $felder bestimmt. Die entsprechenden 
				Daten werden aus dem assoziativen Array $eingabe abgerufen. */
				$name_des_elements = $felder[0][$i];
				$wert = $eingabe[$name_des_elements];
				// Wenn der aktuelle Wert leer ist NULL eintragen
				if(!$wert){
					$anfrage .= "NULL, ";
				} else {
					// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
					if(is_string($wert)){
						$anfrage .= "'".$wert."', ";
					} else {
						$anfrage .= $wert.", ";
					}
				}
			}
			if(count($felder[0])){
				$name_des_elements = $felder[0][$i];
				$wert = $eingabe[$name_des_elements];
				// Wenn der aktuelle Wert leer ist NULL eintragen
				if(!$wert){
					$anfrage .= "NULL ";
				} else {
					// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
					if(is_string($wert)){
						$anfrage .= "'".$wert."' ";
					} else {
						$anfrage .= $wert." ";
					}
				}
			}
			$anfrage .= ");";
			
			
			$resultat = mysql_query($anfrage);
		}
		
		/* Event auslösen wenn $resultat gleich true und der neue Benutzer noch nicht
		aktiviert wurde. */
		if($resultat AND !$eingabe["aktiv"]){
			new CunddEvent("userAdded",$eingabe["benutzer"]);
		}
		
		
		// DEBUGGEN
		if($say){
			echo '$anfrage='.$anfrage.'<br />';
			echo '$resultat='.$resultat.'<br />';
		}
		// DEBUGGEN------------------------------------------ 
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob der aktuelle Benutzer eingeloggt ist. */
	public static function isLoggedIn(){
		return CunddLogin::isLoggedIn();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Daten des aktuellen Benutzers zurück.
	 * @return array( 'name' => name , 'maingroup' => maingroup , 'groups' => groups )
	 */
	public static function getSessionUserData(){
		$userdata['name'] = $_SESSION['benutzer'];
		$userdata['benutzer'] = $_SESSION['benutzer'];
		$userdata['user'] = $_SESSION['benutzer'];
		
		
		$userdata['hauptgruppe'] = $_SESSION["hauptgruppe"];
		$userdata['maingroup'] = $_SESSION["hauptgruppe"];
		
		$userdata['gruppen'] = $_SESSION["gruppen"];
		$userdata['groups'] = $_SESSION["gruppen"];
		
		return $userdata;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen bestimmten Wert des aktuellen Benutzers zurück.
	 * @param string $valueName
	 * @return unknown|boolean|boolean
	 */
	public static function getSessionUserValue($valueName){
		$userdata = self::getSessionUserData();
		if(array_key_exists($valueName,$userdata)){
			if($userdata[$valueName]){
				return $userdata[$valueName];
			} else {
				return (bool) false;
			}
		} else {
			return (bool) false;
		}
		
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Namen des aktuell eingeloggten Benutzers zurück. */
	public static function getSessionUser(){
		return self::getSessionUserValue('name');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode definiert die automatisch generierten Werte für die Eingabe. */
	/**
	 * @param reference $data
	 * @return Ambigous <string, multitype:>
	 */
	public static function prepareUserData(&$data){
		$username = CunddBenutzer::getSessionUserValue('name');
		$group = CunddBenutzer::getSessionUserValue('groups');
		
		$data['ersteller'] = $username;
		$data['erstellungsdatum'] = date("Y-m-d");
		$data['erstellungszeit'] = date("H:i");
		 
		$data['bearbeitungsdatum'] = date("Y-m-d");
		$data['bearbeitungszeit'] = date("H:i");
		if($data['bearbeiter']){
			$data['bearbeiter'] = $data['bearbeiter'].'+'.$username;
		} else {
			$data['bearbeiter'] = $username;
		}
		
		
		if(!$data['rechte'])		$data['rechte'] = '6664';
		if(!$data['gruppe'])		$data['gruppe'] = $group;
		if(!$data['hauptgruppe'])	$data['hauptgruppe'] = $group;
		
		/* TODO Kept for backwards compatibility */
		if(!$data['sprache'])		$data['sprache'] = CunddLang::get();
		
		if(!$data['lang'])			$data['lang'] = CunddLang::get();
		
		return $data;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * @see CunddGroup::isIn() */
	public static function isIn($groupId){
		return CunddGroup::isIn($groupId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Daten des aktuell eingeloggten Users aus. */
	public static function printSessionUserData($printGuest = NULL){
		$userdata = self::getSessionUserData();
		
		// Den Gast eintragen
		if(!current($userdata) AND $printGuest){
			$userdata['name'] = CunddLang::__('guest');
			$userdata['maingroup'] = 4;
			$userdata['groups'] = 'keine';
			$print = true;
		} else if(current($userdata)){
			$print = true;
		} else {
			$print = false;
		}
		
		if($print){
			// Den Namen der Usergroup ermitteln
			$userdata['maingroup'] = CunddGroup::getName($userdata['maingroup']);
			
			$values = array(array(
				CunddLang::__('Username') => $userdata['name'],
				CunddLang::__('Maingroup') => $userdata['maingroup'],
				CunddLang::__('Groups') => $userdata['groups'],
				),
			);
			$cols = array(
			CunddLang::__('Username'),
			CunddLang::__('Maingroup'),
			CunddLang::__('Groups'),
			);
			$tablename = CunddLang::__('Session user');
			$edit_call = 'none';
			
			return CunddTemplate::showTable($values,$tablename,$cols,true,$edit_call);
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erzeugt eine Box mit verschiedenen Infos für den User.
	 * @param boolean $noOuptut
	 * @return string
	 */
	public static function userBox($printGuest = NULL,$noOutput = NULL){
		ob_start();
		$outputTemp = CunddBenutzer::printSessionUserData($printGuest);
		$outputTemp = CunddLogin::checkIfLoggedInElseShowForm();
		$outputTemp = CunddLogin::printLogoutLink();
		echo CunddTemplate::wrap(CunddLink::newLink('New user','CunddBenutzer::neu','CunddContent'),'new user link');
		$output = ob_get_contents();
		ob_end_clean();
		
		$output = CunddTemplate::wrap($output,'CunddBenutzer userBox','CunddBenutzer userBox');
		
		if(!$noOuptut) echo $output;
		
		return $output;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen Link zum Datei-Upload-Dialog und der Benutzer-Liste aus.
	 * @return string|boolean
	 */
	public function adminBox($noLogoutLink = NULL){
		if(CunddLogin::isLoggedIn()){
			if(!$noLogoutLink){
				$outputTemp = CunddLogin::printLogoutLink(false,true);
				$output = CunddTemplate::wrap($outputTemp,'adminbox logoutlink');
			}
			
			$title = CunddLang::__('Show Users');
			$action = 'CunddUser::show';
			$outputTemp = CunddLink::newLink($title,$action);
			$output .= CunddTemplate::wrap($outputTemp,'adminbox showuser');
			
			$title = CunddLang::__('New User');
			$action = 'CunddUser::neu';
			$outputTemp = CunddLink::newLink($title,$action);
			$output .= CunddTemplate::wrap($outputTemp,'adminbox newuser');
			
			$title = CunddLang::__('File-Upload');
			$action = 'CunddFiles';
			$outputTemp = CunddLink::newLink($title,$action);
			$output .= CunddTemplate::wrap($outputTemp,'adminbox file');
			
			$title = CunddLang::__('File Browser');
			$action = 'CunddFiles::printAllWithDelete';
			$outputTemp = CunddLink::newLink($title,$action);
			$output .= CunddTemplate::wrap($outputTemp,'adminbox filebrowser');
			
			$output = CunddTemplate::wrap($output,'adminbox_container','adminbox');
			echo $output;
			return $output;
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Adminbox aus. */
	public function adminBoxWithLogout(){
		return self::adminBox();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Adminbox aus. */
	public function adminBoxWithoutLogout(){
		return self::adminBox(truec);
	}
	
}
?>