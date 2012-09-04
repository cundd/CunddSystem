<?php
if(!class_exists('CunddError')){
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse CunddError. */
class CunddError {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode wird bei einem Fehler aufgerufen
	 */
	/**
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @param array $errcontext
	 * @return unknown_type
	 */
	public static function error($errno, $errstr, $errfile, $errline, array $errcontext){
		switch ($errno) {
	        case E_NOTICE:
	        case E_USER_NOTICE:
	            // CunddError::printAll($errno, $errstr, $errfile, $errline, $errcontext);
	            break;
	        case E_WARNING:
	        case E_USER_WARNING:
	            CunddError::printAll($errno, $errstr, $errfile, $errline, $errcontext);
	            break;
	        case E_ERROR:
	        case E_USER_ERROR:
	            CunddError::printAll($errno, $errstr, $errfile, $errline, $errcontext);
	            break;
	        default:
	            CunddError::printAll($errno, $errstr, $errfile, $errline, $errcontext);
	            break;
        }
		
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Die Methode wird bei einem Fehler aufgerufen
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @param array $errcontext
	 * @return unknown_type
	 */
	public static function exception($exception){
		//CunddTools::pd($exception);
		$msg = $exception->getMessage();
		$output = "<pre>$msg</pre>";
		echo $output;
		
		$output .= self::_printTrace($exception->getTrace());
		
		self::_logException($output);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt den Trace aus.
	 * @param unknown_type $trace
	 * @return string
	 */
	protected function _printTrace($trace){
		$output = '';
		
		foreach($trace as $key => $tracePoint){
			$class 		= $tracePoint['class'];
			$function 	= $tracePoint['function'];
			$type 		= $tracePoint['type'];
			$file 		= $tracePoint['file'];
			$line 		= $tracePoint['line'];
			
			$output .= "
			<b>$class $type $function</b> in $file @ line $line.";
			if(array_key_exists('data',$tracePoint)) $output .= " Data = $data";
			$output .= "<br />";
		}
		echo $output;
		return $output;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die komplette Fehlermeldung aus.
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @param array $errcontext
	 * @return unknown_type
	 */
	private static function printAll($errno, $errstr, $errfile, $errline, array $errcontext){
		$msg = "$errno: $errstr<br />in $errfile on line $errline";
		throw new Exception($msg);
		
		echo $msg;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode überprüft ob die Exception geloggt werden soll. Höchste Priorität hat der 
	 * Wert in der Konfiguration, wenn dieser nicht gesetzt ist, gilt die Einstellung in 
	 * dieser Datei (self::_logException). */
	protected static function _logException($msg){
		$log = false;
		if(CunddConfig::__('Security/log_exceptions' != NULL)){
			$log = true;
		} else if(self::_logExceptions){
			$log = true;
		}
		
		if($log){
			CunddTools::log($msg);
		}
	}
}
} // END OF CLASS_EXISTS