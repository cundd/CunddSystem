<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddContent" verwaltet die verschiedenen Seiteninhalte der Site. */
class CunddContent{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	const CUNDD_CONTENT_TEMPLATE_BASE_DIR_DESCRIPTION = 'Cundd_Content_';
	public $version = 1.1;
	private $contentId = 0; // Speichert die eindeutige ID des Content-Eintrags
	private $contentRecord = array(); // Speichert die aus der MySQL-Tabelle ausgelesenen Daten
	private $contentTextArray = array(); // Speichert das Array das den Inhalt wiedergibt
	private $contentToExecute = array(); // Speichert nur die Elemente die ausgeführt werden
	private $output = ''; // Speichert den Output des Records
	private $textOutput = ''; // Speichert den Text um ihn dann via CunddTemplate einzufügen
	private $search; // Speichert den ursprünglichen Suchparameter
	private $skipEmptyContentText = 1;
	private $debug = false;
	
	protected $_templatePrefix = 'Cundd_Content_Output_Standard_';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * @param string|number $search
	 * @param boolean $withoutParsing
	 * @param array $additionalPara
	 * @param boolean $noOutput
	 * @return string|false|string
	 */
	function CunddContent($search,$withoutParsing = NULL,array $additionalPara = NULL,$noOutput = NULL){
		$this->cleanUpSearch($search);
		$this->search = $search;
		
		if(is_numeric($search)){
			$this->contentId = $search;
			$this->get();
		} else if(!$this->getTemplate($search,$additionalPara)){
			$this->getByTitle($search);
		}

		
		
		if(!$withoutParsing){
			// Wenn der Content-Record existiert und der Editor geladen werden soll
			if($this->getContentRecord() AND CunddRight::get($this->contentRecord) >= 6){
				$this->output = '';
				$this->initEditor();
			} else if($this->contentRecord){ // Wenn der Content-Record existiert
				ob_start();
					$this->parse();
					$this->output .= ob_get_contents();
				ob_end_clean();
				
			} else if(!$this->createNewContentRecord()){ // Der Content-Record existiert nicht
				if($this->debug){
					$this->output = '$contentRecord is empty with $search = '.$search;
					CunddTools::log('CunddContent', $this->output);
					$this->render();
					return $this->output;
				} else {
					return (bool) false;
				}
			} else {
				$this->output .= 'Content record created at content-Id '.$this->contentId.'. Search was '.$this->search;
			}
			
			// Den TinyMCE-Wrapper hinzufügen
		/*	if($this->getContentRecord()){
				if(CunddRight::get($this->contentRecord) >= 6){
					$this->output = '';
					$this->initEditor();
				}
			}
			/* */
			
			// Das Ergebnis anzeigen
			if(!$noOutput) $this->render();
			
			return $this->output;
		}
		/* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest einen bestimmten Content-Eintrag entsprechend der Eigenschaft 
	 * $contentId aus.
	 */
	public function getById(){
		$say = false;
		
		
		$this->_getFromDatabase(array('schluessel' => $this->contentId));
		
		return;
	}
	
	private function get(){
		return $this->getById();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest einen bestimmten Content-Eintrag definiert durch den title aus.
	 * @param string $title
	 * @return unknown_type
	 */
	public function getByTitle(&$title){
		$say = false;
		
		// Clean up parameter
		$str_replace_para = array("\\'");
		$title = str_replace($str_replace_para,'',$title);
		
		return $this->_getFromDatabase(array('title' => $title));
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode stellt die Anfrage an die Datenbank.
	 * @param array $where[optional] array( 'keyIsColumn' => 'valueIsValue' [ , ... ] )
	 * @param boolean $ignoreLang[optional]
	 * @return array|false
	 */
	protected function _getFromDatabase(array $where = array(),$ignoreLang = false){
		$table = 'CunddContent';
		$db = new CunddDB($table);
		
		
		/* Nach der Sprache filtern wenn das System mehrsprachig läuft und das Argument 
		 * $ignoreLang nicht TRUE ist
		 */
		if(CunddConfig::get('cunddsystem_multilanguage_enabled') AND !$ignoreLang){
			if(!array_key_exists('lang',$where)){
				$where[] = '{(}';
				// $where['lang'] = CunddLang::get();
				// $where[] = '{o}';
				// $where['lang'] = CunddLang::get();
				$where[] = "lang LIKE '".CunddLang::get()."' OR lang IS NULL";
				$where[] = '{)}';
			}
			/* '{o}'){ // Connector einfügen
					$whereTemp .= ' OR ';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{(}'){
					$whereTemp .= ' ( ';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{)}'){
					$whereTemp .= ' ) ';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{o(}'){
					$whereTemp .= ' OR (';
					$lastValueWasConnector = true;
					
				} else if(is_numeric($key) AND $value == '{)o}'){
				*/
			/*
			$anfrage .= " AND (";
			$anfrage .= "lang='".CunddLang::get()."' OR ";
			$anfrage .= "lang IS NULL OR lang='0') ";
			/* */
			
		}
		$result = $db->select(NULL,$where);
		
		
		
		if($result){
			$this->contentRecord = $result[0];
			$this->contentId = $this->contentRecord['schluessel'];
			return $result[0];
		} else {
			$fehlerMsg = 'Warning: mysql_fetch_array(): supplied argument is not a valid MySQL result resource in /Users/daniel/Sites/CunddSystem/Cundd/klassen/CunddContent.cpp on line 34 
				anfrage='.$anfrage;
			CunddTools::log_fehler('CunddContent',$fehlerMsg);
			return false;
		}
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode parsed den Inhalt eines Content-Eintrags. Das Feld "text" wird dabei nach 
	 * Zeichenketten eingeschlossen von "{" und "}" durchsucht. Diese Zeichenketten werden 
	 * dann an CunddController gesendet.
	 * @return array
	 */
	public function parse(){
		$say = false;
		
		/* Die Tags nach denen das Text-Feld durchsucht wird */
		$cunddTags = '!\{[a-zA-Z0-9:()$-<>_]*\}!';
		
		// Den Prefix für die Cases in CunddTemplate lesen
		if(CunddConfig::__('Content/template_prefix')){
			$this->_templatePrefix = CunddConfig::__('Content/template_prefix');
		}
		
		
		$fields = CunddFelder::get_eintrag();
		foreach($fields[0] as $key => $field){
			if(CunddConfig::get('content_show_'.$field) AND $field != 'text'){ // Den Zusatztext ausgeben
				$this->output .= CunddTemplate::inhalte_einrichten(
						$this->getContentRecord(), 
						$this->getContentRecord('recht'), 
						$this->_templatePrefix.$field, 
						'output');
			
			
			} else if(CunddConfig::get('content_show_'.$field) AND $field == 'text'){ // Den Haupttext ausgeben
				$contentText = $this->contentRecord['text']; // Das Text-Feld
				$matches = array();
				
				// Die Tags extrahieren
				$numberOfMatches = preg_match_all($cunddTags ,$contentText, $matches);
				$this->contentToExecute = $matches[0];
				
				// Die Textteile zwischen den Tags extrahieren
				$splitMatches = preg_split($cunddTags, $contentText, NULL);
				
				// Den Content in die richtige Reihnfolge bringen
				foreach($splitMatches as $key => $textPart){
					$tempContentArray[] = $textPart;
					$tempContentArray[] = $this->contentToExecute[$key];
				}
				
				// DEBUGGEN
				if($say OR $this->debug){
					echo '<h2>$matches:</h2>'; CunddTools::pd($matches);
					echo '<h2>$split:</h2>'; CunddTools::pd($splitMatches);
					echo '<h2>$tempContentArray:</h2>'; CunddTools::pd($tempContentArray);
					echo '<h2>$contentToExecute:</h2>'; CunddTools::pd($this->contentToExecute);
				}
				
				$this->contentTextArray = $tempContentArray;

				// Execute in-text-code
				$this->execute($field);

				// Create the output for the executed text
				$data = $this->getContentRecord();
				$data['text'] = $this->textOutput;
				$this->output .= CunddTemplate::inhalte_einrichten(
						$data,
						$this->getContentRecord('recht'),
						$this->_templatePrefix.'text',
						'output');
			}
		}
		return $tempContentArray;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode führt die in den Tags definierten Funktionen aus.
	 * @param string $keyPara
	 * @return boolean|number
	 */
	private function execute($keyPara){
		$say = false;
		
		// Die Controller-Instanz einlesen
		$controller = &$GLOBALS['CunddController'];
		$i = 0;
		
		// Wenn die Klasse CunddController nicht gefunden wird, wird die CunddController-Datei geladen
/*		if(!class_exists('CunddController')){
			require(CunddConfig::get('CunddAjaxPHP_verweis'));
		}
		if(!$controller){
			$controller = new CunddController('initOnly');
		}
		/* */
		
		
		
		foreach($this->contentToExecute as $key => $textPart){
			while($i < count($this->contentTextArray)){
				$patternToSkip = "![\n|\r|\t]*!"; // $patternToSkip = "!\a|\e|\f|\v|\cM\cJ|\n|\r|\t!";
				$contentTextArrayPartClean = preg_replace($patternToSkip,'', $this->contentTextArray[$i]);
				
				if($this->skipEmptyContentText AND ($contentTextArrayPartClean == '' OR $this->contentTextArray[$i] == "")){
					/* DEBUGGEN */if($say OR $this->debug) echo "SKIPPED$contentTextArrayPartClean.";/* DEBUGGEN */
					$i++;
				} else 
				// Überprüfen ob das aktuelle Element das nächste auszuführenden Array ist
				if($textPart == $this->contentTextArray[$i]){ // Wenn ja -> ausführen
					/* Die Klammern '{' und '}' entfernen und den String als Parameter 
					an den Controller senden, wenn der String nicht mit "->" beginnt. */
					$internCall = str_replace('{','',str_replace('}','',$this->contentTextArray[$i]));
					
					$patternIdentifyMethod = '!\-.gt.|\->!';
					$callsMethod = preg_match($patternIdentifyMethod, $internCall);
					
					if($callsMethod < 1){
						
						// TODO: $tempCunddAjax = new CunddAjax($internCall);
						
						
						// DEBUGGEN
						if($say OR $this->debug){
							echo '$controller = ';
							var_dump($controller);
						}
						// DEBUGGEN
						
						$tempCunddController = $controller->init($internCall);
						
						// DEBUGGEN
						if($say OR $this->debug){
							echo '$controller = ';
							var_dump($controller);
						}
						// DEBUGGEN
						
						$i++;
						break;
					
					/* Wenn $internCall mit "->" beginnt wird der in $internCall definierte
					 Methode-Aufruf, an das vorher erstellte Objekt (gespeichert in  $controller->kind) gesendet. */
					} else {
						$patternIdentifyMethod = '!\-.gt.|\->!';
						$internCall = preg_replace($patternIdentifyMethod,'',$internCall); // "->" entfernen
						//echo "<h1>\$internCall nummer =$internCall.</h1><br>";
						
						// DEBUGGEN
						if($say OR $this->debug){
							echo '<br />$controller = ';
							var_dump($controller);
							echo '<br />$controller->kind = ';
							var_dump($controller->kind);
						}
						// DEBUGGEN
						
						/*
						$internCallAndPara = explode("(",$internCall);
						$internCall = $internCallAndPara[0];
						$para = str_replace(')','',$internCallAndPara[1]);
						/* */
						$return = CunddTools::stringToActionAndPara($internCall,$internCall,$para);
						
						$tempCunddControllerChild = call_user_func(array($controller->kind, $internCall), $para);
						
						//echo 'i'.$internCall;
						//$tempCunddAjax = call_user_method($internCall, &$CunddAjax_instanz->kind);
						//$tempCunddAjax = $CunddAjax_instanz->CunddAjax($internCall);
						$i++;
						break;
					}
				
				// Wenn nicht den Part ausgeben
				} else {
					// DEBUGGEN
					if($say OR $this->debug){
						echo "<h2>Print current content: ".$this->contentTextArray[$i]."</h2>";
					}
					// DEBUGGEN
					
					$tempOutput = $this->contentTextArray[$i];
					$this->textOutput .= $tempOutput;
					$i++;
				}
			}
		}
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Wenn keine Befehle ausgeführt werden müssen wird der Content ausgegeben
		if(count($this->contentToExecute) == 0){
			/* DEBUGGEN */if($this->debug) echo '<h2>No content to execute</h2>';/* DEBUGGEN */
			// CunddTemplate::showContent($this->getContentRecord());
			$this->textOutput .= $this->getContentRecord($keyPara);
			return (bool) true;
		} else {
			return $i;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den ermittelten Record zurück.
	 * @param string $field
	 * @return array
	 */
	public function getContentRecord($field = NULL){
		if($field){
			if(array_key_exists($field,$this->contentRecord)){
				return $this->contentRecord[$field];
			}
		}
		return $this->contentRecord;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den gerenderten Output zurück.
	 * @return string
	 */
	public function getOutput(){
		return $this->output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode fügt einen neuen Content-Record hinzu.
	 * @param array $data
	 * @return int
	 */
	public static function insert($data){
		// Data vorbereiten
		CunddBenutzer::prepareUserData($data);
		
		$db = new CunddDB('CunddContent');
		$table = $db->getTable();
		$adapter = $db->getAdapter();
		$columns = CunddFelder::get_eintrag();
		foreach($columns[0] as $key => $column){
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
	public static function update(array $data,$contentId){
		$idColName = 'schluessel';
		
		// Data vorbereiten
		CunddBenutzer::prepareUserData($data);
		
		$db = new CunddDB('CunddContent');
		$table = $db->getTable();
		$adapter = $db->getAdapter();
		$columns = CunddFelder::get_eintrag();
		foreach($columns[0] as $key => $column){
			$parsedColumns[$column] = $column; 
		}
		$relevantData = array_intersect_key($data,$parsedColumns);
		$where = "$idColName = $contentId";
		
		
		
		$result = $adapter->update($table, $relevantData, $where);
		return $result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode . */
	public static function getMaxId(){
		$idColName = 'schluessel';
		$db = new CunddDB('CunddContent');
		$table = $db->getTable();
		$sql = "SELECT MAX($idColName) FROM $table"; 
		$result = $db->getAdapter()->fetchRow($sql);
		return $result['MAX(schluessel)'];
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob für den aktuellen Content eine Template-Datei existiert. Zur 
	 * Bestimmung des Namens der Template-Datei werden alle Sonderzeichen im Content-Name 
	 * gelöscht.
	 * @param string $contentname
	 * @param array $para
	 * @return boolean|mixed|boolean
	 */
	private function getTemplate($contentname,$para = NULL){
		$say = false;
		
		// Prepare the $contentname
		$pattern = "/[^a-zA-Z0-9]/";
		$contentname = preg_replace($pattern, "", $contentname);
		$contentname = ucfirst(strtolower($contentname));
		
		
		$tag = self::CUNDD_CONTENT_TEMPLATE_BASE_DIR_DESCRIPTION.$contentname;
		$layoutPath = CunddPath::getAbsoluteLayoutPath($tag);
		
		if(!CunddConfig::get('CunddTemplate_advanced_enabled')) return (bool) false;
		
		// CunddTemplate_advanced is enabled
		/* DEBUGGEN */if($say AND !CunddTools::fileExists($layoutPath,true)) echo "The file $layoutPath does not exist.";/* DEBUGGEN */
		if(CunddTools::fileExists($layoutPath,true) AND class_exists('CunddView') AND class_exists('CunddLayout')){
			$layout = new CunddLayout();
			$view = new CunddView();
			
			$view->clearAllPlaceholders();
			if($para) $view->registerPlaceholdersFromArray($wert);
			
			$layout->setView($view);
			
			$layoutDir = CunddPath::getAbsoluteLayoutDir($tag);
			$layoutFile = CunddPath::getLayoutFile($tag);
			$layout->setLayoutPath($layoutDir);
			$layout->setLayout($layoutFile);
			
			
			$renderedLayout = $layout->render();
			$this->output = $renderedLayout;
			return $renderedLayout;
		} else {
			return (bool) false;
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode initialisiert den Editor eines CunddContent.
	 * @return false|false|string
	 */
	private function initEditor(){
		$say = true;
		
		$contentRecord = $this->getContentRecord();
		if($contentRecord){
			$right = CunddRechte::get($contentRecord);
		} else {
			$right = false;
		}
		/* DEBUGGEN */
		if($this->debug AND $say){
			echo '$right='.$right.' ';
			CunddTools::pd($contentRecord);
		}
		/* DEBUGGEN */
		
		if($right >= 6){ // Das Schreiben ist erlaubt
			// Die TinyMCE-Settings überschreiben
			$tinymceOverwriteSettings['save_onsavecallback'] = 'this.submit';
			$tinymce = new CunddTinyMCE($tinymceOverwriteSettings);
			
			/*
			// Wrap the TinyMCE-div around the CunddContent
			$tinyMceClass = CunddConfig::get('CunddTinyMCE_initForCSSClass');
			$wrapperBegin = '<div id="'.$this->contentId.'" class="'.$tinyMceClass.'">';
			$wrapperEnd = '</div>';
			
			//$tag = 'Cundd_Content_TinyMCE_JavaScript';
			//$this->output .= CunddTemplate::__($wert,$right,$tag,'special');
			$this->output = $wrapperBegin.$this->output.$wrapperEnd;
			
			/* */
			
			
			
			// Eine Instanz von CunddForm erstellen
			$fields = CunddFelder::get_eintrag();
			foreach($fields[0] as $key => $field){
				if(CunddConfig::get('content_show_'.$field)){
					$options = array('value' => $contentRecord[$field]);
					$inputs[] = array('name' => $field, 'type' => $fields[2][$key], 'options' => $options);
				}
			}
			
			// Die Content-ID versteckt mitschicken
			$inputs[] = array('name' => 'contentId', 'type' => 'hidden', 'options' => $this->contentId);
			
			$action = 'CunddContent::save';
			$formname = 'CunddContent';
			$class = $formname;
			
			$form = new CunddForm($inputs,$action,$formname,$right,$class);
			$formoutput = $form->getOutput();
			
			$this->output .= $formoutput;
			
		} else if($right >= 4){ // Wenn das Schreiben nicht erlaubt ist
			return (bool) false;
		} else {
			return (bool) false;
		}
		return $this->output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt einen neuen Content-Record.
	 * @return false|int
	 */
	private function createNewContentRecord(){
		if(!CunddLogin::isLoggedIn()) return (bool) false;
		
		if(gettype($this->search) == 'string'){
			$title = $this->search; 
		} else if(is_numeric($this->search)){
			$title = "CunddContent record created at $this->contentId";
			$schluessel = $this->search;
		} else {
			$title = "CunddContent record created at $this->contentId";
		}
		
		$data = array(
			'title' => $title,
			'eventdatum' => '0000-00-00',
			//'subtitle' => '',
			//'beschreibung' => '',
			'text' => "",
			'schluessel' => $schluessel,
		);
		
		$newId = CunddContent::insert($data);
		$this->contentId = $newId;
		return $newId;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Output aus.
	 * @return boolean|boolean
	 */
	public function render(){
		if($this->output){
			// Die Mailto-Links verschlüsseln
			$cunddLink_Mailto_Instance = new CunddLink_Mailto();
			$secure = $cunddLink_Mailto_Instance->createMailtoLinks($this->output); 
			echo $this->output;
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode verarbeitet die Daten beim Speichern eines Content.
	 * @param boolean $noOutput[optional]
	 * @return string|boolean
	 */
	public static function save($noOutput = false){
		// Updates speichern
		$data = $_POST;
		foreach($data as $key => $value){
			if(gettype($value) == 'string'){
				$data[$key] = stripslashes($value);
			}
		}
		$contentId = $data['contentId'];
		$result = CunddContent::update($data,$contentId);
		
		// Daten aktualisieren
		$content = new CunddContent($contentId,true,NULL,true);
		$record = $content->getContentRecord();
		
		
		$right = new CunddRight($record);
		
		// Statusnachricht ausgeben
		if($result){
			$tag = 'Cundd_Content_Save_Success';
			$output .= CunddTemplate::__($record,$right,$tag,'output');
		} else {
			$tag = 'Cundd_Content_Save_Error';
			$output .= CunddTemplate::__($record,$right,$tag,'output');
		}
		
		if(!$noOutput){
			echo $output;
			$content = new CunddContent($contentId);
			return $content->getOutput();
		} else {
			return (bool) false;
		}
		
		
	}
	/**
	 * @see save()
	 */
	public static function safe($noOutput = false){
		return self::save($noOutput);
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode bereitet die Suchanfrage vor.
	 * @param string $search
	 * @return mixed
	 */
	private function cleanUpSearch(&$search){
		if(gettype($search) == 'string'){
			// Clean up parameter
			$str_replace_para = array("\\'","'",'"');
			$search = str_replace($str_replace_para,'',$search);
		}
		return $search;
	}

}
?>