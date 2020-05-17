<?php
/**********************************************************************************
 * Author: 		Fromm Alexander
 * Date: 		20100806
 * Desc:		This file contains all strings in german
 *********************************************************************************/
include_once('modules/default/lang/lang_vextra_de.php');
include_once('modules/default/lang/lang_vcat_de.php');
include_once('modules/default/lang/lang_country_de.php');
include_once('modules/default/lang/lang_prodcat_de.php');
include_once('modules/default/lang/lang_prodcatprop_de.php');
if(!isset($lang) || !is_array($lang)){
	$lang = array();
}
$lang['LID'] = 'DE'; //Language ID
$lang['TXT_1'] = 'CarAds';
$lang['TXT_2'] = 'Auto';
$lang['TXT_3'] = 'Motorrad';
$lang['TXT_4'] = 'LKW';
$lang['TXT_5'] = 'Auto-Suche';
$lang['TXT_6'] = 'Marke';
$lang['TXT_7'] = 'Baujahr';
$lang['TXT_8'] = 'Von:';
$lang['TXT_9'] = 'Bis:';
$lang['TXT_10'] = 'Leistung';
$lang['TXT_11'] = 'unter';
$lang['TXT_12'] = 'über';
$lang['TXT_13'] = 'Preis';
$lang['TXT_14'] = 'Laufleistung';
$lang['TXT_15'] = 'Kraftstoff';
$lang['TXT_16'] = 'beliebig';
$lang['TXT_17'] = 'Fahrzeugzustand';
$lang['TXT_18'] = 'Fhzg.Kategorie';
$lang['TXT_19'] = 'Fahrzeugfarbe';
$lang['TXT_20'] = 'met.';
$lang['TXT_21'] = 'Schaltung';
$lang['TXT_22'] = 'Abgasnorm';
$lang['TXT_23'] = 'Anzahl Türen';
$lang['TXT_24'] = 'türig';
$lang['TXT_25'] = 'Ergebnisse sortieren nach';
$lang['TXT_26'] = 'Datum';
$lang['TXT_27'] = 'PLZ-Umkreis';
$lang['TXT_28'] = 'kein';
$lang['TXT_29'] = 'Alter der Anzeige';
$lang['TXT_30'] = 'Tag(e)';
$lang['TXT_31'] = 'Suchen';
$lang['TXT_32'] = 'Art der Anzeige';
$lang['TXT_33'] = array( 1 => 'Händler'
						, 2 => 'Privat'
						, 3 => 'System'
						);
$lang['TXT_34'] = 'Privat';
$lang['TXT_35'] = 'aufsteigend';
$lang['TXT_36'] = 'absteigend';
$lang['TXT_37'] = 'Auto-Inserieren';
$lang['TXT_38'] = 'Monat';
$lang['TXT_39'] = 'Jahr';
$lang['TXT_40'] = '---';//'keine Angabe';
$lang['TXT_41'] = 'Weiter >>';
$lang['TXT_42'] = 'Modell';
$lang['TXT_43'] = 'Inseratdauer';
$lang['TXT_44'] = 'Inserieren';
$lang['TXT_45'] = 'Woche(n)';
$lang['TXT_46'] = 'Nachname';
$lang['TXT_47'] = 'Vorname';
$lang['TXT_48'] = 'E-Mail';
$lang['TXT_49'] = 'Tel. Nr. 1';
$lang['TXT_50'] = 'Tel. Nr. 2';
$lang['TXT_51'] = 'PLZ';
$lang['TXT_52'] = 'Ort';
$lang['TXT_53'] = 'Firmenname';
$lang['TXT_54'] = 'Anschrift';
$lang['TXT_55'] = 'Fahrzeugdaten';
$lang['TXT_56'] = 'weitere Details';
$lang['TXT_57'] = 'Leergewicht';
$lang['TXT_58'] = 'Zylinder';
$lang['TXT_59'] = 'Hubraum';
$lang['TXT_60'] =  'ccm';
$lang['TXT_61'] = 'Zurück';
$lang['TXT_62'] = 'Verbr. innerorts';
$lang['TXT_63'] = 'Verbr. ausserorts';
$lang['TXT_64'] = 'l/100km';
$lang['TXT_65'] = 'CO²-Emission';
$lang['TXT_66'] = 'g/100km';
$lang['TXT_67'] = 'kg';
$lang['TXT_68'] = 'Beschreibung';
$lang['TXT_69'] = 'Zeichen';
$lang['TXT_70'] = Array(0 => 'Festpreis',
						1 => 'VHS',
						2 => 'VB');
$lang['TXT_71'] = 'Neue Suche';
$lang['TXT_72'] =  Array(0 => 'kW', 
						1 => 'PS');
						
$lang['TXT_73'] = 'Trefferliste';

$lang['TXT_74'] = Array('0' => '&euro;'
						, '1'=>'$'
						, '2'=>'Rubel'
						);
						
$lang['TXT_75'] = Array('0' => 'km'
						, '1' => 'Meile(n)'
						);
						
$lang['TXT_75_0'] = 'km';
$lang['TXT_76'] = 'TÜV';
$lang['TXT_77'] = 'AU';
$lang['TXT_78'] = '----';
$lang['TXT_79'] = 'Verkäuferdaten';
$lang['TXT_80'] = 'Tel. Nr.';
$lang['TXT_81'] = 'Benutzername';
$lang['TXT_82'] = 'Passwort';
$lang['TXT_83'] = 'Extras';
$lang['TXT_84'] = 'Fahrzeugdetails';
$lang['TXT_85'] = 'Umweltplakette';
$lang['TXT_86'] = 'Anbieter';
$lang['TXT_87'] = 'E-Mail Kontakt';
$lang['TXT_88'] = 'Name';
$lang['TXT_89'] = 'Ihre E-Mail Adresse';
$lang['TXT_90'] = 'Nachricht';
$lang['TXT_91'] = 'E-Mail Senden';
$lang['TXT_92'] = 'weitere Optionen';
$lang['TXT_93'] = 'Druckansicht';
$lang['TXT_94'] = 'PDF-Ansicht';
$lang['TXT_95'] = 'Videos';
$lang['TXT_96'] = 'Inserat Parken';
$lang['TXT_97'] = 'Suche verändern';
$lang['TXT_98'] = 'Fotos';
$lang['TXT_99'] = 'Motorrad-Inserieren';
$lang['TXT_100'] = 'Motorrad-Suche';
$lang['TXT_101'] = 'LKW-Suche';
$lang['TXT_102'] = 'LKW-Inserieren';
$lang['TXT_103'] = 'Fahrzeugart';
$lang['TXT_104'] = 'Achsenanzahl';
$lang['TXT_105'] = 'Radformel';
$lang['TXT_106'] = 'zulässiges Gesamtgewicht';
$lang['TXT_107'] = 'Impressum';
$lang['TXT_108'] = 'Neues Inserat aufgeben.';
$lang['TXT_109'] = 'Klimatisierung';
$lang['TXT_110'] = 'in';
$lang['TXT_111'] = 'Foto hochladen';
$lang['TXT_112'] = 'Nächste';
$lang['TXT_113'] = 'Treffer anzeigen';
$lang['TXT_114'] = 'Standort';
$lang['TXT_115'] = 'Ihre Suchanfrage lieferte kein Ergebnis.';
$lang['TXT_116'] = 'Newsletter';
$lang['TXT_117'] = 'Regeln';
$lang['TXT_118'] = 'Druckansicht';
$lang['TXT_119'] = 'PDF-Ansicht';
$lang['TXT_120'] = 'Beobachten';
$lang['TXT_121'] = 'Alle Fahrzeuge des Anbieters.';
$lang['TXT_122'] = 'Auto-Inserat bearbeiten';
$lang['TXT_123'] = 'Auto-Schnellsuche';
$lang['TXT_124'] = 'Inserat-Nr.';
$lang['TXT_125'] = 'Inserat anzeigen';
$lang['TXT_126'] = 'Fotogallerie';
$lang['TXT_127'] = 'Inserat abschließen (und Fotos übernehmen)';
$lang['TXT_128'] = 'in';
$lang['TXT_129'] = 'Suchergebnisliste';
$lang['TXT_130'] = 'Passwort';
$lang['TXT_131'] = 'Kostenfrei Anmelden';
$lang['TXT_132'] = 'Passwort (Wdh.)';
$lang['TXT_133'] = 'Passwort vergessen?';
$lang['TXT_134'] = 'Anmeldung';
$lang['TXT_135'] = 'kostenlos';
$lang['TXT_136'] = 'Ich akzeptiere die ';
$lang['TXT_137'] = 'Ich möchte einen Newsletter erhalten.';
$lang['TXT_138'] = 'Login';
$lang['TXT_139'] = 'Logout';
$lang['TXT_140'] = 'Automarkt';
$lang['TXT_141'] = 'Motorrad-Markt';
$lang['TXT_142'] = 'LKW-Markt';
$lang['TXT_143'] = 'Löschen';
$lang['TXT_144'] = 'Bearbeiten';
$lang['TXT_145'] = 'Abbrechen';
$lang['TXT_146'] = 'Manager Cockpit';
$lang['TXT_147'] = 'Meine Inserate';
$lang['TXT_148'] = 'Auto-Inserate';
$lang['TXT_149'] = 'Motorrad-Inserate';
$lang['TXT_150'] = 'LKW-Inserate';
$lang['TXT_151'] = 'Persönliche Einstellungen';
$lang['TXT_152'] = 'Meine Daten ändern';
$lang['TXT_153'] = 'Passwort ändern';
$lang['TXT_154'] = 'Profil bearbeiten';
$lang['TXT_155'] = 'Benutzeraccount löschen';
$lang['TXT_156'] = 'Speichern';
$lang['TXT_157'] = 'Inserat löschen';
$lang['TXT_158'] = 'Altes Passwort';
$lang['TXT_159'] = 'Neues Passwort';
$lang['TXT_160'] = 'Neues Passwort (Wdh.)';
$lang['TXT_161'] = 'Motorrad-Schnellsuche';
$lang['TXT_162'] = 'Cockpit';
$lang['TXT_163'] = 'Games';
$lang['TXT_164'] = 'Anmelden';
$lang['TXT_165'] = 'Logout';
$lang['TXT_166'] = 'SUCHEN';
$lang['TXT_167'] = 'INSERIEREN';
$lang['TXT_168'] = 'Car-Match';
$lang['TXT_169'] = 'Noch nicht angemeldet? Dann ';
$lang['TXT_170'] = 'Übersicht';
$lang['TXT_171'] = 'Mein Parkhaus';
$lang['TXT_172'] = 'Ausparken';
$lang['TXT_173'] = 'Datenaustausch konfigurieren';
$lang['TXT_174'] = 'Datenbestand importieren';
$lang['TXT_175'] = 'Datenbestand exportieren';
$lang['TXT_176'] = 'Datenaustausch';
$lang['TXT_177'] = 'Hier haben Sie die Möglichkeit einen bestehenden Fahrzeugbestand manuell zu importieren.';
$lang['TXT_178'] = 'Datei';
$lang['TXT_179'] = 'Schnittstellenformat';
$lang['TXT_180'] = 'Importieren';
$lang['TXT_181'] = array('AS24' => 'autoscout24.de'
						, 'MOBILE' => 'mobile.de'
						//, 'STX3' => 'STX3'
						);
//$lang['TXT_182'] = 'Wählen Sie hier die zu importierende Datei und deren Format aus.<br/>Achtung die max. Dateigröße ist auf 100MB beschränkt. Bitte aktivieren und nutzen Sie bei größeren Dateien den FTP-Zugang.';
$lang['TXT_182'] = 'Wählen Sie hier die zu importierende Datei und deren Format aus.<br/>Achtung die max. Dateigröße ist auf 100MB beschränkt.';
$lang['TXT_183'] = 'Manueller Datenimport';
$lang['TXT_184'] = 'FTP-Zugang aktivieren';
$lang['TXT_185'] = 'Datenexport aktivieren';
$lang['TXT_186'] = 'Datenimport aktivieren';
$lang['TXT_187'] = 'Datenbestand automatisch abgleichen nach dem Hochladen.';
$lang['TXT_188'] = 'LKW-Schnellsuche';
$lang['TXT_189'] = 'Alle Angebote des Anbieters';
$lang['TXT_190'] = 'Herzlich Willkommen zum Fahrzeugangebot von  ';
$lang['TXT_191'] = 'anzeigen';
$lang['TXT_192'] = 'Pflichtfeld';
$lang['TXT_194'] = 'Verkäuferangaben';
$lang['TXT_195'] = 'Angebot bearbeiten';
$lang['TXT_196'] = 'Suchkriterien';
$lang['TXT_197'] = 'Anzeigen filtern';
$lang['TXT_198'] = 'Schnellsuche';
$lang['TXT_199'] = 'Aktuelle Angebote';
$lang['TXT_200'] = 'Geparkte Autos anzeigen';
$lang['TXT_201'] = 'Geparkte Motorrads anzeigen';
$lang['TXT_202'] = 'Geparkte LKWs anzeigen';
$lang['TXT_203'] = 'Datenimport';
$lang['TXT_204'] = 'Datenexport';
$lang['TXT_205'] = 'Daten importieren';
$lang['TXT_206'] = 'Protokoll';
$lang['TXT_207'] = 'Hier haben Sie die Möglichkeit Ihren Fahrzeugbestand zu exportieren und somit eine Sicherungskopie Ihrer Angebote zu erhalten.';
$lang['TXT_208'] = 'Daten exportieren';
$lang['TXT_209'] = 'Welchen Fahrzeugbestand wollen Sie exportieren?';
$lang['TXT_210'] = 'In welchem Format wollen Sie Ihren gewählten Fahrzeugbestand exportieren';
$lang['TXT_211'] = 'Sollen die Fotos Ihrer Angebote mit exportiert werden?';
$lang['TXT_212'] = 'Ja';
$lang['TXT_213'] = 'Nein';
$lang['TXT_214'] = 'Es existieren bereits Exportdateien zu Ihrem {-VT_TYPE-}-Bestand.';
$lang['TXT_215'] = 'Um welche Fahrzeugarten handelt es sich';
$lang['TXT_216'] = 'Bitte wählen';
$lang['TXT_217'] = 'Diesen Fahrzeugbestand ohne Fotos können Sie hier downloaden <a href="{-LINK_CSV_FILE-}">CSV-Datei</a><br/>Den Fahrzeugbestand inkl. aller Fotos steht hier zur Verfügung <a href="{-LINK_ZIP_FILE-}">ZIP-Datei</a>';
$lang['TXT_218'] = 'Motorrad';
$lang['TXT_219'] = 'Format';
$lang['TXT_220'] = 'Sicherheitscode';
$lang['TXT_221'] = 'Sie suchen ein Auto?';
$lang['TXT_222'] = 'Facebook-Beiträge';
$lang['TXT_223'] = 'Home';
$lang['TXT_224'] = 'Auto Angebote';
$lang['TXT_225'] = 'Motorrad Angebote';
$lang['TXT_226'] = 'LKW Angebote';
$lang['TXT_227'] = 'Sie suchen ein Motorrad?';
$lang['TXT_228'] = 'Sie suchen einen LKW?';
$lang['TXT_229'] = 'Gültig ab 01.01.2012';
$lang['TXT_230'] = 'Kontaktformular';
$lang['TXT_231'] = 'der Online Automarkt, Motorradmarkt und LKW-Markt für Neuwagen und Gebrauchtwagen - DER ONLINE FAHRZEUGMARKT - DIE ONLINE FAHRZEUGBÖRSE';//PKW-Markt, Automarkt, Motorradmarkt, LKW-Markt, Markt für Nutzfahrzeuge';
$lang['TXT_232'] = 'Neues Passwort zusenden	';
$lang['TXT_234'] = 'HSN';
$lang['TXT_235'] = 'TSN';
$lang['TXT_236'] = 'Fahrgestellnummer';
$lang['TXT_237'] = 'Eine Nachricht wurde aus dem Impressum an Sie geschickt:<br/><br/>{-CONTACT_NAME-} schickt Ihnen folgende Nachricht:<br/>{-MESSAGE-}<br/><br/>Um Ihn zu kontaktieren schreiben sie bitte an folgende Email-Adresse: {-CONTACT_EMAIL-}';
$lang['TXT_238'] = 'Impressumsbenachrichtigung';
$lang['TXT_239'] = 'Sehr geehrte(r) Herr/Frau {-USER_VNNAME-}<br/>Es besteht Interesse für Ihr Fahrzeug {-USER_ADS_LINK-}<br/><br/>Ihnen wird folgende Nachricht von Herr/Frau <b>{-CONTACT_NAME-}</b> zugeschickt:<br/><b>{-MESSAGE-}</b><br/><br/>Falls Sie kontakt mit Herr/Frau {-CONTACT_NAME-} aufnehmen möchten so schreiben Sie an folgende E-Mail Adresse:&nbsp;&nbsp;<b>{-CONTACT_EMAIL-}</b>.<br/><br/>Mit freundlichen Grüßen<br/>Ihr Team von {-SENDER_NAME-}';
$lang['TXT_240'] = 'Kontaktanfrage';
$lang['TXT_241'] = 'Herzlich Willkommen bei {-WEBSITE_NAME-},<br/>Wir freuen uns sehr über Ihre Registrierung und wünschen Ihnen viel Spaß beim Inserieren und Verkauf Ihrer Fahrzeuge.<br/>Ihr persönliches Zugangspasswort lautet: <b>{-USER_PW-}</b><br/>Mit freundlichen Grüßen <br/>Ihr Team von {-WEBSITE_NAME-}';
$lang['TXT_242'] = 'Registrierung bei ';
$lang['TXT_243'] = 'Sehr geehrtes Mitglied von {-WEBSITE_NAME-}, <br/>Ihr Passwort wurde zurückgesetz. Es lautet nun <b>{-USER_PW-}</b><br><br>Sie können sich damit nun einloggen. Bitte ändern Sie es nach Möglichkeit im User-Bereich.<br/>Mit freundlichen Grüßen <br/>Ihr Team von {-WEBSITE_NAME-}';
$lang['TXT_244'] = 'Modellvariante';
$lang['TXT_245'] = 'Land';
$lang['TXT_246'] = 'Autotunes.de die Online Fahrzuegbörse für Autos/PKWs, Motorräder, LKWs und Wohnmobile. Suchen und Finden Sie hier Ihr Traumfahrzeug.';
$lang['TXT_247'] = 'PKW, Auto, Motorrad, LKW, Wohmobil, PKW-Markt, Automarkt, Motorradmarkt, LKW-Markt, Wohmmobilmarkt, Online Fahrzeugbörse, Fahrzeugmarkt, Internet, Traumwagen';
$lang['TXT_248'] = 'Welche Fahrzeugarten wollen Sie per FTP übertragen?';
$lang['TXT_249'] = 'Ein aktiver Datenimport ermöglicht es Fahrzeugbestände per Webformular zu importieren.';
$lang['TXT_250'] = 'Ein aktiver FTP-Zugang ermöglicht es Fahrzeugbstände per FTP zu übertragen. Spezifizieren Sie bitte hier auch, welche Fahrzeugarten Sie per FTP übermitteln werden.';
$lang['TXT_251'] = 'FAQ';
$lang['TXT_252'] = 'Car-Matching';
$lang['TXT_253'] = 'Produktkategorien';
$lang['TXT_254'] = 'Angaben entsprechend § 5 Telemediengesetz (TMG)';
$lang['TXT_255'] = 'Telefon-Nr';
$lang['TXT_256'] = 'Disclaimer';
$lang['TXT_257'] = 'Zum Angebot';
$lang['TXT_258'] = 'Sie verlassen gerade {-SYS_SITE_NAME-} und werden automatisch auf ein externes Angebot verwiesen. Sollten Sie nicht innerhalb der nächsten <span class="ticker">5</span> Sekunden weitergeleitet werden so klicken Sie bitte auf den folgenden Link.';
$lang['TXT_259'] = 'Zurück zum Inserat';
$lang['TXT_260'] = 'Energieeffizienz';
$lang['TXT_270'] = 'Mwst. ausweisbar';
$lang['TXT_271'] = 'Steuersatz';
$lang['TXT_273'] = 'Brutto';
$lang['TXT_274'] = 'Netto';
$lang['TXT_275'] = 'Mwst. nicht ausweisbar';

$lang['INFO_1'] = 'Inserat erfolgreich aufgenommen.';
$lang['INFO_2'] = 'E-Mail verschickt.';
$lang['INFO_3'] = 'Sie haben sich erfolgreich registriert. Zugangsdaten wurden an Ihre E-Mail Adresse geschickt.';
$lang['INFO_4'] = 'Wollen Sie das Inserat wikrlich löschen?';
$lang['INFO_5'] = 'Wollen Sie die Änderungen wirklich speichern?';
$lang['INFO_6'] = 'Änderungen übernommen';
$lang['INFO_7'] = 'Inserat erfolgreich gelöscht.';
$lang['INFO_8'] = 'Keine Inserate vorhanden';
$lang['INFO_9'] = 'Änderungen übernommen';
$lang['INFO_10'] = 'INSERT Datensatz: ';
$lang['INFO_11'] = 'UPDATE Datensatz: ';
$lang['INFO_12'] = 'Datenexport CSV-Datei erzeugt.';
$lang['INFO_13'] = 'Zur Zeit keine Exportdateien vorhanden. Bitte führen Sie zuerst einen Export durch.';
$lang['INFO_14'] = 'Bitte geben Sie den unten angezeigten Sicherheitscode ein.';
$lang['INFO_15'] = 'Gefällt dir dieses Angebot?<br/> Dann zeig es deinen Freunden.';
$lang['INFO_16'] = 'Ein neues Passwort wurde an Ihre E-Mail Adresse verschickt.';
$lang['INFO_17'] = '<strong><h3>Autotunes.de</h3>ist ein/eine online Fahrzeugmarkt/Fahrzeugbörse  für Autos, Motorräder und LKW / Nutzfahrzeuge / Wohnmobile.</strong><br/>Nutzen Sie unseren kostenlosen Service zum Inserieren und Suchen von Fahrzeugen. Die komfortable Suchfunktion mit Multi-Brand-Selection unterstützt Sie dabei Ihr Traumfahrzeug noch schneller zu finden. Nach einer kurzen und kostenlosen <a href="/member/register"><u>Registrierung</u></a> können Sie beliebig viele Inserate kostenfrei inserieren (mit variabler Inseratlaufzeit und beliebig vielen Bildern).<br/><br/>Hier gehts direkt zum <a href="/car/search"><u>Automarkt</u></a>, <a href="/bike/search"><u>Motorradmarkt</u></a> und <a href="/truck/search"><u>LKW-Markt</u></a>.';

$lang['ERR_1'] = 'Füllen Sie bitte alle Rot gekennzeichneten Felder aus.\nBitte beachten Sie, keine Tausender- und Nachkommastellenzeichen.\nÜberprüfen Sie nochmal ihre E-Mail Adresse auf Richtigkeit.';
$lang['ERR_2'] = 'Ungültige Eingabe.';
$lang['ERR_3'] = 'Anzeige erfolgreich aufgenommen';
$lang['ERR_4'] = 'Keine Treffer gefunden.';
$lang['ERR_5'] = 'Inserat nicht vorhanden';
$lang['ERR_6'] = 'Sie müssen die AGB akzeptieren';
$lang['ERR_7'] = 'Bitte geben Sie Ihren Namen an.';
$lang['ERR_8'] = 'Ihr E-Mail Text darf nicht leer sein.';
$lang['ERR_9'] = 'Ihre E-Mail wurde nicht verschickt.';
$lang['ERR_10'] = 'Sie können pro Inserat nur alle 5 min. eine E-Mail verschicken.';
$lang['ERR_11'] = 'E-Mail wird bereits verwendet. Bitte andere wählen.';
$lang['ERR_12'] = 'Bitte geben Sie ein Passwort an!';
$lang['ERR_13'] = 'Ihre Passwörter stimmen nicht überein!';
$lang['ERR_14'] = 'Ihre E-Mail Adresse ist ungültig';
$lang['ERR_15'] = 'Sie müssen den AGB\'s zustimmen';
$lang['ERR_16'] = 'Eine Registrierung ist im Moment nicht möglich. Versuchen Sie es später nochmal.';
$lang['ERR_17'] = 'Login fehlgeschlagen.';
$lang['ERR_18'] = 'Datensatz konnte nicht importiert werden.';
$lang['ERR_19'] = 'Fahrzeug Marke nicht vorhanden.';
$lang['ERR_20'] = 'AS24 Kundenummer fehlt.';
$lang['ERR_21'] = 'Fahrzeug Modell nicht vorhanden.';
$lang['ERR_22'] = 'Fahrzeug Kategorie nicht vorhanden.';
$lang['ERR_23'] = 'Währung nicht vorhanden.';
$lang['ERR_24'] = 'Kraftstoff nicht vorhanden.';
$lang['ERR_25'] = 'EZ nicht vorhanden.';
$lang['ERR_26'] = 'KM nicht vorhanden.';
$lang['ERR_27'] = 'Preis nicht vorhanden.';
$lang['ERR_28'] = 'Leistung nicht vorhanden.';
$lang['ERR_29'] = 'Fahrzeugfarbe nicht vorhanden.';
$lang['ERR_30'] = 'Mwst. nicht angegeben.';
$lang['ERR_31'] = 'Angabe zur Beschädigung fehlt.';
$lang['ERR_32'] = 'Angeb zum Jahreswagen fehlt.';
$lang['ERR_33'] = 'Angabe zum Neufahrzeug fehlt.';
$lang['ERR_34'] = 'Angabe zur Unserer Empfehlung fehlt.';
$lang['ERR_35'] = 'Matallic Angabe fehlt.';
$lang['ERR_36'] = 'Angaben zum Vorführfahrzeug fehlt';
$lang['ERR_37'] = 'Fahrzeug nicht gefunden';
$lang['ERR_38'] = 'Datenimport im Moment vom System deaktiviert.';
$lang['ERR_39'] = 'Datenexport im Moment vom System deaktiviert.';
$lang['ERR_40'] = 'Ihre momentanen Einstellungen lassen keinen Datenimport zu.';
$lang['ERR_41'] = 'Ihre momentanen Einstellungen lassen keinen Datenexport zu.';
$lang['ERR_42'] = 'Benutzer nicht gefunden';
$lang['ERR_43'] = 'Dateiname nicht vorhanden.';
$lang['ERR_44'] = 'Datensatz nicht aufgenommen';
$lang['ERR_45'] = 'Datenexport konnte nicht durchgeführt werden.';
$lang['ERR_46'] = 'Exportformat nicht erkannt.';
$lang['ERR_47'] = 'Fahrzeugtyp nicht gefunden.';
$lang['ERR_48'] = 'CSV-Datei konnte nicht erzeugt werden.';
$lang['ERR_49'] = 'Bitte wählen Sie das Schnittstellenformat des zu importierenden Datenbestandes';
$lang['ERR_50'] = 'Bitte wählen Sie die Fahrzeugart welche dem importierenden Datenbestand zugeordnet werden soll.';
$lang['ERR_51'] = 'Sicherheitscode nicht korrekt';
$lang['ERR_52'] = 'Nächste Kontaktmail in 5 min. möglich.';
$lang['ERR_53'] = 'Registrierungsbestätigung konnte nicht verschickt werden.';
$lang['ERR_54'] = 'Firmenname ist ungültig';
$lang['ERR_55'] = 'Telefonnummer ungültig';
$lang['ERR_56'] = 'PLZ ungültig';
$lang['ERR_57'] = 'Ort ungültig';
$lang['ERR_58'] = 'Adresse ungültig';

$lang['V_EEK'] = array('A+','A','B','C','D','E','F','G');

$lang['V_MWST'] = array(0 => 7
						, 1 => 7.6
						, 2 => 8
						, 3 => 9
						, 4 => 10.7
						, 5 => 15
						, 6 => 16
						, 7 => 17
						, 8 => 17.5
						, 9 => 18
						, 10 => 19
						, 11 => 19.6
						, 12 => 20
						, 13 => 21
						, 14 => 22
						, 15 => 23
						, 16 => 24
						, 17 => 25
						, 18 => 26	
						);

$lang['V_FUEL'] = Array( 0 => 'Benzin',
                              1 => 'Diesel',
                              2 => 'Elektro',
                              3 => 'LPG(Autogas)',
                              4 => 'Ethanol',
                              5 => 'Wasserstoff',
                              6 => 'CNG(Erdgas)',
                              7 => 'Hybrid',
                              8 => 'Gas(Allgemein)',
                            );
$lang['V_STATE'] = Array(  0 => 'Neufahrzeug'
                           , 1 => 'Jahresfahrzeug'
                           , 2 => 'Gebrauchtfahrzeug'
                           , 3 => 'Blechschaden'
                           , 4 => 'Unfallfahrzeug'
                           , 5 => 'Vorführwagen'
                           , 6 => 'Oldtimer'
                             );     
$lang['V_CLR'] = Array(  0 => 'Beige',
                              1 => 'Blau',
                              2 => 'Braun',
                              3 => 'Gelb',
                              4 => 'Gold',
                              5 => 'Grau',
                              6 => 'Gruen',
                              7 => 'Orange',
                              8 => 'Violett',
                               9 => 'Schwarz',
                              10 => 'Silber',
                              11 => 'Rot',
                              12 => 'Weiß'
                            );
$lang['V_SHIFT'] = Array(	0 => 'Manuell',
                            1 => 'Automatik',
                            2 => 'Halbautomatik'
                         );              
$lang['V_EMISSION_NORM'] = Array(0 => 'Katalysator',
                                1 => 'G-Kat',
                                2 => 'Euro 2',
                                3 => 'Euro 3',
                                4 => 'Euro 4',
                                5 => 'D3-Norm',
                                6 => 'D4-Norm',
                                7 => 'US-Norm',
								8 => 'Euro 5',
								9 => 'Euro 6'
                                );   
$lang['V_ECOLOGIC_TAG'] =  Array( 0 => 'keine',
                                      1 => 'Rot',
                                      2 => 'Gelb',
                                      3 => 'Grün');
$lang['V_KLIMA'] = Array( 	0 => 'Klimaanlage',
							1 => 'Klimaautomatik'
						);   
/*
$lang['CAR_CATT'] = Array(0 => 'Cabrio'
                        , 1 => 'Coupé'
                        , 2 => 'Geländewagen/Pickup'
                        , 3 => 'Kleinwagen'
                        , 4 => 'Kombi'
                        , 5 => 'Limousine'
                        , 6 => 'Kleinbus'
                        , 7 => 'Sportwagen'
                        , 8 => 'Lieferwagen'
                       );
*/
$lang['CAR_DOOR'] = Array(2, 4, 6);   
/*
$lang['BIKE_CAT'] = Array( 1 => 'Chopper'
                          , 2 => 'Enduro'
                          , 3 => 'Mofa'
                          , 4 => 'Moped'
                          , 5 => 'Leichtkraftrad'
                          , 6 => 'Quad'
                          , 8 => 'Rallye'
                          , 9 => 'Rennsport'
                          , 10 => 'Roller'
                          , 11 => 'Seitenwagen'
                          , 12 => 'Sportler'
                          , 13 => 'Streetfighter'
                          , 14 => 'Tourer'
                          , 15 => 'Trike'
                          , 16 => 'Dirt Motorrad'
                          , 17 => 'Naked Motorrad'
                          , 18 => 'Pocket Motorrad'
                          , 19 => 'Sporttourer'
                          , 20 => 'Supermoto'
                   );
*/                   
$lang['truckWheelFormula'] = Array( 0 => '4x2',
                                    1 => '4x4',
                                    2 => '6x2',
                                    3 => '6x6',
                                    4 => '6x4',
                                    5 => '8x2',
                                    6 => '8x4',
                                    7 => '8x8',
                                    8 => '6x2/4',
                                    9 => '6x2x4',
                                    10 => '8x2x6',
                                    11 => '8x2/4',
                                    12 => '8x4x4');

$lang['CAR_PRICE'] = Array(	1000,2000,3000,4000,5000,6000,7000,8000,9000,10000
							,11000,12000,13000,14000,15000,16000,17000,18000,19000
							,20000,25000,30000,35000,40000,45000,50000,55000,60000
							,65000,70000,75000,80000,85000,90000,95000,100000);
$lang['CAR_POWER'] = Array(25,35,44,55,66,74,85,96,110,147,184,222,260,295,333);
$lang['CAR_KM'] = Array(5000,10000,15000,20000,25000,30000,35000,40000,45000,50000
						,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000
						,110000,120000,130000,140000,150000,160000,170000,180000,190000,200000
					);
					
					
$lang['BIKE_PRICE'] = Array(	1000,2000,3000,4000,5000,6000,7000,8000,9000,10000
				,11000,12000,13000,14000,15000,16000,17000,18000,19000
				,20000,25000,30000,35000,40000,45000,50000,55000,60000
				,65000,70000,75000,80000,85000,90000,95000,100000);		
$lang['BIKE_POWER'] = Array(25,35,44,55,66,74,85,96,110,147,184,222,260,295,333);					
$lang['BIKE_KM'] = Array(5000,10000,15000,20000,25000,30000,35000,40000,45000,50000
						,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000
						,110000,120000,130000,140000,150000,160000,170000,180000,190000,200000
					);
					

$lang['TRUCK_PRICE'] = Array(	1000,2000,3000,4000,5000,6000,7000,8000,9000,10000
							,11000,12000,13000,14000,15000,16000,17000,18000,19000
							,20000,25000,30000,35000,40000,45000,50000,55000,60000
							,65000,70000,75000,80000,85000,90000,95000,100000);
$lang['TRUCK_POWER'] = Array(25,35,44,55,66,74,85,96,110,147,184,222,260,295,333);
$lang['TRUCK_KM'] = Array(5000,10000,15000,20000,25000,30000,35000,40000,45000,50000
						,55000,60000,65000,70000,75000,80000,85000,90000,95000,100000
						,110000,120000,130000,140000,150000,160000,170000,180000,190000,200000
					);

$lang['USER_ADS_LENGTH'] = array(1,2,3,4,8,12);

$lang['V_EEK'] = array('A+' => 'A+'
					, 'A' => 'A'
					, 'B' => 'B'
					, 'C' => 'C'
					, 'D' => 'D'
					, 'E' => 'E'
					, 'F' => 'F'
					, 'G' => 'G'
					);
					
$lang['RULES'] = Array(array('TITLE' => 'Allgemein'
								, 'CONTENT' => array('Die Benutzung des Dienstes {-SYS_SITE_NAME-}, im Nachfolgenden einfach {-SYS_SITE_NAME-} genannt, erfolgt ausschließlich nach folgenden aufgeführten Bestimmungen.'
													,'Änderungen dieser Bestimmungen werden den Mitgliedern per E-Mail zugeschickt. Jedes Mitglied hat die Möglichkeit den geänderten Bestimmungen innerhalb einer Woche zu wiedersprechen und zwar in Form einer Kündigung seiner Mitgliedschaft im Mitgliederbereich. Sollte dies nicht geschen so werden die Änderungen nach dieser Frist wirksam.'
													, 'Die folgenden Bedingungen sind für den Fahrzeugmarkt als auch für das Forum gültig.'
													, '{-SYS_SITE_NAME-} übernimmt keine Verantwortung und auch keine Haftung für Inhalte die durch Links auf externe Seiten verweisen.'
													, '{-SYS_SITE_NAME-} tritt in der Beziehung zwischen Anbieter und Käufer weder als Vermittler noch als Partei oder Vertreter einer Partei auf. {-SYS_SITE_NAME-} stellt lediglich die technische Voraussetzung zur Übermittlung der Inserate zur Verfügung.' 
													)
								)
						, array('TITLE' => 'Mitgliedschaft'
								, 'CONTENT' => array('Die Mitgliedschaft sowie die Benutzung von {-SYS_SITE_NAME-} ist absolut kostenfrei.'
													, 'Jedes Mitglied ist bei der Anmeldung verpflichtet, die erforderlichen Daten wahrheitsgemäß anzugeben und diese Daten immer auf dem aktuellsten Stand zu halten.'
													, 'Mit der Mitgliedschaft stimmt das Mitlglied zu, dass seine Daten elektronisch gespeichert werden. Die Weitergabe, seiner elektronisch gespeicherten Daten, an Dritte erfolgt nur mit Einwilligung des Mitglieds, auf Grund gesetzlicher Bestimmungen/Änderungen oder auf Grund höherer Gewalt.'
													, 'Die Mitgliedschaft kann jederzeit von Seiten des Mitglieds im Benutzerbereich beendet werden. Dadurch werden alle zur Zeit laufenden Inserate beendet und gelöscht. Forenbeiträge bleiben jedoch erhalten.'
													, '{-SYS_SITE_NAME-} behält sich das Recht vor an seine Mitglieder Werbemails/Newsletter zu senden, sofern diesem bei der Anmeldung seitens des Mitglieds zugestimmt wurde.'														
													)
								)
						, array('TITLE' => 'Regeln zum Fahrzeugmarkt'
								, 'CONTENT' => array('Im folgenden sind unter Inserent(en) Anbieter/Mitglieder zu verstehen, die ihr Fahrzeug zum Verkauf anbieten.'
													, 'Das Einstellen von Fahrzeuginseraten setzt die unbeschränkte Geschäftfähigkeit des Inserenten voraus.'
													, 'Die zulässige Anzahl gleichzeitig abrufbarer Inserate in der Datenbank von {-SYS_SITE_NAME-} ist pro Inserent nicht beschränkt, d.h. jedes Mitglied darf soviele Fahrzeuge inserieren wie er möchte.'
													, 'Die Inseratdauer pro Inserat beträgt max. 12 Wochen.'
													, 'Es dürfen nur seriöse Verkaufsangebote in die Datenbank von {-SYS_SITE_NAME-} eingestellt werden. Unseriöse Angebote insbesondere Lock-Vogel Angebote, Angebote zu Fahrzeugen über die der Inserent aktuell nicht verfügungsberechtigt ist oder ähnliche, sind nicht gestattet und werden ohne Angabe von Gründen unwiederruflich gelöscht.'
													, 'Der Inserent verpflichtet sich sein Fahrzeug mit Hilfe der von {-SYS_SITE_NAME-} vorgesehenen Eingabemasken soweit wie möglich wahrheitsgetreu zu beschreiben. Vorallem Schäden, Kratzer usw. sollten bei der Beschreibung des Fahrzeugs nicht fehlen. Die Angabe von Bilder ist optional.'
													, 'Inserate mit kostenpflichtigen Telefonnummern, insbesondere 0190er Rufnummern oder ähnliche sind verboten und werden sofort und unwiederruflich gelöscht. Der vielleicht schon entstandene Schaden für die Anrufer wird von {-SYS_SITE_NAME-} nicht getragen.'
													, 'Der Inserent ist für die eingestellten Inhalte selbst verantwortlich. Sollten Rechte Dritter (z.B. Urheberrecht) verletzt werden so ist der Inserent selbst verpflichtet die Inhalte zu ändern/löschen. Dadurch entstandene Schäden sind vom Inserenten selbst zu tragen.'
													, '{-SYS_SITE_NAME-} ist zur Prüfung der Inhalte nicht verpflichtet. Sollte {-SYS_SITE_NAME-} jedoch von nicht erlaubten Inseraten Kenntnis bekommen so werden diese unverzüglich gelöscht. Es besteht dann auch kein Anspruch des Inserenten auf entstandenen Schaden oder entgangene Gewinne. Als unerlaubt gelten folgende Inhalte: Pornogrphie, Glückspiel, Spiritismus, Kriminalität, Inhalte die ungesetzlich, obszön, bedrohend, beleidigend, verleumderisch oder skandalös sind.'
													, 'Der Inserent verpflichtet sich, dass die von Ihm übermittelten Dateien vor dem Einstellen keine Viren oder ähnliche Programme enthalten. {-SYS_SITE_NAME-} behält sich Ersatzanspruch wegen virenbedingter Schäden vor.'
													)
								)
						, array('TITLE' => 'Haftung und Recht'
								, 'CONTENT' => array('{-SYS_SITE_NAME-} übernimmt für seine Dinestleistung und seinen Support keinerlei Gewährleistung. Die Benutzung von {-SYS_SITE_NAME-} erfolgt auf eigene Verantwortung.'
													, '{-SYS_SITE_NAME-} haftet nicht für Mitglieder, die andere Mitglieder im Forum beleidigen oder beschimpfen. {-SYS_SITE_NAME-} geht gegen solche Mitglieder wie oben beschrieben vor. Fühlt sich der Beleidigte sich in seiner Menschenwürde verletzt, so liegt es in seinerm Ermessen gegen seinen Angreifer vorzugehen.'
													, '{-SYS_SITE_NAME-} haftet nicht für die Inhalte der Inserate. Sollten durch solche Inserate Rechte Dritter verletzt werden so ist ganz allein der Inserent zur Verantwortung zu ziehen.'
													, 'Die bei der Abfrage gewonnen Daten dürfen nicht vollständig, teilweise oder auszugsweise dazu verwendet werden, um eine eigene Datenbank in irgendeiner Form aufzubauen.'
													, 'Mit der Einstellung der Inserate in die Datenbank räumt der Inserent {-SYS_SITE_NAME-} das Recht ein, die übermittelten Inhalte sowie Dateien für die Dauer des Inserates zu nutzen.'
													)
								)
						, array('TITLE' => 'Schlussbestimmung'
								, 'CONTENT' => array('Änderungen dieser Regeln werden registrierten Mitglieder per E-Mail zugeschickt und auf der Website von {-SYS_SITE_NAME-} bekannt gegeben. Sollte einzelne Bestimmungen dieser Regeln gemäß geltendem Recht ungültig sein oder sollten einzelne Bedingungen eine oder mehrere Lücken enthalten, so berührt dies die Wirksamkeit der übrigen Bestimmungen nicht.'
													, 'Es gilt das Recht der Bundesrepublik Deutschland.'
													, 'Ausschließlicher Gerichtsstand ist Ulm an der Donau.'
													) 
								)								
						);
				
$lang['FAQ'] = Array(array('TITLE' => 'Was ist {-SYS_SITE_NAME-}?'
								, 'CONTENT' => array('{-SYS_SITE_NAME-} ist eine kostenfreie online Fahrzeugbörse für Autos, Motorräder und LKW/Wohnmobile. Neben dem Suchen und Finden des eigenen Traumwagens, bietet {-SYS_SITE_NAME-} die Möglichkeit sein eigenes Fahrzeug zu inserieren und somit dem Verkauf anzubieten. {-SYS_SITE_NAME-} tritt hierbei weder als Käufer noch Verkäufer des Angebotes auf!')
								)
						, array('TITLE' => 'Ist die Anmeldung bei {-SYS_SITE_NAME-} kostenpflichtig?'
								, 'CONTENT' => array('Die Anmeldung ist und bleibt bei {-SYS_SITE_NAME-} kostenfrei.' 
													)
								)
						, array('TITLE' => 'Ist die Nutzung von {-SYS_SITE_NAME-} kostenpflichtig?'
								, 'CONTENT' => array('Die Nutzung von {-SYS_SITE_NAME-} ist kostenfrei.')
								)
						, array('TITLE' => 'Welche Datenaustauschformate für den Fahrzeughandel werden unterstützt?'
								, 'CONTENT' => array('Im Moment werden die Formate von autoscout24.de und mobile.de unterstützt. Somit besteht die Möglichkeit, den eigenen Fahrzeugbestand komfortable über eine Datei auf {-SYS_SITE_NAME-} zu portieren bzw. zu exportieren.')
								)
						, array('TITLE' => 'Wieviele Inserate können Sie max. aufgeben?'
								, 'CONTENT' => array('Die Anzahl der Inserate ist nicht begrenzt.') 
								)	
						, array('TITLE' => 'Wieviele Biler kann ich in ein Inersat einfügen?'
								, 'CONTENT' => array('Sie haben die Möglichkeit beliebig viele Bilder Ihrem Inserat hinzuzufügen. Eine Bilddatei kann max. 1MB groß sein und muss im JPEG- oder PNG-Format vorliegen.') 
								)								
						);	

$lang['SYS_DISC'] = Array(array('TITLE' => 'Haftung für Inhalte'
								, 'CONTENT' => array('Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.')
								)
						, array('TITLE' => 'Haftung für Links'
								, 'CONTENT' => array('Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.' 
													)
								)
						, array('TITLE' => 'Urheberrecht'
								, 'CONTENT' => array('Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.')
								)
						, array('TITLE' => 'Datenschutz'
								, 'CONTENT' => array('Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben.

Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.

Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.')
								)
						, array('TITLE' => 'Datenschutzerklärung für die Nutzung von Facebook-Plugins'
								, 'CONTENT' => array('Auf unseren Seiten sind Plugins des sozialen Netzwerks Facebook, 1601 South California Avenue, Palo Alto, CA 94304, USA integriert. Die Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem "Like-Button" ("Gefällt mir") auf unserer Seite. Eine Übersicht über die Facebook-Plugins finden Sie hier: <a href="http://developers.facebook.com/docs/plugins/">http://developers.facebook.com/docs/plugins/</a>.
Wenn Sie unsere Seiten besuchen, wird über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook "Like-Button" anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Facebook-Profil verlinken. Dadurch kann Facebook den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von facebook unter <a href="http://de-de.facebook.com/policy.php">http://de-de.facebook.com/policy.php</a>

Wenn Sie nicht wünschen, dass Facebook den Besuch unserer Seiten Ihrem Facebook-Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem Facebook-Benutzerkonto aus.') 
								)	
						, array('TITLE' => 'Datenschutzerklärung für die Nutzung von Google Analytics'
								, 'CONTENT' => array('Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc. ("Google"). Google Analytics verwendet sog. "Cookies", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglichen. Die durch den Cookie erzeugten Informationen über Ihre Benutzung dieser Website werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Im Falle der Aktivierung der IP-Anonymisierung auf dieser Webseite wird Ihre IP-Adresse von Google jedoch innerhalb von Mitgliedstaaten der Europäischen Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum zuvor gekürzt.

Nur in Ausnahmefällen wird die volle IP-Adresse an einen Server von Google in den USA übertragen und dort gekürzt. Im Auftrag des Betreibers dieser Website wird Google diese Informationen benutzen, um Ihre Nutzung der Website auszuwerten, um Reports über die Websiteaktivitäten zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen gegenüber dem Websitebetreiber zu erbringen. Die im Rahmen von Google Analytics von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt.

Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website vollumfänglich werden nutzen können. Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Website bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren: <a href="http://tools.google.com/dlpage/gaoptout?hl=de">http://tools.google.com/dlpage/gaoptout?hl=de</a>. <p><i>Quellenangaben: <a href="http://www.e-recht24.de/muster-disclaimer.htm" target="_blank">Disclaimer</a> von eRecht24, dem Portal zum Internetrecht von Rechtsanwalt Sören Siebert, <a href="http://www.e-recht24.de/artikel/datenschutz/6590-facebook-like-button-datenschutz-disclaimer.html" target="_blank">Facebook Disclaimer von eRecht24</a>, <a href="http://www.google.com/intl/de_ALL/analytics/tos.html" target="_blank">Google Analytics Bedingungen</a></i></p>') 
								)								
						);	
/*
 * $lang['truckExt'] = Array(0 => 'ABS',
                          1 => 'Allrad',
                          2 => 'Differentialsperre',
                          3 => 'Frontheber',
                          4 => 'Hydraulik',
                          5 => 'Kabine',
                          6 => 'Klima',
                          7 => 'Kompressor',
                          8 => 'Laermarm',
                          9 => 'Navigationssystem',
                          10 => 'Oldtimer',
                          11 => 'Standheizung',
                          12 => 'Tempomat',
                          13 => 'Traktionskontrolle',
                          14 => 'Zusatzscheinwerfer'
                    );     
                    
$lang['bikeExt'] = Array( 0 => 'ABS',
                          1 => 'Elektrostarter',
                          2 => 'Heizgriffe',
                          3 => 'Kickstarter',
                          4 => 'Koffer',
                          5 => 'Oldtimer',
                          6 => 'Scheibe',
                          7 => 'Sturzbügel',
                          8 => 'Verkleidung'
                   );    
                                           
$lang['V_EXTRA'] = Array(0 => 'CD-System',
                        1 => 'DVD-System',
                        2 => 'Elektr. Aussenspiegel',
                        3 => 'Elektr. Fensterheber',
                        4 => 'Elektr. verstellbare Sitze',
                        7 => 'Lederausstattung',
                        8 => 'Navigationssystem',
                        9 => 'Schiebedach',
                        10 => 'Sitzheizung',
                        11 => 'Sound-System',
                        12 => 'Allradantrieb',
                        13 => 'Anhängerkupplung',
                        14 => 'Behindertengerecht',
                        15 => 'Bordcomputer',
                        16 => 'Nebelscheinwerfer',
                        17 => 'ParkDistancsControl',
                        18 => 'Servolenkung',
                        19 => 'Standheizung',
                        20 => 'Tempomat',
                        21 => 'Xenonscheinwerfer',
                        22 => 'ALU-/Leichmetallfelgen',
                        23 => 'Colorverglasung',
                        24 => 'Dachreling',
                        25 => 'Heckspoiler',
                        26 => 'Spoiler',
                        27 => 'Sportsitze',                                      
                        28 => 'ABS',
                        29 => 'Airbag',
                        30 => 'Seitenairbag',
                        31 => 'Beifahrerairbag',
                        32 => 'Alarmanlage',
                        33 => 'Wegfahrsperre',
                        34 => 'ESP',
                        35 => 'Traktionskontrolle',
                        36 => 'Zentralverriegelung',
                        37 => 'kein Raucherwagen',
                        38 => 'Scheckheftgepflegt',
                        39 => 'Mwst. ausweisbar'
                        );
                    */
?>