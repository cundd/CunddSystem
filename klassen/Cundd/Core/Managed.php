<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_Core_Managed wird als Base-Protokol importiert und bietet die Methoden 
 * zum Lesen und Schreiben von Managed-Object-Files.
 * @package Cundd_Core
 * @version 1.0
 * @since Dec 25, 2009
 * @author daniel
 */
abstract class Cundd_Core_Managed 
extends Cundd_Core_Beginofinheritancechain 
//extends Cundd_Core_Model_Singleton 
{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	protected static $_cundd_managed_registryKey 	= '_managed';
	
	protected $_cundd_managed_user_data 			= array();
	protected $_cundd_managed_user_dir_name 		= '';
	protected $_cundd_managed_user_path 			= '';
	protected $_cundd_managed_user_file_path 		= '';
	
	protected $_cundd_managed_user_dir_name_prefix 	= 'user_';
	protected $_cundd_managed_user_file_suffix 		= '.txt';
	
	/**
	 * @var string Possible values are 'file' or 'database'. Currently only 'file' is supported
	 */
	protected $_cundd_managed_user_storage 			= 'file';
	
	/**
	 * @var string Possible values are 'file' or 'database'. Currently only 'file' is supported
	 */
	protected $_cundd_managed_public_storage 		= 'file';
	
	/**
	 * @var string Possible values are 'file' or 'database'. Currently only 'file' is supported
	 */
	protected $_cundd_managed_system_storage 		= 'file';
	
	const CUNDD_MANAGED_MODE_NONE 		= 0;
	const CUNDD_MANAGED_MODE_USER 		= 1;
	const CUNDD_MANAGED_MODE_PUBLIC 	= 2;
	const CUNDD_MANAGED_MODE_SYSTEM 	= 3;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ruft die Konstruktor-Methode auf. */
	public function __construct(array $arguments = array()){
		parent::__construct($arguments);
		$this->_cundd_protocol_init_cundd_core_managed();
		
		return $this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode dient als Konstruktor. */
	protected function _cundd_protocol_init_cundd_core_managed(){
		$say = false;
		
		
//		// Zuerst wird versucht den Singleton zu laden
//		$loadSingleton = NULL;
//		if($this->_isSingleton()){
//			$loadSingleton = $this->_loadSingleton();
//		}
//		
		
//		$className = get_class($this);
		
//		$registeredSingleton = $this->_getRegisteredSingleton();
		
//		$registeredSingleton = false;
//		if($registeredSingleton){
//			// /* DEBUGGEN */if($say) echo 'is registered<br />';/* DEBUGGEN */
//		return $registeredSingleton;
			
//		} else {
		
		
		if($this->_isManaged() AND $this->_getManagedMode() !== self::CUNDD_MANAGED_MODE_NONE){	
			$storageMode = $this->_cundd_managed_getConfig('_storage');
			$managedMode = $this->_managedMode();
			switch ($storageMode){
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
				case 'file':
					switch ($managedMode) {
						case 1:
							$result = $this->_cundd_managed_initFile_User();
						break;
						
						case 2:
							$result = $this->_cundd_managed_initFile_Public();
							break;
							
						case 3:
							$result = $this->_cundd_managed_initFile_System();
							break;
						
						default:
							$msg = "Failure in managed-mode $managedMode.";
							throw new Exception($msg);
							break;
							break;
					}
					break;
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
				default:
					if($storageMode){
						$msgStrMode = $storageMode;
					} else {
						$msgStrMode = '-- none specified --';
					}
					$msg = "Unsupported storage mode $msgStrMode.";
					throw new Exception($msg);
					break;
			}
			
			
			$this->_registerManaged();
		}
		
		
		
		
		
		return $result;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode speichert das Objekt entsprechend der Einstellungen. */
	final public function cundd_managed_save(){
		$storageMode = $this->_cundd_managed_getConfig('_storage');
		switch ($storageMode){
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			case 'file':
				$return = $this->_cundd_managed_writeToFileSystem();
				break;
			
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			default:
				if($storageMode){
					$msgStrMode = $storageMode;
				} else {
					$msgStrMode = '-- none specified --';
				}
				$msg = "Unsupported storage mode $msgStrMode.";
				
				throw new Exception($msg);
				break;
		}
		return $return;
	}
	public function saveManaged(){
		return $this->cundd_managed_save();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// USER
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht ein entsprechendes User-File zu laden, wenn dies nicht gelingt 
	 * wird gegebenenfalls der User-Ordner erstellt, wenn ein Benutzer eingeloggt ist.
	 * @return unknown
	 */
	protected function _cundd_managed_initFile_User(){
		if(!$this->_cundd_managed_checkIfManagedAllowed()){
			return (bool) false;
		}
		
		
		$result = $this->_cundd_managed_readFromFileSystem();
		if(!$result){
			$this->_cundd_managed_getUserData();
			if($this->_cundd_managed_user_data['name']){
				$result = $this->_cundd_managed_createUserDirIfNotExists();
			} else {
				$result = (bool) false;
			}
		}
		return $result;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die Daten des aktuellen Benutzers.
	 * @return array( 'name' => name , 'maingroup' => maingroup , 'groups' => gruppen )
	 */
	protected function _cundd_managed_getUserData(){
		$sessionUserData = CunddUser::getSessionUserData();
		$userName = $sessionUserData['name'];
		
		$userData = CunddUser::get_daten($userName);
		
		$sessionUserData['id'] = $userData['schluessel'];
		
		$this->_cundd_managed_user_data = $sessionUserData;
		
		return $this->_cundd_managed_user_data;
	}
	
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// PUBLIC
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht ein entsprechendes Public-File zu laden, wenn dies nicht gelingt 
	 * behandelt das System Public als wäre es ein bestimmter Benutzer mit dem Namen 
	 * "public".
	 * @return unknown
	 */
	protected function _cundd_managed_initFile_Public(){
		$result = $this->_cundd_managed_readFromFileSystem();
		if(!$result){
			$this->_cundd_managed_getPublicData();
			$result = $this->_cundd_managed_createUserDirIfNotExists();
		}
		return $result;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die Daten des aktuellen Benutzers.
	 * @return array( 'name' => name , 'maingroup' => maingroup , 'groups' => gruppen )
	 */
	protected function _cundd_managed_getPublicData(){
		$sessionUserData['name'] = 'public';
		$sessionUserData['maingroup'] = 0;
		$sessionUserData['groups'] = 0;
		$sessionUserData['id'] = 0; // 0 ist eine unmögliche User-ID
		
		$this->_cundd_managed_user_data = $sessionUserData;
		return $this->_cundd_managed_user_data;
	}
	
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// FILESYSTEM
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode schreibt die Daten ins Filesystem. */
	protected function _cundd_managed_writeToFileSystem($content = ''){
		if($this->_cundd_managed_checkIfManagedAllowed()){
			$path = $this->_cundd_managed_getFilePath();
			if(!$content) $content = $this->_cundd_managed_prepareContentForSaving();
			
			$fp = fopen($path, 'w');
			$writeResult = fwrite($fp, $content);
			fclose($fp);
			
			if(!$writeResult){
				$msg = "The file $path couldn't be written.";
				CunddTools::error($msg);
				return (bool) false;
			} else {
				return (bool) true;
			}
		} else {
			// TODO: handle not singleton
			return (bool) false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode liest die Daten vom Filesystem. */
	protected function _cundd_managed_readFromFileSystem(){
		if($this->_cundd_managed_checkIfManagedAllowed()){
			$path = $this->_cundd_managed_getFilePath();
			
			if(file_exists($path)){
				$readResult = file_get_contents($path, FILE_BINARY);
				
				$object = $this->_cundd_managed_prepareContentAfterReading($readResult);
				
				$msg = '_cundd_managed_readFromFileSystem';
				CunddTools::log($msg);
				echo $msg;
				$this->pd($object);
				
				return $object;
				// $noTarget = 'noSpecialTargetSet';
				// return $this->_overwriteThis($object,$noTarget,true);
				
			} else {
				return (bool) false;
			}
			
			if(!$readResult){
				$msg = "The file $path couldn't be read.";
				CunddTools::error($msg);
				return (bool) false;
			} else {
				return (bool) true;
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den absoluten Pfad zum Temp-Verzeichnis zurück.
	 * @return string
	 */
	protected function _cundd_managed_getTempDir(){
		return CunddPath::getAbsoluteTempDir();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt das Verzeichnis für den Session-User.
	 * @return boolean
	 */
	protected function _cundd_managed_createUserDirIfNotExists(){
		$this->_cundd_managed_user_path = CunddPath::getAbsoluteTempDir().$this->_cundd_managed_getUserDirName();
		
		// Überprüfen ob der Ordner existiert
		if(file_exists($this->_cundd_managed_user_path)){
			// Überprüfen ob der Ordner beschreibbar ist
			if(!is_writable($this->_cundd_managed_user_path)){
				$msg = "The temp directory $this->_cundd_managed_user_path exists but is not writable.";
				throw new Exception($msg);
			}
		} else if($this->_cundd_managed_createUserDir()){
			return (bool) true;
		} else {
			$msg = "The temp directory $userDirPath could not be created.";
			throw new Exception($msg);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode erstellt den User-Ordner und gibt bei Erfolg TRUE zurück.
	 * @return boolean
	 */
	protected function _cundd_managed_createUserDir(){
		if(CunddPath::checkIfTempDirIsWritable()){
			if(mkdir($this->_cundd_managed_user_path,0777)){
				$msg = "The new user folder ".$this->_cundd_managed_user_path." has been created.";
				$this->log($msg);
				
				new CunddEvent('Cundd_Core_cundd_managed_user_dir_created');
				new CunddEvent('dirCreated');
				
				return (bool) true;
			} else {
				return (bool) false;
			}
		} else {
			return (bool) false;
		}
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Namen des User-Verzeichnisses zurück.
	 * @return string
	 */
	protected function _cundd_managed_getUserDirName(){
		$this->_cundd_managed_user_dir_name = $this->_cundd_managed_user_dir_name_prefix.$this->_cundd_managed_user_data['id'];
		return $this->_cundd_managed_user_dir_name;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt den Namen der Managed-Datei. */
	protected function _cundd_managed_getFilePath(){
		$fileName = get_class($this);
		
		if(!$this->_cundd_managed_user_path) $this->_cundd_managed_user_path = CunddPath::getAbsoluteTempDir().$this->_cundd_managed_getUserDirName();
		
		$this->_cundd_managed_user_file_path = $this->_cundd_managed_user_path.'/'.$fileName.$this->_cundd_managed_user_file_suffix;
		return $this->_cundd_managed_user_file_path;
	}
	
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//
	// MAIN
	//
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode ermittelt die Daten, die in der Datei gespeichert werden sollen.
	 * @return string
	 */
	protected function _cundd_managed_prepareContentForSaving(){
		$binaryContent = serialize($this);
		return $binaryContent;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode entpackt das gespeicherte Objekt und gibt das Objekt zurück.
	 * @param binary $rawData
	 * @return Cundd_Core_Abstract
	 */
	protected function _cundd_managed_prepareContentAfterReading($rawData){
		$binaryContent = unserialize($rawData);
		return $binaryContent;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft zuerst ob in der Konfiguration des Moduls ein entsprechender 
	 * Eintrag gemacht wurde, wenn kein Wert definiert ist wird überprüft ob eine passende 
	 * Eigenschaft gesetzt ist. Wenn auch dies nicht der Fall ist wird FALSE zurückgegeben.
	 * @param string $configName
	 * @return string
	 */
	protected function _cundd_managed_getConfig($configName){
		// Parse the name of the config value
		$replacement = '_cundd_managed_'.$this->_cundd_managed_managedModeToString().'_';
		// $newConfigName = preg_replace (array('!_!','!_cundd_managed!'), $replacement, $configName,1);
		$newConfigName = preg_replace ('!_!', $replacement, $configName,1);
		
		
		$classProperties = get_object_vars($this);
		
		
		if(CunddConfig::__($this->_getModule().'/'.$newConfigName)){
			$return = CunddConfig::__($this->_getModule().'/'.$newConfigName);
		} else if(array_key_exists($newConfigName,$classProperties)){
			$return = $this->$newConfigName;
		} else {
			$return = false;
		}
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den übergebenen Managed-Mode-Key als entsprechenden String zurück. 
	 * Wenn kein Key übergeben wurde wird der des aktuellen Objekts ermittelt und der ent-
	 * sprechende String zurückgegeben.
	 * @param int $modeInt
	 * @return string 'None'|'User'|'Public'|'System'
	 */
	protected function _cundd_managed_managedModeToString($modeInt = NULL){
		if(!$modeInt){
			$modeInt = $this->_managedMode();
		}
		
		switch ($modeInt){
			case 0:
				$return = 'none';
				break;
				
			case 1:
				$return = 'user';
				break;
				
			case 2:
				$return = 'public';
				break;
				
			case 3:
				$return = 'system';
				break;
				
			default:
				$return = false;
				break;
		}
		
		
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode registriert das Objekt wenn es als persistent definiert ist.
	 * @return boolean
	 */
	final protected function _cundd_managed_unregisterManaged(){
		if($this->_isManaged()){
			$managedObjects = array();
			
			$className = get_class($this);
			
			/* Überprüfen ob ein Registry-Eintrag für Managed Objects angelegt ist, wenn 
			 * ja den Wert laden
			 */
			if(Cundd_Registry::isRegistered($this->_cundd_managed_getRegistryKey())){
    			$managedObjects = Cundd_Registry::get($this->_cundd_managed_getRegistryKey());
			}
			
			// TODO: register not Singleton
			unset($managedObjects[$className]);
			
			// Das aktualisierte Array registrieren
			$registryKey = $this->_cundd_managed_getRegistryKey();
			
			
			Cundd_Registry::set($registryKey,$managedObjects);
			
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	protected function _unregisterManaged(){
		return $this->_cundd_managed_unregisterManaged();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode registriert das Objekt wenn es als persistent definiert ist.
	 * @return boolean
	 */
	final protected function _cundd_managed_registerManaged(){
		if($this->_isManaged()){
			$managedObjects = array();
			
			$className = get_class($this);
			
			/* Überprüfen ob ein Registry-Eintrag für Managed Objects angelegt ist, wenn 
			 * ja den Wert laden
			 */
			if(Cundd_Registry::isRegistered($this->_cundd_managed_getRegistryKey())){
    			$managedObjects = Cundd_Registry::get($this->_cundd_managed_getRegistryKey());
			}
			
			// TODO: register not Singleton
			$temp = $this;
			$managedObjects[$className] = $temp;
			
			// Das aktualisierte Array registrieren
			$registryKey = $this->_cundd_managed_getRegistryKey();
			
			
			Cundd_Registry::set($registryKey,$managedObjects);
			
			return (bool) true;
		} else {
			return (bool) false;
		}
	}
	protected function _registerManaged(){
		return $this->_cundd_managed_registerManaged();
	}
	protected function _updateManaged(){
		return $this->_cundd_managed_registerManaged();
	}
	protected function _cundd_managed_updateManaged(){
		return $this->_cundd_managed_registerManaged();
	}
	public function _cundd_managed_update(){
		return $this->_cundd_managed_registerManaged();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Registry-Key für Managed Objects zurück. */
	protected function _cundd_managed_getRegistryKey(){
		return Cundd_Core_Managed::cundd_managed_getRegistryKey();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Registry-Key für Managed Objects zurück. */
	public static function cundd_managed_getRegistryKey(){
		return self::$_cundd_managed_registryKey;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode versucht ein Managed-Object zu laden.
	 * @return Cundd_Core_Abstract
	 */
	final protected function _cundd_managed_loadManaged(){
		if($this->_isManaged() AND $this->_getManagedMode() !== self::CUNDD_MANAGED_MODE_NONE){
			$storageMode = $this->_cundd_managed_getConfig('_storage');
			switch ($storageMode){
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
				case 'file':
					$return = $this->_cundd_managed_readFromFileSystem();
					break;
				
				//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
				default:
					$msg = "Unsupported storage mode $storageMode.";
					throw new Exception($msg);
					break;
			}
		}
		return $return;
	}
	protected function _loadManaged(){
		return $this->_cundd_managed_loadManaged();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob der Mode PUBLIC oder USER ist. Wenn der Mode USER ist wird
	 * überprüft ob ein User eingeloggt ist.
	 * @param boolean $required
	 * @return boolean
	 */
	protected function _cundd_managed_checkIfManagedAllowed($required = false){
		// $required = true;
		if($this->_isSingleton()){
			
			// Load user data
			$this->_cundd_managed_getUserData();
			
			
			if($this->_managedMode() == self::CUNDD_MANAGED_MODE_PUBLIC){
				$return = (bool) true;
				$mode = "public";
			} else if(	$this->_managedMode() == self::CUNDD_MANAGED_MODE_USER AND 
						$this->_cundd_managed_user_data['id']){
				$return = (bool) true;
				$mode = "user";
			} else {
				if($required){
					$msg = "The user is not allowed in mode ".$this->_managedMode().".";
					throw new Exception($msg);
				}
				$return = (bool) false;
			}
			
		} else {
			if($required){
				throw new Exception("No singleton.");
			}
			$return = (bool) false;
		}
		return $return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob das Objekt neu vom Filesystem geladen werden soll. Dies ist
	 * beispielsweise der Fall wenn der Managed-Mode USER ist und sich ein Benutzer ange-
	 * meldet hat.
	 * @return boolean
	 */
	protected function _cundd_managed_checkIfForceLoad(){
		$return = (bool) false;
		
		// Case 1: managed-mode = 1 and user logged in
			$key = '_cundd_user_userLoggedIn';
			if(Cundd::registry($key) AND $this->_managedMode() == self::CUNDD_MANAGED_MODE_USER){
				$return = (bool) true;
			}
			
		// Case 2: managed-mode = 2 and user logged out
			$key = '_cundd_user_userLoggedOut';
			if(Cundd::registry($key) AND $this->_managedMode() == self::CUNDD_MANAGED_MODE_PUBLIC){
				$return = (bool) true;
			}
		
		// Return
		return $return;
	}
	
	
	
	
	
	
	
	
	
	
	/*
	
	
	protected function _isMutable(){return true;}
	protected function _isPersistent(){return true;}
	protected function _managedMode(){return CUNDD_MANAGED_MODE_USER;}
	/* */
	
}
?>