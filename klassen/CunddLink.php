<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * Die Klasse "CunddLink" bietet Methoden zur Verwaltung und Ausgabe der Links.
 */
class CunddLink extends Cundd_Tools_Link{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//Variablen deklarieren
	public $tabelle;
	
	protected $_useHardLink = false;
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Konstruktor
	 * Gibt die Navigation entsprechende der übergebenen Tabelle aus
	 * @param string $tabelle
	 * @param boolean $initOnly
	 * @param boolean $noOutput=false Display the fetched data
	 * @return CunddLink|boolean
	 */
	public function CunddLink($tabelle,$initOnly = false,$useHardLinks = false,$noOutput = false){
		$this->_useHardLink = $useHardLinks;
		$CunddMakeTabelle_return = $this->init($tabelle);
		
		if($CunddMakeTabelle_return AND !$initOnly AND !$noOutput){// Links ausgeben
			$result = $this->anzeigen();
			
			// Diesen div in der JavaScript-Variable "CunddSystemInstanzen" speichern
			$this->js_instanz();
		} else if($CunddMakeTabelle_return AND $initOnly){
			return $this;
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode initialisiert die Instanz. */
	public function init($tabelle){
		// Parameter speichern
		// "link_" an den Tabellennamen anhängen
		$this->tabelle = "link_".$tabelle;
		
		// Überprüfen ob die MySQL-Tabelle existiert und wenn ja -> ausgeben
		$CunddMakeTabelle_return = new CunddMakeTabelle($this, "link");
		return $CunddMakeTabelle_return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ruft das Skript zum Anzeigen und das zum Erstellen des JavaScript-Codes
	 * auf.
	 * @return string
	 */
	public function __toString(){
		$this->printAll();
		return '';
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ruft das Skript zum Anzeigen und das zum Erstellen des JavaScript-Codes
	 * auf.
	 * @return void
	 */
	public function printAll(){
		$this->anzeigen();
		$this->js_instanz();
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode liest die Links aus der übergebenen Tabelle aus und gibt das Ergebnis 
	 * weiter an "CunddTemplate".
	 * @return unknown_type
	 */
	private function anzeigen(){
		$say = false;
		
		// Mit MySQL-Datenbank verbinden
		mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		mysql_query("USE `".CunddConfig::get('mysql_database')."`;");
		
		
		// Die grundsätzliche Anfrage
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				$this->tabelle."`";
		
		// Optionen
			// Überprüfen ob der Benutzer zur Gruppe "root" gehört
		/*	$ist_root = floor($_SESSION['gruppen'] / pow(2,1)) % 2;
			if(!$ist_root){
				/* Überprüfen ob ein Benutzer eingeloggt ist und ob dieser die nötigen Rechte zum 
				lesen der Einträge hat. Zuerst wird überprüft ob der User überhaupt eingeloggt ist. 
				Dann werden die Gruppen des Users gelesen und die Suche nach den passenden Einträgen 
				ermöglicht. */
				// Öffentliche Einträge lesen
		/*		$anfrage .= " WHERE floor(rechte / POW(10,0)) % 10 > '0' ";
				
				/* Wenn ein Benutzer eingeloggt ist alle Einträge zeigen die er erstellt hat oder die von 
				einem Member der Gruppe erstellt wurde, dessen Hauptgruppe eine Gruppe des eingeloggten 
				Benutzers ist. */
	/*			if($_SESSION["benutzer"]){
					$anfrage .= "OR ersteller LIKE '".$_SESSION["benutzer"].
						"' OR floor(rechte / POW(10,4)) % 10 > '0' ";
					
					// Überprüfen ob für alle eingeloggten Benutzer sichtbar
					$anfrage .= "OR floor(rechte / POW(10,1)) % 10 > '0' ";
					
					
					// TODO: gruppe funktioniert nicht
					// Mitgliedschaft in der Hauptgruppe und die Rechte für die Gruppe
					// mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
					$gruppe = $_SESSION["gruppen"];
					/*
					$anfrage .= "OR (floor(".$gruppe.
						" / POW(2,gruppe)) % 2 AND floor(rechte / POW(10,2)) % 10 > '0') ";
					*/
	/*				$anfrage .= "OR (".$gruppe.
						" & POW(2,gruppe) AND floor(rechte / POW(10,2)) % 10 > '0') ";
				}
			}
			
			
			if($ist_root){
				$anfrage .= " WHERE schluessel LIKE '%'";
			}
			
	
			// Nach der Sprache filtern
			if(CunddConfig::get('cunddsystem_multilanguage_enabled')){
				$anfrage .= " AND (";
				$anfrage .= "lang='".CunddLang::get()."' OR ";
				$anfrage .= "lang IS NULL OR lang='0' OR lang='') ";
			}
			
		// Anfrage schließen
		$anfrage .= "AND aktiv = 1 "; // nur aktive Links
		$anfrage .= "ORDER BY schluessel ASC, prioritaet DESC, parent ASC;";//, prioritaet DESC ;";
		/* */
		
		
		$data = array('table' => $this->tabelle);
		$adapter = Cundd::getModel('Core/Adapter_Content',$data);
		$resultat = $adapter->load();
		
		//echo $adapter->getQuery();
		
		
		// MySQL-Anfrage stellen
		// $resultat = mysql_query($anfrage);
		
		// DEBUGGEN
		if($say){
			echo "\$anfrage=".$adapter->getQuery()."<br />";
			echo "\$resultat=$resultat<br />";
			CunddTools::pd($resultat);
		}
		// DEBUGGEN
		
		// Überprüfen ob MySQL eine Antwort geliefert hat
		if($resultat){
			// Ergebnisse ausgeben
			return $this->ergebnis_ausgeben($resultat);
		} else {
			// Fehlermeldung ausgeben
			echo 'Beim Auslesen der MySQL-Tabelle ist ein Fehler aufgetreten.';
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode liest die Links aus der übergebenen Tabelle aus und gibt das Ergebnis 
	 * weiter an "CunddTemplate".
	 * @return array
	 */
	public function getLinks(){
		$say = false;
		
		// Mit MySQL-Datenbank verbinden
		mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		mysql_query("USE `".CunddConfig::get('mysql_database')."`;");
		
		
		// Die grundsätzliche Anfrage
		$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
				$this->tabelle."`";
		
		$data = array('table' => $this->tabelle);
		$adapter = Cundd::getModel('Core/Adapter_Content',$data);
		$resultat = $adapter->load();
		
		//echo $adapter->getQuery();
		
		
		// MySQL-Anfrage stellen
		// $resultat = mysql_query($anfrage);
		
		// DEBUGGEN
		if($say){
			echo "\$anfrage=".$adapter->getQuery()."<br />";
			echo "\$resultat=$resultat<br />";
			CunddTools::pd($resultat);
		}
		// DEBUGGEN
		
		// Überprüfen ob MySQL eine Antwort geliefert hat
		if($resultat){
			// Ergebnisse ausgeben
			return $resultat;
		} else {
			// Fehlermeldung ausgeben
			echo 'Beim Auslesen der MySQL-Tabelle ist ein Fehler aufgetreten.';
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt das Ergebnis der MySQL-Anfrage aus bzw. vermittelt das Ergebnis an 
	 * die Template-Manager-Klasse "CunddTemplate" und erstellt einen leeren Eintrag, wenn das 
	 * Maximum der Einträge für diese Seite noch nicht erreicht ist.
	 * @param unknown_type $resultat
	 * @return boolean
	 */
	private function ergebnis_ausgeben($resultat){
		// Links ausgeben
		CunddTemplate::links($resultat, $this->tabelle);
		return (bool) true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode erweitert das JavaScript-Array "CunddSystemInstanzen" um ein neues 
	 * Element entsprechend dieser Instanz von "CunddLink".
	 * @return void
	 */
	private function js_instanz(){
		echo '<script type="text/javascript">
				var CunddLinkAjax_instanz = new CunddLinkAjax("'.CunddConfig::get('prefix').$this->tabelle.'");
				CunddSystemInstanzen.push("'.CunddConfig::get('prefix').$this->tabelle.'");
				</script>';
	}
	
	
	
	
	
	
	
	
	
}
?>