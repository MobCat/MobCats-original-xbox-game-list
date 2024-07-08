<?php
// Load local db
$db = new SQLite3('titleIDs.db');

// Load $CNDPrefix and $CNDDirLst from central config
require_once('config.php');

// Pre defs 
$error = "";
$result = array();

// Valid params for ?sn or ?id etc. Map them to a db lookup.
$valid_params = array(
    'sn'   => 'Serial_Num',
    'id'   => 'Title_ID_HEX',
    'xmid' => 'XMID',
    'md5'  => 'MD5_Checksum',
);

// Load our git dir list from external source.. aka url.
function fetchJsonData($url) {
    // Initialize a cURL session
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
    
    // Execute the cURL session and fetch the response
    $response = curl_exec($ch);
    
    // Close the cURL session
    curl_close($ch);
    
    // Return the response as an associative array
    return json_decode($response, true);
}
// Function to search for a path in the JSON data
function searchPathInJson($jsonData, $searchPath) {
    // Loop through each item in the "tree" array
    foreach ($jsonData['tree'] as $item) {
        // Check if the "path" matches the search path
        if ($item['path'] === $searchPath) {
            return true;
        }
    }
    return false;
}

// "Valadate" user input from url args
// "" This is probs in no way safe or secure, we are just basic lenth and type checking..
function validateAndProcessInput($param, $value) {
    switch ($param) {
        case 'md5':
            // Check if length is 32 and contains only hexadecimal characters
            if (strlen($value) === 32 && ctype_xdigit($value)) {
                return strtoupper($value);
            }
            break;
        case 'sn':
            // Check if length is 6 and contains alphanumeric characters
            // Insert dash if it's missing
            if ($value[2] !== '-') {
                $value = substr($value, 0, 2) . '-' . substr($value, 2);
            }
            if (strlen($value) === 6) {
                return strtoupper($value);
            }
            break;
        case 'id':
            // Check if length is 8 and contains only hexadecimal characters
            //TODO: Gonna remove the 0x prefix from the data in the db at some point. it's kinda silly and not used anymore.
            //Left over formatting from like 2 years ago...
            if (stripos($value, '0x') === 0) {
                $value = substr($value, 2);
            }
            if (strlen($value) === 8 && ctype_xdigit($value)) {
                return '0x' . strtoupper($value);
            }
            break;
        case 'xmid':
            // AC00201A
            //If you use US07701W-NTSC-U and US07701W-PAL-GBR. we default back to just US07701W
            //BUGBUG: if you try and lookup api.php?xmid=US07701W-PAL-GBR we get if (!$processedValue) { errored. not sure If I wanna fix this yet.
            //We probs can just do the same lenth check as id and strip the exceses.
            if (strlen($value) === 8 && ctype_alnum($value)) {
                return strtoupper($value);
            }
    }
    return null;
}


foreach ($valid_params as $param => $column) {
    if (isset($_GET[$param])) {
        $value = $_GET[$param];
        $processedValue = validateAndProcessInput($param, $value);
        
        if ($processedValue !== null) {
            $stmt = $db->prepare("SELECT * FROM TitleIDs WHERE $column = :value");
            $stmt->bindValue(':value', $processedValue, SQLITE3_TEXT);
            $resultSet = $stmt->execute();

            //Check results
            $row = $resultSet->fetchArray(SQLITE3_ASSOC);
            
            // If result database fill out an array
            if ($row) {
                // Load dir list from github
                //Please note:
                // Githubs recursive directory list is a little slow, well a few secs slow.
                // If its to slow, I made a very shitty and crude clone. Change $CNDDirLst to point to apiDirLst.php
                if (isset($_GET['imgs'])) {
                    $gitJson = fetchJsonData($CNDDirLst);
                }

                $resultSet = $stmt->execute();
                while ($row = $resultSet->fetchArray(SQLITE3_ASSOC)) {
                    // Now we have some data in our array

                    // I need speed. If you need speed, remove &imgs to get raw data, no pre-formatted image urls.
                    // The slowdown is in githubs recursive directory listing
                    if (isset($_GET['imgs'])) {
                        // Decode cover stats array
                        $coverStats = json_decode($row['Cover_Stats'], true);
                        //HotFix: for if array null aka I havent id'ed it yet
                        $coverStats = empty($coverStats) ? ["undefined"] : $coverStats;
                        // Now we can build array keys for the for loop.
                        $keys = array_keys($coverStats);
                        // For each item of the array, postFix and lookup the image. Except the 0th index, then we just add it normaly.
                        // US07701W vs US07701W-PAL-GBR
                        for ($index = 0; $index < count($keys); $index++) {
                            $i = $coverStats[$keys[$index]];
                            // String builder
                            if ($index == 0) { // "Skip" for first index / the "Default" index.
                                $postFix = substr($row['XMID'], 0,2)."/".$row['XMID'].".jpg";
                            } else {
                                $postFix = substr($row['XMID'], 0, 2) . "/" . $row['XMID'] . "-". $i . ".jpg";
                            }
                            $titleXBX       = "xbx/".strtolower(substr($row['Title_ID_HEX'], 2))."-TitleImage.xbx";
                            $titleIcon      = "icon/".strtolower(substr($row['Title_ID_HEX'], 2))."-TitleImage.png";
                            $titleThumbnail = "thumbnail/".$postFix;
                            $titleCover     = "cover/".$postFix;
                            $titleDisc      = "disc/".$postFix;

                            // lookup and add extra data
                            //TODO: XBX lookup can be fixed / removed once we fix our dataset.
                            searchPathInJson($gitJson, $titleXBX)       ? $row['imgs'][$i]['Title_Icon_XBX'] = $CNDPrefix . $titleXBX       : null;
                            searchPathInJson($gitJson, $titleIcon)      ? $row['imgs'][$i]['Title_Icon_PNG'] = $CNDPrefix . $titleIcon      : null;
                            searchPathInJson($gitJson, $titleThumbnail) ? $row['imgs'][$i]['Thumbnail']      = $CNDPrefix . $titleThumbnail : null;
                            searchPathInJson($gitJson, $titleCover)     ? $row['imgs'][$i]['Cover_Scan']     = $CNDPrefix . $titleCover     : null;
                            searchPathInJson($gitJson, $titleDisc)      ? $row['imgs'][$i]['Disc_Scan']      = $CNDPrefix . $titleDisc      : null;
                        }
                    }
                    // Commit results
                    $result[] = $row;
                }
            } else {
                $error .= "No result found";
            }
        } else {
            $error .= "Invalid value for parameter ?$param=. Visit https://mobcat.zip/XboxIDs/APIDocs.htm for more info.";
        }
    } 
}

//BUG: This is technically incorrect, but it works.
// It will just error out, we ignore this error and jump to a new page. And stop the page from doing anything further
// This does seem to brake Invalid params error though? lol idk..
if (!$processedValue) {
    header("Location: https://mobcat.zip/XboxIDs/APIDocs.htm");
    die();
}


// Output the result or error
header('Content-Type: application/json; charset=utf-8');
if ($error) {
    // If ther was an error sitting in the error var, print that instead.
    echo json_encode(array('error' => $error));
} else {
    echo json_encode($result);
}
