//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Klasse CunddLink()
/* Die Klasse CunddLink bietet ein Alias auf die Klasse CunddLinkAjax. */
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	// TODO: geht nicht
	function CunddLink(CunddLink_instanz){
		this.inherits(CunddLinkAjax);
		return this
	}
// }
	
	
	
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
// Klasse CunddLinkAjax()
/* Die Klasse bietet die grundlegenden Methoden zum Senden einer Ajax-Anfrage im 
CunddSystem, außerdem werden die benötigten EventListener automatisch installiert. 
Die Klasse handhabt das Stellen der Anfrage, sowie die Ausgabe des 
Ergebnisses. */
/* WICHTIG: jQuery-Bibliothek benötigt! Zum Stellen von Ajax-Anfragen wird die 
 jQuery-Bibliothek benötigt. Weitere Informationen unter http://jquery.com/ */
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddLinkAjax(CunddLink_instanz){
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
		// Variablen definieren
		this.name = "daniel";
		this.CunddLink_instanz = CunddLink_instanz; /* Speichert die "CunddLink"-Instanz 
			zur welcher diese "CunddAjax"-Instanz gehört. */
		
		this.CunddContent = window.document.getElementById(CunddContent_div);
		
		this.EventListener_setzen();
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "EventListener_setzen" installiert die EventListener für die Links. 
	Der Befehl bzw. ein Hinweis auf den Befehl der auszuführen ist wird im als Attri-
	but "class" im ersten Kind-Element des Links gespeichert und beim Klick auf den 
	Link als Parameter übergeben. */
	CunddLinkAjax.prototype.EventListener_setzen = function(){
		var CunddLinkAjax_instanz = this;
		
		/*
		for(var i = 0; i < window.document.getElementById(this.CunddLink_instanz).childNodes.length; i++){
			// Überprüfen ob der aktuelle div eine Link-Liste ist
			var aktueller_div = window.document.getElementById(this.CunddLink_instanz);
			var ist_link;
			
			if(aktueller_div.firstChild.className == "link"){
				ist_link = true;
			} else {
				ist_link = false;
			}
			
			// Wenn es eine Link-Liste ist
			if(ist_link){
				
				// Für jedes Element des divs einen EventListener setzen
				for(var i = 0; i < aktueller_div.childNodes.length; i++){
					//alert(diese_instanz.name)
					/*
					aktuelles_feld.onblur = function(){
								CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_inhalt_aendern(this);
							}
					*/
		/*			var aktueller_link = aktueller_div.childNodes[i];
					//aktueller_link.style.backgroundColor = "#ff9999";
					aktueller_link.onclick = function(){
						CunddLinkAjax_instanz.link_click_func(this);
					}
				}
			}
		}
		if(!ist_link){
		//	alert("Ist Blog");
		}
		/* */
		
		$('.link').click(
				function(){
					CunddLinkAjax_instanz.link_click_func(this);
				}
		)
		
		// Die EventListener für das Einblenden der Unterpunkte der Navigation
		$('.hasChildren').click(
			function(){
				CunddLinkAjax.showChildren(this);
				CunddLinkAjax_instanz.link_click_func(this);
				
			}
		);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "link_click_func" ruft stellt eine Ajax-Anfrage zum Anzeigen eines 
	anderen Inhalts. Der per Ajax gesendete Aufruf wird im Attribut "class" des ersten 
	Kind-Elements gespeichert. */
	CunddLinkAjax.prototype.link_click_func = function(ausloeser){
		parameter = new Object();
		parameter.aufruf = ausloeser.firstChild.className;
		
		new CunddUpdate(CunddAjaxPHP_verweis, parameter, CunddContent_div);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "inhalt_aendern()" ändert den Inhalt des angegebenen div entsprechend dem 
	gesendeten Aufruf. */
	CunddLinkAjax.prototype.inhalt_aendern = function(parameter){
		new CunddUpdate(CunddAjaxPHP_verweis, parameter, CunddContent_div);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Variante der Methode "inhalt_aendern()". */
	CunddLinkAjax.inhalt_aendern = function(parameter){
		new CunddUpdate(CunddAjaxPHP_verweis, parameter, CunddContent_div);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode blendet die erste Unterebene des angeklickten Elements ein. */
	CunddLinkAjax.showChildren = function(caller){
		// Die Kind-Elemente einblenden
		var callerId = caller.id;
		var childClass = callerId.replace('CunddLink','childOf');
		
		childSelector = '.' + childClass;
		$(childSelector).css('display','block');
		
		// Den EventListener zum Ausblenden setzen
		$(caller).click(
			function(){
				CunddLinkAjax.hideChildren(this);
			}	
		)
		/* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode blendet alle Unterebenen des angeklickten Elements aus. */
	CunddLinkAjax.hideChildren = function(caller){
		// Die Kind-Elemente ausblenden
		var callerId = caller.id;
		var childClass = callerId.replace('CunddLink','childOf');
		
		childSelector = '.' + childClass;
		$(childSelector).css('display','none');
		
		
		$(childSelector).each(
			function(){
				CunddLinkAjax.hideChildren(this);
			}
		);
		
		// Den EventListener zum Ausblenden setzen
		$(caller).click(
			function(){
				CunddLinkAjax.showChildren(this);
			}	
		)
		/* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode blendet alle Unterebenen des angeklickten Elements ein. */
	CunddLinkAjax.showAllChildren = function(caller){
		// Die Kind-Elemente einblenden
		var callerId = caller.id;
		var childClass = callerId.replace('CunddLink','childOf');
		
		childSelector = '.' + childClass;
		$(childSelector).css('display','block');
		
		
		$(childSelector).each(
			function(){
				CunddLinkAjax.showChildren(this);
			}
		);
		
		// Den EventListener zum Ausblenden setzen
		$(caller).click(
			function(){
				CunddLinkAjax.hideChildren(this);
			}	
		)
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode blendet alle Unterebenen ein. */
	CunddLinkAjax.showAll = function(){
		$('.isChild').css('display','block');
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode blendet alle Unterebenen aus. */
	CunddLinkAjax.hideAll = function(){
		$('.isChild').css('display','none');
	}
	
	
	