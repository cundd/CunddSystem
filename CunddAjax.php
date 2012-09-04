<?php
@session_start();
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Diese Datei wird von Ajax aufgerufen. Anhand des Wertes von $_POST["aufruf"] wird er-
mittelt welches PHP-Skript ausgeführt werden soll. Diese Datei/dieses Skript ist sozu-
sagen ein Containers für die Cundd-Module. Außerdem wird der div, der geändert werden 
soll, ebenfalls als Parameter übergeben. Sein Inhalt wird aber von der  aufrufenden 
JavaScript-Funktion geändert. */

$CunddAjax_instanz = new CunddAjax();
class CunddAjax{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $kind; // Speichert die Instanz des Moduls, das von CunddAjax aufgerufen wird
	var $name = 'CunddAjax';
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddAjax($internCall = NULL){
	// Überprüfen ob CunddSystem nicht bereits aufgerufen wurde
	if(!class_exists("CunddConfig")){
		$GLOBALS['CunddAjax'] =& $this;
		include(dirname(__FILE__).'/CunddSystem.php');
	}
		
		$this->kind = new CunddController($internCall);
	}
}
?>