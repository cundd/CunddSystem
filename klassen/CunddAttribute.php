<?php 
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddAttribute" bietet verschieden Methoden zur Handhabung von Attributes
 * im CunddSystem. */
class CunddAttribute {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private static $debug = false;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* HANDLER */
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode verarbeitet eine Attribut-Input und parsed ihn in einen speicherbaren 
	 * String.
	 * @param array $completeInput
	 * @param string/array(attributeName => value) $oldAttributes
	 * @param string/array(	'attribute' => attributeName,
						  [ 'inputType' => inputType ])
	 * @return string
	 */
	public static function handleAttributeInput(array $completeInput, $oldAttributes, $attributeSettings = NULL){
		$attributeList = self::getAttributeSettingsArray($attributeSettings);
		
		$relevantInput = array();
		$oldAttributeArray = self::getAttributesArray($oldAttributes); 
		// echo $oldAttributes;
		// self::getAttributesFromString($oldAttributeString);
		
		$emptyAttributeArray = self::getEmptyAttributeArray($attributeList);
		$newAttributeArray = self::updateAttributes($emptyAttributeArray,$completeInput);
		$oldAttributeArray = self::synchronizeKeys($oldAttributeArray,$emptyAttributeArray);
		$newAttributeArray = self::updateAttributes($oldAttributeArray,$newAttributeArray,true);
		
		return self::getAttributeStringFromArray($newAttributeArray);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode durchsucht das assoziative Array $newArray nach Keys die ebenfalls in 
	 * $oldAttributes vorhanden sind und überschreibt die entsprechenden Werte in 
	 * $oldAttributes. */
	private static function updateAttributes(array $oldAttributes, array $newArray, $onlyIfNotEmpty = NULL){
		$elementsWithSameKey = array_intersect_key($newArray,$oldAttributes);
		
		foreach($elementsWithSameKey as $key => $value){
			if($onlyIfNotEmpty){
				if($value OR $value==0){
					$oldAttributes[$key] = $value;
				} else {
					// Do nothing
				}
			} else {
				$oldAttributes[$key] = $value;
			}
		}
		return $oldAttributes;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode synchronisiert die Keys des ersten mit dem zweiten Array. */
	private static function synchronizeKeys(array $target, array $pattern){
		foreach($pattern as $key => $value){
			if(!array_key_exists($key, $target)){
				$target[$key] = NULL;
			}
		}
		return $target;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode parsed das assoziative Array in einen Key-Value-String. */
	private static function getAttributeStringFromArray($attributeArray){
		$attributeString = '';
		foreach($attributeArray as $key => $value){
			$attributeString .= $key.'='.$value.';';
		}
		
		return $attributeString;
	}

	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt ein assoziatives Array mit leeren Werten zurück. */
	private static function getEmptyAttributeArray($attributeList = NULL){
		$attributeList = self::getAttributeSettingsArray($attributeList);
		$emptyArray = array();
		
		foreach($attributeList as $keyInAttributeList => $currentAttribute){
			$attributeName = $currentAttribute['attribute'];
			
			$value = $currentAttribute[1];
			$emptyArray[$attributeName] = NULL;
		}
		
		return $emptyArray;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* INPUT */
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode parsed das übergebene Argument als AttributeSettings-Array. Wenn kein 
	 * Parameter übergeben wurde wird versucht mittels der eigenen Klasse die Attribute-
	 * Settings zu ermitteln. */
	private static function getAttributeSettingsArray($attributeSettings = NULL){
		if($attributeSettings){
			// Entsprechend dem Typ von $attributeSettings die Attributliste ermitteln
			if(gettype($attributeSettings) == 'string'){
				/* DEBUGGEN */if(self::$debug) echo 'string<br />';/* DEBUGGEN */
				$attributeList = self::getAttributeSettingsFromString($attributeSettings);
			} else if(gettype($attributeSettings) == 'array'){
				/* DEBUGGEN */if(self::$debug) echo 'array<br />';/* DEBUGGEN */
				$attributeList = $attributeSettings;
			}
		} else {
			// Entsprechend dem Namen dieser Klasse die Attributliste ermitteln
			$attributeList = self::getAttributeSettingsFromClass();
		}
		
		return $attributeList;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die Input-Felder entsprechend eines Strings aus. Der String enthält
	 * jedes Attribut und dessen Input-Type. */
	protected function getAttributeSettingsFromString($attributeInputString){
		$allAttributesInArray = explode(';',$attributeInputString);
		
		foreach($allAttributesInArray as $key => $currentAttribute){
			$currentArributePair = explode('=',$currentAttribute);
			
			$currentAttribute = $currentArributePair[0];
			$currentInputType = $currentArributePair[1];
			$parsedAttributes[$key] = array(	'attribute' => $currentAttribute,
													'inputType' => $currentInputType,
													'type' => $currentInputType, // Alias für inputType
													'input' => $currentInputType, // Alias für inputType
													);
		}
		
		return $parsedAttributes;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt die Input-Felder entsprechend der Eingabe (Array oder String) zurück. */
	public function createAttributeInputs($input = NULL,$oldAttributes = NULL,$otherData = NULL){
		$output = '';
		if($oldAttributes){
			$oldAttributesArray = self::getAttributesArray($oldAttributes);
		}
		
		$attributeSettings = self::getAttributeSettingsArray($input);
		
		foreach($attributeSettings as $attributeSetting){
			// Handle each one
			switch($attributeSetting['type']){
				case 'radioOnOff':
				case 'radiobuttonOnOff':
					/* array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] ); */
					// TODO: Handle checked
					$onChecked = $oldAttributesArray[$attributeSetting['attribute']];
					if($onChecked){
						$offChecked = NULL;
					} else {
						$onChecked = NULL;
						$offChecked = true;
					}
					
					$radioButtonCollection = array(
						array('on', 1, $onChecked),
						array('off', 0, $offChecked),
						);
					$inputName = $attributeSetting['attribute'];
					$output .= CunddTemplate::createRadioButtons($radioButtonCollection,$inputName,$otherData);
					break;
					
				case 'checkOnOff':
				case 'checkboxOnOff':
				case 'checkboxBool':
				case 'checkboxBoolean':
				case 'checkBool':
				case 'checkBoolean':
					// TODO: Handle checked
					$value = 1;
					
					$checked = $oldAttributesArray[$attributeSetting['attribute']];
					
					$checkboxInput = $attributeSetting['attribute'];
					$inputName = $attributeSetting['attribute'];
					$output .= CunddTemplate::createCheckboxes($checkboxInput,$inputName,$checked,$value,$otherData);
					break;
					
				case 'radio':
				case 'radiobutton':
					/* array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] ); */
					// TODO: Handle checked
					$radioButtonCollection = '';
					$inputName = $attributeSetting['attribute'];
					$output .= CunddTemplate::createRadioButtons($radioButtonCollection,$inputName,$otherData);
					break;
					
				case 'text':
				case 'textbox':
					/* array( array(Label [ , Value , Checked , Required ]) [ , array(Label [ , Value , Checked , Required ]) ] ); */
					// TODO: Handle checked
					$radioButtonCollection = '';
					$inputName = $attributeSetting['attribute'];
					$right = 6;
					$required = NULL;
					
					$tag = 'Cundd_Template_Standard_Text';
					$wert = $otherData;
					$wert[$tag] = $oldAttributesArray[$inputName];
					$wert['label'] = $inputName;
					$wert['name'] = $inputName;
					
					$output .= CunddTemplate::__($wert,$right,$tag,'special',$required);
					//__($wert, $recht, $tag, $type = 'output', $required = NULL){
					/*
					 * case "sprache":
						// Out/Input -> Text
						$output .= 'eintragfeld" value="'.$wert[$tag];
						break;
					 */
					//$output .= CunddTemplate::createRadioButtons($radioButtonCollection,$inputName,$otherData);
					break;
					
				default:
					echo '<br>default:'.$attributeSetting.'<br>$attributeSetting:'.$attributeSetting['type'].'<br>';
					break;
			}
		}
		
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode gibt die Input-Felder entsprechend der Eingabe (Array oder String) aus. */
	public function printAttributeInputs($input = NULL){
		$output = self::createAttributeInputs($input);
		echo $output;
		return $output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* OUTPUT */
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode gibt ein assoziatives Attribute-Array zurück. */
	protected static function getAttributesArray($attributePara){
		if(gettype($attributePara) == 'string'){
			$parsedAttributes = self::getAttributesFromString($attributePara);
		} else if(gettype($attributePara) == 'array'){
			$parsedAttributes = $attributePara;
		}
		
		return $parsedAttributes;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode parsed den Argument-String in ein assoziatives Array. */
	protected static function getAttributesFromString($attributeString){
		$allAttributesInArray = explode(';',$attributeString);
		
		foreach($allAttributesInArray as $key => $currentAttribute){
			$currentArributePair = explode('=',$currentAttribute);
			$parsedAttributes[$currentArributePair[0]] = $currentArributePair[1]; 
		}
		
		return $parsedAttributes;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode parsed den Argument-String und gibt den Wert des als 2. Parameter über-
	 * gebenen Attributs zurück. */
	protected static function getAttributeValueFromString($attributeString, $search){
		$parsedAttributes = self::getAttributesFromString($attributeString);
		return $parsedAttributes[$search];
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode liest die Argument-Settings für die übergebene Klasse aus der 
	 * Konfigurationsdatei. */
	protected function getAttributeSettingsFromClass($className = NULL){
		if(!$className){
			$className = get_class($this);
			if(!$className){
				// Guess the class :)
				$className = 'CunddBenutzer';
			}
		}
		$classAttributeKey = $className.'_AttributeList';
		$attributeList = CunddConfig::__($classAttributeKey);
		
		if($attributeList){
			return $attributeList;
		} else {
			/* DEBUGGEN */if(self::$debug) echo "No AttributeList for configuration-key $classAttributeKey.";/* DEBUGGEN */
		}
	}
}
?>