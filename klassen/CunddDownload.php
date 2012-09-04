<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse CunddDownload bietet verschiedene Methoden zur Ein- und Ausgabe von Down-
 * loads. Die benötigten Links werden automatisch erstellt. */
class CunddDownload extends CunddFiles {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $contentId; // Speichert die Content-ID des Download-Records
	private $fileId; // Speichert die File-ID des Download-Records
	private $debug = true;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Der Konstruktor bietet ein Interface zur Ein- bzw. Ausgabe eines Downloads. Der benötigte 
	 * Download-Link wird automatisch erstellt. */
	function CunddDownload($contentId = NULL,$fileId = NULL){
		if($fileId) $this->fileId = $fileId;
		if($contentId){
			$this->contentId = $contentId;
			$content = new CunddContent($contentId,true);
			$contentRecord = $content->getContentRecord();
			if($contentRecord == false){ // Der Content existiert nicht
				if($this->createNewDownloadContentRecord()){
					$this->createDownloadContentOutput();
				}
			} else {
				$this->createDownloadContentOutput();
			}
		} else if($this->createNewDownloadContentRecord()){
			$this->createDownloadContentOutput();
		} else {
			// ERROR
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Daten eines Standard-Download-Records zurück. */
	private function getStandardDownloadContentRecord($contentId,$fileId = '0'){
		$data = array(
			'title' => "CunddDownload record with content-ID $contentId",
			'eventdatum' => '0000-00-00',
			//'subtitle' => '',
			//'beschreibung' => '',
			'text' => "CunddDownload($contentId,$fileId)",
		);
		return $data;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt einen neuen Download-Content-Record. */
	function createNewDownloadContentRecord(){
		// Einen leeren Content-Record erstellen
		$data = array();
		$newId = CunddContent::insert($data);
		$this->contentId = $newId;
		
		$data = $this->getStandardDownloadContentRecord($newId);
		$result = CunddContent::update($data,$newId);
		
		return $result;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt den grafischen Output der Klasse. */
	private function createDownloadContentOutput(){
		$contentId = $this->contentId;
		$content = new CunddContent($contentId);
		$content = $content->getContentRecord();
		
		$right = new CunddRechte($content);
		$right = $right->getRight();
		if($right >= 6){
			$inputs = array(array('name' => 'fileId','type' => 'text'));
			$action = 'CunddDownload::edit';
			$formname = 'CunddDownload';
			$class = $formname;
			
			$form = new CunddForm($inputs,$action,$formname,$right,$class);
			$form->render();
		} else if($right >= 4){
			// Allow read
		} else {
			/* DEBUGGEN */if($this->debug) echo 'The current user has no rights.<br />';/* DEBUGGEN */
			// Allow nothing
		}
		echo 'RECHT:'.$right;
	}
}