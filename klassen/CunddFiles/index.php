<?php
@session_start();

// Die benötigten Klassen einbinden
require('../CunddConfig.php');
require('../CunddFiles.cpp');
require('../CunddRechte.cpp');
require('../CunddGruppen.cpp');
require('../CunddPath.php');

// Die fileId auslesen
if($_GET['fileId']){
	$fileId = $_GET['fileId'];
} else if($_GET['id']){
	$fileId = $_GET['id'];
} else {
	die('<h1>ERROR</h1>');
}

// Wenn eine fileId ermittelt wurde die Methode CunddFiles::provide_download() aufrufen 
CunddFiles::provide_download($fileId);
?>