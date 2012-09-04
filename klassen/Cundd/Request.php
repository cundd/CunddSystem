<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Request erweitert Cundd_Core_Singleton.
 * @package Cundd_Core_Singleton
 * @version 1.0
 * @since Feb 1, 2010
 * @author daniel
 */
class Cundd_Request extends Cundd_Core_Singleton {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Variablen deklarieren
    public $para = array();
    public $url = '';
    public $module = '';
    public $controller = '';
    public $action = '';
    public $site = '';
    public $extraParameters = array();

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     *
     */
    protected function _construct(array $arguments = array()) {
	if (!$this->wasLoaded()) {
	    $this->_init();
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode initialisiert das Singleton-Objekt. */
    protected function _init() {
//		REDIRECT_QUERY_STRING	jkl=adsf
//			REDIRECT_URL	/vbc/HUL:l/dsf
//			GATEWAY_INTERFACE	CGI/1.1
//			SERVER_PROTOCOL	HTTP/1.1
//			REQUEST_METHOD	GET
//			QUERY_STRING	no value
//			REQUEST_URI	/vbc/HUL:l/dsf?jkl=adsf
//			SCRIPT_NAME	/vbc/index.php
	$this->_parsePara();
	$this->_parseExtraData();
	$this->_parseUrl();
	return;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode parsed ggfl. den Request und gibt ein Array zurück, das $_POST und $_GET
     * vereint. */
    protected function _parsePara() {
	if (array_key_exists('REDIRECT_STATUS', $_SERVER)) {
	    if (!$_GET)
		$_GET = array();
	    if ($_SERVER['REDIRECT_STATUS'] == '404') { // Die Anfrage wurde mittels einem ErrorDocument umgeleitet
		parse_str($_SERVER['REDIRECT_QUERY_STRING'], $_GET);
	    } else if ($_SERVER['REDIRECT_STATUS'] == '200') { // Die Anfrage wurde mittels ModRewrite umgeleitet
	    }
	}

	// $this->para = array_merge($_POST,$_GET);
	$this->para = $_POST;
	foreach ($_GET as $key => $value) {
	    $this->para[$key] = $value;
	}


	return $this->para;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * The key "data" is frequently used to store extra data on Ajax requests. The method
     * checks if a value for "data" exists and if it contains "=" and/or "&" characters.
     *
     */
    protected function _parseExtraData() {
	if (array_key_exists('data', $this->para)) {
	    if (strpos($this->para['data'], '&') || strpos($this->para['data'], '=')) {
		parse_str($this->para['data'], $this->extraParameters);
	    }
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode parsed die Url. */
    protected function _parseUrl() {
	// REDIRECT_URL	/vbc/HUL:l/dsf
	if (array_key_exists('REDIRECT_URL', $_SERVER)) {
	    $requestUri = $_SERVER["REDIRECT_URL"]; // /Module/Controller/Action/
	    $moduleControllerAction = str_replace(CunddConfig::__('BasePath'), '', $requestUri); // Delete the base-path from the request
	}
	$this->url = $moduleControllerAction;


	// TODO Überprüfen ob ein passendes Modul besteht, bzw. die Seite aus dem CMS aufrufen

	return $this->url;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //
    // STATIC ACCESSORS
    //
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt die URL zurück.
     * @return string
     */
    public static function getUrl() {
	$instance = self::getInstance();
	return $instance->url;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt alle Parameter zurück wenn kein Argument übergeben wurde, ansonsten
     * den Parameter mit dem Key $name.
     * @param string $name[optional]
     * @return mixed
     */
    public static function getPara($name = NULL) {
	$return = NULL;
	$instance = self::getInstance();
	$para = $instance->para;
	$extraPara = $instance->extraParameters;

	/* Wenn $name gesetzt ist überprüfen ob ein Parameter mit dem Key $name existiert,
	 * dann entweder diesen, oder NULL zurückgeben.
	 * Wenn $name nicht gesetzt ist wird das gesamte Parameter-Array zurückgegeben.
	 */
	if ($name !== NULL) {
	    if (array_key_exists($name, $para)) {
		$return = $para[$name];
	    } else if (array_key_exists($name, $extraPara)) {
		$return = $extraPara[$name];
	    }
	} else {
	    $return = array_merge($para,$extraPara);
	}

	return $return;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt die Parameter zurück.
     * @return array
     */
    public static function getAllParameters() {
	$instance = self::getInstance();
	return array_merge($instance->para, $instance->extraParameters);
    }

    /**
     * Die Methode gibt die Parameter zurück.
     * @return array
     */
    public static function getParameters() {
	return self::getAllParameters();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt eine Instanz zurück.
     * @return Cundd_Request
     */
    public static function getInstance() {
	return new Cundd_Request();
    }

}

?>