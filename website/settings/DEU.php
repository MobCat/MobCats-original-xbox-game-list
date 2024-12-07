<?php
// This lang file is mechinene translated. sorry.
// CDN Setup
//TODO: figure out if we can stop / 404 when someone trys to visit config.php manually in browser.
//header('HTTP/1.0 404 Not Found', true, 404);
//http_response_code(404);
//BUGBUG: The above code brakes meta tags.
// The meta render thing tries to include our config php, sees it's 404'ing and then nopes out.
// With this 404 now set, discord or wherever will now no longer render anything, because the website is "404".
// It doesn't know the difference between say, title.php or config.php being included by title.php.
// It simply saw a 404, and then stops.

// Change this IP to your dev server.
// In my case I use http://192.168.1.99/ to get to the dev server.
// However if you use something like https://dev.url/ then you would change this to "dev.url"

if ($_SERVER['HTTP_HOST'] != "192.168.1.99"){
    // Prod:
    $MainURL   = "https://mobcat.zip/XboxIDs/";
    $CNDPrefix = "https://raw.githubusercontent.com/MobCat/MobCats-original-xbox-game-list/main/";
    $CNDDirLst = "https://api.github.com/repos/MobCat/MobCats-original-xbox-game-list/git/trees/main?recursive=1";
    $DevEnv    = "Prod";
} else {
    // Dev:
    $MainURL   = "http://192.168.1.99/XboxIDs/";
    $ProdURL   = "https://mobcat.zip/XboxIDs/";
    $CNDPrefix = "http://192.168.1.99/XboxIDs/CoverScans/";
    $CNDDirLst = "http://192.168.1.99/XboxIDs/apiDirLst.php";
    $DevEnv    = "Dev";
}

// If your feeling adventurous, you could build out this config to do auto fail over, or any number of checks or advanced conf setups.
// Check if the CDN is even there before trying to load, if it's not, pick a new CDN.
// Check where the user is located and load the CDN that is closest. Some $_SERVER['REMOTE_ADDR'] bs.
// It's a php script, you can do whatever you want.

// Global lookup dicts.
// Moved here to make editing or changing language easier.

// PLEASE NOTE:
// All of this is sorta just placeholder. none of theses dict indexes are "set in stone" yet
// This will change. my bad.. 

// Lookup to convert raw Features data to readable strings.
// Anything >= 26 is auto re-colored orange for xbox live.
// This does make expanding the data set a pain in the arse
// But it also means we don't have to store a color for every single entry.

$dataLookup = array(
    0  => '',                    
    1  => 'Spieler',             
    2  => 'System Link',         
    3  => 'Memory Unit',
    4  => 'HDD',
    5  => 'Eigene Soundtracks',  
    6  => 'Dolby Digital-UnterstÜtzung', 
    7  => '480i',                
    8  => 'HDTV',                
    9  => 'HDTV 480p',           
    10 => 'HDTV 720p',           
    11 => 'HDTV 1080i',          
    12 => 'HDTV 16 x 9',     
    13 => 'Widescreen',          
    14 => 'PAL 50Hz Only',       
    15 => 'NTSC 60Hz Only',     
    16 => 'Region',       
    17 => 'Kommunikator Headset',
    18 => 'Lenkrad',      
    19 => 'Lichtkanone',            
    20 => 'Arcade Stick',         
    21 => 'Tanzkissen',         
    22 => 'Tastatur',             
    23 => 'Flight Stick',         
    24 => 'Einzigartiger Controller',    
    25 => 'Rangliste',           

    26 => 'Xbox <i>Live</i> Aware',
    27 => 'Online-Mehrspielermodus',    
    28 => 'Downloadbare Inhalte',      
    29 => 'Benutzergenerierte Inhalte',
    30 => 'Ranglisten', // Anzeiger? as ratings is a typo?
    31 => 'Freunde',               
    32 => 'Sprachausgabe',                 
    33 => 'Spiel Clips',            
    34 => 'Clans',                 
    35 => '',           
);

$LANG_Verified = '✔️ Geprüft';
$LANG_No_features = 'Für diesen Titel gibt es noch keine Merkmale in der db.<br>Bitte helfen Sie.';

// Country code / box art variant lookup to string and flag icon png.
//ISO 3166-1 alpha-3 code here
// This is not the best use of an array / dict / struct.
// (We only really need the png lookup for NTSC..)
// NTSC codes are just kinda made up. PAL codes do support ISO 3166-1 though.
// When using lookups of less then 7 chars, it must be above other codes so it will match first.
// This is only really an issue for some made up NTSC variants
$LangLookup = array(
    'NTSC-U'  => ['USA.png', 'Vereinigte Staaten von Amerika'],
    'NTSC-U-' => ['USA.png', 'Vereinigte Staaten von Amerika'], // Platinum / special
    'NTSC-UC' => ['CAN.png', 'U.S.A. / Französisch-Kanada'],
    'NTSC-J'  => ['JPN.png', 'Japan'],
    'NTSC-J-' => ['JPN.png', 'Japan'], // Best of
    'NTSC-JK' => ['KOR.png', 'Korea'],
    'NTSC-JA' => ['CHN.png', 'Asien'],
    'NTSC-JT' => ['TWN.png', 'Taiwan'],
    'NTSC-JS' => ['SGP.png', 'Singapur'],
    'NTSC-JM' => ['MEX.png', 'Mexiko'],

    'PAL-GBR' => ['GBR.png', 'Vereinigtes Königreich von Großbritannien'],
    'PAL-EUR' => ['EUR.png', 'Generisches Europa'],
    'PAL-AUS' => ['AUS.png', 'Australien'],
    'PAL-NZL' => ['NZL.png', 'Neuseeland'],
    'PAL-CZE' => ['CZE.png', 'Tschechische Republik'],
    'PAL-DEU' => ['DEU.png', 'Deutschland'],
    'PAL-DNK' => ['DNK.png', 'Dänemark'],
    'PAL-ESP' => ['ESP.png', 'Spanisch'],
    'PAL-FRA' => ['FRA.png', 'Frankreich'],
    'PAL-FIN' => ['FIN.png', 'Finnland'],
    'PAL-GRC' => ['GRC.png', 'Griechenland'],
    'PAL-ITA' => ['ITA.png', 'Italien'],
    'PAL-NLD' => ['NLD.png', 'Königreich der Niederlande'],

);
// Because of our 7 char pattern matching shortcut, variants may decode / look weird. but it's ok I guess.
// (NTSC-U-Platinum) United States of America
// The actual variant is in the () while the returned string is the same for all NTSC-U games.
// So the returned string is almost useless... almost..

// Setttings.php
$LANG_Global_Settings = 'Globale Einstellungen';
$LANG_Select = 'Wählen Sie Ihre bevorzugte Sprache oder Region';
$LANG_Save   = 'Speichern Sie';

// Menu Bar
$LANG_Menu_Top        = 'Oben';
$LANG_Menu_Main       = 'Hauptmenü';
$LANG_Menu_Attacher   = 'Attacher';
$LANG_Menu_Icons      = 'Icon Herunterladen';
$LANG_Menu_Features   = 'Eigenschaften';
$LANG_Menu_CoverScans = 'Titelbild scannen';
$LANG_Menu_RawData    = 'Rohdaten';
$LANG_Menu_API        = 'API';
$LANG_Menu_Settings   = 'Einstellungen';

//Title.php
$LANG_XBE_Title_Name     = 'XBE Titel Name';
$LANG_ISO_Filename       = 'ISO Dateiname';
$LANG_Title_ID           = 'Title ID';
$LANG_Serial_Number      = 'Seriennummer';
$LANG_Publisher          = 'Herausgeber';
$LANG_Certification_Date = 'Datum der Zertifizierung';

$LANG_AKA_Title = '<h3 style="display: inline;">ABA</h3> Auch bekannt als';

$LANG_Download = 'Herunterladen';
$LANG_Customize = 'Anpassen';

$LANG_Download_Icons_Title = 'Icons herunterladen oder einbetten';
$LANG_Box_Art_Thumbnail = 'Box Art Vorschaubild';
$LANG_Title_Icon = 'Titel Symbol';

$LANG_Features_Title = 'Merkmale für';

$LANG_Cover_Scans_title = 'Cover Scans | Cover Bewertung / UPC Barcode';

$LANG_Click_On_Scan = 'Anklicken, um den Scan in voller Größe in einem neuen Tab zu öffnen';

$LANG_Missing_PAL_Disc_Warning = 'Bitte beachten Sie: Es ist üblich, dass alternative PAL Varianten keine alternativen Discs haben.
In diesem Fall verwenden Sie bitte den Standard PAL-GBR Disc Scan, sofern verfügbar.';

$LANG_Download_Thumbs = 'Thumbnails herunterladen';

$LANG_Alt_Scans_Title = 'Alternatives Verpackungsdesign';

$LANG_Raw_DB_Title = 'Datenbank Rohdaten';

$LANG_API_Links_Title = 'API Links';

// Massiv array of arrays for decoding $row['Cover_Rating']
// AKA Content descriptors
$raitingLookup = array(
    "ESRB" => array(
        1  => 'Alkohol-Referenz',
        2  => 'Lebendiges Blut',
        3  => 'Blut',
        4  => 'Blut und Blutvergießen',
        5  => 'Cartoon Gewalt',
        6  => 'Komischer Unfug',
        7  => 'Grober Humor',
        8  => 'Medikamenten-Referenz',
        9  => 'Fantasie Gewalt',
        10 => 'Glücksspiel-Themen',
        11 => 'Intensive Gewalt',
        12 => 'Sprache',
        13 => 'Liedtext',
        14 => 'Reifer Humor',
        15 => 'Nacktheit',
        16 => 'Partielle Nacktheit',
        17 => 'Echtes Glücksspiel',
        18 => 'Sexueller Inhalt',
        19 => 'Sexuelle Themen',
        20 => 'Sexuelle Gewalt',
        21 => 'Simuliertes Glücksspiel',
        22 => 'Starke Sprache',
        23 => 'Starker Liedtext',
        24 => 'Starker sexueller Inhalt',
        25 => 'Anzügliche Themen',
        26 => 'Referenz Tabak',
        27 => 'Gebrauch von Drogen',
        28 => 'Alkoholkonsum',
        29 => 'Tabakkonsum',
        30 => 'Gewalttätige Referenzen',
        31 => 'Gewalt',
        //
        32 => 'Leichte Gewalttätigkeit',
        33 => 'Leichte Sprache',
        34 => 'Leichte anzügliche Themen',
        35 => 'Leichte Blut',
        36 => 'Leichte Liedtext',
        99 => '10', // Documentation ID
    ),
    "PEGI" => array(
        1 => 'Schlechte Sprache',
        2 => 'Diskriminierung',
        3 => 'Medikamente',
        4 => 'Furcht/Horror',
        5 => 'Glücksspiel',
        6 => 'Geschlecht',
        7 => 'Gewalt',
        8 => 'Online',
        99 => '11', // Documentation ID
    ),
    "ACB" => array( 
        // Office of Film & Literature Classification or now known as 
        // Australian Classification Board dont really have Content descriptors
        // we dident even get MA and R games untill after the og xbox so this is
        // more of a placeholder then anything.
        1 => 'General',
        2 => 'Very Mild',
        3 => 'Very Low',
        4 => 'Mild',
        5 => 'Low',
        6 => 'Medium Level',
        7 => 'Moderate',
        8 => 'Strong',
        9 => 'High',
        10 => 'Very High',
        11 => 'Extreme',

        20 => 'Violence',
        21 => 'Horror Theme',
        22 => 'Coarse Language',
        23 => 'Drug Themes',
        24 => 'Sexual References',
        25 => 'Animated Violence', // Maybe Animated and Fantasy should remove Violence post fix
        26 => 'Fantasy Violence',  // to get Fantasy Violence we would just do [17, 11]
        27 => 'Gambling References',
        28 => 'Adult Themes',
        29 => 'Coarse Language',
        30 => 'Blood and Gore',

        99 => '12', // Documentation ID

    ),
    "SELL" => array(
        // Just translasions of what the logo seas.
        0  => 'Geeignet für alle Altersgruppen',                 // Pour tous publics
        12 => 'Nicht empfohlen für Kinder unter 12 Jahren', // Déconseillé aux moins de 12 ans
        16 => 'Nicht empfohlen für Kinder unter 16 Jahren', // Déconseillé aux moins de 16 ans
        18 => 'Verboten für Kinder unter 18 Jahren',       // Interdit aux moins de 18 ans
        99 => '13', // Documentation ID
    ),
    "CERO" => array(
        // Just translasions of what the logo seas.
        0  => 'Für alle Altersgruppen',        // ZN Old / A New
        12 => '12 Jahre und älter',      // 12 Old / B New
        15 => '15 Jahre und älter',      // 15 Old / C Bew
        17 => '17 Jahre und älter',      // D New
        18 => '18 Jahre und älter', // 18 Old / Z New. But we are using the new ones descriptor
        99 => '14', // Documentation ID
    ),
    "USK" => array(
        // Just translasions of what the logo seas.
        0 =>  'Keine Altersbeschränkung',
        6 =>  'Eingeschränkt für<br>personen unter 6 Jahren',
        12 => 'Eingeschränkt für<br>personen unter 12 Jahren',
        16 => 'Eingeschränkt für<br>personen unter 16 Jahren',
        18 => 'Beschränkt für<br>personen unter 18 Jahren',
        99 => '15', // Documentation ID
    ),
    "ELSPA" => array(
        99 => '16', // Documentation ID
    ),
    "BBFC" => array(
        99 => '17', // Documentation ID
    ),
    "aDeSe" => array(
        0  => 'Geeignet für alle Zielgruppen',
        13 => 'Nicht geeignet für Spieler<br>unter 13 Jahren',
        16 => 'Nicht geeignet für Spieler<br>unter 16 Jahren',
        18 => 'Nicht geeignet für Spieler<br>unter 18 Jahren',
        99 => '19&l=deu', // Documentation ID
    ),
    "KMRB" => array(
        0  => 'Für alle Altersgruppen',
        12 => '12 Jahre und älter',
        15 => '15 Jahre und älter',
        18 => '18 Jahre und älter',
        99 => '20', // Documentation ID
    ),

);
?>