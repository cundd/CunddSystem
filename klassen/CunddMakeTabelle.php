<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddMakeTabelle" überprüft ob die angegebene Tabelle bereits 
existiert und erstellt diese gegebenenfalls. */
class CunddMakeTabelle{
	// Variablen deklarieren
	var $blog_inst; // Speichert einen Zeiger auf die Instanz des Eltern-Objekts
	var $typ; // Speichert ob die neue Tabelle eine Eintrags- oder eine Link-Tabelle ist
	public $version = 1.1;


	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	function CunddMakeTabelle($CunddBlog_para, $typ){
		// Die Parameter speichern
		$this->blog_inst = $CunddBlog_para;
		$this->typ = $typ;
		
		// Mit MySQL-Datenbank verbinden
		mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		mysql_query("USE `".CunddConfig::get('mysql_database')."`;");
	
		// Existenz der Tabelle überprüfen
		$ueberpruefen_ergebnis = $this->ueberpruefen();
		
		if(!$ueberpruefen_ergebnis && CunddLogin::isLoggedIn()){
			$erstellen_ergebnis = $this->erstellen();
			/* Die Rückgabe von "erstellen()" ist 1 wenn die Tabelle erfolgreich erstellt 
			wurde und 0 wenn beim Erstellen ein Fehler aufgetreten ist. */
			if($erstellen_ergebnis){
				echo "Die Tabelle ".CunddConfig::get('prefix').$this->blog_inst->tabelle." wurde neu erstellt.<br />";
			} else {
				echo "Beim Erstellen der Tabelle ".CunddConfig::get('prefix').$this->blog_inst->tabelle." ist ein Fehler aufgetreten.<br />";
			}
		}
	
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode überprüft ob eine Tabelle mit dem angegebenen Namen existiert und gibt 
	TRUE bzw. FALSE zurück. */
	function ueberpruefen(){
		$anfrage = "SHOW TABLES LIKE '".CunddConfig::get('prefix').$this->blog_inst->tabelle."';";
		$resultat = mysql_query($anfrage);
		
		if($resultat) {
			// "$wert" ist NULL wenn keine Tabelle mit dem angegebenen Namen existiert
			if($wert = mysql_fetch_row($resultat)) {
				return true;
			}else{
				return false;
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt die MySQL-Tabelle */
	function erstellen(){
		/* Blog-Tabelle erstellen die benötigten Felder werden aus der MySQL-Tabelle ausge-
		lesen. */
		if($this->typ == "eintrag"){
			$felder = CunddFelder::get_eintrag();
		} else if($this->typ == "link"){
			$felder = CunddFelder::get_link();
		}
		
		$anfrage = "CREATE TABLE `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
			$this->blog_inst->tabelle."` (";
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
}
?>