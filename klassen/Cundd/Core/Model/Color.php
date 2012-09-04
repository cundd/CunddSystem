<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Core_Model_Color erweitert Cundd_Core_Model_Abstract.
 * @package Cundd_Core
 * @version 1.0
 * @since Dec 15, 2009
 * @author daniel
 */
class Cundd_Core_Model_Color extends Cundd_Core_Simple{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_red = 0;
	protected $_green = 0;
	protected $_blue = 0;
	protected $_alpha = 0;
	protected $_colors = array();
	protected $_fileName = '';
	protected $_fileBinary;
	protected $_imageWidth = 0;
	protected $_imageHeight = 0;
	protected $_imageMimeType = '';
	protected $_colorFields;
	protected $_foundColorFields = array();
	protected $_percentPerColorField = array();
	protected $_countedColorFields = array();
	
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode lädt eine Bilddatei und ermittelt das Vorkommen von verschiedenen Farb-
	 * gruppen innerhalb des Bildes.
	 * @param string $file
	 * @return array
	 */
	public function newFromFile($file){
		$this->_fileName = $file;
		
		if($this->_loadFile()){
			return $this->_getColors();
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode lädt eine Datei und speichert die Binärdaten in der Eigenschaft 
	 * $_fileBinary.
	 * @return boolean|image resource identifier
	 */
	protected function _loadFile(){
		$say = false;
		
		$file = $this->_fileName;
		$fileData = getimagesize($file);
		// $this->pd($fileData);
		
		list($this->_imageWidth, $this->_imageHeight, $type, $attr) = $fileData; 
		$this->_imageMimeType = $fileData['mime'];
		
		switch($this->_imageMimeType){
			case 'image/jpeg':
			case 'image/pjpeg': //wegen IE
				if($say) echo 'jpeg<br />';
				$fileBinarydata = imagecreatefromjpeg($file);
				break;
			case 'image/png':
				if($say) echo 'png<br />';
				$fileBinarydata = imagecreatefrompng($file);
				break;
			case 'image/gif':
				if($say) echo 'gif<br />';
				$fileBinarydata = imagecreatefromgif($file);
				break;
			default:
				if($say) echo "Couldn\'t detect image type. Recognized as $mimeType.";
				$fileBinarydata = (bool) false;
				CunddTools::log('Cundd_Core_Model_Color','Could not use mime-type of new file. Mime-type detected as:'.$mimeType.'.');
		}
		
		$this->_fileBinary = $fileBinarydata;
		return $fileBinarydata;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode scannt ein Bild mit einer durch die Konfiguration definierte Anzahl an 
	 * Reihen und Spalten. Die Farbe jedes dieser Scan-Punkte wird ermittelt und auf sein
	 * Vorkommen in einem Hue-Lightness-Koordinatensystem hin überprüft. Dieses Koordinaten-
	 * system ist in verschiedene Felder unterteilt; die Namen der entsprechenden Felder, 
	 * pro Scanpixel werden ermittelt und anschließend die Verteilung der Treffer pro Farb-
	 * feld ausgewertet. Zurückgegeben wird ein assoziatives Array mit allen Farbfeldern 
	 * und deren prozentuellen Präsenz in der Bilddatei.
	 * @return array
	 */
	protected function _getColors(){
		list($scanRows,$scanColumns) = $this->_getScanSettings();
		$deltaX = floor($this->_imageWidth  / $scanColumns); 
		$deltaY = floor($this->_imageHeight / $scanRows); 
		
		$fields = array();
		$k = 0;
		// Scan x-Direction
		for($i = 0;$i < $scanColumns;$i++){
			
			// Scan y-Direction
			for($j = 0;$j < $scanRows;$j++){
				// get a color
				$x = $i * $deltaX;
				$y = $j * $deltaY;
				$color = $this->_getColorAt($x,$y);
				$rgb = $color['rgb'];
				$hsl = $color['hsl'];
				
				$fields[] = $this->_determineField($hsl);
				$this->_colors[] = $color;
			}
		}
		$this->_foundColorFields = $fields;
		$this->_percentPerColorField = $this->_evaluateFields();
		
		
		return $this->_percentPerColorField;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wertet die ermittelten Farbfelder aus.
	 * @return array
	 */
	protected function _evaluateFields(){
		$countArray = array();
		$return = array();
		$foundColorFields = $this->_foundColorFields;
		
		// Zählen
		$checkedFields = count($foundColorFields);
		foreach($foundColorFields as $key => $colorField){
			if(is_numeric($colorField) OR gettype($colorField) == 'string'){
				if(array_key_exists($colorField,$countArray)){
					$countArray[$colorField] = $countArray[$colorField] + 1;
				} else {
					$countArray[$colorField] = 1;
				}
			}
		}
		
		$this->_countedColorFields = $countArray;
		
		// Auswerten
		$foundFields = count($countArray);
		foreach($this->_colorFields as $fieldName => $value){
			if(array_key_exists($fieldName,$countArray)){
				// $checkedFields = 100%
				// $countArray[$fieldName] = gezählt
				// $checkedFields/$countArray[$fieldName] * 100 = prozent
				
				// $return[$fieldName] = $checkedFields/$countArray[$fieldName] * 100;
				// $return[$fieldName] = (int) floor($checkedFields/$countArray[$fieldName]);
				// $return[$fieldName] = (int) floor($foundFields/$countArray[$fieldName] * 100);
				$return[$fieldName] = $countArray[$fieldName]/$checkedFields * 100;
			} else {
				$return[$fieldName] = 0;
			}
		}
		
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode . */
	public function getMiddleColorOfColorField($input){
		die('DOESNT WORK');
		// Den Mode ermitteln
		if(is_numeric($input)){
			$mode = 'fieldId';
		} else if(gettype($input) == 'string'){
			$mode = 'fieldName';
		}
		
		$colorFields = $this->_getColorFields();
		$i = 0;
		$matchingColorField = NULL;
		foreach($colorFields as $key => $colorField){
			switch($mode){
				case 'fieldId':
					if($i == $input) $matchingColorField = $colorField;
					break;
				case 'fieldName':
					if($key == $input) $matchingColorField = $colorField;
					break;
			}
			if($matchingColorField) break;
			$i++;
		}
		
		
		$this->pd($matchingColorField);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt das meist gefundene Farb-Feld des Bildes.
	 * @return array
	 */
	public function getMostCountedColorField(){
		$elementValue = max($this->_countedColorFields);
		$elementKey = array_search($elementValue,$this->_countedColorFields);
		
		$element['fieldAmount'] = $elementValue;
		$element['fieldName'] = $elementKey;
		$element[$elementKey] = $elementValue;
		
		return $element;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Eigenschaft $_countedColorFields zurück.
	 * @return array
	 */
	public function getCountedColorFields(){
		$countedOnly = $this->_countedColorFields;
		
		$colorFields = array();
		foreach($this->_getColorFields() as $fieldName => $colorField){
			$target =& $colorFields[$fieldName];
			$set = $this->_setIfKeyExists($fieldName,$countedOnly,$target);
			if(!$target) $target = 0;
		}
		arsort($colorFields);
		return $colorFields;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die Farbe an der angegebenen Position und gibt diese in einem 
	 * assoziativen Array mit den Keys 'rgb' und 'hsl' und den entsprechenden Werten zurück.
	 * @param int $x
	 * @param int $y
	 * @return array 
	 */
	protected function _getColorAt($x,$y){
		$say = false;
		
		$colorIndex = imagecolorat($this->_fileBinary, $x, $y);
		$color = imagecolorsforindex($this->_fileBinary, $colorIndex);
		$hsl = $this->_rgb2hsl($color);
		
		// DEBUGGEN
		if($say){
			echo $colorIndex.'<br>';
			echo 'RGB-Wert für rot: ' . $color[red] . '<br>';
			echo 'RGB-Wert für grün: ' . $color[green] . '<br>';
			echo 'RGB-Wert für blau: ' . $color[blue] .'<br>';
			echo 'HUE: ' . $hsl['h'] . '<br>';
			echo 'SATURATION: ' . $hsl['s'] . '<br>';
			echo 'Lightness: ' . $hsl['l'] .'<br><br>';
		}
		// DEBUGGEN
		
		return array('rgb' => $color,'hsl' => $hsl);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt zu welchem Farbfeld eine Farbe gehört. Die Einstellungen der 
	 * Farbfelder werden in der Konfiguration vorgenommen.
	 * DER ABLAUF:
	 * Zuerst wird ermittelt ob die Farbe 'grau' ist. Ausschlaggebend ist dabei der Schwell-
	 * wert der Saturation (Color__gray_treshold).
	 * Im nächsten Schritt wird die Farbe innerhalb eines Hue-Lightness-Koordinatiensystems
	 * lokalisiert (ist die Farbe als 'grau' erkannt entfällt hier die Hue-Achse). Dieses 
	 * Koordinatensystem ist in die gewünschten Felder unterteilt. Nun wird ermittelt in 
	 * welchem der Felder sich die Farbe befindet. Diese Information wird dann zurück-
	 * gegeben.
	 * 
	 * DIE DEFINITION DER FELDER:
	 * Die einzelnen Farbfelder werden über zwei Punkte innerhalb des Hue-Lightness-Koordi-
	 * natiensystems definiert: Den Startpunkt (minL und minH -> links unten) und den End-
	 * punkt (maxL und maxH -> rechts oben).
	 * Mögliche Formen der Deklaration sind:
	 * 1. array( 'start' => array(H,L) , 'end' => array(H,L) )
	 * 2. array( 'minH' => value1 , 'minL' => value2 , 'maxH' => value3 , 'maxL' => value4 )
	 * oder
	 * 3. array( valueMinH , valueMinL , valueMaxH , valueMaxL )
	 * Jedes Feld wiederum wird in einem separaten Element des Konfigurationsarrays 
	 * 'Color__colorFields' definiert.
	 * Die Angabe der Werte erfolgt in %; 100 stellt damit das Maximum dar.
	 * 
	 * Graufelder:
	 * Felder für Grauwerte können ebenfalls definiert werden. Dazu werden pro Feld nur die 
	 * Werte für minLightness und maxLightness gesetzt. Hilfreich ist das setzen des ersten 
	 * Wertes des Farbfeld-Arrays auf 'gray' somit wird das Feld direkt als Graufeld erkannt. 
	 * Die Einordnung in ein Feld geschieht bei Graufeldern allein aufgrund des Lightness-
	 * Wertes.
	 * @param array $hsl
	 * @return string|string|false
	 */
	protected function _determineField(array $hsl){
		$say = false;
		
		$hue = 100 * $hsl['h'];
		$saturation = 100 * $hsl['s'];
		$lightness = 100 * $hsl['l'];
		
		$colorFields = $this->_getColorFields();
		// $this->pd($colorFields);
		
		// Step 1: Check if it is gray
		$isGray = $saturation <= CunddConfig::__('Color__gray_treshold');
		
		
		// Step 2: Get the field
		if($isGray){
			/* DEBUGGEN */if($say) echo 'is Gray ';/* DEBUGGEN */
			foreach($colorFields as $fieldName => $colorField){
				if(array_search('gray',$colorField)){
					if($this->_isInGrayField($lightness,$colorField)) return $fieldName;
				}
			}
		} else {
			/* DEBUGGEN */if($say) echo 'is not Gray ';/* DEBUGGEN */
			foreach($colorFields as $fieldName => $colorField){
				if($this->_isInField($hue,$lightness,$colorField)) return $fieldName;
			}
		}
		
		
		return (bool) false;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob die Farbe (definiert durch die übergebenen Werte $hue und 
	 * $lightness) im übergebenen $colorField enthalten sind.
	 * @param number $hue
	 * @param number $lightness
	 * @param array $colorField
	 * @return boolean
	 */
	protected function _isInField($hue,$lightness,array $colorField){
		// $this->pd($colorField);
		if(	$lightness 	>	$colorField['minL'] AND 
			$lightness 	<=	$colorField['maxL'] AND 
			$hue		>	$colorField['minH'] AND 
			$hue		<=	$colorField['maxH']
		){
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob der Grauwert (definiert durch den übergebenen Wert 
	 * $lightness, im übergebenen $colorField enthalten sind.
	 * @param number $hue
	 * @param number $lightness
	 * @param array $colorField
	 * @return boolean
	 */
	protected function _isInGrayField($lightness,array $colorField){
		if(	$lightness 	>	$colorField['minL'] AND 
			$lightness 	<=	$colorField['maxL']
		){
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* (non-PHPdoc)
	 * @see Cundd/klassen/Cundd/Core/Model/Cundd_Core_Model_Abstract#_isMutable()
	 */
	protected function _isMutable(){
		return true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt ein Array mit der Anzahl der zu scannenden Reihen und Spalten des 
	 * Rasterbildes zurück.
	 * @return array
	 */
	abstract protected function _getScanSettings();
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Function taken from hofstadler.andi@gmx.at at 
	 * http://www.php.net/manual/en/function.imagecolorsforindex.php#86198.
	 * Thanks a lot tho hofstadler.andi@gmx.at!
	 * /
	/**
	 * Convert RGB colors array into HSL array
	 * 
	 * @param array $ RGB colors set, each color component with range 0 to 255
	 * @return array HSL set, each color component with range 0 to 1
	 */
	protected function _rgb2hsl(array $rgb){
	    /*
	     * $clrR = ($rgb[0]);
	     * $clrG = ($rgb[1]);
	     * $clrB = ($rgb[2]);
	     */
		$clrR = ($rgb['red']);
	    $clrG = ($rgb['green']);
	    $clrB = ($rgb['blue']);
	     
	    $clrMin = min($clrR, $clrG, $clrB);
	    $clrMax = max($clrR, $clrG, $clrB);
	    $deltaMax = $clrMax - $clrMin;
	     
	    $L = ($clrMax + $clrMin) / 510;
	     
	    if (0 == $deltaMax){
	        $H = 0;
	        $S = 0;
	    }
	    else{
	        if (0.5 > $L){
	            $S = $deltaMax / ($clrMax + $clrMin);
	        }
	        else{
	            $S = $deltaMax / (510 - $clrMax - $clrMin);
	        }
	
	        if ($clrMax == $clrR) {
	            $H = ($clrG - $clrB) / (6.0 * $deltaMax);
	        }
	        else if ($clrMax == $clrG) {
	            $H = 1/3 + ($clrB - $clrR) / (6.0 * $deltaMax);
	        }
	        else {
	            $H = 2 / 3 + ($clrR - $clrG) / (6.0 * $deltaMax);
	        }
	
	        if (0 > $H) $H += 1;
	        if (1 < $H) $H -= 1;
	    }
	    return array('h' => $H,'s' => $S,'l' => $L);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die Farb-Bereiche und gibt sie im passenden Format zurück.
	 * @return array
	 */
	protected function _getColorFields(){
		if(!$this->_colorFields){
			$colorFieldsTemp = CunddConfig::__('Color__colorFields');
			$colorFields = array();
			// Prepare the color-fields
			foreach($colorFieldsTemp as $fieldName => $colorField){
				// Check if it is a gray field
				if(count($colorField) == 3){
					//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
					// It is gray
					$colorFields[$fieldName]['gray'] = 'gray';
					
					$colorFields[$fieldName]['minL'] = '';
					if(array_key_exists('start',$colorField)){
						$colorFields[$fieldName]['minL'] = $colorField['start'][1];
					} else {
						$this->_setIfKeyExists('minL',$colorField,$colorFields[$fieldName]['minL'],$colorField[1]);
					}
					
					$colorFields[$fieldName]['maxL'] = '';
					if(array_key_exists('end',$colorField)){
						$colorFields[$fieldName]['maxL'] = $colorField['end'][1];
					} else {
						$this->_setIfKeyExists('maxL',$colorField,$colorFields[$fieldName]['maxL'],$colorField[2]);
					}  
				} else {
					//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
					// It is not gray
					$colorFields[$fieldName]['minH'] = '';
					if(array_key_exists('start',$colorField)){
						$colorFields[$fieldName]['minH'] = $colorField['start'][0];
					} else {
						$this->_setIfKeyExists('minH',$colorField,$colorFields[$fieldName]['minH'],$colorField[0]);
					}
					
					$colorFields[$fieldName]['minL'] = '';
					if(array_key_exists('start',$colorField)){
						$colorFields[$fieldName]['minL'] = $colorField['start'][1];
					} else {
						$this->_setIfKeyExists('minL',$colorField,$colorFields[$fieldName]['minL'],$colorField[1]);
					}
					
					$colorFields[$fieldName]['maxH'] = '';
					if(array_key_exists('end',$colorField)){
						$colorFields[$fieldName]['maxH'] = $colorField['end'][0];
					} else {
						$this->_setIfKeyExists('maxH',$colorField,$colorFields[$fieldName]['maxH'],$colorField[2]);
					} 
					
					$colorFields[$fieldName]['maxL'] = '';
					if(array_key_exists('end',$colorField)){
						$colorFields[$fieldName]['maxL'] = $colorField['end'][1];
					} else {
						$this->_setIfKeyExists('maxL',$colorField,$colorFields[$fieldName]['maxL'],$colorField[3]);
					}
				}  
			}
			
			$this->_colorFields = $colorFields;
		}
		return $this->_colorFields;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode zeichnet eine Diagramm der Farbverteilung. */
	public function drawGraph(){
		$data = $this->getCountedColorFields();
		ksort($data);
		$arguments['data'] = $data;
		$graph = Cundd::getModel('Graph/Barchart',$arguments);
		return $graph->draw();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt einen Link für jede der verfügbaren Farbfelder. */
	/**
	 * @param unknown_type $useImageInThisModulsResourcesDir
	 * @param unknown_type $withSuffix
	 * @return unknown_type
	 */
	public function createColorChoser($useImageInThisModulsResourcesDir = false, $withSuffix = '.png',$separator = ''){
		$colorFields = $this->_getColorFields();
		$this->_mpColorChoserOutput = '';
		foreach($colorFields as $fieldName => $colorField){
			if($useImageInThisModulsResourcesDir){
				$resourceUrl = CunddPath::getAbsoluteResourcesUrlOfModul($useImageInThisModulsResourcesDir).'Color/'.$fieldName.$withSuffix;
				$title = "<img src='$resourceUrl' width='20' height='20' />";
			} else {
				$title = $fieldName;
			}
			$action = $_SERVER['REQUEST_URI'].'&mainColor='.$fieldName;
			$this->_mpColorChoserOutput .= CunddLink::newHardLink($title,$action).$separator;
		}
		return $this->_mpColorChoserOutput;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt einen Link für jede der verfügbaren Farbfelder aus. */
	public function printColorChoser($useImageInThisModulsResourcesDir = false, $withSuffix = '.png'){
		if(!$this->_mpColorChoserOutput){
			$this->createColorChoser($useImageInThisModulsResourcesDir, $withSuffix);
		}
		echo $this->_mpColorChoserOutput;
		return $this->_mpColorChoserOutput;
	}
}
?>