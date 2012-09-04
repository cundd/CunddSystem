//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Klasse CunddUpdate()
/* Die Klasse stellt eine Ajax-Anfrage und ersetzt den Inhalt des angegebenen 
 div durch die Antwort der Ajax-Anfrage. */
/* WICHTIG: jQuery-Bibliothek benötigt! Zum Stellen von Ajax-Anfragen wird die 
jQuery-Bibliothek benötigt. Weitere Informationen unter http://jquery.com/ */
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Konstruktor
/* Der Konstruktor erhält als Parameter ein Objekt beinhaltend die aufzurufende 
 HTML- bzw. PHP-Datei, die Parameter welche per Ajax gesendet werden sollen und 
 die ID des div, dessen Inhalt aktualisiert werden soll. Zusätzlich kann optional 
 das Objekt, das CunddUpdate aufgerufen hat und der Name der Funktion, die nach dem 
 Complete-Event ausgeführt werden soll, angegeben werden.
 Eine andere Möglichkeit zum Aufruf der Methode bietet die Angabe von "datei", 
 "data" und "targetId" als separate Argumente. Die Angabe einer on-complete-
 Funktion ist dabei nicht möglich. */
function CunddUpdate(parameterObjekt){
	var fehler = false;
	
	// Überprüfen wie der Konstruktor aufgerufen wurde
	if(CunddUpdate.arguments.length == 3){ // Aufruf mit mehreren Argumenten
		var argumentArray = CunddUpdate.arguments;
		this.datei = argumentArray[0];
		this.data = argumentArray[1];
		this.targetId = argumentArray[2];
		
		// Nicht verwendete Properties auf null setzen
		this.completeFunction = null;
		
		// processData auf true setzen
		this.processData = true;
		this.contentType = "application/x-www-form-urlencoded";
		
		
	} else if(CunddUpdate.arguments.length == 1){ // Argument ist Objekt
		this.datei = parameterObjekt.datei;
		this.data = parameterObjekt.data;
		this.targetId = parameterObjekt.targetId;
		
		// Optionen überprüfen		
		if(parameterObjekt.completeFunction){
			this.completeFunction = parameterObjekt.completeFunction;
		} else {
			this.completeFunction = null;
		}
		if(parameterObjekt.sendRawData){
			this.processData = false//true//null;
//			this.contentType = "application/x-www-form";
			this.contentType = "multipart"//form-data";
		} else {
			this.processData = true;
			this.contentType = "application/x-www-form-urlencoded";
		}
	} else {
		// Fehlerhafter Aufruf
		fehler = true;
		alert("FEHLER")
	}
	
	//alert(this.contentType)
	this.name = "CunddUpdate";
	
	
	if(!fehler){
		// Das Aussehen des Mouse-Cursors ändern
		window.document.getElementsByTagName('body')[0].className = 'elementSpecialCursor';
		window.document.getElementsByTagName('a').className = 'elementSpecialCursor';
		
		// Die Parameter der Ajax-Anfrage speichern
		var ajaxParameter = {
			contentType: this.contentType,
			processData: this.processData,
			completeFunction: this.completeFunction,
			targetId: this.targetId,
			url: this.datei,
			type: "POST",
			data: this.data,
			cache: false,
			dataType: "html",
			error: this.error,
			success: this.display,
			complete: this.oncomplete
		}
		
		// Die Ajax-Anfrage senden
		var ajaxAufruf = $.ajax(ajaxParameter);
	}
}



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Methode display gibt die Antwort der Ajax-Anfrage im Browser aus. Sie er-
 hält als Parameter die ID des div, dessen Inhalt aktualisiert werden soll, die 
 Antwort der Ajax-Anfrage und eine Statusangabe über den Ajax-Aufruf. */
CunddUpdate.prototype.display = function(data, textStatus){
	// this zeigt auf das Ajax-Objekt
	
	/* Wenn auch eine complete-Funktion aufgerufen wird und Cundd_auto_refresh auf 
	true gesetzt ist, soll das Ergebnis der Ajax-Anfrage hier noch nicht ausge-
	geben werden  */
	if(this.completeFunction && Cundd_auto_refresh == 1){
		// Dieses "Zwischen-Ergebnis" nicht ausgeben
	} else {
		$("#"+this.targetId).html(data);
	}
}



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Methode error definiert das Verhalten bei einem Fehler während der Ajax-
 Anfrage. */
CunddUpdate.prototype.error = function(XMLHttpRequest, textStatus, errorThrown){
	// Der Fehler wird ignoriert
	// alert(XMLHttpRequest+" \n "+textStatus+" \n "+errorThrown)
}



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Methode complete überprüft ob zusätzliche Parameter angegeben wurden und 
 ruft ggfl. die angegebenen Funktion auf. */
CunddUpdate.prototype.oncomplete = function(XMLHttpRequest, textStatus){
	// this zeigt auf das Ajax-Objekt
	
	// Den Mouse-Cursor auf normal setzen
	window.document.getElementsByTagName('body')[0].className = 'elementNormalCursor';
	window.document.getElementsByTagName('a').className = 'elementNormalCursor';
	
	// Überprüfen ob Optionen angegeben wurden und Cundd_auto_refresh gleich 1 ist
	if(this.completeFunction && Cundd_auto_refresh == 1){
		/* Wenn completeFunction eine CunddBlog-Instanz aufruft muss zuerst der 
		Prefix aus dem Aufruf entfernt werden. */
		if(this.completeFunction.replace("CunddBlog","")){ // Überprüfen ob Blog
			this.completeFunction = this.completeFunction.replace(CunddPrefix,"");
		}
		/* Den passenden Aufruf, der an "CunddAjax.php" weitergeleitet wird, in Form des 
		 "aufruf"-Objekts erstellen. */
		var aufruf = {aufruf: this.completeFunction};
		
		new CunddUpdate(CunddAjaxPHP_verweis, aufruf, CunddContent_div);
	}
}




