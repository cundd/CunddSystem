<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * Die Klasse CunddLink_Mailto erweitert CunddLink.
 * @package CunddLink
 * @version 1.0
 * @since Nov 28, 2009
 * @author daniel
 */
class CunddLink_Mailto extends CunddLink{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private static $searchMailto;
	private static $replaceMailto;
	private static $searchAt;
	private static $searchInName;
	private static $replaceInName;
	private static $searchDot;
	private static $init;
	
	private $output;
	private $debug = false;
	
	const ENABLE_MAILTO = true;
	const ENABLE_AT = true;
	const ENABLE_IN_NAME = false;
	const ENABLE_DOT = false;
		
		
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 * @return CunddLink_Mailto|false
	 */
	public function CunddLink_Mailto(){
		if(CunddConfig::__('CunddLink_Mailto_enabled')){
			if(!self::$init){
				$this->init();
			}
			return $this;
		} else {
			return (bool) false;
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode durchsucht den übergebenen String und gibt den veränderten Text zurück.
	 * @param string $content
	 * @return string|string
	 */
	public function createMailtoLinks(&$content){
		$content = $this->createHrefs($content);
		$content = $this->createTitles($content);
		return $content;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ersetzt die HREF-Adressen. */
	private function createHrefs(&$content){
		$pattern = '!mailto:\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b!';
		//$pattern = '!\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b!';
		//$pattern = '!^[0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$!';
		$pattern = '!mailto:+[\w|\.]+@([\w|\-]+\.)*+[a-zA-Z]*!';
		//$pattern = '/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]+$/';
		// mailto:wien@zierl.cc
		
		$matches = array();
		$pregmatchresult = preg_match_all($pattern, $content,$matches);
		
		/* DEBUGGEN */if($this->debug) CunddTools::pd($matches);/* DEBUGGEN */
		
		
		if($pregmatchresult){
			foreach($matches[0] as $key => $emailLink){
				$encodedLink = $this->createSingleMailtoLink($emailLink);
				$content = str_replace($emailLink,$encodedLink,$content);
			}
			return $content;
		} else {
			return $content;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ersetzt die Titel der Links. */
	private function createTitles(&$content){
		$pattern = '![\w|\.]+@([\w|\-]+\.)*+[a-zA-Z]*!';
		
		$matches = array();
		$pregmatchresult = preg_match_all($pattern, $content,$matches);
		
		/* DEBUGGEN */if($this->debug) CunddTools::pd($matches);/* DEBUGGEN */
		
		
		if($pregmatchresult){
			foreach($matches[0] as $key => $emailTitle){
				$encodedTitle = $this->createSingleMailtoTitle($emailTitle);
				$content = str_replace($emailTitle,$encodedTitle,$content);
			}
			return $content;
		} else {
			return $content;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt einen verschlüsselten Link aus dem übergebenen Link. Die Reihen-
	 * folge der Befehle ist umgekehrt zur Reihenfolge bei der Entschlüsselung. Auch die 
	 * Search- und Replace-Werte sind bewusst vertauscht.
	 * @param string $emailLink
	 * @return string
	 */
	private function createSingleMailtoLink($emailLink){
		if(self::ENABLE_DOT) 		$emailLink = str_replace('.',self::$searchDot,$emailLink);
		if(self::ENABLE_MAILTO) 	$emailLink = str_replace(self::$replaceMailto,self::$searchMailto,$emailLink);
		if(self::ENABLE_IN_NAME) 	$emailLink = str_replace(self::$replaceInName,self::$searchInName,$emailLink);
		if(self::ENABLE_AT) 		$emailLink = str_replace('@',self::$searchAt,$emailLink);
		/*
		href="#" onclick="window.open
('http://webmaster.windowscasino.com/affiliates/aiddownload.asp?
affid=1234','childWin','');return false;"><object><!--this being my 
flash code--></object></a>
/* */
		
		// return "javascript:CunddLink_Mail('$emailLink');";
		return "#\" onclick=\"javascript:CunddLink_Mail('$emailLink');";
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt den Titel ohne die gängigen Mail-Kennzeichen.
	 * @param string $emailTitle
	 * @return string
	 */
	private function createSingleMailtoTitle($emailTitle){
		// Den @-Ersatz bestimmen
		if(CunddConfig::__('CunddLink_Mailto_atReplace')){
			$atReplace = CunddConfig::__('CunddLink_Mailto_atReplace');
		} else {
			$atReplacePath = CunddPath::getAbsoluteClassUrl().'CunddLink/Mailto/apeonwhite.png';
			$atReplace = "<img src='$atReplacePath' class='CunddMailto CunddMailtoAt' alt='apeonwhite' border='0' />";
		}
		$emailTitle = str_replace('@',$atReplace,$emailTitle);
		return $emailTitle;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode lädt die Encryption-Keys.
	 * @return boolean
	 */
	public function init(){
		// Prepare random
		self::$searchMailto = $this->getEncryptionKey('mailto');
		self::$replaceMailto = 'mailto:';
		self::$searchAt = $this->getEncryptionKey('at');
		self::$searchInName = $this->getEncryptionKey('inname');
		self::$replaceInName = 'a';
		self::$searchDot = $this->getEncryptionKey('dot');
		self::$init = true;
		
		return (bool) true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt den Encryption-Key für die Verschlüsselung der Mail-Links.
	 * @param string $keyFor
	 * @return string|false
	 */
	private function getEncryptionKey($keyFor){
		$encKeyPrefix = 'CunddLink_Mailto_Enckey_';
		switch($keyFor){
			case "mailto":
			case "at":
			case "inname":
			case "dot":
				return CunddConfig::__($encKeyPrefix.$keyFor);
				break;
				
			default:
				return (bool) false;
				break;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt einen zufälligen String.
	 * @return string
	 */
	private function getRandomString(){
		$stringMd5 = md5(uniqid(rand(), true));
		return substr($stringMd5, 0, 8);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt die JavaScript-Funktion.
	 * @return string
	 */
	public function createJavaScriptCode(){
		$this->output .= "<script type='text/javascript'>
			function CunddLink_Mail(parastring){
				var cleanParastring = parastring;
				";
		if(self::ENABLE_AT){$this->output .= "
				var searchAt = '".self::$searchAt."';
				cleanParastring = cleanParastring.replace(searchAt,'@');
				";}
		if(self::ENABLE_IN_NAME){$this->output .= "
				var searchInName = '".self::$searchInName."';
				var replaceInName = '".self::$replaceInName."';
				cleanParastring = cleanParastring.replace(searchInName,replaceInName);
				";}
		
		if(self::ENABLE_MAILTO){$this->output .= "
				var searchMailto = '".self::$searchMailto."';
				var replaceMailto = '".self::$replaceMailto."';
				cleanParastring = cleanParastring.replace(searchMailto,replaceMailto);
				";}
		
		if(self::ENABLE_DOT){$this->output .= "
				var searchDot = '".self::$searchDot."';
				var replaceDot = '.';
				cleanParastring = cleanParastring.replace(searchDot,replaceDot);
				";}
		if(CunddConfig::__('CunddLink_Mailto_Debug')){$this->output .= "
				window.alert(cleanParastring);
				";}
		$this->output .= "
				window.location.href=cleanParastring;
				return cleanParastring;
			}
			</script>";
		return $this->output;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die JavaScript-Funktion aus.
	 * @return boolean|boolean
	 */
	public function printJavaScriptCode(){
		if(CunddConfig::__('CunddLink_Mailto_enabled')){
			if(!$this->output) $this->createJavaScriptCode();
			echo $this->output;
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
}
?>