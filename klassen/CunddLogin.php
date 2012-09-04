<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddLogin" bietet verschiedene Methoden für die Loggin-Prozedur. */
class CunddLogin{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddLogin(){
		// Der Konstruktor verweist auf die Methode "login()"
		$this->login();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode "login()" ermöglicht den Login am System. Sind die $_SESSION-Variblen 
	 * "benutzer", "gruppen", etc. gesetzt wird eine Erfolgsmeldung angezeigt. Wenn die 
	 * $_SESSION-Variablen nicht gesetzt sind, blendet die Methode das Login-Formular ein, wenn 
	 * die Login-Daten in der Variable $_POST[] keinen Wert besitzen. Sind Werte angegeben 
	 * werden diese mit den Daten in der MySQL-Benutzer-Tabelle verglichen. Bei Übereinstimmung 
	 * werden die $_SESSION[]-Daten für den Benutzer gespeichert. */
	function login(){
		// Überprüfen ob der Benutzer bereits eingeloggt ist
		if($_SESSION["benutzer"] AND $_SESSION["gruppen"]){
			// Eine Erfolgsmeldung ausgeben
			CunddTemplate::login('korrekt');
			
		} else // Überprüfen ob die benötigten Daten per $_POST[] übergeben wurden
		if($_POST["CunddLoginFormular_benutzer"] AND $_POST["CunddLoginFormular_passwort"]){
			// Wenn ja -> Die Daten auf ihre Richtigkeit überprüfen
			
			// $_POST[]-Variablen speichern
			$benutzer = $_POST["CunddLoginFormular_benutzer"];
			$passwort = $_POST["CunddLoginFormular_passwort"];
			
			
			// Benutzer-Login-Daten aus der MySQL-Tabelle abrufen
			mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
				CunddConfig::get('mysql_passwort'));
				
			$anfrage = "SELECT * FROM `".CunddConfig::get('mysql_database').
				"`.`".CunddConfig::get('prefix')."benutzer` WHERE benutzer = '".$benutzer.
				"' AND passwort = '".$passwort."' AND aktiv > 0;";
			
			$resultat = mysql_query($anfrage);
			while($versuch = mysql_fetch_array($resultat)){
				if($versuch["benutzer"] == $benutzer AND $versuch["passwort"] == $passwort){
					$login_korrekt = true; // Variable $login_korrekt auf TRUE setzen
					$wert = $versuch; // Die korrekten Benutzer-Daten in der Variable $wert speichern
					break;
				}
			}
			
			// Überprüfung verarbeiten
			if($login_korrekt){
				CunddTemplate::login('korrekt');
				
				// Starten der Session und speichern der wichtigsten Benutzerdaten
				//session_start();
				$_SESSION["benutzer"] = $benutzer;
				// Das Passwort wird NICHT gespeichert: $_SESSION["passwort"] = $passwort;
				$_SESSION["hauptgruppe"] = $wert["hauptgruppe"];
				$_SESSION["gruppen"] = $wert["gruppen"];
				$_SESSION["sprache"] = $wert["sprache"];
				
				$eventData = array();
				$eventData['name'] = $benutzer;
				$eventData['maingroup'] = $wert['hauptgruppe'];
				$eventData['groups'] = $wert['gruppen'];
				$eventData['id'] = $wert['schluessel'];
				new CunddEvent('userLoggedIn',$eventData);
				
			} else {
				CunddTemplate::login('fehler');
			}
			
			
		} else { /* Wenn keine Benutzer-Daten per $_POST übermittelt wurden wird das 
			Formular eingeblendet */
			CunddTemplate::login('normal');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode löscht die Session des Benutzers und loggt ihn somit aus. */
	public static function logout(){
		CunddTemplate::logout();
		
		
		$eventData = CunddUser::getSessionUserData();
		new CunddEvent('userLoggedOut',$eventData);
		
		
		ob_start();
		if(isset($_SESSION)){
			$_SESSION = array();
			session_destroy();
		}
		ob_end_clean();
		
		
		
		// Seite neu laden
		echo '<script type="text/javascript">
				setTimeout(CunddLogoutRedirect, Cundd_redirect_zeit);
				function CunddLogoutRedirect(){
					window.location = "'.$_SERVER['HTTP_REFERER'].'";
				}
			</script>';
	}
	
	// Alias
	function out(){
		CunddLogin::logout();
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt das Login-Formular nur aus, wenn kein Benutzer eingeloggt ist.
	 * @return CunddLogin|boolean
	 */
	public static function checkIfLoggedInElseShowForm(){
		if(!self::isLoggedIn()){
			return new CunddLogin();
		} else {
			return (bool) true;
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob ein Benutzer eingeloggt ist.
	 * @return string|false
	 */
	public static function isLoggedIn(){
		return CunddBenutzer::getSessionUser();
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt einen Logout-Link.
	 * @param string $class
	 * @return string
	 */
	public static function createLogoutLink($class = NULL){
		$title = CunddLang::__('logout');
		return CunddLink::newLink($title,'CunddLogin::logout',NULL,NULL,$class);
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Logout-Link aus.
	 * @param string $class
	 * @return string
	 */
	public static function printLogoutLink($class = NULL,$noOutput = NULL){
		if(self::isLoggedIn()){
			$link = self::createLogoutLink();
			if(!$noOutput) echo $link;
		}
		return $link;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erzeugt eine Box mit verschiedenen Infos für den User.
	 * @param boolean $printGuest
	 * @param boolean $noOutput
	 * @return string
	 */
	public static function userBox($printGuest = NULL,$noOutput = NULL){
		return CunddBenutzer::userBox($printGuest,$noOutput);
	}
}