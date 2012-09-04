<?php

//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Tools_Link erweitert Cundd_Tools_Abstract und bietet Methoden zur 
 * Verwaltung und Ausgabe der Links.
 * @package Cundd_Tools_Abstract
 * @version 1.0
 * @since Jan 12, 2010
 * @author daniel
 */
class Cundd_Tools_Link extends Cundd_Tools_Abstract {

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die statische Methode ergänzt die Daten eines Links um die nötigen Parameter für
     * einen Seitenaufruf per Ajax.
     * @param string $title The displayed text of the link.
     * @param string $aufruf The link action.
     * @param string $divToChange[optional] The div whose content should be changed.
     * @param string $data[optional] The data to be sent with the action.
     * @param string $class[optional] The CSS class to wrap the link.
     * @return string
     */
    public static function newLink($title, $aufruf, $divToChange = NULL, $data = NULL, $class = 'CunddLink CunddLink') {
	return self::newSoftLink($title, $aufruf, $divToChange, $data, $class);
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Returns a new soft link, which requests data using Ajax.
     * 
     * @param string $title The displayed text of the link.
     * @param string $aufruf The link action.
     * @param string $divToChange[optional] The div whose content should be changed.
     * @param string $data[optional] The data to be sent with the action.
     * @param string $class[optional] The CSS class to wrap the link.
     * @return string
     */
    public static function newSoftLink($title, $aufruf, $divToChange = NULL, $data = NULL, $class = 'CunddLink CunddLink') {
	if (!$divToChange) {
	    $divToChange = CunddConfig::get('CunddContent_div');
	}

	// TODO:
	//echo '<a href="#" class="CunddNewLink CunddLink" onclick="CunddLinkAjax.inhalt_aendern(CunddAjax_verweis.php, {aufruf:'.$aufruf.'}, '.$divToChange.')">';
	$linkAction = CunddLink::newLinkAction($aufruf, $divToChange, $data);
	$newLink = '<a href="#" class="' . $class . '" ' . $linkAction . '>' . $title . '</a>';

	return $newLink;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die statische Methode erstellt einen Link ohne Ajax-Aufruf.
     * @param string $title
     * @param string $aufruf[optional]
     * @param string $target[optional]
     * @param string $class[optional]
     * @return string
     */
    public static function newHardLink($title, $aufruf = NULL, $target = '_self', $class = 'CunddNewLink CunddHardLink') {
	// TODO:
	//echo '<a href="#" class="CunddNewLink CunddLink" onclick="CunddLinkAjax.inhalt_aendern(CunddAjax_verweis.php, {aufruf:'.$aufruf.'}, '.$divToChange.')">';

	if (!$aufruf)
	    $aufruf = $title;

	$newLink = '<a href="' . $aufruf . '" class="' . $class . '">' . $title . '</a>';

	return $newLink;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die statische Methode erstellt die nötige Aktion für einen Ajax-Link.
     * @param string $aufruf
     * @param string $divToChange
     * @param string $data
     * @return string
     */
    public static function newLinkAction($aufruf, $divToChange = NULL, $data = NULL) {
	if (!$divToChange) {
	    $divToChange = CunddConfig::get('CunddContent_div');
	}

	// TODO:
	//echo '<a href="#" class="CunddNewLink CunddLink" onclick="CunddLinkAjax.inhalt_aendern(CunddAjax_verweis.php, {aufruf:'.$aufruf.'}, '.$divToChange.')">';
	$newLinkAction = 'onclick="CunddLinkAjax.inhalt_aendern(object = {aufruf:\'' . $aufruf . '\', targetId:\'' . $divToChange . '\'';

	// Wenn spezielle Daten angegeben wurden, diese mitsenden
	if ($data) {
	    $newLinkAction .= ', data:\'' . $data . '\'';
	}

	$newLinkAction .= '})"';

	return $newLinkAction;
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode speichert einen neuen Link in der MySQL-Datenbank.
     * @param array $input array('column1' => 'value1' [, 'column2' => 'value2' [, ... ]] )
     * @param string $table
     * @return int|boolean
     */
    public function newLinkRecord(array $input, $table = NULL) {
	$error = NULL;
	if ($table) {
	    $this->tabelle = $table;
	} else if ($this->tabelle == false) {
	    $error = 'No table set';
	    echo $error;
	    //CunddTools::error('CunddLink',$error);
	}

	if (!$error) {
	    // Daten vorbereiten
	    CunddBenutzer::prepareUserData($input);
	    $columns = CunddFelder::get_link();
	    foreach ($columns[0] as $key => $column) {
		$parsedColumns[$column] = $column;
	    }
	    $relevantData = array_intersect_key($input, $parsedColumns);


	    $db = new CunddDB($this->tabelle);
	    $table = $db->getTable();
	    $adapter = $db->getAdapter();



	    $result = $adapter->insert($table, $relevantData);

	    if ($result) {
		return $adapter->lastInsertId();
	    } else {
		return (bool) false;
	    }
	} else if ($error) {
	    return (bool) false;
	}
    }

    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    //MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
    /**
     * Die Methode speichert eine Reihe von neuen Links in der MySQL-Datenbank.
     * @param array $inputSequences array( array('column1' => 'value1' [, 'column2' => 'value2' [, ... ]] ) [, array('column1' => 'value1' [, 'column2' => 'value2' [, ... ]] ) ])
     * @param string $table
     * @return int|boolean
     */
    public function newLinkRecordSequence(array $inputSequences, $table = NULL) {
	$error = NULL;
	$result = false;
	if ($table) {
	    $this->tabelle = $table;
	} else if ($this->tabelle == false) {
	    $error = 'No table set';
	    echo $error;
	}

	// Die Tiefe des übergebenen Arrays überprüfen
	if (gettype($inputSequences[0]) != 'array') {
	    // Es wurde keine Sequence übergeben sondern nur ein einzelner Record
	    $error = 'Input is not a Sequence. Calling the method newLinkRecord() with $inputSequence.';
	    echo $error;
	    $result = $this->newLinkRecord($inputSequences);
	}

	if (!$error) {
	    foreach ($inputSequences as $key => $input) {
		$result = $this->newLinkRecord($input);
	    }
	} else {
	    CunddTools::error('CunddLink', $error);
	}

	return $result;
    }

}

?>