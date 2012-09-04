<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddInhalt" bietet verschiedene Methoden für das Erstellen und Bear-
beiten der Inhalte. */
class CunddInhalt{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddInhalt(){
		$this->edit($_POST);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "edit()" bearbeitet einen bestehenden Artikel. Vorher wird allerdings 
	überprüft ob ein Schlüssel übergeben wurde. Wenn nicht wird angenommen, dass es sich um 
	einen neuen Eintrag handelt und dieser erstellt. */
	function edit($eingabe){
		$say = true;
		
		/* Es wird überprüft ob das öffentliche Schreiben im System erlaubt ist. Wenn nicht 
		wird überprüft ob ein Benutzer eingeloggt ist. */
		if(CunddConfig::get(oeffentlich_schreiben)){
			if($_SESSION["benutzer"] AND $_SESSION["gruppen"]){
				$edit = true;
			}
		} else {
			$edit = true;
		}
		//*/
		
		if($edit){
			$felder = CunddFelder::get_eintrag();
			
			$eingabe["bearbeiter"] = $_SESSION["benutzer"].'+'.$eingabe["bearbeiter"];
			$eingabe["bearbeitungsdatum"] = date("Y-m-d");
			$eingabe["bearbeitungszeit"] = date("H:i");
			
			if($eingabe["eventdatum"]){ // If a eventdate was given parse and save it
			    // Parse the date with CunddTools::someDateToMySQLFormat()
				$eingabe["eventdatum"] = CunddTools::someDateToMySQLFormat($eingabe["eventdatum"]);
			} else { // Set the current date as the event date
			    $eingabe["eventdatum"] = date("Y-m-d");
			}
			
			/* ÜBerprüfen ob ein Schluessel übergeben wurde und wenn nicht einen neuen leeren 
			Eintrag in die MySQL-Tabelle einfüllen. */
			if(!$eingabe["schluessel"]){
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
				// Neuer Artikel
				
				/* Wenn "zeige_gruppe" nicht aktiv ist wird das Standard-Recht für neue Einträge 
				auf "6664" gesetzt. */
				if(!CunddConfig::get(zeige_gruppe)){
					$eingabe["rechte"] = 6664;
				} else if(!$eingabe["rechte"]){
					$eingabe["rechte"] = 6664;
				}
					
				
				// Leeren Eintrag erstellen
					$anfrage = "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".
							CunddConfig::get('prefix').$eingabe["tabelle"].
							"` () VALUES (";
					// Für jedes Feld
					for($i = 0; $i < count($felder[0]) - 1; $i++){
						$anfrage .= "'0', ";
					}
					if($i < count($felder[0])){
						$anfrage .= "'0'";
					}
					echo $anfrage .= ");";
					
					$resultat = mysql_query($anfrage);
				
					
				// Höchsten Schluessel in der Tabelle ermitteln
					mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
					CunddConfig::get('mysql_passwort'));
						
					echo $anfrage = "SELECT schluessel FROM `".CunddConfig::get('mysql_database')."`.`".
						CunddConfig::get('prefix').$eingabe["tabelle"].
						"` ORDER BY schluessel DESC LIMIT 0,1;";
					
					$resultat = mysql_query($anfrage);
					$wert = mysql_fetch_row($resultat);
					$eingabe["schluessel"] = $wert[0];
				
				
				// Werte setzen
				/* Wenn $_SESSION["benutzer"] leer ist wurde der Eintrag vermutlich zum 
				öffentlichen editieren zugänglich gemacht. */
				if($_SESSION["benutzer"]){				
					$eingabe["ersteller"] = $_SESSION["benutzer"];
					$eingabe["bearbeiter"] = $_SESSION["benutzer"];
					$eingabe["gruppe"] = $_SESSION["hauptgruppe"];
				} else {
					$eingabe["ersteller"] = "oeffentlich";
					$eingabe["bearbeiter"] = "oeffentlich";
					$eingabe["gruppe"] = 0;
				}
				
				if(!$eingabe["gruppe"]){
					$eingabe["gruppe"] = 1;
				}
				
				$eingabe["erstellungsdatum"] = date("Y-m-d");
				$eingabe["erstellungszeit"] = date("H:i");
				
				$eingabe["sprache"] = CunddLang::get_lang();
			}
			
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			// Nun die Werte eintragen
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),
				CunddConfig::get('mysql_passwort'));
		
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				$eingabe["tabelle"]."` SET ";
			// Jedes Element aus $felder an die MySQL-Anfrage anhängen
			for($i = 0; $i < count($felder[0]) - 1; $i++){
				/* Die Reihnfolge wird durch die Elemente in $felder bestimmt. Die entsprechenden 
				Daten werden aus dem assoziativen Array $eingabe abgerufen. */
				$name_des_elements = $felder[0][$i];
				$wert = $eingabe[$name_des_elements];
				
				if($wert OR $name_des_elements == 'bildlink'){
					$anfrage .= '`'.$name_des_elements.'`=';
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
				
				if($wert OR $name_des_elements == 'bildlink'){
					$anfrage .= '`'.$name_des_elements.'`=';
					// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
					if(is_string($wert)){
						$anfrage .= "'".$wert."' ";
					} else {
						$anfrage .= $wert." ";
					}
				}
			}
			$anfrage .= " WHERE schluessel='".$eingabe["schluessel"]."';";
			
			
			// DEBUGGEN
			if($say){
				echo '$anfrage = '.$anfrage;
				CunddTools::log("CunddInhalt",$anfrage);
			}
			// DEBUGGEN
			
			$resultat *= mysql_query($anfrage);
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode delete() setzt das Feld "geloescht" in der MySQL-Tabelle. Die Daten des 
	 * zu löschenden Eintrags können per Argument oder per $_POST übergeben werden. */
	function delete($table = NULL, $id = NULL){
		$say = true;
		
		/* Es wird überprüft ob das öffentliche Schreiben im System erlaubt ist. Wenn nicht 
		wird überprüft ob ein Benutzer eingeloggt ist und ein Schlüssel übergeben wurde. */
		if(CunddConfig::get('oeffentlich_schreiben') OR ($_SESSION["benutzer"] AND $_SESSION["gruppen"])){
			$entry = &$_POST;
			
			if($table){
				$entry["tabelle"] = $table;
			}
			if($id){
				$entry['schluessel'] = $id;
			}
			
			$entry['geloescht'] = date('Y-m-d');
			
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),
					CunddConfig::get('mysql_passwort'));
			
			// Eintrage aus vorheriger Tabelle entfernen
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				$entry["tabelle"]."` SET geloescht='".$entry['geloescht']."' WHERE schluessel=".$entry["schluessel"].";";
			
			$resultat = mysql_query($anfrage);
			if(!$resultat){
				// Überprüft ob bisher noch kein Fehler auftrat
				echo '<h3>DELETE-Fehler</h3>';
				echo '<pre>';
				echo $anfrage;
				echo '</pre>';
			}
			
			// DEBUGGEN
			if($say){
				echo '$anfrage='.$anfrage;
			}
			// DEBUGGEN
		}
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "loeschen()" kopiert den Inhalt eines Eintrags in die Tabelle 
	"geloeschte_eintraege" und löscht ihn aus der angegebenen Tabelle. */
	function loeschen(){
		$eingabe = $_POST;
		
		return CunddInhalt::delete();
		
		/* Es wird überprüft ob das öffentliche Schreiben im System erlaubt ist. Wenn nicht 
		wird überprüft ob ein Benutzer eingeloggt ist und ein Schlüssel übergeben wurde. *//*
		if(CunddConfig::get(oeffentlich_schreiben)){
			if($_SESSION["benutzer"] AND $_SESSION["gruppen"] AND $eingabe["schluessel"]){
				$loeschen_erlaubt = true;
			}
		} else {
			$loeschen_erlaubt = true;
		}
		
		
		if($loeschen_erlaubt){
			// ALTE METHODE 
			/*
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),
				CunddConfig::get('mysql_passwort'));
			
			// Daten in Tabelle geloeschte_eintraege" schreiben
				// Alte Daten auslesen
				$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".
					CunddConfig::get('prefix').$eingabe["tabelle"]."` WHERE schluessel=".$eingabe["schluessel"].
					";";
				$resultat = mysql_query($anfrage);
				$wert = mysql_fetch_array($resultat);
				
				// Schlüssel auf NULL setzen
				$wert["schluessel"] = 'NULL';
				
				$felder = CunddFelder::get_eintrag();
				
				$anfrage = "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"geloeschte_eintraege` VALUES(";
				for($i = 0; $i < count($felder[0]); $i++){
					/* Die Reihnfolge wird durch die Elemente in $felder bestimmt. Die entsprechenden 
					Daten werden aus dem assoziativen Array $eingabe abgerufen. *//*
					$aktueller_wert = $wert[$felder[0][$i]];
					// Wenn der aktuelle Wert leer ist NULL eintragen
					if(!$aktueller_wert){
						$anfrage .= "'0', ";
					} else {
						// Wenn der aktuelle Wert ein String ist wird er in Anführungszeichen eingeschlossen
						if(is_string($aktueller_wert)){
							$anfrage .= "'".$aktueller_wert."', ";
						} else {
							$anfrage .= $aktueller_wert.", ";
						}
					}
				}
				
				$anfrage .= "'".$eingabe["tabelle"]."');";
				
				if(!$resultat = mysql_query($anfrage)){ // Überprüft ob bisher noch kein Fehler auftrat
					echo '<h3>INSERT-Fehler</h3>';
					echo '<pre>';
					echo $anfrage;
					echo '</pre>';
				}
			
			// Löschender Benutzer eintragen
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"geloeschte_eintraege` SET bearbeiter = CONCAT(bearbeiter, ' - ','".$_SESSION["benutzer"].
				"') WHERE schluessel=".$eingabe["schluessel"].";";
			if($resultat AND !$resultat *= mysql_query($anfrage)){
				// Überprüft ob bisher noch kein Fehler auftrat
				echo '<h3>UPDATE-Fehler</h3>';
				echo '<pre>';
				echo $anfrage;
				echo '</pre>';
			}
			
			// Vorherige Tabelle eintragen
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"geloeschte_eintraege` SET tabelle = '".$eingabe["tabelle"]."' WHERE schluessel=".
				$eingabe["schluessel"].";";
			if($resultat AND !$resultat *= mysql_query($anfrage)){
				// Überprüft ob bisher noch kein Fehler auftrat
				echo '<h3>UPDATE-2-Fehler</h3>';
				echo '<pre>';
				echo $anfrage;
				echo '</pre>';
			}
			
			// Eintrage aus vorheriger Tabelle entfernen
			$anfrage = "DELETE FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				$eingabe["tabelle"]."` WHERE schluessel=".$eingabe["schluessel"].";";
			if($resultat AND !$resultat *= mysql_query($anfrage)){
				// Überprüft ob bisher noch kein Fehler auftrat
				echo '<h3>DELETE-Fehler</h3>';
				echo '<pre>';
				echo $anfrage;
				echo '</pre>';
			}
			
			
			
		}
		
		return $resultat;
		/* */
	}
}