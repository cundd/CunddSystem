<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Gallery_Model_Image erweitert Cundd_Gallery_Model_Abstract.
 * @package Cundd_Gallery_Model_Abstract
 * @version 1.0
 * @since Feb 6, 2010
 * @author daniel
 */
class Cundd_Gallery_Model_Image extends Cundd_Gallery_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $path = '';
	public $newPath = '';
	public $binary = NULL;
	public $scaleMode = 0;
	public $size = 0;
	
	
	
	protected $_binary = NULL;
	protected $_data = array();
	protected $_newWidth = 0;
	protected $_newHeight = 0;
	protected $_autoSave = false;
	
	
	
	protected $_debug = false;
	
	
	
	const SCALE_MODE_SHORT_SIDE_MIN 	= 1;
	const SCALE_MODE_MAX_WIDTH 			= 2;
	const SCALE_MODE_MAX_HEIGHT 		= 3;
	const SCALE_MODE_MIN_WIDTH 			= 4;
	const SCALE_MODE_MIN_HEIGHT 		= 5;
	const SCALE_MODE_WIDTH_TO 			= 6;
	const SCALE_MODE_HEIGHT_TO 			= 7;
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	protected function _construct(array $arguments = array()){
		$this->_registerProperties($arguments);
		if($this->path) $this->_initWithPath();
		
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode lädt eine Datei mittels des Pfads. */
	protected function _initWithPath(){
		if(file_exists($this->path)){ // Wenn die Datei-Existiert
			/*
			$handle = fopen($filename, "r");
			$this->_binary = fread($handle, filesize($filename));
			fclose($handle);
			/* */
			$this->_readImageData();
			$this->_scale();
		} else if($this->_debug){
			$this->throwE("File $this->path doesn't exist.");
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest die Daten des Bildes mittels getimagesize() und speichert diese in 
	 * der Eigenschaft $_data. Darüberhinaus werden die Elemente für width, height und 
	 * IMAGETYPE_XXX mit entsprechenden Keys gespeichert. */
	protected function _readImageData(){
		$this->_data = getimagesize($this->path);
		$this->_data['width'] = $this->_data[0];
		$this->_data['height'] = $this->_data[1];
		$this->_data['IMAGETYPE_XXX'] = $this->_data[2];
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob die nötigen Parameter zum Skalieren vorhanden sind und ruft 
	 * die Methoden zum Skalieren auf. */
	protected function _scale(){
		if($this->scaleMode AND $this->_getRelevantLength()){
			$this->_prepareSize();
			if($this->_scaleImage() AND $this->_autoSave){
				$this->save();
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode speichert das Objekt. Wenn die Eigenschaft $newPath gesetzt ist wird die 
	 * Datei in diesem Pfad gespeichert, ansonsten wird versucht die ursprüngliche Datei zu 
	 * überschreiben.
	 * @param string $path[optional]
	 * @return boolean
	 */
	public function save($path = NULL){
		$saved = true;
		if($path){
			
		} else if($this->newPath){
			$path = $this->newPath;
		} else {
			$path = $this->path;
		}
		
		// Das Bild im Original-Datei-Format speichern
		switch($this->getData('mime')) {
			case 'image/jpeg':
			case 'image/pjpeg': //wegen IE
				$saved *= imagejpeg($this->binary, $path,$this->_config('image_jpegQuality'));
				break;
			case 'image/png':
				$saved *= imagepng($this->binary, $path);
				break;
			case 'image/gif':
				$saved *= imagegif($this->binary, $path);
				break;
			default:
				$saved *= imagepng($this->binary, $path);
				CunddTools::log('CunddGalerie','Saved image as PNG.');
		}
		return $saved;
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode bereitet die Maße zum Skalieren vor. */
	protected function _prepareSize(){
		// Überprüfen ob das Bild sogar ein Quadrat ist
		if($this->getData('width') == $this->getData('height')){ // Square
			$this->_newWidth = $this->_getRelevantLength();
			$this->_newHeight = $this->_getRelevantLength();
			return;
		}
		
		switch($this->scaleMode){
			case self::SCALE_MODE_SHORT_SIDE_MIN:
				// Get the short side
				if($this->getData('width') < $this->getData('height')){
					$this->_newWidth = $this->_getRelevantLength();
					$this->_newHeight = $this->_calculateNewHeight($this->_newWidth);
				} else if($this->getData('width') > $this->getData('height')){
					$this->_newHeight = $this->_getRelevantLength();
					$this->_newWidth = $this->_calculateNewWidth($this->_newHeight);
				}
				break;
				
			case self::SCALE_MODE_MAX_WIDTH:
				if($this->getData('width') > $this->_getRelevantLength()){
					$this->_newWidth = $this->_getRelevantLength();
					$this->_newHeight = $this->_calculateNewHeight($this->_newWidth);
				}
				break;
				
			case self::SCALE_MODE_MAX_HEIGHT:
				if($this->getData('height') > $this->_getRelevantLength()){
					$this->_newHeight = $this->_getRelevantLength();
					$this->_newWidth = $this->_calculateNewWidth($this->_newHeight);
				}
				break;
				
			case self::SCALE_MODE_WIDTH_TO:
				$this->_newWidth = $this->_getRelevantLength();
				$this->_newHeight = $this->_calculateNewHeight($this->_newWidth);
				break;
				
			case self::SCALE_MODE_MIN_WIDTH:
				if($this->getData('width') < $this->_getRelevantLength()){
					$this->_newWidth = $this->_getRelevantLength();
					$this->_newHeight = $this->_calculateNewHeight($this->_newWidth);
				}
				break;
				
			case self::SCALE_MODE_MIN_HEIGHT:
				if($this->getData('height') < $this->_getRelevantLength()){
					$this->_newHeight = $this->_getRelevantLength();
					$this->_newWidth = $this->_calculateNewWidth($this->_newHeight);
				}
				break;
				
			case self::SCALE_MODE_HEIGHT_TO:
				$this->_newHeight = $this->_getRelevantLength();
				$this->_newWidth = $this->_calculateNewWidth($this->_newHeight);
				break;
				
			default:
				break;
		}
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt den Wert auf den hin skaliert werden soll.
	 * @return number
	 */
	protected function _getRelevantLength(){
		return (integer) $this->size;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode skaliert das gegebene Bild.
	 * @return resource
	 */
	protected function _scaleImage(){
		$say = false;
		switch($this->getData('mime')){
			case 'image/jpeg':
			case 'image/pjpeg': //wegen IE
				if($say) echo 'jpeg';
				$original = imagecreatefromjpeg($this->path);
				break;
			case 'image/png':
				if($say) echo 'png';
				$original = imagecreatefrompng($this->path);
				break;
			case 'image/gif':
				if($say) echo 'gif';
				$original = imagecreatefromgif($this->path);
				break;
			default:
				if($say) echo 'Couldn\'t detect image type';
				CunddTools::log('CunddGalerie','Could not detect mime-type of new file. Mime-type detected as:'.$filedata['type'].'.');
		}
		
		
		
		if($original) {
			//Erstellen der Bühne
			$newStage = imagecreatetruecolor($this->_newWidth, $this->_newHeight);
			
			//Bild skalieren und auf Bühne aufbringen
			if(imagecopyresampled($newStage, $original, 0, 0, 0, 0, $this->_newWidth, $this->_newHeight, $this->getData('width'), $this->getData('height'))){
				// Bild wurde skaliert
			} else {
				$msg = 'Cound not resample image';
				CunddTools::error(__CLASS__,$msg);
				throw new Exception($msg);
			}
			
			
			$this->binary = &$newStage;
			return $newStage;
		} else {
			$msg = 'Cound not load image';
			CunddTools::error(__CLASS__,$msg);
			throw new Exception($msg);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die proportionale Höhe bei definierter Breite.
	 * @param int $newWidth
	 * @return array 
	 */
	protected function _calculateNewHeight($newWidth){
		$return = self::getSizeWithWidth($newWidth,$this->getData('width'),$this->getData('height'));
		return $return['height'];
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die proportionale Breite bei definierter Höhe.
	 * @param int $newHeight
	 * @return array 
	 */
	protected function _calculateNewWidth($newHeight){
		$return = self::getSizeWithHeight($newHeight,$this->getData('width'),$this->getData('height'));
		return $return['width'];
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt alle Daten des Bildes zurück. Wenn ein Name übergeben wurde wird der
	 * spezifische Wert zurückgegeben.
	 * @param string $key
	 * @return string
	 */
	public function getData($key = NULL){
		$return = false;
		if(func_num_args() > 0){
			if(array_key_exists($key,$this->_data)){
				$return = $this->_data[$key];
			}
		} else {
			$return = $this->_data;
		}
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// 
	// STATIC
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW

	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die proportionale Höhe bei definierter Breite.
	 * @param int $newWidth
	 * @param int $oldWidth
	 * @param int $oldHeight
	 * @return array number 
	 */
	public static function getSizeWithWidth($newWidth,$oldWidth,$oldHeight){
		$aspectRatio = $oldWidth/$oldHeight;
		$newHeight = $newWidth / $aspectRatio;
		return array('width' => $newWidth, 'height' => $newHeight,'aspectRatio' => $aspectRatio);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die proportionale Breite bei definierter Höhe.
	 * @param int $newHeight
	 * @param int $oldWidth
	 * @param int $oldHeight
	 * @return array number 
	 */
	public static function getSizeWithHeight($newHeight,$oldWidth,$oldHeight){
		$aspectRatio = $oldWidth/$oldHeight;
		$newWidth = $newHeight * $aspectRatio;
		return array('width' => $newWidth, 'height' => $newHeight);
	}
}
?>