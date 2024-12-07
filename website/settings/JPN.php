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

// Lookup to convert raw Features data to readable strings.
// Anything >= 26 is auto re-colored orange for xbox live.
// This does make expanding the data set a pain in the arse
// But it also means we don't have to store a color for every single entry.
//BUG: These translasions are dog shit, and need to be fixed. Right now they are just a place holder.
$dataLookup = array(
    0  => '',                    
    1  => '選手',             
    2  => 'システムリンク',         
    3  => '記憶装置',
    4  => 'HDD',
    5  => 'サウンドトラック',  
    6  => 'ドルビーデジタルサウンド', 
    7  => '480i',                
    8  => 'HDTV',                
    9  => 'HDTV 480p',           
    10 => 'HDTV 720p',           
    11 => 'HDTV 1080i',          
    12 => 'HDTV 16 x 9',     
    13 => 'ワイド画面',          
    14 => 'PAL 50Hz Only',       
    15 => 'NTSC 60Hz Only',     
    16 => '地域',       
    17 => '通信機用ヘッドセット',
    18 => 'ハンドル',      
    19 => 'ライトガン',            
    20 => 'アーケードスティック',         
    21 => 'ダンスパッド',         
    22 => 'キーボード',             
    23 => 'フライトスティック',         
    24 => '独自のコントローラー',    
    25 => '順位',           

    26 => 'Xbox <i>ライブ</i> アウェア',
    27 => 'オンラインマルチプレイヤー',    
    28 => 'コンテンツダウンロード',      
    29 => 'ユーザー生成コンテンツ',
    30 => '得点板',           
    31 => '友人',               
    32 => '音声通信',                 
    33 => 'ゲームクリップ',            
    34 => '氏族',                 
    35 => '',           
);

$LANG_Verified = 'Verified ⭕';
$LANG_No_features = 'dbにこのタイトルの機能がまだありません。<br>助けてください。';

// Country code / box art variant lookup to string and flag icon png.
//ISO 3166-1 alpha-3 code here
// This is not the best use of an array / dict / struct.
// (We only really need the png lookup for NTSC..)
// NTSC codes are just kinda made up. PAL codes do support ISO 3166-1 though.
// When using lookups of less then 7 chars, it must be above other codes so it will match first.
// This is only really an issue for some made up NTSC variants
// TODO: Because of 日本語 and 日本国 we can use this lang selector for both cover stats and lang sector
// But it will be ok for a test.
$LangLookup = array(
    'NTSC-U'  => ['USA.png', 'アメリカ合衆国'],
    'NTSC-U-' => ['USA.png', 'アメリカ合衆国'], // Platinum / special
    'NTSC-UC' => ['CAN.png', 'アメリカ / カナダ'],
    'NTSC-J'  => ['JPN.png', '日本語'],
    'NTSC-J-' => ['JPN.png', '日本語'], // Best of
    'NTSC-JK' => ['KOR.png', '朝鮮'],
    'NTSC-JA' => ['CHN.png', 'アジア'],
    'NTSC-JT' => ['TWN.png', '台湾'],
    'NTSC-JS' => ['SGP.png', 'シンガポール'],
    'NTSC-JM' => ['MEX.png', 'メキシコ'],

    'PAL-GBR' => ['GBR.png', 'グレートブリテン英国'],
    'PAL-EUR' => ['EUR.png', '欧州'],
    'PAL-AUS' => ['AUS.png', '豪州'],
    'PAL-NZL' => ['NZL.png', 'ニュージーランド'],
    'PAL-CZE' => ['CZE.png', 'チェコ共和国'],
    'PAL-DEU' => ['DEU.png', 'ドイツ'],
    'PAL-DNK' => ['DNK.png', 'デンマーク'],
    'PAL-ESP' => ['ESP.png', 'スペイン語'],
    'PAL-FRA' => ['FRA.png', '仏国'],
    'PAL-FIN' => ['FIN.png', 'フィンランド'],
    'PAL-GRC' => ['GRC.png', 'ギリシア共和国'],
    'PAL-ITA' => ['ITA.png', 'イタリア'],
    'PAL-NLD' => ['NLD.png', 'オランダ王国'],

);
// Because of our 7 char pattern matching shortcut, variants may decode / look weird. but it's ok I guess.
// (NTSC-U-Platinum) United States of America
// The actual variant is in the () while the returned string is the same for all NTSC-U games.
// So the returned string is almost useless... almost..

// Settings
$LANG_Global_Settings = 'グローバル設定';
$LANG_Select = 'ご希望の言語または地域をお選びください。';
$LANG_Save   = 'セーブ';

// Menu Bar
$LANG_Menu_Top        = '上';
$LANG_Menu_Main       = 'メイン';
$LANG_Menu_Attacher   = 'Attacher';
$LANG_Menu_Icons      = 'アイコンダウンロード';
$LANG_Menu_Features   = 'システムの特徴';
$LANG_Menu_CoverScans = 'カバースキャン';
$LANG_Menu_RawData    = '生データ';
$LANG_Menu_API        = 'API';
$LANG_Menu_Settings   = '設定';

//Title.php
$LANG_XBE_Title_Name     = 'XBE タイトル名';
$LANG_ISO_Filename       = 'ISO ファイル名';
$LANG_Title_ID           = '名 ID';
$LANG_Serial_Number      = 'シリアル番号';
$LANG_Publisher          = '出版社';
$LANG_Certification_Date = '認定日';

$LANG_AKA_Title = '<h3 style="display: inline;">別名</h3>';

$LANG_Download = 'ダウンロード';
$LANG_Customize = 'カスタマイズ';

$LANG_Download_Icons_Title = 'アイコンのダウンロードまたは埋め込み';
$LANG_Box_Art_Thumbnail = 'ボックスアートサムネイル';
$LANG_Title_Icon = 'タイトルアイコン';

$LANG_Features_Title = '特徴の';

$LANG_Cover_Scans_title = '表紙スキャン | 表紙格付け';

$LANG_Click_On_Scan = 'ボックスアートスキャンをクリックすると新しいタブで開きます。';

$LANG_Missing_PAL_Disc_Warning = '注意：PALの代替版には代替ディスクがないのが普通です。
この場合、デフォルトのPAL-GBRディスクスキャンが利用可能な場合は、それを使用してください。';

$LANG_Download_Thumbs = 'サムネイル画像のダウンロード';

$LANG_Alt_Scans_Title = 'の代替ボックスアート';

$LANG_Raw_DB_Title = 'データベースの生データ';

$LANG_API_Links_Title = 'APIのリンク';

// Massiv array of arrays for decoding $row['Cover_Rating']
// AKA Content descriptors
$raitingLookup = array(
    "ESRB" => array(
        1  => 'アルコール参考文献',
        2  => '血のアニメ',
        3  => '血',
        4  => '血と血糊',
        5  => '漫画の暴力',
        6  => 'コミックのいたずら',
        7  => '下品なユーモア',
        8  => '医薬品リファレンス',
        9  => 'ファンタジーの暴力',
        10 => 'ギャンブルのテーマ',
        11 => '激しい暴力',
        12 => '言語',
        13 => '歌詞',
        14 => '成熟したユーモア',
        15 => '裸体',
        16 => '裸体部分的',
        17 => '本物のギャンブル',
        18 => '性的コンテンツ',
        19 => '性的テーマ',
        20 => '性的暴力',
        21 => '疑似ギャンブル',
        22 => '強い言語',
        23 => '強い歌詞',
        24 => '強い性的コンテンツ',
        25 => '暗示的なテーマ',
        26 => 'タバコのリファレンス',
        27 => '薬物使用',
        28 => '飲酒',
        29 => 'タバコの使用',
        30 => '暴力的な言及',
        31 => '暴力',
        //
        32 => '軽度暴力',
        33 => '軽度言語',
        34 => '軽度暗示的テーマ',
        35 => '軽度血',
        36 => '軽度歌詞',

        99 => '10', // Documentation ID
    ),
    "PEGI" => array(
        1 => '悪口',
        2 => '差別',
        3 => '医薬品',
        4 => '恐怖かホラーか',
        5 => 'ギャンブル',
        6 => '性',
        7 => '暴力',
        8 => 'ネット',
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
        25 => 'Animated Violence', 
        26 => 'Fantasy Violence', 
        27 => 'Gambling References',
        28 => 'Adult Themes',
        29 => 'Coarse Language',
        30 => 'Blood and Gore',

        99 => '12', // Documentation ID
    ),
    "SELL" => array(
        // Just translasions of what the logo seas.
        0  => '全年齢対象',                      // Pour tous publics
        12 => '12歳以下のお子様にはお勧めできません。', // Déconseillé aux moins de 12 ans
        16 => '16歳以下のお子様にはお勧めできません。', // Déconseillé aux moins de 16 ans
        18 => '18歳未満入場不可',                // Interdit aux moins de 18 ans
        99 => '13', // Documentation ID
    ),
    "CERO" => array(
        // Just translasions of what the logo seas.
        0  => '全年齢',          // ZN Old / A New
        12 => '12才以上対象',    // 12 Old / B New
        15 => '15才以上対象',    // 15 Old / C Bew
        17 => '17才以上対象',    // D New
        18 => '18才以上のみ対象', // 18 Old / Z New. But we are using the new ones descriptor
        99 => '14', // Documentation ID
    ),
    "USK" => array(
        // Just translasions of what the logo seas.
        0 =>  '年齢制限なし',
        6 =>  '6歳以下の入場制限',
        12 => '12歳以下の入場制限',
        16 => '16歳以下の入場制限',
        18 => '18歳以下の入場制限',
        99 => '15', // Documentation ID
    ),
    "ELSPA" => array(
        99 => '16', // Documentation ID
    ),
    "BBFC" => array(
        99 => '17', // Documentation ID
    ),
    "aDeSe" => array(
        0  => 'すべての観客に適している',
        13 => '13歳未満のプレーヤ<br>ーには適していない。',
        16 => '16歳未満のプレーヤ<br>ーには適していない。',
        18 => '18歳未満のプレーヤ<br>ーには適していない。',
        99 => '19&l=jpn', // Documentation ID. lol that would work to hack in landuge support for docs too.
        // But that beeing said, we really should just be reading the cookie setting we have already set
        // not doing some dumb url arg..
    ),
    "KMRB" => array(
        0  => '全年齢',
        12 => '12才以上対象',
        15 => '15才以上対象',
        18 => '18才以上のみ対象',
        99 => '20', // Documentation ID
    ),
);
?>