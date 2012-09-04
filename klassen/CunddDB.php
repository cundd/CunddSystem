<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * Die Klasse "CunddDB" zentralisiert die Kommunikation mit der Datenbank.
 */
class CunddDB extends Zend_Db {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	const CUNDD_STANDARD_ADAPTER = 'Pdo_Mysql';
	
	public $version = 0.1; // Die Version der Klasse
	public $result; // Speichert das geparsede Ergebnis
	public $kind; // Speichert die Art der Anfrage
	
	protected $_query; // Speichert die Anfrage
	protected $table; // Speichert den zur Anfrage gehörenden Tabellenname
	protected $state; // Speichert den Status der Anfrage
	protected $_connection; // Speichert die Server-Verbindung
	protected $_rawResult; // Speichert das nicht geparsede Ergebnis der Anfrage
	protected $where; // Speichert den WHERE-Part einer Anfrage
	protected $order; // Speichert den ORDER BY-Part einer Anfrage
	protected $adapter; // Speichert den Adapter
	protected $debug = false; // Debuggen
	
	protected static $tableHistory; // Speichert den Tabellennamen als Klassenvariable
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	public function CunddDB($table = NULL, $kind = NULL){
		$config = array(
			'host'     => CunddConfig::get('mysql_host'),
    		'username' => CunddConfig::get('mysql_benutzer'),
    		'password' => CunddConfig::get('mysql_passwort'),
    		'dbname'   => CunddConfig::get('mysql_database'),
			'profiler' => true,
		);
		
		if($table) $this->setTable($table);
		
		$this->adapter = Zend_Db::factory(self::CUNDD_STANDARD_ADAPTER,$config);
		
		
		return $db;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt eine neue Instanz des System-Standard-Adapter zurück. */
	/* public static function newAdapter(){
		$config = array(
			'host'     => CunddConfig::get('mysql_host'),
    		'username' => CunddConfig::get('mysql_benutzer'),
    		'password' => CunddConfig::get('mysql_passwort'),
    		'dbname'   => CunddConfig::get('mysql_database'),
		);
		
		return Zend_Db::factory(self::CUNDD_STANDARD_ADAPTER,$config);
	}
	/* */
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt die Anzahl der passenden Datensätze zurück.
	 *
	 * @param string|array $wherePara
	 * @return int
	 */
	public function count($wherePara = NULL){
		if($wherePara){
			$where = $this->cunddSetWhere($wherePara);
		}
		
		$target = $this->cunddGetTarget();
		
		$queryString = 'SELECT COUNT(*) FROM '.$target.' '.$where.';';
		
		$this->_sendQuery($queryString);
		
		$this->_query = $queryString;
		
		$this->result = $this->adapter->fetchOne($queryString);
		
		return (int)$this->result;

		/*
		$countResultArray = $this->parseResult();
		$countResult = (int)($countResultArray[0][0]); // * 1 = parse to int
		
		$this->result = $countResult;
		return $countResult;
		 * 
		 */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zur Daten-Eingabe. Die Eingabe-Parameter werden in 
	 * einem assoziativen Array übergeben. Key = MySQL-Field, Value = Value. */
/*	public function insert(array $input){
		// Die Spalten-Namen in einem String speichern
		/*
		 * 
		INSERT INTO table_name (column1, column2, column3,...)
		VALUES (value1, value2, value3,...)
		/* */
/*		$columes = array();
		$values = array();
		
		foreach($input as $key => $value){
			$columes[] = $key;
			$values[] = $value;
		}
		
		$columesString = CunddTools::arrayToString($columes);
		$valuesString = CunddTools::arrayToString($values,"','");
		$target = $this->getTarget();
		
		$queryString = 'INSERT INTO '.$target.' ('.$columesString.') VALUES (\''.$valuesString.'\');';
		$this->setQuery($queryString);
		
		$this->sendQuery();
		return $this->parseResult();
	}

	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zum Löschen von Records. */
/*	public function delete($where = NULL, $limit = NULL, $order = NULL){
		// Die Bedingungen festlegen
		if(isset($where)){
			$whereLocal = $this->setWhere($where);
		}
		
		// Das Limit festlegen
		if($limit){
			$limit = 'LIMIT '.$limit;
		}
		
		// Die Sortierung festlegen
		if($order){
			$order = $this->setOrder($order);
		}
		
		// Die Tabelle festlegen
		$source = $this->getTarget();
		
		$this->connect();
		
		$query = 'DELETE FROM '.$source.$whereLocal.$limit.$order.';';
		$this->setQuery($query);
		
		$this->sendQuery();
		return $this->parseResult();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zum Bearbeiten von Records. */
/*	public function update(array $input, $where = NULL, $limit = NULL, $order = NULL){
		// Die Spaltennamen und neuen Werte festlegen
		foreach($input as $key => $value){
			$columns[] = $key;
			$values[] = $value;
			$columnsAndValues[] = $key.'=\''.$value.'\'';
		}
		$setString = CunddTools::arrayToString($columnsAndValues);
		$setString = 'SET '.$setString;
		
		// Die Bedingungen festlegen
		if(isset($where)){
			$whereLocal = $this->setWhere($where);
		}
		
		// Das Limit festlegen
		if($limit){
			$limit = 'LIMIT '.$limit;
		}
		
		// Die Sortierung festlegen
		if($order){
			$order = $this->setOrder($order);
		}
		
		// Die Tabelle festlegen
		$source = $this->getTarget();
		
		$this->connect();
		
		$query = 'UPDATE '.$source.' SET '.$setString.$whereLocal.$limit.$order.';';
		$this->setQuery($query);
		
		$this->sendQuery();
		return $this->parseResult();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zum Erstellen einer Tabelle. Die Eingabe-Parameter 
	 * werden in einem assoziativen Array übergeben. Key = MySQL-Field, Value = Settings.
	 * @param array $input array('column name' => 'column settings')
	 * @param string $primaryKeyAndAutoIncrement Column name
	 * @param bool $ifNotExists
	 * @param string $characterSet
	 * @return unknown_type
	 */
	public function create(array $input, $primaryKeyAndAutoIncrement = NULL, $ifNotExists = true, $characterSet = 'CHARACTER SET utf8'){
		// Die Spalten-Namen in einem String speichern
		/*
		 * IF NOT EXISTS
		 *
		 * CREATE TABLE test (a INT NOT NULL AUTO_INCREMENT,
    ->        PRIMARY KEY (a), KEY(b))
    ->        ENGINE=MyISAM SELECT b,c FROM test2;


		 * 
		CREATE TABLE tk (col1 INT, col2 CHAR(5), col3 DATE)
  	  PARTITION BY KEY(col3)
  	  PARTITIONS 4;
		/* */
		$columes = array();
		$values = array();
		$columesAndSettings = array();
		$primaryKeyString = '';
		
		foreach($input as $key => $setting){
			// AutoIncrement und Primary Key
			if($key == $primaryKeyAndAutoIncrement){
				$setting = $setting.' NOT NULL AUTO_INCREMENT ';
				$primaryKeyString = ', PRIMARY KEY (`'.$key.'`)';
			}
			
			$columes[] = $key;
			$settings[] = $setting;
			$columesAndSettings[] = $key.' '.$setting;
		}
		
		$columesAndSettingsString = CunddTools::arrayToString($columesAndSettings);
		$target = $this->cunddGetTarget();
		
		if($ifNotExists){
			$ifNotExistsString = ' IF NOT EXISTS ';
		} else {
			$ifNotExistsString = '';
		}
		
		
		$queryString = 'CREATE TABLE '.$ifNotExistsString.' '.$target.' ('.$columesAndSettingsString.$primaryKeyString.') '.$characterSet.';';
		// $this->setQuery($queryString);
		
		return $this->_sendQuery($queryString);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zum Hinuzufügen einer Spalte zu einer Tabelle. */
/*	public function alterAdd(array $input, $afterTableName = NULL){
		$mode = 'ADD';
		return $this->alter($mode,$input,$afterTableName);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zum Löschen einer Spalte zu einer Tabelle. */
/*	public function alterDrop(array $input){
		$mode = 'DROP';
		return $this->alter($mode,$input);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Anfrage zum Bearbeiten der Struktur einer Tabelle. */
/*	public function alter($mode, array $input, $afterTableName = NULL){
		// Die Spalten-Namen in einem String speichern
		/*
		 * ALTER ONLINE TABLE t1 ADD COLUMN c3 INT COLUMN_FORMAT DYNAMIC STORAGE MEMORY;
		/* */
/*		$columes = array();
		$values = array();
		$columesAndSettings = array();
		
		foreach($input as $key => $setting){
			$columes[] = $key;
			$settings[] = $setting;
			$columesAndSettings[] = ' '.$mode.' '.$key.' '.$setting;
		}
		
		
		if($afterTableName){
			// TODO: AFTER doesn't work properly
			$afterString = ' AFTER '.$afterTableName.',';
		}
		
		
		$columesAndSettingsString = CunddTools::arrayToString($columesAndSettings,$afterString);
		$target = $this->getTarget();
		
		
		
		
		$queryString = 'ALTER TABLE '.$target.$columesAndSettingsString.';';
		$this->setQuery($queryString);
		
		$this->sendQuery();
		return $this->parseResult();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** Die Methode erstellt eine Anfrage zur Daten-Abfrage.
	 * @param string/array $what
	 * @param string/array $where
	 * @param array $limit
	 * @param string/array $order
	 * @return array
	 */
	public function select($what = NULL, $where = NULL, $limit = NULL, $order = NULL){
		$say = false;
		
		// Die Daten festlegen die abgefragt werden sollen
		if($what){
			$whatTemp = '';
			switch (gettype($what)) {
				case 'array':
					for($i = 0; $i < count($what)-1; $i++){
						$whatTemp .= ' '.$what[$i].',';
					}
					$whatTemp = $what[$i];
					break;
				
				case 'string':
					$whatTemp = $what;
					break;
					
				default:
					$whatTemp = $what;
				break;
			}
			
			$what = $whatTemp;
		} else {
			$what = ' * ';
		}
		
		
		// Die Bedingungen festlegen
		if(isset($where)){
			$whereLocal = $this->cunddSetWhere($where);
		}
		
		// Das Limit festlegen
		if($limit){
			$limitTemp = 'LIMIT '.$limit[0].','.$limit[1];
			$limit = $limitTemp;
		}
		
		// Die Sortierung festlegen
		if($order){
			$order = $this->cunddSetOrder($order);
		}
		
		// Die Tabelle festlegen
		$source = $this->cunddGetFrom();
		
		$query = 'SELECT '.$what.' FROM '.$source.$whereLocal.$limit.$order.';';
		
		// DEBUGGEN
		if($say){
			echo "\$query=$query";	
		}
		// DEBUGGEN
		
		$this->_query = $query;
		
		
		// $this->sendQuery() is replaced be fetchAll()
		$result = $this->adapter->fetchAll($query);
		
		return $result;
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die erzeugte Anfrage zurück.
	 * @return string
	 */
	public function getQuery(){
		return $this->_query;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Wert für $this->query.
	 * @param string $query
	 * @return void
	 */
/*	protected function setQuery($query){
		$this->query = $query;
		$this->state = 1;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den String für den INTO- bzw. FROM-Part zurück.
	 * @return string
	 */
	protected function cunddGetTarget(){
		$table = $this->getTable();
		$source = '`'.CunddConfig::get('mysql_database')."`.`".$table.'`';
		return $source;
	}
	protected function cunddGetFrom(){
		return $this->cunddGetTarget();
	}
	protected function cunddGetInto(){
		return $this->cunddGetTarget();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** Die Methode setzt den WHERE-Part einer Anfrage.
	 * Als Parameter erwartet die Funktionen einen String der die Bedingung darstellt, oder
	 * ein assoziatives Array dessen Key-Value-Paare das Feld (=Key) und den Wert/die 
	 * Bedingung (=Value) die Bedingungen abbilden. Diese Bedingungen werden standardmäßig 
	 * per AND verknüpft, außer das Element das auf das Key-Value-Paar folgt lautet:
	 * kein Key
	 * Value: "{o}"
	 * Trifft der Parser auf dieses Paar wird anstelle des AND ein OR der WHERE-Klausel hin-
	 * zugefügt. Analog definieren die Werte "{(}" und "{)}" das Öffnen bzw. schließen einer
	 * Klammer. "{o(}" und "{)o}" jeweils mit OR als Connector
	 * 
	 * @param string/array $wherePara
	 * @return unknown_type
	 */
	public function cunddSetWhere($wherePara){
		$say = false;
		$whereTemp = '';
		
		
		// Den aktuellen Wert von $this->where abrufen
		if(!$this->cunddGetWhere()){
			// $this->setWhere(' WHERE ');
			$this->where = ' WHERE ';
		}
		$oldWhere = $this->cunddGetWhere();
		
		// Den Typ des Parameter ermitteln
		if(gettype($wherePara) == 'array'){
			$lastValueWasConnector; // Speichert ob das letzte Element ein Connector war
			$isFirstElement = true;
			$whereTemp = '';
			
			foreach($wherePara as $key => $value){
				// Überprüfen ob der aktuelle Wert eines der Keywords ist
				if(is_numeric($key) AND $value == '{o}'){ // Connector einfügen
					$whereTemp .= ' OR ';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{(}'){
					
					if($lastValueWasConnector == false AND !$isFirstElement){
					// Wenn das vorige Element kein Connector war muss an dieser Stelle einer eingefügt werden 
						$whereTemp .= ' AND ';
					}
					$whereTemp .= ' ( ';
					$lastValueWasConnector = true;
					
					
				} else if(is_numeric($key) AND $value == '{)}'){
					$whereTemp .= ' ) ';
					$lastValueWasConnector = false;
					
				} else if(is_numeric($key) AND $value == '{a}'){
					$whereTemp .= ' AND ';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{o(}'){
					$whereTemp .= ' OR (';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{)o}'){
					$whereTemp .= ') OR ';
					$lastValueWasConnector = true;
				
				} else if(is_numeric($key)){
					$whereTemp .= $value;
					$lastValueWasConnector = false;
					
				} else {/* */
					// Die Bedingung einfügen
					if($lastValueWasConnector == false AND !$isFirstElement){
					// Wenn das vorige Element kein Connector war muss an dieser Stelle einer eingefügt werden 
						$whereTemp .= ' AND ';
					}
					
					// Bedingung
					$whereTemp .= $key." LIKE '".$value."'";
					
					if($isFirstElement){
						$isFirstElement = false;
					}
				}
			}
		} else if(gettype($wherePara) == 'string'){
			$whereTemp = $wherePara;
		}

		// DEBUGGEN
		if ($say) {
		    CunddTools::pd($whereTemp);
		    CunddTools::pd(gettype($wherePara));
		    CunddTools::pd(count($wherePara));
		}
		// DEBUGGEN

		if($whereTemp){
			$this->where = $oldWhere.$whereTemp;
			return $this->where;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Wert von $this->where zurück. */
	public function cunddGetWhere(){
		if($this->where){
			return $this->where;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt den ORDER BY-Part einer Anfrage.
	 * @param string/array $orderPara
	 * @return string/false
	 */
	public function cunddSetOrder($orderPara){
		// Den aktuellen Wert von $this->order abrufen
		$oldOrder = $this->cunddGetOrder();
		if(!$oldOrder){
			//$this->cunddSetOrder(' ORDER BY ');
			$this->order = ' ORDER BY ';
			$oldOrder = $this->cunddGetOrder();
		}
		
		switch (gettype($orderPara)) {
			case 'array':
				$orderTemp .= CunddTools::arrayToString($orderPara);
				break;
			
			case 'string':
				$orderTemp = $orderPara;
				break;
				
			default:
				$orderTemp = $orderPara;
			break;
		}
		
		if($orderTemp){
			$this->order = $oldOrder.$orderTemp;
			return $this->order;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Wert von $this->order. */
	public function cunddGetOrder(){
		if($this->order){
			return $this->order;
		} else {
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode parsed das Ergebnis der Anfrage */
/*	public function parseResult(){
		if($this->rawResult){
			$this->state = 4;
		} else {
			// DEBUGGEN
			if($this->debug){
				echo 'query: '.$this->query.'<br />';
				echo 'mysql_error: '.mysql_error();
			}
			// DEBUGGEN
			
			$this->state = 3;
			CunddTools::error('CunddDB',$this->getState.': '.mysql_error());
			return false;
		}
		
		if($this->state == 4){
			if(is_resource($this->rawResult)){
				while($row = mysql_fetch_array($this->rawResult)){
					$this->result[] = $row;
				}
				if($this->result){
					$this->state = 5;
					return $this->result;
				}
			} else if($this->rawResult == 1){ //!mysql_num_rows($this->rawResult)){
				$this->state = 5;
				return $this->rawResult;
			} else {
				$this->state = 6;
				
				// DEBUGGEN
				if($this->debug){
					echo 'rawResult: '.$this->rawResult;
				}
				// DEBUGGEN
				CunddTools::error('CunddDB','mysql_fetch_array(): supplied argument is not a valid MySQL result resource');
				return $this->rawResult;
			}
		}
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Status der Anfrage als String zurück */
/*	public function getState(){
		$stateCaptions = array('No query','Query created','Query sent','Server returned error','Success','Parsed','Empty result');
		
		return $stateCaptions[$this->state]; 
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt den Tabellennamen zurück oder gibt eine Fehlermeldung aus wenn 
	 * keiner definiert ist. */
	public function cunddGetTable($table = NULL){
		if($table){
			return $this->cunddSetTable($table);
		} else if($this->table){
			return $this->table;
		} else if(self::$tableHistory){
			return self::$tableHistory;
		} else {
			CunddTools::error('CunddDB','No table set');
		}
	}
	public function getTable($table = NULL){
		return $this->cunddGetTable($table);
	}
	public function table($table = NULL){
		return $this->cunddGetTable($table);
	}
	public function gt($table = NULL){
		return $this->cunddGetTable($table);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode setzt den Tabellennamen. */
	public function cunddSetTable($table){
		$table = CunddConfig::get('prefix').$table;
		$this->table = $table;
		self::$tableHistory = $table;
		return $table;
	}
	public function setTable($table){
		return $this->cunddSetTable($table);
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Adapter zurück. */
	/**
	 * @return 
	 */
	public function getAdapter(){
		return $this->adapter;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode sendet die Anfrage */
	protected function _sendQuery($query){
		if(!$this->_connection){
			$this->_connect();
		}
		$this->_rawResult = mysql_query($query, $this->_connection);
		return $this->_rawResult;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erstellt eine Verbindung zum DB-Server. */
	public function _connect(){
		$this->_connection = mysql_connect(CunddConfig::get('mysql_host'),CunddConfig::get('mysql_benutzer'),CunddConfig::get('mysql_passwort'));
		if(!$this->_connection){
			CunddTools::log('CunddDB','Could not connect to database');
			// DEBUGGEN
			if($this->debug){
				echo 'mysql_error: '.mysql_error();
			}
			// DEBUGGEN
			return false;
		} else {
			return $this->_connection;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob eine Tabelle existiert.
	 * @param string $table Tablename without the system-prefix
	 * @return boolean
	 */
	public static function tableExists($table){
		$tempDb = new CunddDB($table);
		$anfrage = "SHOW TABLES LIKE '".CunddConfig::get('prefix').$table."';";
		$result = $tempDb->getAdapter()->query($anfrage)->fetchAll();
		return (bool) count($result);
	}
/* */
}
?>