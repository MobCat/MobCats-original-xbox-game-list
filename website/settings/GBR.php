<?php
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

// Lookup for custom barcodes that can not be genraited with the barcode lib.
// Custom barcodes are stored in /jbcode/custom/
//$barcodeLookup = array(
//   )

// Lookup to convert raw Features data to readable strings.
// Anything >= 26 is auto re-colored orange for xbox live.
// This does make expanding the data set a pain in the arse
// But it also means we don't have to store a color for every single entry.

$dataLookup = array(
    0  => '',                    
    1  => 'Players',             
    2  => 'System Link',         
    3  => 'Memory Unit',
    4  => 'HDD',
    5  => 'Custom Soundtracks',  
    6  => 'Dolby Digital Sound', 
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
    17 => 'Communicator Headset',
    18 => 'Steering Wheel',      
    19 => 'Light Gun',            
    20 => 'Arcade Stick',         
    21 => 'Dance pad',         
    22 => 'Keyboard',             
    23 => 'Flight Stick',         
    24 => 'Unique Controller',    
    25 => 'Ranking',           

    26 => 'Xbox <i>Live</i> Aware',
    27 => 'Online Multiplayer',    
    28 => 'Content Download',      
    29 => 'User Generated Content',
    30 => 'Scoreboards',           
    31 => 'Friends',               
    32 => 'Voice',                 
    33 => 'Game Clips',            
    34 => 'Clans',                 
    35 => '',           
);

$LANG_Verified = '✔️ Verified';
$LANG_No_features = 'No features for this title in db yet.<br>Please help.';

// Country code / box art variant lookup to string and flag icon png.
//ISO 3166-1 alpha-3 code here
// This is not the best use of an array / dict / struct.
// (We only really need the png lookup for NTSC..)
// NTSC codes are just kinda made up. PAL codes do support ISO 3166-1 though.
// When using lookups of less then 7 chars, it must be above other codes so it will match first.
// This is only really an issue for some made up NTSC variants
$LangLookup = array(
    'NTSC-U'  => ['USA.png', 'United States of America'],
    'NTSC-U-' => ['USA.png', 'United States of America'], // Platinum / special
    'NTSC-UC' => ['CAN.png', 'U.S.A / French Canada'],
    'NTSC-J'  => ['JPN.png', 'Japan'],
    'NTSC-J-' => ['JPN.png', 'Japan'], // Best of
    'NTSC-JK' => ['KOR.png', 'Korea'],
    'NTSC-JA' => ['CHN.png', 'Asia'],
    'NTSC-JT' => ['TWN.png', 'Taiwan'],
    'NTSC-JS' => ['SGP.png', 'Singapore'],
    'NTSC-JM' => ['MEX.png', 'Mexico'],

    'PAL-GBR' => ['GBR.png', 'United Kingdom of Great Britain'],
    'PAL-EUR' => ['EUR.png', 'Generic Europe'],
    'PAL-AUS' => ['AUS.png', 'Australia'],
    'PAL-NZL' => ['NZL.png', 'New Zealand'],
    'PAL-CZE' => ['CZE.png', 'Czech Republic'],
    'PAL-DEU' => ['DEU.png', 'Germany'],
    'PAL-DNK' => ['DNK.png', 'Denmark'],
    'PAL-ESP' => ['ESP.png', 'Spanish'],
    'PAL-FRA' => ['FRA.png', 'France'],
    'PAL-FIN' => ['FIN.png', 'Finland'],
    'PAL-GRC' => ['GRC.png', 'Greece'],
    'PAL-ITA' => ['ITA.png', 'Italy'],
    'PAL-NLD' => ['NLD.png', 'Kingdom of the Netherlands'],

);
// Because of our 7 char pattern matching shortcut, variants may decode / look weird. but it's ok I guess.
// (NTSC-U-Platinum) United States of America
// The actual variant is in the () while the returned string is the same for all NTSC-U games.
// So the returned string is almost useless... almost..

// Setttings.php
$LANG_Global_Settings = 'Global Settings';
$LANG_Select = 'Select your preferred language or region';
$LANG_Save   = 'Save';

// Menu Bar
$LANG_Menu_Top        = 'Top';
$LANG_Menu_Main       = 'Main';
$LANG_Menu_Attacher   = 'Attacher';
$LANG_Menu_Icons      = 'Icon Downloads';
$LANG_Menu_Features   = 'Features';
$LANG_Menu_CoverScans = 'Cover Scans';
$LANG_Menu_RawData    = 'Raw Data';
$LANG_Menu_API        = 'API';
$LANG_Menu_Settings   = 'Settings';

//Title.php
$LANG_XBE_Title_Name     = 'XBE Title Name';
$LANG_ISO_Filename       = 'ISO Filename';
$LANG_Title_ID           = 'Title ID';
$LANG_Serial_Number      = 'Serial Number';
$LANG_Publisher          = 'Publisher';
$LANG_Certification_Date = 'Certification Date';

$LANG_AKA_Title = '<h3 style="display: inline;">AKA</h3> Also Known As';

$LANG_Download = 'Download';
$LANG_Customize = 'Customize';

$LANG_Download_Icons_Title = 'Download or Embed Icons';
$LANG_Box_Art_Thumbnail = 'Box Art Thumbnail';
$LANG_Title_Icon = 'Title Icon';

$LANG_Features_Title = 'Features for';

$LANG_Cover_Scans_title = 'Cover scans | Cover Rating / UPC Barcode';

$LANG_Click_On_Scan = 'Click to open full sized scan in new tab';

$LANG_Missing_PAL_Disc_Warning = 'Please note: It is common for alternate PAL variants to not have alternate discs.
In this case, please use the default PAL-GBR disc scan where available.';

$LANG_Download_Thumbs = 'Download Thumbnails';

$LANG_Alt_Scans_Title = 'Alternate box art';

$LANG_Raw_DB_Title = 'Raw database data';

$LANG_API_Links_Title = 'API Links';

// Massiv array of arrays for decoding $row['Cover_Rating']
// AKA Content descriptors
$raitingLookup = array(
    "ESRB" => array(
        1  => 'Alcohol Reference',
        2  => 'Animated Blood',
        3  => 'Blood',
        4  => 'Blood and Gore',
        5  => 'Cartoon Violence',
        6  => 'Comic Mischief',
        7  => 'Crude Humor',
        8  => 'Drug Reference',
        9  => 'Fantasy Violence',
        10 => 'Gambling Themes',
        11 => 'Intense Violence',
        12 => 'Language',
        13 => 'Lyrics',
        14 => 'Mature Humor',
        15 => 'Nudity',
        16 => 'Partial Nudity',
        17 => 'Real Gambling',
        18 => 'Sexual Content',
        19 => 'Sexual Themes',
        20 => 'Sexual Violence',
        21 => 'Simulated Gambling',
        22 => 'Strong Language',
        23 => 'Strong Lyrics',
        24 => 'Strong Sexual Content',
        25 => 'Suggestive Themes',
        26 => 'Tobacco Reference',
        27 => 'Use of Drugs',
        28 => 'Use of Alcohol',
        29 => 'Use of Tobacco',
        30 => 'Violent References',
        31 => 'Violence',
        // I missed some and I hate this.
        32 => 'Mild Violence',
        33 => 'Mild Language',
        34 => 'Mild Suggestive Themes',
        35 => 'Mild Blood',
        36 => 'Mild Lyrics',

        99 => '10', // Documentation ID
    ),
    "PEGI" => array(
        1 => 'Bad Language',
        2 => 'Discrimination',
        3 => 'Drugs',
        4 => 'Fear/Horror',
        5 => 'Gambling',
        6 => 'Sex',
        7 => 'Violence',
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
        0  => 'Suitable for all ages',                 // Pour tous publics
        12 => 'Not recommended<br>for children under 12', // Déconseillé aux moins de 12 ans
        16 => 'Not recommended<br>for children under 16', // Déconseillé aux moins de 16 ans
        18 => 'Forbidden for children under 18',       // Interdit aux moins de 18 ans
        99 => '13', // Documentation ID
    ),
    "CERO" => array(
        // Just translasions of what the logo seas.
        0  => 'For all ages',        // ZN Old / A New
        12 => 'Ages 12 and up',      // 12 Old / B New
        15 => 'Ages 15 and up',      // 15 Old / C Bew
        17 => 'Ages 17 and up',      // D New
        18 => 'Ages 18 and up only', // 18 Old / Z New. But we are using the new ones descriptor
        99 => '14', // Documentation ID
    ),
    "USK" => array(
        // Just translasions of what the logo seas.
        0 =>  'Not age restricted',
        6 =>  'Restricted for those<br>below the age of 6',
        12 => 'Restricted for those<br>below the age of 12',
        16 => 'Restricted for those<br>below the age of 16',
        18 => 'Restricted for those<br>below the age of 18',
        99 => '15', // Documentation ID
    ),
    "ELSPA" => array(
        99 => '16', // Documentation ID
    ),
    "BBFC" => array(
        18 => 'Suitable only for persons of 18 years and over',
        99 => '17', // Documentation ID
    ),
    "aDeSe" => array(
        0  => 'Suitable for all audiences',
        13 => 'Not suitable for players<br>under 13 years of age',
        16 => 'Not suitable for players<br>under 16 years of age',
        18 => 'Not suitable for players<br>under 18 years of age',
        99 => '19', // Documentation ID
    ),
    "KMRB" => array(
        0  => 'Suitable for all ages',
        12 => 'Ages 12 and over',
        15 => 'Ages 15 and over',
        18 => 'Ages 18 and over',
        99 => '20', // Documentation ID
    ),

);
?>