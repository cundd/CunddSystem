<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Diese Klasse dient als System-Controller und -Router. */
class CunddController{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	var $kind; // Speichert die Instanz des Moduls, das von CunddController aufgerufen wird
	
	private $name = 'CunddController';
	private $version = '2.0';
	private $fallback;
	private $_controller = '';
	private $_action = '';
	private $_para;
	
	private $debug = false;
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	public function CunddController($internCall = NULL){
		$this->init($internCall);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode ist der Router des CunddSystems.
	 * @param string $internCall
	 */
	public function init($internCall = NULL){
		// Lokale Konfigurationen
		$say = false;
		
		$sayAufruf = true;
		$logSay = true;
		$ignoreEmpty =true;
		
		
		//ini_set("error_reporting",E_ALL);
		
		
		// Falls die Anfrage umgeleitet wurde wird sie hier verarbeitet
		$request = $this->routeFromRewrite();
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Den für diese Instanz gültigen Aufruf ermitteln
		$validMode;
		/* Wenn nur das System initialisiert werden soll */
//		if($internCall == 'initOnly'){
//				$aufruf = 'initOnly';
//				$validMode = 'internCall - initOnly';
//		} else
		/* Wenn das System als Application installiert ist und CunddController mittels der URL routen soll. */
		if(Cundd::getSystemMode() == Cundd::CUNDD_SYSTEM_MODE_APP AND $internCall === 'initOnly'){
			$this->_prepareAndDispatchToController();
			$validMode = 'appController';
			return;
		} else 
		if(Cundd::getSystemMode() == Cundd::CUNDD_SYSTEM_MODE_APP AND $internCall !== 'initOnly' AND $internCall == true){
			$this->_dispatchToController($internCall);
			$validMode = 'internController';
			return;
		} else 
		/* Wenn der Controller systemintern (z.B. von CunddContent) aufgerufen wurde, das System nicht als Application installiert ist und die Anfrage kein Download ist: */
		if($internCall AND !$this->isDownloadRequest() AND Cundd::getSystemMode() !== Cundd::CUNDD_SYSTEM_MODE_APP){
			if($internCall == 'initOnly'){
				$aufruf = 'initOnly';
				$validMode = 'internCall - initOnly';
			} else if(!$this->dispatchToController($internCall,true)){
				$aufruf = $internCall;
				$validMode = 'internCall - not dispatched';
			} else {
				$validMode = 'internCall - dispatched';
				return;
			}
		/* Wenn der Aufruf per GET erlaubt und $_POST['aufruf'] nicht gesetzt ist: */
		} else if($this->isDownloadRequest()){
			if(array_key_exists('download', $_GET)){
				$fileId = $_GET['download'];
			} else if(array_key_exists('Download', $_GET)){
				$fileId = $_GET['Download'];
			}
			$aufruf = 'CunddFiles::provideDownload('.$fileId.')';
			$validMode = 'download';
		} else if(array_key_exists ("aufruf", $_POST)){
			$aufruf = $_POST["aufruf"];
			$validMode = 'post';
		} else if($request['action']){
			$aufruf = $request["action"];
			$para = $request["para"];
			$validMode = 'request';
		} else if(CunddConfig::get('CunddController_allow_get') AND !array_key_exists ("aufruf", $_GET)){
			$aufruf = $_GET["aufruf"];
			$validMode = 'get';
		}
		
		/* 
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Aufruf zerlegen und den eigentlichen Aufruf extrahieren
		$aufruf_und_para = explode("(",$aufruf);
		$aufruf = $aufruf_und_para[0];
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Parameter extrahieren
		$para = str_replace(')','',$aufruf_und_para[1]);
		$para = explode(",",$para);
		/* */
		$para = array();
		self::stringToActionAndPara($aufruf,$aufruf,$para);
		
		
		// $_POST['data'] an $para anhängen
		if(!isset($para['data']) AND array_key_exists('data', $_POST)){
			$para['data'] = $_POST['data'];
		} else if(!isset($para['data'])){
			$para['data'] = $para[0];
		} else if(array_key_exists('data', $_POST)){
			$para[] = $_POST['data'];
		}
		$this->_para = $para;
		
		
		// Diese Instanz global speichern
		$this->publish();
		
		
		// Die Sprache updaten
		CunddLang::update();
		
		
		
		// Den Aufruf als JavaScript-Variable zurückgeben
		/*
		 echo '<script type="text/javascript">alert("HI");CunddAjaxAufruf_alt = "'.$aufruf.'";</script>';
		 //*/
		
		
		// DEBUGGEN:
		if($say OR CunddConfig::get('controller_display_debug_all')){
			CunddTools::pd($_POST);
			//CunddTools::pd($_FILES);
			//CunddTools::pd($_SESSION);
			$echoString .=  CunddConfig::get('prefix').'<br />';
			$echoString = "\$_POST aufruf=".$_POST["aufruf"].'<br />';
			$echoString .= "\$_GET aufruf=".$_GET["aufruf"].'<br />';
			$echoString .= '$aufruf = '.$aufruf.'<br />';
			$echoString .= '$validMode = '.$validMode.'<br />';
			$echoString .= '$request = '.var_export($request,true).'<br />';
			$echoString .= 'The system mode is '.Cundd::getSystemMode().'<br />';
			echo $echoString;
			
			if($logSay){
				CunddTools::log("CunddController",$echoString);
			}
		}
		// DEBUGGEN
		
		
		$aufruf = $this->checkIfFallback($aufruf);
		
		
		new CunddEvent('willSendHeader');
		$this->sendHeader();
		new CunddEvent('didSendHeader');

		// Überprüfen welches PHP-Skript aufgerufen werden soll
		switch($aufruf){
			case "CunddBlog":
				$this->kind = new CunddBlog($para[0],$para[1],$para[2]);
				break;
				
			case "CunddInhalt":
				$this->kind = new CunddInhalt();
				break;
				
			case "CunddInhalt::loeschen":
			case "CunddInhalt::delete":
				$this->kind = CunddInhalt::loeschen();
				break;
				
			case "CunddBenutzer::show":
			case "CunddUser::show":
				$this->kind = CunddBenutzer::show();
				break;
				
			case "CunddBenutzer":
			case "CunddBenutzer::neu":
			case "CunddBenutzer::edit":
			case "CunddUser":
			case "CunddUser::neu":
			case "CunddUser::edit":
				$this->kind = new CunddBenutzer();
				break;
				
			case "CunddBenutzer::showVC":
			case "CunddBenutzer::visitingCards":
			case "CunddUser::showVC":
			case "CunddUser::visitingCards":
				$this->kind = new CunddUser_Visitingcard();
				$this->kind->render();
				break;
			
			case "CunddUser_Visitingcard":
				$this->kind = new CunddUser_Visitingcard();
				break;
				
			case "CunddLogin":
				$this->kind = new CunddLogin();
				break;
				
			case "CunddLogin::logout":
			case "CunddLogin::out":
				$this->kind = CunddLogin::logout();
				break;
				
			case "CunddLink":
				$this->kind = new CunddLink($para[0]);
				break;
			
			case "CunddLink::newLink":
				$this->kind = CunddLink::newLink($para[0],$para[1]);
				break;
				
			case "CunddMSG":
				$this->kind = new CunddMSG();
				break;
				
			case "CunddMSG::new":
				$this->kind = CunddMSG::new_msg($para[0], $para[1], $para[2], $para[3], $para[4], $para[6]);
				break;
				
			case "CunddMSG::detail":
			case "CunddMSG::msg_detail":
				$this->kind = CunddMSG::msg_detail();
				break;
				
			case "CunddFiles":
			case "CunddFiles::new":
				$this->kind = new CunddFiles();
				break;
			
			case "CunddFiles::provideDownloadLink":
				$this->kind = CunddFiles::provide_download($para["data"]);
				break;
				
			case "CunddFiles::provideDownload":
			case "CunddFiles::provide_download":
			case "download":
			case "Download":
				$this->kind = CunddFiles::provide_download($para["data"]);
				break;
				
			case "CunddFiles::edit":
				$this->kind = CunddFiles::edit();
				break;
				
			case "CunddFiles::delete":
				$this->kind = CunddFiles::delete($para['data']);
				break;
				
			case "CunddFiles::newGroup":
				$this->kind = CunddFiles::newGroup();
				break;
				
			case "CunddFiles::printAllWithDelete":
				$this->kind = CunddFiles::printAllWithDelete();
				break;
				
			case "CunddCalendar":
				$this->kind = new CunddCalendar($para[0]);
				break;
				
			case "CunddCalendar::render":
				$this->kind = new CunddCalendar("render");
				break;
				
			case "CunddAlbum":
				$this->kind = new CunddAlbum($para[0]);
				break;
				
			case "CunddImages":
				$this->kind = new CunddImages();
				$this->kind->show();
				break;
				
			case "CunddAlbum::next":
				$this->kind = new CunddAlbum();
				$this->kind->next();
				break;
				
			case "CunddImages::next":
				$this->kind = new CunddImages();
				$this->kind->next();
				break;
				
			case "CunddAlbum::previous":
				$this->kind = new CunddAlbum();
				$this->kind->previous();
				break;
				
			case "CunddImages::previous":
				$this->kind = new CunddImages();
				$this->kind->previous();
				break;
				
			case "CunddAlbum::first":
				$this->kind = new CunddAlbum();
				$this->kind->first();
				break;

			case "CunddImages::first":
				$this->kind = new CunddImages();
				$this->kind->first();
				break;
			
			case "CunddAlbum::last":
				$this->kind = new CunddAlbum();
				$this->kind->last();
				break;
				
			case "CunddImages::last":
				$this->kind = new CunddImages();
				$this->kind->last();
				break;
				
			case "CunddAlbum::stepOut":
				$this->kind = new CunddAlbum();
				$this->kind->stepOut();
				break;
				
			case "CunddImages::stepOut":
				$this->kind = new CunddImages();
				$this->kind->stepOut();
				break;
				
			case "CunddImages::stepInto":
				$this->kind = new CunddImages();
				$this->kind->stepInto($para['data']);
				$this->kind->stepOutLink();
				break;
				
			case "CunddAlbum::stepInto":
				$this->kind = new CunddAlbum();
				$this->kind->stepInto($para['data']);
				$this->kind->stepOutLink();
				break;
				
			case "CunddImages::printDetail":
			case "CunddAlbum::printDetail":
				$this->kind = new CunddAlbum();
				$this->kind->printDetail($para['data']);
				$temp = new CunddContent('CunddGalerie::stepInto');
				break;
				
			case "CunddImages::printOverview":
			case "CunddAlbum::printOverview":
				$this->kind = new CunddAlbum();
				$this->kind->printOverview();
				$this->kind->stepOutLink();
				break;
				
			case "CunddGalerie::printDetailOfSelf":
				$this->kind->printDetailOfSelf($para[0]);
				$this->kind->createAllSiblingLinks();
				break;
				
			case "CunddGalerie::printSingle":
				$this->kind->printSingle($para[0]);
				$this->kind->createAllSiblingLinks();
				break;
				
			case "CunddContent":
				$this->kind = new CunddContent($para['data']);
				break;
			
			case "CunddContent::save":
				$this->kind = CunddContent::safe();
				break;
			
			case "CunddTemplate":
				$this->kind = new CunddTemplate($para[0],$para);
				break;
				
			case "CunddTerminal":
				$this->kind = new CunddTerminal();
				break;
				
				
				
			case "initOnly":
				break;
				
			case "": // Empty: Wird ignoriert wenn $ignoreEmpty = true
				if($ignoreEmpty){
					break;
				} else {
					// dont break
				}
				
			default:
				/* Den Aufruf an den regulären Controller-Dispatcher senden, wenn dieser als 
				 * Ergebnis FALSE sendet wird versucht einen Content-Record zu laden.
				 */ 
				if(!$this->_dispatchToController($aufruf)){
					// Den Aufruf an CunddContent senden
					$content = $this->tryContent($aufruf);
					if(!$content AND CunddConfig::get('allow_controller_redirect')){
						echo '<script type="text/javascript">
						//javascript-redirect
						window.location="/'.$aufruf.'";
						</script>';
					}
					
					if($this->debug){
						echo '<div class="CunddDebug">CunddController:<br />';
						if(!$content) echo 'Beim Aufruf von "CunddController" ist ein Fehler aufgetreten.';
						echo '<h3>Aufruf:</h3>';
						CunddTools::pd($aufruf);
						echo '<h3>Valid-Mode:</h3>';
						CunddTools::pd($validMode);
						echo '<h3>Parameter:</h3>';
						echo '<pre>$_GET:';
						var_dump($_GET);
						
						echo '$_POST:';
						var_dump($_POST);
						
						echo '$para:';
						var_dump($para);
						echo '$internCall:'.$internCall;
						echo '</pre>';
						echo '</div>';
					}
				}
				
				break;
		}
		
		
		// Diese Instanz global speichern
		$this->publish();
		
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode speichert diese Instanz global. */
	function publish(){
		$GLOBALS['CunddController'] = $this;
		Cundd::registry('CunddController',$this);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode löst die umgeleitete URL in die einzelnen Variablen auf. */
	function routeFromRewrite(){
		$request = NULL;
		
		if(CunddRequest::init()){
			$request["action"] = CunddRequest::getAction();
			$request["para"] = CunddRequest::getPara();
		} else {
			CunddTools::error('CunddController','Error while init of CunddRequest.');
		}
		return $request;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode aktualisiert die Sprachwahl. */
	/*function updateLang(){
		if($_GET['lang']){
			$GLOBALS['CunddLang'] = $_GET['lang'];
		} else if($_POST['lang']){
			$GLOBALS['CunddLang'] = $_POST['lang'];
		}
	}/* */
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob ein Download eingeleitet werden soll.
	 * @return boolean|boolean
	 */
	private function isDownloadRequest(){
		if(array_key_exists('download',$_GET) OR array_key_exists('Download',$_GET)){
			Cundd::setMode('download');
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode übergibt den Aufruf an CunddContent. */
	private function tryContent($aufruf){
		$aufruf = CunddTools::cleanString($aufruf);
		return new CunddContent($aufruf);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode teilt einen übergebenen String in die Aktion und die Parameter.
	 * @param string $string
	 * @param string $action
	 * @param string|array $para
	 * @return array
	 */
	public static function stringToActionAndPara($string,&$action = NULL,&$para = NULL){
		return CunddTools::stringToActionAndPara($string,$action,$para);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob ein Aufruf ermittelt wurde, wenn nicht wird der Fallback 
	 * ausgeführt und sozusagen die "Home"-Seite dargestellt. */
	/**
	 * @param string $aufruf
	 * @return string|string
	 */
	public function checkIfFallback(&$aufruf){
		if(!$aufruf){
			$aufruf = $this->getFallback();
			return $aufruf;
		} else {
			return $aufruf;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt die Eigenschaft $fallback, die den Aufruf definiert, wenn keiner 
	 * definiert ist.
	 * @param string $newFallback
	 * @return void
	 */
	public function setFallback($newFallback){
		$this->fallback = $newFallback;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Eigenschaft $fallback zurück. Wenn der Wert nicht gesetzt ist 
	 * wird er auf den Wert in der Konfigurationsdatei gesetzt.
	 * @return string
	 */
	private function getFallback(){
		if(!isset($this->fallback) AND CunddConfig::__('Cundd_Controller_Fallback')){
			$this->setFallback(CunddConfig::__('Cundd_Controller_Fallback'));
		}
		return $this->fallback;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob der Aufruf einen Modulnamen und Controller enthält.
	 * @param string $call
	 * @return boolean
	 */
	protected function _dispatchToController($call,$forceAppMode = false){
		$say = false;
		
		if(strpos($call,'/')){
			$controllerAndAction = CunddClassLoader::getControllerAndAction($call,array(),'.php',$forceAppMode);
			
			$this->_controller = $controllerAndAction['controllerClass'];
			$this->_action = $controllerAndAction['action'];
			
			// DEBUGGEN
			if($say OR $this->debug){
				CunddTools::debug($call);
				echo "System mode is ".CunddSystem::getSystemMode()."<br />";
				echo $this->_controller . '::'. $this->_action . $this->_para;
				CunddTools::pd($this->_para);
				CunddTools::pd($controllerAndAction);
				echo '-----------------------<br />';
			}
			// DEBUGGEN
			
			
			// Exit when empty
			if(!$this->_controller AND !$this->_action) return (bool) false;
			
			try{
				// $controllerReturn = call_user_func($this->_controller.'::'.$this->_action,$this->_para);
				$controllerReturn = call_user_func(array($this->_controller,$this->_action),$this->_para);
			} catch(Exception $exception){
				if(strpos($exception->getMessage(),"2: call_user_func(Array) [<a href='function.call-user-func'>function.call-user-func</a>]: First argument is expected to be a valid callback") !== false){
					// TODO Use ErrorController
					/* DEBUGGEN */
					if($this->debug OR $say){
						echo "$this->_controller::$this->_action";
						CunddTools::pd($controllerAndAction);
					}	
					/* DEBUGGEN */
					
					echo 'Use ErrorController<br />';
				} else {
					throw $exception;
				}
			}
			/* */
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	/** 
	 * @see _dispatchToController()
	 */
	public function dispatchToController($call,$forceAppMode = false){
		return $this->_dispatchToController($call,$forceAppMode);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode führt das Routing aus wenn das System im App-Modus läuft.
	 * @return boolean
	 */
	protected function _prepareAndDispatchToController(){
		$requestUri = $_SERVER["REDIRECT_URL"]; // /Module/Controller/Action/
		$moduleControllerAction = str_replace(CunddConfig::__('BasePath'),'',$requestUri); // Delete the base-path from the request
		// $moduleControllerAction = preg_replace('!\A/|/$!','',$moduleControllerAction); // Delete beginning and ending "/"
		$moduleControllerAction = preg_replace('!\A/!','',$moduleControllerAction); // Delete beginning and ending "/"
		
		return $this->_dispatchToController($moduleControllerAction);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode sendet die Header. */
	public function sendHeader(){
		if(!headers_sent() AND !$this->isDownloadRequest()){
			
			/* 
			 * CUNDD_VIEW_MODE = '_cundd_viewMode';
			 * CUNDD_VIEW_MODE_HTML = 'html';
			 * CUNDD_VIEW_MODE_XML = 'xml';
			 */
			/* @var string Cundd::CUNDD_VIEW_MODE_HTML|Cundd::CUNDD_VIEW_MODE_XML */
			$mode = Cundd::getViewMode();
			switch ($mode) {
				case Cundd::CUNDD_VIEW_MODE_HTML:
					header("Content-Type: text/html");
					break;
				
				case Cundd::CUNDD_VIEW_MODE_XML:
					header("Content-Type: text/xml");
					break;
					
				default:
					header("Content-Type: text/html");
					//nothing ;
					break;
			}
		} else if(!headers_sent() AND $this->isDownloadRequest()){
			// Is download
		} else {
			// Headers already sent
		}
	}
}

?>