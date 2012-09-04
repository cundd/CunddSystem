<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Music_ColorController erweitert Cundd_Music_controllers_AbstractController.
 * @package Cundd_Music
 * @version 1.0
 * @since Feb 13, 2010
 * @author daniel
 */
class Cundd_Music_ColorController extends Cundd_Music_controllers_AbstractController{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * indexAction
	 */
	public static function indexAction(){
		CunddTools::log("COLOR CONTROLLER here".var_export(Cundd_Request::getParameters(),true));
		
		$tempPath = CunddPath::getAbsoluteTempDir();
		$fileHandler = fopen($tempPath."output",'w');
		$fileData = '';
		
		echo 'hallo world';
		
		
		for($i = 0;$i < 8;$i++){
			sleep(2);
			$fileData = time()."
			";
			fwrite($fileHandler,$fileData);
		}
		
		
		CunddTools::log("was here");
		
		/*
		$asin = Cundd_Request::getPara('asin');
		$color = Cundd::getModel('Music/Entity_Color');
		$baseUrl = CunddConfig::__('Music/image_base_url');
		$return = $color->newFromFile($baseUrl.$asin);

		// Serialize the $color-Object and save it as 'colorSpectrumPosition'
		// $return['colorSpectrumPosition'] = mysql_real_escape_string(htmlentities(serialize($color)));
		$return['colorObject'] = serialize($color);
		$mainColorArray = $color->getMostCountedColorField();
		$return['mainColor'] = $mainColorArray['fieldName'];
		
		CunddTools::pd($color);
		/* */
		return;
	}
}
?>