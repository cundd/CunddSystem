<?php
if(!class_exists('CunddRechte')){
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddRechte" bietet eine Methode zum Überprüfen ob dem derzeitig einge- 
loggten Benutzer das Lesen und/oder Schreiben erlaubt ist. */
/* Erklärung des Rechte Management Systems: Es gibt verschiedene Rechte für die Verwalt-
ung von Einträgen und ähnlichem. Das System basiert auf dem unter Unix-Systemen üblichen 
Octal-System, allerdings besteht die Angabe der Rechte im Cundd-System aus 4 Werten 
(XXXX). Der erste Wert (von Rechts) beschreibt die Rechte für die Öffentlichkeit, also 
alle Benutzer und Besucher der Seite die NICHT eingeloggt sind. Der zweite Wert be-
schreibt die Rechte für alle Benutzer die am System angemeldet (eingeloggt) sind. Der 
dritte Wert beschreibt die Rechte für die Gruppe, der vierte die Rechte für den Er-
steller. Die Rechte für die Gruppe bezieht sich auf die Hauptgruppe des Erstellers und 
gelten für alle Benutzer die Mitglied der Hauptgruppe des Benutzers sind.
Im System sind folgende Werte üblich:
0 = keine Rechte d.h. nicht öffentlich les- oder schreibbar
				 bzw. nicht nach dem Loggin les- oder schreibbar
				 bzw. nicht von Mitgliedern der Gruppe les- oder schreibbar
				 bzw. nicht vom Ersteller les- oder schreibbar
4 = Lese-Rechte d.h. öffentlich les- aber nicht schreibbar
				bzw. nicht nach dem Loggin les- aber nicht schreibbar
				bzw. nicht von Mitgliedern les- aber nicht schreibbar
				bzw. nicht vom Erstelle les- aber nicht schreibbar
6 = Schreib-Rechte	d.h. öffentlich les- und schreibbar
					bzw. nicht nach dem Loggin les- und schreibbar
					bzw. nicht von Mitgliedern les- und schreibbar
					bzw. nicht vom Erstelle les- und schreibbar

Die in Betriebssystemen möglichen Werte für das Ausführen von Dateien (7, 5, 3 oder 1) 
werden im Cundd-System nicht verwendet. Der Wert 7 allerdings wird stellvertretend für 
den Wert 6 verwendet.

Definition der Positionen (am Beispiel 6440):
6			4			4			0
Ersteller	Gruppe		Eingeloggt	Öffentlich


Benutzer die zur Gruppe "root" gehören haben überall das Recht zu schreiben.

Die Verwaltung der Rechte bzw. die Werte (0, 5, 6, etc.) ergeben sich aus der Dar-
stellung der Dezimalwerte im Dualsystem. Informationen dazu finden Sie zum Beispiel 
unter http://www.moock.org/asdg/technotes/bitwise/ 

*/

class CunddRechte{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $currentRight; // Speichert das ermittelte Recht der Instanz
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor: Ruft die statische Methode get() auf und speichert das Ergebnis in der 
	 * Eigenschaft $currentRight.
	 * @param array $wert
	 * @return int
	 */
	function CunddRechte(array $wert){
		$this->currentRight = CunddRechte::get($wert);
		return $this->currentRight;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Eigenschaft $currentRight zurück.
	 * @return int|false
	 */
	public function getRight(){
		if(isset($this->currentRight)){
			return $this->currentRight;
		} else {
			return false;
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode handhabt das Verhalten der Klasse wenn eine Instanz als String ausgegeben 
	 * werden soll. */
	public function __toString(){
		return "$this->currentRight";
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Übergeben werden die Daten des zu verändernden/anzuzeigenden Eintrags. Zurückgegeben 
	 * wird 4 wenn der Benutzer nur Lesen darf, 6 wenn der Benutzer auch Schreiben darf. 
	 * Gibt die Methode FALSE zurück hat der Benutzer keine Rechte.
	 * @param array $wert
	 * @return number|false
	 */
	public static function get(array $wert){
		$say = false;
		
		// Überprüfen ob der derzeitige Benutzer auch der Ersteller ist
		if($wert["ersteller"] == $_SESSION["benutzer"]){
			$ist_ersteller = 1;
		}
		
		/* Überprüfen ob ein leerer Eintrag erstellt, oder ein bereits bestehender 
		angezeigt werden soll. */
		// Wenn $wert["tabelle"] leer ist wurde das Skript von CunddLink aufgerufen
		if(!$wert["ersteller"] AND $wert["tabelle"]){
			$rechte = 7777;
	//	} else if(!$wert["tabelle"]){
	//		$rechte = 7777;
		} else {	
			$rechte = $wert["rechte"]; // 7777, 7045, etc.
		}
		
		
		// Prinzip: floor(parameter/10^(stelle))%10
		$benutzer_rechte 	= floor($rechte / pow(10,3)) % 10;
		$gruppen_rechte 	= floor($rechte / pow(10,2)) % 10;
		$alle_rechte 		= floor($rechte / pow(10,1)) % 10;
		$oeffentlich_rechte = floor($rechte / pow(10,0)) % 10;
		
		// Schreibrechte überprüfen
		$ergebnis = $oeffentlich_rechte&2;
		$rueckgabe = decbin($ergebnis>>1);
		
		if($_SESSION["benutzer"]){ $ist_eingeloggt = true; } // Überprüft ob ein Benutzer eingeloggt ist
		$ergebnis = $alle_rechte&2;
		$rueckgabe += decbin($ergebnis>>1) * $ist_eingeloggt;
		
		$ergebnis = $gruppen_rechte&2;
		$rueckgabe += decbin($ergebnis>>1) * CunddGruppen::ist_in($wert["gruppe"]);
		
		$ergebnis = $benutzer_rechte&2;
		$rueckgabe += decbin($ergebnis>>1) * $ist_ersteller;
		
		
		/* Die Methode gibt den Wert 6 zurück wenn der Benutzer schreiben darf.
		Das System nimmt an, dass ein Benutzer der schreiben darf auch lesen darf. */
		if($rueckgabe){
			$rueckgabe = 6;
		}
		
		// Wenn der Benutzer nicht schreiben darf -> überprüfen ob er lesen darf
		if(!$rueckgabe){
			$ergebnis = $oeffentlich_rechte&4;
			$rueckgabe += decbin($ergebnis>>2);
			
			$ergebnis = $alle_rechte&4;
			$rueckgabe += decbin($ergebnis>>2) * $ist_eingeloggt;
			
			$ergebnis = $gruppen_rechte&4;
			$rueckgabe += decbin($ergebnis>>2) * CunddGruppen::ist_in($wert["gruppe"]);
			
			$ergebnis = $benutzer_rechte&4;
			$rueckgabe += decbin($ergebnis>>2) * $ist_ersteller;
			
			if($rueckgabe){
				$rueckgabe = 4;
			}
		}
		
		
		
		// Überprüfen ob der Benutzer zur Gruppe "root" gehört
		if(floor($_SESSION["gruppen"] / pow(2,1)) % 2){
			$rueckgabe = 6;
		}
		
		
		// DEBUGGEN
		if($say){
			echo '<pre>';
			echo '$benutzer_rechte='.$benutzer_rechte.'<br />';
			echo '$gruppen_rechte='.$gruppen_rechte.'<br />';
			echo '$alle_rechte='.$alle_rechte.'<br />';
			echo '$oeffentlich_rechte='.$oeffentlich_rechte.'<br />';
			echo '$rueckgabe='.$rueckgabe;
			echo '</pre>';
			echo '<pre>';var_dump($wert);echo '</pre>';
		}
		// DEBUGGEN
		
		if($rueckgabe){
			return $rueckgabe;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt die Rechte für das Anzeigen bzw. Verändern der Benutzer-Felder. 
	 * Die Methode gibt ein Array mit den sichtbaren Feldern und den dazugehörigen Rechten 
	 * zurück.
	 * @return multitype:
	 */
	public static function get_benutzer_felder(){
		$say = false;
		
		/* Die Gruppe für welche die Werte überprüft werden ist die derzeitige Gruppe aus 
		$_SESSION */
		// Überprüfen ob $_SESSION["gruppen"] einen Wert hat
		//if($_SESSION["gruppen"]){
		if(CunddUser::getSessionUserValue("maingroup")){
			$gruppe = CunddUser::getSessionUserValue("maingroup");
			// Alle Gruppen zu denen ein Benutzer gehört ermitteln
			$gruppen_liste = CunddGruppen::get();
			if(!$gruppen_liste) return array();
			
			// Die Sichtbarkeitsrechte für alle Gruppen im Array $gruppen_liste lesen
			$anfrage = "SELECT ";
			for($i = 0; $i < count($gruppen_liste); $i++){
				$anfrage .= $gruppen_liste[$i]["gruppenname"].", ";
			}
			$anfrage .= "feld, type, required from `".CunddConfig::get('mysql_database')."`.`".
				CunddConfig::get('prefix')."benutzer_verwaltung_sichtbarkeit` WHERE ";
			for($i = 0; $i < count($gruppen_liste) - 1; $i++){
				$anfrage .= $gruppen_liste[floor($i)]["gruppenname"]." > 0 OR ";
			}
			if(count($gruppen_liste)){
				 $anfrage .= $gruppen_liste[$i]["gruppenname"]." > 0;";
			}
		} else 
		// Überprüfen ob die Benutzerliste öffentlich ist
		if(CunddConfig::get('benutzerliste_sichtbarkeit') == 1 OR 
			CunddConfig::get('bedingung_neuer_benutzer') == 'n'){
			$gruppe = "oeffentlich";
			$anfrage = "SELECT ".$gruppe.", feld, type, required from `".CunddConfig::get('mysql_database')."`.`".
				CunddConfig::get('prefix')."benutzer_verwaltung_sichtbarkeit` WHERE ".$gruppe." > 0;";
		}
		
		// Anfrage senden
		mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
			CunddConfig::get('mysql_passwort'));
		if($anfrage){
			$resultat = mysql_query($anfrage);
			
			$i = 0;
			$gruppen_sichtbarkeit = array();
			

			/* Das Ergebnis auslesen und dabei alle Daten in ein assoziatives und
			 * indizierte Array schreiben. */
			while($wert = mysql_fetch_array($resultat)){
				// Namen des Feldes speichern
				$gruppen_sichtbarkeit[$i]["feld"] = $wert["feld"];
				
				// Typ des Feldes speichern
				$gruppen_sichtbarkeit[$i]["type"] = $wert["type"];
								
				// Required: gibt an ob das Feld ausgefüllt sein muss
				$gruppen_sichtbarkeit[$i]["required"] = $wert["required"];
				
				
				$hoechst_recht = 0; // Der aktuelle Wert des Rechts für das aktuelle Feld
				// Den höchsten Wert der Rechte speichern
				for($j = 0; $j < count($wert); $j++){
					// Der alte Wert wird überschrieben wenn der neue größer ist
					// "floor()" eines Text-Strings ergibt 0
					if($hoechst_recht < $wert[$j] AND floor($wert[$j])){
						$hoechst_recht = $wert[$j];
					}
				}
				$gruppen_sichtbarkeit[$i]["rechte"] = floor($hoechst_recht);
				
				$i++;
			}
		}
		
		
		// DEBUGGEN
		if($say){
			echo '$anfrage: '.$anfrage.'<br />';
		}
		// DEBUGGEN
		
		return $gruppen_sichtbarkeit;
	}
}

/*
777
8
1100001001
1000
1000
10

echo '<pre>';
echo $r=777;
echo '<br>';
echo $b=2;
echo '<br>';
echo decbin($r);
echo '<br>';
echo decbin($b);
echo '<br>';
echo decbin($erg=$r&$b);
echo '<br>';
echo decbin($erg>>2);
echo '</pre>';

echo '<pre>';
echo $r=777;
echo '<br>';
echo $b=8;
echo '<br>';
echo decbin($r);
echo '<br>';
echo decbin($b);
echo '<br>';
echo decbin($erg=$r&$b);
echo '<br>';
echo decbin($erg>>2);
echo '</pre>';

*/
} //END OF CLASS_EXISTS

?>