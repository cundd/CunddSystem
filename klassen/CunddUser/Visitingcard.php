<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Klasse CunddUser_Visitingcard erweitert CunddUser um die Ausgabe der Benutzer
 * in einer Liste von Visitenkarten. */
class CunddUser_Visitingcard extends CunddUser {
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Variablen deklarieren
	private $template;
	private $userCollection;
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/**
	 * Konstruktor
	 */
	function CunddUser_Visitingcard($template = NULL){
		if($template){
			$this->template = $template;
		} else {
			$this->template = 'Cundd_User_Visitingcards_cards';
		}
		
		$this->userCollection = $this->getVisitingcardEnabledUsers();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode setzt das Template. */
	public function setTemplate($template){
		$this->template = $template;
		return $this->template;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** Die Methode gibt alle Benutzer mit dem Attribute "showVC=1" zurück.
	 * @return array|false
	 */
	private function getVisitingcardEnabledUsers(){
		// Alle entsprechenden Benutzer aus der Datenbank abrufen
		$cunddDB = new CunddDB();
		$cunddDB->setTable('benutzer');
		$where = array('attribute' => '%showVC=1%');
		$order = array('schluessel');
		$result = $cunddDB->select(NULL,$where,NULL,NULL);
		
		return $result;
	}
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Visitenkarten aus. */
	public function printAllVisitingcards(){
		foreach($this->userCollection as $key => $user){
			$this->printVisitingcardOfUserAt($key);
		}
		if(count($this->userCollection)){
			
		}
	}
	public function render(){
		return $this->printAllVisitingcards();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/** 
	 * Die Methode gibt die Visitenkarte des Users an der Stelle $index der Eigenschaft 
	 * $userCollection aus.
	 * @param $index
	 * @return string|false
	 */
	public function printVisitingcardOfUserAt($index){
		if($this->userCollection[$index]){
			$user = $this->userCollection[$index];
			// Die Attribute parsen
			$attributeArray = array();
			$attributeArray = NULL;
			$attributeArray = self::getAttributesFromString($user['attribute']);
			
			$user = array_merge($user,$attributeArray);
			
			$tag = $this->template;
			$output = CunddTemplate::__($user,'4',$tag,'output');
			
			echo $output;
			return $output;
			
		} else {
			return (bool) true;
		}
	}
}
?>