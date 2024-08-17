<?php

// Main builder
function buildAttacher($id, $name) {
    // Convert ID and name
    // hexToLittleEndian
    $idHex = implode('', array_reverse(str_split($id, 2)));
    // stringToUTF16BE
    $nameHex = bin2hex(mb_convert_encoding($name, 'UTF-16LE'));

    // Combine ID and name
    $hexCode = $idHex . $nameHex;

    // Read default.data
    $file = file_get_contents('default.data');

    // Inject hex code at offset 0x00000180
    $file = substr_replace($file, hex2bin($hexCode), 0x180, strlen($hexCode) / 2);

    return $file;
}

// Process user input if set
if (isset($_POST['id']) && isset($_POST['name'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];

    // Validate ID length and format
    if (strlen($id) !== 8 || !ctype_xdigit($id)) {
        die('ID must be 8 characters long and in hexadecimal format.');
    }

    // Validate name length
    if (strlen($name) > 42) {
        die('Name must be at most 42 characters long.');
    }

    // Build attacher with user input, then download the xbe.
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="default.xbe"');
    echo (buildAttacher($id, $name));
    exit;

}

// If no user input, only API. Procuess URL args
$XMID = isset($_GET['XMID']) ? $_GET['XMID'] : null;
$D = isset($_GET['download']) ? $_GET['download'] : null;
$prefillXMID = $XMID !== null ? htmlspecialchars($XMID) : '';

// Auto load data from db, setup attacher and download it.
if ($XMID !== '' && $D !== null) {
    //TODO: You should change this to where your copy of the titleDB is.
    //If you place it in the same folder as this php script, just use 'titleIDs.db'
    $db = new SQLite3('../XboxIDs/titleIDs.db');
    $stmt = $db->prepare("SELECT Title_ID, Full_Name FROM TitleIDs WHERE XMID = ?");
    $stmt->bindValue(1, $prefillXMID, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $titleID = $row['Title_ID'];
        $titleNm = $row['Full_Name'];
    }

    // Change the headder to stream our file as a download.
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="default.xbe"');
    echo (buildAttacher($titleID, $titleNm));
    exit;

}
// If no API input from user auto download, load imput form
$titleID = "";
$titleNm = "";
$FolderNameEgg = "My Cool Game";
$XISONameEgg = "Cool Game";

// If XMID set, load data.
if ($prefillXMID !== '') {
    // Does sorta feel like im dubbleing up code hre, but its fine I gueess...
    $db = new SQLite3('../XboxIDs/titleIDs.db');
    $stmt = $db->prepare("SELECT Title_ID, Full_Name, Title_Name, Filename FROM TitleIDs WHERE XMID = ?");
    $stmt->bindValue(1, $prefillXMID, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $titleID = $row['Title_ID'];
        $titleNm = $row['Full_Name'];
        $FolderNameEgg = $row['Title_Name'];
        $XISONameEgg = $row['Filename'];

        if ($FolderNameEgg == ''){
            $FolderNameEgg = "My Cool Game";
        }
    } 

}
// Else, just load the from
// Set weither we can edit the title ID input form theough
$idDisabled = $titleID !== '' ? 'disabled' : '';




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icon.png">
    <title>ISO Attacher Builder</title>
            <style>
        @media (prefers-color-scheme: dark) {
            /* Dark mode styles go burr */
            body {
                background-color: black;
                color: white;
            }
        }
        /* Inline the image and header */
        img, h1 {
        display: inline;
        margin: 0; /* Remove default margin */
        padding: 0; /* Remove default padding */
        }
        input:disabled {
            background-color: #dfdede;
        }
    </style>
</head>
<body>
    <h1>MobCat's super simple XISO attacher builder for og xbox</h1><br><br>
    <b>Usage:</b> Enter your Title ID in hex and the Game Title you wish to see on your dashboard<br>
    In the below text boxes, then click Build.<br>
    You can see a full list of title IDs <a href="../XboxIDs" target="_blank">here</a><br>
    A <code>default.xbe</code> will automatically download with these settings.<br>
    Place this <code>default.xbe</code> in the same game folder as your game iso. Like this.<br>
    <pre>F/
 Games/
      <?= $FolderNameEgg ?>/
                  default.xbe
                  <?= $XISONameEgg ?>.iso
        </pre>

    Then run the game from your custom dash like you would any other game.<br>
    Please note: Your custom bios or softmod must support ISO attaching and loading, you can find more info about this <a href="../XboxGuides/XISO/Mount" target="_blank">here</a><br><br>
    <form id="fileBuilderForm" action="" method="post">
        <label for="id">Title ID (Hex, 8 chars):</label><br>
        <input type="text" id="id" name="id" minlength="8" maxlength="8" pattern="[0-9A-Fa-f]{8}" value="<?= htmlspecialchars($titleID) ?>" <?= $idDisabled ?> required><br><br>
        <?php if ($idDisabled): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($titleID) ?>">
        <?php endif; ?>
        <label for="name">Game Title (42 chars max):</label><br>
        <input type="text" id="name" name="name" minlength="1" maxlength="42" size="42" value="<?= htmlspecialchars($titleNm) ?>" required><br><br>
        <input type="submit" value="Build attacher" id="submitButton"><br><br>
        <?php //BUGBUG: Form reset does not work if $idDisabled. Don't really cear to fix it right now, just reload page without ?XMID 
        // Ok... not a bug. It resets the form back to the pre filled state, aka just undoes your changes, not clears the form?>
        <input type="reset" value="Reset form">
    </form>
</body>
</html>
