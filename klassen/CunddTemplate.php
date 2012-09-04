<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddTemplate" verwaltet den visuellen Output der Werte die in 
"CunddBlogLesen" geladen wurden. */
class CunddTemplate{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $output;
	private $template;
	
	private $debug = false;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Der Konstruktor . */
	public function __construct($template,$para = NULL,$noOutput = NULL){
		$wert = array();
		$wert['tag'] = $template;
		$this->template = $template;
		
		if($para){
			if(gettype($para) == 'string'){
				$paraTemp = array();
				$action = 'dummy';
				CunddController::stringToActionAndPara($para,$action,$paraTemp);
			} else if(gettype($para) == 'array'){
				$paraTemp = $para;
			}
			
			array_merge($wert,$paraTemp);
		}
		
		// Get the right
		if(array_key_exists('right',$wert)){
			$right = $wert['right'];
		} else if(array_key_exists('recht',$wert)){
			$right = $wert['recht'];
		} else {
			$right = 6;
		}
		
		// Get the type
		if(array_key_exists('type',$wert)){
			$type = $wert['type'];
		} else {
			$type = 'output';
		}
		
		// Get required
		if(array_key_exists('required',$wert)){
			$required = $wert['required'];
		} else {
			$required = NULL;
		}
		
		$this->output .= CunddTemplate::__($wert,$right,$template,$type,$required);
		if(!$noOutput) echo $this->output;
		
		// DEBUGGEN
		if($say OR $this->debug){
			echo '$template:'.$template.'<br>$para:';
			CunddTools::pd($para);
		}
		// DEBUGGEN
		
		return $this;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Output aus. */
	public function render(){
		return $this->output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt eine Liste aller Benutzer aus.
	 * @param array $rowArray
	 * @param string $table_name
	 * @param array $table_cols
	 * @param boolean $kopf_zeile
	 * @param string|'none' $edit_call 
	 * @return string
	 */
	public static function show_table(array $rowArray,$table_name = 'table', array $table_cols = array(),$kopf_zeile = NULL, $edit_call = 'new CunddBenutzer_js(this)'){
		if(count($table_cols) > 0){
			// Beginn der Tabelle
			$output .= '<table class="'.$table_name.'">';
			
			// Kopf-Zeile anzeigen
			if($kopf_zeile){
				$output .= '<tr>';
				
				for($i = 0; $i < count($table_cols); $i++){
					$output .= '<th>'.$table_cols[$i].'</th>';
				}
				
				$output .= '</tr>';
			}
			
			// Werte anzeigen
			for($i = 0; $i < count($rowArray); $i++){ // Für jeden Benutzer eine Zeile
				$output .= '<tr id="'.$rowArray[$i]["schluessel"].'" name="'.$rowArray[$i]["schluessel"].'"';
				if($edit_call != 'none') $output .= 'onmouseover="'.$edit_call.'"';
				$output .= 'class="normal">';
				
				for($j = 0; $j < count($table_cols); $j++){ // Für jedes Feld eine Spalte
					$output .= '<td>'.$rowArray[$i][$table_cols[$j]].'</td>';
				}
				$output .= '</tr>';
			}
			
			$output .= '</table>';
		} else { // Alle anzeigen
			$outputTableBegin = '<table class="'.$table_name.'">';
			$outputRow = '';
			$outputHead = '';
			$headIsCreated = false;
			
			foreach($rowArray as $key => $row){
				// Für jede Zeile
				$outputRow .= '<tr id="'.$row["schluessel"].'" name="'.$row["schluessel"].'"';
				if($edit_call){$outputRow .= 'onmouseover="'.$edit_call.'"';}
				$outputRow .= 'class="normal">';
				
				
				foreach($row as $col => $value){ // Für jede Spalte
					if(!$headIsCreated){$outputHead .= '<th>'.$col.'</th>';}
					$outputRow .= '<td>'.$value.'</td>'; 
				}
				$outputRow .= '</tr>';
				$headIsCreated = true;
			}
			
			$outputTableEnd = '</table>';
			
			
			$output = $outputTableBegin;
			if($kopf_zeile){
				$output .= "<tr>$outputHead</tr>";
			}
			$output .= $outputRow;
			$output .= $outputTableEnd;	
		}
		
		
		echo $output;
		return $output;
		
	}
	public static function showTable(array $rowArray,$table_name = 'table', array $table_cols = array(),$kopf_zeile = NULL, $edit_call = 'new CunddBenutzer_js(this)'){
		return self::show_table($rowArray,$table_name,$table_cols,$kopf_zeile, $edit_call);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt eine Liste aller Benutzer aus. */
	public static function show_benutzer($benutzer){
		// Alle Benutzer in einer Tabelle ausgeben
		// Beginn der Tabelle
		$output .= '<table class="CunddBenutzer">';
		
		// Felder auslesen
		$standard_gruppe = "oeffentlich";
		if($_SESSION["gruppen"]){
			//$felder = CunddFelder::get_benutzer($_SESSION["gruppen"]);
			
			$felder = array();
			$felder_liste = CunddRechte::get_benutzer_felder();
			foreach($felder_liste as $feld){
				$felder[0][] = $feld['feld'];
			}
		} else {
			$felder = CunddFelder::get_benutzer($standard_gruppe);
		}
		
		
		// Kopf-Zeile anzeigen
		$kopf_zeile = true;
		if($kopf_zeile AND $benutzer[0]){
			$output .= '<tr>';
			for($i = 0; $i < count($felder[0]); $i++){
				// Bestimmte Felder auslassen
				if($felder[0][$i] == "passwort" OR $felder[0][$i] == "passwort wiederholen" OR 
					$felder[0][$i] == "passwort_wiederholen" OR $felder[0][$i] == "bildlink"){
					// Auslassen
				} else {
					$output .= '<th>'.$felder[0][$i].'</th>';
				}
			}
			$output .= '</tr>';
		}
		
		// Benutzer anzeigen
		for($i = 0; $i < count($benutzer); $i++){ // Für jeden Benutzer eine Zeile
			$output .= '<tr id="'.$benutzer[$i]["benutzer"].'" name="'.$benutzer[$i]["benutzer"].
				'" onmouseover="new CunddBenutzer_js(this)" class="normal">';
			for($j = 0; $j < count($felder[0]); $j++){ // Für jedes Feld eine Spalte
				// Bestimmte Felder auslassen
				if($felder[0][$j] == "passwort" OR $felder[0][$j] == "passwort wiederholen" OR 
					$felder[0][$j] == "passwort_wiederholen" OR $felder[0][$j] == "bildlink"){
					// Auslassen
				} else {
					$aktueller_feldname = $felder[0][$j];
					$output .= '<td>'.$benutzer[$i][$aktueller_feldname].'</td>';
				}
			}
			$output .= '</tr>';
		}
		
		$output .= '</table>';
		
		echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode blendet das Formular zum Erstellen bzw. Bearbeiten der Informationen 
	 eines Files ein. 
	 Da der Upload ohne Ajax funktioniert wird das Formular in einem iframe eingebettet, 
	 dessen Inhalt sich dann entsprechend ändert. */
	public static function file_input_form($kind){
		$inFunctionSecurity['allow_get'] = true;
		
		// Die Felder für das Formular auslesen
		if($kind == "files"){
			$felder_liste = CunddFelder::get_files();
		} else if($kind == "group"){
			$felder_liste = CunddFelder::get_files_group();
		}
		
		
		/* Überprüfen ob ein bereits existierendes File bearbeitet werden soll. Wenn ja 
		 wurde ein Parameter mit dem Titel des Files übergaben. */
		if($_POST["old_file_id"]){
			// Die alten File-Infos laden
			$old_file_id = $_POST["old_file_id"];
			$wert =  CunddFiles::get_daten($old_file_id);
		} else if(CunddConfig::get('CunddController_allow_get') AND $_GET["old_file_id"] AND $inFunctionSecurity['allow_get']){
			// Die alten File-Infos laden
			$old_file_id = $_GET["old_file_id"];
			$wert =  CunddFiles::get_daten($old_file_id);
		}
		if($old_file_id){
			/* Wenn bestimmte Parameter einer bereits vorhandenen Datei nicht verändert werden dürfen. */
		}
		
		
		
		// Überprüfen ob der Benutzer das Recht zum Upload eines neuen Files hat
		if(CunddGruppen::ist_in(CunddConfig::get("bedingung_neues_file")) OR
		   CunddGruppen::ist_in(1) OR 
		   CunddConfig::get("bedingung_neues_file") == 'n' AND 
		   $felder_liste
		   ){
			
			if($kind == "files"){
				/* Wenn ein bestehendes File verändert werden kann, wird eine entsprechende 
				 Nachricht angezeigt. */
				if($old_file_id){
					echo CunddTemplate::inhalte_einrichten(NULL, 6, "old_file", "output");
					/* Wenn noch keine Angaben gemacht wurden wird das Formular mit einer Aufforderung 
					 eingeblendet. */
				} else if(!$_POST){
					echo CunddTemplate::inhalte_einrichten(NULL, 6, "new_file", "output");
				}
			} else if($kind == "group"){
				/* Wenn eine bestehende Gruppe verändert werden kann, wird eine entsprechende 
				 Nachricht angezeigt. */
				if($old_file_id){
					echo CunddTemplate::inhalte_einrichten(NULL, 6, "old_group", "output");
					/* Wenn noch keine Angaben gemacht wurden wird das Formular mit einer Aufforderung 
					 eingeblendet. */
				} else if(!$_POST){
					echo CunddTemplate::inhalte_einrichten(NULL, 6, "new_group", "output");
				}
			}
			
			// Wenn $felder_liste = NULL ist kein Benutzer eingeloggt.
			if($felder_liste){
				echo '<div class="eintrag">';
				echo '<div id="CunddFileInput">';
				
				// Die Uploadify-JavaScript-Bibliothek einbinden
				$libUrl = CunddPath::getAbsoluteClassUrl().'CunddFiles/uploadify/jquery.uploadify.js';
				echo '<script type="text/javascript" src="'.$libUrl.'"></script>';
				
				echo '<form name="CunddFileInputFormular" id="CunddFileInputFormular" class="benutzer" 
					action="#" method="post"';
					/* */
				/* echo '<form name="CunddFileInputFormular" id="CunddFileInputFormular" class="benutzer" 
					action="" method="post"';
					/* */
				//echo 'enctype="multipart/form-data"';
				echo '>';
				
				if($kind == "files"){ /* Wenn der Typ des Objekts nicht "group" ist überprüfen
										ob ein File-Input angezeigt werden soll */
					// Vorschau bzw. File-Auswahl-Button anzeigen
					if($old_file_id){
						// Alte Datei übermitteln
						$wert["old_file_id"] = $old_file_id;
						echo CunddTemplate::inhalte_einrichten($wert, 6, "files_old_file_id", "output");
					} else {
						echo CunddTemplate::inhalte_einrichten($wert, 6, "files_userfile", "spezial");
					}
				}
			}
			
			for($i = 0; $i < count($felder_liste); $i++){
				$tag = "files_".$felder_liste[$i]["name"];
				$wert['field_name'] = $felder_liste[$i]["name"];
				
				// TODO: Check the rights of the current user for this file
				$recht = 6;
				
				// Überprüfen ob das Feld laut Config-File angezeigt werden soll
				if($kind == 'group'){
					if(CunddConfig::get("zeige_group_".$felder_liste[$i]["name"])){
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $tag, 
														  $felder_liste[$i]["type"]);
					}
				} else {
					if(CunddConfig::get("zeige_file_".$felder_liste[$i]["name"])){
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $tag, 
														  $felder_liste[$i]["type"]);
					}
				}
			}
			
			if($felder_liste){
				// Die Session-ID senden
				echo CunddTemplate::inhalte_einrichten($wert, 4, "session_id", "output");
				// Den Submit-Button anzeigen
				echo CunddTemplate::inhalte_einrichten($wert, 7, "files_submit", "spezial");
				
				echo '</form>
				</div>
				</div>
				<script type="text/javascript">
				var CunddFileInputFormular_obj;
				CunddFileInputFormular_obj = window.document.getElementById("CunddFileInputFormular");
				if(!CunddFileInputFormular_obj){
					CunddFileInputFormular_obj = CunddFileInputFormular;
				}
				';
				
				// Anhand von $kind überprüfen ob uploadify initialisiert werden soll.
				if($kind == "group" OR $old_file_id){
					echo 'CunddFileInputFormular_obj.onsubmit = function(){
							CunddFiles_js.eintrag_inhalt_aendern(this);
							return false;
						}';
				} else if($kind == "files"){
					echo 'CunddFiles_js.initUploadify(CunddFileInputFormular_obj);';
				} else {
					// FEHLER
				}
				
				
				echo '</script>';
				/* */
			} else {
				CunddTools::log_fehler("CunddTemplate",'$felder_liste is empty.');
			}
		} else {
			echo 'FALSCH';
			CunddTools::log_fehler("CunddTemplate","Not allowed to Upload new file. Permission denied.");
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode blendet das Formular zum Erstellen bzw. Bearbeiten eines Benutzers ein 
	wenn die Werte in $_POST leer sind. Ansonsten werden sie überprüft und ggfl. in 
	"CunddBenutzer::neu()" verarbeitet. */
	public static function benutzer_formular(){
		// Die Felder und dazugehörigen Rechte auslesen
		$felder_liste = CunddRechte::get_benutzer_felder();
		
		
		
		/* Überprüfen ob ein bereits existierender Benutzer bearbeitet werden soll. Wenn ja 
		wurde ein Parameter mit dem Namen des Benutzers übergaben. */
		if($_POST["alter_benutzer"]){
			$alter_benutzer = $_POST["alter_benutzer"];
			// Die alten Benutzerdaten laden
			$wert =  CunddBenutzer::get_daten($alter_benutzer);
			
			/* Der Name des Benutzers darf nicht verändert werden. Der Wert für dieses Recht 
			wird deshalb überschrieben. */
			$i = 0;
			while($felder_liste[$i]["feld"] == "benutzer"){
				$i++;
			}
			$felder_liste[$i-1]["rechte"] = 4;
			$felder_liste[$i-1]["required"] = 0;
			
		}
		
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Wenn $_POST nicht leer ist überprüfen ob die Daten korrekt eingegeben wurden.
		if($_POST){
			/* Den assoziativen Namen des Feldes "passwort wiederholen" das mit "_" übergeben 
			wird mit einem Leerzeichen speichern. Behebt einen Bug. */
			$_POST["passwort wiederholen"] = $_POST["passwort_wiederholen"];
			
			// Überprüfen ob keines der mit "required" markierten Felder leer ist
			for($i = 0; $i < count($felder_liste); $i++){
				if($felder_liste[$i]["required"]){
					if($_POST[$felder_liste[$i]["feld"]]){
						// Die Felder sind soweit ok
					} else {
						// Ein mit "required" markiertes Feld ist noch leer
						$fehler = "required";
					}
				}
			}
			
			
			// Überprüfen ob das Passwort mit der Wiederholung des Passworts übereinstimmt
			if($_POST["passwort"] != $_POST["passwort wiederholen"]){
				$fehler = "passwort";
			}
			
			
			// Überprüfen ob der Benutzername bereits vergeben ist
			mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'), 
				CunddConfig::get('mysql_passwort'));
			
			$anfrage = "SELECT benutzer FROM `".CunddConfig::get('mysql_database')."`.`".
				CunddConfig::get('prefix')."benutzer` WHERE benutzer = '".$_POST["benutzer"]."';";
			$resultat = mysql_query($anfrage);
			$fetch = mysql_fetch_array($resultat);
			
			/* Wenn die Antwort von MySQL = TRUE ist, ist der Benutzer schon vorhanden.
			Wenn die Variable $alter_benutzer aber einen Wert hat, wird der Fehler nicht aktiv. */
			if($fetch AND !$alter_benutzer){
				$fehler = "benutzer schon vorhanden";
			}
		}
		
		
		// Überprüfen ob der Benutzer das Recht zum Erstellen eines neuen Benutzers hat
		if(CunddGruppen::ist_in(CunddConfig::get("bedingung_neuer_benutzer")) OR
			CunddGruppen::ist_in(1) OR CunddConfig::get("bedingung_neuer_benutzer") == 'n' OR 
			$alter_benutzer
		){
			/* Wenn Angaben gemacht wurden und keine Fehler aufgetreten sind werden die Daten 
			verarbeitet. */
			/*if($_POST["passwort"] AND !$fehler){
				// Überprüfen ob ein alter Benutzer als Parameter angegeben wurde
				if($alter_benutzer){
					CunddBenutzer::edit($_POST);
				} else {
					CunddBenutzer::neu($_POST);
				}
			} else if($felder_liste){ 
			
			/* Wenn Angaben gemacht wurden und keine Fehler aufgetreten sind werden die Daten 
			verarbeitet. */
			if(($_POST["passwort"] AND !$fehler) OR ($alter_benutzer AND !$fehler) AND $_POST["passwort"]){
				// Überprüfen ob ein alter Benutzer als Parameter angegeben wurde
				// Die gruppen-Eingabe serialisieren
				/* Die Werte aller Checkboxen mit dem Namen "gruppen[]" werden in einem Array übergeben. 
				 Alle Werte der Elemente werden miteinander addiert. */
				for($i = 0; $i < count($_POST["gruppen"]); $i++){
					$gruppen += pow(2, $_POST["gruppen"][$i]);
				}
				// $eingabe["gruppen"] mit der Summe überschreiben 
				$wert["gruppen"] = $gruppen;
				$_POST["gruppen"] = $gruppen;
				
				if($alter_benutzer){// AND $_POST["daten_bearbeitet"] == "edit"){
					
					if(CunddBenutzer::edit($_POST)){
						echo CunddTemplate::inhalte_einrichten(NULL, 0, benutzer_edited, output);
					}
				} else {
					if(CunddBenutzer::neu($_POST)){
						echo CunddTemplate::inhalte_einrichten(NULL, 0, benutzer_gespeichert, output);
					}
				}
			} else if($felder_liste){ /* Sonst überprüfen ob Felder angezeigt werden können, also ob
										ein Benutzer eingeloggt ist und was bei den vorigen Angaben 
										nicht stimmte. */
				
				/* Wenn Angaben gemacht wurden und Fehler aufgetreten sind wird eine Fehlermeldung 
				ausgegeben und das Formular erneut angezeigt. */
				if($_POST["passwort"] AND $fehler){
					switch($fehler){
						case "required":
							echo CunddTemplate::inhalte_einrichten(NULL, 4, benutzer_neu_fehler_required, output);
							break;
						case "passwort":
							echo CunddTemplate::inhalte_einrichten(NULL, 4, benutzer_neu_fehler_passwort, output);
							break;
						case "benutzer schon vorhanden":
							echo CunddTemplate::inhalte_einrichten(NULL, 4, benutzer_neu_fehler_benutzer, output);
							break;
					}
					
					// Die alten Angaben per $wert übergeben
					$wert = $_POST;
					/* Die Werte aller Checkboxen mit dem Namen "gruppen[]" werden in einem Array übergeben. 
					Alle Werte der Elemente werden miteinander addiert. */
					for($i = 0; $i < count($wert["gruppen"]); $i++){
						$gruppen += pow(2, $wert["gruppen"][$i]);
					}
					// $eingabe["gruppen"] mit der Summe überschreiben 
					$wert["gruppen"] = $gruppen;
				}
				
				
				/* Wenn ein bestehender Benutzer verändert werden kann, wird eine entsprechende 
				Nachricht angezeigt. */
				if($alter_benutzer){
					echo CunddTemplate::inhalte_einrichten(NULL, 4, benutzer_edit, output);
				/* Wenn noch keine Angaben gemacht wurden wird das Formular mit einer Aufforderung 
				eingeblendet. */
				} else if(!$_POST){
					echo CunddTemplate::inhalte_einrichten(NULL, 4, benutzer_neu, output);
				}
				
				// Wenn $felder_liste = NULL ist kein Benutzer eingeloggt sonst das Formular anzeigen.
				if($felder_liste){
					echo '<div class="eintrag">';
					echo '<div id="CunddBenutzer">
						<form name="benutzer" id="CunddBenutzerFormular" class="benutzer" 
							action="#" method="post">';
						if($alter_benutzer){
							// Alter-Benutzer übermitteln
							$wert["alter_benutzer"] = $alter_benutzer;
							echo CunddTemplate::inhalte_einrichten($wert, 4, alter_benutzer, output);
						}
				}
				
				for($i = 0; $i < count($felder_liste); $i++){
					$tag = $felder_liste[$i]["feld"];
					echo CunddTemplate::inhalte_einrichten($wert, $felder_liste[$i]["rechte"], $tag, 
						$felder_liste[$i]["type"], $felder_liste[$i]["required"]);
				}
				
				if($felder_liste){				
					// Den Submit-Button anzeigen
					echo CunddTemplate::inhalte_einrichten($wert, 7, submit, spezial);
					
					echo '</form>
						</div>
						</div>
						<script type="text/javascript">
						var CunddBenutzerFormular = window.document.getElementById("CunddBenutzerFormular");
						CunddBenutzerFormular.onsubmit = function(){
							CunddBenutzer_js.eintrag_inhalt_aendern(this);
							return false;
						}
						</script>';
				}
			}
		} else {
			echo 'FALSCH';
			CunddTools::log_fehler("CunddTemplate","Not allowed to create new user. Permission denied.");
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt eine Rückmeldung entsprechend dem übergebenen Parameter. Wird kein 
	Parameter übergeben wird das Login-Formular ausgegeben, wenn der Parameter "fehler" ist 
	wird eine Fehlermeldung und das Login-Formular ausgegeben; ist der Parameter "korrekt" 
	wird die Meldung des erfolgreichen Logins angezeigt; ist er "normal" wird die Auf-
	forderung zum Eingebene der Benutzer-Daten eingeblendet. */
	public static function login(){
		echo '<div class="eintrag">';
		// Wenn ein Parameter übergeben wurde überprüfen welcher es ist
		if(func_num_args()){
			if(func_get_arg(0) == "korrekt"){ // Login Korrekt
				echo CunddTemplate::inhalte_einrichten($wert, $recht, "korrekt", output);
				echo '<script type="text/javascript">
						setTimeout(CunddLogoutRedirect, Cundd_redirect_zeit);
						function CunddLogoutRedirect(){
							window.location.href = "index.php";
							//window.location = "'.$_SERVER['HTTP_REFERER'].'";
						}
					</script>';
			} else if(func_get_arg(0) == "fehler"){ // Login inkorrekt
				echo CunddTemplate::inhalte_einrichten($wert, $recht, "fehler", output);
				CunddTemplate::login_formular();
			} else if(func_get_arg(0) == "normal"){ // Aufforderung zur Eingabe
				echo CunddTemplate::inhalte_einrichten($wert, $recht, "normal", output);
				CunddTemplate::login_formular();
			} else { // Das Skript wurde mit einem ungültigen Parameter aufgerufen
				echo CunddTemplate::inhalte_einrichten($wert, $recht, "verboten", output);
			}
		} else { // Kein Parameter wurde übergeben
			CunddTemplate::login_formular();
		}
		echo '</div>';
	}		
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt das Formular zur Eingabe der Login-Daten */
	public static function login_formular(){
		// Das Formular
		echo '<div class="Cunddlogin">
				<form action="#" name="CunddLoginFormular" id="CunddLoginFormular" 
					method="post">';
		echo		'<label for="CunddLoginFormular_benutzer">Benutzer: </label><input type="text" 
						id="CunddLoginFormular_benutzer" name="CunddLoginFormular_benutzer" value="'.
						$_POST['CunddLoginFormular_benutzer'].'" />';
		echo		'<label for="CunddLoginFormular_passwort">Passwort: </label><input type="password" 
						id="CunddLoginFormular_passwort" name="CunddLoginFormular_passwort" value="'.
						$_POST['CunddLoginFormular_passwort'].'" />';
		/*echo		'<input type="checkbox" id="CunddLoginFormular_eingeloggt_bleiben" 
						name="CunddLoginFormular_eingeloggt_bleiben" value="'.
						$_POST['CunddLoginFormular_eingeloggt_bleiben'].'" />
						<label for="CunddLoginFormular_eingeloggt_bleiben"> Eingeloggt bleiben</label>'; // */
		echo		'<input id="CunddLogin_abschicken" type="submit" value="abschicken" />
				</form>
			</div>';
			
		// Javascript-Befehl zum automatischen Auswählen des Benutzer-Feldes
		echo '<script type="text/javascript">
			new CunddLogin_js("CunddLoginFormular");
			</script>';
		
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ist ein Alias für inhalte_einrichten() */
	/**
	 * @see inhalte_einrichten()
	 */
	public static function __($wert, $recht, $tag, $type = 'output', $required = NULL){
		return CunddTemplate::inhalte_einrichten($wert, $recht, $tag, $type, $required);
	}
	/**
	 * @see inhalte_einrichten()
	 */
	public static function printContent($wert, $recht, $tag, $type = 'output', $required = NULL){
		$output = CunddTemplate::inhalte_einrichten($wert, $recht, $tag, $type, $required);
		echo $output;
		return $output;
	}
	/**
	 * @see inhalte_einrichten()
	 */
	public static function p($wert, $recht, $tag, $type = 'output', $required = NULL){
		$output = CunddTemplate::inhalte_einrichten($wert, $recht, $tag, $type, $required);
		echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt welche Form die Ausgabe des übergebenen Parameters haben muss. 
	 * Sie gibt die passende Umgebung (z.B. Textarea) aus und ruft die Methode "inhalt_ausfuellen" 
	 * auf welche die jeweiligen Klassen und Inhalte ausgibt. Der Parameter $recht gibt außerdem
	 * an ob der derzeitige Benutzer das Recht hat den Eintrag zu verändern (wenn der Wert "6"
	 * ist darf der Benutzer schreiben).
	 * @param array $wert A dicitionary of userdata passed to the output template
	 * @param int $recht Indicates the rights a user has for the specific template
	 * @param string $tag The name of the template
	 * @param $type The kind of output
	 * @param boolean $required (optional) May be used for input fields
	 * @return void
	*/
	public static function inhalte_einrichten($wert, $recht, $tag, $type = 'output', $required = NULL){
		/* Verschiedene Arten des Outputs sind möglich:
			1.	$type = "output": Output -> Text / Input -> Kein Input
			2.	$type = "textarea": Out/Input -> Textarea
			3.	$type =	"text": Out/Input -> Text
			4.	$type = "checkbox": Out/Input -> Sonderformen (Checkboxen, Radiobuttons, etc.)
		*/
		
		/* Überprüfen ob das anzuzeigende Feld als "required" markiert ist. Also vom Benutzer 
		asugefüllt werden muss. */
		/*
		if(func_num_args() > 4){
			$required = func_get_arg(4);
		}
		/* */
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// 1. Output -> Text / Input -> Kein Input
		if($type == "output"){
			/* Bei reinem Output wird hier kein "Rahmen" vorgegeben. Die Formatierung geschieht 
			nur in "inhalt_ausfuellen". */
			$output .= CunddTemplate::inhalt_ausfuellen($wert, $tag);
			
		} else 
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// 2.	Out/Input -> Textarea
		if($type == "textarea"){
			/* Die Textarea wird geöffnet. Das Attribut "name" setzt sich zusammen aus 
			"eintrag" + dem Schlüssel des aktuellen Eintrags + dem Namen des jeweiligen 
			tags. */
			$output .= '<textarea name="'.$tag.'" id="'.$tag.$wert["schluessel"].'" ';
			
			// Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt
			if($recht < 6){
				// Der Benutzer darf NICHT schreiben
				$output .= ' readonly="readonly" ';
			}
			
			$output .= 'class="';
			// Die jeweilige CSS-Klasse wird von "inhalt_ausfuellen()" eingetragen
			$output .= CunddTemplate::inhalt_ausfuellen($wert, $tag);
			
			// Die Textarea schließen
			$output .= '</textarea>';
			
		} else 
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// 3.	Out/Input -> Text
		if($type == "text" OR $type == "password"){
			/* Eine einzeilige Texteingabe. Erstellt wird ein Label mit dem Namen des Feldes 
			beginnend mit einem Großbuchstaben. */
			$output .= '<label for="'.$tag.$wert["schluessel"].'">'.ucfirst($tag);
			if($required){
				$output .= ' *';
			}
			$output .= ': </label><input type="'.$type.'" name="'.$tag.'" id="'.$tag.$wert["schluessel"].'"';
			
			// Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt
			if($recht < 6){
				$output .= ' readonly="readonly" ';
			}
			
			$output .= 'class="';
			// Die jeweilige CSS-Klasse wird von "inhalt_ausfuellen()" eingetragen
			$output .= CunddTemplate::inhalt_ausfuellen($wert, $tag, $recht);
			
			// Das Feld schließen
			$output .= '" />';
			
			//$output .= '<br />';
			
		} else 
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// 4.	Out/Input -> Sonderformen (Checkboxen, Radiobuttons, etc.)
		if ($type == "spezial" OR $type == "special" OR $type == "bild" OR $type == "checkbox" OR
			$type == "select" OR $type == "select multiple"){
			$output .= CunddTemplate::inhalt_ausfuellen($wert, $tag, $recht, $required);
			
		} else 
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// 5.	Out/Input -> TinyMCE
		if (strtolower($type) == "tinymce" OR strtolower($type) == "rte"){
			/* Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt 
			 * und ein statischer div wird ausgegeben. Ein statischer div wird auch ausgegeben 
			 * wenn TinyMCE deaktiviert ist. */
			if($recht < 6 OR CunddConfig::get('CunddTinyMCE_enable') == false){ // Nicht Schreiben
				$output .= '<div name="'.$tag.'" id="'.$tag.$wert["schluessel"].'" class="'.$tag.'">';
				$output .= $wert[$tag]; // Der Inhalt
				$output .= '</div>';
				
			} else { // Schreiben erlaubt
				/* TEXTAREA */
				$output .= '<textarea name="'.$tag.'" id="'.$tag.$wert["schluessel"].'_input" class="'.$tag.' ';
				if(CunddConfig::get('CunddTinyMCE_enable')){ // Die TinyMCE-Klasse
					$output .= CunddConfig::get('CunddTinyMCE_initForCSSClass');
				}
				$output .= '" style="display:none;" cols="50">'.$wert[$tag].'</textarea>';
				/* */
				
				
				/* DIV */
				$output .= '<div name="'.$tag.'" id="'.$tag.$wert["schluessel"].'_output" class="'.$tag;
				
				if(CunddConfig::get('CunddTinyMCE_enable')){ // Die TinyMCE-Klasse
					$output .= ' '.CunddConfig::get('CunddTinyMCE_initForCSSClass');
				}
				/* */
				$output .= '">';
				$output .= $wert[$tag]; // Der Inhalt
				$output .= '</div>';
				/* */
			}
			
		} else {
			// Ein Fehler trat auf
			$output .= "<h2>CunddTemplate: Beim einrichten des tag $tag mit dem Typen $type trat ein Fehler auf.</h2><br />";
			CunddTools::error('CunddTemplate',"Error while preparing the output of tag $tag with the type $type");
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Das Ergebnis zurückgeben
		return $output;
	}
	
	
	
	//MW
	//MW
	//MW
	//MW
	//MW
	//MW
	//MW
	//MW
	//MW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ermittelt gibt das grafische Element entsprechend dem übergebenen tag 
	 * aus. Zuerst wird überprüft ob das erweiterte Template-System aktiviert ist, dann ob 
	 * die entsprechende Template-Datei existiert. Wenn eine der beiden Bedingungen nicht 
	 * erfüllt wird werden die Elemente mittels switch() ausgegeben. */ 
	public static function inhalt_ausfuellen($wert, $tag, $recht = NULL, $required = NULL){
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Überprüfen ob das erweiterte Template angewendet wird und die Datei gefunden wurde
		$advancedResult = false;
		if(CunddConfig::__('CunddTemplate_advanced_enabled')){
			$advancedResult = CunddTemplate::advancedTemplate($wert, $tag, $recht, $required); // returns false if file not exists
			if($advancedResult){
				$output .= $advancedResult;
			}
		} else {
			$advancedResult = false;
		}
		
		
		if(!$advancedResult){
			switch($tag){
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// LINKS
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "links_head":
					// Output -> Text / Input -> Kein Input
					$output .= '<div class="CunddLink" id="'.CunddConfig::get('prefix').$wert[tabelle].'">';
					break;
				
				case "links_leer_offen":
					// Output -> Text / Input -> Kein Input
					// Öffnet einen leeren div
					$output .= '<div>';
					break;
				
				case "links":
					// Output -> Text / Input -> Kein Input
					$output .= '<div class="link ';
					if($wert["parent"]) $output .= 'isChild childOf'.$wert["parent"].' ';
					if($wert["depth"]) $output .= 'withDepth'.$wert["depth"].' ';
					if($wert["children"]) $output .= 'hasChildren numChildren'.$wert["children"];
					$output .= '" id="CunddLink'.$wert["schluessel"].'">';
					
					/* Dieser div ist "firstChild" dieses Links und speichert den auszführenden 
					Befehl im "class"-Attribut. */
					$output .= '<div class="'.$wert["link"].'"></div>';
					
					$output .= '<a class="CunddLink_text" name="CunddLink_button" href="#">'.$wert["name"].'</a>';
					$output .= '</div>';
					break;
				
				case "links_popup":
					// Output -> Text / Input -> Kein Input
					$output .= '<script type="text/javascript">
						link_id = "CunddLink'.$wert["parent"].'";
						parent_link = window.$(link_id);
						link_popup = window.document.createElement("div");
						link_popup.className = "popup";
						link_popup.id = "CunddLink'.$wert["schluessel"].'";
						
						
						   <div class="popup" id="CunddLink'.$wert["schluessel"].'">';
					
					/* Dieser div ist "firstChild" dieses Links und speichert den auszführenden 
					Befehl im "class"-Attribut. */
					$output .= '<div class="'.$wert["link"].'"></div>';
					
					$output .= '<a class="CunddLink_text" name="CunddLink_button" href="#">'.$wert["name"].'</a>';
					$output .= '</div>';
					break;
				
				case "links_foot":
					// Output -> Text / Input -> Kein Input
					/* Den "eintrag"-div schließen und somit die optische Trennung zum
					nächsten Eintrag erstellen. */
					$output .= '</div>';
					break;
					
				case "links_hardlink":
					$output .= $wert[$tag];
					break;
				// LINKS
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// CONTENT-TOOLBAR
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "content_toolbar":
					// Output -> Text / Input -> Kein Input
					$output .= '<div class="contenttoolbar">';
					/*$output .= '<h1>Content-Toolbar der Tabelle "'.$wert["tabelle"].
						'"</h1>'; // */
					$output .= '<p>Neuer Eintrag <input type="button" value="+" id="neuer_eintrag_anzeigen_'.
						CunddConfig::get('prefix').$wert[tabelle].
						'" name="neuer_eintrag" /></p>';
					$output .= '</div>';
					break;
				// CONTENT-TOOLBAR
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// EINTRAG
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "eintrag_head":
					// Output -> Text / Input -> Kein Input
					
					// Anker
					$replaceChars = array(' ','/','"','\'');
					$titleSave = str_replace($replaceChars,'_',$wert["title"]);
					$output .= '<a name="'.$titleSave.'"></a>';
					$output .= '<div class="eintrag" id="eintrag'.$wert[schluessel].'"';
					// Wenn dieser Eintrag der neue, leere ist -> verstecken
					if(!$wert[schluessel]){
						$output .= ' style="display:none;" ';
					}
					$output .= '>';
					$output .= '<form ';
					$output .= 'action="#" ';
					$output .= 'method="post" name="formular'.$wert[schluessel].
						'" id="formular'.$wert[schluessel].'" class="eintragForm entryForm">';
					break;
				
				case "eintrag_foot":
					// Output -> Text / Input -> Kein Input
					/* Den "eintrag"-div schließen und somit die optische Trennung zum
					nächsten Eintrag erstellen. */
					$output .= '</form></div>';
					break;
					
				case "tabelle":
					// Output -> Text / Input -> Kein Input
					/* Den Namen der MySQL-Tabelle mitschicken. */
					$output .= '<input type="hidden" name="'.$tag.'" value="'.$wert[$tag].'">';
					
					// Den Schlüssel mitschicken
					$output .= '<input type="hidden" name="schluessel" value="'.$wert["schluessel"].'">';
					
					// Wenn die Recht nicht explizit angezeigt werden -> hier mitschicken
					if(!CunddConfig::get("zeige_rechte") OR !CunddConfig::get("zeige_zusatzinfos")){
						$output .= '<input type="hidden" name="rechte" value="'.$wert["rechte"].'">';
					}
					// Wenn die Recht nicht explizit angezeigt werden -> hier mitschicken
					if(!CunddConfig::get("zeige_gruppe") OR !CunddConfig::get("zeige_zusatzinfos")){
						$output .= '<input type="hidden" name="gruppe" value="'.$wert["gruppe"].'">';
					}
					break;
				// EINTRAG
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// TOOLBAR
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "toolbar":
					// Output -> Text / Input -> Kein Input
					//*
					if($recht == 6 OR $recht == 7){
						$output .= '<div class="toolbar" name="toolbar" id="'.$tag.$wert["schluessel"].'">';
						/*// Überschrift
						$output .= '<h1>Toolbar id="'.$tag.$wert["schluessel"].'"</h1>';
						// Save-Button */
						$output .= '<input type="button" class="input toolbar_button_save" name="toolbar_button_save" 
								id="toolbar_save'.$wert["schluessel"].'" value="save" />';
						// Löschen-Button */
						$output .= '<input type="button" class="input toolbar_button_delete" name="toolbar_button_delete" 
								id="toolbar_delete'.$wert["schluessel"].'" value="-" />';	
						$output .= '</div>';//*/
					}
					break;
				// TOOLBAR
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// INHALT
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "title":
					// Out/Input -> Textarea
					$output .= 'title" cols="50" rows="1">'.$wert[$tag];
					break;
				
				case "subtitle":
					// Out/Input -> Textarea
					$output .= 'subtitle" cols="50">'.$wert[$tag];
					break;
				
				case "beschreibung":
					// Out/Input -> Textarea
					$output .= 'beschreibung" cols="50">'.$wert[$tag];
					break;
				
				case "bildlink":
					// Out/Input -> Sonderform
					
					// Überprüfen ob eine fileId angegeben wurde
					if($wert[$tag]){
						$output .= '<div class="bildlink">';
						
						$maxHeight = CunddConfig::get('CunddInhalt_max_eintrag_image_height');
						$maxWidth = CunddConfig::get('CunddInhalt_max_eintrag_image_width');
						
						if(CunddConfig::get(galerie_link_aktiv)){
							$output .= '<a href="'.CunddConfig::get(galerie_link).$wert[$tag].'" target="_self">';
						}
						$output .= '<img ';
							if($maxHeight) $output .= 'height="'.$maxHeight.'" ';
							if($maxWidth) $output .= 'width="'.$maxWidth.'" ';
						
						$attributes = CunddFiles::getAttributesOfFile($wert[$tag]);
						$output .= 'src="'.CunddFiles::get_real_path_by_fileId($wert[$tag], NULL, $attributes['forceRemotePath']).'" />';
						if(CunddConfig::get(galerie_link_aktiv)){
							$output .= '</a>';
						}
					}
					
					// Wenn der Benutzer das Recht zum Schreiben hat wird das Input-Feld angezeigt
					if($recht == 6 OR $recht == 7){
						$output .= '<label for="'.$tag.$wert[$tag].'">'.$tag.': </label><input type="'.
						$type.'" name="'.$tag.'" id="'.$tag.$wert[$tag].'"';
						// Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt
						if($recht != 6 AND $recht != 7){
							$output .= ' readonly="readonly" ';
						}
						$output .= 'value="'.$wert[$tag].'" class="eintragfeld" />';
					}
					
					if($wert[$tag]){
						//$output .= '<div class="bildlink">';
						$output .= '</div>';
					}
					
					// Ein div der das float-Problem verhindert
					$output .= '<div class="clear"></div>';
					 
					break;
				
				case "text":
					// Out/Input -> Textarea
					$output .= 'text" cols="50">';
					$output .= $wert[$tag];
					/* */
					
					/* MIT TINYMCE */
					/* Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt 
					 * und ein statischer div wird ausgegeben. */
					/*if($recht < 6){
						$output .= '<div name="'.$tag.'" id="'.$tag.$wert["schluessel"].'" class="text ';
						if(CunddConfig::get('CunddTinyMCE_enable')){ // Die TinyMCE-Klasse
							$output .= CunddConfig::get('CunddTinyMCE_initForCSSClass');
						}
						$output .= '">';
						$output .= $wert[$tag]; // Der Inhalt
						$output .= '</div>';
					} else { // Die Textarea anzeigen
						$output .= '<textarea name="'.$tag.'" id="'.$tag.$wert["schluessel"].'" class="text ';
						if(CunddConfig::get('CunddTinyMCE_enable')){ // Die TinyMCE-Klasse
							$output .= CunddConfig::get('CunddTinyMCE_initForCSSClass');
						}
						$output .= '" cols="50">';
						$output .= $wert[$tag]; // Der Inhalt
						$output .= '</textarea>';
					}
					/* */
					break;
				// INHALT
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// QUOTE
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "quote_head":
					// Output -> Text / Input -> Kein Input
					$output .= '<div class="quote">';
					break;
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "erstellung":
					// Output -> Text / Input -> Kein Input
					$output .= 'Erstellt';
					
					if(CunddConfig::get(zeige_ersteller)){
						$output .= CunddTemplate::inhalte_einrichten($wert, $recht, ersteller);
					}
					if(CunddConfig::get(zeige_erstellungsdatum)){
						$output .= CunddTemplate::inhalte_einrichten($wert, $recht, erstellungsdatum);
					}
					if(CunddConfig::get(zeige_erstellungszeit)){
						$output .= CunddTemplate::inhalte_einrichten($wert, $recht, erstellungszeit);
					}
					break;
					
				
					case "ersteller":
						// Wenn es nicht Neu = Leer ist
						if($wert[$tag]){
							$output .= ' von '.$wert[$tag];
						}
						break;
					
					case "erstellungsdatum":
						// Wenn es nicht Neu = Leer ist
						if($wert[$tag]){
							$output .= ' am '.$wert[$tag];
						}
						break;
					
					case "erstellungszeit":
						// Wenn es nicht Neu = Leer ist
						if($wert[$tag]){
							$output .= ' um '.$wert[$tag];
						}
						break;
					
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "bearbeitung":
					// Output -> Text / Input -> Kein Input
					//$output .= '. ';
					$output .= ' Bearbeitet';
					
					if(CunddConfig::get(zeige_bearbeiter)){
						$output .= CunddTemplate::inhalte_einrichten($wert, $recht, bearbeiter);
					}
					if(CunddConfig::get(zeige_bearbeitungsdatum)){
						$output .= CunddTemplate::inhalte_einrichten($wert, $recht, bearbeitungsdatum);
					}
					if(CunddConfig::get(zeige_bearbeitungszeit)){
						$output .= CunddTemplate::inhalte_einrichten($wert, $recht, bearbeitungszeit);
					}
					break;
					
				
					case "bearbeiter":
						// Wenn es nicht Neu = Leer ist
						if($wert[$tag]){
							$output .= ' von '.$wert[$tag];
							$output .= '<input type="hidden" name="'.$tag.'" value="'.$wert[$tag].'">';
						}
						break;
					
					case "bearbeitungsdatum":
						// Wenn es nicht Neu = Leer ist
						if($wert[$tag]){
							$output .= ' am '.$wert[$tag];
						}
						break;
					
					case "bearbeitungszeit":
						// Wenn es nicht Neu = Leer ist
						if($wert[$tag]){
							$output .= ' um '.$wert[$tag];
						}
						break;
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "eventdatum":
					$output .= 'eintragfeld" value="'.$wert[$tag];
					break;
				
				
				case "quote_foot":
					// Output -> Text / Input -> Kein Input 
					//$output .= '. '; // Der Punkt beendet den Satz der Bearbeitungsdaten
					$output .= '</div>';
					break;
				// QUOTE
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// ZUSATZINFOS
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "rechte":
					// Out/Input -> Sonderform
					$output .= '<label for="'.$tag.$wert["schluessel"].'">'.$tag.': </label><input type="'.
						$type.'" name="'.$tag.'" id="'.$tag.$wert["schluessel"].'"';
					// Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt
					if($recht != 6 AND $recht != 7){
						$output .= ' readonly="readonly" ';
					}
					$output .= 'value="'.$wert[$tag].'" class="eintragfeld" />';
					break;
				
				case "gruppe":
					// Output -> Text / Input -> Kein Input
					$output .= '<div class="quote">Gruppe: '.$wert[$tag].'</div>';
					break;
				
				case "sprache":
					// Out/Input -> Text
					$output .= 'eintragfeld" value="'.$wert[$tag];
					break;
				
				case "schluessel":
					// Output -> Text / Input -> Kein Input
					// Wenn es nicht Neu = Leer ist
					if($wert[$tag]){
						$output .= '<div class="quote">Schl&uuml;ssel: '.$wert[$tag].'</div>';
					}
					break;
				// ZUSATZINFOS
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// BENUTZERINFOS
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "benutzer_neu_fehler_required":
					$output .= '<p class="title">Bitte f&uuml;llen Sie alle Felder aus die mit * gekennzeichnet sind!</p>';
					break;
				
				case "benutzer_neu_fehler_passwort":
					$output .= '<p class="title">&Uuml;berpr&uuml;fen Sie die Eingabe Ihres Passworts. Die Werte stimmen 
						nicht &uuml;berein.</p>';
					break;
					
				case "benutzer_neu_fehler_benutzer":
					$output .= '<p class="title">Der von Ihnen gew&auml;hlte Benutzername ist leider bereits vergeben. 
						Bitte w&auml;hlen Sie einen anderen.</p>';
					break;
				
				case "benutzer_neu":
					$output .= '<p class="title">Geben Sie hier die Daten f&uuml;r den neuen Benutzer ein. Die mit * gekennzeichneten 
						Felder m&uuml;ssen ausgef&uuml;llt werden!</p>';
					break;
					
				case "benutzer_edit":
					$output .= '<p class="title">Geben Sie hier die neuen Daten f&uuml;r den Benutzer ein. Der Benutzername 
						kann nicht ge&auml;ndert werden. Die mit * gekennzeichneten Felder m&uuml;ssen 
						ausgef&uuml;llt werden!</p>';
					break;
					
				case "benutzer_edited":
				case "benutzer_gespeichert":
					$output .= '<p class="title">Die Benutzerdaten wurden gespeichert!</p>';
					break;
								
				case "anzahl_eintraege":
					// Output -> Text / Input -> Kein Input
					// Wenn es nicht Neu = Leer ist
					if($wert[$tag]){
						$output .= 'Anzahl der Eintr&auml;ge: '.$wert[$tag];
					}
					break;
					
				// Out/Input -> Text
				case "benutzer":
				case "passwort":
				case "passwort wiederholen":
				case "anrede": // Evtl. Drop-down
				case "vorname":
				case "nachname":
				case "firma":
				case "abteilung":
				case "email":
				case "telefon":
				case "handy":
				case "adresse":
				case "plz":
				case "ort":
				case "staat":
				case "sprache": // Evtl. Drop-down
				case "geburtstag":
				case "homepage":
				case "chat":
				case "bildlink":
					$output .= 'benutzerinfo" value="'.$wert[$tag];
					break;
				
				
				case "aktiv":
					// Out/Input -> Sonderform
					$output .= '<input type="checkbox" name="aktiv" id="aktiv_nummer_'.$i.'" value="1"';
					if($wert[$tag]){ // Bisheriger Wert ermitteln
						$output .= 'checked="checked" ';
					}
					if($recht < 6){ // Schreibrecht prüfen
						$output .= 'disabled="disabled" ';
					}
					
					$output .= '"/>';
					$output .= '<label for="aktiv_nummer_'.$i.'"> Benutzer freischalten</label><br />';
					break;
					
					
				case "alter_benutzer":
					// Output -> Text / Input -> Kein Input
					
					$output .= '<input type="hidden" name="alter_benutzer" value="'.$wert[$tag].'" />';
					break;
					
					
				case "hauptgruppe":
					// Out/Input -> Sonderform
					// Label
					$output .= '<label for="eintrag-'.$wert["schluessel"].'-'.$tag.'">'.ucfirst($tag);
						if($required){ // Überprüfen ob "required"
							$output .= ' *';
						}
						$output .= ': </label>';
					// Input
					$output .= '<select name="hauptgruppe" class="" id="eintrag-'.
							$wert["schluessel"].'-'.$tag.'"';
						if($recht < 6){ // Schreibrecht prüfen
							$output .= ' disabled="disabled" ';
						}
						$output .=  '>';
					// Optionen
					for($i = 0; $i < count($alle_gruppen = CunddGruppen::get_all()); $i++){
						$output .= '<option value="'.$alle_gruppen[$i]["gruppeid"].'">'.
							$alle_gruppen[$i]["gruppenname"].'</option>';
					}
					$output .= '</select>';
					$output .= '<br />';
					break;
				
				
				case "gruppen":
					// Out/Input -> Sonderform
					// CHECKBOX-VARIANTE
					if(count($alle_gruppen = CunddGruppen::get_all())){
						$output .= '<p>Weiter Gruppen:</p>';
					}
					for($i = 0; $i < count($alle_gruppen = CunddGruppen::get_all()); $i++){
						$output .= '<input type="checkbox" name="gruppen[]" id="gruppen_nummer_'.$i.'" value="'.
							pow(2, $alle_gruppen[$i]["gruppeid"]).'" ';
						
						// Bisheriger Wert ermitteln
						if(floor($wert["gruppen"] / pow(2, $alle_gruppen[$i]["gruppeid"])) % 2){
							$output .= 'checked="checked" ';
						}
						if($recht < 6){ // Schreibrecht prüfen
							$output .= 'disabled="disabled" ';
						}
						
						$output .= '"/>';
						
						// Label
						$output .= '<label for="gruppen_nummer_'.$i.'">'.$alle_gruppen[$i]["gruppenname"].'</label>';
						$output .= '<br />';
					}
					break;//*/
					
					/* SELECT-VARIANTE
					// Label
					$output .= '<label for="eintrag-'.$wert["schluessel"].'-'.$tag.'">'.ucfirst($tag);
						if(func_num_args() > 3 AND func_get_arg(3)){ // Überprüfen ob "required"
							$output .= ' *';
						}
						$output .= ': </label>';
					// Input
					$output .= '<select name="gruppen[]" size="5" multiple="multiple" class="" id="eintrag-'.
							$wert["schluessel"].'-'.$tag.'"';	
						// Wenn der Wert des Parameters "recht" nicht 6 ist, ist das Schreiben nicht erlaubt
						if($recht < 6){
							$output .= ' disabled="disabled" ';
						}
						$output .=  '>';
					// Optionen
					for($i = 0; $i < count($alle_gruppen = CunddGruppen::get_all()); $i++){
						$output .= '<option value="'.pow(2, $alle_gruppen[$i]["gruppeid"]).'">'.
							$alle_gruppen[$i]["gruppenname"].'</option>';
					}
					$output .= '</select>';
					break;//*/
				
				
				case "submit":
					$output .= '<input id="CunddBenutzer_submit" type="submit" value="';
					$output .= 'ok';
					$output .= '" />';
					break;
					
				case "benutzer_formular_hidden":
					$output .= '<input type="hidden" name="benutzer_formular_hidden" value="1" />';
					break;
				// BENUTZERINFOS
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// LOGIN
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "korrekt":
					$output .= '<p class="title">Sie haben sich erfolgreich eingeloggt!</p>';
					break;
					
				case "fehler":
					$output .= '<p class="title">Fehler! Bitte überprüfen Sie Ihre Eingabe!</p>';
					break;
					
				case "normal":
					$output .= '<p class="title">Bitte geben Sie Ihre Login-Daten ein!</p>';
					break;
					
				case "verboten":
					$output .= '<p class="title">Vorgang nicht erlaubt! Falscher Parameter.</p>';
					break;
					
				case "logout":	
					$output .= '<p class="title">Sie haben sich erfolgreich ausgeloggt!</p>';
					break;
				// LOGIN
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// MSG
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "msg_no_new_messages":
					$output .= '<p class="title">'.$wert.'</p>';
					break;
				case "msg_new_message_preview_head":
					$output .= '<p class="title">'.$wert.'</p>';
					$output .= '<table class="CunddMSG">';
					break;
					
				case "msg_detail_from":
					$output .= '<p><b>From: </b>'.$wert.'</p>';
					break;
				case "msg_detail_to":
					$output .= '<p><b>To: </b>'.$wert.'</p>';
					break;
				case "msg_detail_to_group":
					$output .= '<p><b>To the groups: </b>'.$wert.'</p>';
					break;
				case "msg_detail_date":
					$output .= '<p><b>Date: </b>'.$wert.'</p>';
					break;
				case "msg_detail_subject":
					$output .= '<p><b>Subject: </b>'.$wert.'</p>';
					break;
				case "msg_detail_content":
					$output .= '<hr />';
					$output .= '<p><b>Content: </b><br />';
					$output .= $wert;
					$output .= '</p>';
					$output .= '<hr />';
					break;
				case "msg_detail_attachment":
					$output .= '<p><b>Attachment: </b>'.$wert.'</p>';
					break;
				// MSG
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// FILES
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "files_success":
					$output .= '<p class="title">'.CunddLang::get("files_success").'</p>';
					break;
					
				case "files_old_file_success":
					$output .= '<p class="title">'.CunddLang::get("files_old_file_success").'</p>';
					break;
					
				case "files_old_file_error": 
					$output .= '<p class="title">'.CunddLang::get("files_old_file_error").'</p>';
					break;
					
				
				case "new_file":
				case "files_new":
					$output .= '<p class="title">'.CunddLang::get("files_new_file").'</p>';
					break;
				
				case "old_file":
				case "files_edit":
					// Ein Zurück-Link in Text-Form
					$output .= '<p class="title">'.CunddLang::get("files_old_file").'</p>';
					break;
					
				case "new_group":
				case "group_new":
					$output .= '<p class="title">'.CunddLang::get("files_new_group").'</p>';
					break;
					
				case "old_group":
				case "group_edit":
					// Ein Zurück-Link in Text-Form
					$output .= '<p class="title">'.CunddLang::get("files_old_group").'</p>';
					break;
				
				case "files_old_file_id":
					//$output .= '<p>Here is the space for the file\'s thumbnail</p>';
					$output .= '<input type="hidden" name="old_file_id" value="'.$wert["old_file_id"].'" />';
					break;
				
				case "files_all_files_count":
					$output .= '<p class="title">'.$wert[$tag].' Records</p>';
					break;
				
				// Out/Input -> Text
				case "files_title":
				case "files_beschreibung":
				case "files_tags":
				case "files_copyright":
					$output .= 'fileinfo" value="'.$wert[$wert['field_name']];
					break;
				
				// Out/Input - > Sonderform
				case "files_userfile":
					$output .= '<label for="'.$tag.$wert["schluessel"].'">'.$tag.': </label><input type="file" 
						name="userfile" id="'.$tag.$wert["schluessel"].'"';
					$output .= 'value="'.$wert[$wert['field_name']].'" class="eintragfeld" />';
					break;
					
				case "files_rechte":
					$output .= '<label for="'.$tag.$wert["schluessel"].'">'.$tag.': </label><input type="'.
						$type.'" name="'.$tag.'" id="'.$tag.$wert["schluessel"].'"';
					$output .= 'value="'.$wert[$wert['field_name']].'" class="eintragfeld" />';
					break;
				
				case "files_parent":
					$output .= '<label for="'.$tag.$wert["schluessel"].'">'.$tag.': </label>';
					$output .= '<select name="'.$tag.'" ';
					if($recht < 6){
					$output .= ' readonly="readonly" ';
					}
					$output .= 'id="'.$tag.$wert["schluessel"].'">';
					$output .= CunddFiles::printGroups("helper",$wert['parent']);
					$output .= '</select>';
					break;
					
					/*
					$output .= '<label for="'.$tag.$wert["schluessel"].'">'.$tag.': </label><input type="text" 
					name="userfile" id="'.$tag.$wert["schluessel"].'"';
					$output .= 'value="'.$wert[$tag].'" class="eintragfeld" />';
					break;
					*/
					
				case "files_print_groups":
					$output .= '<option value="'.$wert["schluessel"].'" '.$wert['selected'].' '.$wert['disabled'].'>'.$wert["title"].'</option>';
					break;
				
				// Output -> Text / Input -> Kein Input
				case "files_dateiname":
				case "files_originalname":
				case "files_type":
				case "files_size": 
				case "files_ersteller": 
				case "files_erstellungsdatum": 
				case "files_erstellungszeit": 
				case "files_bearbeiter": 
				case "files_bearbeitungsdatum": 
				case "files_bearbeitungszeit": 
				case "files_gruppe": 
				case "files_geloescht":
					// Wenn es nicht Neu = Leer ist
					$output .= '<input type="hidden" name="daten_bearbeitet" value="edit" />';
					$output .= '<div class="quote">'.$tag.': '.$wert[$tag].'</div>';
					break;
				
				// Output -> Text / Input -> Kein Input
				case "session_id":
					$output .= '<input type="hidden" name="PHPSESSID" value="'.session_id().'" />';
					break;
	
				// Out/Input - > Sonderform
				case "files_submit":
					$output .= '<input id="CunddBenutzer_submit" type="submit" name="files_submit'.$wert['schluessel'].'" value="';
					$output .= 'ok';
					$output .= '" />';
					break;
	
				
				// FILES
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
					
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// GALERIE
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "images_show_images":
				case "album_show_album":
				case "galerie_show_galerie":
					// Ein Bild ausgegeben von CunddImages
					$output .= '<div class="'.$wert['className'].' '.$tag.' '.$wert['currentDepth'].'">
						<a href="#" class="CunddNewLink '.$wert['classPrefix'].' '.$tag.' step_into" ';
					$output .= CunddLink::newLinkAction($wert['aufruf'], NULL, $wert['data']);
					$output .= '>';
					
					$maxWidth = $wert['maxWidth'];
					$maxHeight = $wert['maxHeight'];
					$output .= '<img ';
						if($maxHeight) $output .= 'height="'.$maxHeight.'" ';
						if($maxWidth) $output .= 'width="'.$maxWidth.'" ';
					$output .= 'src="'.$wert[$tag].'" alt="'.$wert['title'].'" />';
					$output .= '</a>';
					$output .= '</div>';
					break;
									
				case "images_show_next_images_link":
				case "galerie_show_next_galerie_link":
				case "album_show_next_album_link":
					$output .= '<div class="'.$tag.' galerie link">
						<a href="#" class="CunddNewLink '.$wert['classPrefix'].' next" ';
					$output .= CunddLink::newLinkAction($wert['aufruf']);
					$output .= '>'.$wert['title'].'</a>
						</div>';
					break;
					
				case "images_show_previous_images_link":
				case "galerie_show_previous_galerie_link":
				case "album_show_previous_album_link":
					$output .= '<div class="'.$tag.' galerie link">
						<a href="#" class="CunddNewLink '.$wert['classPrefix'].' previous" ';
					$output .= CunddLink::newLinkAction($wert['aufruf']);
					$output .= '>'.$wert['title'].'</a>
					</div>';
					break;
					
				case "images_show_last_images_link":
				case "galerie_show_last_galerie_link":
				case "album_show_last_album_link":
					$output .= '<div class="'.$tag.' galerie link">
						<a href="#" class="CunddNewLink '.$wert['classPrefix'].' last" ';
					$output .= CunddLink::newLinkAction($wert['aufruf']);
					$output .= '>'.$wert['title'].'</a>
					</div>';
					break;
				
				case "images_show_first_images_link":
				case "galerie_show_first_galerie_link":
				case "album_show_first_album_link":
					$output .= '<div class="'.$tag.' galerie link">
						<a href="#" class="CunddNewLink '.$wert['classPrefix'].' first" ';
					$output .= CunddLink::newLinkAction($wert['aufruf']);
					$output .= '>'.$wert['title'].'</a>
					</div>';
					break;
					
				case "images_step_into_images_link":
				case "album_step_into_album_link":
					$output .= '<div class="'.$tag.' galerie link">
						<a href="#" class="CunddNewLink '.$wert['classPrefix'].' step_into" ';
					$output .= CunddLink::newLinkAction($wert['aufruf'], NULL, $wert['data']);
					$output .= '>'.$wert['title'].'</a>
					</div>';
					break;
				
				case "images_step_out_images_link":
				case "album_step_out_album_link":
				case "galerie_step_out_galerie_link":
					$output .= '<div class="'.$tag.' galerie link">
					<a href="#" class="CunddNewLink '.$wert['classPrefix'].' step_out" ';
					$output .= CunddLink::newLinkAction($wert['aufruf'], NULL, $wert['data']);
					$output .= '>'.$wert['title'].'</a>
					</div>';
					break;
					
				case "album_show_album_detail":
				case "images_show_images_detail":
					// Ein Bild in Originalgröße ausgegeben
					$output .= '<div class="'.$wert['className'].' '.$tag.' detail">';
					// $output .= '<a href="#" class="CunddNewLink '.$wert['classPrefix'].' '.$tag.' step_into" ';
					// $output .= CunddLink::newLinkAction($wert['aufruf'], NULL, $wert['data']);
					// $output .= '>';
					
					$maxWidth = $wert['maxWidth'];
					$maxHeight = $wert['maxHeight'];
					$output .= '<img ';
						if($maxHeight) $output .= 'height="'.$maxHeight.'" ';
						if($maxWidth) $output .= 'width="'.$maxWidth.'" ';
					$output .= ' src="'.$wert[$tag].'" alt="'.$wert['title'].'" />';
					// $output .= '</a>';
					$output .= '</div>';
					break;
				
				case "images_print_detail_images_link":
				case "album_print_detail_album_link":
					$output .= '<div class="'.$tag.'">
					<a href="#" class="CunddNewLink '.$wert['classPrefix'].' print_detail" ';
					$output .= CunddLink::newLinkAction($wert['aufruf'], NULL, $wert['data']);
					$output .= '>'.$wert['title'].'</a>
					</div>';
					break;
					
				case "galerie_images_information":
					$output .= '<div class="Galerie information '.$wert['config_parameter'].' information_'.$wert['field_name'].'">';
					$output .= '<span class="name">'.$wert['field_name'].'</span>';
					$output .= '<span class="value">'.$wert['field_value'].'</span>';
					$output .= '</div>';
					break;
					
				case "CunddGalerie_show_preview_begin":
					$output .= '<div class="CunddGalerie_show_preview preview_container '.$tag.'">';
					break;
					
				case "CunddGalerie_show_preview_end":
					$output .= '</div>';
					//$output .= '<div class="CunddGalerie_show_preview_end preview_container">';
					break;
					
				case "CunddGalerie_show_preview_information_begin":
					$output .= '<div class="information_container '.$tag.' '.$wert['currentDepth'].'">';
					break;
	
				case "CunddGalerie_show_preview_information_end":
					$output .= '</div>';
					break;	
					
				case "image_show_link_class":
					break;
					
				case "images_show_images_container":
				case "album_show_album_container":
				case "galerie_show_galerie_container":
					$output .= '<div id="'.$tag.$wert.'" class="Galerie container '.$tag.' '.$wert['schluessel'].' '.$wert['currentDepth'].'">';
					break;
	
				// GALERIE
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// ALLGEMEINES
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "back_btn":
					// Ein Zurück-Link in Form eines Buttons
					$output .= '<input type="button" value="'.CunddLang::get("back").'" OnClick="javascript:history.back();">';
					break;
					
				case "back":
				case "back_link":
					// Ein Zurück-Link in Text-Form
					$output .= '<a href="javascript:history.back();">'.CunddLang::get("back").'</a>';
					break;
					
				case "open_div":
				case "universal_open_div":
				case "Cundd_Template_OpenDiv":
					$output .= '<div id="'.$wert['id'].'" class="'.$wert['class'].'">';
					break;
					
				case "close_div":
				case "universal_close_div":
				case "Cundd_Template_CloseDiv":
					$output .= '</div>';
					break;
					
				case "radio_button":
				case "Cundd_Template_RadioButton":
				case "Cundd_Template_Standard_RadioButton":
					$fieldId = $wert['inputName'].$wert['key'];
					$output .= '<div id="Cundd_Template_'.$fieldId.'" 
								class="Cundd_Template_Standard_RadioButton_inner">
									<input id="'.$fieldId.'" type="radio" name="'.$wert['inputName'].'" ';
					if($wert['checked']) $output .= 'checked="checked"';
					$output .= 'value="'.$wert['value'].'">
									<label for="'.$fieldId.'">'.$wert['label'].'</label>
								</div>';
					break;
				
				case "Cundd_Template_Checkbox":
				case "Cundd_Template_Standard_Checkbox":
					$fieldId = $wert['inputName'].$wert['key'];
					$output .= '<div id="Cundd_Template_'.$fieldId.'" 
								class="Cundd_Template_Standard_Checkbox_inner">
									<input id="'.$fieldId.'" type="checkbox" name="'.$wert['inputName'].'"';
					if($wert['checked']) $output .= 'checked="checked"';
					$output .= ' value="'.$wert['value'].'">
									<label for="'.$fieldId.'">'.$wert['label'].'</label>
								</div>';
					break;
				
				case "Cundd_Template_Text":
				case "Cundd_Template_Standard_Text":
					$fieldId = $wert['inputName'].$wert['key'];
					$output .= '<label for="Cundd_Template_'.$fieldId.'">'.$wert['label'].': </label>
								<input id="'.$tag.'" class="Cundd_Template_Standard_Text Cundd_Template_Standard Cundd_Template" 
								type="text" value="'.$wert[$tag].'" name="'.$wert['name'].'"/>';
					// $output .= 'Cundd_Template_Standard_Text Cundd_Template_Standard Cundd_Template" value="'.$wert[$tag];
					break;
				// ALLGEMEINES
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// FORMULAR
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "Cundd_Form_Standard_Radiobutton":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									<input id="'.$fieldId.'" type="radio" name="'.$wert['name'].'" ';
					if($wert['checked']) $output .= 'checked="checked"';
					$output .= 'value="'.$wert['value'].'">
									<label for="'.$fieldId.'">'.$wert['label'].'</label>
								</div>';
					break;
				
				case "Cundd_Form_Standard_Checkbox":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									<input id="'.$fieldId.'" type="checkbox" name="'.$wert['name'].'"';
					if($wert['checked']) $output .= 'checked="checked"';
					$output .= ' value="'.$wert['value'].'">
									<label for="'.$fieldId.'">'.$wert['label'].'</label>
								</div>';
					break;
				
				case "Cundd_Form_Standard_Text":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									<label for="'.$fieldId.'">'.$wert['label'].': </label>
									<input id="'.$fieldId.'" class="'.$wert['class'].'" 
									type="text" value="'.$wert['value'].'" name="'.$wert['name'].'"';
					if(array_key_exists('size',$wert)) $output .= ' size="'.$wert['size'].'" ';
					$output .= '		/>
								</div>';
					// $output .= 'Cundd_Template_Standard_Text Cundd_Template_Standard Cundd_Template" value="'.$wert[$tag];
					break;
				
				case "Cundd_Form_Standard_Textarea":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									<label for="'.$fieldId.'">'.$wert['label'].': </label>
								<textarea id="'.$fieldId.'" class="'.$wert['class'].'" name="'.$wert['name'].'" >'.$wert['value'].'</textarea>
								</div>';
					// $output .= 'Cundd_Template_Standard_Text Cundd_Template_Standard Cundd_Template" value="'.$wert[$tag];
					break;
				
				case "Cundd_Form_Standard_Output":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									'.$wert['output'].'
								</div>';
					// $output .= 'Cundd_Template_Standard_Text Cundd_Template_Standard Cundd_Template" value="'.$wert[$tag];
					break;
				
				case "Cundd_Form_Standard_Hidden":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<input type="hidden" name="'.$wert['name'].'" value="'.$wert['value'].'">'; 
					break;
					
				case "Cundd_Form_Standard_File":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									<label for="'.$fieldId.'">'.$wert['label'].': </label>
									<input id="'.$fieldId.'" class="'.$wert['class'].'" 
									type="file"  name="'.$wert['name'].'"/>
								</div>';
					break;
					
				case "Cundd_Form_Standard_Submit":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag.'">
									<input type="submit" value="'.$wert['label'].'">
								</div>';
					break;
					
				case "Cundd_Form_Standard_Head":
					$output .= '<form id="'.$wert['formid'].'" name="'.$wert['formname'].'" class="'.$wert['class'].'" action="'.$wert['headaction'].'" method="'.$wert['method'].'" enctype="multipart/form-data">';
					break;
					
				case "Cundd_Form_Standard_Foot":
					$output .= '</form>';
					break;
				
				case "Cundd_Form_Standard_JavaScriptCode":
					$output .= '<script type="text/javascript">
						var Cundd_Formular = window.document.getElementById(\''.$wert['formid'].'\');
						Cundd_Formular.onsubmit = function(){
							var data = [];
							data = $(\'#\'+this.id).serializeArray();
	
							var action = {};
							action = {name: "aufruf", value: "'.$wert['action'].'"};
							
							data.push(action); // "Aufruf"-Objekt anhängen
							
							new CunddUpdate({
											datei: CunddAjaxPHP_verweis,
											data: data,
											targetId: '.$wert['targetDivId'].'
											}
							);
							return false;
						}
						</script>';
					break;
				
				/* OBSOLETE *//*
				case "Cundd_Form_Standard_RTE":
				case "Cundd_Form_Standard_TinyMCE":
					$fieldId = $wert['name'].$wert['key'];
					$output .= '<div id="Cundd_Formular'.$fieldId.'" 
								class="'.$wert['class'].' '.$tag;
					if(CunddConfig::get('CunddTinyMCE_enable')) $output .= CunddConfig::get('CunddTinyMCE_initForCSSClass');
					$output .= '">
									<label for="'.$fieldId.'">'.$wert['label'].': </label>
									<textarea id="'.$fieldId.'" class="'.$wert['class'].'" 
									type="text" value="'.$wert[$tag].'" name="'.$wert['name'].'"/>
								</div>';
					break;
				/* */
				
				case "Cundd_Form_Standard_RTE_Javascript":
				case "Cundd_Form_Standard_TinyMCE_Javascript":
					$output .= '<script type="text/javascript">
									CunddTinyMCE.addEventListener();
								</script>';
					break;
					
				case "Cundd_Form_Standard_Focus":
				case "Cundd_Form_Standard_Setfocus":
					$output .= '<script type="text/javascript">
								//	$(document).ready(function(){
										window.document.getElementById("'.$wert['id'].'").focus();
								//	}
								//	);
								</script>';
					break;
				// FORMULAR
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// ATTRIBUTE
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "attribute":
					$output .= CunddBenutzer::createAttributeInputs(NULL,$wert[$tag],$wert);
					$output .= '<input type="hidden" name="oldAttribute" value="'.$wert[$tag].'">';
					break;
				// ATTRIBUTE
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// CONTENT
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				case "Cundd_Content_Save_Success":
					$output .= '<h1>'.CunddLang::__('Saving the content succeeded').'</h1>';
					break;
				
				case "Cundd_Content_Save_Error":
					$output .= '<h1>'.CunddLang::__('Error while trying to save the content to the record {1}','test').'</h1>';
					break;
					
				case "Cundd_Content_Output_Standard_ersteller":
				case "Cundd_Content_Output_Standard_erstellungsdatum":
				case "Cundd_Content_Output_Standard_erstellungszeit":
				case "Cundd_Content_Output_Standard_bearbeiter":
				case "Cundd_Content_Output_Standard_bearbeitungsdatum":
				case "Cundd_Content_Output_Standard_bearbeitungszeit":
				case "Cundd_Content_Output_Standard_subtitle":
				case "Cundd_Content_Output_Standard_bildlink":
				case "Cundd_Content_Output_Standard_rechte":
				case "Cundd_Content_Output_Standard_gruppe":
				case "Cundd_Content_Output_Standard_schluessel":
					// nothing
					$output .= '';
					break;
				// CONTENT
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
				// ERROR
				default:
					if(CunddConfig::__('Template/echo_tag_error')){
						$output .= "<br />Fehler bei der Zuordnung des tags $tag <br />";
					} else {
						$output .= '';
					}
					break;
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWM {
			}
		}
		
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Das Ergebnis zurückgeben
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt das Template entsprechend dem übergebenen Tag.
	 * @param array $wert
	 * @param string $tag
	 * @param int $recht[optional]
	 * @param boolean $required[optional]
	 * @return string
	 */
	private static function advancedTemplate($wert, $tag, $recht = NULL, $required = NULL){
		$layoutPath = CunddPath::getAbsoluteLayoutPath($tag);
		if($layoutPath AND class_exists('CunddView') AND class_exists('CunddLayout')){
			// Zend_Layout laden
			$layout = new CunddLayout();
			
			/* Die View laden und einstellen */
			$view = new CunddView();
			
			$view->clearAllPlaceholders();
			$view->registerPlaceholdersFromArray($wert);
			
			$layout->setView($view);
			


			$layoutDir = CunddPath::getAbsoluteLayoutDir($tag);
			$layoutFile = CunddPath::getLayoutFile($tag);


//			CunddTools::pd($layoutDir);
//			CunddTools::pd($layoutFile);

			$layout->setLayoutPath($layoutDir);
			$layout->setLayout($layoutFile);
			
			$renderedLayout = $layout->render();
			return $renderedLayout;
		} else {
			return false;
		}
		
		
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ist ein Alias für showEntry */
	/* !! OBSOLETE !! */
	public static function show($wert){
		CunddTemplate::showEntry($wert);
	}
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ruft die Funktion "CunddRechte" auf, welche die jeweiligen Rechte für den 
	 * derzeitigen Nutzer zurückgibt. Wenn der Benutzer das Recht zum Lesen hat, wird die 
	 * Methode "inhalte_einrichten" aufgerufen welche für die Ausgabe des Inhalts weiter zu-
	 * ständig ist.
	 * @param array $wert
	 * @return void
	 */
	public static function showEntry($wert){
		/* Die Rechte des Benutzer für den als Parameter übergebenene Artikel."CunddRechte" 
		gibt 0 (= keine Rechte),4 (= nur Lesen), oder 6 (= Lesen + Schreiben) zurück. */
		$recht = CunddRechte::get($wert);
		
		/* DEBUGGEN:
		echo $recht;
		echo '<pre>'; echo var_dump($wert); echo '</pre>';
		//*/
		
		
		
		if($recht){
			// Die Anzeigbaren Felder auslesen
			$felder = CunddFelder::get_eintrag();
			// Beginn des "eintrage"-divs
			echo CunddTemplate::inhalte_einrichten($wert, $recht, 'eintrag_head', 'output');
			
			// Toolbar für das Editieren des Eintrags
			if(CunddConfig::get('zeige_toolbar')){
				echo CunddTemplate::inhalte_einrichten($wert, $recht, 'toolbar', 'spezial');
			}
			
			
			// Die Inhalte wie in der MySQL-Tabelle "eintrag_sichtbarkeit" ausgeben
			for($i = 0; $i < count($felder[0]); $i++){
				/* Überprüfen ob es sich bei dem Inhalt um den Beginn der Quote oder 
				der Zusatzinformationen handelt	und wenn ja, ob die Bereiche angezeigt 
				werden sollen. */
				if(($felder[0][$i] == "ersteller" AND CunddConfig::get('zeige_quote')) OR 
					($felder[0][$i] == "rechte" AND CunddConfig::get('zeige_zusatzinfos'))
					){
					echo CunddTemplate::inhalte_einrichten($wert, $recht, 'quote_head', 'output');
				}
				
				
				
				/* Überprüfen ob es sich bei dem Inhalt um die Quote oder 
				die Zusatzinformationen handelt	und wenn ja, ob die Bereiche angezeigt 
				werden sollen. */
				/* Wenn das Ergebnis nicht dem Parameter entspricht, wurde etwas ersetzt.
				Somit kann überprüft werden ob der Parameter den String enthält. */
				$suche_zusatz = array('rechte','gruppe','sprache','schluessel');
				
				if($felder[0][$i] != str_replace("erstell","r",$felder[0][$i])){
					$ist_quote = true;
				} else 
				if($felder[0][$i] != str_replace("bearbeit","r",$felder[0][$i])){
					$ist_quote = true;
				} else 
				if($felder[0][$i] != str_replace($suche_zusatz,"r",$felder[0][$i])){
					$ist_zusatz = true;
				} else {
					$ist_quote = false;
					$ist_zusatz = false;
				}
			
				
				if($ist_quote){ // Ist Teil der Quote!
					if(CunddConfig::get('zeige_quote')){ // Anzeigen?
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $felder[0][$i], $felder[2][$i]);
					}
				} else if($ist_zusatz){ // Ist Teil der Zusatzinformationen!
					if(CunddConfig::get('zeige_zusatzinfos')){ // Anzeigen?
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $felder[0][$i], $felder[2][$i]);
					}
				} else { /* Ist weder Quote noch Zusatzinformation -> Auslesen ob der Inhalt laut "config.php" 
					angezeigt werden soll. */
					$config_parameter = "zeige_".$felder[0][$i];
					if(CunddConfig::get($config_parameter)){
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $felder[0][$i], $felder[2][$i]);
					}
				}
				
				/* Überprüfen ob der vorige Inhalt der letzte Inhalt der Quote oder 
				der Zusatzinformationen war und somit hier die Quote geschlossen werden 
				muss. */
				if($felder[0][$i] == "bearbeitungszeit" AND CunddConfig::get('zeige_quote') OR 
					$felder[0][$i] == "schluessel" AND CunddConfig::get('zeige_zusatzinfos')
					){
					echo CunddTemplate::inhalte_einrichten($wert, $recht, 'quote_foot', 'output');
				}
			}
			/* Den Namen MySQL-Tabelle in der dieser Eintrag steht "hidden" per 
			Post mitschicken. */
			echo CunddTemplate::inhalte_einrichten($wert, $recht, 'tabelle', 'output');
			
			/* Den "eintrag"-div schließen und somit die optische Trennung zum
			nächsten Eintrag erstellen. */
			echo CunddTemplate::inhalte_einrichten($wert, $recht, 'eintrag_foot', 'output');
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ruft die Funktion "CunddRechte" auf, welche die jeweiligen Rechte für den 
	 * derzeitigen Nutzer zurückgibt. Wenn der Benutzer das Recht zum Lesen hat, wird die 
	 * Methode "inhalte_einrichten" aufgerufen welche für die Ausgabe des Inhalts weiter zu-
	 * ständig ist.
	 * @param array $wert
	 * @return void
	 */
	public static function showContent($wert){
		/* Die Rechte des Benutzer für den als Parameter übergebenene Artikel."CunddRechte" 
		gibt 0 (= keine Rechte),4 (= nur Lesen), oder 6 (= Lesen + Schreiben) zurück. */
		$recht = CunddRechte::get($wert);
		
		/* DEBUGGEN:
		echo $recht;
		echo '<pre>'; echo var_dump($wert); echo '</pre>';
		//*/
		
		
		
		if($recht){
			// Die Anzeigbaren Felder auslesen
			$felder = CunddFelder::get_eintrag();
			// Beginn des "eintrage"-divs
			echo CunddTemplate::inhalte_einrichten($wert, $recht, 'eintrag_head', 'output');
			
			
			// Die Inhalte wie in der MySQL-Tabelle "eintrag_sichtbarkeit" ausgeben
			for($i = 0; $i < count($felder[0]); $i++){
				/* Überprüfen ob es sich bei dem Inhalt um den Beginn der Quote oder 
				der Zusatzinformationen handelt	und wenn ja, ob die Bereiche angezeigt 
				werden sollen. */
				if(($felder[0][$i] == "ersteller" AND CunddConfig::get(zeige_quote)) OR 
					($felder[0][$i] == "rechte" AND CunddConfig::get(zeige_zusatzinfos))
					){
					echo CunddTemplate::inhalte_einrichten($wert, $recht, quote_head, output);
				}
				
				
				
				/* Überprüfen ob es sich bei dem Inhalt um die Quote oder 
				die Zusatzinformationen handelt	und wenn ja, ob die Bereiche angezeigt 
				werden sollen. */
				/* Wenn das Ergebnis nicht dem Parameter entspricht, wurde etwas ersetzt.
				Somit kann überprüft werden ob der Parameter den String enthält. */
				$suche_zusatz = array('rechte','gruppe','sprache','schluessel');
				
				if($felder[0][$i] != str_replace("erstell","r",$felder[0][$i])){
					$ist_quote = true;
				} else 
				if($felder[0][$i] != str_replace("bearbeit","r",$felder[0][$i])){
					$ist_quote = true;
				} else 
				if($felder[0][$i] != str_replace($suche_zusatz,"r",$felder[0][$i])){
					$ist_zusatz = true;
				} else {
					$ist_quote = false;
					$ist_zusatz = false;
				}
			
				
				if($ist_quote){ // Ist Teil der Quote!
					if(CunddConfig::get(zeige_quote)){ // Anzeigen?
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $felder[0][$i], $felder[2][$i]);
					}
				} else if($ist_zusatz){ // Ist Teil der Zusatzinformationen!
					if(CunddConfig::get(zeige_zusatzinfos)){ // Anzeigen?
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $felder[0][$i], $felder[2][$i]);
					}
				} else { /* Ist weder Quote noch Zusatzinformation -> Auslesen ob der Inhalt laut "config.php" 
					angezeigt werden soll. */
					$fieldname = $felder[0][$i];
					$fieldoutput = $felder[2][$i];
					
					// Den Content nur ausgeben -> den $fieldoutput anpassen
					if($fieldoutput !== 'special' OR $fieldoutput !== 'output' OR $fieldoutput !== 'spezial'){
						$fieldoutput = 'special';
					} 
					
					$config_parameter = "content_show_".$fieldname;
					if(CunddConfig::get($config_parameter)){
						echo CunddTemplate::inhalte_einrichten($wert, $recht, $fieldname, $fieldoutput);
					}
				}
				
				/* Überprüfen ob der vorige Inhalt der letzte Inhalt der Quote oder 
				der Zusatzinformationen war und somit hier die Quote geschlossen werden 
				muss. */
				if($felder[0][$i] == "bearbeitungszeit" AND CunddConfig::get(zeige_quote) OR 
					$felder[0][$i] == "schluessel" AND CunddConfig::get(zeige_zusatzinfos)
					){
					echo CunddTemplate::inhalte_einrichten($wert, $recht, quote_foot, output);
				}
			}
			/* Den Namen MySQL-Tabelle in der dieser Eintrag steht "hidden" per 
			Post mitschicken. */
			echo CunddTemplate::inhalte_einrichten($wert, $recht, tabelle, output);
			
			/* Den "eintrag"-div schließen und somit die optische Trennung zum
			nächsten Eintrag erstellen. */
			echo CunddTemplate::inhalte_einrichten($wert, $recht, eintrag_foot, output);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt eine Nachricht aus, wenn der Benutzer erfolgreich ausgeloggt wurde.*/
	public static function logout(){
		$output  = CunddTemplate::inhalte_einrichten($wert, $recht, eintrag_head, output);
		$output .= CunddTemplate::inhalte_einrichten($wert, $recht, logout, output);
		$output .= CunddTemplate::inhalte_einrichten($wert, $recht, eintrag_foot, output);
		echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ruft die Funktion "CunddRechte" auf, welche die jeweiligen Rechte für den 
	derzeitigen Nutzer zurückgibt. Wenn der Benutzer das Recht zum Lesen hat, wird die 
	Methode "inhalte_einrichten" aufgerufen welche für die Ausgabe des Inhalts weiter zu-
	ständig ist. */
	public static function links($resultat, $tabelle){
		// Beginn des "links"-divs
		$wert["tabelle"] = $tabelle;
		echo CunddTemplate::inhalte_einrichten($wert, $recht, links_head, output);
		$links = array();		
			
		// Link-Verschachtelung berücksichtigen
		/* Die Links werden als Objekte in einem mehrdimensionalen Array gespeichert. Der 
		Index des Arrays $links ist der Elternknoten des aktuellen Links ($wert). Das erste 
		Element in $links steht dabei immer für oberste Ebene, die in den meisten Fällen von 
		Anfang an sichtbar ist.
		 
		BEISPIEL:
		
		links[0] (wert1, wert2,	wert4, wert7)
		links[1] (keine Unterlinks)
		links[2] (wert3, wert8)
		links[3] (wert5)
		links[4] (keine Unterlinks)
		links[5] (wert6)
		
		
		*/
		
		foreach($resultat as $wert){
			$wert["tabelle"] = $tabelle;
			$der_eltern_knoten = $wert["parent"];
			
			$links[$der_eltern_knoten][count($links[$der_eltern_knoten]) + 1] = $wert;
		}
		/* while ($wert = mysql_fetch_array($resultat, MYSQL_ASSOC)) {
			$wert["tabelle"] = $tabelle;
			$der_eltern_knoten = $wert["parent"];
			
			$links[$der_eltern_knoten][count($links[$der_eltern_knoten]) + 1] = $wert;
		}
		*/
		
		/*/ DEBUGGEN: 
		echo '<pre>';
		var_dump($links);
		echo '</pre>';
		//*/
				
		// Einzelne Links anzeigen
		$tiefe = 0;
		CunddTemplate::links_ausgeben($links, 0, $tiefe);
		/*
			if($wert["parent"]){ // parent=0 Knoten der obersten Ordnung
				
			}
				
			/* Die Rechte des Benutzer für den als Parameter übergebenene Artikel."CunddReche" 
			gibt 0 (= keine Rechte),4 (= nur Lesen), oder 6 (= Lesen + Schreiben) zurück. */
		/*	$recht = CunddRechte::get($wert);
			
			if($recht){
				echo CunddTemplate::inhalte_einrichten($wert, $recht, links, output);
			}
		}//*/
		// Den "links"-div schließen
		echo CunddTemplate::inhalte_einrichten($wert, $recht, links_foot, output);
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ruft die Funktion "CunddRechte" auf, welche die jeweiligen Rechte für den 
	derzeitigen Nutzer zurückgibt. Wenn der Benutzer das Recht zum Lesen hat, wird die 
	Methode "inhalte_einrichten" aufgerufen welche für die Ausgabe des Inhalts weiter zu-
	ständig ist. Danach wird überprüft ob der aktuelle Link Sublinks hat. */
	public static function links_ausgeben($links, $aktueller_link, $tiefe){
		$parent_knoten = $links[$aktueller_link];
		/* Weiter Informationen zu "foreach()" unter 
		http://at.php.net/manual/de/control-structures.foreach.php */
		if(is_a($parent_knoten,'array') OR true){
			foreach($parent_knoten as $array_index => $wert) {
				//echo $tiefe."  dieser_link <b>".$wert["name"].'</b>,S/P='.$wert["schluessel"].'/'.$wert["parent"];
				// Element ausgeben
				$naechster_link = $wert["schluessel"];
				
				// Die aktuelle Tiefe in der Baumstruktur und die Anzahl der Kind-Elemente mitsenden
				$wert['depth'] = $tiefe;
				$wert['children'] = count($links[$wert["schluessel"]]);
				
				
				
				/* Die Rechte des Benutzer für den als Parameter übergebenene Artikel."CunddReche" 
				gibt 0 (= keine Rechte),4 (= nur Lesen), oder 6 (= Lesen + Schreiben) zurück. */
				$recht = CunddRechte::get($wert);
				if($recht){
					// Überprüfen ob es sich um ein Link-Popup handelt
					if($tiefe){
						echo CunddTemplate::inhalte_einrichten($wert, $recht, 'links', 'output');
					} else {
						echo CunddTemplate::inhalte_einrichten($wert, $recht, 'links', 'output');
					}
					
				}
				
				// Überprüfen ob dieser Link Sublinks hat
				$naechster_link = $wert["schluessel"];
				if($links[$naechster_link]){
					//echo ''.$tiefe." nächster Link:".$naechster_link."<br />";
					CunddTemplate::links_ausgeben($links, $naechster_link, $tiefe+1);
				}
				//*/
					
			}
		} else {
			echo 'empty linksset';
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt eine Reihe von Radio-Buttons aus.
	 * Das übergebene Array folgt dem Schema:
	 * array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] );
	 * @param array $radioButtonCollection
	 * @param string $inputName
	 * @return string
	 */
	public static function printRadioButtons(array $radioButtonCollection,$inputName){
		$output = self::createRadioButtons($radioButtonCollection,$inputName);
		echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode erstellt die Ausgabe einer Reihe von Radio-Buttons.
	 * Das übergebene Array folgt dem Schema:
	 * array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] );
	 * @param array $radioButtonCollection
	 * @param string $inputName
	 * @return string
	 */
	public static function createRadioButtons(array $radioButtonCollection,$inputName,$otherData = NULL){
		$tag = 'Cundd_Template_Standard_RadioButton';
		
		foreach($radioButtonCollection as $key => $radioButton){
			$wert = $otherData;
			
			if(gettype($radioButton)=='array'){
				// Das Label ermitteln
				$wert['label'] = $radioButton[0];
				
				// Den Wert ermitteln
				if(count($radioButton) > 1){
					$wert['value'] = $radioButton[1];
				} else {
					$wert['value'] = $wert['label'];
				}
				
				// Checked ermitteln
				if(count($radioButton) > 2){
					$wert['checked'] = $radioButton[2];
				} else {
					$wert['checked'] = false;
				}
				
				// Required ermitteln
				if(count($radioButton) > 3){
					$wert['required'] = $radioButton[3];
				} else {
					$wert['required'] = false;
				}
			} else if(gettype($radioButton)=='string'){
				$wert['label'] = $radioButton;
				$wert['value'] = $radioButton;
				$wert['checked'] = false;
				$wert['required'] = false;
			}
			
			$wert['inputName'] = $inputName;
			$wert['key'] = $key;
			
			$output .= self::__($wert, 6, $tag, 'special', $wert['required']);
		}
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt eine einzelne oder eine Reihe von Checkboxen aus.
	 * Ist $checkboxInput ein Array, dann folgt es dem Schema:
	 * array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] );
	 * $checked ist nur wirksam wenn $checkboxInput vom Typ String ist 
	 * @param string/array $radioButtonCollection
	 * @param string $inputName
	 * @param boolean $checked
	 * @return string
	 */
	public static function printCheckboxes($checkboxInput,$inputName,$checked = NULL){
		$output = self::createCheckboxes($checkboxInput,$inputName,$checked);
		echo $output;
		return $output;
	}
		
		
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode erstellt die Ausgabe einer einzelnen oder einer Reihe von Checkboxen.
	 * Ist $checkboxInput ein Array, dann folgt es dem Schema:
	 * array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] );
	 * $checked ist nur wirksam wenn $checkboxInput vom Typ String ist 
	 * @param string/array $radioButtonCollection
	 * @param string $inputName
	 * @param boolean $checked
	 * @param string $value
	 * @return string
	 */
	public static function createCheckboxes($checkboxInput,$inputName,$checked = NULL,$value = NULL,$otherData = NULL){
		$tag = 'Cundd_Template_Standard_Checkbox';
		// <input type="checkbox" name="zutat" value="salami"> Salami<br>
		
		if(gettype($checkboxInput) == 'array'){
			if(count($checkboxInput) > 1){
				$inputName = $inputName.'[]';
			}
			foreach($checkboxInput as $key => $checkbox){
				$wert = $otherData;
				// Das Label ermitteln
				$wert['label'] = $checkbox[0];
				
				// Den Wert ermitteln
				if(count($checkbox) > 1){
					$wert['value'] = $checkbox[1];
				} else {
					$wert['value'] = $wert['label'];
				}
				
				// Checked ermitteln
				if(count($checkbox) > 2){
					$wert['checked'] = $checkbox[2];
				} else {
					$wert['checked'] = false;
				}
				
				// Required ermitteln
				if(count($checkbox) > 3){
					$wert['required'] = $checkbox[3];
				} else {
					$wert['required'] = false;
				}
				
				
				$wert['inputName'] = $inputName;
				$wert['key'] = $key;
				
				$output = self::__($wert, 6, $tag, $wert['required']);
			}
		} else if(gettype($checkboxInput) == 'string'){
			$wert = array();
			// Das Label ermitteln
			$wert['label'] = $checkboxInput;
			
			// Value ermitteln
			if($value){
				$wert['value'] = $value;
			} else {
				$wert['value'] = $checkboxInput;
			}
			
			
			// Checked ermitteln
			if($checked){
				$wert['checked'] = 'checked';
			} else {
				$wert['checked'] = false;
			}
			
			$wert['inputName'] = $inputName;
			$output = self::__($wert, 6, $tag);
		} else {
		}
		return $output;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode fügt einen div um den übergebenen String. */
	public static function wrap($content,$class = NULL,$id = NULL){
		$wert['class'] = $class;
		$wert['id'] = $id;
		$wert['identifier'] = 'wrap';
		
		$output  = self::__($wert,4,'Cundd_Template_OpenDiv','output');
		$output .= $content;
		$output .= self::__($wert,4,'Cundd_Template_CloseDiv','output');
		return $output;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt vorzugsweise ein advanced Template und gibt dieses zurück.
	 * @param string $tag
	 * @param mixed $data
	 * @return string
	 */
	function createAdvanced($tag,$data = NULL){
		if($data){
			if(gettype($data) == 'array'){
				$wert = $data;
			} else {
				$wert[$tag] = $data;
			}
		}
		$right = 6;
		$type = 'output';
		$wert[] = $tag;
		return CunddTemplate::__($wert,$right,$tag,$type);
	}
}
?>