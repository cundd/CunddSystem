<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die globale Variable $alle_einstellungen definieren. Die Variable speichert die aus 
 der Konfigurationsdatei gelesenen Einstellungen. */
// self::$_allConfigurations = NULL;


	
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddConfig" dient als statische Klasse zur Abfrage oft verwendeter 
 Konfigurationswerte. Die Parameter werden aus der Datei "config.php" ausgelesen und in 
 ein assoziatives Array geschrieben. Entsprechend dem Parameter der beim Aufruf der 
 Methode "get()" übergeben wird, wird der Wert aus "config.php" zurückgegeben. Am Beginn 
 des Skripts wird zuerst überprüft, ob das Skript bereits aufgerufen wurde und sich die 
 benötigten Daten noch im Speicher befinden. */
class CunddConfig{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected static $_registryKey = '_cundd_alle_einstellungen';
	protected static $_allConfigurations;
	protected static $_allowOverwrite;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * @param string $variable
	 * @return mixed
	 */
	public static function get($variable){
		$say = false;
		$error = false;
		// Die globale Variable $alle_einstellungen lokal lesbar machen
			// global self::$_allConfigurations;
			// self::$_allConfigurations = Cundd::registry(self::$_registryKey);
		
		
		
		// Überprüfen ob sich die Konfigurationsdaten noch im Speicher befinden
		if(!self::$_allConfigurations){
			/* Mit "ob_start" wird die Weitergabe vom Ergebnis von "require()" nicht an den 
			Client weitergegeben sondern durch "ob_get_contents()" in die Variable "array_inhalt 
			geschrieben. "ob_end_clean()" beendet das Buffern ohne, dass die Daten an den Client 
			geliefert werden. */
			ob_start();
				/* Überprüfen von wo aus die Methode aufgerufen wird bzw. welcher Pfad richtig 
				ist */
				if(file_exists("./Cundd/admin/config.php")){
					include("./Cundd/admin/config.php");
					include("./Cundd/admin/server.php");
					include("./Cundd/admin/config_extra.php");
					$array_inhalt = ob_get_contents();
				} else if(file_exists("../config.php")){
					include("../config.php");
					include("../server.php");
					include("../config_extra.php");
					$array_inhalt = ob_get_contents();
				} else if(file_exists("../../admin/config.php")){
					include("../../admin/config.php");
					include("../../admin/server.php");
					include("../../admin/config_extra.php");
					$array_inhalt = ob_get_contents();
				} else if(file_exists("./admin/config.php")){
					include("./admin/config.php");
					include("./admin/server.php");
					include("./admin/config_extra.php");
					$array_inhalt = ob_get_contents();
				} else if(file_exists(dirname(__FILE__)."/admin/config.php")){
					include(dirname(__FILE__)."/admin/config.php");
					include(dirname(__FILE__)."/admin/server.php");
					include(dirname(__FILE__)."/admin/config_extra.php");
					$array_inhalt = ob_get_contents();
				} else {
				    $error = true;
				}
			ob_end_clean();

			if($error){ // If an error occured
			    die("CunddConfig: Configuration file not found.");
			}

			// $variable parsen
			$variable = str_replace(" ", "", $variable);
			
			self::$_allConfigurations = array();
			
			// Reguläre Ausdrücke Infos: http://www.php-resource.de/tutorials/read/10/1/
			// Entfernt alle Leerzeichen
			$array_inhalt = str_replace(" ","",$array_inhalt);
			
			// Entfernt alle Kommentare, also Bereiche die mit "#" beginnen.
			$ersetzen = array("!#+.+[/\r\n|\n|\r/]!");
			$array_inhalt = preg_replace($ersetzen,"",$array_inhalt);
			
			// Entfernt alle Zeilenumbrüche
			$ersetzen = array("![\r\n|\n|\r]!");//+(\W+\W|\W)!");
			$array_inhalt = preg_replace($ersetzen,"&",$array_inhalt);
			
			// Überflüssige & entfernen
			$ersetzen = array("![&|&&|&&&|&&&&|&&&&&|&&&&&&|&&&&&&&]!");//+(\W+\W|\W)!");
			$array_inhalt = preg_replace($ersetzen,"&",$array_inhalt);
			
			
			/* Handhabt den ersten Parameter als URL und liest diese analog zu "$_GET" aus.
			Als Ergebnis werden die Daten in ein Assoziatives Array "$alle_einstellungen" 
			geschrieben. */
			parse_str($array_inhalt, self::$_allConfigurations);
			
			
			/* Die zusätzlichen Konfigurationen werden oben nach "ob_start()" eingelesen. 
			 die eingelesenen Werte werden in self::$_allConfigurations geschrieben. */
			foreach($cundd_extra_einstellungen as $key => $value){
				self::$_allConfigurations[$key] = $value;
			}
			
			
			// Speichern ob die Einstellungen überschrieben werden dürfen
			self::$_allowOverwrite = $cundd_extra_einstellungen['Cundd_Security_allow_config_overwrite'];
			
			// Wenn erlaubt die Verbindung zum MySQL-Server überprüfen
			if(self::$_allConfigurations['allow_auto_mysql_server_to_local']){
				if(!@mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
					CunddConfig::get('mysql_passwort'))
					) {
					self::$_allConfigurations['mysql_host']="127.0.0.1";
					//CunddTools::log("CunddConfig","Used alternativ MySQL-Server with IP 127.0.0.1.");
				}
			}

		}
		
		
		
		// Überprüfen ob eine spezielle Modul-Konfiguration geladen werden soll
		$moduleConf = self::_checkIfModulConf($variable);
		if($moduleConf) return $moduleConf;
		

		$overwrite = self::_checkIfOverwrite($variable);
		if($overwrite !== NULL) return $overwrite;
		/* */
		
		
		// DEBUGGEN
		if($say){
		// Das ganze Array ausgeben
		echo '<h1>alle_einstellungen</h1>';
		echo '<pre>';
		var_dump(self::$_allConfigurations);
		echo '</pre>';
		}
		// DEBUGGEN
		
		
		// Write registry
		// Cundd::registry(self::$_registryKey,self::$_allConfigurations);
		
		
		/* Überprüfen ob die Konfiguration gelesen werden konnte, wenn nicht wird eine
		Fehlermeldung in die Datei zurückgegeben. */
		$rueckgabe = self::$_allConfigurations[$variable];
		if(is_null($rueckgabe)){
			/* CunddTools::log_fehler("CunddConfig","Falscher Parameter ".$variable.
				". array_inhalt=".$array_inhalt); // */
		}
				
		return $rueckgabe;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ist ein Alias für die Methode get().
	 * @param string $variable
	 * @return mixed
	 */
	public static function __($variable){
		return self::get($variable);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest die Modul-Konfiguration wenn $variable einen "/" enthält.
	 * @param string $variable
	 * @param string $classFileSuffix
	 * @return mixed
	 */
	private static function _checkIfModulConf($variable,$classFileSuffix = '.php'){
			// global self::$_allConfigurations;
			// self::$_allConfigurations = Cundd::registry(self::$registryKey);
			
		
		if(strpos($variable,'/')){
			/*
			// Im Einstellungs-Array suchen
			if(self::$_allConfigurations){
				if(array_key_exists($variable,self::$_allConfigurations)){
					return self::$_allConfigurations[$variable];
				}
			}
			/* */
			
			// Sonst laden
			$relevantNamespace = 'Cundd';
			$modelDir = CunddConfig::__('Cundd_model_dir');
			$classDir = CunddConfig::__('Cundd_conf_dir');
			$confFilename = CunddConfig::__('Cundd_conf_name');
			
			$variableArray = explode('/',$variable);
			
			$modulConfPath = $relevantNamespace.'/'.$variableArray[0].'/'.$classDir.$confFilename;

			// DEBUGGEN
			if($say){
			    echo "<h1>". CunddPath::getAbsoluteLocalClassDir()."$modulConfPath <br />exists=".
			    var_export(file_exists(CunddPath::getAbsoluteLocalClassDir().$modulConfPath),1)."</h1>";

			    echo "<h1>".CunddPath::getAbsoluteGlobalClassDir()."$modulConfPath <br />exists=".
			    var_export(file_exists(CunddPath::getAbsoluteGlobalClassDir().$modulConfPath),1)."</h1>";
			}
			// DEBUGGEN

			// Return if neither a local or global file was found
			if(	!file_exists(CunddPath::getAbsoluteLocalClassDir().$modulConfPath) &&
				!file_exists(CunddPath::getAbsoluteGlobalClassDir().$modulConfPath)
				){
			    return (bool) false;
			}


			include($modulConfPath);
			self::$_allConfigurations = array_merge($config,self::$_allConfigurations);
			
			// Write registry
			// Cundd::registry(self::$_registryKey,self::$_allConfigurations);
			
			if(array_key_exists($variableArray[1],self::$_allConfigurations)){
				return self::$_allConfigurations[$variableArray[1]];
			} else {
				return (bool) false;
			}
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermöglicht das überschreiben von Einstellungen mittels $_GET oder $_POST.
	 * @param string $variable
	 * @return mixed
	 */
	protected function _checkIfOverwrite($variable){
		if(!self::$_allowOverwrite) return (bool) false;
		
		$return = NULL;
		
		if(array_key_exists($variable,$_GET)){
			$return = $_GET[$variable];
		} else if(array_key_exists($variable,$_POST)){
			$return = $_POST[$variable];
		}
		/*
		else if(array_key_exists($variable,$requestPara)){
			$return = $requestPara[$variable];
		}
		/* */
		
		
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt alle bisher abgerufenen Configurationen zurück. Allerdings nur wenn 
	 * ein passender Parameter übergeben wurde und ein Benutzer eingeloggt ist.
	 * @param string $para
	 * @return array
	 */
	public static function getAll($para){
		if(CunddLogin::isLoggedIn() AND $para == 'superman'){
			return self::$_allConfigurations;
		} else {
			return NULL;
		}
	}
	
	
	
	
	
	
	
}
?>