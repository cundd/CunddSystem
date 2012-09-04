<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddFelder" bietet Methoden zur Verwaltung der verschiedenen Informa-
tions-Felder im System. Dazu gehören die Felder (MySQL-Spalten) der einzelnen Einträge, 
aber auch die Felder der Informationen über die Benutzer. */
class CunddFelder{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "get" liest die anzuzeigenden Felder und deren Einstellungen für den an-
	gegebenen Benutzer aus der MySQL-Tabelle aus und gibt diese Daten in einem mehr-
	dimensionalen Array zurück. */
	function get_benutzer($gruppe){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
		
		// Tabelle "benutzer_verwaltung_sichtbarkeit" auslesen
		$anfrage = "SELECT feld, type, ".$gruppe." FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."benutzer_verwaltung_sichtbarkeit` WHERE ".$gruppe." > 0;";
		$resultat = mysql_query($anfrage);
		
		// Ergebnis in Array-speichern
		$felder_namen = array();
		$felder_rechte = array();
		$felder_type = array();
		$i = 0;
		while($wert = mysql_fetch_row($resultat)){
			// Feld-Name auslesen
			$felder_namen[$i] = $wert[0];
			
			// Type auslesen
			$felder_type[$i] = $wert[1];
			
			// Die zur Gruppe gehörenden Rechte speichern
			$felder_rechte[$i] = $wert[2];
			
			$i++;
		}
		
		$felder = array($felder_namen, $felder_rechte);
		
		return $felder;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode "get_eintrag()" liest die Felder die für einen Eintrag angezeigt werden 
	 * können aus der MySQL-Tabelle "eintrag_sichtbarkeit" und gibt diese in einem mehr-
	 * dimensionalen Array zurück. Die erste Dimension beschreibt den Namen des Feldes, die 
	 * zweite die Art des Out/Input-Typs. */
	function get_eintrag(){
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
		
		// Tabelle "eintrag_sichtbarkeit" auslesen
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
			CunddConfig::get('prefix')."eintrag_sichtbarkeit`;";
		$resultat = mysql_query($anfrage);
		
		// Ergebnis in Array-speichern
		$felder_namen = array();
		$felder_einstellungen = array();
		$felder_typen = array();
		$i = 0;
		while($wert = mysql_fetch_row($resultat)){
			// Feld-Name auslesen
			$felder_namen[$i] = $wert[0];
			
			// Die MySQL-Einstellungen auslesen
			$felder_einstellungen[$i] = $wert[1];
			
			// Den Feld-Typ auslesen
			$felder_typen[$i] = $wert[2];
			
			$i++;
		}
		
		$felder = array($felder_namen, $felder_einstellungen, $felder_typen);
		
		return $felder;
	}
	
	

	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "install_get_benutzer_verwaltung_sichtbarkeit" dient als statische 
	Methode zum Installieren der Felder aller Informationen die über einen Benutzer ge-
	speichert werden. Die Methode "install_get_benutzer_verwaltung_sichtbarkeit" gibt ein 
	mehrdimensionales Array zurück, welches in der ersten Dimension die Namen der Felder, in 
	der zweiten die MySQL-Einstellungen und in der dritten die Einstellungen über Out/Input-
	Funktionen enthält. */
	function install_get_benutzer_verwaltung_sichtbarkeit(){
		// Dieses Array enthält die Felder der Benutzer-Informationen
		$felder_namen = array(
		  "benutzer",
		  "passwort",
		  "passwort wiederholen",
		  "anrede",
		  "vorname",
		  "nachname",
		  "firma",
		  "abteilung",
		  "email",
		  "telefon",
		  "handy",
		  "adresse",
		  "plz",
		  "ort",
		  "staat",
		  "lang",
		  "geburtstag",
		  "homepage",
		  "chat",
		  "bildlink",
		  "aktiv",
		  "hauptgruppe",
		  "gruppen",
		  "anzahl_eintraege",
		  "ersteller",
		  "erstellungsdatum",
		  "bearbeiter",
		  "bearbeitungsdatum",
		  "attribute",
		  "schluessel"
		);
		
		
		// Dieses Array enhält die zugehörigen MySQL-Einstellungen
		$felder_einstellungen = array(
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TINYTEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT COMMENT 'strasse und hausnummer'",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "TEXT",
		  "DATE",
		  "TEXT",
		  "TEXT COMMENT 'ein assoziatives array mit den daten verschiedener chat-clients'",
		  "TEXT COMMENT 'der eindeutige dateiname des bildes das zu diesem benutzer gehoert also der avatar'",
		  "INT DEFAULT 1 COMMENT 'gibt an ob der benutzer freigegeben also aktiv ist oder in einer warteschlange'",
		  "BIGINT UNSIGNED",
		  "BIGINT UNSIGNED",
		  "BIGINT UNSIGNED",
		  "TEXT",
		  "DATE",
		  "TEXT",
		  "DATE",
		  "TEXT",
		  "INT NOT NULL AUTO_INCREMENT"
		);
		
		
		/* Dieses Array enthält die Zuordnung der Felder zu den Out/Input-Typen d.h.
		ob das Feld beschrieben werden kann oder nicht, etc. */
		$felder_typen = array(
			"text",
			"password",
			"password",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"text",
			"bild",
			"checkbox",
			"select",
			"select multiple",
			"output",
			"output",
			"output",
			"output",
			"output",
			"output",
			"special",
			"output"
		);
		
		
		/* Dieses Array enthält die Einstellungen welche Felder zwingend benötigt werden. */
		$felder_required = array(
		  1,
		  1,
		  1,
		  0,
		  1,
		  1,
		  0,
		  0,
		  1,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0,
		  0
		);
		
		
		// Die drei Arrays in einem Array vereinen
		$felder = array($felder_namen, $felder_einstellungen, $felder_typen, $felder_required);
		
		return $felder;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode füllt die in der Methode "install_get" angegebenen Felder in die MySQL-
	Liste der Felder ("benutzer_verwaltung_sichtbarkeit") ein. */
	function install_benutzer_verwaltung_sichtbarkeit($aufrufer){
		// Die Methode muss vom Installationsskript aufgerufen werden
		if($aufrufer == "installer_skript"){
			$felder = CunddFelder::install_get_benutzer_verwaltung_sichtbarkeit();
			
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			// Tabelle "benutzer_verwaltung_sichtbarkeit" ausfüllen
			$anfrage = "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` (feld, type, required) VALUES ";
			for($i = 0; $i < count($felder[0]) - 1; $i++){
				$anfrage .= "('".$felder[0][$i]."', '".$felder[2][$i]."', '".$felder[3][$i]."'), ";
			}
			if(count($felder[0])){
				$anfrage .= "('".$felder[0][$i++]."', '".$felder[2][$i]."', '".$felder[3][$i]."')";
			}
			$anfrage .= ";";
			
			$resultat = mysql_query($anfrage);
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode füllt die für einen Eintrag angegebenen Felder in die MySQL-Tabelle der 
	Felder ("eintrag_sichtbarkeit") ein. */
	function install_eintrag_sichtbarkeit($aufrufer){
		$drop_table = true; // Gibt an ob die Tabelle, falls vorhanden, gelöscht werden soll
		
		// Die Methode muss vom Installationsskript aufgerufen werden
		if($aufrufer == "installer_skript"){
			// Die Namen der Felder
			$felder_namen = array(
								"title",
								"ersteller",
								"erstellungsdatum",
								"erstellungszeit",
								"bearbeiter",
								"bearbeitungsdatum",
								"bearbeitungszeit",
								"eventdatum",
								"subtitle",
								"beschreibung",
								"text",
								"bildlink",
								"rechte",
								"gruppe",
								"geloescht",
								"lang",
								"attribute",
								"schluessel"
								);
			
			// Die MySQL-Einstellungen
			$felder_einstellungen = array(
								"TEXT",
								"TEXT",
								"DATE",
								"TIME",
								"TEXT",
								"DATE",
								"TIME",
								"DATE",
								"TEXT",
								"TEXT",
								"TEXT",
								"TEXT",
								"INT(4) NOT NULL DEFAULT 0",
								"INT NOT NULL DEFAULT 0",
								"DATE NOT NULL",
								"CHAR(2)",
								"TEXT",
								"INT NOT NULL AUTO_INCREMENT"
								);
			
			// Die Out/Input-Typen der Felder
			$felder_typen = array(
								"textarea",
								"output",
								"output",
								"output",
								"output",
								"output",
								"output",
								"text",
								"textarea",
								"textarea",
								"rte",
								"spezial",
								"spezial",
								"output",
								"output",
								"text",
								"output",
								"output"
								);
			
			// Die Felder in einem Array vereinen
			$felder = array($felder_namen, $felder_einstellungen, $felder_typen);
			
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
			
			// Tabelle "eintrag_sichtbarkeit" erstellen
			if($drop_table){
				$anfrage = "DROP TABLE IF EXISTS `".CunddConfig::get('mysql_database')."`.`".
					CunddConfig::get('prefix')."eintrag_sichtbarkeit`; ";
				mysql_query($anfrage);
			}
			
			$anfrage = "CREATE TABLE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"eintrag_sichtbarkeit` (
								`feld` TEXT,
								`einstellungen` TEXT,
								`type` TEXT, 
								`schluessel` INT NOT NULL AUTO_INCREMENT, 
								PRIMARY KEY (`schluessel`)
								)
								CHARACTER SET utf8;";
			
			$resultat = mysql_query($anfrage);
			
			
			// Tabelle "eintrag_sichtbarkeit" einfüllen
			$anfrage = "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"eintrag_sichtbarkeit` () VALUES ";
			for($i = 0; $i < count($felder[0]) - 1; $i++){
				$anfrage .= "('".$felder[0][$i]."', ";
				$anfrage .= "'".$felder[1][$i]."', ";
				$anfrage .= "'".$felder[2][$i]."', NULL), ";
			}
			if(count($felder[0])){
				$anfrage .= "('".$felder[0][$i]."', ";
				$anfrage .= "'".$felder[1][$i]."', ";
				$anfrage .= "'".$felder[2][$i]."', NULL)";
			}
			$anfrage .= ";";
			
			$resultat *= mysql_query($anfrage);
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Spalten für die Link-MySQL-Tabelle und die entsprechenden MySQL-
	Einstellungen zurück. */
	function get_link(){
		// Die Namen der Felder
		$felder_namen = array(
							"name",
							"aktiv",
							"link",
							"parent",
							"rechte",
							"gruppe",
							"ersteller",
							"erstellungsdatum",
							"erstellungszeit",
							"bearbeiter",
							"bearbeitungsdatum",
							"bearbeitungszeit",
							"prioritaet",
							"lang",
							"schluessel"
							);
		
		// Die MySQL-Einstellungen
		$felder_einstellungen = array(
									"TEXT",
									"INT DEFAULT 0",
									"TEXT",
									"INT UNSIGNED DEFAULT 0",
									"INT(4)",
									"INT UNSIGNED",
									"TEXT",
									"DATE",
									"TIME",
									"TEXT",
									"DATE",
									"TIME",
									"INT UNSIGNED",
									"CHAR(2)",
									"INT NOT NULL AUTO_INCREMENT"
									);
		
		
		
		// Die Felder in einem Array vereinen
		$felder = array($felder_namen, $felder_einstellungen);
		
		return $felder;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "get_files" dient als statische Methode und gibt die MySQL-Felder in der 
	 Tabelle "_files" in einem mehrdimensionalen Array zurück. */
	function get_files(){
		// Namen der Felder
		$name = array('title', 
					 'dateiname', 
					 'originalname', 
					 'parent',
					 'beschreibung', 
					 'tags', 
					 'copyright', 
					 'type', 
					 'size', 
					 'ersteller', 
					 'erstellungsdatum', 
					 'erstellungszeit', 
					 'bearbeiter', 
					 'bearbeitungsdatum', 
					 'bearbeitungszeit', 
					 'rechte', 
					 'gruppe', 
					 'geloescht',
					 'attribute'
					 );
		
		// MySQL-Einstellungen
		$einstellungen = array('TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'DATE',
							   'TIME',
							   'TEXT',
							   'DATE',
							   'TIME',
							   'INT(4)',
							   'INT',
							   'DATE', 
							   'TEXT'
								 );
		$type = array('text', 
					  'output', 
					  'output', 
					  'spezial',
					  'text', 
					  'text', 
					  'text', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'spezial', 
					  'output', 
					  'output',
					  'output'
					  );
		
		// Daten in das Array $files_felder schreiben
		$files_felder = array();
		for($i=0; $i < count($name); $i++){
			$files_felder[$i]["name"] = $name[$i];
			$files_felder[$i]["einstellungen"] = $einstellungen[$i];
			$files_felder[$i]["type"] = $type[$i];
		}
		
		return $files_felder;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "get_files_group" dient als statische Methode und gibt die Felder für das 
	 Erstellen einer neuen File-Gruppe in einem mehrdimensionalen Array zurück. */
	function get_files_group(){
		// Namen der Felder
		$name = array('title', 
					  'dateiname', 
					  'originalname', 
					  'parent',
					  'beschreibung', 
					  'tags', 
					  'copyright', 
					  'type', 
					  'size', 
					  'ersteller', 
					  'erstellungsdatum', 
					  'erstellungszeit', 
					  'bearbeiter', 
					  'bearbeitungsdatum', 
					  'bearbeitungszeit', 
					  'rechte', 
					  'gruppe', 
					  'geloescht',
					  'attribute'
					  );
		
		// MySQL-Einstellungen
		$einstellungen = array('TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'TEXT',
							   'DATE',
							   'TIME',
							   'TEXT',
							   'DATE',
							   'TIME',
							   'INT(4)',
							   'INT',
							   'DATE', 
							   'TEXT'
							   );
		$type = array('text', 
					  'output', 
					  'output', 
					  'spezial',
					  'text', 
					  'text', 
					  'text', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'output', 
					  'spezial', 
					  'output', 
					  'output',
					  'output'
					  );
		
		// Daten in das Array $files_felder schreiben
		$files_felder = array();
		for($i=0; $i < count($name); $i++){
			$files_felder[$i]["name"] = $name[$i];
			$files_felder[$i]["einstellungen"] = $einstellungen[$i];
			$files_felder[$i]["type"] = $type[$i];
		}
		
		return $files_felder;
	}
}
?>