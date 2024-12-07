<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $configId = $_POST['config_id'];
    $_SESSION['config_id'] = $configId;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$configId = $_SESSION['config_id'] ?? 'GBR';
$configFile = "{$configId}.php";
include $configFile;
?>
<head>
    <style>
        /* Style for image dropdown 
        .image-dropdown { 
            background-image: url('GBR.png'); Dropdown icon 
            background-repeat: no-repeat; 
            background-position: left center; 
            padding-left: 25px; Space for the icon 
        } 
        */

        body {
                    background-color: black;
                    color: #aaa;
                    margin: 15px;
                    margin-top: 10px;
                }
                h1, h3 {
                    margin: 0; 
                    padding: 0;
                    white-space: nowrap; 
                }
                img, h1 {
                    display: inline;
                    margin: 0; /* Remove default margin */
                    padding: 0; /* Remove default padding */
                }
    </style> 
</style>
</head>

<body>
    <img src="../icon.png" width="64" height="64"><h1> MobCat's Xbox Title ID Database</h1>
    <h2><?= $LANG_Global_Settings ?></h2>
    <span style="color:red"><i>Please note</i>:</span> This page and settings are WIP "Under construction".<br>
    Not all text will be translated (like this text) not all settings are<br>
    global as some pages have not been updated yet.<br>
    If you would like to help with translations, please DM MobCat.<br><br>
<a href="javascript:history.go(-1)">< Back to last page</a><br><br>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="config_id"><?= $LANG_Select ?>:</label><br>
    <select name="config_id" id="config_id">
        <option value="GBR"<?php if ($configId === 'GBR') echo ' selected="GBR"'; ?>>🇬🇧 <?= $LangLookup['PAL-GBR'][1] ?></option>
        <!--<option value="ESP"<?php if ($configId === 'ESP') echo ' selected="ESP"'; ?>>🇪🇸 <?= $LangLookup['PAL-ESP'][1] ?></option>-->
        <!--<option value="FRA"<?php if ($configId === 'FRA') echo ' selected="FRA"'; ?>>🇫🇷 <?= $LangLookup['PAL-FRA'][1] ?></option>-->
        <option value="DEU"<?php if ($configId === 'DEU') echo ' selected="DEU"'; ?>>🇩🇪 <?= $LangLookup['PAL-DEU'][1] ?></option>
        <option value="JPN"<?php if ($configId === 'JPN') echo ' selected="JPN"'; ?>>🇯🇵 <?= $LangLookup['NTSC-J'][1] ?></option>

    </select>
    <input type="submit" value="<?= $LANG_Save ?>">
</form>
</body>
