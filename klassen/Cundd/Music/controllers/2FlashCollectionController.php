<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Music_FlashCollectionController erweitert Cundd_Music_AbstractController.
 * @package Cundd_Music
 * @version 1.0
 * @since Jan 21, 2010
 * @author daniel
 */
class Cundd_Music_FlashCollectionController extends Cundd_Music_controllers_AbstractController{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * indexAction
	 */
	public static function indexAction(){
		ob_start();
			
			$collection = self::_getCollection();
			$children = $collection->getChildren();
			// $children = $x->getChildren();
			// CunddTools::pd($children);
			
			/*
			 * SETTINGS
			 * $config['flash_maxCols'] = 5;
			 * $config['flash_maxRows'] = NULL;
			 * $config['flash_spaceBetweenAlbums'] = 8;
			 * $config['flash_albumWidth'] = 200;
			 * $config['flash_albumHeight'] = 200;
			 * $config['flash_objectType'] = 'Cundd.GraphicObjects.UrlLoading.Cundd_GraphicObjects_UrlLoading_Rect';
			 */
			
			$xOffset = 0;
			$colCounter = 0;
			$row = 0;
			$allFlashObjects = array();
			
			if($children){
				foreach($children as $key => $child){
					$args = array();
					$args['asin'] = $child->getValue('_asin');
					
					
					if($args['asin']){
						if($colCounter > CunddConfig::__('Music/flash_maxCols')){
							$row++;
							$xOffset = 0;
							$colCounter = 0;
						}
						
						
						$data = array(
						'x' => $xOffset,
						'y' => $row * ( CunddConfig::__('Music/flash_albumHeight') + CunddConfig::__('Music/flash_spaceBetweenAlbums') ),
						'width' => CunddConfig::__('Music/flash_albumWidth'),
						'height' => CunddConfig::__('Music/flash_albumHeight'),
						'type' => CunddConfig::__('Music/flash_objectType'),
						'url' => CunddConfig::__('Music/image_base_url') . $args['asin'],
						);
						$allFlashObjects[] = Cundd::getModel('Flash',$data);
						
						
						$xOffset = $xOffset + CunddConfig::__('Music/flash_albumWidth') + CunddConfig::__('Music/flash_spaceBetweenAlbums');
						$colCounter++;
					}
				}
			} else {
				// die("No children");
			}
			
			
			$lastObject = $allFlashObjects[count($allFlashObjects) - 1];
			
			// CunddTools::pd($allFlashObjects);
			
			
			//$collection = $lastObject->getCollection();
			$collection = Cundd::getCollection('Flash');
			
			
			$collection->objects=$allFlashObjects;
			
			
			$collection->render();
			
		$output = ob_get_contents();
		ob_end_clean();
		
		header("Content-Type:application/xml");
//		header('Content-Type: text/html; charset=utf-8');
//		header("Content-Type:text/xhtml");
		
		$output = CunddTools::xmlCleanup($output);
		echo $output;
		return $output;
	}
}
?>