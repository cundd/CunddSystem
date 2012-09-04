<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * Die Klasse "CunddGruppen" bietet verschiedene Methoden zum handhaben der Gruppen.
 * @author daniel
 *
 */
class CunddGruppen{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	const NAME_OF_PUBLIC_GROUP = 'oeffentlich';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine neue Gruppe und füllt sie in den entsprechenden Tabellen 
	ein. Sie gibt das Ergebnis der MySQL-Anfrage zurück. */
	function neu($eingabe){
		// Überprüfen "wer" die Methode aufgerufen hat.
		if($_SESSION["benutzer"]){ // Ein eingeloggter Benutzer
			$benutzer = $_SESSION["benutzer"];
		}else if($eingabe["benutzer"] == "install_skript"){ // Das Installationsskript
			$benutzer = "install_skript";
		}else{ // Ein unbekannter Nutzer hat das Skript aufgerufen. Es wird deshalb nicht ausgeführt
			$benutzer = NULL;
		}
		
		if($benutzer){
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			// In Tabelle "gruppen" eintragen
			$anfrage = "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"gruppen` () VALUES ('".$eingabe["gruppen_name"]."','".$benutzer."','".date("Y-m-d")."','".
				$benutzer."','".date("Y-m-d")."',NULL); ";
			$resultat = mysql_query($anfrage);
				
			/* Die Spalte für die neue Gruppe an die Tabelle "benutzer_verwaltung_sichtbarkeit" 
			anhängen um die Zuordnung zu ermöglichen. */
			$anfrage = "ALTER TABLE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` ADD COLUMN `".$eingabe["gruppen_name"].
				"` INT NOT NULL DEFAULT 0 AFTER `schluessel`;";
				
			$resultat *= mysql_query($anfrage); /* "*=" wenn eine der mysql-Anfragen 0 zurück-
												gibt ist $resultat schlussendlich 0 */
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt alle Gruppen zu denen ein Benutzer gehört und gibt in einem 
	 * mehrdimensionalen Array deren Namen und ID zurück. Wenn keine Gruppensammlung übergeben 
	 * wurde wird die des aktuell eingeloggten Benutzers ermittelt.
	 * @param int $gruppen
	 * @return array|false
	 */
	public static function getGroupNamesAndIds($gruppen = NULL){
		$say = false;
		$groupInfo = array();
		
		if($gruppen === NULL) $gruppen = CunddUser::getSessionUserValue('groups');
		
		
		if($gruppen){
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			$anfrage .= "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"gruppen` WHERE floor(".$gruppen." / POW(2,gruppeid)) % 2;";// = gruppeid;";
			
			$resultat = mysql_query($anfrage);
			if(!$resultat) break;

			while($wert = mysql_fetch_array($resultat)){
				// "floor()" wird verwendet, dass $i sicher als Integer gehandhabt wird
				$groupInfo[(int)$i]["gruppenname"] = $wert["gruppenname"];
				$groupInfo[(int)$i]["groupname"] = $wert["gruppenname"];
				
				$groupInfo[(int)$i]["gruppeid"] = $wert["gruppeid"];
				$groupInfo[(int)$i]["groupid"] = $wert["gruppeid"];
				$i++;
			}
		} else {
			$groupInfo = (bool) false;
		}
		
		
		return $groupInfo;
	}
	/**
	 * @see getGroupNamesAndIds()
	 */
	public static function getCurrentUsersGroups(){
		return self::getGroupNamesAndIds();
	}
	/**
	 * @see getGroupNamesAndIds()
	 */
	public static function get(){
		return self::getGroupNamesAndIds();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt ob der Benutzer aus $_SESSION["benutzer"] Mitglied in der über-
	gebenen Gruppe ist und gibt TRUE bzw. FALSE zurück. Als Parameter muss die ID der ge-
	suchten Gruppe übergeben werden.
	 * @param int $gruppen_id_para
	 * @return boolean
	 */
	function ist_in($gruppen_id_para){
		$resultat = false;
		
		// Alle Gruppen eines Benutzers auslesen
		$alle_gruppen = CunddGruppen::get();

		if(is_numeric($gruppen_id_para)){
			for($i = 0; $i < count($alle_gruppen); $i++){
				if(floor($alle_gruppen[$i]["gruppeid"]) == floor($gruppen_id_para)){
					$resultat = true;
					break;
				}
			}
		} else {
			$resultat = false;
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt ob der aktuell eingeloggte Benutzer Mitglied in der übergebenen 
	 * Gruppe ist und gibt TRUE bzw. FALSE zurück. Als Parameter muss die ID der gesuchten 
	 * Gruppe übergeben werden.
	 * @param int $gruppen_id_para
	 * @return boolean
	 */
	public static function isIn($gruppen_id_para){
		$resultat = false;
		
		// Alle Gruppen eines Benutzers auslesen
		$alle_gruppen = CunddGruppen::get();
		
		if(is_numeric($gruppen_id_para)){
			for($i = 0; $i < count($alle_gruppen); $i++){
				if(floor($alle_gruppen[$i]["groupid"]) == floor($gruppen_id_para)){
					$resultat = true;
					break;
				}
			}
		} else {
			$resultat = false;
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt den Gruppenname anhand einer übergebenen Gruppen-ID.
	 * @param int $gruppen_id_para
	 * @return string|false
	 */
	function get_name($gruppen_id_para){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT gruppenname FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
		"gruppen` WHERE gruppeid=".$gruppen_id_para.";";
		
		$resultat = mysql_query($anfrage);
		
		if($resultat){
			$wert = mysql_fetch_array($resultat);
		} else {
			return false;
		}
		
		return $wert["gruppenname"];
	}
	public static function getName($groupId){
		return self::get_name($groupId);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die Gruppen-ID anhand eines übergebenen Gruppenname.
	 * @param string $gruppen_name_para
	 * @return int
	 */
	function get_id($gruppen_name_para){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT gruppeid FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
		"gruppen` WHERE gruppenname=".$gruppen_name_para.";";
		
		$resultat = mysql_query($anfrage);
		
		$wert = mysql_fetch_array($resultat);
		
		return $wert["gruppeid"];
	}
	/**
	 * @see get_id()
	 */
	public static function getId($groupName){
		return self::get_id($groupName);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt alle Gruppen die in der Datenbank aufgelistet sind. */
	function get_all(){
		$root_ignorieren = true; // Wenn TRUE wird die Gruppe "root" nicht ausgegeben
		
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		
		$anfrage = "SELECT gruppenname, gruppeid FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."gruppen`";
			
		if($root_ignorieren){	
			$anfrage .= " WHERE gruppenname <> 'root'";
		}
		$anfrage .= ";";
		
		$resultat = mysql_query($anfrage);
		
		$gruppen = array();
		
		while($wert = mysql_fetch_array($resultat)){
			// "floor()" wird verwendet, dass $i sicher als Integer gehandhabt wird
			$gruppen[floor($i)]["gruppenname"] = $wert["gruppenname"];
			$gruppen[floor($i)]["gruppeid"] = $wert["gruppeid"];
			$i++;
		}
		return $gruppen;
	}
	
}