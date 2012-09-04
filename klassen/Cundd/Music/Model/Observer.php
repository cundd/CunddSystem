
<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Observer erweitert Cundd_Core_Model_Observer.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 14, 2009
 * @author daniel
 */
class Cundd_Music_Model_Observer extends Cundd_Core_Model_Observer{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Event-Handler für 'gotNewRelease'.
	*/
	public function gotNewReleases(array $event){
		$process = Cundd::process();
		$process->parameters = $event;
		$process->controller = 'Music/Index/savenewrelease';
		$process->dispatch();
		/*
		CunddTools::wp('disp'.$process->getPid());
		CunddTools::pd($process->getCommand());
		/*
		
		$result = array();
		$data = $event['data'];
		$cunddAdapter = Cundd::getModel('Music/Adapter_Cundd');


		for($i = 0;$i < count($data);$i++){
			$dataEntity = $data[$i];
			$result[] = $cunddAdapter->insertReleaseIfNotExists($dataEntity);
		}


		return $result;
		/* */
	}
}
?>