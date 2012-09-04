<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Vis_Model_Grid_Caller erweitert Cundd_Core_Simple.
 * @package Cundd_Vis
 * @version 1.0
 * @since Feb 16, 2010
 * @author daniel
 */
class Cundd_Vis_Model_Grid_Caller extends Cundd_Core_Simple{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	// const GRID_CONTROLLER = 'Vis/Grid';
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		$server = $this->_config('grid_server_url');
		$account = $this->_config('grid_server_my_account');
		$password = $this->_config('grid_server_my_password');
		
		$remoteController = $arguments[Cundd_Vis_Model_Abstract::GRID_REMOTE_CONTROLLER_VAR_PREFIX];
		
		
		$originalData = Cundd::registry(Cundd_Vis_Model_Grid_Dispatcher::PROCESS_DATA_PREFIX.'parameter');
		
		// Create the request-string
		if($this->_config('grid_server_controllerPath')){
			$requestString = $server.'/'.$this->_config('grid_server_controllerPath');
		} else {
			$requestString = $server.'/'.CunddPath::getRelativeModulUrl('Vis').'/Controller.php';
		}
		
		// Add the grid-controller
		$requestString .= '?1='.rawurlencode($remoteController).'&';
		
		// Add the data
		$requestString .= '2='.$originalData;
		
		// Add the account information
		$requestString .= "3=$account&4=$password&";
		
		
		$homepage = file_get_contents($requestString);
		CunddTools::log(__CLASS__.' request='.$requestString);
		
		echo $homepage;
	}
}
?>