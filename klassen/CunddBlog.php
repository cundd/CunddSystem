<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddBlog" ermöglicht das erstellen eines einfachen Blogging-Sys-
tems. Durch das "includen" dieses Dokuments, innerhalb der im Web sichtbaren PHP+HTML-
Seite, und dem Aufruf des Befehls "CunddBlog" mit den Parametern für die MySQL-Tabelle 
die die Daten dieser Seite enthalten soll/enthält und einer Angabe für die maximale 
Anzahl an Einträgen, die auf dieser Seite erstellbar sind.
Das Programm setzt dabei eine Konfigurationsdatei ("config.php") voraus, die sich 
im Ordner "./admin" (relativ zum Stammordner von "CunddConfig.php") befindet. Außerdem 
wird die Datei "./admin/server.php" benötigt die die spezifischen Server-Daten, wie z.B. 
MySQL-Benutzername und -Passwort enthält. */
class CunddBlog{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//Variablen deklarieren
	var $mysql_benutzer;
	var $mysql_passwort;
	var $mysql_database;
	var $tabelle;
	var $max_eintraege;
	var $gruppe;

	public $version = 1.1;
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddBlog($tabelle,$max_eintraege = 100,$gruppe = null){
		// JavaScript-Variablen ausgeben
		CunddJavaScript::init();
		
		// Parameter speichern
		$this->tabelle = $tabelle;
		$this->max_eintraege = $max_eintraege;
		$this->gruppe = $gruppe;
		
		// Überprüfen ob die MySQL-Tabelle existiert und wenn ja -> ausgeben
		$CunddMakeTabelle_return = new CunddMakeTabelle($this, eintrag);
		
		if($CunddMakeTabelle_return){
			// div erstellen der den gesamten Blog-Inhalt einschließt
			echo '<div id="'.CunddConfig::get('prefix').$tabelle.'" class="CunddBlog">';
			
			// Blog-Inhalte ausgeben
			$CunddBlogLesen = new CunddBlogLesen($this, 'HTML');
			
			// "CunddContent"-div schließen
			echo '</div>';
			
			// Eine JavaScript-Instanz von CunddBlogMain erstellen
			$this->js_instanz();
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine JavaScript-Instanz von CunddBlogMain und erweitert das 
	JavaScript-Array "CunddSystemInstanzen" um ein neues Element entsprechend dieser Instanz 
	von "CunddBlog". */
	function js_instanz(){
		echo '<script type="text/javascript">			
				var CunddBlogMain_instanz = new CunddBlogMain("'.CunddConfig::get('prefix').$this->tabelle.
					'", "'.$this->max_eintraege.'", "'.$this->gruppe.'");
				CunddSystemInstanzen.push("'.CunddConfig::get('prefix').$this->tabelle.'");
				</script>';
	}
}
?>