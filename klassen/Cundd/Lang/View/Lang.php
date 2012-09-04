<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Lang_View_Lang erweitert Cundd_Core_View_Cundd.
 * @package Cundd_Core_View_Cundd
 * @version 1.0
 * @since Jan 13, 2010
 * @author daniel
 */
class Cundd_Lang_View_Lang extends Cundd_Core_View_Cundd{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $langHomeLinks = array();
	public $langCurrentLinks = array();
	public $langLibs = array();
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	public function __construct(array $arguments = array()){		
		parent::__construct($arguments);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode rendert die Links für den Sprachwechsel.
	 * @param array $links array( 'langLibs' => $langLibs, 'langHomeLinks' => $langHomeLinks, 'langCurrentLinks' => $langCurrentLinks )
	 * @param string $titleWrap[optional] '|' is a placeholder for the matching LangLib-Name
	 */
	public function renderHomeLinks(array $links = array(),$titleWrap = '|'){
		$this->_renderLinks($links,'langHomeLinks',$titleWrap);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode rendert die Links für den Sprachwechsel.
	 * @param array $links array( 'langLibs' => $langLibs, 'langHomeLinks' => $langHomeLinks, 'langCurrentLinks' => $langCurrentLinks )
	 * @param string $titleWrap[optional] '|' is a placeholder for the matching LangLib-Name
	 */
	public function renderCurrentLinks(array $links = array(),$titleWrap = '|'){
		$this->_renderLinks($links,'langCurrentLinks',$titleWrap);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode rendert die Links für den Sprachwechsel.
	 * @param array $links array( 'langLibs' => $langLibs, 'langHomeLinks' => $langHomeLinks, 'langCurrentLinks' => $langCurrentLinks )
	 */
	protected function _renderLinks($links,$type = 'langCurrentLinks',$titleWrap = '|'){
		if($links){
			$this->langCurrentLinks = $links['langCurrentLinks'];
			$this->langHomeLinks = $links['langHomeLinks'];
			$this->langLibs = $links['langLibs'];
		}
		
		if($type == 'langCurrentLinks'){
			$relevantLinks = $this->langCurrentLinks;
		} else if($type == 'langHomeLinks'){
			$relevantLinks = $this->langHomeLinks;
		}
		
		$localOutput = '';
		
		
		foreach($relevantLinks as $key => $link){
			$currentLangLibName = $this->langLibs[$key];
			$title = str_replace('|',$currentLangLibName,$titleWrap);
			$action = $link;
			$target = '_self';
			$class = 'lang_link';
			
			$localOutput .= Cundd_Tools_Link::newHardLink($title,$action,$target,$class);
			//Cundd_Tools_Link::newHardLink($title,$action,$target,$class);
		}
		
		$this->add(CunddTemplate::wrap($localOutput,'cundd_lang_links'));
		return;
	}
}
?>