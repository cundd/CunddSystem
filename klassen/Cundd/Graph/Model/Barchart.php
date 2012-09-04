<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Graph_Barchart erweitert Cundd_Graph_Abstract.
 * @package Cundd_Graph
 * @version 1.0
 * @since Dec 16, 2009
 * @author daniel
 */
class Cundd_Graph_Model_Barchart extends Cundd_Graph_Model_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren	
	protected $_barImageMode = ''; // singleColor|multiColor
	protected $_barImageFolder = '';
	protected $_barImagePrefix = '';
	protected $_barImageSuffix = '';
	protected $_barImageWidth = 20;
	protected $_barImageScale = 2;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * @param array $arguments
	 * @return unknown
	 */
	public function __construct(array $arguments = array()){
		$this->_setIfKeyExists('data',$arguments,$this->_data);
		$this->_setIfKeyExists('labels',$arguments,$this->_labels);
		
		
		$this->_setIfKeyExists('_barImageMode',		$arguments,$this->_barImageMode,	CunddConfig::__('Graph/_barImageMode'));
		$this->_setIfKeyExists('_barImageFolder',	$arguments,$this->_barImageFolder,	CunddPath::getAbsoluteResourcesUrlOfModul('Core').'Color/');
		$this->_setIfKeyExists('_barImagePrefix',	$arguments,$this->_barImagePrefix,	CunddConfig::__('Graph/_barImagePrefix'));
		$this->_setIfKeyExists('_barImageSuffix',	$arguments,$this->_barImageSuffix,	CunddConfig::__('Graph/_barImageSuffix'));
		$this->_setIfKeyExists('_barImageScale',	$arguments,$this->_barImageScale);
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Eigenschaft $data.
	 * @param array $data array( Data name => Data value [ , Data name => Data value , ... ] )
	 */
	public function setData(array $data){
		$this->_data = $data;
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt den Output.
	 * @return string|false
	 */
	public function render(){
		$i = 1;
		$dataRow = array();
		$secondDataRow = array();
		
		// Für jeden Wert in $data wird ein Eintrag erstellt
		foreach($this->_data as $key => $value){
			// TODO: Image-Class
			$number = sprintf("%02d",$i);
			$height = $value * $this->_barImageScale;
			if($height == 0 AND CunddConfig::__('Graph/_displayZeroValues')) $height = 1;
			
			$imgUrl = $this->_barImageFolder.$this->_barImagePrefix.$number.$this->_barImageSuffix;
			$dataRow[$key] = "<img src='$imgUrl' width='$this->_barImageWidth' height='$height' />";
			$secondDataRow[$key] = $key;
			$tableCols[] = $key;
			$i++;
		}
		
		/*
		$this->pd($dataRow);
		$this->pd($tableCols);
		/* */
		
		if(count($dataRow) > 0){
			$table_name = 'Graph';
			$allDataRows = array();
			$allDataRows[0] = $dataRow;
			$allDataRows[1] = $secondDataRow;
			
			ob_start();
			$outputTemp = CunddTemplate::show_table($allDataRows,$table_name, $tableCols,false,'none');
			ob_end_clean();
			
			$this->_output = $outputTemp;
			return $this->_output;
		} else {
			return (bool) false;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>