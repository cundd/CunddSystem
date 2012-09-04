//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Klasse CunddLogin_js()
/* Die Klasse bietet die Methoden zum abschicken des Login-Formulars. */
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Konstruktor
function CunddLogin_js(CunddLoginInstanzId){
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Auto-Focus "_benutzer" Textfeld
	window.document.getElementById(CunddLoginInstanzId + "_benutzer").focus();
	
	/* Das Formular muss als Objekt gespeichert werden um das Verhindern des Ab-
	 schickens zu ermöglichen. */
	CunddLoginFormular_form = window.document.getElementById(CunddLoginInstanzId);
	CunddLoginFormular_form.onsubmit = function(){
		parameter = $("#"+this.id).serializeArray();
		
		// Das "aufruf"-Objekt zur Definition des PHP-Skripts erstellen
		var aufruf = {name: "aufruf", value: "CunddLogin"};
		parameter.push(aufruf); // "Aufruf"-Objekt anhängen
		
		// Ajax-Anfrage stellen
		CunddLinkAjax.inhalt_aendern(parameter);
		return false;
	}
 }
