//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Javascript-Klasse "CunddBenutzer_js" bietet verschiedene Methoden für den Umgang 
mit Benutzern. */
// class CunddBenutzer_js{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddBenutzer_js(aufrufer){
		this.mouse_over(aufrufer);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode für den MouseOver-Effekt. */
	CunddBenutzer_js.prototype.mouse_over = function(aufrufer){
		aufrufer.alte_farbe = aufrufer.style.backgroundColor;
		aufrufer.style.backgroundColor = "#FF7300";
		
		aufrufer.alte_klasse = aufrufer.className;
		aufrufer.className = "hover";
		
		diese_instanz = this;
		
		aufrufer.onclick = function(){
			/* Den passenden Aufruf, der an "CunddAjax.php" weitergeleitet wird, in Form des 
			 "aufruf"-Objekts erstellen. */
			var aufruf = {name: "aufruf", value: "CunddBenutzer"};
			var inhalt =[];
			inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
			/* Den alten Benutzer als Objekt erstellen. */
			var alter_benutzer = {name: "alter_benutzer", value: aufrufer.id};
			inhalt.push(alter_benutzer); // "alter_benutzer"-Objekt anhängen
			
			
			new CunddUpdate({
							datei: CunddAjaxPHP_verweis,
							data: inhalt,
							targetId: CunddContent_div
							}
			);
		}
		
		
		aufrufer.onmouseout = function(){
			diese_instanz.mouse_out(aufrufer);
		}
	}
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode für den MouseOut-Effekt. */
	CunddBenutzer_js.prototype.mouse_out = function(aufrufer){
		aufrufer.style.backgroundColor = aufrufer.alte_farbe;
		
		aufrufer.className = aufrufer.alte_klasse;
		
		diese_instanz = this;
		
		aufrufer.onmouseout = function(){
			diese_instanz.mouse_over(aufrufer);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "eintrag_inhalt_aendern()" verarbeitet die Daten des Formulars. */
	CunddBenutzer_js.eintrag_inhalt_aendern = function(aufrufer){
		/* "feld_para" ist ein Feld in einem Formular des Blogs. Um welches Formular es sich 
		handelt wird mit "parentNode" ermittelt. */
		var inhalt = [];
		inhalt = $('#'+aufrufer.id).serializeArray();
		
		var aufruf = {};
		// Der passende Aufruf, der an "CunddAjax.php" weitergeleitet wird
		aufruf = {name: "aufruf", value: "CunddBenutzer"};
		
		inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
		
		new CunddUpdate({
						datei: CunddAjaxPHP_verweis,
						data: inhalt,
						targetId: CunddContent_div
						}
						);
	}
	