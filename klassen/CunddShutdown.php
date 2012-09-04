<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse CunddShutdown.
 * @package 
 * @version 1.0
 * @since Nov 28, 2009
 * @author daniel
 */
class CunddShutdown {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected static $_debug = false;
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode wird am Ende des Skripts aufgerufen. */
	public static function handler(){
		new CunddEvent('willShutdown');
		
		self::_savePersistent();
		self::_saveManaged();
		
		new CunddEvent('didShutdown');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * TODO: Works just for Singleton
	 * Die Methode speichert alle Managed Objects. */
	protected static function _saveManaged(){
		$say = false;
		$managed = Cundd_Registry::getIfRegistered(Cundd_Core_Managed::cundd_managed_getRegistryKey());
		
		/* DEBUGGEN */
		if(self::$_debug OR $say){
			echo 'CunddShutdown count managed:'.count($managed);
			CunddTools::pd($managed);
		}
		/* DEBUGGEN */
		
		if($managed){
			foreach($managed as $key => $object){
				$object->resetLoaded();
				$result .= $object->saveManaged();
			}
		}
		return $result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * TODO: Works just for Singleton
	 * Die Methode speichert alle persistenten Objekte. */
	protected static function _savePersistent(){
		$say = false;
		
		$persistent = Cundd_Registry::getIfRegistered(Cundd_Core_Model_Persistent::getRegistryKeyForPersistentObjects());
		
		/* DEBUGGEN */
		if(self::$_debug OR $say){
			echo 'CunddShutdown count persistent:'.count($persistent);
			CunddTools::pd($persistent);
		}
		/* DEBUGGEN */
		
		if($persistent){
			foreach($persistent as $key => $object){
				$object->resetLoaded();
				$result .= $object->savePersistent(true);
			}
		}
		return $result;
	}
}
?>