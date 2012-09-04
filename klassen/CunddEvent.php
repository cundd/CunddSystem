<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse "CunddEvent" wird beim Auftreten eines Events aufgerufen und kann dann 
 entsprechende Funktionen ausführen. */
class CunddEvent{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $_profile = false;
	private $_debug = false;
	
	public static $_eventObserverDictionary = NULL;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddEvent($eventName,array $para = array()){
		switch($eventName){
				case "userAdded":
				case "neuer_benutzer":
				// Überprüfen ob/dass zusätzliche Parameter übergeben wurden
				if(func_get_arg(1)){
					/* CunddMSG soll eine Nachricht betreffend des neuen Benutzers erstellen, 
					als Parameter wird der neue Benutzername übergeben. */
					CunddMSG::neuer_benutzer(func_get_arg(1));
				}
		}
		/* DEBUGGEN */
		if($this->_profile){
			$msg = "Fired the event $eventName";
			CunddTools::log('CunddEvent',$msg);
			if($this->_debug) CunddTools::brp($msg);
		}	
		/* DEBUGGEN */
		
		
		return $this->registeredObservers($eventName,$para);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob für den aktuellen Event ein Observer registriert ist. */
	private function registeredObservers($eventName,array $para = array()){
		$return = array();
		
		/* Wenn die statische Eigenschaft $_eventObserverDictionary noch nicht gesetzt ist 
		 * werden die Event-Observer-Paare geladen und anschließend in $_eventObserverDictionary
		 * gespeichert.
		 */
		if(!CunddEvent::$_eventObserverDictionary){
			include('CunddEvent/conf/observers.php');
			CunddEvent::$_eventObserverDictionary = array();
			
			foreach($observers as $eventObserverPair){
				if(array_key_exists($eventObserverPair[0],CunddEvent::$_eventObserverDictionary)){ // Anhängen
					CunddEvent::$_eventObserverDictionary[$eventObserverPair[0]][] = $eventObserverPair[1];
				} else {
					CunddEvent::$_eventObserverDictionary[$eventObserverPair[0]] = array();
					CunddEvent::$_eventObserverDictionary[$eventObserverPair[0]][] = $eventObserverPair[1];
				}
			}
		}
		
		
		if(array_key_exists($eventName,CunddEvent::$_eventObserverDictionary)){
			$observers = CunddEvent::$_eventObserverDictionary[$eventName];
			
			foreach($observers as $observer){
				$module = $observer;
				$modelName = $module.'/Observer';
				$options = array();
				$options['eventName'] = $eventName;
				$options['data'] = $para;
				
				$return[] = Cundd::getModel($modelName,$options);
			}
		}
		
		/*
		$modules = array_keys($observers,$eventName,true);
		
		foreach($modules as $key => $module){
			$modelName = $module.'/Observer';
			$options = array();
			$options['eventName'] = $eventName;
			$options['data'] = $para;
			
			$return[] = Cundd::getModel($modelName,$options);
		}
		/* */
		
		return $return;
	}
}
?>