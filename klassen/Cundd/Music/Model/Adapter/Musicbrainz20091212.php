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
	protected $_adapterMode = '';
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

	private $debug = true;


	// http://php.net/manual/en/function.sleep.php SLEEP = Wait

	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Konstruktor
	*/
	public function __construct($arguments = array()){
		$this->_requestParameters = $arguments;

		$this->_adapterMode = $arguments['mode'];

		$this->_result = $this->_get($arguments);
		return $this;




		$methodName = (string) '_get'.ucfirst($this->_adapterMode);
		if(method_exists($this, $methodName)){
			$temp = $this->$methodName($arguments);
		} else {
			/* DEBUGGEN */if($this->debug) die("Method $methodName not found.<br />");/* DEBUGGEN */
			return false;
		}

		// $this->pd($temp);
		$this->_result = $temp;
		return $this;
		/* */
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt einen Artist mittels des übergebenen Namens. */
	private function _getArtist($arguments){
		$artists = $this->_getArtists($arguments);
		return $artists[0];
	}
	private function _getArtistWithName($arguments){
		return $this->_getArtist($arguments);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt alle Artists passend zum übergebenen Namens. */
	private function _getArtists($arguments){
		$name = $arguments['name'];
		//$mbid = '320e6f9c-a990-4e7c-bc9c-807d30519536';
		$requestUrl = $this->_baseUrl.'artist/?type=xml';
		$requestUrl = $this->_baseUrl.'artist/';
		/*
		 $requestUrl = $this->_baseUrl.'release/?type=xml';
		 $requestUrl = $this->_baseUrl."release/$mbid/?type=xml";
		 // $requestUrl = $this->_baseUrl.'release/MBID/?type=xml';

		 /* */

		$client = new Zend_Rest_Client($requestUrl);
		$client->type('xml');
		$client->name($this->_cleanUpParameter($name));
		//$client->artist($this->_cleanUpParameter($name));


		$result = $client->get();



		// SPEICHERN DER DATEN
		$col = $result->__get('artist-list');
		$attributes = $col->attributes();


		$this->_resultObject = &$result;
		$this->_resultCount = $attributes['count']*1;


		// Die Artists speichern
		$artists = array();
		if($this->_resultCount > 1){
			$i = 0;
			foreach($col->artist as $artist){
				$artists[$i] = $col->artist[$i];
				$i++;
			}
		} else {
			$artists[0] = $col->artist;
		}
		/* */


		/*
		 if(gettype($artists) == 'array'){
			$artistsResult = $artists;
			} else {
			$artistsResult = clone $artists;
			}
			/* */
		$artistsResult = array();
		//$this->simpleXmlToArray($artists,$artistsResult);

		foreach($artists as $key => $artist){ // Für jeden Artist
			foreach($artist->children() as $dataName => $artistData) { // Für jeden Node
				$artistsResult[$key][$dataName] = (string) $artistData;

				if($artistData instanceof SimpleXMLElement){ // Wenn es ein Node mit Attributen ist
					foreach($artistData->attributes() as $attribute => $value){
						$artistsResult[$key][$attribute] = (string) $value;
						$artistsResult[$key][$dataName.'_'.$attribute] = (string) $value;
					}
				}
			}
			 
			$artistAttributes = $artist->attributes();
			$artistsResult[$key]['id'] = (string) $artistAttributes['id'];
			// $id = (string) $artistAttributes['id'];
			// $artist->addChild('id', $id);
				
			$artistsResult[$key]['type'] = (string) $artistAttributes['type'];
			// $type = (string) $artistAttributes['type'];
			// $artist->addChild('type',$type);
		}
		/* */

		// $this->pd($artists);
		// echo 'f'.gettype($artists[0]).'l';
		return $artistsResult;
	}
	private function _getArtistsWithName($arguments){
		return $this->_getArtists($arguments);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt alle Alben passend zu den übergebenen Parametern. */
	private function _getAlbums($arguments){
		return $this->_get($arguments,'release');
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt das erste Album passend zu den übergebenen Parametern. */
	private function _getAlbum($arguments){
		$result = $this->_getAlbums($arguments);
		return $result[0];
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt alle Tracks passend zu den übergebenen Parametern. */
	private function _getTracks($arguments){
		return $this->_get($arguments,'track');
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt den ersten Track passend zu den übergebenen Parametern. */
	private function _getTrack($arguments){
		$result = $this->_getTracks($arguments);
		return $result[0];
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt das erste Label passend zu den übergebenen Parametern. */
	private function _getLabel($arguments){
		$result = $this->_getLabels($arguments);
		return $result[0];
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode ermittelt alle Label passend zu den übergebenen Parametern. */
	private function _getLabels($arguments){
		return $this->_get($arguments,'label');
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode bietet ein allgemeines Interface für die Abfrage und das Parsen der
	* Daten. */
	private function _get(array $arguments, $resource = NULL,array $overwriteAllowedParameters = array()){
		if(!$resource) $resource = $this->_adapterMode;

		if(count($overwriteAllowedParameters) == 0){
			$allowedParameters = $_allowedParametersCol[$resource];
		} else {
			$allowedParameters = $overwriteAllowedParameters;
		}

		$requestUrl = $this->_baseUrl.$resource.'/';
		$client = new Zend_Rest_Client($requestUrl);
		$client->type('xml');
		$this->_setArguments($arguments,$client,$allowedParameters);


		$result = $client->get();
		$col = $result->__get($resource.'-list');
		$attributes = $col->attributes();

		$this->_resultObject = &$result;
		$this->_resultCount = (int) $attributes['count'];

		$returnResult = array();
		$this->_simpleXmlToFlatArray($col,$returnResult);

		//$this->pd($returnResult);

		return $returnResult;
	}
}
?>