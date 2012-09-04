<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * Die Klasse "CunddRequest" verwaltet die Aktionen bei einer Umgeleiteten URL.
 * @deprecated
 */
class CunddRequest {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Variablen deklarieren
    static $redirect_query_string;
    static $redirect_url;
    static $gateway_interface;
    static $server_protocol;
    static $request_method;
    static $query_string;
    static $request_uri;
    static $script_name;
    static $para = NULL;
    static $action = NULL;
    private static $debug = 0;
    private static $hasInstance = false;

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Konstruktor
    public function __construct() {
	return $this->init();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Konstruktor
    public function init() {
	if (!self::$hasInstance) {
	    self::safeStrings();
	    self::parseRedirect_query_string();

	    // Überprüfen ob die URL umgeleitet wurde
	    if (self::checkIfRedirected()) {
		self::parseRedirect_url();
		


		self::$hasInstance = true;
	    } else {
		self::$hasInstance = true;
	    }
	}

	return self::$hasInstance;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode speichert die Werte der Server-Variablen.
     * @deprecated
     */
    private static function safeStrings() {
	self::$redirect_query_string = $_SERVER["REDIRECT_QUERY_STRING"];
	self::$redirect_url = $_SERVER["REDIRECT_URL"];
	self::$gateway_interface = $_SERVER["GATEWAY_INTERFACE"];
	self::$server_protocol = $_SERVER["SERVER_PROTOCOL"];
	self::$request_method = $_SERVER["REQUEST_METHOD"];
	self::$query_string = $_SERVER["QUERY_STRING"];
	self::$request_uri = $_SERVER["REQUEST_URI"];
	self::$script_name = $_SERVER["SCRIPT_NAME"];
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob die URL umgeleitet wurde.
     * @deprecated
     */
    private static function checkIfRedirected() {
	if (!self::$query_string AND self::$redirect_url) {
	    return true;
	} else {
	    return false;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode löst die umgeleitete URL in die einzelnen Variablen auf.
     * @deprecated
     */
    private static function parseRedirect_query_string() {
	$rqs = self::$redirect_query_string;

	$paraTemp = explode('&', $rqs);

	foreach ($paraTemp as $paraKeyValuePair) {
	    $paraKeyValuePair = explode('=', $paraKeyValuePair);
	    $para[$paraKeyValuePair[0]] = $paraKeyValuePair[1];
	}

	self::$para = $para;

	/*
	 * 	REDIRECT_QUERY_STRING	jkl=adsf
	  REDIRECT_URL	/vbc/HUL:l/dsf
	  GATEWAY_INTERFACE	CGI/1.1
	  SERVER_PROTOCOL	HTTP/1.1
	  REQUEST_METHOD	GET
	  QUERY_STRING	no value
	  REQUEST_URI	/vbc/HUL:l/dsf?jkl=adsf
	  SCRIPT_NAME	/vbc/index.php
	 */
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode löst die umgeleitete URL in die einzelnen Variablen auf.
     * @deprecated
     */
    private static function parseRedirect_url() {
	$rqs = self::$redirect_url;


	$basePath = CunddConfig::get("BasePath");

	$rqsClean = str_replace($basePath, '', $rqs);


	$paraTemp = explode('/', $rqsClean);
	$cleanParaTemp = array();
	foreach ($paraTemp as $currentParaTemp) {
	    if ($currentParaTemp != '') {
		$cleanParaTemp = $currentParaTemp;
	    }
	}
	$paraTemp = $cleanParaTemp;


	// Die Action ermitteln
	if (gettype($paraTemp) == 'array') {
	    self::$action = $paraTemp[0];
	} else if (gettype($paraTemp) == 'string') {
	    self::$action = $paraTemp;
	}


	for ($i = 1; $i < count($paraTemp); $i = $i + 2) {
	    $para[$paraTemp[$i]] = $paraTemp[$i + 1];
	}


	if ($para) {
	    if (self::$para) {
		self::$para = array_merge($para, self::$para);
	    } else {
		self::$para = $para;
	    }
	}


	// DEBUGGEN
	if (self::$debug) {
	    CunddTools::pd($paraTemp);
	    echo '<pre>';
	    var_dump($para);
	    echo '</pre>';
	}
	// DEBUGGEN

	/*
	 * 	REDIRECT_QUERY_STRING	jkl=adsf
	  REDIRECT_URL	/vbc/HUL:l/dsf
	  GATEWAY_INTERFACE	CGI/1.1
	  SERVER_PROTOCOL	HTTP/1.1
	  REQUEST_METHOD	GET
	  QUERY_STRING	no value
	  REQUEST_URI	/vbc/HUL:l/dsf?jkl=adsf
	  SCRIPT_NAME	/vbc/index.php
	 */
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt $para zurück.
     * @return array
     * @deprecated
     */
    public static function getPara() {
	if(!self::$para){
	    self::init();
	}
	return self::$para;
	/*
	  if(self::$para){
	  return self::$para;
	  } else if(self::$action){
	  return NULL;
	  } else {
	  return CunddRequest::init();
	  }

	  /* */
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt $action zurück.
     * @return string
     * @deprecated
     */
    public static function getAction() {
	if (self::$action == NULL) {
	    new CunddRequest();
	}
	return self::$action;
	/*
	  if(self::$action){
	  return self::$action;
	  } else {
	  $temp = CunddRequest::init();
	  return CunddRequest::getAction();
	  }
	  /* */
    }

}

?>