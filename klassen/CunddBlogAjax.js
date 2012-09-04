//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Klasse CunddBlogAjax()
/* Die Klasse bietet die grundlegenden Methoden zum Senden einer Ajax-Anfrage im 
Zusammenhang mit dem Blog-System von Cundd. Außerdem werden die benötigten Event-
Listener automatisch installiert. Die Klasse handhabt das Stellen der Anfrage, 
sowie die Ausgabe des Ergebnisses. */
/* WICHTIG: jQuery-Bibliothek benötigt! Zum Stellen von Ajax-Anfragen wird die 
jQuery-Bibliothek benötigt. Weitere Informationen unter http://jquery.com/ */
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddBlogAjax(CunddBlogMain_instanz){
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Variablen definieren
		/*this.auto_refresh = false; /* Gibt an ob der Inhalt nach dem Speichern neu ge-
									laden werden soll. */
		this.CunddBlogMain_instanz = CunddBlogMain_instanz; /* Speichert die "CunddBlog"-Instanz 
			zur welcher diese "CunddBlogAjax"-Instanz gehört. */
		
		this.CunddContent = window.document.getElementById(this.CunddBlogMain_instanz.CunddBlog_instanz).
			parentNode;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "eintrag_inhalt_aendern()" verarbeitet die Daten des Formulars. */
	CunddBlogAjax.prototype.eintrag_inhalt_aendern = function(feld_para){
		diese_instanz = this;
		
		/* "feld_para" ist ein Feld in einem Formular des Blogs. Um welches Formular es sich 
		handelt wird mit "parentNode" ermittelt. */
		if(feld_para.className.match('entryForm')){
			inhalt_node_id = feld_para.id; 
		} else {
			inhalt_node_id = feld_para.parentNode.id;
		}
		
		
		inhalt = $("#"+inhalt_node_id).serializeArray();
		
		/* Den passenden Aufruf, der an "CunddAjax.php" weitergeleitet wird, in Form des 
		"aufruf"-Objekts erstellen. */
		var aufruf = {name: "aufruf", value: "CunddInhalt"};
		inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
		
		new CunddUpdate({
						datei: CunddAjaxPHP_verweis,
						data: inhalt,
						targetId: CunddContent_div,
						completeFunction: "CunddBlog("+this.CunddBlogMain_instanz.CunddBlog_instanz+","+this.CunddBlogMain_instanz.max_eintraege+",gruppen)"
						}
		);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "eintrag_inhalt_aendern()" verarbeitet die Daten des Formulars. */
	CunddBlogAjax.prototype.eintrag_loeschen = function(feld_para){
		diese_instanz = this;
		
		/* "feld_para" ist ein Feld in einem Formular des Blogs. Um welches Formular es sich 
		 handelt wird mit "parentNode" ermittelt. */
		inhalt_node_id = feld_para.parentNode.id;
		inhalt = $("#"+inhalt_node_id).serializeArray();
		
		/* Den passenden Aufruf, der an "CunddAjax.php" weitergeleitet wird, in Form des 
		 "aufruf"-Objekts erstellen. */
		var aufruf = {name: "aufruf", value: "CunddInhalt::delete"};
		inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
		
		new CunddUpdate({
						datei: CunddAjaxPHP_verweis,
						data: inhalt,
						targetId: CunddContent_div,
						completeFunction: "CunddBlog("+this.CunddBlogMain_instanz.CunddBlog_instanz+","+this.CunddBlogMain_instanz.max_eintraege+",gruppen)"
						}
		);
	}
	
	
	