//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Javascript-Klasse "CunddInhalt" bietet verschiedene Methoden für den Umgang mit 
Einträgen. Die Methode "neu()" zum Beispiel erstellt einen neuen, leeren Eintrag. */
// class CunddInhalt{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddInhalt(CunddBlogMain_instanz){
		this.testName = "CunddInhalt";
		this.CunddBlogMain_instanz = CunddBlogMain_instanz; /* Speichert die "CunddBlog"-Instanz 
			zur welcher diese "CunddInhalt"-Instanz gehört. */
		
		this.content_div = window.document.getElementById(CunddBlogMain_instanz.CunddBlog_instanz);
		var diese_instanz = this; // Ein Zeiger auf die aktuelle Instanz von "CunddInhalt"
		
		// Leeren Eintrag verstecken
		this.neu_eintrag = window.document.getElementById("eintrag");
		if(this.neu_eintrag){
			this.neu_eintrag.style.display = "none";
		}
		
		
		
		// Die EventListener für die Felder und den "Verstecken"-Button setzen
		this.EventListener();
		this.neuer_eintrag_verstecken('init');
		this.textarea_groesse();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode installiert EventListener die beobachten ob in einer Textarea RETURN ge-
	drückt wird. Wenn das geschieht erhöht die Methode die Anzahl der Reihen automatisch. */
	CunddInhalt.prototype.textarea_groesse = function(){
		diese_instanz = this;
		//CunddBlogMain_instanz = this.CunddBlogMain_instanz;
		
		// EventListener für jede Textarea
		if(CunddContent_div && window.document.getElementById(CunddContent_div)){
			for(var j = 0; j < window.document.getElementById(CunddContent_div).getElementsByTagName("textarea").length; j++){
				var aktuelles_feld = window.document.getElementById(CunddContent_div).getElementsByTagName("textarea")[j];
				
				/* Das umgeht einen Fehler: Die per Ajax erzeugte Elemente konnten per 
				"getElementsByTagName" nicht erfasst werden. */
				aktuelles_feld_id = aktuelles_feld.getAttribute("id");
				aktuelles_feld = window.document.getElementById(aktuelles_feld_id);
				
				//window.$(this.CunddLink_instanz).style.backgroundColor = "#ff9999";
				//aktuelles_feld.style.backgroundColor = "#99ff99"; // VERSUCH */
				
				// Die Größe der Textfelder automatisch anpassen
				diese_instanz.textarea_groesse_auto(aktuelles_feld);
				
				aktuelles_feld.onkeydown = function(dieser_event){
						if(!dieser_event){
							dieser_event = window.event;
						}
						
						if(dieser_event.keyCode == 13){
							diese_instanz.textarea_groesse_auto(this);
						} else if(dieser_event.ctrlKey){
							diese_instanz.textarea_groesse_auto(this);
						}
				}
			}
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode passt die Anzahl der Reihen der übergebenen Textarea automatisch an. */
	CunddInhalt.prototype.textarea_groesse_auto = function(feld_para){
		// Die Größe der Textfelder automatisch anpassen
			// Infos: http://tuckey.org/textareasizer/
			
			// Größe entsprechend der Umbrüch
			/* Der Inhalt der Textarea wird mittels eines regulären Ausdrucks zerlegt. Die 
			Elemente bilden die Elemente eines Arrays und dessen Länge gibt die Anzahl der 
			Vorkommnisse der Suchbegriffe. */
			suche = /\n|\n\r|\r|<br>|<br \/>|<br>.<\/br>/;
			teile = feld_para.value.split(suche);
			entsprechend_umbrueche = teile.length;
			
			// Größe nach Anzahl der Zeichen
			// Der Wert für die Anzahl der Zeichen pro Reihe wird geschätzt
			zeichen_pro_zeile = 60;
			entsprechend_zeichen = Math.floor(feld_para.value.length / zeichen_pro_zeile);
			
			// Überprüfen welcher Wert größer ist
			if(entsprechend_umbrueche < entsprechend_zeichen){
				feld_para.setAttribute("rows", entsprechend_zeichen);
			} else {
				feld_para.setAttribute("rows", entsprechend_umbrueche);
			}
			
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode erhöht die Anzahl der Reihen der übergebenen Textarea um 1. */
/*	CunddInhalt.prototype.textarea_groesse_aendern = function(feld_para){
		diese_instanz = this;
		reihen_zahl = 0;
		reihen_zahl = Math.floor(window.$(feld_para.id).getAttribute("rows")) + 1;
		window.$(feld_para.id).setAttribute("rows", reihen_zahl);
	} /* */
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "EventListener" installiert die EventListener zum Speichern der Formular-
	 * Felder. */
	CunddInhalt.prototype.EventListener = function(){
		diese_instanz = this;
		CunddBlogMain_instanz = this.CunddBlogMain_instanz;
		// EventListener installieren entsprechend der Einstellung in config.php
		switch(Cundd_eventlistener_eintrag_aendern){
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			case "blur":
				for(i = 0; i < this.content_div.getElementsByTagName("form").length; i++){
					var aktuelles_formular = this.content_div.getElementsByTagName("form")[i];
					
					/* Überprüfen ob das aktuelle Formular nicht der Button zum Einblenden des 
					leeren Eintrags ist. */
					if(aktuelles_formular.name != "content_toolbar"){
						// EventListener für jedes Input-Feld
						for(var j = 0; j < aktuelles_formular.getElementsByTagName("input").length; j++){
							var aktuelles_feld = aktuelles_formular.getElementsByTagName("input")[j];
							
							// Überprüfen ob der Input-Typ gleich "text" ist
							if(aktuelles_feld.type == "text"){
								
								
								/* Das umgeht einen Fehler: Die per Ajax erzeugte Elemente konnten per 
								"getElementsByTagName" nicht erfasst werden. */
								//aktuelles_feld_id = window.$(aktuelles_feld).getAttribute("id");
								/*alert("aktuelles_feld: "+aktuelles_feld + " - aktuelles_feld.id: "+aktuelles_feld.id
									   + " - aktuelles_feld.className: "+ aktuelles_feld.className
									   + " - aktuelles_feld.name: "+aktuelles_feld.name)//*/
								
								aktuelles_feld_id = window.$(aktuelles_feld).getAttribute("id");
								aktuelles_feld = window.$(aktuelles_feld_id);
								 
								aktuelles_feld.style.backgroundColor = "#ff9999"; // VERSUCH */
								
								aktuelles_feld.onblur = function(){
										CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_inhalt_aendern(this);
									}
							}
						}
						
						// EventListener für jede Textarea
						for(var j = 0; j < aktuelles_formular.getElementsByTagName("textarea").length; j++){
							var aktuelles_feld = aktuelles_formular.getElementsByTagName("textarea")[j];
							
							/* Das umgeht einen Fehler: Die per Ajax erzeugte Elemente konnten per 
							"getElementsByTagName" nicht erfasst werden. */
							aktuelles_feld_id = window.$(aktuelles_feld).getAttribute("id");
							aktuelles_feld = window.$(aktuelles_feld_id);
							
							//window.$(this.CunddLink_instanz).style.backgroundColor = "#ff9999";
							aktuelles_feld.style.backgroundColor = "#99ff99"; // VERSUCH */
							
							aktuelles_feld.onblur = function(){
									CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_inhalt_aendern(this);
								}
						}
					}
				}
				break;
			
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			case "save":
				$('.toolbar_button_save').click(
					function(){
						CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_inhalt_aendern(this.parentNode);
					}
				);
				/*
				// Alle Elemente mit der Klasse "toolbar" ermitteln
				for(var i = 0; i < window.document.getElementsByName("toolbar").length; i++){
					var toolbar = window.document.getElementsByName("toolbar")[i];
					for(var j = 0; j < toolbar.childNodes.length; j++){
						if(toolbar.childNodes[j].name == "toolbar_button_save"){
							toolbar.childNodes[j].onclick = function(){
								CunddBlogMain_instanz.CunddBlogAjax_instanz.
									eintrag_inhalt_aendern(this.parentNode);
							}
						}
					}
				}
				/* */
				break;
			
			//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
			default:
				alert("Cundd_eventlistener_eintrag_aendern: "+Cundd_eventlistener_eintrag_aendern);
		}
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Den EventListener für den "delete"-Button setzen
		$('.toolbar_button_delete').click(
				function(){
					loeschen = confirm("delete?");
					if(loeschen){
						CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_loeschen(this.parentNode);
					}
				}
		);
		/*
		// Alle Elemente mit dem Namen "toolbar" ermitteln
		for(var i = 0; i < window.document.getElementsByName("toolbar").length; i++){
			var toolbar = window.document.getElementsByName("toolbar")[i];
			for(var j = 0; j < toolbar.childNodes.length; j++){
				if(toolbar.childNodes[j].name == "toolbar_button_delete"){
					toolbar.childNodes[j].onclick = function(){
						loeschen = confirm("delete?");
						if(loeschen){
							CunddBlogMain_instanz.CunddBlogAjax_instanz.
								eintrag_loeschen(this.parentNode);
						}
					}
				}
			}
		}
		/* */
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Den EventListener für TinyMCE setzen
		if(CunddTinyMCE_enable){
			var elementSpecification = 'textarea.'+CunddTinyMCE_initForCSSClass;
			elementSpecification = '.'+CunddTinyMCE_initForCSSClass;
			$(elementSpecification).click(
				function(){
					CunddTinyMCE.initAndFocus(this);
				}
			);
		}
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Das Senden abfangen
		$('.eintrag form').submit(
			function() {
				CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_inhalt_aendern(this);
				return false
			}
		);
		/* */
		
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Den EventListener für das Anzeigen des neuen Eintrags setzen
		/*
		if($("#eintrag.eintrag")){
			var button_id = "neuer_eintrag_anzeigen_" + this.CunddBlogMain_instanz.CunddBlog_instanz;
			var buttonIdSelector = '#'+button_id;
			this.neu_button = window.document.getElementById(button_id);
			
			var thisInstance = this;
			$(buttonIdSelector).click(
				function(){
					thisInstance.neuer_eintrag_zeigen(thisInstance);
				}
			);
		}
		/* */
		
		
		
		return this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode setzt die On-Click EventListener für das Initiieren des 
	 * TinyMCE-Editors beim Klick in einen entsprechenden di. */
/*	CunddInhalt.setTinyMCEInitEventListener = function(){
		/*
		diese_instanz = this;
		CunddBlogMain_instanz = this.CunddBlogMain_instanz;
		
		// 
		// CunddTinyMCE_enable
		// CunddTinyMCE_initForCSSClass
		
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		/* Den EventListener für das Klicken in eine entsprechende Textarea setzen wenn in der 
		 * Konfiguration CunddTinyMCE_enable auf TRUE gesetzt ist.
		 */
/*		if(CunddTinyMCE_enable){
			var classString = '[class*='+CunddTinyMCE_initForCSSClass+']';
			var elements = $(classString);
			
			for(var element in elements){
				//alert(element)
			    //element.style.color = '#ff0000';
			}

			
			/*
			for(var i = 0; i < window.document.getElementsByTagName("textarea").length; i++){
				var currentTextarea = window.document.getElementsByTagName("textarea")[i];
				if(currentTextarea.className == CunddTinyMCE_initForCSSClass){
					currentTextarea.onclick = function(){
						try (
							// TODO: set corresponding command
							tinymce.execCommand()
						);
					}	
				}
			}
			/* */
/*		}
		//return this;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "neuer_eintrag_zeigen()" macht den leeren Eintrag sichtbar. */
	CunddInhalt.prototype.neuer_eintrag_zeigen = function(){
		this.neu_eintrag.style.display = "block";
		neu_eintrag_temp = this.neu_eintrag;
		diese_instanz = this;
		
		// Funktion umkehren
		this.neu_button.value = "-";
		this.neu_button.onclick = function(){
			diese_instanz.neuer_eintrag_verstecken(diese_instanz);
		}
		
		return this;
		
		/*
		 * this.neu_eintrag.style.display = "block";
		
		var buttonId = "neuer_eintrag_anzeigen_" + this.CunddBlogMain_instanz.CunddBlog_instanz;
		
		
		neu_eintrag_temp = this.neu_eintrag;
		diese_instanz = this;
		
		// Funktion umkehren
		this.neu_button = window.document.getElementById(buttonId);
		this.neu_button.value = "-";
		
		
		this.neu_button.onclick = function(){
			diese_instanz.neuer_eintrag_verstecken(diese_instanz);
		}
		
		return this;
		 */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "neuer_eintrag_verstecken()" macht den leeren Eintrag unsichtbar. Der 
	 * Parameter "init" ist optional. Wenn er gegeben ist wird nicht überprüft ob das 
	 * Formular leer ist. */
	CunddInhalt.prototype.neuer_eintrag_verstecken = function(dontCheckIfEmpty){
		button_id = "neuer_eintrag_anzeigen_" + this.CunddBlogMain_instanz.CunddBlog_instanz;
		this.neu_button = window.document.getElementById(button_id);
		
		// Variablen deklarieren
		leeres_formular_vorhanden = false;
		inhalt_ist_leer = 1;
		diese_instanz = this;
		
		// Überprüfen ob der leere Eintrag überhaupt erstellt wurde
		for(var i = 0; i < window.document.getElementsByTagName("form").length; i++){
			if(window.document.getElementsByTagName("form")[i].name == "formular"){
				leeres_formular_vorhanden = true;
			}
		}
		
		if(leeres_formular_vorhanden){			
			// Überprüfen ob die Felder leer sind
			/* WICHTIG: "i = 0" wäre der Save-Button, 1 = delete-Button, 
			x.length - 0 = Tabellenname, x.length - 1 = schluessel,
			x.length - 2 = rechte */
			for(var i = 2; i < window.document.formular.elements.length; i++){
				if(window.document.formular.elements[i].value != "" && 
					window.document.formular.elements[i].type != "hidden" &&
					window.document.formular.elements[i].type != "button" &&
					window.document.formular.elements[i].type != "submit"){
					inhalt_ist_leer *= 0;
				}
			}
			
			if(dontCheckIfEmpty == 'init'){
				inhalt_ist_leer = true;
			}
			
			// Abfragen ob der neue Eintrag gelöscht werden soll
			if(!inhalt_ist_leer){
				var loeschen = confirm("delete?");
				if(loeschen){
					// Neuen Eintrag leeren
					for(var i = 0; i < window.document.formular.elements.length - 1; i++){
						// Wenn er nicht ein Button oder hidden ist
						if(window.document.formular.elements[i].type != "hidden" &&
						window.document.formular.elements[i].type != "button" &&
						window.document.formular.elements[i].type != "submit"){
							window.document.formular.elements[i].value = "";
						}
					}
					inhalt_ist_leer = 1;
				}
			}
			
			
		
			if(inhalt_ist_leer){
				this.neu_eintrag = window.document.getElementById("eintrag");
				this.neu_button = window.document.getElementById(button_id);
				
				if(this.neu_eintrag && this.neu_button){
					this.neu_eintrag.style.display = "none";
					neu_eintrag_temp = this.neu_eintrag;
				
				
					// Funktion umkehren
					this.neu_button.value = "+";
					this.neu_button.onclick = function(){
						diese_instanz.neuer_eintrag_zeigen(diese_instanz);
					}
				}
			}
		}
		
		return this;
	}
	
	
	
	