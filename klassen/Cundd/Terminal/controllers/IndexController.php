<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Terminal_IndexController erweitert Cundd_Terminal_controllers_AbstractController.
 * @package Cundd_Terminal
 * @version 1.0
 * @since Feb 5, 2010
 * @author daniel
 */
class Cundd_Terminal_IndexController extends Cundd_Terminal_controllers_AbstractController{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * indexAction
	 */
	public static function indexAction(){
		if(!CunddLogin::isLoggedIn()){
			CunddLogin::checkIfLoggedInElseShowForm();
		} else if(Cundd_Terminal_Model_Terminal::isPermitted()){
			$data = array('_newCall' => Cundd_Request::getPara('newCall'));
			$data['mode'] = Cundd_Request::getPara('mode');
			$model = Cundd::getModel('Terminal',$data);
			
			$viewArgs = array('model' => &$model);
			$view = Cundd::getView('Terminal',$viewArgs);
			echo $view->render();
		} else {
//		    echo 'Not permitted';
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode löscht die History. */
	public static function clearAction(){
		if(!CunddLogin::isLoggedIn()){
			CunddLogin::checkIfLoggedInElseShowForm();
		} else if(Cundd_Terminal_Model_Terminal::isPermitted()){
			$data = array('_newCall' => Cundd_Request::getPara('newCall'));
			$data['mode'] = Cundd_Request::getPara('mode');
			$model = Cundd::getModel('Terminal',$data);
			
			$model->clearHistory();
			
			$viewArgs = array('model' => &$model);
			$view = Cundd::getView('Terminal',$viewArgs);
			echo $view->render();
		}
	}
}
?>