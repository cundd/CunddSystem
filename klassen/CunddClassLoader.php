<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * Die Klasse bietet eine Übergangslösung zum Laden von benötigten Klassen-Dateien.
 */
class CunddClassLoader {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //Variablen deklarieren
    public static $say = false;
    private static $_includePathSet = false;
    protected static $_mode = 0;
    protected static $_didLoadCunddPathClass = false;
    protected static $_callCounter = 0;
    protected static $_absoluteGlobalClassDir = NULL;
    const CUNDD_CLASS_LOADER_MODE_NONE = -1;
    const CUNDD_CLASS_LOADER_MODE_GLOBAL_ONLY = 1;
    const CUNDD_CLASS_LOADER_MODE_LOCAL_ONLY = 2;
    const CUNDD_CLASS_LOADER_MODE_BOTH = 3;

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob eine Klasse mit dem übergebenen Namen existiert und versucht
     * sonst sie zu laden.
     * @param string $className
     * @param string $classFileSuffix (optional)
     * @return bool
     */
    public static function checkIfClassExistsElseLoad($className, $classFileSuffix = '.php') {
	if (!self::checkIfClassExists($className)) {
	    $result = true;

	    if (!self::$_didLoadCunddPathClass)
		self::loadCunddPathClass();
//	    $result = self::addCunddClassPathToIncludePath();


	    $classPath = self::parseClassname($className, $classFileSuffix);
	    $result = self::loadClassFile($classPath);

	    if (!$result) {
		$currentIncludePath = get_include_path();
		$errorMsg = "<pre>Class $className couldn't be loaded. Supposed path is $classPath.\nThe include path is $currentIncludePath.</pre>";
		die($errorMsg);
		if (class_exists('CunddTools'))
		    CunddTools::error('CunddClassLoader', $errorMsg);
		/* DEBUGGEN */if (self::$say)
		    echo $errorMsg; /* DEBUGGEN */

		return $result;
	    } else {
		return $result;
	    }
	} else {
	    return true;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob eine Klasse mit dem übergebenen Namen existiert.
     * @param string $className
     * @return boolean
     */
    public static function checkIfClassExists($className) {
	return class_exists($className);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den in die Ordnerstruktur zerlegten Klassennamen zurück.
     * @param string $className
     * @param string $classFileSuffix
     * @return string
     */
    public static function parseClassname($className, $classFileSuffix = '.php') {
	return str_replace('_', '/', $className) . $classFileSuffix;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     *  Die Methode fügt das Cundd-Klassen-Verzeichnis zum include-Path hinzu.
     *
     * @return bool
     */
    public static function addCunddClassPathToIncludePath() {
	if (!self::$_includePathSet && self::mode() > self::CUNDD_CLASS_LOADER_MODE_GLOBAL_ONLY) {
	    $oldPath = get_include_path();

	    $classDir = self::getAbsoluteClassDir();

	    set_include_path(get_include_path() . PATH_SEPARATOR . $classDir);

	    self::$_includePathSet = true;

	    if ($oldPath == get_include_path()) {
		return (bool) false;
	    } else {
		return (bool) true;
	    }
	} else {
	    return (bool) true;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode lädt die benötigte Klassen-Datei.
     * @param string $relClassPath
     * @return boolean
     */
    public static function loadClassFile($relClassPath) {
	$say = false;
	$profile = false;

	$startTime = (float) 0;
	$callCounterL = 0;
	$path = '';

	$zendDir = CunddConfig::__('Zend_absolute_dir');
	$loaded = false;

	if ($profile) {
	    $startTime = microtime(true);
	    $callCounterL = self::$_callCounter++;

	    echo "<pre>Began call $callCounterL loadClassFile() at: " . date('H:i:s') . ' ' . microtime() . '</pre>';
	}

	// DEBUGGEN
	if ($say) {
	    echo get_include_path() . "<br />";
	    echo "RelPath: $relClassPath<br />";
	    echo 'ZendPath: ' . $zendDir . $relClassPath . "\n";
	}
	// DEBUGGEN

//	if (strpos($relClassPath, 'Zend') !== false) {
//	    require_once($relClassPath);
//	    $loaded = true;
//	} else if (file_exists($classDir . $relClassPath) && self::mode() > self::CUNDD_CLASS_LOADER_MODE_GLOBAL_ONLY) { // try to load the local class file
//	    require_once($classDir . $relClassPath);
//	    $loaded = true;
//	} else if (CunddPath::) { // else try to load the global class file
//	    // did load the global class file
//	    $loaded = true;
//	} else {
//	    $msg = "Class-file $classDir$relClassPath couldn't be found.";
//
//	    throw new Exception($msg);
//	    $loaded = false;
//	}
	if (strpos($relClassPath, 'Zend') !== false) {
	    require_once($relClassPath);
	    $loaded = true;
	} else {
	    $path = CunddPath::getAbsoluteFilePath(CunddPath::getClassDirName().$relClassPath);
	    if($path){
		require_once($path);
		$loaded = true;
	    }
	}

	if(!$loaded){
	    $msg = "Class-file $path for class $relClassPath couldn't be found.";

	    throw new Exception($msg);
	    $loaded = false;
	}


	if ($profile) {
	    echo "<pre>Ended call $callCounterL loadClassFile() at: " . date('H:i:s') . ' ' . microtime() . ' diff=' . (microtime(true) - $startTime) . "\n\n</pre>";
	}

	return (bool) $loaded;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den Pfad zum Klassen-Verzeichnis zurück.
     */
    private static function getAbsoluteClassDir() {
	if (!class_exists('CunddPath', false)) {
	    echo '<pre>';
	    throw new Exception('Class CunddPath not loaded yet.');
	    echo '</pre>';
	}

	return CunddPath::getAbsoluteClassDir();
    }


    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the path to the global class directory. The value is tested and called through the inclusion of the file
     * CunddClassLoaderPathChecker the mode()-method.
     * @return string
     */
    public static function getAbsoluteGlobalClassDir(){
	if(self::$_absoluteGlobalClassDir == NULL){
	    self::mode();
	}
	return self::$_absoluteGlobalClassDir;
    }
    

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Loads the class CunddPath.
     */
    public static function loadCunddPathClass() {
	if (!self::$_didLoadCunddPathClass) {
	    $absoluteLocalClassDir = CunddSystem::getDir() . '/' . CunddConfig::get('CunddBasePath') . CunddConfig::__('Cundd_class_path');
	    $relClassPath = 'CunddPath.php';

	    if (file_exists($absoluteLocalClassDir . $relClassPath)) { // try to load the local class file
		require_once($absoluteLocalClassDir . $relClassPath);
		$loaded = true;
	    } else if (strpos($relClassPath, 'Zend') !== false) {
		require_once($relClassPath);
		$loaded = true;
	    } else if ((include_once $relClassPath)) { // else try to load the global class file
		// did load the global class file
	    } else {
		$msg = "Class-file $absoluteLocalClassDir$relClassPath couldn't be found.";

		throw new Exception($msg);
		$loaded = false;
	    }
	    self::$_didLoadCunddPathClass = true;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the mode of the class loader as a constant. The constants indicate if the system has class files stored only local,
     * only global or both. On error CUNDD_CLASS_LOADER_MODE_NONE is returned.
     * @return CUNDD_CLASS_LOADER_MODE_BOTH|CUNDD_CLASS_LOADER_MODE_LOCAL_ONLY|CUNDD_CLASS_LOADER_MODE_GLOBAL_ONLY|CUNDD_CLASS_LOADER_MODE_NONE
     */
    public static function mode() {
	if (self::$_mode == 0) {
	    $relClassPath = 'CunddClassLoaderPathChecker.php';
	    $say = false;
	    $classDir = self::getAbsoluteClassDir();


	    $local = false;
	    $global = false;

	    if (file_exists($classDir . $relClassPath)) { // check for the local file
		$local = true;
	    }
	    self::$_absoluteGlobalClassDir = @(include $relClassPath);
	    if (self::$_absoluteGlobalClassDir) { // try to load the global class file
		self::$_absoluteGlobalClassDir .= '/';
		$global = true;
	    }

	    if ($local && $global) {
		self::$_mode = self::CUNDD_CLASS_LOADER_MODE_BOTH;
	    } else if ($local) {
		self::$_mode = self::CUNDD_CLASS_LOADER_MODE_LOCAL_ONLY;
	    } else if ($global) {
		self::$_mode = self::CUNDD_CLASS_LOADER_MODE_GLOBAL_ONLY;
	    } else {
		self::$_mode = self::CUNDD_CLASS_LOADER_MODE_NONE;
	    }
	}
	return self::$_mode;
    }



    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //
    // MODEL
    //
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode liefert eine Instanz des übergebenen Models im relevanten Namespace.
     * @param string $model Modul/Model
     * @param array $options
     * @return Model
     */
    public static function getModel($model, array $options = array()) {
	$modelname = self::getModelName($model);

	/*
	  $temp =& new $modelname();
	  $temp =& $temp->__construct($options);
	  /* */

	//$temp =& new $modelname($options);
	$temp = new $modelname($options);
	return $temp;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den gesamten Namen eines Models zurück (inkl. Namespace).
     * @param string $model
     * @return string
     */
    public static function getModelName($model) {
	$relevantNamespace = 'Cundd';
	$modelDir = str_replace('/', '_', CunddConfig::__('Cundd_model_dir'));
	$modelname = $relevantNamespace . '_' . str_replace('/', '_' . $modelDir . '', $model);

	/* Überprüfen ob das "Standard"-Model geladen werden soll:
	 * (Der Name des Standardmodels lautet wie das Modul)
	 */
	if (!preg_match('!/!', $model)) {
	    $modelname = $modelname . '_' . $modelDir . $model;
	}

	return $modelname;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode ermittelt den Modul-Namen des übergebenen Klassennamen.
     * @param string $name
     * @return string
     */
    public static function getModuleFromClassName($className) {
	$nameParts = explode('_', $className);
	return $nameParts[1];
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode ermittelt den Namespace des übergebenen Klassennamen.
     * @param string $name
     * @return string
     */
    public static function getNamespaceFromClassName($className) {
	$nameParts = explode('_', $className);
	return $nameParts[0];
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode liefert eine Instanz des übergebenen Models im relevanten Namespace.
     * @param string $model Modul/Model
     * @param array $options
     * @return Model
     */
    public static function getCollection($model, array $options = array()) {
	$modelname = self::getCollectionName($model);

	/*
	  $temp =& new $modelname();
	  $temp =& $temp->__construct($options);
	  /* */

	//$temp = new $modelname($options);
	$temp = new $modelname($options);
	return $temp;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den gesamten Namen eines Models zurück (inkl. Namespace).
     * @param string $model
     * @return string
     */
    public static function getCollectionName($model) {
	$relevantNamespace = 'Cundd';
	$modelDir = str_replace('/', '_', CunddConfig::__('Cundd_model_dir'));
	$modelname = $relevantNamespace . '_' . str_replace('/', '_' . $modelDir . 'Collection_', $model);

	/* Überprüfen ob das "Standard"-Model geladen werden soll:
	 * (Der Name des Standardmodels lautet wie das Modul)
	 */
	if (!preg_match('!/!', $model)) {
	    $modelname = $modelname . '_' . $modelDir . 'Collection_' . $model;
	}

	return $modelname;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //
    // VIEW
    //
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode liefert eine Instanz der übergebenen View im relevanten Namespace.
     * @param string $view Module/View
     * @param array $options
     * @return Cundd_Core_View_Abstract
     */
    public static function getView($view, array $options = array()) {
	$viewName = self::getViewName($view);

	/*
	  $temp =& new $modelname();
	  $temp =& $temp->__construct($options);
	  /* */

	// $temp =& new $viewName($options);
	return new $viewName($options);
	return $temp;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den gesamten Namen einer View zurück (inkl. Namespace).
     * @param string $view
     * @return string
     */
    public static function getViewName($view) {
	$relevantNamespace = 'Cundd';
	$viewDir = str_replace('/', '_', CunddConfig::__('Cundd_view_dir'));
	$viewname = $relevantNamespace . '_' . str_replace('/', '_' . $viewDir . '', $view);

	/* Überprüfen ob die "Standard"-View geladen werden soll:
	 * (Der Name der Standardview lautet wie das Modul)
	 */
	if (!preg_match('!/!', $view)) {
	    $viewname = $viewname . '_' . $viewDir . $view;
	}

	return $viewname;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //
    // SINGLETON
    //
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode liefert eine Referenz auf das Singleton-Objekt.
     * http://en.wikipedia.org/wiki/Singleton_pattern#PHP
     * @param string $model
     * @param array $options
     * @return Model
     */
    public static function getSingleton($model, $options = array()) {
	return CunddClassLoader::getModel($model, $options);
	$instance = & self::getModel($model, $options);

	if ($instance->checkIfSingleton()) {
	    return $instance;
	} else {
	    throw new Exception('Required model is not a singleton.');
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //
    // CONTROLLER
    //
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
     * und gibt den Namen des Controllers zurück.
     * @param string $controllerDescription Module/Controller::Action
     * @param array $options
     * @param string $controllerFileSuffix
     * @return string
     */
    public function getController($controllerDescription, array $options = array(), $controllerFileSuffix = '.php') {
	$returnArray = self::getControllerAndAction($controllerDescription, $options, $controllerFileSuffix);
	return $returnArray['controller'];
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
     * und gibt die Action zurück.
     * @param string $controllerDescription Module/Controller::Action
     * @param array $options
     * @param string $controllerFileSuffix
     * @return string
     */
    public function getControllerAction($controllerDescription, array $options = array(), $controllerFileSuffix = '.php') {
	$returnArray = self::getControllerAndAction($controllerDescription, $options, $controllerFileSuffix);
	return $returnArray['action'];
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
     * und gibt diese in einem assoziativen Array zurück.
     * @param string $controllerDescription Module/Controller::Action|Module/Controller/Action
     * @param array $options
     * @param string $controllerFileSuffix
     * @return array('module' => $module, 'controller' => $controller, 'action' => $action, 'controllerClass' => $controllerName, 'absControllerClassPath' => $absControllerPath)
     */
    public function getControllerAndAction($controllerDescription, array $options = array(), $controllerFileSuffix = '.php', $forceAppMode = false) {
	if (Cundd::getSystemMode() == Cundd::CUNDD_SYSTEM_MODE_APP OR $forceAppMode) {
	    return self::_getControllerAndActionInAppMode($controllerDescription, $options, $controllerFileSuffix);
	} else if (Cundd::getSystemMode() == Cundd::CUNDD_SYSTEM_MODE_WEB) {
	    return self::_getControllerAndActionInWebMode($controllerDescription, $options, $controllerFileSuffix);
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
     * und gibt diese in einem assoziativen Array zurück.
     * @param string $controllerDescription Module/Controller::Action|Module/Controller/Action
     * @param array $options
     * @param string $controllerFileSuffix
     * @return array('module' => $module, 'controller' => $controller, 'action' => $action, 'controllerClass' => $controllerName, 'absControllerClassPath' => $absControllerPath)
     */
    protected static function _getControllerAndActionInAppMode($controllerDescription, array $options = array(), $controllerFileSuffix = '.php') {
	$say = false;
	
	$relevantNamespace = 'Cundd';
	$defaultPrefix = 'index';

	$actionNameSuffix = 'Action';
	$actionNameDefault = 'index';


	$controllerNameSuffix = 'Controller';
	$controllerNameDefault = 'Index';
	$controllerDir = CunddConfig::__('Cundd_controller_dir');

	$patternMode = 0;
	$failureArray = array(
	    'module' => NULL,
	    'controller' => NULL,
	    'action' => NULL,
	    'controllerClass' => NULL,
	    'absControllerClassPath' => NULL,
	);

	// Detect the Pattern of the given controller description
	$partName = '[_a-zA-Z0-9]+'; // Repräsentiert einen gültigen Namen eines Moduls, Controllers oder Action
	//$partName = '\w*'; // Repräsentiert einen gültigen Namen eines Moduls, Controllers oder Action
	if (preg_match("!$partName/$partName::$partName$!", $controllerDescription)) { // Module/Controller::Action
	    $patternMode = 4;
	} else if (preg_match("!$partName/$partName::$!", $controllerDescription)) { // Module/Controller::
	    $patternMode = 5;
	} else if (preg_match("!($partName/){3}$!", $controllerDescription)) { // Module/Controller/Action/
	    $patternMode = 6;
	} else if (preg_match("!($partName/){2}$partName$!", $controllerDescription)) { // Module/Controller/Action
	    $patternMode = 3;
	} else if (preg_match("!($partName/){2}$!", $controllerDescription)) { // Module/Controller/
	    $patternMode = 7;
	} else if (preg_match("!$partName/$partName!", $controllerDescription)) { // Module/Controller
	    $patternMode = 8;
	} else if (preg_match("!$partName/$!", $controllerDescription)) { // Module/
	    $patternMode = 1;
	} else if (preg_match("!$partName$!", $controllerDescription)) { // Module
	    $patternMode = 2;
	} else {
	    $patternMode = 0;
	    //return $failureArray;
	}

	// Get the parts
	$parts = array();
	if (!preg_match_all("!$partName!", $controllerDescription, $parts, PREG_PATTERN_ORDER)) {
	    $msg = "The part-name-pattern \"$partName\" couldn't be found in the controller-description $controllerDescription.";
	    throw new Exception($msg);
	}
	$parts = $parts[0];

	

	switch ($patternMode) {
	    // Only the module is given
	    case 1:
	    case 2:
		$module = ucfirst(strtolower($parts[0]));
		$controller = $controllerNameDefault . $controllerNameSuffix;
		$action = $actionNameDefault . $actionNameSuffix;
		break;

	    // Module and controller are given
	    case 5:
	    case 7:
	    case 8:
		$module = ucfirst(strtolower($parts[0]));
		$controller = ucfirst(strtolower($parts[1])) . $controllerNameSuffix;
		$action = $actionNameDefault . $actionNameSuffix;
		break;

	    // All three are given
	    case 3:
	    case 4:
	    case 6:
		$module = ucfirst(strtolower($parts[0]));
		$controller = ucfirst(strtolower($parts[1])) . $controllerNameSuffix;
		$action = strtolower($parts[2]) . $actionNameSuffix;
		break;

	    default:
		// Error
		break;
	}


	$controllerPath = $relevantNamespace . '/' . $module . '/' . $controllerDir . $controller . $controllerFileSuffix;
	$absControllerPath = CunddPath::getAbsoluteFilePath(CunddPath::getClassDirName().$controllerPath, true);

	$controllerName = $relevantNamespace . '_' . $module . '_' . $controller;


	$returnArray = array(
	    'module' => $module,
	    'controller' => $controller,
	    'action' => $action,
	    'controllerClass' => $controllerName,
	    'absControllerClassPath' => $absControllerPath,
	);

	/* DEBUGGEN */
	if ($say) {
	    echo "CunddClassLoader: The detected pattern-mode $patternMode in description $controllerDescription.<br />";
	    CunddTools::pd($returnArray);
	    CunddTools::pd($parts);
	}
	/* DEBUGGEN */

	/* Wenn die Controller-Datei existiert das Array zurückgeben
	 * sonst ein leeres Array zurückgeben.
	 */
	if ($absControllerPath) {
	    require_once($absControllerPath);
	    return $returnArray;
	} else {
	    return $failureArray;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode zerlegt den übergebenen String in den passenden Controller und die Action
     * und gibt diese in einem assoziativen Array zurück.
     * @param string $controllerDescription Module/Controller::Action|Module/Controller/Action
     * @param array $options
     * @param string $controllerFileSuffix
     * @return array('module' => $module, 'controller' => $controller, 'action' => $action, 'controllerClass' => $controllerName, 'absControllerClassPath' => $absControllerPath)
     */
    protected static function _getControllerAndActionInWebMode($controllerDescription, array $options = array(), $controllerFileSuffix = '.php') {
	$say = false;
	
	$relevantNamespace = 'Cundd';
	$defaultPrefix = 'index';

	$actionNameSuffix = 'Action';
	$actionNameDefault = 'Index';


	$controllerNameSuffix = 'Controller';
	$controllerNameDefault = 'Index';
	$controllerDir = CunddConfig::__('Cundd_controller_dir');


	$module = 'Cms';
	$controller = $controllerNameDefault . $controllerNameSuffix;
	$action = 'read' . $actionNameSuffix;

	$controllerPath = $relevantNamespace . '/' . $module . '/' . $controllerDir . $controller . $controllerFileSuffix;
	$absControllerPath = CunddPath::getAbsoluteClassDir() . $controllerPath;
	$controllerName = $relevantNamespace . '_' . $module . '_' . $controller;

	$returnArray = array(
	    'module' => $module,
	    'controller' => $controller,
	    'action' => $action,
	    'controllerClass' => $controllerName,
	    'absControllerClassPath' => $absControllerPath,
	);

	/* DEBUGGEN */
	if ($say) {
	    echo "The detected pattern-mode $patternMode for description $controllerDescription.<br />";
	    CunddTools::pd($returnArray);
	    CunddTools::pd($parts);
	}
	/* DEBUGGEN */

	/* Wenn die Controller-Datei existiert das Array zurückgeben
	 * sonst ein leeres Array zurückgeben.
	 */
	if (file_exists($absControllerPath)) {
	    require_once($absControllerPath);
	    return $returnArray;
	} else {
	    return $failureArray;
	}
    }



    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the path to local if found, or the global class file.
     * @param string $controllerPath
     * @return string
     */
    protected static function _getAbsoluteClassDirForControllerPath($controllerPath){
	if(file_exists(CunddPath::getAbsoluteLocalClassDir().$controllerPath)){ // Check for local class file
	    return CunddPath::getAbsoluteLocalClassDir().$controllerPath;
	} else if(file_exists(CunddPath::getAbsoluteGlobalClassDir().$controllerPath)){
	    return CunddPath::getAbsoluteGlobalClassDir().$controllerPath;
	} else {
	    return (string)'';
	}
    }

}