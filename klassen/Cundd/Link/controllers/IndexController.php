<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Link_IndexController extends Cundd_Core_controllers_AbstractController.
 * @package Cundd_File
 * @version 1.0
 * @since Feb 4, 2010
 * @author daniel
 */
class Cundd_Link_IndexController extends Cundd_Core_controllers_AbstractController{
	const CURRENT_LINK_TABLE_PARA_KEY = 'linkTable';
	
	public function indexAction(){
		
	}
	
	/**
	 * List all links.
	 * @return void
	 */
	public function listAction(){
		$linkTableName = Cundd_Request::getPara(self::CURRENT_LINK_TABLE_PARA_KEY);
		if(!$linkTableName) return;
		$linksObj = new CunddLink($linkTableName,false,false,true);
		$links = $linksObj->getLinks();
		
		CunddTemplate::showTable($links,'links');
	}
	
	/**
	 * Show the form for a new link, if a user is logged in.
	 * @return void
	 */
	public function newAction(){
		CunddTools::pd(Cundd_Request::getPara());
		
		if(Cundd_Request::getPara('insert')){
			echo "Hallo";
			self::insertAction();
		}
		
		if(!CunddUser::isLoggedIn()){
			CunddLogin::checkIfLoggedInElseShowForm();
			return;
		}
		
		$inputs = array(
			array('name' => 'table', 	'type' => 'text',),
			array('name' => 'name', 	'type' => 'text',),
			array('name' => 'aktiv', 	'type' => 'checkbox',),
			array('name' => 'link', 	'type' => 'text',),
			array('name' => 'rechte', 	'type' => 'text',),
			array('name' => 'insert', 	'type' => 'hidden',	'options' => '1'),
		);
		
		
		$action = 'Link/Index/insert';
		$action = 'POST:SELF';
		$form = new CunddForm($inputs, $action);
		$form->render();
	}
	
	/**
	 * Inserts a new link.
	 * @return void
	 */
	public function insertAction(){
		if(!Cundd_Request::getPara('table')){
			echo "Please specifiy a table name (the name of the navigation).";
			return;
		}
		
		
		$nav = new CunddLink(Cundd_Request::getPara('table'));
		$parameters = Cundd_Request::getAllParameters();
		
		if(Cundd_Request::getPara('aktiv')) $parameters['aktiv'] = (integer) $parameters['aktiv'];
		
		
		if($nav->newLinkRecord($parameters) === false){
			echo "Couldn't create link record.";
		}
	}
}
	