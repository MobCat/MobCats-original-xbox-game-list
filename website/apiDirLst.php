<?php
// This is a VEREY crude and shit clone of 
// https://api.github.com/repos/YourUsername/YouRepro/git/trees/main?recursive=1
// As far as I remember this is only used in coverFlow and the api. but we are only using it in coverFlow
// This is the most trash code I have stolen in my life.

function getDirContents($path, $cdnURL) {
    $blacklist = array('*.db', '*.py', '*.md');

    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

    $files = array(); 
    foreach ($rii as $file) {
        if (!$file->isDir()) {
            $filePath = str_replace('\\', '/', $file->getPathname());
            $relativePath = str_replace($path . '/', '', $filePath);

            $exclude = false;
            foreach ($blacklist as $pattern) {
                $regex = '/' . str_replace(array('\*', '\.'), array('.*', '\.'), preg_quote($pattern, '/')) . '$/';
                if (preg_match($regex, basename($relativePath))) {
                    $exclude = true;
                    break;
                }
            }

            if (!$exclude) {
                $files[] = array(
                    'path' => $relativePath,
                    'mode' => '100644', // Assuming a default mode for files
                    'type' => 'blob',
                    //'sha' => sha1_file($filePath), // Holy shit this is slow. Yeah I don't need this right now.
                    'size' => filesize($filePath),
                    'url' => $cdnURL . '/' . $relativePath
                );
            }
        }
    }

    return $files;
}

$path = "CoverScans";
$CDNURL = "http://192.168.1.99/XboxIDs/CoverScans";
$rawCDN = getDirContents($path, $CDNURL);

// Define the JSON structure
$jsonData = array(
    'sha' => '', // Placeholder for the SHA checksum of the JSON data
    'url' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",
    'tree' => $rawCDN
);

// Calculate the SHA checksum for the JSON data
$jsonData['sha'] = sha1(json_encode($jsonData['tree']));

// Output the JSON data
header('Content-Type: application/json; charset=utf-8');
echo json_encode($jsonData, JSON_PRETTY_PRINT);
?>