<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license
 * @copyright
 * Die Klasse Cundd_Music_Model_Artwork erweitert Cundd_Music_Model_Abstract.
 * @package Cundd_Music
 * @version 1.0
 * @since Dec 14, 2009
 * @author daniel
 */
/**
 * @author daniel
 *
 */
class Cundd_Music_Model_Entity_Artwork extends Cundd_Music_Model_Entity_Abstract{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected $_output = '';
	protected $_baseUrl = 'http://ec1.images-amazon.com/images/P/';
	protected $_mode = 'not strict';



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode . */
	public function __construct($arguments = array()){
		return $this->createOutput($arguments);
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode erstellt den Output. */
	/**
	 * @param array $arguments
	 * @return string
	 */
	public function createOutput($arguments = array()){
		if(array_key_exists('asin',$arguments)){
			$imgUrl = $this->_baseUrl.$arguments['asin'];
			$this->_output = "<img src='$imgUrl' ";
				
			$dimension = 200;
			if($this->_setIfKeyExists('dimension',$arguments,$dimension,'')) $this->_output .= "width='$dimension' height='$dimension'";
			
			$alt = 'alt';
			if($this->_setIfKeyExists('alt',$arguments,$alt)) $this->_output .= " alt='$alt'";
			
			$title = 'title';
			$artist = NULL;
			$this->_setIfKeyExists('artist',$arguments,$artist);
			if($this->_setIfKeyExists('title',$arguments,$title)) $this->_output .= " title='$artist - $title'";
			
			$this->_output .= "/>";
			return $this->_output;
		} else if($this->_mode == 'strict'){
			throw new Exception('Can\'t create Artwork without a given asin');
		}
	}


	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode gibt den Output aus.
	* @return string
	*/
	public function printOutput($arguments = array()){
		if($this->_output){
			echo $this->_output;
			return $this->_output;
		} else {
			$this->createOutput($arguments);
			echo $this->_output;
			return $this->_output;
		}
	}



	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	* Die Methode erstellt den Output mittels des übergebenen Asin-Codes und gibt den
	* Output aus. */
	public function printFromAsin($asin){
		$arguments = array('asin' => $asin);
		$this->createOutput($arguments);
		echo $this->_output;
		return $this->_output;
	}
}
?>