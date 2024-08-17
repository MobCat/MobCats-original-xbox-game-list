<?php
// Load main database
$db = new SQLite3('titleIDs.db');

// Load $CNDPrefix, $CNDDirLst and $dataLookup from central config 
include 'config.php';

function countEnabledFeatures($data) {
    if (!is_array($data)) {
        return 0;
    }

    $count = 0;
    foreach ($data as $entries) {
        foreach ($entries as $entry) {
            if ($entry[0] == 1) {
                $count++;
            }
        }
    }
    return $count;
}

function renderTable($jsonData, $lookup) {
    $data = json_decode($jsonData, true);
    if (!$data) {
        return '';
    }

    $html = '';

    // Only Draw Verified if key is "1"
    if (isset($data['1'])) {
        $html .= '<div class="verified-text"><strong>Verified</strong></div>';
        $entries = $data['1'];
    } else {
        $entries = $data[0];
    }

    // Calculate number of columns (12 entries, fixed 4 rows per column)
    $numEntries = count($entries);
    $numColumns = $numEntries / 4;

    $html .= '<table border="1">';

    for ($row = 0; $row < 4; $row++) {
        $html .= '<tr>';

        for ($col = 0; $col < $numColumns; $col++) {
            $entryIndex = $row + ($col * 4);
            $entry = $entries[$entryIndex];
            $isEnabled = $entry[0] == 1;
            $lookupValue = $lookup[$entry[1]];

            $text = '';
            // Shitty logic for building strings like 'Memory Unit 20 Blocks' and 'Players 1-4'
            if (count($entry) == 2) {
                $text = $lookupValue;
            } elseif (count($entry) == 3) {
                if ($entry[1] == 3) {
                    $text = $lookupValue . ' ' . $entry[2] . ' Blocks';
                } else {
                    $text = $lookupValue . ' ' . $entry[2];
                }
            } elseif (count($entry) == 4) {
                $text = $lookupValue . ' ' . $entry[2] . '-' . $entry[3];
            }

            $color  = $entry[1] <= 25 ? '#77bb44' : '#ff8b28'; // Green and xlive orange
            $color2 = $entry[1] <= 25 ? '#507e3a' : '#c56818'; // Dark green and orange for unsupported fectuers
            // Take padding back to 5px if you ever removed this cursed math clip path
            // Random note about the "new" width="160px"
            $html .= '<td width="160px" height="20px" style="clip-path: polygon(5px 0px, calc(100% - 5px) 0px,100% 5px, 100% calc(100% - 5px), calc(100% - 5px) 100%, 5px 100%, 0px calc(100% - 5px), 0px 5px); padding: 10px; border: 3px solid '.($isEnabled ? $color : $color2).'; background-color: ' . ($isEnabled ? $color : '#ccc') . ';">';

                        
            $html .= $text . '</td>';
        }

        $html .= '</tr>';
    }

    $html .= '</table>';

    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Get DB info
    $ListDate = date('Ymd', filemtime('titleIDs.db'));
    $sumResult = $db->query('SELECT COUNT(*) as totalRows, COUNT(DISTINCT Serial_Num) as uniqueSerialNum FROM TitleIDs');
    $sumRow = $sumResult->fetchArray(SQLITE3_ASSOC);

    // Build info for meta tag
    $stats = "Last Updated: ".$ListDate."\nList contains: \n". $sumRow['totalRows'] ." Unique Title Checksums\n". $sumRow['uniqueSerialNum']. " Unique Title IDs";
    echo '
        <meta property="og:title" content="MobCat\'s OG Xbox Title ID DB v0.8">
        <meta property="og:description" content="'. $stats .'">
        <meta property="og:image" content="https://www.mobcat.zip/XboxIDs/icon.png">
        <meta name="theme-color" content="#7eb900">
    ';

    ?>
    <title>Original Xbox Title ID DB <?= $ListDate ?></title>
    <link rel="icon" type="image/x-icon" href="icon.png">
    <style>
    /* I dont like this being here, needs to go back to style.css
    However IDK how to make the new javascript for the popput box read it.. */
        body {
            background-color: black;
            color: #aaa;
        }
        .hover-text {
            text-decoration: underline;
            cursor: pointer;
            display: inline-block;
        }
        .verified-text { color: #888; }
        .popup-box {
            display: none;
            position: absolute;
            border: 1px solid black;
            background-color: #222;
            color: #555;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        th {
            padding: 8px;
            text-align: left;
            position: sticky;
            top: 0;
            background-color: #aaa;
            color: black;
            border-collapse: collapse;
            z-index: 2;
        }
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-left: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <?php // Draw info about db and download link 
    if ($DevEnv === "Dev") {
     print('<span style="color:red"><i>Loading from DEV CND</i></span><br>');
    }?>
    <h1>MobCat's Original Xbox Title ID Database</h1>
    <a href="titleIDs.db" download="MobCatsOGXboxTitleIDs.db">Download the complete database</a><br /><br>
    <i><b>Basic stats:</b></i><br>
    <?= nl2br($stats); ?><br /><br />
    <b><i>Please note:</i></b> Not all info is 100% accurate yet, and a lot is missing. I'm doing my best.<br>
    Most of this info is generated automatically by extracting metadata from the games <code>default.xbe</code> in the root of the disk.<br>
    This database only contains info about this file, no other xbes are logged yet.<br>
    Some info like Serial Number and XMID are not directly in the xbe metadata, but are generated from data within the metadata.<br>
    Some info like full title name, filenames and features are compiled from other 3rd party sources.<br>
    Because of this reliance on 3rd party sources for some data, some data in this db is factually incorrect to be more compatible with said databases.<br>
    Notes on this are available where possible.<br>
    The features listed on the back of the box are rarely correct, often contain typos and need to be verified.<br><br>
    Cover scans and box art thumbnails can be found <a href="CoverFlow.php">Here</a><br>
    Info about the web API can be found <a href="documentation/?id=2">Here</a><br><br><br>
    <table id="gamesList">
        <thead>
            <tr>
                <th>Title Icon</th>
                <th>Title ID</th>
                <th>Serial Number</th>
                <th>XMID</th>
                <th>Full Name<br><sub>(Filename)</sub></th>
                <th>Title Name</th>
                <th>Publisher</th>
                <th>Features<br><sub>(Click to toggle)</sub></th>
                <th>Region</th>
                <th>ESRB Rating</th>
                <th>Cert Timestamp</th>
                <th>default.xbe<br>MD5 Checksum<br><sub>(Click for more info)</sub></th>
            </tr>
        </thead>
        <tbody>
    <?php
    $results = $db->query('SELECT * FROM TitleIDs ORDER BY Full_Name');
    while ($row = $results->fetchArray()) {
        $features = $row['Features'];
        $jsonData = json_decode($features, true);

        
        //DEBUG: Only render rows for titles that have Features json table in db.
        // this should be the ONLY change from tableTest.php
        /*
        if (!$jsonData) {
            continue;
        }*/
        
        $enabledCount = countEnabledFeatures($jsonData);
        $tableHTML = renderTable($features, $dataLookup);
        $uniqueId = uniqid();

        //https://raw.githubusercontent.com/MobCat/MobCats-original-xbox-game-list/main/icon/4d530058-TitleImage.png
        //https://github.com/MobCat/MobCats-original-xbox-game-list/raw/main/xbx/00000002-TitleImage.xbx
        //$titleIcon = substr($row['Title_Image'], 14, -1);
        $titleIconPNG = $CNDPrefix."icon/".substr($row['Title_ID'], 0, 4)."/".$row['Title_ID'].".png";
        $titleIconXBX = $CNDPrefix."xbx/".substr($row['Title_ID'], 0, 4)."/".$row['Title_ID'].".png";

        $detailsLink = "<a href=\"title.php?".$row['XBE_MD5']."\" target=\"_blank\">".$row['XBE_MD5']."</a>";

        // Only print "Bad Dumps" / bad xiso converts if we in Dev environment
        if ($row['Full_Name'] !== '!BAD DUMP!' || $DevEnv == "Dev") {
    ?>
        <tr id='<?= $row['XBE_MD5'] ?>'>
            <!-- <td><img src="<?= $titleIconPNG ?>" width="128" height="128" onerror="this.onerror=null; this.src='missing-icon-CDX.png';"></td> -->
            <td><img src="<?= $titleIconPNG ?>" width="128" height="128"></td>
            <td><?= $row['Title_ID']?></td>
            <td><?= $row['Serial_Num'] ?></td>
            <td><?= $row['XMID'] ?></td>
            <td><?= $row['Full_Name'] ?><br><br><sup><?= $row['Filename'] ?></sup></td>
            <td><?= $row['Title_Name'] ?></td>
            <td><?= $row['Publisher'] ?></td>
            <td>
        <div class="hover-text" id="hoverText<?= $uniqueId ?>">
            <?php
                if (!$jsonData) {
                    echo "";
                } else {
                    echo "$enabledCount supported features"; 
                }
            ?> 
        </div>
        <div class="popup-box" id="popupBox<?= $uniqueId ?>">
            <?= $tableHTML; ?>
        </div>
        </td>
        <td><?= $row['Region'] ?></td>
        <td><?= $row['XBE_Rating'] ?></td>
        <td><?= $row['Cert_Timestamp'] ?></td>
        <td><?= $detailsLink ?></td>
    </tr>
    <?php
    }
    }
    ?>
</tbody>
</table>

<?php ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.hover-text').forEach(function (hoverText) {
                hoverText.addEventListener('click', function (event) {
                    var popupBox = document.getElementById('popupBox' + hoverText.id.replace('hoverText', ''));
                    var currentlyDisplayed = popupBox.style.display === 'block';
                    var allPopupBoxes = document.querySelectorAll('.popup-box');

                    allPopupBoxes.forEach(function(box) {
                        box.style.display = 'none';
                    });

                    if (!currentlyDisplayed) {
                        var rect = hoverText.getBoundingClientRect();
                        popupBox.style.top = rect.bottom + window.scrollY + 'px';
                        popupBox.style.left = rect.left + window.scrollX + 'px';
                        popupBox.style.display = 'block';
                    }
                });
            });

            document.addEventListener('click', function (event) {
                document.querySelectorAll('.popup-box').forEach(function (popupBox) {
                    if (!popupBox.previousElementSibling.contains(event.target) && !popupBox.contains(event.target)) {
                        popupBox.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>