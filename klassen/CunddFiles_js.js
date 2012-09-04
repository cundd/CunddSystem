//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Javascript-Klasse "CunddFiles_js" bietet verschiedene Methoden für den Umgang 
mit Benutzern. */
/* WICHTIG: Das jQuery-Plugin "uploadify" wird benötigt. Weitere Informationen unter 
 www.uploadify.com */
// class CunddFiles_js{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddFiles_js(aufrufer){
		this.mouse_over(aufrufer);
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode für den MouseOver-Effekt. */
	CunddFiles_js.prototype.mouse_over = function(aufrufer){
		aufrufer.alte_farbe = aufrufer.style.backgroundColor;
		aufrufer.style.backgroundColor = "#FF7300";
		
		aufrufer.alte_klasse = aufrufer.className;
		aufrufer.className = "hover";
		
		var diese_instanz = this;
		
		aufrufer.onclick = function(){
			/* Den passenden Aufruf, der an "CunddAjax.php" weitergeleitet wird, in Form des 
			 "aufruf"-Objekts erstellen. */
			var aufruf = {name: "aufruf", value: "CunddFiles"};
			var inhalt =[];
			inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
			/* Das alte File als Objekt erstellen. */
			var old_file = {name: "old_file_id", value: aufrufer.id};
			inhalt.push(old_file); // "old_file"-Objekt anhängen
			
			
			new CunddUpdate({
							datei: CunddAjaxPHP_verweis,
							data: inhalt,
							targetId: CunddContent_div
							}
			);
			/* */
		}
		
		
		aufrufer.onmouseout = function(){
			diese_instanz.mouse_out(aufrufer);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode für den MouseOut-Effekt. */
	CunddFiles_js.prototype.mouse_out = function(aufrufer){
		aufrufer.style.backgroundColor = aufrufer.alte_farbe;
		
		aufrufer.className = aufrufer.alte_klasse;
		
		var diese_instanz = this;
		
		aufrufer.onmouseout = function(){
			diese_instanz.mouse_over(aufrufer);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "eintrag_inhalt_aendern()" verarbeitet die Daten des Formulars wenn eine
	 * Datei bearbeitet werden soll. */
	CunddFiles_js.eintrag_inhalt_aendern = function(aufrufer){
		/* "feld_para" ist ein Feld in einem Formular des Blogs. Um welches Formular es sich 
		handelt wird mit "parentNode" ermittelt. */
		var inhalt = [];
		inhalt = $('#'+aufrufer.id).serializeArray();
		
		var aufruf = {};
		// Der passende Aufruf, der an "CunddAjax.php" weitergeleitet wird
		aufruf = {name: "aufruf", value: "CunddFiles"};		
		inhalt.push(aufruf); // "Aufruf"-Objekt anhängen
		// inhalt ist ein String
		var type = {}; // ruft CunddFiles.php im "group"-Modus auf
		type = {name: "type", value: "group"};
		inhalt.push(type);
		
		new CunddUpdate({
						datei: CunddAjaxPHP_verweis,
						data: inhalt,
						targetId: CunddContent_div
						}
		); /* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode "initUploadify" initialisiert das jQuery-Plugin "uploadify". Als Para-
	 meter wird ein Form-Element erwartet, das Input vom Typ "file" enthält. */
	CunddFiles_js.initUploadify = function(aufrufer){
		var uploadifyFormElementObject; /* Das Element das zum Senden der Files angsprochen 
										wird */
		var scriptDataObject;
		
		// Für jedes file-input-Feld im Formular
		for(var i = 0; i < aufrufer.elements.length; i++){
			if(aufrufer.elements[i].type == "file"){
				// Den Namen des Input-Felds speichern
				var fileDataName = aufrufer.elements[i].name;
				
				// Das Objekt zur Übergabe von Parametern beim Upload
				scriptDataObject = {'aufruf':'CunddFiles'};
				var uploadifyFilePath = './' + CunddPath_CunddBasePath + CunddPath_Cundd_class_path + 'CunddFiles/uploadify/';
				
				// uploadify-Parameter-Objekt erstellen
				var uploadify_para = {
					'uploader': uploadifyFilePath + 'uploadify_uploader.swf',
					'script': CunddAjaxPHP_verweis,
					'scriptData':scriptDataObject,
					'fileDataName': fileDataName,
					'scriptAccess':'always',
					'cancelImg': uploadifyFilePath + 'uploadify_cancel.png',
					'folder': CunddFiles_upload_dir,
					'hideButton': false,
					'auto': false,
					'multi': false,
					'wmode': 'transparent',
					'onComplete': CunddFiles_js.postUpload,
					'onError': CunddFiles_js.errorHandler
						}
				/* */
				// uploadify anwenden
				uploadifyFormElementObject = '#'+aufrufer.elements[i].id;
				var upFE = aufrufer.elements[i];
				$(uploadifyFormElementObject).fileUpload(uploadify_para);
			}
		}
		
		
		// EventListener für das Formular
		var formular = window.document.getElementById(aufrufer.id);
		formular.onsubmit = function(){
			if(aufrufer.files_title.value){
				// Das Formular serialisieren
				var scriptDataString = $('#'+aufrufer.id).serialize();
				//var scriptDataString = $('#'+aufrufer.id).serializeArray();
				
				//var aufruf = {name: "aufruf", value: "CunddFiles"};
				//scriptDataString.push(aufruf);
				
				scriptDataString = escape(scriptDataString);
				
				$(uploadifyFormElementObject).fileUploadSettings('scriptData', '&' + scriptDataString + '&aufruf=CunddFiles');
				//$(uploadifyFormElementObject).fileUploadSettings(scriptDataString);
				
				// Den Senden-Button deactivieren
				aufrufer.files_submit.setAttribute("disabled", "disabled");
				//aufrufer.files_submit.disabled = 'disabled';
				
				// Das File hochladen
				$(uploadifyFormElementObject).fileUploadStart();
			}
			
			return false;
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode wird nach einem erfolgreichen Upload aufgerufen. */
	CunddFiles_js.postUpload = function(event, queueID, fileObj, response, data){
		var form = event.target.parentNode;
		form.files_submit.removeAttribute("disabled");
		

		// Das Formular leeren
		for(var i = 0; i < form.elements.length; i++){
			if(form.elements[i].type == "text"){
				// Den Namen des Input-Felds speichern
				var currentField = form.elements[i];
				currentField.value = '';
			}
		}
		/* */
		
		return true;
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die Methode wird nach einem erfolgreichen Upload aufgerufen. */
	CunddFiles_js.errorHandler = function(event, queueID, fileObj, errorObj){
		var form = event.target.parentNode;
		form.files_submit.removeAttribute("disabled");
		
		window.alert(errorObj.text);
		
		return true;
	}
	
	/* */
	
	
	
	
	