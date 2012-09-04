<?php
error_reporting(E_ALL &~E_NOTICE);
//error_reporting(0);

date_default_timezone_set(date_default_timezone_get());

// Load the system
$GLOBALS['CunddSystem_initOptions'] = array(
	'viewMode' => 'xml',
//	'systemMode' => 'app',
);
include(dirname(__FILE__).'/../../../CunddSystem.php');


// Process the request
if($argv){
	$processHandler = Cundd::getModel('Vis/Process_Handler',$argv);
} else {
	$gridHandler = Cundd::getModel('Vis/Grid_Handler',Cundd_Request::getAllParameters());
}
?>