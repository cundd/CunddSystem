<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddInstall" führt ein kurzes Installations-Skript aus. In einem 
Formular werden die MySQL-Zugangsdaten eingetragen und in die Datei "./admin/server.php" 
geschrieben. Außerdem wird in dem Skript der erste Benutzer angelegt. */
class CunddInstall{
    public $version = 1.1;
    
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	function install_rechte(){
		// Überprüfen ob die Admin-Datei bereits besteht und beschreibbar ist
		$admin_dateiname = "server.php";
		$ordner = "../";
		
		// Überprüft ob der Ordner beschreibbar ist
		if(is_writable($ordner)){
			// Überprüft ob die Datei existiert und beschreibbar ist
			if(is_writable($ordner.$admin_dateiname)){
				$datei_ok = true;
			} else {
				// Erzeugt eine leere Datei
				fopen($ordner.$admin_dateiname, w);
				$datei_ok = true;
			}
		}
		
		if($datei_ok){
			echo 'Die Datei kann beschrieben werden. <a href="install_formular.php" target="_self">
					Weiter</a>';
		} else {
			echo 'Die Datei kann nicht beschrieben werden. &Uuml;berpr&uuml;fen Sie bitte Ihre 
			Ordner-Rechte. <a href="'.$_SERVER['PHP_SELF'].'">Erneut versuchen</a>';
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	function formular(){
		$formular_nicht_anzeigen = false;
		//require_once(dirname(__FILE__).'/../../CunddConfig.php');
		set_error_handler(array('CunddInstall','error'));
		spl_autoload_register(array('CunddInstall','autoLoader'));
		
		
//		if($_POST["mysql_host"] AND $_POST["mysql_benutzer"] AND $_POST["mysql_passwort"] AND 
//		$_POST["mysql_database"]){
		if(array_key_exists("mysql_host",$_POST) AND array_key_exists("mysql_benutzer",$_POST) AND 
		array_key_exists("mysql_passwort",$_POST) AND array_key_exists("mysql_database",$_POST)){
			// Überprüfen ob die Verbindung hergestellt werden kann
			if(@mysql_connect($_POST["mysql_host"], $_POST["mysql_benutzer"], $_POST["mysql_passwort"])){
				// Überprüfen ob die angegebene Datenbank existiert
				if(mysql_query("USE `".$_POST["mysql_database"]."`;")){
					// Installationsskripts ausführen
					$server_php_schreiben = CunddInstall::server_php_schreiben($_POST);
					$benutzer_tabelle_erstellen = CunddInstall::benutzer_tabelle_erstellen($_POST);
					$benutzer_verwaltung_sichtbarkeit_tabelle_erstellen = 
						CunddInstall::benutzer_verwaltung_sichtbarkeit_tabelle_erstellen($_POST);
					$gruppen_tabelle_erstellen = CunddInstall::gruppen_tabelle_erstellen($_POST);
					$gruppen_erstellen = CunddInstall::gruppen_erstellen($_POST);
					$benutzer_erstellen = CunddInstall::benutzer_erstellen($_POST);
					$eintrag_sichtbarkeit = CunddInstall::eintrag_sichtbarkeit();
					$geloescht_tabelle_erstellen = CunddInstall::geloescht_tabelle_erstellen();
					$msg_tabelle_erstellen = CunddInstall::msg_tabelle_erstellen();
					$files_tabelle_erstellen = CunddInstall::files_tabelle_erstellen();
					$content_tabelle_erstellen = CunddInstall::content_tabelle_erstellen();
					
					// Skript-Erfolg ausgeben
					echo '<h2>Installations Fortschritt</h2>';
					echo '<hr><p class="klein">Die Server-Zugangsdaten schreiben: ';
					if($server_php_schreiben){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Benutzertabellen erstellen: ';
					if($benutzer_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Benutzer-Feld-Tabelle erstellen: ';
					if($benutzer_verwaltung_sichtbarkeit_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Gruppen-Tabelle erstellen: ';
					if($gruppen_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die Gruppen Eintragen: ';
					if($gruppen_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Den Standard-Benutzer eintragen: ';
					if($benutzer_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Tabelle für die Eintrags-Informationen 
						erstellen und ausf&uuml;llen: ';
					if($eintrag_sichtbarkeit){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Tabelle f&uuml;r gel&ouml;schte 
						Eintr&auml;ge erstellen: ';
					if($geloescht_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Tabelle f&uuml;r System-interne 
					Nachrichten erstellen: ';
					if($msg_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Tabelle f&uuml;r hochgeladene
					Files erstellen: ';
					if($files_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><p class="klein">Die MySQL-Tabelle f&uuml;r Seiten-Inhalte
					erstellen: ';
					if($content_tabelle_erstellen){
						echo 'erledigt 	&radic;';
					} else {
						echo 'Fehler :-(';
					}
					echo '</p><hr><br />';
					
					
					// Überprüfen ob Alles richig installiert wurde
					if($benutzer_tabelle_erstellen AND $benutzer_verwaltung_sichtbarkeit_tabelle_erstellen 
						AND $gruppen_tabelle_erstellen AND $gruppen_erstellen AND $benutzer_erstellen){
						echo '<h2>Herzlichen Gl&uuml;ckwunsch</h2>';
						echo '<p>Die Einstellungen wurden erfolgreich gespeichert.</p>';
						echo '<p>Ein Administrator-Benutzer wurde mit dem Benutzernamen und dem Passwort 
							aus den MySQL-Einstellungen angelegt. Sie k&ouml;nnen sich mit diesen Daten 
							in Ihren CunddBlog einloggen.</p>';
						$formular_nicht_anzeigen = true;
					} else {
						echo '<h2>FEHLER</h2>';
						echo '<p>Bei der Installation ist ein Fehler aufgetreten. Bitte wiederholen Sie 
							den Installationsvorgang.';
					}
					
					
					
				} else {
					echo '<p>Bitte &uuml;berpr&uuml;fen Sie die Angabe der MySQL-Datenbank!</p>';
				}
			} else {
				echo '<p>Bitte &uuml;berpr&uuml;fen Sie Ihre Angaben!</p>';
			}
		}
		
		if(!$formular_nicht_anzeigen){
			echo '<form action="install_formular.php" method="post">
					<p>MySQL-Server:<div class="klein">Geben Sie hier die Adresse des MySQL-Servers ein. 
						In den meisten Fällen dürfte die Angabe "localhost" richtig sein.</div>
					<input type="text" name="mysql_host" value="';
			if($_POST["mysql_host"]){
				echo $_POST["mysql_host"];
			}else{
				echo "localhost";
			}
			echo '" /></p>
					
					<p>MySQL-Benutzername:<div class="klein">Geben Sie hier Ihren Benutzernamen zum 
						Zugriff auf den MySQL-Servers ein.</div>
					<input type="text" name="mysql_benutzer" value="'.$_POST["mysql_benutzer"].'" /></p>
					
					<p>MySQL-Passwort:<div class="klein">Geben Sie hier das Passwort zu Ihrem MySQL-Server 
						ein.</div>
					<input type="password" name="mysql_passwort" value="'.$_POST["mysql_passwort"].'" /></p>
					
					<p>MySQL-Datenbank:<div class="klein">Geben Sie hier den Namen der Datenbank an, in 
						der CunddBlog die ben&ouml;tigten Tabellen schreiben soll.</div>
					<input type="text" name="mysql_database" value="'.$_POST["mysql_database"].'" /></p>
					
					<p>MySQL-Tabellen-Prefix:<div class="klein">Geben Sie hier eine Zeichenkette an mit 
						der die, von CunddBlog erstellten Tabellen in der MySQL-Datenbank beginnen 
						sollen.</div>
					<input type="text" name="mysql_prefix" value="';
			if($_POST["mysql_prefix"]){
				echo $_POST["mysql_prefix"];
			}else{
				echo "Cundd_";
			}
			echo '" /></p>
					<input type="submit" value="Eingabe pr&uuml;fen" />
				</form>';
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle für das Speichern der Benutzer und deren Daten
	Dazu wird die Datei "CunddBenutzerInfoFelder" benötigt in welcher alle Felder in einem 
	Array aufgelistet sind. */
	function benutzer_tabelle_erstellen($eingabe){
		$currentPath = dirname(__FILE__).'/';
		require_once($currentPath."../../CunddConfig.php");
		require_once($currentPath."../../klassen/CunddTools.php");
		require_once($currentPath."../../klassen/CunddUser.php");
		require_once($currentPath."../../klassen/CunddFelder.php");
		$drop_table = false; // Gibt an ob die Tabelle, falls sie existiert, gelöscht werden soll
		
		mysql_connect($eingabe["mysql_host"], $eingabe["mysql_benutzer"], $eingabe["mysql_passwort"]);
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".$eingabe["mysql_database"]."`.`".
				CunddConfig::get('prefix')."benutzer`; ";
			mysql_query($anfrage);
		}
		
		// Die zu erstellenden Felder abrufen
		$felder = CunddFelder::install_get_benutzer_verwaltung_sichtbarkeit();
		
		$anfrage = "CREATE TABLE IF NOT EXISTS `".$eingabe["mysql_database"]."`.`".CunddConfig::get('prefix')."benutzer` (";
		
		// Für jedes Feld aus "CunddBenutzerInfoFelder" den String an die MySQL-Anfrage anhängen
		for($i = 0; $i < count($felder[0]); $i++){
			$anfrage .= "`".$felder[0][$i]."` ".$felder[1][$i].", ";
		}
		
		
		// Die Spalte "schluessel" erstellen
		$anfrage .= //"`schluessel` INT NOT NULL AUTO_INCREMENT,
			"PRIMARY KEY ( `schluessel`)
			)
			CHARACTER SET utf8;";

		$resultat = mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle für die Einstellungen der Sichtbarkeit der 
	Felder für die Benutzerverwaltung für die verschiedenen Gruppen. */
	function benutzer_verwaltung_sichtbarkeit_tabelle_erstellen($eingabe){
		$drop_table = true; // Gibt an ob die Tabelle, falls vorhanden, gelöscht werden soll
		
		mysql_connect($eingabe["mysql_host"], $eingabe["mysql_benutzer"], $eingabe["mysql_passwort"]);
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".$eingabe["mysql_database"]."`.`".CunddConfig::get('prefix')."benutzer_verwaltung_sichtbarkeit`; ";
			mysql_query($anfrage);
		}
		
		$anfrage = "CREATE TABLE IF NOT EXISTS `".$eingabe["mysql_database"]."`.`".CunddConfig::get('prefix')."benutzer_verwaltung_sichtbarkeit` (
			`feld` TEXT NOT NULL COMMENT 'gibt das feld an',
			`type` TEXT,
			`required` INT DEFAULT 0,
			`schluessel` INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (`schluessel`)
			)
			CHARACTER SET utf8;";
		
		$resultat = mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle für die Einstellungen der Sichtbarkeit der 
	Felder für die Benutzerverwaltung für die verschiedenen Gruppen. */
	function gruppen_tabelle_erstellen($eingabe){
		$drop_table = true; // Gibt an ob die Tabelle, falls vorhanden, gelöscht werden soll
		
		mysql_connect($eingabe["mysql_host"], $eingabe["mysql_benutzer"], $eingabe["mysql_passwort"]);
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".$eingabe["mysql_database"]."`.`".CunddConfig::get('prefix')."gruppen`; ";
			mysql_query($anfrage);
		}
		
		$anfrage = "CREATE TABLE  IF NOT EXISTS`".$eingabe["mysql_database"]."`.`".CunddConfig::get('prefix')."gruppen` (
			`gruppenname` TEXT NOT NULL COMMENT 'gibt den name der gruppe an',
			`ersteller` TEXT,
			`erstellungsdatum` DATE,
			`bearbeiter` TEXT,
			`bearbeitungsdatum` DATE,
			`gruppeid` INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (`gruppeid`)
			)
			CHARACTER SET utf8; ";
		
		$resultat = mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ruft das Skript zum erstellen einer neuen Gruppe für jede der 
	Standard-Gruppen auf. Die Standard-Gruppen sind "root", "verwalter" und "editor". */
	function gruppen_erstellen($eingabe){
		// Die Bibliothek zum Verwalten der Gruppen einbinden
		$currentPath = dirname(__FILE__).'/';
		require_once($currentPath."../../klassen/CunddUser.php");
		require_once($currentPath."../../klassen/CunddGruppen.php");
		//array_push($eingabe, "benutzer" => "install_skript");
		$eingabe["benutzer"] = "install_skript";
		
		// "root" eintragen
		//array_push($eingabe, "gruppen_name" => "root");
		$eingabe["gruppen_name"] = "root";
		$root = CunddGruppen::neu($eingabe);
		
		// "verwalter" eintragen
		$eingabe["gruppen_name"] = "verwalter";
		$verwalter = CunddGruppen::neu($eingabe);
		
		// "editor" eintragen
		$eingabe["gruppen_name"] = "editor";
		$editor = CunddGruppen::neu($eingabe);
		
		// "oeffentlich" eintragen
		$eingabe["gruppen_name"] = "oeffentlich";
		$editor = CunddGruppen::neu($eingabe);
		
		
		// Die Standard-Sichtbarkeiten der Benutzer-Info-Felder setzen
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),
				CunddConfig::get('mysql_passwort'));
			
			// Den Default-Wert von "root" auf "7" setzen
			$anfrage = "ALTER TABLE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` MODIFY COLUMN `root` INTEGER NOT NULL DEFAULT 7;";
			$resultat = mysql_query($anfrage);
			
			
			// Den Default-Wert von "verwalter" auf "7" setzen
			$anfrage = "ALTER TABLE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` MODIFY COLUMN `verwalter` INTEGER NOT NULL DEFAULT 7;";
			$resultat *= mysql_query($anfrage);
			
			
			// Den Default-Wert von "oeffentlich" auf "4" setzen
			$anfrage = "ALTER TABLE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` MODIFY COLUMN `oeffentlich` INTEGER NOT NULL DEFAULT 4;";
			$resultat *= mysql_query($anfrage);
			
			
			// Benutzer-Info-Felder eintragen	
			$sichtbarkeit = CunddFelder::install_benutzer_verwaltung_sichtbarkeit("installer_skript");
			
			
			// Die Standard-Werte für "editor" setzen
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` SET editor=7 WHERE schluessel < 21;";
			$resultat *= mysql_query($anfrage);
				
			$anfrage = "UPDATE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"benutzer_verwaltung_sichtbarkeit` SET editor=4 WHERE schluessel >= 21;";
			$resultat *= mysql_query($anfrage);
			
		
		if($root AND $verwalter AND $editor AND $sichtbarkeit AND $resultat){
			return true;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Die Methode erstellt die MySQL-Tabelle für das Speichern der Benutzer und ihrer Daten
	function benutzer_erstellen($eingabe){
		// Die Hauptgruppe des Benutzers der automatisch erstellt wird
		// 1=root 2=verwalter 3=editor
		$auto_benutzer_hauptgruppe = 1;
		
		// Die Bibliothek zum Verwalten der Benutzer einbinden
		
		// Parameter speichern
		$eingabe["ersteller"] = "installer_skript";
		$eingabe["benutzer"] = $eingabe["mysql_benutzer"];
		$eingabe["passwort"] = $eingabe["mysql_passwort"];
		$eingabe["vorname"] = $eingabe["mysql_benutzer"];
		$eingabe["nachname"] = "auto_benutzer_hauptgruppe";
		$eingabe["lang"] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$eingabe["geburtstag"] = date("Y-m-d");
		$eingabe["homepage"] = $_SERVER['HTTP_HOST'];
		$eingabe["aktiv"] = 1;
		$eingabe["hauptgruppe"] = $auto_benutzer_hauptgruppe;
		$eingabe["bearbeiter"] = "installer_skript";
		$eingabe["gruppen"] = pow(2,$auto_benutzer_hauptgruppe);
		$eingabe["attribute"] = ' ';
		
		$resultat = CunddBenutzer::neu($eingabe);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle mit den Sichtbarkeiten und der Reihnfolge der 
	Eintrags-Informationen und füllt diese aus. */
	function eintrag_sichtbarkeit(){
		return CunddFelder::install_eintrag_sichtbarkeit("installer_skript");
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle in welche gelöschte Einträge kopiert werden.*/
	function geloescht_tabelle_erstellen(){
		$drop_table = false; // Gibt an ob die Tabelle, falls vorhanden, gelöscht werden soll
		
		$resultat = 1;
		mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				"geloeschte_eintraege`; ";
			$resultat *= mysql_query($anfrage);
		}
		
		
		// Tabelle erstellen
		$felder = CunddFelder::get_eintrag();
		
		$anfrage = "CREATE TABLE IF NOT EXISTS `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
			"geloeschte_eintraege` (";
		for($i = 0; $i < count($felder[0]); $i++){
			$anfrage .= "`".$felder[0][$i]."` ";
			$anfrage .= $felder[1][$i].", ";
		}
		$anfrage .= "`tabelle` TEXT, ";
		$anfrage .= "PRIMARY KEY (`schluessel`)
			)
			CHARACTER SET utf8;";
			
		$resultat *= mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode schreibt die angegeben Daten in die Datei "server.php" und gibt den Wert 
	TRUE zurück wenn der Schreibvorgang erfolgreich war. */
	function server_php_schreiben($eingabe){
		$admin_dateiname = "server.php";
		$ordner = "../";
		// Daten in die Datei "server.php" schreiben
/* WICHTIG: Die seltsame Formatierung dieses Codes ist aufgrund der 
Formatierung in der Ausgabe-Datei. */
$datei_text = '# Die Datei zum Speichern der Server-Daten
# Das Verwenden von Sonderzeichen wie auch der Einsatz
# 	von Tabulator ist nicht erlaubt.
# MySQL-Server Daten
mysql_host='.$eingabe["mysql_host"].'
mysql_benutzer='.$eingabe["mysql_benutzer"].'
mysql_passwort='.$eingabe["mysql_passwort"].'
mysql_database='.$eingabe["mysql_database"].'

# Der Prefix für die Tabellen in der MySQL-Datenbank
prefix='.$eingabe["mysql_prefix"];
		
		$verbindung = fopen($ordner.$admin_dateiname, w);
		if(fwrite($verbindung, $datei_text)){
			$formular_nicht_anzeigen = true;
		} else {
			throw new Exception('Server file couldn\'t be written');
		}
		
		// Rechte ändern
		@chmod ($ordner.$admin_dateiname, 0777);
		fclose($verbindung);
		
		return $formular_nicht_anzeigen;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle für das Speichern System-interner Nachrichten
	 mit Hilfe der CunddMSG-Bibliothek. */
	function msg_tabelle_erstellen(){
		$drop_table = false; // Gibt an ob die Tabelle, falls vorhanden, gelöscht werden soll
		
		mysql_connect(CunddConfig::get("mysql_host"), CunddConfig::get("mysql_benutzer"), CunddConfig::get("mysql_passwort"));
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix')."msg`; ";
			mysql_query($anfrage);
		}
		
		$anfrage = "CREATE TABLE IF NOT EXISTS `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix')."msg` (
		`from` TEXT,
		`to` TEXT,
		`to_group` TEXT,
		`date_read` DATE,
		`date` DATE,
		`subject` TEXT,
		`content` TEXT,
		`attachment` TEXT,
		`schluessel` INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY (`schluessel`)
		)
		CHARACTER SET utf8; ";
		
		$resultat = mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle für das Speichern der Daten von hochgeladenen 
	 Dateien. */
	function files_tabelle_erstellen(){
		$drop_table = false; // Gibt an ob die Tabelle, falls vorhanden, gelöscht werden soll
		$fields = CunddFelder::get_files();
		
		mysql_connect(CunddConfig::get("mysql_host"), CunddConfig::get("mysql_benutzer"), CunddConfig::get("mysql_passwort"));
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix')."files`; ";
			mysql_query($anfrage);
		}
		
		$anfrage = "CREATE TABLE IF NOT EXISTS `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix')."files` (";
		for($i=0; $i < count($fields); $i++){
			$anfrage .= "`".$fields[$i]["name"]."` ";
			$anfrage .= $fields[$i]["einstellungen"].", ";
		}
		$anfrage .= "`schluessel` INT NOT NULL AUTO_INCREMENT,
			PRIMARY KEY (`schluessel`)
			)
			CHARACTER SET utf8; ";
		
		$resultat = mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle für das Speichern von Seiten-Inhalten und 
	 -Strukturen. */
	function content_tabelle_erstellen(){
		$drop_table = false;
		$felder = CunddFelder::get_eintrag();
		
		if($drop_table){
			$anfrage = "DROP TABLE IF EXISTS `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix')."CunddContent`; ";
			mysql_query($anfrage);
		}
		
		$anfrage = "CREATE TABLE IF NOT EXISTS `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
		"CunddContent` (";
		for($i = 0; $i < count($felder[0]); $i++){
			$anfrage .= "`".$felder[0][$i]."` ";
			$anfrage .= $felder[1][$i].", ";
		}
		$anfrage .= "PRIMARY KEY (`schluessel`)
		)
		CHARACTER SET utf8
		COMMENT = 'CunddBlog_mysql_table_schema';";
		
		$resultat = mysql_query($anfrage);
		
		return $resultat;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Error-Meldung aus.
	 * @param unknown_type $errno
	 * @param unknown_type $errstr
	 * @param unknown_type $errfile
	 * @param unknown_type $errline
	 */
	public static function error($errno, $errstr, $errfile, $errline){
		//echo "$errno: $errstr in $errfile @$errline";
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht eine Klasse zu laden. */
	public static function autoLoader($class){
		$currentPath = dirname(__FILE__).'/';
		//require_once($currentPath."../../klassen/$class.php");
		require_once($currentPath."../../".CunddConfig::__('Cundd_class_path').str_replace('_','/',$class).'.php');
	}
}
?>