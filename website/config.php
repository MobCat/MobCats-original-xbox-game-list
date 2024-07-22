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
    'NTSC-JA' => ['CHN.png', 'Asia'], // Redump made up stuff. 90% sure this is Taiwan
    'NTSC-JT' => ['TWN.png', 'Taiwan'],

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
?>