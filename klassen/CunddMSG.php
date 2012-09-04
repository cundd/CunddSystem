<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddMSG" bietet verschiedene Methoden zum generieren und darstellen 
 System-interner Nachrichten. */
class CunddMSG{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $verion = 0.1;
	var $messages;
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Der Konstruktor liest die Nachrichten des jeweils eingeloggten Benutzers aus und 
	 stellt sie im Browser dar. */
	function CunddMSG(){
		$this->messages = $this->get_messages();
		$this->display_messages();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Inhalt der Nachrichten im Browser aus (= Detailansicht). */
	function msg_detail(){
		$message = CunddMSG::get_messages();
		$message_id = $_POST["msg_id"]; // gibt an welche Nachricht im Detail gezeigt wird
		
		for($i = 0; $i < count($message); $i++){
			if($message[$i]["schluessel"] == $message_id){
				$detail_nachricht = $message[$i];
				break;
			}
		}
		// Kopf-Zeile anzeigen
		$details = array('from','to','to_group','date','subject','content','attachment');
		if($details AND $message){
			// Zurück-Button erstellen
			echo CunddTemplate::inhalte_einrichten(NULL, NULL, "back_btn", "output");
			
			// Die Nachricht ausgeben
			for($i = 0; $i < count($details); $i++){
				$tag = "msg_detail_".$details[$i];
				echo CunddTemplate::inhalte_einrichten($detail_nachricht[$details[$i]], $recht, $tag, "output");
			}
			
			// Nachricht auf "gelesen" setzen, also den Wert für "date_read" eintragen
			CunddMSG::set_read($message_id);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt die Nachrichten im Browser aus. */
	function display_messages(){
		// Überprüfen ob ein Fehler aufgetreten ist
		if(!$this->messages){
			echo '<h1>die</h1>';
			die;
		}
		// Überprüfen ob es neue Nachrichten gibt
		if(!$this->messages[0][0]){
			// Keine neuen Nachrichten
			$tag = "msg_no_new_messages";
			
			echo CunddTemplate::inhalte_einrichten(CunddLang::get($tag), $recht, $tag, "output");
		} else {
			// Kopf der Tabelle schreiben
			$tag = "msg_new_message_preview_head";
			echo CunddTemplate::inhalte_einrichten(CunddLang::get($tag), $recht, $tag, "output");
			
			// Kopf-Zeile anzeigen
			//$kopf_zeile = array('from','to','to_group','date_read','date','subject','content','attachment','schluessel');
			$kopf_zeile = array('from','to','to_group','date_read','date','subject');
			if($kopf_zeile){
				echo '<tr>';
				for($i = 0; $i < count($kopf_zeile); $i++){
					echo '<th>'.$kopf_zeile[$i].'</th>';
				}
				echo '</tr>';
			}
			
			// Mail-Vorschau anzeigen
			for($i = 0; $i < count($this->messages); $i++){ // Für jede Nachricht eine Zeile
				// EventListener für jede Zeile
				echo '<tr id="'.$this->messages[$i]["schluessel"].'" name="'.$this->messages[$i]["schluessel"].
				'" onmouseover="new CunddMSG_js(this)" class="normal">';
				for($j = 0; $j < count($kopf_zeile); $j++){ // Für jedes Feld eine Spalte
					$aktueller_feldname = $kopf_zeile[$j];
					echo '<td>'.$this->messages[$i][$aktueller_feldname].'</td>';
				}
				echo '</tr>';
			}
			
			echo '</table>';
			
		}
									  
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die Nachrichten des jeweils eingeloggten Benutzers entsprechend 
	 dessen Namen und Gruppen aus. */
	function get_messages(){
		$say = false;
		/* Wenn ein Benutzer eingeloggt ist alle Einträge zeigen die an ihn oder eine Gruppe
		 adressiert sind, in der er Mitglied ist. Außerdem werden alle Nachrichten angezeigt, 
		 für die kein Empfänger und keine Empfänger-Gruppe definiert wurden. Diese Nachrichten 
		 sind für alle (eingeloggten) Benutzer sichtbar. */
		if($_SESSION["benutzer"]){
			// Mit MySQL-Datenbank verbinden
			mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
						  CunddConfig::get('mysql_passwort'));
			mysql_query("USE `".CunddConfig::get('mysql_database')."`;");
				
			// Überprüfen ob der Benutzer zur Gruppe "root" gehört
			$ist_root = floor($_SESSION["gruppen"] / pow(2,1)) % 2;
			if(!$ist_root){
				// Die grundsätzliche Anfrage
				$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"msg`";
				
				// Überprüfen ob eine Nachricht an alle Benutzer adressiert ist
				$anfrage .= " WHERE (`to` = '' AND `to_group` = '') ";
				
				// Überprüfen ob die Nachricht an den Benutzer adressiert ist
				$anfrage .= "OR (`to` LIKE '".$_SESSION["benutzer"].";%' OR `to` LIKE '%;".$_SESSION["benutzer"].";%') ";
				
				/* Alle Gruppen ermitteln zu denen der Benutzer gehört und überprüfen ob die Nachricht 
				an eine dieser Gruppen adressiert ist. */
				// mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
				$gruppen = CunddGruppen::get();
				
				for($i = 0; $i < count($gruppen); $i++){
					$anfrage .= "OR (`to_group` LIKE '".$gruppen[$i]["gruppeid"].";%' OR `to_group` LIKE '%;".$gruppen[$i]["gruppeid"].";%') ";
				}
			} else { // Wenn root
				// Die grundsätzliche Anfrage
				$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"msg`";
			}
				
			// Nach "schluessel" sortieren
			$anfrage .= " ORDER BY date DESC ";
			
			// Anfrage schließen
			$anfrage .= ";";
			
			
			// MySQL-Anfrage stellen
			$resultat = mysql_query($anfrage);
		}
		
		// DEBUGGEN
		if($say){
			echo '$anfrage='.$anfrage.'<br />';
			echo '$resultat='.$resultat.'<br />';
		}
		// DEBUGGEN------------------------------------------
		
		// Überprüfen ob MySQL eine Antwort geliefert hat
		if($resultat){
			$nachrichten = array();
			$i = 0;
			// $resultat in Array speichern
			while($nachrichten[$i] = mysql_fetch_array($resultat)){
				$i++;
			}
			
			// Ergebnisse zurückgeben
			return $nachrichten;
		} else {
			// Fehlermeldung ausgeben
			echo 'Beim Auslesen der Nachrichten ist ein Fehler aufgetreten. <br />Benutzer:'.
			$_SESSION["benutzer"].', in der Gruppe:'.$_SESSION[gruppen].'.';
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Wert für "date_read" in der MySQL-Tabelle und markiert die 
	 Nachricht somit als gelesen. */
	function set_read($schluessel){
		$say = false;
		mysql_connect(CunddConfig::get("mysql_host"), CunddConfig::get("mysql_benutzer"), 
					  CunddConfig::get("mysql_passwort"));
		
		$anfrage = "UPDATE `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix').
			"msg` SET date_read='".date("Y-m-d")."' WHERE schluessel='".$schluessel."';";
		
		$resultat = mysql_query($anfrage);
		
		// DEBUGGEN
		if($say){
			echo "anfrage: ".$anfrage."<br />
				resultat: ".$resultat."<br />
				schluessel: ".$schluessel."<br />";
		}
		// DEBUGGEN------------------------------------------
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode sendet eine email mit den übergebenen Parametern. */
	public function send_message_as_email($sender, $receiver, $subject, $message, $attachement = NULL){
		$say = false;
		
		// Die ini-Werte aus der Konfigurationsdatei auslesen
		$iniVars = array('mail.add_x_header','mail.log','SMTP','smtp_port','sendmail_from','sendmail_path');
		foreach($iniVars as $iniVar){
			$iniVarConfigValue = CunddConfig::get($iniVar);
			if($iniVarConfigValue){
				ini_set($iniVar, $iniVarConfigValue);
			}
		}
		
		if(gettype($receiver) == "array"){
			foreach($receiver as $currentReceiver){
				mail($currentReceiver, $subject, $message);
			}
		} else {
			mail($receiver, $subject, $message);
		}
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine neue Nachricht mit den übergebenen Parametern. */
	public function new_msg($sender, $receiver, $subject, $message, $attachement = NULL, $receiverGroup = NULL){
		$say = false;
		
		mysql_connect(CunddConfig::get("mysql_host"), CunddConfig::get("mysql_benutzer"), 
			CunddConfig::get("mysql_passwort"));
		
		$anfrage = "INSERT INTO `".CunddConfig::get("mysql_database")."`.`".
			CunddConfig::get('prefix')."msg` () VALUES (";
		
		// Absender = "from"
		$anfrage .= "'".$sender."',";
		
		
		// Empfänger = "to"
		if(gettype($receiver) == "array"){
			// String beginnen
			$anfrage .= "'";
			for($i = 0; $i < count($receiver); $i++){
				$anfrage .= $receiver[$i].";";
			}
		} else {
			$anfrage .= "'".$receiver;
		}
		$anfrage .= "',";
		
		
		// Empfänger-Gruppen = "to_group"
		if(gettype($receiverGroup) == "array"){
			// String beginnen
			$anfrage .= "'";
			for($i = 0; $i < count($receiverGroup); $i ++){
				$anfrage .= $receiverGroup[$i].";";
			}
		} else {
			$anfrage .= "'".$receiverGroup;
		}
		$anfrage .= "',";
		
		
		// date_read
		$anfrage .= "NULL,";
		
		
		// date
		$anfrage .= "'".date("Y-m-d")."',";
		
		
		// subject
		$anfrage .= "'".$subject."',";
		
		
		// content
		$anfrage .= "'".$message."',";
		
		
		// attachement
		$anfrage .= "'".$attachement."',";
		
		
		// schluessel
		$anfrage .= "NULL);";
		
		
		
		// MySQL-Anfrage senden
		$resultat = mysql_query($anfrage);
		
		
		// DEBUGGEN
		if($say){
			echo "CunddMSG<br />
			MySQL-Anfrage: '".$anfrage."'<br />
			Resultat: '".$resultat."'<br />";
		}
		// DEBUGGEN------------------------------------------
		
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Bereich für vom System versendete und erstellte Nachrichten
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "neuer_benutzer" sendet eine Nachricht an alle angegebenen Gruppen und
	Benutzer, in der die Aktivierung des übergebenen Benutzers gewünscht wird. */
	function neuer_benutzer($benutzername){
		// Ein Array mit den allen Gruppen-IDs, die die Nachricht erhalten sollen
		$empfaenger_gruppen = array(1,2);
		// Ein Array mit den Namen bestimmter Benutzer die die Nachricht erhalten sollen
		//$empfaenger = array();
		/* $say gibt an ob das Skript die MySQL-Anfrage und das Resultat im Browser ausgeben
		soll. */
		$say = false;
		
		// Die automatische Nachricht auslesen
		$nachricht = CunddLang::get("msg_new_user",$benutzername);
		
		mysql_connect(CunddConfig::get("mysql_host"), CunddConfig::get("mysql_benutzer"), 
			CunddConfig::get("mysql_passwort"));
		
		$anfrage = "INSERT INTO `".CunddConfig::get("mysql_database")."`.`".
			CunddConfig::get('prefix')."msg` () VALUES (";
		// Absender = "from"
		$anfrage .= "'CunddSystem',";
		
		// Empfänger = "to"
		if($empfaenger){
			// String beginnen
			$anfrage .= "'";
			for($i = 0; $i < count($emfpaenger); $i++){
				$anfrage .= $empfaenger[$i].";";
			}
		} else {
			$anfrage .= "'";
		}
		$anfrage .= "',";
		
		// Empfänger-Gruppen = "to_group"
		if($empfaenger_gruppen){
			// String beginnen
			$anfrage .= "'";
			for($i = 0; $i < count($empfaenger_gruppen); $i ++){
				$anfrage .= $empfaenger_gruppen[$i].";";
			}
		} else {
			$anfrage .= "'";
		}
		$anfrage .= "',";
		
		// date_read
		$anfrage .= "NULL,";
		
		// date
		$anfrage .= "'".date("Y-m-d")."',";
		
		// subject
		$anfrage .= "'".CunddLang::get("msg_new_user_subject")."',";
		
		// content
		$anfrage .= "'".$nachricht."',";
		
		// attachement
		$anfrage .= "'',";
		
		// schluessel
		$anfrage .= "NULL);";
		
		
		
		// MySQL-Anfrage senden
		$resultat = mysql_query($anfrage);
		
		
		// DEBUGGEN
		if($say){
			echo "CunddMSG<br />
			MySQL-Anfrage: '".$anfrage."'<br />
			Resultat: '".$resultat."'<br />";
		}
		// DEBUGGEN------------------------------------------
		
		
		return $resultat;
	}
}
?>