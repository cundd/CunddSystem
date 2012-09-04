<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "IntallStandardCunddContentRecords" bietet die Methoden zur Installa-
 tion der Standard-Content-Records z.B. zur Steuerung der Ausgabe der Galerie. */
class IntallStandardCunddContentRecords {
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Variablen deklarieren
	
	
	
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Konstruktor: Ruft die einzelnen Methoden zur Installation auf. */
function IntallStandardCunddContentRecords(){
	$say = true;
	$cleanUpBevorInstall = true; /* Gibt an ob die Tabelle vor dem Ausführen des Skripts
								  gelöscht werden soll. */
	
	$installFields = array(array('class' => 'CunddBenutzer',	'method' => 'CunddBenutzer'),
						   array('class' => 'CunddBenutzer',	'method' => 'show'),
						   array('class' => 'CunddBenutzer',	'method' => 'get'),
						   array('class' => 'CunddBenutzer',	'method' => 'get_daten'),
						   array('class' => 'CunddBenutzer',	'method' => 'edit'),
						   array('class' => 'CunddBenutzer',	'method' => 'neu'),
						   array('class' => 'CunddBlog',	'method' => 'CunddBlog'),
						   array('class' => 'CunddBlog',	'method' => 'js_instanz'),
						   array('class' => 'CunddBlogLesen',	'method' => 'CunddBlogLesen'),
						   array('class' => 'CunddBlogLesen',	'method' => 'ergebnis_ausgeben'),
						   array('class' => 'CunddBlogLesen',	'method' => 'max_eintraege_check'),
						   array('class' => 'CunddBlogXML',	'method' => 'CunddBlogXML'),
						   array('class' => 'CunddCalendar',	'method' => 'CunddCalendar'),
						   array('class' => 'CunddCalendar',	'method' => 'sessionLoad'),
						   array('class' => 'CunddCalendar',	'method' => 'init'),
						   array('class' => 'CunddCalendar',	'method' => 'render'),
						   array('class' => 'CunddContent',	'method' => 'CunddContent'),
						   array('class' => 'CunddContent',	'method' => 'get'),
						   array('class' => 'CunddContent',	'method' => 'getByTitle'),
						   array('class' => 'CunddContent',	'method' => 'parse'),
						   array('class' => 'CunddContent',	'method' => 'execute'),
						   array('class' => 'CunddEvent',	'method' => 'CunddEvent'),
						   array('class' => 'CunddFelder',	'method' => 'CunddFelder'),
						   array('class' => 'CunddFelder',	'method' => 'get_benutzer'),
						   array('class' => 'CunddFelder',	'method' => 'get_eintrag'),
						   array('class' => 'CunddFelder',	'method' => 'install_get_benutzer_verwaltung_sichtbarkeit'),
						   array('class' => 'CunddFelder',	'method' => 'install_benutzer_verwaltung_sichtbarkeit'),
						   array('class' => 'CunddFelder',	'method' => 'install_eintrag_sichtbarkeit'),
						   array('class' => 'CunddFiles',	'method' => 'get_link'),
						   array('class' => 'CunddFiles',	'method' => 'get_files'),
						   array('class' => 'CunddFiles',	'method' => 'get_files_group'),
						   array('class' => 'CunddFiles',	'method' => 'CunddFiles'),
						   array('class' => 'CunddFiles',	'method' => 'get'),
						   array('class' => 'CunddFiles',	'method' => 'getOfType'),
						   array('class' => 'CunddFiles',	'method' => 'printOfType'),
						   array('class' => 'CunddFiles',	'method' => 'get_mime_type'),
						   array('class' => 'CunddFiles',	'method' => 'get_daten'),
						   array('class' => 'CunddFiles',	'method' => 'check_type'),
						   array('class' => 'CunddFiles',	'method' => 'save_file'),
						   array('class' => 'CunddFiles',	'method' => 'sync_global'),
						   array('class' => 'CunddFiles',	'method' => 'newGroup'),
						   array('class' => 'CunddFiles',	'method' => 'getGroups'),
						   array('class' => 'CunddGalerie',	'method' => 'printGroups'),
						   array('class' => 'CunddGalerie',	'method' => 'getOfParent'),
						   array('class' => 'CunddGalerie',	'method' => 'printOfGroup'),
						   array('class' => 'CunddGalerie',	'method' => 'CunddGalerie'),
						   array('class' => 'CunddGalerie',	'method' => 'CunddGalerie'),
						   array('class' => 'CunddGalerie',	'method' => 'init'),
						   array('class' => 'CunddGalerie',	'method' => 'release'),
						   array('class' => 'CunddGalerie',	'method' => 'overwrite'),
						   array('class' => 'CunddGalerie',	'method' => 'overwriteAndRelease'),
						   array('class' => 'CunddGalerie',	'method' => 'show'),
						   array('class' => 'CunddGalerie',	'method' => 'showImageWithPicture'),
						   array('class' => 'CunddGalerie',	'method' => 'showImageWithoutPicture'),
						   array('class' => 'CunddGalerie',	'method' => 'getSingle'),
						   array('class' => 'CunddGalerie',	'method' => 'nextLink'),
						   array('class' => 'CunddGalerie',	'method' => 'next'),
						   array('class' => 'CunddGalerie',	'method' => 'previousLink'),
						   array('class' => 'CunddGalerie',	'method' => 'previous'),
						   array('class' => 'CunddGalerie',	'method' => 'firstLink'),
						   array('class' => 'CunddGalerie',	'method' => 'first'),
						   array('class' => 'CunddGalerie',	'method' => 'lastLink'),
						   array('class' => 'CunddGalerie',	'method' => 'last'),
						   array('class' => 'CunddGalerie',	'method' => 'showImageAtLink'),
						   array('class' => 'CunddGalerie',	'method' => 'showImageAt'),
						   array('class' => 'CunddGalerie',	'method' => 'createShowImageAtLinksFromAToB'),
						   array('class' => 'CunddGalerie',	'method' => 'createAllShowImageAtLinks'),
						   array('class' => 'CunddGalerie',	'method' => 'serialize'),
						   array('class' => 'CunddGalerie',	'method' => 'save'),
						   array('class' => 'CunddGalerie',	'method' => 'loadSession'),
						   array('class' => 'CunddGalerie',	'method' => 'createAllStepIntoLinks'),
						   array('class' => 'CunddGalerie',	'method' => 'stepIntoLink'),
						   array('class' => 'CunddGalerie',	'method' => 'stepInto'),
						   array('class' => 'CunddGalerie',	'method' => 'getParentId'),
						   array('class' => 'CunddGalerie',	'method' => 'stepOut'),
						   array('class' => 'CunddGalerie',	'method' => 'stepOutLink'),
						   array('class' => 'CunddGalerie',	'method' => 'printOverview'),
						   array('class' => 'CunddGalerie_CunddAlbum',	'method' => 'getDetail'),
						   array('class' => 'CunddGalerie_CunddAlbum',	'method' => 'printDetail'),
						   array('class' => 'CunddGalerie_CunddAlbum',	'method' => 'printDetailOfSelf'),
						   array('class' => 'CunddGalerie_CunddImage',	'method' => 'printDetailLink'),
						   array('class' => 'CunddGalerie_CunddImage',	'method' => 'createAllPrintDetailLinks'),
						   array('class' => 'CunddGalerie_CunddImage',	'method' => 'CunddGalerie'),
						   array('class' => 'CunddGalerie_CunddImage',	'method' => 'extends'),
						   array('class' => 'CunddGruppen',	'method' => '$classPrefix'),
						   array('class' => 'CunddGruppen',	'method' => 'CunddAlbum'),
						   array('class' => 'CunddGruppen',	'method' => 'extends'),
						   array('class' => 'CunddGruppen',	'method' => '$classPrefix'),
						   array('class' => 'CunddGruppen',	'method' => 'CunddImages'),
						   array('class' => 'CunddGruppen',	'method' => 'init'),
						   array('class' => 'CunddGruppen',	'method' => 'CunddGruppen'),
						   array('class' => 'CunddInhalt',	'method' => 'neu'),
						   array('class' => 'CunddInhalt',	'method' => 'get'),
						   array('class' => 'CunddInhalt',	'method' => 'ist_in'),
						   array('class' => 'CunddInhalt',	'method' => 'get_name'),
						   array('class' => 'CunddInhalt',	'method' => 'get_id'),
						   array('class' => 'CunddInhalt',	'method' => 'get_all'),
						   array('class' => 'CunddInhalt',	'method' => 'CunddInhalt'),
						   array('class' => 'CunddInhalt',	'method' => 'edit'),
						   array('class' => 'CunddInhalt',	'method' => 'loeschen'),
						   array('class' => 'CunddLang',	'method' => 'CunddJavaScript'),
						   array('class' => 'CunddLang',	'method' => 'init'),
						   array('class' => 'CunddLink',	'method' => 'get'),
						   array('class' => 'CunddLink',	'method' => 'CunddLink'),
						   array('class' => 'CunddLogin',	'method' => 'anzeigen'),
						   array('class' => 'CunddLogin',	'method' => 'ergebnis_ausgeben'),
						   array('class' => 'CunddLogin',	'method' => 'js_instanz'),
						   array('class' => 'CunddLogin',	'method' => 'newLink'),
						   array('class' => 'CunddLogin',	'method' => 'newLinkAction'),
						   array('class' => 'CunddMakeTabelle',	'method' => 'login'),
						   array('class' => 'CunddMakeTabelle',	'method' => 'logout'),
						   array('class' => 'CunddMakeTabelle',	'method' => 'out'),
						   array('class' => 'CunddMSG',	'method' => 'ueberpruefen'),
						   array('class' => 'CunddMSG',	'method' => 'erstellen'),
						   array('class' => 'CunddMSG',	'method' => 'CunddMSG'),
						   array('class' => 'CunddRechte',	'method' => 'msg_detail'),
						   array('class' => 'CunddRechte',	'method' => 'display_messages'),
						   array('class' => 'CunddRechte',	'method' => 'get_messages'),
						   array('class' => 'CunddRechte',	'method' => 'set_read'),
						   array('class' => 'CunddRechte',	'method' => 'neuer_benutzer'),
						   array('class' => 'CunddRechte',	'method' => 'CunddRechte'),
						   array('class' => 'CunddRechte',	'method' => 'get'),
						   array('class' => 'CunddSessionSetter',	'method' => 'get_benutzer_felder'),
						   array('class' => 'CunddSessionSetter',	'method' => 'mysql_connect'),
						   array('class' => 'CunddTemplate',	'method' => 'CunddTemplate'),
						   array('class' => 'CunddTemplate',	'method' => 'show_table'),
						   array('class' => 'CunddTemplate',	'method' => 'show_benutzer'),
						   array('class' => 'CunddTemplate',	'method' => 'file_input_form'),
						   array('class' => 'CunddTemplate',	'method' => 'inhalte_einrichten'),
						   array('class' => 'CunddTemplate',	'method' => 'benutzer_formular'),
						   array('class' => 'CunddTemplate',	'method' => 'login'),
						   array('class' => 'CunddTemplate',	'method' => 'login_formular'),
						   array('class' => 'CunddTemplate',	'method' => 'inhalt_ausfuellen'),
						   array('class' => 'CunddTemplate',	'method' => 'show'),
						   array('class' => 'CunddTools',	'method' => 'logout'),
						   array('class' => 'CunddTools',	'method' => 'links'),
						   array('class' => 'CunddTools',	'method' => 'links_ausgeben'),
						   array('class' => 'CunddTools',	'method' => 'CunddTools'),
						   array('class' => 'CunddTools',	'method' => 'log_fehler'),
						   array('class' => 'CunddTools',	'method' => 'log'),
						   array('class' => 'CunddTools',	'method' => 'breakpoint'),
						   array('class' => 'CunddTools',	'method' => 'bp'),
						   array('class' => 'CunddTools',	'method' => 'datum_anpassen'),
						   array('class' => 'CunddTools',	'method' => 'predump'),
						   array('class' => 'CunddTools',	'method' => 'log_fehler'),
						   array('class' => 'CunddTools',	'method' => 'log'),
						   array('class' => 'CunddTools',	'method' => 'breakpoint'),
						   array('class' => 'CunddTools',	'method' => 'bp'),
						   array('class' => 'CunddTools',	'method' => 'datum_anpassen'),
						   array('class' => 'CunddTools',	'method' => 'predump'),
						   array('class' => 'CunddTools',	'method' => 'pd'),
						   array('class' => 'CunddTools',	'method' => 'session_setter'),
						   array('class' => 'CunddTools',	'method' => 'xmlFileToArray'),
						   array('class' => 'CunddTools',	'method' => 'stepIntoNode'),
						   );
	$anfrage = '';
	mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'), 
				  CunddConfig::get('mysql_passwort'));
	mysql_query("USE `".CunddConfig::get('mysql_database')."`;");
	
	
	// Tabelleninhalt löschen
	if($cleanUpBevorInstall){
		$anfrage = "DELETE FROM `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
		"CunddContent`;";
	}
	
	
	
	// Records erstellen
	foreach($installFields as $key => $field){
		$anfrage .= "INSERT INTO `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
		"CunddContent` (`title`, `ersteller`, `erstellungsdatum`, `erstellungszeit`, `bearbeiter`, 
		`bearbeitungsdatum`, `bearbeitungszeit`, `eventdatum`, `subtitle`, `beschreibung`, `text`, `bildlink`, 
		`rechte`, `gruppe`, `sprache`, `schluessel`) VALUES ('".$field['class']."::".$field['method']."', 
		'installer_script', '".date('Y-m-d')."', '".date('H:i')."', 'installer_script', '".date('Y-m-d')."', 
		'".date('H:i')."', '', '', 'Automatically created record for ".$field['class']."::".$field['method']."', 
		'{".$field['class']."::".$field['method']."}', NULL, '0', '0', NULL, NULL);
		<br />";
	}
	$result = mysql_query($anfrage);
	
	
	// DEBUGGEN
	if($say){
		echo "-- result =".$result."<br />";
		echo $anfrage;
	}
	// DEBUGGEN
	
}
}
	
?>