<?php
	session_start();
	require('../CunddCalendar.cpp');
	require('../CunddConfig.php');
	require('substitution_js.php');
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Diese Datei wird im iFrame von CunddCalendar als Source angegeben. */
	
	echo '<div style="color:#f00;">';
	/*
	echo 'DU';
	echo '<pre>'; echo var_dump($_POST); echo '</pre>';
	echo '<pre>'; echo var_dump($_GET); echo '</pre>';
	
	echo '<pre>'; echo var_dump($_SESSION); echo '</pre>';
	
	error_reporting(E_ALL);
	echo 'DB';
//	echo CunddCalendar::render();
	echo 'GH';
	 /* */
	$pk = new CunddCalendar('render');
	echo 'DU';
//	$pk->render();
	echo '<pre>'; echo var_dump($_SESSION); echo '</pre>';
	
?>