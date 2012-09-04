<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/**
 * @license 
 * @copyright 
 * Die Klasse Cundd_File_Model_Abstract erweitert Cundd_Core_Mutable.
 * @package Cundd_Core
 * @version 1.0
 * @since Feb 4, 2010
 * @author daniel
 */
class Cundd_File_Model_Abstract extends Cundd_Core_Mutable{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	public $tmp_name = '';
	public $name = '';
	public $type = '';
	public $error = 0;
	public $size = 0;
	
	public $uploadPath = '';
	
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**/
	public function __construct(array $arguments = array()){
		parent::__construct($arguments);
		$this->_registerProperties($arguments);
		return;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode schreibt die Datei in das lokale oder entfernte Dateisystem.
	 * @param string $fileTempPath[optional]
	 * @param string $fileName[optional]
	 * @param string $uploadPath[optional] Path the file should be saved at
	 * @return boolean
	 */
	protected function _saveFileToFileSystem($fileTempPath = NULL, $fileName = NULL, $uploadPath = NULL){
		$moved = false;
		$say = false;
		
		if(!$fileName) $fileName = $this->name;
		// Den echten Upload-Path überprüfen
		if(!$uploadPath) $uploadPath = $this->_getRealPath($fileName,'upload');
		
		if(!$fileTempPath){
			if($this->tmp_name){
				$fileTempPath = $this->tmp_name;
			} else {
				if($say) Cundd::throwE("No file temporary path set.");
				return (bool) false;
			}
		}
		
		
		/* Überprüfen ob die Datei lokal oder auf einem entfernten Server gespeichert werden 
		 soll. */
		if(CunddConfig::get('CunddFiles_use_ftp')){ // Entfernt speichern
			// Den Port bestimmen
			if(CunddConfig::get('CunddFiles_ftp_port')){
				$port = CunddConfig::get('CunddFiles_ftp_port');
			} else {
				$port = 21;
			}
			
			
			$ftpConnection = ftp_connect(CunddConfig::get('CunddFiles_ftp_server'), $port);
			
			$ftpLogin = ftp_login($ftpConnection, CunddConfig::get('CunddFiles_ftp_user'), CunddConfig::get('CunddFiles_ftp_password')) or die("<h1>You do not have access to this ftp server!</h1>");
			
			
			if(ftp_put($ftpConnection, $uploadPath, $fileTempPath, FTP_BINARY)) {
				//CunddTools::log("CunddFiles","b".CunddFiles::get_real_path($_POST["files_dateiname"])."b");
				$moved = true;
			}
			
			
			if (ftp_chmod($ftpConnection, 0666, $uploadPath) !== false) {
				CunddTools::log("CunddFiles","ftp_chmod changed");
			} else {
				CunddTools::log("CunddFiles","ftp_chmod could not be changed");
			}
			
			
			// DEBUGGEN
			if($say){
				echo 'Entfernt speichern<br />';
				echo 'Erfolg '.$moved.'<br />';
				echo '$ftpConnection = '.$ftpConnection.'<br />';
				echo '$ftpLogin = '.$ftpLogin.'<br />';
				echo '$uploadPath = '.$uploadPath.'<br />';
				CunddTools::log('$ftpConnection','$ftpConnection='.$ftpConnection.' upload-path:'.$uploadPath.'; fileTempPath'.$fileTempPath);
			}
			// DEBUGGEN
			
			
		} else { // Lokal speichern
			//$moved = move_uploaded_file($fileTempPath, $uploadPath);
			$moved = rename($fileTempPath, $uploadPath);
			
			// DEBUGGEN
			if($say){
				echo 'Lokal speichern<br />';
				echo 'Erfolg='.$moved.' $fileTempPath:'.$fileTempPath.'<br />$uploadPath:'.$uploadPath.'<br />';
			}
			// DEBUGGEN
		}
		
		
		
		if($moved){
			CunddTools::log_fehler("CunddFiles","File-copy ok");
			return true;
		} else {
			CunddTools::log_fehler("CunddFiles","File-copy failed. Target-Folder ".$uploadPath);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode ermittelt den Pfad zu einer Datei und gibt diesen zurück. Es können 
	 * verschiedene Modi für die Methode angegeben werden: "thumb" (oder "thumbnail"), 
	 * "download", "upload". Der Standardwert ist "download".
	 * @param string $filename
	 * @param string $mode
	 * @param boolean $forceRemotePath
	 * @return string
	 */
	protected function _getRealPath($filename, $mode = NULL, $forceRemotePath = NULL){
		$say = false;
		
		if(!$mode){
			$mode = "download";
		}
		
		if(CunddConfig::get('CunddFiles_use_ftp')){
			$useRemote = true;
		} else if($forceRemotePath){
			$useRemote = true;
		} else {
			$useRemote = false;
		}
		
		// DEBUGGEN
		if($say) echo "useRemote $useRemote useRemote";
		// DEBUGGEN
		
		switch($mode){
			case "download":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_server_web_representation').CunddConfig::get('CunddFiles_upload_dir').$filename;
					return $url;
				} else {
					$url = CunddPath::getAbsoluteFileUploadUrl().$filename;
					return $url;
				}
				
				break;
				
			case "thumb":
			case "thumbnail":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_server_web_representation').CunddConfig::get('CunddFiles_upload_dir').CunddConfig::get('CunddFiles_thumbnail_subdir').$filename;
					return $url;
				} else {
					$url = CunddConfig::get('CunddFiles_upload_dir').CunddConfig::get('CunddFiles_thumbnail_subdir').$filename;
					return $url;
				}
				
				break;
				
			case "upload":
			case "upload_detail":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_base_path').CunddConfig::get('CunddFiles_upload_dir').$filename;
					return $url;
				} else {
					$url = '.'.CunddConfig::get('CunddFiles_upload_dir').$filename;
					return $url;
				}
				
				break;
				
			case "upload_thumb":
			case "upload_thumbnail":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_base_path').CunddConfig::get('CunddFiles_upload_dir').'thumbnails/'.$filename;
					return $url;
				} else {
					$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'thumbnails/'.$filename;
					return $url;
				}
				
				break;
				
			case "upload_original":
				// Überprüfen ob die Dateien extern gelagert werden
				if($useRemote){
					$url = CunddConfig::get('CunddFiles_ftp_base_path').CunddConfig::get('CunddFiles_upload_dir').'original/'.$filename;
					return $url;
				} else {
					$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'original/'.$filename;
					return $url;
				}
				
				break;
				
			case "upload_temp":
			case "upload_temp_detail":
				$url = '.'.CunddConfig::get('CunddFiles_upload_dir').$filename;
				return $url;
				break;
				
			case "upload_temp_original":
				$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'original/'.$filename;
				return $url;
				break;
				
			case "upload_temp_thumbnail":
			case "upload_temp_thumb":
				$url = '.'.CunddConfig::get('CunddFiles_upload_dir').'thumbnails/'.$filename;
				return $url;
				break;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode führt die nötigen Methoden zum Speichern des Objekts aus.
	 * TODO: call additional routines when saving as not anonymous.
	 */
	public function save(){
		return $this->_saveFileToFileSystem();
	}
}
?>