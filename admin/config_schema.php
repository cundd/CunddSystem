# Die Konfigurationsdatei für CunddBlog.
# Boolean'sche Wert werden durch 0 bzw. 1 definiert.
# Das Verwenden von Sonderzeichen wie auch der Einsatz
# von Tabulator ist nicht erlaubt.


# Einstellungen zur Ausgabe der Informationen eines Eintrags
# Ist der Wert 1 dann wird die Information dargestellt.
zeige_ersteller = 1
zeige_erstellungsdatum = 1
zeige_erstellungszeit = 1
zeige_bearbeiter = 1
zeige_bearbeitungsdatum = 1
zeige_bearbeitungszeit = 1
zeige_title = 1
zeige_subtitle = 0
zeige_beschreibung = 0
zeige_text = 1
zeige_bildlink = 1
zeige_eventdatum = 0
zeige_rechte = 1
zeige_gruppe = 1
zeige_sprache = 1
zeige_schluessel = 1

zeige_quote = 0
zeige_zusatzinfos = 0
zeige_toolbar = 1

# Die Toolbar für den gesamten Content-div der CunddBlog-Instanz
zeige_content_toolbar = 1

# Anzahl der Einträge die pro Seite sichtbar sein sollen. 
# Wenn es kein Limit geben soll setzen Sie den Wert auf 0
zeige_eintrag_limit = 0

# Die URL der PHP-Datei die per Ajax aufgerufen wird. 
# Diese muss im Quellordner der Datei sein, aus welcher der Ajax-Befehl aufgerufen wird
CunddAjaxPHP_verweis = DER RELATIVE PFAD ZUR SYSTEM_AJAX_DATEI

# Der Pfad zum Haupt-Stylesheet relativ zur CunddAjaxPHP_verweis-Datei
CunddMainStylesheet = main.css


# Der Pfad zu den Dateien des CunddSystems
# BEISPIEL INDEX DATEI UNTER www.domain.com/folder/
BasePath = folder/
CunddBasePath = Cundd/
CunddBaseUrl = www.domain.com

# Der Dateiname des Stylesheets der in den Kalender eingebunden werden soll
# Wenn 0 dann kein Stylesheety
calendarStylesheet = ../../../../main.css

# Angabe für die ID des HTML-DIVs der geändert werden soll
CunddContent_div = CunddContent

# Angabe für die Art des "EventListeners" zum Ändern der Einträge
# Mögliche Werte sind "blur", "return" und "save"
# Aktuell ist nur "save" erlaubt
Cundd_eventlistener_eintrag_aendern = save

# Angabe für die Dauer in Millisekunden die vor einem automatischen Redirect vergehen
Cundd_redirect_zeit = 100

# Gibt an wer fremde Benutzer sehen kann
# 1 (binär: 0001) = alle
# 2 (binär: 0010) = eingeloggte Benutzer
# 4 (binär: 0100) = alle Mitglieder der Gruppe "verwalter"
# 8 (binär: 1000) = alle Mitglieder der Gruppe "root"
benutzerliste_sichtbarkeit = 4

# Gibt an in welcher Gruppe ein Benutzer sein muss um einen neuen Benutzer zu erstellen
# n = alle
bedingung_neuer_benutzer = n
CunddBenutzer_group_public_register = 3

# Gibt an in welcher Gruppe ein Benutzer sein muss um eine Datei hochzuladen
# n = alle
bedingung_neues_file = n

# Gibt an ob das Schreiben für Besucher grundsätzlich erlaubt ist oder nicht
oeffentlich_schreiben = 0

# Gibt an ob der Blog-Inhalt nach dem Speichern neu geladen werden soll.
Cundd_auto_refresh = 1

# Gibt an welche Sprachbibliothek gelesen werden soll
CunddLangLib = CunddLangLib_de

# Gibt an wo CunddFiles die Uploads speichert
CunddFiles_upload_dir = /Cundd/uploads/

# Gibt an ob die Dateien auf einem entfernten Server gespeichert werden sollen
CunddFiles_use_ftp = 0
# CunddFiles_ftp_server = ftp.brutzel.net
CunddFiles_ftp_server = 85.25.130.64
CunddFiles_ftp_server_web_representation = http://www.vbc-feldkirch.at
CunddFiles_ftp_user = webusr17
CunddFiles_ftp_password = Dup2gunu
CunddFiles_ftp_base_path = /htdocs

# Gibt an ob TinyMCE als Editor verwendet werden soll
CunddTinyMCE_enable = 1
CunddHead_enabled = 1

# Einstellungen zur Ausgabe der Informationen eines Eintrags
# Ist der Wert 1 dann wird die Information dargestellt.
zeige_file_title = 1
zeige_file_dateiname = 1
zeige_file_originalname = 1
zeige_file_parent = 1
zeige_file_beschreibung = 1
zeige_file_tags = 0
zeige_file_copyright = 0
zeige_file_type = 1
zeige_file_size = 1
zeige_file_ersteller = 1
zeige_file_erstellungsdatum = 1
zeige_file_erstellungszeit = 1
zeige_file_bearbeiter = 0
zeige_file_bearbeitungsdatum = 0
zeige_file_bearbeitungszeit = 0
zeige_file_rechte = 0
zeige_file_gruppe = 0
zeige_file_geloescht = 0

# Das root-Element im CunddFiles-System
CunddFiles_root_group = 1083


# The ini-php-file
ini_file = /Applications/MAMP/conf/php5

# Ende der Config-Datei