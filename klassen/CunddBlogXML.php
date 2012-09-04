<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddBlogXML" liest die Daten einer angegebenen MySQL-Tabelle aus. Dabei 
kommen die dort definierten Lese-Rechte zum tragen. Das Ergebnis des Auslesens wird im 
XML-Stil ausgegeben. Das Schreiben und Editieren von Einträgen ist hier nicht möglich. */
class CunddBlogXML{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//Variablen deklarieren
	var $mysql_benutzer;
	var $mysql_passwort;
	var $mysql_database;
	var $tabelle;
	var $max_eintraege;
	var $gruppe;
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddBlogXML($tabelle){		
		// Parameter speichern
		$this->tabelle = $tabelle;
		// Dokument als XML deklarieren
		echo '<?xml version="1.0" ?>
				<'.$tabelle.'>
				';
		
		// Blog-Inhalte ausgeben
		$CunddBlogLesen = new CunddBlogLesen($this, 'XML');
		
		// "CunddContent"-div schließen
		echo '</'.$tabelle.'>';
	}
}
?>