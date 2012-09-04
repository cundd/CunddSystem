<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Music_controllers_AbstractController erweitert Cundd_Core_controllers_AbstractController.
 * @package Cundd_Music
 * @version 1.0
 * @since Jan 24, 2010
 * @author daniel
 */
class Cundd_Music_controllers_AbstractController extends Cundd_Core_controllers_AbstractController{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected static $debug = false;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die passenden Objekte. */
	protected static function _getCollection(){
		set_time_limit(0);
		if(!$model = $_GET['model']){
			$model = 'Release';
		}
		$arguments = Cundd_Request::getPara();
		
		/*
		$session = new Cundd_Session();
		$bing = $session->get('bing') + 1;
		echo $bing;
		$session->set('bing',$bing);
		//CunddTools::pd(unserialize(implode($session->get())));
		/* */
		
		// Das Referenzalbum lesen
		$reference = self::_getReference();
		
		
		if(!$reference->isEmpty()){
			$filterString = '';
			if(array_key_exists('filter',$arguments)){
				$filterString = $arguments['filter'];
			} else if(array_key_exists('filterString',$arguments)){
				$filterString = $arguments['filterString'];
			} else {
				// $filterString = 'genre+origin+designer+year+color'; // genre+origin+designer+year+color
			}
			
			/*
			if(!$filterString){
				$filterString = 'artist';
				
			}
			/* */
			
			// Filter auslesen
			$filterArguments = array('filterString' => $filterString,'source' => $reference);
			$filter = Cundd::getModel('Music/Filter',$filterArguments);
			
			// CunddTools::pd($filter->getFilter());
			
			$arguments = $filter->mergeWith($arguments);
			
			CunddTools::log('Music_controllers_Abstract',$arguments);
			
			$x = Cundd::getModel('Music/Collection_'.$model,$arguments);
		} else {
			$x = Cundd::getModel('Music/Collection_'.$model,$arguments);
			if($x->getChildren(0)){
				self::_setReference($x->getChildren(0));
			} else {
				CunddTools::debug(NULL,2);
			}
		}
		
		return $x;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt eine neue Referenz.
	 * @return Cundd_Music_Model_Reference
	 */
	protected static function _setReference($newRefObject = null){
		$reference = NULL;
		if(!$newRefObject){
			$arguments = Cundd_Request::getPara();
			
			$newRefObjectCol = Cundd::getModel('Music/Collection_Release',$arguments);
			$newRefObject = $newRefObjectCol->getChildren(0);
		}
		
		if($newRefObject){
			$reference = Cundd::getModel('Music/Reference',array($newRefObject));
		} else {
			/* DEBUGGEN */if(self::$debug) echo 'No reference set.';/* DEBUGGEN */
		}
		return $reference;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt das Referenc-Album zurück.
	 * @return Cundd_Music_Model_Reference
	 */
	protected function _getReference(){
		$ref = Cundd::getModel('Music/Reference');
//		if(!$ref->wasLoaded()){
//			$ref = $ref->load();
//		}
		return $ref;
	}
}
?>