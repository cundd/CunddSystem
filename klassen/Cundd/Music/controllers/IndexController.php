<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Music_IndexController erweitert Cundd_Music_controllers_AbstractController.
 * @package Cundd_Music
 * @version 1.0
 * @since Jan 24, 2010
 * @author daniel
 */
class Cundd_Music_IndexController extends Cundd_Music_controllers_AbstractController{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * indexAction
	 */
	public static function indexAction(){		
		$collection = self::_getCollection();
		$children = $collection->getChildren();
		
		
		if($children){
			foreach($children as $key => $child){
				$args = array();
				$args['asin'] = $child->getValue('_asin');
				$args['dimension'] = 100;
				$args['title'] = $child->getValue('_title');
				$args['artist'] = $child->getValue('_artist');
				// CunddTools::pd($child);
				$args['alt'] = $child->getValue('_title');
				if($args['asin']){
					$lastChildWithAsin = $child;
					$artwork = Cundd::getModel('Music/Entity_Artwork',$args);
					$artwork->printOutput();
				}
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * listAction
	 */
	public static function listAction(){
		$collection = self::_getCollection();
		$children = $collection->getChildren();
		$properties = array('_artist','_album','_label','_designer','_origin','_genre','_tracksCol','_trackcount',
				'_date','_title','_rating','_catalogId','_barcode','_format','_diskId','_asin');
		
		if($children){
			$tableInput = array();
			foreach($children as $key => $child){
				$tableInput[$key] = array();
				foreach($properties as $property){
					$propertyWithout_ = str_replace('_','',$property);
					$tableInput[$key][$propertyWithout_] = $child->getValue($property);
				}
			}
			
			
			
			$table = CunddTemplate::showTable($tableInput,'beatles',array(),true,null);
			
			/*
			foreach($children as $key => $child){
				$args = array();
				$args['asin'] = $child->getValue('_asin');
				$args['dimension'] = 100;
				if($args['asin']){
					$lastChildWithAsin = $child;
					$artwork = Cundd::getModel('Music/Entity_Artwork',$args);
					$artwork->printOutput();
					// echo '<br />';
				}
			}
			/* */
		} 
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * changereferenceAction
	 */
	public static function changereferenceAction(){
		self::_setReference();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * savenewreleaseAction wird als Process aufgerufen wenn neue Daten ermittelt und in die 
	 * Datenbank geschrieben werden sollen. */
	public static function savenewreleaseAction(){
		echo '
		
		
		go
		
		
		';
		$data = Cundd_Request::getPara('data');
		
		CunddTools::pd($data);
		
		
		CunddTools::log(__CLASS__.'::'.__FUNCTION__.' '.var_export($data,true));
		$result = array();
		
		$cunddAdapter = Cundd::getModel('Music/Adapter_Cundd');


		for($i = 0;$i < count($data);$i++){
			$dataEntity = $data[$i];
			$result[] = $cunddAdapter->insertReleaseIfNotExists($dataEntity);
		}


		return $result;
	}
}
?>