<?php
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
//MWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMWMW
/** 
 * Die Klasse CunddLang erweitert Cundd_Lang.
 * Der Konstruktor der Klasse "CunddLang" liest die in der Konfigurationsdatei 
 * definierte Sprachbibliothek. Eine Instanz der Klasse wird in dieser Datei erstellt. Die 
 * Methode "get" holt den im ersten Parameter übergebenen Sprachbaustein und ersetzt die 
 * dort definierten Tags durch einen optionalen zweiten Parameter.
 * Beispiel: Cundd_Lang wird mit den Parametern "msg_neuer_benutzer" und "benutzername" 
 * aufgerufen. Das Skript lädt nun zuerst die in config.php angegebene Sprachbibliothek 
 * und liest das dort definierte Array "CunddLangLib" ein. Nun dient der erste an 
 * übergebene Parameter als Key für die Abfrage des in der Sprachbibliotek gespeicherten 
 * Wertes. In diesem Beispiel würde der Wert für CunddLangLib("msg_neuer_benutzer") 
 * abgefragt. Das Ergebnis wäre ähnlich dem Satz "Der Benutzer {1} wurde neu erstellt. 
 * Bitte prüfen Sie den Benutzer und aktivieren den Account, wenn der Benutzer die Rechte 
 * erhalten soll."
 * In der endgültigen Ausgabe, soll der String "{1}" durch den Namen des neuen Benutzer 
 * ersetzt werden. Also durch das Element des Parameter-Arrays mit dem Index 1. 
 * Demzufolge würde ein Tag {2} mit dem dritten Element des Parameter-Arrays ersetzt.
 * @license 
 * @copyright
 * @package Cundd_Tools
 * @version 1.2
 * @since Jan 12, 2010
 * @author daniel 
 */
class CunddLang extends Cundd_Lang{
}