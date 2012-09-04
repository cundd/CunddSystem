//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Javascript-Klasse "CunddTinyMCE" bietet verschiedene Methoden für den Umgang mit 
dem TinyMCE-Plugin. */
// class CunddTinyMCE{
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddTinyMCE(){
	
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode setzt die EventListener für die TinyMCE-Felder. */
	CunddTinyMCE.addEventListener = function(){
		if(CunddTinyMCE_enable){
			var elementSpecification = 'textarea.'+CunddTinyMCE_initForCSSClass;
			elementSpecification = '.'+CunddTinyMCE_initForCSSClass;
			$(elementSpecification).click(
				function(){
					CunddTinyMCE.initAndFocus(this);
				}
			);
		}
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode initialisiert die TinyMCE-Instanz und setzten den Fokus auf 
	 * diese. */
	CunddTinyMCE.initAndFocus = function(caller){
		var timeoutTime = 1000;
		
		// Die Input-id ermitteln
		var input = caller.id; 
		input = input.replace('_output','_input')
		
		// Den div ausblenden
		$('#'+caller.id).css('display','none');
		
		
		// Die Textbox einblenden
		$('#'+input).css('display','block');
		$('#'+input).tinymce(CunddTinyMCE_settings);
		
		
		/* TODO: setFocus -> input not defined */
		/*
		var functionCall = "CunddTinyMCE.setFocus('" + input + "')";
		var timeout = setTimeout(functionCall,timeoutTime);
		/* */
		
		
		
		
		//$(caller).tinymce().execCommand('mceFocus',false,false);
		//$(this).tinymce().execCommand('mceFocus', false, this.id);
		// $().execCommand('mceFocus', true, this);
		/* */
	
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode setzt die angegebene Instanz. */
	CunddTinyMCE.setFocus = function(callerId){
		var timeoutTime = 1000;
		
		var caller = $("#"+callerId);
		var tinymceInstance = caller.tinymce();
		if(tinymceInstance){
			tinymceInstance.execCommand('mceFocus',true,true);
		} else {
			var functionCall = "CunddTinyMCE.setFocus('" + input + "')";
			var timeout = setTimeout(functionCall,timeoutTime);
		}
		//tinymceInstance.execCommand('mceAddControl',true,callerId);
		
		/*
		tinymceInstance.execCommand('mceRepaint',false,false);
		
		// Show the Toolbar
		//$('.mceExternalToolbar').css({'display' : 'block'});
		/* */
	}
	
	
	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	/* Die statische Methode setzt wird beim Aufruf der TinyMCE-Speicher-Funktion ausge-
	 * führt. */
	CunddTinyMCE.onSave = function(target){
		var targetElement = window.document.getElementById(target.id)
		var parentNode = targetElement.parentNode;
		
		
        /* mce.save();
        mce.remove();
		alert(event.parent)
		*/
		CunddBlogMain_instanz.CunddBlogAjax_instanz.eintrag_inhalt_aendern(targetElement);
		
		/* */
	}
//}