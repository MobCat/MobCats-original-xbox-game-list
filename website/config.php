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
    $CNDPrefix = "https://raw.githubusercontent.com/MobCat/MobCats-original-xbox-game-list/main/";
    $CNDDirLst = "https://api.github.com/repos/MobCat/MobCats-original-xbox-game-list/git/trees/main?recursive=1";
    $DevEnv    = "Prod";
} else {
    // Dev:
    $CNDPrefix = "http://192.168.1.99/XboxIDs/CoverScans/";
    $CNDDirLst = "http://192.168.1.99/XboxIDs/apiDirLst.php";
    $DevEnv    = "Dev";
}

// If your feeling adventurous, you could build out this config to do auto fail over, or any number of checks or advanced conf setups.
// Check if the CDN is even there before trying to load, if it's not, pick a new CDN.
// Check where the user is located and load the CDN that is closest. Some $_SERVER['REMOTE_ADDR'] bs.
// It's a php script, you can do whatever you want.
?>