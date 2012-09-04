//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Die Javascript-Klasse "CunddBlogMain" instanziert die nötigen Instanzen der JavaScript-
Klassen. */
// class CunddBlogMain{	
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
	// Konstruktor
	function CunddBlogMain(CunddBlog_instanz, max_eintraege, gruppe){
		// Daten der zugehörigen "CunddBlog"-Instanz speichern
		this.CunddBlog_instanz = CunddBlog_instanz;
		this.max_eintraege = max_eintraege;
		this.gruppe = gruppe;
		
		// Jeder Blog hat eine eigene Instanz von "CunddAjax"
		this.CunddBlogAjax_instanz = new CunddBlogAjax(this);
		// Beim Laden des Blogs wird eine Instanz von "CunddInhalt" erstellt
		this.CunddInhalt_instanz = new CunddInhalt(this);
		
		
		
	}
//}