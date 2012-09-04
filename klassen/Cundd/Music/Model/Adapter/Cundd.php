<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Adapter_Cundd erweitert Cundd_Music_Model_Adapter_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 12, 2009
 * @author daniel
 */
class Cundd_Music_Model_Adapter_Cundd extends Cundd_Music_Model_Adapter_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $_debug = false;



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Konstruktor
	*/
	public function __construct($arguments = array()){
		$this->_requestParameters = $arguments;

		parent::__construct($arguments);
		// $this->pd($arguments);

		$this->_adapterMode = $arguments['mode'];

		$this->_result = $this->_get($arguments);

		//$this->pd($this->_result);
		return $this;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode erstellt die Tabelle zum Speichern der Daten im System. */
	public function createTable(){
		$db = new CunddDB(CunddConfig::__('Music/database_name'));

		$input = $this->_getAllowedParameters(true);
		$db->create($input,'id',true);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode speichert die Daten eines einmal aufgerufenen Release wenn sich dieser
	* noch nicht in der Datenbank befindet. */
	public function insertReleaseIfNotExists($data){
		// Die Tabelle erstellen wenn sie nicht existiert
		// $this->createTable();

		// Überprüfen ob der Eintrag existiert
		$getRecord = $this->_getRecordWithMbId($data['mbId']);

		if($getRecord == false AND $data){
			return $this->insert($data);
		} else {
			return $getRecord;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt einen Record anhand einer mbId.
	* @param string $mbId
	* @return false|array
	*/
	protected function _getRecordWithMbId($mbId){
		return $this->_get(array('mbId' => $mbId));
	}


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode sucht einen Eintrag in der Datenbank.
	 * @param array $arguments
	 * @param string $resource
	 * @param array $allowedParameters
	 * @return false|array(array(["mbId"],["origin"],["color01"],...,["color11"],["colorSpectrumPosition"],
	 * ["colorObject"],["mainColor"],["asin"],["id"],["artist"],["artistId"],["label"],["date"],["title"],
	 * ["catalogId"],["barcode"],["format"],["trackcount"]) [ , array(...) ] )
	 */
	protected function _get(array $arguments, $resource = NULL,array $allowedParameters = array()){
		$say = false;
		$what = '*'; //'COUNT(*)';
		$selectNotEmpty = false;
		if(Cundd_Request::getPara('limitCount')) $limitCount = 50;
		$limitOffset = 0;
		
		// $this->createTable();

		if(count($allowedParameters) == 0){
			$allowedParameters = $this->_getAllowedParameters();
		}

		$db = new CunddDB(CunddConfig::__('Music/database_name'));
		$adapter = $db->getAdapter();
		$select = $adapter->select();
		$selectPt2 = $select->from($db->getTable());


		foreach($arguments as $argumentKey => $argumentValue){
			// Überprüfen ob es sich um ein Datum handelt
			if($argumentKey == 'date' AND in_array($argumentKey,$allowedParameters) AND $argumentValue){
				$date = $argumentValue;
				if(strlen($date) == 4){ // 2008 -> Year given
					$where = "`date` LIKE '$date-%'";
					$select->where($where);
					$selectNotEmpty = true;
				} else if(strlen($date) == 10){ // 2008-10-03 -> whole date given
					// DATE(date)
					$where = "`date` >= '$date'";
					$select->where($where);
					$selectNotEmpty = true;
				} else {
					/* DEBUGGEN */if($say) echo 'Length of Date '.$date.' = '.strlen($date);;/* DEBUGGEN */
				}
			} else if(in_array($argumentKey,$allowedParameters) AND $argumentValue){
				$where = $argumentKey.' = ?';
				$select->where($where,$argumentValue);
				$selectNotEmpty = true;
			}
		}



		if($selectNotEmpty){
			// Add the limit
			if($limitCount) $select->limit($limitCount,$limitOffset);
			
			$stmt = $adapter->query($select);
			$result = $stmt->fetchAll();
		}
		
		$GLOBALS['search'] = $select->__toString();
		/* DEBUGGEN */
		if($say OR $this->_debug){
			$this->pd($arguments);
			$this->pd($select->__toString());
			$this->pd($result);
			echo 'Result='.$result;
		}
		/* DEBUGGEN */
		if(count($result) == 0 OR !$selectNotEmpty){
			return (bool) false;
		} else {
			return $result;
		}
	}
	/**
	 * @see _get()
	 */
	public function get(array $arguments, $resource = NULL,array $allowedParameters = array()){
		return $this->_get($arguments, $resource, $allowedParameters);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode speichert die Daten eines einmal aufgerufenen Release.
	* @param array $data
	* @return int|false
	*/
	private function insert(array $data){
		// Check if mbId ist not NULL
		if($data['mbId']){
			// Die Farben ermitteln
			$colors = array();
			if($data['asin']) $colors = $this->getColors($data['asin']);
				
			$insertData = array_merge($data,$colors);
			/*
			 $insertData['origin']	= $data['4_0___country'];
			 $insertData['asin']		= $data['asin'];
			 $insertData['mbId']		= $data['id'];
			 /* */
				
			$db = new CunddDB(CunddConfig::__('Music/database_name'));
			$adapter = $db->getAdapter();
				
			$result = $adapter->insert($db->getTable(), $insertData);
				
			return $adapter->lastInsertId();
		} else {
			return (bool) false;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt die Spalten der MySQL-Tabelle zurück. */
	private function _getAllowedParameters($installMode = false){
		$input = array(
		'mbId', 
		'origin',
		'color01',
		'color02',
		'color03',
		'color04',
		'color05',
		'color06',
		'color07',
		'color08',
		'color09',
		'color10',
		'color11',
		'colorSpectrumPosition',
		'colorObject',
		'mainColor',
		'asin',
		'id',

		'artist',
		'artistId',
		'label',
		'date',
		'title',
		'catalogId',
		'barcode',
		'format',
		'trackcount',
		);
		$installOptions = array(
		'VARCHAR(255)',
		'VARCHAR(255)',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'TINYINT',
		'VARCHAR(255)',
		'BLOB',
		'VARCHAR(255)',
		'VARCHAR(255)',
		'BIGINT',


		'VARCHAR(255)',
		'VARCHAR(255)',
		'VARCHAR(255)',
		'DATE',
		'VARCHAR(255)',
		'VARCHAR(255)',
		'VARCHAR(255)',
		'VARCHAR(255)',
		'INT',
		);

		if(!$installMode){
			return $input;
		} else {
			$return = array();
			$i = 0;
			while($para = $input[$i]){
				$return[$para] = $installOptions[$i];
				$i++;
			}
			return $return;
		}


	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt die Farben des Bildes.
	* @param string $asin
	* @return array
	*/
	public function getColors($asin){
		$color = Cundd::getModel('Music/Entity_Color');
		$baseUrl = CunddConfig::__('Music/image_base_url');
		$return = $color->newFromFile($baseUrl.$asin);

		// Serialize the $color-Object and save it as 'colorSpectrumPosition'
		// $return['colorSpectrumPosition'] = mysql_real_escape_string(htmlentities(serialize($color)));
		$return['colorObject'] = serialize($color);
		$mainColorArray = $color->getMostCountedColorField();
		$return['mainColor'] = $mainColorArray['fieldName'];

		return $return;
	}
}
?>