<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Diese Datei enthält zusätzliche Detail-Konfigurationen welche die Übersichtlichkeit
 von config.php verringern würden. */
$cundd_extra_einstellungen = array();


//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Protocol - Definitionen des Standard-Protokol. */
$cundd_extra_einstellungen["Cundd_protocol"] = 'http';


//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Attributes - Definitionen der Attribut-Listen für die verschiedenen Klassen. */
/* */
$cundd_extra_einstellungen["CunddBenutzer_AttributeList"] = array(
// array('attribute' => 'showVC','type' => 'checkboxBoolean',),
//array('attribute' => 'showVC','type' => 'radioOnOff',),
array('attribute' => 'funktion','type' => 'text',),
);


//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Files - Definitionen verschiedener Pfade und Dateinamenserweiterungen. */
$cundd_extra_einstellungen["CunddFiles_thumbnail_subdir"] = 'thumbnails/';
$cundd_extra_einstellungen["Cundd_managed_object_dir"] = 'temp/';
$cundd_extra_einstellungen["Cundd_class_path"] = 'klassen/';
$cundd_extra_einstellungen["Cundd_layout_dir"] = 'layout/';
$cundd_extra_einstellungen["Cundd_view_dir"] = 'View/';
$cundd_extra_einstellungen["Cundd_view_template_dir"] = 'template/';
$cundd_extra_einstellungen["CunddTempate_layout_file_suffix"] = '.phtml';
$cundd_extra_einstellungen["Cundd_model_dir"] = 'Model/';
$cundd_extra_einstellungen["Cundd_controller_dir"] = 'controllers/';
$cundd_extra_einstellungen["Cundd_conf_dir"] = 'conf/';
$cundd_extra_einstellungen["Cundd_conf_name"] = 'config.php';
$cundd_extra_einstellungen["Cundd_resources_dir"] = 'Resources/';
$cundd_extra_einstellungen["Cundd_collection_dir"] = 'Collection/';
$cundd_extra_einstellungen["Zend_absolute_dir"] = '/Users/daniel/Sites:/home/daniel/Sites/ZendFramework';



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Debugging - Debugging-Einstellungen. */
$cundd_extra_einstellungen["controller_display_call"] = 0;
$cundd_extra_einstellungen["controller_display_debug_all"] = 0;


//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Visibility - Einstellungen zur Sichtbarkeit. */
$cundd_extra_einstellungen["zeige_group_title"] 			= 1;
$cundd_extra_einstellungen["zeige_group_dateiname"] 		= 0;
$cundd_extra_einstellungen["zeige_group_originalname"] 		= 0;
$cundd_extra_einstellungen["zeige_group_parent"] 			= 0;
$cundd_extra_einstellungen["zeige_group_beschreibung"] 		= 1;
$cundd_extra_einstellungen["zeige_group_tags"] 				= 0;
$cundd_extra_einstellungen["zeige_group_copyright"] 		= 0;
$cundd_extra_einstellungen["zeige_group_type"] 				= 0;
$cundd_extra_einstellungen["zeige_group_size"] 				= 0;
$cundd_extra_einstellungen["zeige_group_ersteller"] 		= 0;
$cundd_extra_einstellungen["zeige_group_erstellungsdatum"] 	= 0;
$cundd_extra_einstellungen["zeige_group_erstellungszeit"] 	= 0;
$cundd_extra_einstellungen["zeige_group_bearbeiter"] 		= 0;
$cundd_extra_einstellungen["zeige_group_bearbeitungsdatum"] = 0;
$cundd_extra_einstellungen["zeige_group_bearbeitungszeit"] 	= 0;
$cundd_extra_einstellungen["zeige_group_rechte"] 			= 0;
$cundd_extra_einstellungen["zeige_group_gruppe"] 			= 0;
$cundd_extra_einstellungen["zeige_group_geloescht"] 		= 0;

$cundd_extra_einstellungen["images_show_images"]			= 1;
$cundd_extra_einstellungen["images_show_title"]				= 1;
$cundd_extra_einstellungen["images_show_dateiname"]			= 0;
$cundd_extra_einstellungen["images_show_originalname"]		= 0;
$cundd_extra_einstellungen["images_show_parent"]			= 0;
$cundd_extra_einstellungen["images_show_beschreibung"]		= 1;
$cundd_extra_einstellungen["images_show_tags"]				= 0;
$cundd_extra_einstellungen["images_show_copyright"]			= 0;
$cundd_extra_einstellungen["images_show_type"]				= 0;
$cundd_extra_einstellungen["images_show_size"]				= 0;
$cundd_extra_einstellungen["images_show_ersteller"]			= 1;
$cundd_extra_einstellungen["images_show_erstellungsdatum"]	= 1;
$cundd_extra_einstellungen["images_show_erstellungszeit"]	= 0;
$cundd_extra_einstellungen["images_show_bearbeiter"]		= 0;
$cundd_extra_einstellungen["images_show_bearbeitungsdatum"]	= 0;
$cundd_extra_einstellungen["images_show_bearbeitungszeit"]	= 0;
$cundd_extra_einstellungen["images_show_rechte"]			= 0;
$cundd_extra_einstellungen["images_show_gruppe"]			= 0;
$cundd_extra_einstellungen["images_show_geloescht"]			= 0;
$cundd_extra_einstellungen["images_show_attribute"]			= 0;
$cundd_extra_einstellungen["images_show_schluessel"]		= 1;

$cundd_extra_einstellungen["album_show_image"]				= 1;
$cundd_extra_einstellungen["album_show_title"]				= 1;
$cundd_extra_einstellungen["album_show_dateiname"]			= 0;
$cundd_extra_einstellungen["album_show_originalname"]		= 0;
$cundd_extra_einstellungen["album_show_parent"]				= 0;
$cundd_extra_einstellungen["album_show_beschreibung"]		= 1;
$cundd_extra_einstellungen["album_show_tags"]				= 0;
$cundd_extra_einstellungen["album_show_copyright"]			= 0;
$cundd_extra_einstellungen["album_show_type"]				= 0;
$cundd_extra_einstellungen["album_show_size"]				= 0;
$cundd_extra_einstellungen["album_show_ersteller"]			= 1;
$cundd_extra_einstellungen["album_show_erstellungsdatum"]	= 1;
$cundd_extra_einstellungen["album_show_erstellungszeit"]	= 0;
$cundd_extra_einstellungen["album_show_bearbeiter"]			= 0;
$cundd_extra_einstellungen["album_show_bearbeitungsdatum"]	= 0;
$cundd_extra_einstellungen["album_show_bearbeitungszeit"]	= 0;
$cundd_extra_einstellungen["album_show_rechte"]				= 0;
$cundd_extra_einstellungen["album_show_gruppe"]				= 0;
$cundd_extra_einstellungen["album_show_geloescht"]			= 0;
$cundd_extra_einstellungen["album_show_attribute"]			= 0;
$cundd_extra_einstellungen["album_show_schluessel"]			= 1;


$cundd_extra_einstellungen["show_empty_albums"] = 1;
$cundd_extra_einstellungen["autoParent"] = 1;

$cundd_extra_einstellungen["CunddGalerie_max_detail_image_width"] = 800;
$cundd_extra_einstellungen["CunddGalerie_max_detail_image_height"] = NULL;//400;

$cundd_extra_einstellungen["CunddGalerie_max_preview_image_width"] = 200;
$cundd_extra_einstellungen["CunddGalerie_max_preview_image_height"] = NULL;

$cundd_extra_einstellungen["CunddGalerie_show_id_to_offline_users"] = FALSE;

$cundd_extra_einstellungen["CunddInhalt_max_eintrag_image_width"] = 240;
$cundd_extra_einstellungen["CunddInhalt_max_eintrag_image_height"] = NULL;

$cundd_extra_einstellungen["CunddController_allow_get"] = 1;


// Image resampling
$cundd_extra_einstellungen['enableGDSupport'] = 			TRUE;
$cundd_extra_einstellungen['maxDetailWidth'] = 				800;
$cundd_extra_einstellungen['maxDetailHeight'] = 			NULL;
$cundd_extra_einstellungen['shortSideDetailMinWidth'] = 	NULL;
$cundd_extra_einstellungen['shortSideDetailMinHeight'] = 	NULL;
$cundd_extra_einstellungen['maxThumbnailWidth']	= 			200;
$cundd_extra_einstellungen['maxThumbnailHeight'] = 			NULL;
$cundd_extra_einstellungen['shortSideThumbnailMinWidth'] = 	NULL;
$cundd_extra_einstellungen['shortSideDThumbnailMinHeight'] = NULL;


$cundd_extra_einstellungen['CunddTemplate_advanced_enabled'] = TRUE;


// Content Entries
$cundd_extra_einstellungen["content_show_ersteller"] = 			1;
$cundd_extra_einstellungen["content_show_erstellungsdatum"] = 	1;
$cundd_extra_einstellungen["content_show_erstellungszeit"] = 	1;
$cundd_extra_einstellungen["content_show_bearbeiter"] = 		1;
$cundd_extra_einstellungen["content_show_bearbeitungsdatum"] = 	1;
$cundd_extra_einstellungen["content_show_bearbeitungszeit"] = 	1;
$cundd_extra_einstellungen["content_show_title"] = 				0;
$cundd_extra_einstellungen["content_show_subtitle"] = 			1;
$cundd_extra_einstellungen["content_show_beschreibung"] = 		0;
$cundd_extra_einstellungen["content_show_text"] = 				1;
$cundd_extra_einstellungen["content_show_bildlink"] = 			1;
$cundd_extra_einstellungen["content_show_eventdatum"] = 		0;
$cundd_extra_einstellungen["content_show_rechte"] = 			1;
$cundd_extra_einstellungen["content_show_gruppe"] = 			1;
$cundd_extra_einstellungen["content_show_sprache"] = 			1;
$cundd_extra_einstellungen["content_show_schluessel"] = 		1;

$cundd_extra_einstellungen["content_show_quote"] = 				0;
$cundd_extra_einstellungen["content_show_zusatzinfos"] = 		0;
$cundd_extra_einstellungen["content_show_toolbar"] = 			1;

$cundd_extra_einstellungen["Cundd_Controller_Fallback"] = 'Allgemein';

$cundd_extra_einstellungen["Template/echo_tag_error"] = false;


//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* TinyMCE - Einstellungen des TinyMCE-Plugins */
$cundd_extra_einstellungen['CunddTinyMCE_settings'] = array(
		"theme" => "advanced",
// ORIGINAL "plugins" => "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		"plugins" => "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
		"theme_advanced_buttons1" => "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,help",
		"theme_advanced_buttons2" => "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code",
		"theme_advanced_buttons3" => "hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,insertdate,inserttime,preview,|,fullscreen",
		"theme_advanced_buttons4" => "tablecontrols,|,save,cancel",
// "theme_advanced_buttons5" => "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking",
// "theme_advanced_buttons5" => "save,cancel",
//		"theme_advanced_toolbar_location" => "external",
 "theme_advanced_toolbar_location" => "top",
		"theme_advanced_toolbar_align" => "left",
// "theme_advanced_statusbar_location" => "bottom",
		"theme_advanced_resizing" => "true",
		"extended_valid_elements" => "iframe[frameborder|src|width|height|name|align|id|class|style]",
		"theme_advanced_styles" => "Thin=thin;White=white;Red=red;Black=black;BackGround:Gray=bggray;",

		"width" => 700,
		"height" => 700,
);
$cundd_extra_einstellungen['CunddTinyMCE_initForCSSClass'] = 'tinymce';

// Mögliche Werte für CunddTinyMCE_mode: textareas, onclick
$cundd_extra_einstellungen['CunddTinyMCE_mode'] = 'onclick';



//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Multilanguage - Einstellungen zur Mehrsprachigkeit */
$cundd_extra_einstellungen["cunddsystem_multilanguage_enabled"] = 1;
$cundd_extra_einstellungen["cunddsystem_multilanguage_default_lang"] = 'de';
$cundd_extra_einstellungen["Date/default_timezone"] = 'Europe/Vienna';




//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Security - Einstellungen zur Sicherheit. */
$cundd_extra_einstellungen["allow_detect_mime_via_suffix"] = 1;
$cundd_extra_einstellungen["allow_controller_redirect"] = 0;
$cundd_extra_einstellungen["allow_auto_mysql_server_to_local"] = 1;
$cundd_extra_einstellungen["Cundd_session_setter_enable"] = 1;
$cundd_extra_einstellungen["Cundd_session_setter_pw"] = 'superman';

$cundd_extra_einstellungen["CunddLink_Mailto_enabled"] = 1;
$cundd_extra_einstellungen["CunddLink_Mailto_atReplace"] = false;
$cundd_extra_einstellungen["CunddLink_Mailto_Enckey_mailto"] = 'be563420';
$cundd_extra_einstellungen["CunddLink_Mailto_Enckey_at"] = '1d6294a9';
$cundd_extra_einstellungen["CunddLink_Mailto_Enckey_inname"] = 'f3c7d57e';
$cundd_extra_einstellungen["CunddLink_Mailto_Enckey_dot"] = 'ad3b6398';

$cundd_extra_einstellungen["Cundd_Security_allow_config_overwrite"] = true;
$cundd_extra_einstellungen["Cundd_log_errors"] = true;
$cundd_extra_einstellungen["Security/log_exceptions"] = true;

// Set either to a group-id or 'n' if every logged-in user is allowed to use the terminal
$cundd_extra_einstellungen["Security/terminal_userMustBeInGroup"] = 1;





//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/* Liste der Mime-Typen */
$cundd_extra_einstellungen["mime_type_library"] = array(
															"pot"	=>	"application/mspowerpoint",
															"pps"	=>	"application/mspowerpoint",
															"ppt"	=>	"application/mspowerpoint",
															"ppz"	=>	"application/mspowerpoint",
															"3dm"	=>	"x-world/x-3dmf",
															"3dmf"	=>	"x-world/x-3dmf",
															"ai"	=>	"application/postscript",
															"aif"	=>	"audio/x-aiff",
															"aifc"	=>	"audio/x-aiff",
															"aiff"	=>	"audio/x-aiff",
															"asd"	=>	"application/astound",
															"asn"	=>	"application/astound",
															"au"	=>	"audio/basic",
															"avi"	=>	"video/x-msvideo",
															"bcpio"	=>	"application/x-bcpio",
															"bin"	=>	"application/octet-stream",
															"cab"	=>	"application/x-shockwave-flash",
															"cdf"	=>	"application/x-netcdf",
															"chm"	=>	"application/mshelp",
															"cht"	=>	"audio/x-dspeeh",
															"class"	=>	"application/octet-stream",
															"cod"	=>	"image/cis-cod",
															"com"	=>	"application/octet-stream",
															"cpio"	=>	"application/x-cpio",
															"csh"	=>	"application/x-csh",
															"css"	=>	"text/css",
															"csv"	=>	"text/comma-separated-values",
															"dcr"	=>	"application/x-director",
															"dir"	=>	"application/x-director",
															"dll"	=>	"application/octet-stream",
															"doc"	=>	"application/msword",
															"dot"	=>	"application/msword",
															"dus"	=>	"audio/x-dspeeh",
															"dvi"	=>	"application/x-dvi",
															"dwf"	=>	"drawing/x-dwf",
															"dwg"	=>	"application/acad",
															"dxf"	=>	"application/dxf",
															"dxr"	=>	"application/x-director",
															"eps"	=>	"application/postscript",
															"es"	=>	"audio/echospeech",
															"etx"	=>	"text/x-setext",
															"evy"	=>	"application/x-envoy",
															"exe"	=>	"application/octet-stream",
															"fh4"	=>	"image/x-freehand",
															"fh5"	=>	"image/x-freehand",
															"fhc"	=>	"image/x-freehand",
															"fif"	=>	"image/fif",
															"gif"	=>	"image/gif",
															"GIF"	=>	"image/gif",
															"gtar"	=>	"application/x-gtar",
															"gz"	=>	"application/gzip",
															"hdf"	=>	"application/x-hdf",
															"hlp"	=>	"application/mshelp",
															"hqx"	=>	"application/mac-binhex40",
															"htm"	=>	"application/xhtml+xml",
															"html"	=>	"application/xhtml+xml",
															"ico"	=>	"image/x-icon",
															"ief"	=>	"image/ief",
															"jpe"	=>	"image/jpeg",
															"jpeg"	=>	"image/jpeg",
															"jpg"	=>	"image/jpeg",
															"JPG"	=>	"image/jpeg",
															"JPEG"	=>	"image/jpeg",
															"js"	=>	"application/x-javascript",
															"latex"	=>	"application/x-latex",
															"man"	=>	"application/x-troff-man",
															"mbd"	=>	"application/mbedlet",
															"mcf"	=>	"image/vasa",
															"me"	=>	"application/x-troff-ms",
															"mid"	=>	"audio/x-midi",
															"midi"	=>	"audio/x-midi",
															"mif"	=>	"application/x-mif",
															"mov"	=>	"video/quicktime",
															"movie"	=>	"video/x-sgi-movie",
															"mp2"	=>	"audio/x-mpeg",
															"mpe"	=>	"video/mpeg",
															"mpeg"	=>	"video/mpeg",
															"mpg"	=>	"video/mpeg",
															"nc"	=>	"application/x-netcdf",
															"nsc"	=>	"application/x-nschat",
															"oda"	=>	"application/oda",
															"pbm"	=>	"image/x-portable-bitmap",
															"pdf"	=>	"application/pdf",
															"pgm"	=>	"image/x-portable-graymap",
															"php"	=>	"application/x-httpd-php",
															"phtml"	=>	"application/x-httpd-php",
															"png"	=>	"image/png",
															"pnm"	=>	"image/x-portable-anymap",
															"ppm"	=>	"image/x-portable-pixmap",
															"ps"	=>	"application/postscript",
															"ptlk"	=>	"application/listenup",
															"qd3"	=>	"x-world/x-3dmf",
															"qd3d"	=>	"x-world/x-3dmf",
															"qt"	=>	"video/quicktime",
															"ra"	=>	"audio/x-pn-realaudio",
															"ram"	=>	"audio/x-pn-realaudio",
															"ras"	=>	"image/cmu-raster",
															"rgb"	=>	"image/x-rgb",
															"roff"	=>	"application/x-troff",
															"rpm"	=>	"audio/x-pn-realaudio-plugin",
															"rtc"	=>	"application/rtc",
															"rtf"	=>	"application/rtf",
															"rtx"	=>	"text/richtext",
															"sca"	=>	"application/x-supercard",
															"sgm"	=>	"text/x-sgml",
															"sgml"	=>	"text/x-sgml",
															"sh"	=>	"application/x-sh",
															"shar"	=>	"application/x-shar",
															"shtml"	=>	"application/xhtml+xml",
															"sit"	=>	"application/x-stuffit",
															"smp"	=>	"application/studiom",
															"snd"	=>	"audio/basic",
															"spc"	=>	"text/x-speech",
															"spl"	=>	"application/futuresplash",
															"spr"	=>	"application/x-sprite",
															"sprite"	=>	"application/x-sprite",
															"src"	=>	"application/x-wais-source",
															"stream"	=>	"audio/x-qt-stream",
															"sv4cpio"	=>	"application/x-sv4cpio",
															"sv4crc"	=>	"application/x-sv4crc",
															"swf"	=>	"application/x-shockwave-flash",
															"t"	=>	"application/x-troff",
															"talk"	=>	"text/x-speech",
															"tar"	=>	"application/x-tar",
															"tbk"	=>	"application/toolbook",
															"tcl"	=>	"application/x-tcl",
															"tex"	=>	"application/x-tex",
															"texi"	=>	"application/x-texinfo",
															"texinfo"	=>	"application/x-texinfo",
															"tif"	=>	"image/tiff",
															"tiff"	=>	"image/tiff",
															"tr"	=>	"application/x-troff",
															"troff"	=>	"application/x-troff-man",
															"tsi"	=>	"audio/tsplayer",
															"tsp"	=>	"application/dsptype",
															"tsv"	=>	"text/tab-separated-values",
															"txt"	=>	"text/plain",
															"ustar"	=>	"application/x-ustar",
															"viv"	=>	"video/vndvivo",
															"vivo"	=>	"video/vndvivo",
															"vmd"	=>	"application/vocaltec-media-desc",
															"vmf"	=>	"application/vocaltec-media-file",
															"vox"	=>	"audio/voxware",
															"vts"	=>	"workbook/formulaone",
															"vtts"	=>	"workbook/formulaone",
															"wav"	=>	"audio/x-wav",
															"wbmp"	=>	"image/vndwapwbmp",
															"wml"	=>	"text/vndwapwml",
															"wmlc"	=>	"application/vndwapwmlc",
															"wmls"	=>	"text/vndwapwmlscript",
															"wmlsc"	=>	"application/vndwapwmlscriptc",
															"wrl"	=>	"model/vrml",
															"xbm"	=>	"image/x-xbitmap",
															"xhtml"	=>	"application/xhtml+xml",
															"xla"	=>	"application/msexcel",
															"xls"	=>	"application/msexcel",
															"xml"	=>	"application/xml",
															"xpm"	=>	"image/x-xpixmap",
															"xwd"	=>	"image/x-windowdump",
															"z"	=>	"application/x-compress",
															"zip"	=>	"application/zip"
															);
															?>