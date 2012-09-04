<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Diese Klasse "CunddPath" bietet verschiedene Methoden zur ermittlung der Pfade im 
 * Filessytem und dem Webserver. */
/* Welche Pfade eine Methode ermittelt kann durch deren Name erkannt werden:
 * Verzeichnis im Dateisystem = ...Dir
 * Verzeichnis auf dem Webserver = ...Url
 * Pfad inkl. Dateiname im Dateisystem = ...Path
 */
class CunddPath {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Variablen deklarieren
    public $name = 'CunddPath';
    public $version = 1.1;
    protected static $_absoluteBaseDir = NULL;
    protected static $_relativeBaseDir = NULL;
    protected static $_classDirName = NULL;

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Konstruktor
    function CunddPath() {
	echo $this->name;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    public static function getClassDirName(){
	if(self::$_classDirName == NULL){
	    self::$_classDirName = CunddConfig::__('Cundd_class_path');
	}
	return self::$_classDirName;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum Base-Verzeichnis zurück.
     *
     * @return string
     */
    public static function getAbsoluteBaseUrl() {
	return CunddConfig::get("Cundd_protocol") . '://' . $_SERVER['SERVER_NAME'] . CunddConfig::get('BasePath');
    }

    public static function getAbsBaseUrl() {
	return CunddPath::getAbsoluteBaseUrl();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum lokalen Klassenverzeichnis zurück.
     * @return string
     */
    public static function getAbsoluteFileUploadUrl() {
	return CunddConfig::get("Cundd_protocol") . '://' . $_SERVER['SERVER_NAME'] . CunddConfig::get('BasePath') . CunddConfig::get('CunddFiles_upload_dir');
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum lokalen Klassenverzeichnis zurück.
     * @return string
     */
    public static function getAbsoluteClassDir() {
	return CunddPath::getAbsoluteBaseDir() . self::getClassDirName();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den relativen Pfad zum Klassenverzeichnis zurück.
     * @return string
     */
    public static function getRelativeClassDir() {
	return CunddPath::getRelativeBaseDir() . self::getClassDirName();
    }

    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the path to the directory of local class files.
     * @return string
     */
    public static function getAbsoluteLocalClassDir() {
	return getcwd() . '/' . CunddConfig::get('CunddBasePath') . CunddConfig::get("Cundd_class_path");
	return CunddSystem::getDir() . self::getClassDirName();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the path to the global class directory. The value is tested and called through the inclusion of the file
     * CunddClassLoaderPathChecker the mode()-method.
     * @return string
     */
    public static function getAbsoluteGlobalClassDir() {
	return CunddClassLoader::getAbsoluteGlobalClassDir();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /* Die Methode gibt die Url zum Klassen-Verzeichnis zurück. */
    public static function getAbsoluteClassUrl() {
	return CunddConfig::__('Cundd_protocol') . '://' . $_SERVER['SERVER_NAME'] . '/' . CunddConfig::get('BasePath') . CunddConfig::__('CunddBasePath') . self::getClassDirName();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /* Die Methode gibt die Url zum Klassen-Verzeichnis zurück. */
    public static function getRelativeClassUrl() {
	return CunddConfig::__('CunddBasePath') . self::getClassDirName();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /* Die Methode gibt den absoluten Pfad im Filesystem zum aufgerufenen Skript zurück. */
    public static function getAbsoluteCurrentDir() {
	return dirname(__FILE__);
	$currentScriptName = $_SERVER['SCRIPT_FILENAME'];
	$currentScriptNameTemp = explode('/', $currentScriptName);
	$currentScriptNameClean = array();

	for ($i = 1; $i < count($currentScriptNameTemp) - 1; $i++) {
	    $currentScriptNameClean[] = $currentScriptNameTemp[$i];
	}

	return '/' . CunddPath::arrayToString($currentScriptNameClean, '/') . '/';
	// return '/'.self::arrayToString($currentScriptNameClean,'/');
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum lokalen CunddBase-Verzeichnis zurück.
     * @return string
     */
    public static function getAbsoluteBaseDir() {
//	    echo '<pre>';
//	    throw new ErrorException('getAbsoluteBaseDir');
	if (self::$_absoluteBaseDir == NULL) {
//		$dir = dirname(__FILE__);
	    $dir = CunddSystem::getDir();
	    $dir = str_replace(self::getClassDirName(), '', $dir . '/');

//		echo "$dir<br>".CunddClassLoader::mode()."<br>";

	    self::$_absoluteBaseDir = $dir;
	}
	return self::$_absoluteBaseDir;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum lokalen CunddBase-Verzeichnis zurück.
     * @return string
     */
    public static function getAbsoluteLocalBaseDir() {
//	    echo '<pre>';
//	    throw new ErrorException('getAbsoluteBaseDir');
	if (self::$_absoluteBaseDir == NULL) {
//		$dir = dirname(__FILE__);
	    $dir = CunddSystem::getDir();
	    $dir = str_replace(self::getClassDirName(), '', $dir . '/');

//		echo "$dir<br>".CunddClassLoader::mode()."<br>";

	    self::$_absoluteBaseDir = $dir;
	}
	return self::$_absoluteBaseDir;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the absolute path to the global base dir or NULL on error.
     * @return string
     */
    public static function getAbsoluteGlobalBaseDir() {
	$gClassDir = self::getAbsoluteGlobalClassDir();
	$gBaseDir = str_replace(CunddConfig::get("Cundd_class_path"), '', $gClassDir);

	if (file_exists($gBaseDir)) {
	    return $gBaseDir;
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den relativen Pfad zum lokalen CunddBase-Verzeichnis zurück.
     * @return string
     */
    public static function getRelativeBaseDir() {
	if (self::$_relativeBaseDir == NULL) {
	    $dir = CunddConfig::__('CunddBasePath');
	    self::$_relativeBaseDir = $dir;
	}
	return self::$_relativeBaseDir;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum Base-Verzeichnis zurück.
     * @return string
     */
    public static function getRelativeDownloadDir() {
	$basePath = CunddConfig::get('BasePath');
	$cunddBasePath = CunddConfig::get('CunddBasePath');
	$cundd_class_path = CunddConfig::get('Cundd_class_path');

	return '.' . $cunddBasePath . $cundd_class_path . 'CunddFiles/';
	/*
	 * BasePath = /vbc
	  CunddBasePath = /Cundd
	  CunddBaseUrl = www.cundd.net/vbc
	 */
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zur Layout-Template-Datei zurück.
     * Returns NULL if the file doesn't exist.
     *
     * @param string $layoutClass
     * @return string
     */
    public static function getAbsoluteLayoutPath($layoutClass) {
	$relativeLayoutPathAndFile = self::getRelativeLayoutPathAndFile($layoutClass);
	$relFilePathTemp = $relativeLayoutPathAndFile['filePath'] . '/' . $relativeLayoutPathAndFile['fileName'];
	$relFilePathTemp = $relFilePathTemp . CunddConfig::__('CunddTempate_layout_file_suffix');

	$layoutLocalBaseDir = self::getAbsoluteLocalLayoutBaseDir();
	$layoutGlobalBaseDir = self::getAbsoluteGlobalLayoutBaseDir();

//	CunddTools::pd($layoutLocalBaseDir.$relFilePathTemp);
//	CunddTools::pd($layoutGlobalBaseDir.$relFilePathTemp);

	if (file_exists($layoutLocalBaseDir . $relFilePathTemp)) { // Check local
	    return $layoutLocalBaseDir . $relFilePathTemp;
	} else if (file_exists($layoutGlobalBaseDir . $relFilePathTemp)) { // Check global
	    return $layoutGlobalBaseDir . $relFilePathTemp;
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum Layout-Template-Verzeichnis zurück.
     *
     * @param string $layoutClass
     * @return string
     */
    public static function getAbsoluteLayoutDir($layoutClass) {
	$relativeLayoutPathAndFile = self::getRelativeLayoutPathAndFile($layoutClass);
	$relFilePathTemp = $relativeLayoutPathAndFile['filePath'] . '/' . $relativeLayoutPathAndFile['fileName'];
	$relFilePathTemp = $relFilePathTemp . CunddConfig::__('CunddTempate_layout_file_suffix');

	$layoutLocalBaseDir = self::getAbsoluteLocalLayoutBaseDir();
	$layoutGlobalBaseDir = self::getAbsoluteGlobalLayoutBaseDir();

//	CunddTools::pd($layoutLocalBaseDir.$relFilePathTemp);
//	CunddTools::pd($layoutGlobalBaseDir.$relFilePathTemp);

	if (file_exists($layoutLocalBaseDir . $relFilePathTemp)) { // Check local
	    return $layoutLocalBaseDir . $relativeLayoutPathAndFile['filePath'];
	} else if (file_exists($layoutGlobalBaseDir . $relFilePathTemp)) { // Check global
	    return $layoutGlobalBaseDir . $relativeLayoutPathAndFile['filePath'];
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /* Die Methode gibt den absoluten Pfad zur Layout-Template-Datei zurück. */
    public static function getLayoutFile($layoutClass) {
	$relativeLayoutPathAndFile = self::getRelativeLayoutPathAndFile($layoutClass);
	return $relativeLayoutPathAndFile['fileName'];
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /* Die Methode gibt den relativen Pfad zur Layout-Template-Datei und deren Name zurück. */
    public static function getRelativeLayoutPathAndFile($layoutClass) {
	$relFilePathTemp = explode('_', $layoutClass);
	$newFilePathTemp = array();

	for ($i = 0; $i < count($relFilePathTemp) - 1; $i++) {
	    $newFilePathTemp[] = $relFilePathTemp[$i];
	}

	// Last Id
	/*
	  $lastId = count($newFilePathTemp) - 1;
	  $newFilePathTemp[$lastId] = $newFilePathTemp[$lastId];
	  /*
	  $lastElement = end($newFilePathTemp);
	  CunddTools::pd($newFilePathTemp);
	  echo key($newFilePathTemp);
	  $newFilePathTemp[key($newFilePathTemp)] = strtolower($lastElement);
	  /* */
	$result['filePath'] = CunddPath::arrayToString($newFilePathTemp, '/');
	// $result['filePath'] = self::arrayToString($newFilePathTemp,'/');
	$result['fileName'] = strtolower($relFilePathTemp[$i++]);

	return $result;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zur Layout-Base-Verzeichnis zurück.
     * Returns NULL on error.
     *
     * @return string
     */
    public static function getAbsoluteLayoutBaseDir() {
	$global = self::getAbsoluteGlobalLayoutBaseDir();
	$local = self::getAbsoluteLocalLayoutBaseDir();

	if ($local) { // Check the local
	    return $local;
	} else if ($global) { // Check the global
	    return $global;
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zur Layout-Base-Verzeichnis zurück.
     * Returns NULL on error.
     *
     * @return string
     */
    public static function getAbsoluteLocalLayoutBaseDir() {
	$absLocalBaseDir = self::getAbsoluteLocalBaseDir();
	$layoutDir = CunddConfig::__('Cundd_layout_dir');

//	CunddTools::pd($absLocalBaseDir.$layoutDir);

	if (file_exists($absLocalBaseDir . $layoutDir)) { // Check the local
	    return $absLocalBaseDir . $layoutDir;
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zur Layout-Base-Verzeichnis zurück.
     * Returns NULL on error.
     *
     * @return string
     */
    public static function getAbsoluteGlobalLayoutBaseDir() {
	$absGlobalBaseDir = self::getAbsoluteGlobalBaseDir();
	$layoutDir = CunddConfig::__('Cundd_layout_dir');

//	CunddTools::pd($absGlobalBaseDir.$layoutDir);
	if ($absGlobalBaseDir && file_exists($absGlobalBaseDir . $layoutDir)) {
	    return $absGlobalBaseDir . $layoutDir;
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zum Temp-Verzeichnis zurück. */
    public function getAbsoluteTempDir() {
	$baseDir = self::getAbsoluteBaseDir();
	return $baseDir . CunddConfig::__("Cundd_managed_object_dir");
    }

    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt einen String mit den Elementen des als Parameter
     * übergebenen Arrays zurück. Die Elemente werden entweder durch einen
     * Beistrich oder die Zeichen des zweiten optionalen Parameters
     * getrennt.
     * @param array $arrayPara
     * @param string $seperator
     * @return string|false
     */
    private static function arrayToString(array $arrayPara, $seperator = ',') {
	$resultString = '';
	$i = 0;

	foreach ($arrayPara as $key => $value) {
	    // TODO: handle key
	    $resultString .= $value;
	    $i++;

	    if ($i < count($arrayPara)) {
		$resultString .= $seperator;
	    }
	}

	if ($resultString == '') {
	    return false;
	} else {
	    return $resultString;
	}
    }

    private static function array2String(array $arrayPara, $seperator = ',') {
	return self::arrayToString($arrayPara, $seperator);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * @deprecated
     * Die Methode gibt die Url zum Resources-Ordner des übergebenen Moduls zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteResourcesUrlOfModul($modulName) {
	$modulUrl = self::_getAbsoluteModulUrl($modulName);
	return $modulUrl . '/' . CunddConfig::__('Cundd_resources_dir');
    }

    /**
     * Die Methode gibt die Url zum Resources-Ordner des übergebenen Moduls zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteResourcesUrlOfModule($moduleName) {
	return self::getAbsoluteResourcesUrlOfModul($moduleName);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * @deprecated
     * Die Methode gibt die Url zum View-Ordner des übergebenen Moduls zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteViewDirOfModul($modulName) {
	$modulUrl = self::_getAbsoluteModulDir($modulName);
	return $modulUrl . '/' . CunddConfig::__('Cundd_view_dir');
    }

    /**
     * Die Methode gibt die Url zum View-Ordner des übergebenen Moduls zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteViewDirOfModule($moduleName) {
	return self::getAbsoluteViewDirOfModul($moduleName);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * @deprecated
     * Die Methode gibt die Url zum View-Ordner des übergebenen Moduls zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteTemplateDirOfModul($modulName) {
	$modulUrl = self::getAbsoluteViewDirOfModul($modulName);
	return $modulUrl . CunddConfig::__('Cundd_view_template_dir');
    }

    /**
     * Die Methode gibt die Url zum View-Ordner des übergebenen Moduls zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteTemplateDirOfModule($moduleName) {
	return self::getAbsoluteTemplateDirOfModul($moduleName);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * @deprecated
     * Die Methode gibt die absolute Url eines Modul-Verzeichnisses zurück.
     * @param string $modulName
     * @return string
     */
    protected static function _getAbsoluteModulUrl($modulName) {
	$classUrl = self::getAbsoluteClassUrl();
	$relevantNamespace = 'Cundd';
	return $classUrl . $relevantNamespace . '/' . $modulName;
    }

    /**
     * @see _getAbsoluteModulUrl()
     */
    public static function getAbsoluteModulUrl($modulName) {
	return self::_getAbsoluteModulUrl($modulName);
    }

    /**
     * Die Methode gibt die absolute Url eines Modul-Verzeichnisses zurück.
     * @param string $modulName
     * @return string
     */
    public static function getAbsoluteModuleUrl($moduleName) {
	return self::_getAbsoluteModulUrl($moduleName);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * @deprecated
     * Die Methode gibt den absoluten Pfad zum Modul-Base-Dir des übergebenen Moduls zurück.
     * @param string $moduleName
     * @return string
     */
    public static function getRelativeModulUrl($moduleName) {
	$classDir = self::getRelativeClassUrl();
	$relevantNamespace = 'Cundd';

	$modulDir = str_replace('_', '/', $moduleName);

	return $classDir . $relevantNamespace . '/' . $modulDir;
    }

    /**
     * Die Methode gibt den absoluten Pfad zum Modul-Base-Dir des übergebenen Moduls zurück.
     * @param string $moduleName
     * @return string
     */
    public static function getRelativeModuleUrl($moduleName) {
	return self::getRelativeModulUrl($moduleName);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * @deprecated
     * Die Methode gibt den absoluten Pfad zum Modul-Base-Dir des übergebenen Moduls zurück.
     * @param string $moduleName
     * @return string
     */
    protected function _getAbsoluteModulDir($moduleName) {
	$classDir = self::getAbsoluteClassDir();
	$relevantNamespace = 'Cundd';

	$modulDir = str_replace('_', '/', $moduleName);

	return $classDir . $relevantNamespace . '/' . $modulDir;
    }

    /**
     * @see _getAbsoluteModulDir()
     */
    public static function getAbsoluteModulDir($moduleName) {
	return self::_getAbsoluteModulDir($moduleName) . '/';
    }

    /**
     * Die Methode gibt den absoluten Pfad zum Modul-Base-Dir des übergebenen Moduls zurück.
     * @param string $moduleName
     * @return string
     */
    public static function getAbsoluteModuleDir($moduleName) {
	return self::_getAbsoluteModulDir($moduleName) . '/';
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob der übergebene Pfad (Verzeichnis oder File) schreibbar ist.
     * @param string $path
     * @param boolean $required
     * @return boolean
     */
    public static function checkIfWriteable($path, $required = false) {
	if (is_writable($path)) {
	    return (bool) true;
	} else {
	    if ($required) {
		$msg = "The path $path is not writeable.";
		echo $msg;
		CunddTools::error($msg);
	    }
	    return (bool) false;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the absolute path to a file. The method checks if the file exists in the local
     * or global space and returns the path. If the file exists in both spaces, allways the
     * local path is returned. If the file can't be found in either space NULL is returned
     *
     * @param string $relativeFilePath The path to the file relative to the base path
     * @param bool $includeIfExists Indicates whether the file should be included if it exists
     * @return string The absolute path to the file or NULL on error
     */
    public static function getAbsoluteFilePath($relativeFilePath, $includeIfExists = false) {
	$gPath = CunddPath::getAbsoluteGlobalBaseDir() . $relativeFilePath; // Global path
	$lPath = CunddPath::getAbsoluteLocalBaseDir() . $relativeFilePath; // Local path
	
	if (file_exists($lPath)) {
	    if ($includeIfExists) require($lPath);
	    return $lPath;
	} else if (file_exists($gPath)) {
	    if ($includeIfExists) require($gPath);
	    return $gPath;
	} else {
	    return NULL;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Checks if a file at a given path exists in either the global or local space.
     *
     * @param string $filePath The path to the file relative to the base path
     * @param bool $includeIfExists Indicates whether the file should be included if it exists
     * @return int 1 if found in global space|2 if found in local space|0 if not found
     */
    public static function fileExistsInGlobalOrLocalSpace($relativeFilePath, $includeIfExists = false) {
	$gPath = CunddPath::getAbsoluteGlobalBaseDir() . $relativeFilePath; // Global path
	$lPath = CunddPath::getAbsoluteLocalBaseDir() . $relativeFilePath; // Local path

	if (file_exists($lPath)) {
	    if ($includeIfExists)
		include($lPath);
	    return 2;
	} else if (file_exists($gPath)) {
	    if ($includeIfExists)
		include($gPath);
	    return 1;
	} else {
	    return 0;
	}
    }

    /**
     * @see fileExistsInGlobalOrLocalSpace
     */
    public static function checkIfFileExistsInGlobalOrLocalSpace($relativeFilePath, $includeIfExists = false) {
	return self::fileExistsInGlobalOrLocalSpace($relativeFilePath, $includeIfExists);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the absolute path to the given file in global storage. Doesn't test the file's existence.
     * @param string $filePath The relative path to the file
     * @return string 
     */
    public static function getAbsoluteGlobalPathForFile($relativeFilePath) {
	return CunddPath::getAbsoluteGlobalBaseDir() . $relativeFilePath; // Global path
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns the absolute path to the given file in local storage. Doesn't test the file's existence.
     * @param string $filePath The relative path to the file
     * @return string 
     */
    public static function getAbsoluteLocalPathForFile($relativeFilePath) {
	return CunddPath::getAbsoluteLocalBaseDir() . $relativeFilePath; // Global path
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob der Thumbnail-Ordner schreibbar ist.
     * @return boolean
     */
    public static function checkIfThumbnailDirIsWritable() {
	$path = dirname($_SERVER['SCRIPT_FILENAME']) . CunddFiles::getRealPath('', 'thumb');
	return self::checkIfWriteable($path, TRUE);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob der Upload-Ordner schreibbar ist.
     * @return boolean
     */
    public static function checkIfUploadDirIsWritable() {
	$path = CunddFiles::getRealPath('', 'upload');
	return self::checkIfWriteable($path, TRUE);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob der Upload-Ordner schreibbar ist.
     * @return boolean
     */
    public static function checkIfOriginalDirIsWritable() {
	$path = CunddFiles::getRealPath('', 'upload_original');
	return self::checkIfWriteable($path, TRUE);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob der Upload-Ordner schreibbar ist.
     * @return boolean
     */
    public static function checkIfTempDirIsWritable() {
	return self::checkIfWriteable(self::getAbsoluteTempDir(), TRUE);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob der Temp-Ordner schreibbar ist und existiert, wenn nicht
     * wird versucht den Ordner zu erstellen.
     * @return boolean
     */
    public static function checkIfTempDirIsWritableElseCreate($required = false) {
	$return = false;

	$absTempDir = self::getAbsoluteTempDir();
	if (self::checkIfWriteable($absTempDir)) {
	    $return = true;
	} else if (file_exists($absTempDir) AND $required) {
	    throw new Exception("The temp directory " . $absTempDir . " exists but can't be written.");
	} else if (file_exists($absTempDir)) {
	    $return = false;
	} else if (mkdir($absTempDir, 0777)) {
	    $msg = "The temp directory " . $absTempDir . " has been created.";
	    CunddTools::log($msg);

	    new CunddEvent('dirCreated');

	    $return = (bool) true;
	} else if ($required) {
	    throw new Exception("The temp directory " . $absTempDir . " doesn't exist and couldn't be created.");
	}
	return $return;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den absoluten Pfad zu dem übergebenen Model zurück.
     * @param string $model
     * @param string $classFileSuffix[optional]
     * @return string
     */
    public static function getAbsoluteModelPath($model, $classFileSuffix = '.php') {
	$relPath = self::getRelativeModelPath($model, $classFileSuffix);
	$classDir = self::getAbsoluteClassDir();

	return $classDir . $relPath;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den relativen Pfad zu dem übergebenen Model zurück.
     * @param string $model
     * @param string $classFileSuffix[optional]
     * @return string
     */
    public static function getRelativeModelPath($model, $classFileSuffix = '.php') {
	//return str_replace('_','/',$model).$classFileSuffix;
	return CunddClassLoader::parseClassname($model, $classFileSuffix);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode überprüft ob die Datei eines übergebenen Models existiert.
     * @param string $model
     * @param string $classFileSuffix[optional]
     * @return boolean
     */
    public static function checkIfModelFileExists($model, $classFileSuffix = '.php') {
	$modelName = CunddClassLoader::getModelName($model);
	$modelPath = self::getAbsoluteModelPath($modelName);
	return file_exists($modelPath);
    }

}

// Class loader hack
return true;
?>