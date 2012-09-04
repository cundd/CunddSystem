<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddBlogLesen" liest die bestehenden Daten aus der angegebenen MySQL-
  Tabelle und gibt die Daten aus. */
class CunddBlogLesen {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Variablen deklarieren
    var $blog_inst; // Speichert einen Zeiger auf die Instanz des Eltern-Objekts
    var $seiten_zaehler; /* Variable zum Speichern welche der Seiten mit Einträgen angezeigt
      werden soll (z.B. Seite 3/5). */
    public $currentPage = 1;
    public $entries = 0; // Saves the number of entries in the database
    public $numberOfEntries = 0; // Saves the complete number of entries the current user is allowed to read
    protected $_output;
    protected $_blogEntriesPerPage;

    const CUNDD_BLOG_CURRENT_PAGE_KEY = 'page';

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    function CunddBlogLesen(CunddBlog $CunddBlog_para, $ausgabeform = 'HTML') {
	$say = false;

	$this->_output = $ausgabeform;

	// Das Eltern-Objekt speichern
	$this->blog_inst = $CunddBlog_para;

	// Das TinyMCE-Plugin laden
	$tinyMCE = new CunddTinyMCE();




	// Mit MySQL-Datenbank verbinden
	mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'),
		CunddConfig::get('mysql_passwort'));
	mysql_query("USE `" . CunddConfig::get('mysql_database') . "`;");

	/* Der derzeitige Stand des Seiten-Zählers wird per Request-data übertragen. */
	$tempCurrentPage = (int)Cundd_Request::getPara(self::CUNDD_BLOG_CURRENT_PAGE_KEY);
	if($tempCurrentPage){
	    $this->currentPage = $tempCurrentPage;
	}

//	$requestPara = Cundd_Request::getAllParameters();
//	if ($requestPara) {
//	    if (array_key_exists(self::CUNDD_BLOG_CURRENT_PAGE_KEY, $requestPara)) {
//		$this->currentPage = $requestPara[self::CUNDD_BLOG_CURRENT_PAGE_KEY];
//	    }
//	}


	// Die grundsätzliche Anfrage
	$anfrage = "SELECT * FROM `" . CunddConfig::get('mysql_database') . "`.`" . CunddConfig::get('prefix') .
		$this->blog_inst->tabelle . "`";

	// Optionen
	/* Überprüfen ob ein Limit für die Anzahl der Einträge pro Seite festgelegt wurde und
	  entsprechend die MySQL-Anfrage mit oder ohne LIMIT-Parameter senden. */
	if (CunddConfig::get(zeige_eintrag_limit)) {
	    $anfrage .= " LIMIT " . $seiten_zaehler * CunddConfig::get(zeige_eintrag_limit) .
		    ", " . CunddConfig::get(zeige_eintrag_limit) . ";";
	}


	// Überprüfen ob der Benutzer zur Gruppe "root" gehört
	$ist_root = floor($_SESSION["gruppen"] / pow(2, 1)) % 2;
	if (!$ist_root) {
	    /* Überprüfen ob ein Benutzer eingeloggt ist und ob dieser die nötigen Rechte zum
	      lesen der Einträge hat. Zuerst wird überprüft ob der User überhaupt eingeloggt ist.
	      Dann werden die Gruppen des Users gelesen und die Suche nach den passenden Einträgen
	      ermöglicht. */
	    // Öffentliche Einträge lesen
	    $anfrage .= " WHERE (floor(rechte / POW(10,0)) % 10 > '0' ";

	    /* Wenn ein Benutzer eingeloggt ist alle Einträge zeigen die er erstellt hat oder die von
	      einem Member der Gruppe erstellt wurde, dessen Hauptgruppe eine Gruppe des eingeloggten
	      Benutzers ist. */
	    if ($_SESSION["benutzer"]) {
		$anfrage .= "OR ersteller LIKE '" . $_SESSION["benutzer"] .
			"' OR floor(rechte / POW(10,4)) % 10 > '0' ";

		// Überprüfen ob für alle eingeloggten Benutzer sichtbar
		$anfrage .= "OR floor(rechte / POW(10,1)) % 10 > '0' ";

		// Mitgliedschaft in der Hauptgruppe und die Rechte für die Gruppe

		/* ALT
		  // mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
		  $gruppe = $_SESSION["gruppen"];
		  /*
		  $anfrage .= "OR (floor(".$gruppe.
		  " / POW(2,gruppe)) % 2 AND floor(rechte / POW(10,2)) % 10 > '0') ";
		 *
		  $anfrage .= "OR (".$gruppe.
		  " & POW(2,gruppe) AND floor(rechte / POW(10,2)) % 10 > '0') ";
		 */

		/* Alle Gruppen ermitteln zu denen der Benutzer gehört und überprüfen ob die Nachricht
		  an eine dieser Gruppen adressiert ist. */
		// mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
		/* $gruppen = CunddGruppen::get();
		  for($i = 0; $i < count($gruppen); $i++){
		  $anfrage .= "OR (".$gruppen[$i][1]." & POW(2,gruppe) AND floor(rechte / POW(10,2)) % 10 > '0') ";
		  }// */
	    }

	    $anfrage .= ")";
	}


	if ($ist_root) {
	    $anfrage .= " WHERE schluessel LIKE '%'";
	}

	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Nach der Sprache filtern
	if (CunddConfig::get('cunddsystem_multilanguage_enabled')) {
	    $anfrage .= " AND (";
	    $anfrage .= "lang='" . CunddLang::get() . "' OR ";
	    $anfrage .= "lang IS NULL OR lang='0') ";
	}

	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Gelöschte ausschließen
	$anfrage .= " AND (geloescht='0000-00-00' OR geloescht='0')";



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Set the sort order
	$anfrage .= " ORDER BY ";

	/* Überprüfen ob die Artikel nach dem Event-Datum geordnet werden sollen. Wenn ja,
	  werden die Artikel nicht in der Reihnfolge ihrer Erstellung sondern in Reihnfolge
	  des Event-Datums ausgegeben. Ausschlaggebend ist der Wert von "zeige_eventdatum" in
	  "config.php". Ist dieser gleich 1 wird die Reihung nach dem Event-Datum aktiv. */
	if (CunddConfig::get('zeige_eventdatum')) {
	    $anfrage .= " eventdatum DESC, schluessel DESC ";
	} else {
	    // Nach "schluessel" sortieren
	    $anfrage .= " schluessel DESC ";
	}


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Apple the limit if the configuration for blog_entries_per_page is not 0
	$this->_blogEntriesPerPage = CunddConfig::get("blog_entries_per_page");
	if ($this->_blogEntriesPerPage) {
	    $offset = $this->_blogEntriesPerPage * ($this->currentPage - 1);
	    if($offset < 0) $offset = 0;
	    $anfrage .= " LIMIT $offset,$this->_blogEntriesPerPage ";
	}


	// Anfrage schließen
	$anfrage .= ";";


	// DEBUGGEN
	if ($say) {
	    echo 'MySQL-Anfrage: ' . $anfrage;
	}


	// MySQL-Anfrage stellen
	$resultat = mysql_query($anfrage);


	


	// Überprüfen ob MySQL eine Antwort geliefert hat
	if ($resultat) {
	    // Ergebnisse ausgeben
	    $this->ergebnis_ausgeben($resultat, $this->blog_inst->max_eintraege, $ausgabeform);
	} else {
	    // Fehlermeldung ausgeben
	    echo 'Beim Auslesen der MySQL-Tabelle ist ein Fehler aufgetreten. <br />Benutzer:' .
	    $_SESSION["benutzer"] . ', in der Gruppe:' . $_SESSION["gruppen"] . '.';

	    if(CunddConfig::get("controller_display_debug_all")){
		CunddTools::pd(mysql_error());
	    }
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Funktion gibt das Ergebnis der MySQL-Anfrage aus bzw. vermittelt das Ergebnis an
     * die Template-Manager-Klasse "CunddTemplate" und erstellt einen leeren Eintrag, wenn das
     * Maximum der Einträge für diese Seite noch nicht erreicht ist.
     * @param Resource $resultat
     * @param int $max_eintraege
     * @param string $ausgabeform
     */
    public function ergebnis_ausgeben($resultat, $max_eintraege, $ausgabeform) {
	/* Die Art der Ausgabe der Daten kann entweder im HTML- oder XML-Format geschehen.
	  Wenn es im XML-Format ausgegeben wird soll kein leerer Eintrag erstellt werden. */
	if ($ausgabeform == 'HTML') {
	    /* Überprüfen ob noch Einträge erstellt werden dürfen und ggfl. einen leeren Ein-
	      trag einfüllen. */
	    $this->max_eintraege_check($resultat, $max_eintraege);
	}




	// Ausgefüllte Einträge ausgeben
	while ($wert = mysql_fetch_array($resultat)) {
	    $wert["tabelle"] = $this->blog_inst->tabelle;

	    if ($ausgabeform == 'HTML') {
		CunddTemplate::show($wert);
	    } else if ($ausgabeform == 'XML') {
		CunddTemplate::show_xml($wert);
	    }
	}
	

	// Create footer with "next page" and "previous page"
	$this->_createPageChooser();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Creates the page chooser with "next page", "previous page", or similar, if the output-format is HTML and multiple sites are
     * applicable.
     */
    protected function _createPageChooser() {
	// Get the complete number of entries
	$this->getNumberOfEntries();

	// If the page function is disabled == the config value for blog_entries_per_page
	if (!$this->_blogEntriesPerPage)
	    return;

	$this->numberOfEntries = $this->getNumberOfEntries();
	$hasPrevious = false;
	$hasNext = false;

	// Check if there are previous pages
	if ($this->currentPage > 1) {
	    $hasPrevious = true;
	}

	// Check if there are upcoming pages
	if ($this->numberOfEntries > $this->_blogEntriesPerPage * $this->currentPage) {
	    $hasNext = true;
	}

	$tag = 'Cundd_Blog_Standard_Page_Chooser';
	$type = 'special';
	$right = 0;

	// Get the users right for this blog
	if ($this->blog_inst->gruppe) {
	    if (CunddGroup::isIn((int) $this->blog_inst->gruppe)) {
		$right = 6;
	    }
	}


	
	// Display the links
	$parameters = array('previous' => $hasPrevious,
	    'next' => $hasNext,
	    'currentPage' => $this->currentPage,
	    'blogEntriesPerPage' => $this->_blogEntriesPerPage,
	    'numberOfEntries' => $this->numberOfEntries,
	    'maxNumberOfEntries' => $this->blog_inst->max_eintraege,
	    'tableName' => $this->blog_inst->tabelle,
	    'table' => $this->blog_inst->tabelle,
	);

	
	echo CunddTemplate::__($parameters, $right, $tag, $type);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Determines the number of entries in the blog table.
     * @return int
     */
    public function getNumberOfEntries() {
	if ($this->numberOfEntries == 0) {

	    $where = '';
	    // Überprüfen ob der Benutzer zur Gruppe "root" gehört
	    $ist_root = floor($_SESSION["gruppen"] / pow(2, 1)) % 2;
	    if (!$ist_root) {
		/* Überprüfen ob ein Benutzer eingeloggt ist und ob dieser die nötigen Rechte zum
		  lesen der Einträge hat. Zuerst wird überprüft ob der User überhaupt eingeloggt ist.
		  Dann werden die Gruppen des Users gelesen und die Suche nach den passenden Einträgen
		  ermöglicht. */
		// Öffentliche Einträge lesen
		$where .= "  (floor(rechte / POW(10,0)) % 10 > '0' ";

		/* Wenn ein Benutzer eingeloggt ist alle Einträge zeigen die er erstellt hat oder die von
		  einem Member der Gruppe erstellt wurde, dessen Hauptgruppe eine Gruppe des eingeloggten
		  Benutzers ist. */
		if ($_SESSION["benutzer"]) {
		    $where .= "OR ersteller LIKE '" . $_SESSION["benutzer"] .
			    "' OR floor(rechte / POW(10,4)) % 10 > '0' ";

		    // Überprüfen ob für alle eingeloggten Benutzer sichtbar
		    $where .= "OR floor(rechte / POW(10,1)) % 10 > '0' ";

		    // Mitgliedschaft in der Hauptgruppe und die Rechte für die Gruppe

		    /* ALT
		      // mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
		      $gruppe = $_SESSION["gruppen"];
		      /*
		      $anfrage .= "OR (floor(".$gruppe.
		      " / POW(2,gruppe)) % 2 AND floor(rechte / POW(10,2)) % 10 > '0') ";
		     *
		      $anfrage .= "OR (".$gruppe.
		      " & POW(2,gruppe) AND floor(rechte / POW(10,2)) % 10 > '0') ";
		     */

		    /* Alle Gruppen ermitteln zu denen der Benutzer gehört und überprüfen ob die Nachricht
		      an eine dieser Gruppen adressiert ist. */
		    // mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
		    /* $gruppen = CunddGruppen::get();
		      for($i = 0; $i < count($gruppen); $i++){
		      $anfrage .= "OR (".$gruppen[$i][1]." & POW(2,gruppe) AND floor(rechte / POW(10,2)) % 10 > '0') ";
		      }// */
		}

		$where .= ")";
	    }


	    if ($ist_root) {
		$where .= " schluessel LIKE '%'";
	    }

	    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	    // Nach der Sprache filtern
	    if (CunddConfig::get('cunddsystem_multilanguage_enabled')) {
		$where .= " AND (";
		$where .= "lang='" . CunddLang::get() . "' OR ";
		$where .= "lang IS NULL OR lang='0') ";
	    }

	    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	    // Gelöschte ausschließen
	    $where .= " AND (geloescht='0000-00-00' OR geloescht='0')";

	    // Anfrage schließen
//	    $where .= ";";


	    $count = 0;
	    $db = new CunddDB($this->blog_inst->tabelle);

	    $this->numberOfEntries = $db->count($where);

	    
	}

	return $this->numberOfEntries;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /* Die Methode erstellt einen leeren Eintrag, wenn das Maximum der Einträge für diese
      Seite noch nicht erreicht ist oder die Variable = "n" ist. */
    public function max_eintraege_check($resultat, $max_eintraege) {
	// Get the complete number of entries
	$count = $this->getNumberOfEntries();

	/*
	// Anzahl der verhandenen Einträge abrufen
	mysql_connect(CunddConfig::get('mysql_host'), CunddConfig::get('mysql_benutzer'),
		CunddConfig::get('mysql_passwort'));

	$anfrage = "SELECT COUNT(*) FROM `" . CunddConfig::get('mysql_database') . "`.`" . CunddConfig::get('prefix') .
		$this->blog_inst->tabelle . "` WHERE geloescht='0000-00-00' OR  geloescht='0';";
	$resultat2 = mysql_query($anfrage);
	$count = mysql_fetch_row($resultat2);
	 *
	 */

	// TODO: $max_eintraege wird nicht gelesen
	if ($count < $max_eintraege OR $max_eintraege == "n") {
	    $wert["gruppe"] = $_SESSION["hauptgruppe"];
	    $wert["tabelle"] = $this->blog_inst->tabelle;
	    $wert["text"] = CunddLang::get('CunddBlog_new_entry');
	    $wert["new_entry"] = true;
	    // Die zur Seite gehörende Toolbar anzeigen
	    /* echo '<script type="text/javascript">var Cundd_zeige_content_toolbar = false;</script>'; */
	    if ((CunddConfig::get('zeige_content_toolbar') AND CunddConfig::get('oeffentlich_schreiben')) OR
		    (CunddConfig::get('zeige_content_toolbar') AND $_SESSION["benutzer"])) {
		echo CunddTemplate::inhalte_einrichten($wert, $recht, content_toolbar, spezial);

		/* Eine JavaScript-Variable einrichten, die JavaScript mitteilt, dass die
		  Content-Toolbar sichtbar ist. */
		/* echo '<script type="text/javascript">var Cundd_zeige_content_toolbar = true;</script>'; */
	    }


	    // Einen leeren Eintrag erstellen
	    CunddTemplate::show($wert);
	}
    }

}

?>