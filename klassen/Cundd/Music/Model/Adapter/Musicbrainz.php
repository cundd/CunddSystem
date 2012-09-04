<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Adapter_Musicbrainz erweitert Cundd_Music_Model_Adapter_Abstract.
 * Sie stellt das Interface zum Stellen von Anfragen an den Service von Musicbrainz dar.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 9, 2009
 * @author daniel
 */
class Cundd_Music_Model_Adapter_Musicbrainz extends Cundd_Music_Model_Adapter_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_baseUrl = 'http://musicbrainz.org/ws/1/';
	protected $_limit = 50;//'100';
	protected $_allowedParametersCol = array(
	'album' => array(
		'title',
		'discid',
		'artist',
		'artistid',
		'releasetypes',
		'count',
		'date',
		'asin',
		'lang',
		'script',
		'cdstubs',
		'limit',
	),
	'release' => array(
		'title',
		'discid',
		'artist',
		'artistid',
		'releasetypes',
		'count',
		'date',
		'asin',
		'lang',
		'script',
		'cdstubs',
		'limit',
	),
	'track' => array(
		'title',
		'artist',
		'release',
		'duration',
		'tracknumber',
		'artistid',
		'releaseid',
		'puid',
		'count',
		'releasetype',
		'limit',
	),
	'artist' => array(
		'name',
		'limit',
	),
	'label' => array(
		'name',
		'limit',
	),
	);

	private $debug = false;


	// http://php.net/manual/en/function.sleep.php SLEEP = Wait

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
		return $this;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode bietet ein allgemeines Interface für die Abfrage und das Parsen der
	* Daten.
	* @param array $arguments
	* @param string $resource
	* @param array $overwriteAllowedParameters
	* @return array(array(["asin"],["id"],["artist"],["artistId"],["label"],["date"],["title"],
	* ["catalogId"],["barcode"],["format"],["trackcount"]) [ , array(...) ] ):
	*/
	private function _get(array $arguments, $resource = NULL,array $overwriteAllowedParameters = array()){
		if(!$resource) $resource = $this->_getAdapterMode();

		if(count($overwriteAllowedParameters) == 0){
			$allowedParameters = $this->_allowedParametersCol[$resource];
		} else {
			$allowedParameters = $overwriteAllowedParameters;
		}
		
		// Überprüfen ob ein Parameter übergeben wurde; wenn nicht wird NULL zurückgegeben
		$parameterInBothArrays = array_intersect(array_keys($_GET),$allowedParameters);
		if(count($parameterInBothArrays) == 0){
			return null;
		}

		$requestUrl = $this->_baseUrl.$resource.'/';
		$client = new Zend_Rest_Client($requestUrl);
		$client->type('xml');
		if($this->_limit) $client->limit($this->_limit);
		$this->_setArguments($arguments,$client,$allowedParameters);


		$result = $client->get();
		$col = $result->__get($resource.'-list');

		// Überprüfen ob ein Ergebnis geliefer wurde
		if($col == NULL){
			return false;
		}
		$attributes = $col->attributes();

		// $this->pd($col);

		$this->_resultObject = &$result;
		$this->_resultCount = (int) $attributes['count'];

		$returnResult = array();
		$this->_simpleXmlToFlatArray($col,$returnResult);


		/* DEBUGGEN */if($this->debug) $this->pd($returnResult);/* DEBUGGEN */


		$loops = count($returnResult);
		for($i=0;$i < $loops;$i++){
			if(gettype($returnResult[$i]) == 'array') $returnResult[$i] = $this->_parseFromSource($returnResult[$i]);
		}


		// $this->pd($returnResult);

		return $returnResult;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode mappt die Werte der Source auf ein neues Objekt. */
	protected function _parseFromSource($source){
		$return = array();

		$mapping = $this->_getSourceMapping($this->_adapterMode);
		foreach($mapping as $sourceKey => $propertyName){
			$return[$propertyName] = '';
			$this->_setIfKeyExists($sourceKey,$source,$return[$propertyName]);
		}
		return $return;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt den Adapter-Mode zurück und setzt ihn gegebenenfalls bzw. wirft eine
	* Exception. */
	protected function _getAdapterMode(){
		if(!$this->_adapterMode){
			throw new Exception("No adapter-mode specified.");
		}
		return $this->_adapterMode;
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt das Mapping der Source-Arrays und der Eigenschaften zurück.
	* @param string $adapterMode
	* @return Ambigous <multitype:string >|multitype:multitype:string
	*/
	private function _getSourceMapping($adapterMode = NULL){
		$mappings = array(
			'release' => array(
				'3_name' 				=> 'artist',
				'artist_id'				=> 'artistId',
				'title' 				=> 'album',
				'4_0_0_name'		 	=> 'label',
				'4_0___country' 		=> 'origin',
				'4_0__date' 			=> 'date',
				'title' 				=> 'title',
				'4_0__catalog-number' 	=> 'catalogId',
				'4_0__barcode' 			=> 'barcode',
				'4_0__format' 			=> 'format',
				'id' 					=> 'mbId',
				'track-list_count' 		=> 'trackcount',
				'asin' 					=> 'asin',
		),
			'artist' => array(
				'name' 					=> 'artist',
				'name' 					=> 'name',
				'id' 					=> 'mbId',
				'life-span_begin'		=> 'lifeSpanBegin',
				'life-span_end' 		=> 'lifeSpanEnd',
				'type' 					=> 'type',
		),
			'label' => array(
				'3_name' 				=> 'artist',
				'title' 				=> 'album',
				'4_0_0_name'		 	=> 'label',
				'4_0___country' 		=> 'origin',
				'track-list_count' 		=> 'trackcount',
				'4_0__date' 			=> 'date',
				'title' 				=> 'title',
				'4_0__catalog-number' 	=> 'catalogId',
				'4_0__barcode' 			=> 'barcode',
				'4_0__format' 			=> 'format',
				'id' 					=> 'mbId',
		),
			'track' => array(
				'title' 				=> 'track',
				'3_0_title' 			=> 'album',
				'2_name'	 			=> 'artist',
				'3_0_track-list_count' 	=> 'trackcount',
				'id' 					=> 'mbId',
				'3_0_track-list_offset' => 'trackListOffset',
		),
			
		);
		$mappings['album'] = $mappings['release'];

		if($adapterMode){
			return $mappings[$adapterMode];
		} else {
			return $mappings;
		}
	}
}
?>