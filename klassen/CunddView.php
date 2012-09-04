<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddView" erweitert die Klasse "Zend_View". */
class CunddView extends Zend_View {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    // Variablen deklarieren
    private $placeholderCollection;

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode ist ein Alias für placeholder()
     *
     * @param string $key
     * @param mixed $para1
     * @param mixed $para2
     * @param mixed $para3
     * @param mixed $para4
     * @return mixed
     */
    public function getPlaceholder($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL) {
	return $this->placeholder($key, $para1, $para2, $para3, $para4);
    }

    /**
     * @see getPlaceholder()
     */
    public function p($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL) {
	return $this->getPlaceholder($key, $para1, $para2, $para3, $para4);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt den Wert des Placeholders aus.
     *
     * @param string $key
     * @param mixed $para1
     * @param mixed $para2
     * @param mixed $para3
     * @param mixed $para4
     * @return void
     */
    public function printPlaceholder($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL) {
	echo self::getPlaceholder($key, $para1, $para2, $para3, $para4);
    }

    /**
     * @see printPlaceholder()
     */
    public function printP($key = NULL, $para1 = NULL, $para2 = NULL, $para3 = NULL, $para4 = NULL) {
	self::printPlaceholder($key, $para1, $para2, $para3, $para4);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode ist ermittelt den Wert eines Placeholders und übersetzt diesen mittels
     * CunddLang.
     *
     * @param string $key
     * @return string
     */
    public function getPlaceholderAndTranslate($key = NULL) {
	$placeholderValue = self::p($key);
	$placeholderValue = $placeholderValue . ''; // Parse to string
	return self::__($placeholderValue);
    }

    /**
     * @see getPlaceholderAndTranslate()
     */
    public function pt($key = NULL) {
	return self::getPlaceholderAndTranslate($key);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode übersetzt den übergebenen String mittels CunddLang.
     *
     * @param string $msg
     * @return string
     */
    public function translate($msg = NULL) {
	return CunddLang::get($msg);
    }

    public function __($msg = NULL) {
	return self::translate($msg);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode bereitet das Abfragen der übergebenen Key-Value-Paare in der Template-
     * Datei vor.
     *
     * @param array $wert
     * @return mixed
     */
    public function registerPlaceholdersFromArray(array $wert) {
	foreach ($wert as $key => $value) {
	    $this->placeholder($key)->set($value);
	    $result = $this->placeholder($key)->set($value);
	    $this->placeholderCollection[$key] = $value;
	}
	return $result;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode registriert einen Placeholder.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function registerPlaceholder($key, $value) {
	$this->placeholderCollection[$key] = $value;
	return $this->placeholder($key)->set($value);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode gibt die Eigenschaft $placeholderCollection zurück.
     *
     * @return array
     */
    public function getAllPlaceholders() {
	return $this->placeholderCollection;
    }

    /**
     * @see getAllPlaceholders()
     */
    public function getPlaceholderCollection() {
	return self::getAllPlaceholders();
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode setzt alle registrierten Placeholder auf NULL.
     *
     * @param mixed $newValue (optional)
     * @return bool
     */
    public function clearAllPlaceholders($newValue = NULL) {
	$allPlaceholders = $this->getAllPlaceholders();
	if (gettype($allPlaceholders) == 'array') {
	    foreach ($allPlaceholders as $key => $placeholder) {
		$result = $this->placeholder($key)->set($newValue);
		$this->placeholderCollection[$key] = $newValue;
	    }
	    return $result;
	} else {
	    return false;
	}
    }

}

?>