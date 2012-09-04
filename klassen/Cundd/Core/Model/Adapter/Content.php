<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Core_Model_Adapter_Content erweitert Cundd_Core_Model_Adapter_Abstract
 * und bietet ein Klasse für das Lesen und Schreiben von Daten in MySQL-Tabellen die nach
 * dem Content-Schema aufgebaut sind. Dabei werden standardmäßig Sprache und Rechte des
 * aktuellen Users beachtet.
 * @package Cundd_Core_Model_Adapter_Abstract
 * @version 1.0
 * @since Jan 13, 2010
 * @author daniel
 */
class Cundd_Core_Model_Adapter_Content extends Cundd_Core_Model_Adapter_Abstract {
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Variablen deklarieren



    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Konstruktor
     */
    public function __construct(array $arguments = array()) {
	if(array_key_exists('table',$arguments)) {
	    $table = $arguments['table'];
	} else {
	    $table = "CunddContent";
	}

	if(array_key_exists('kind',$arguments)) 	$kind 	= $arguments['kind'];


	$this->CunddDB($table, $kind);
	return;
    }



    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode standardisiert den Zugriff auf Records in Tabellen nach dem
     * Content-Schema.
     * @return array|false
     */
    public function load() {
	$say = false;
	$where = array();
	// Optionen
	// Überprüfen ob der Benutzer zur Gruppe "root" gehört
	$ist_root = floor(CunddUser::getSessionUserValue('maingroup') / pow(2,1)) % 2;
	if(!$ist_root) {
	    $where[] = '{(}';
	    /* Überprüfen ob ein Benutzer eingeloggt ist und ob dieser die nötigen Rechte zum
				lesen der Einträge hat. Zuerst wird überprüft ob der User überhaupt eingeloggt ist. 
				Dann werden die Gruppen des Users gelesen und die Suche nach den passenden Einträgen 
				ermöglicht. */
	    // Öffentliche Einträge lesen
	    $where[] = "floor(rechte / POW(10,0)) % 10 > '0' ";

	    /* Wenn ein Benutzer eingeloggt ist alle Einträge zeigen die er erstellt hat oder die von
				einem Member der Gruppe erstellt wurde, dessen Hauptgruppe eine Gruppe des eingeloggten 
				Benutzers ist. */
	    if(CunddUser::isLoggedIn()) {
		$where[] = '{o}';
		$where['ersteller'] = CunddUser::getSessionUser(); // Ersteller ist Current User?
		$where[] = "OR floor(rechte / POW(10,4)) % 10 > '0' ";

		// Überprüfen ob für alle eingeloggten Benutzer sichtbar
		$where[] = "OR floor(rechte / POW(10,1)) % 10 > '0' ";


		// TODO: gruppe funktioniert nicht
		// Mitgliedschaft in der Hauptgruppe und die Rechte für die Gruppe
		// mitgliedschaft = benutzergruppe/(2^(gruppe-1)) % 2
		$gruppe = $_SESSION["gruppen"];
		$gruppe = CunddUser::getSessionUserValue('maingroup');

		$where[] = "OR ($gruppe & POW(2,gruppe) AND floor(rechte / POW(10,2)) % 10 > '0') ";
	    }

	    $where[] = '{)}';
	}


	if($ist_root) {
	    // $anfrage .= " WHERE schluessel LIKE '%'";
	}


	if(!CunddUser::isLoggedIn()) { // Verknüpft den Bereich zum Lesen öffentlicher Beiträge
	    $where[] = '{a}';
	}

	$where[] = '{(}';

	// Nach der Sprache filtern
	if(CunddConfig::get('cunddsystem_multilanguage_enabled')) {
	    $where[] = '{(}';
	    $where[] = "lang LIKE '".CunddLang::get()."' OR lang IS NULL";
	    $where[] = '{)}';
	    $where[] = '{a}';
	}

	// Anfrage schließen
	$where['aktiv'] = "1"; // Nur aktive Links
	$where[] = '{)}';
	$order = "prioritaet DESC, schluessel ASC, parent ASC"; // Ordnen


	// Die parent-Funktion aufrufen
	$result = parent::select(NULL,$where,NULL,$order);



	/* DEBUGGEN */
	if($say) {
	    CunddTools::pd($result);
	}
	/* DEBUGGEN */


	return $result;
    }
    /**
     * (non-PHPdoc)
     * @see load()
     */
    public function select() {
	return $this->load();
    }



    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob $data ein Element mit dem Key "id" enthält wenn ja, wird der
     * entsprechende Content-Record bearbeitet, ansonsten wird ein neuer erstellt.
     * @param array $data
     * @return boolean|number
     */
    public function save(array $data) {
	$update = FALSE;
	// Check if the corresponding table shall be created if it doesn't exist
	if(array_key_exists("_autoCreate", $data)){
	    if($data["_autoCreate"] !== "NO" AND $data["_autoCreate"] !== FALSE){
		$this->_createTableIfNotExists();
	    }
	}

	if(array_key_exists("id", $data)) {
	    if($data["id"]) $update = TRUE;
	}
	if($update){
	    echo "Update";
	    return $this->update($data, $data["id"]);
	} else {
	    echo "Insert";
	    return $this->insert($data);
	}
    }


    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode fügt einen neuen Content-Record hinzu.
     * @param array $data
     * @return number
     */
    public function insert(array $data) {
	// Data vorbereiten
	CunddBenutzer::prepareUserData($data);

	$db = new CunddDB($this->table);
	$table = $db->getTable();

	$adapter = $db->getAdapter();
	$columns = CunddFelder::get_eintrag();
	foreach($columns[0] as $key => $column) {
	    $parsedColumns[$column] = $column;
	}
	$relevantData = array_intersect_key($data,$parsedColumns);

	$result = $adapter->insert($table, $relevantData);
	$id = $adapter->lastInsertId();
	return $id;
    }



    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode bearbeitet einen bestehenden Content-Record.
     * @param array $data
     * @param int $contentId
     * @return boolean
     */
    public function update(array $data,$contentId) {
	$idColName = 'schluessel';

	// Data vorbereiten
	CunddBenutzer::prepareUserData($data);

	$db = new CunddDB($this->table);
	$table = $db->getTable();
	$adapter = $db->getAdapter();
	$columns = CunddFelder::get_eintrag();
	foreach($columns[0] as $key => $column) {
	    $parsedColumns[$column] = $column;
	}
	$relevantData = array_intersect_key($data,$parsedColumns);
	$where = "$idColName = $contentId";

	$result = $adapter->update($table, $relevantData, $where);
	return $result;
    }



    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     *  Checks if the corresponding table exists else create it
     * @return mixed
     */
    protected function _createTableIfNotExists() {
	if(!$this->table) return NULL;
	
	if(!$this->tableExists($this->table)) {
	    return $this->newTable($this->table);
	} else {
	    return TRUE;
	}
    }



    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Creates a new table with the given name. If no name is given the
     * method checks for a set table-property. If the property too isn't set
     * "CunddContent" will be used.
     * @param string $theTableName
     * @return mixed
     */
    public function newTable($theTableName = "CunddContent") {
	$drop_table = false;

	if($theTableName == "CunddContent" && $this->table) {
	    $theTableName = $this->table;
	}

	$felder = CunddFelder::get_eintrag();

	$inputs = array();

	for($i = 0; $i < count($felder[0]); $i++) {
	    $inputs[$felder[0][$i]] = $felder[1][$i];
	}

	/*

	if($drop_table) {
	    $anfrage = "DROP TABLE IF EXISTS `".CunddConfig::get("mysql_database")."`.`".CunddConfig::get('prefix')."$theTableName `; ";
	    mysql_query($anfrage);
	}

	$anfrage = "CREATE TABLE IF NOT EXISTS `".CunddConfig::get('mysql_database')."`.`".CunddConfig::get('prefix').
		"$theTableName ` (";
	for($i = 0; $i < count($felder[0]); $i++) {
	    $anfrage .= "`".$felder[0][$i]."` ";
	    $anfrage .= $felder[1][$i].", ";
	}
	$anfrage .= "PRIMARY KEY (`schluessel`)
            )
            CHARACTER SET utf8
            COMMENT = 'CunddBlog_mysql_table_schema';";

	$resultat = mysql_query($anfrage);
	 *
	 *
	 */
	$resultat = $this->create($inputs, "schluessel");
	return $resultat;
    }


}
?>