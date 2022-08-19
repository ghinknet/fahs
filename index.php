<?php
$DEBUG = false;
$langs = array("zh_CN", "en_US");

if (!isset($_GET["donor"]) || !isset($_GET["team"])) {
    exit("empty field");
}

if (!isset($_GET["lang"]) || !in_array($_GET["lang"], $langs)) {
    $lang = "zh_CN";
} else {
    $lang = $_GET["lang"];
}

// Get donor
$donor = $_GET["donor"];

// Setting foor ssl
$arr_context_options = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
        "allow_self_signed" => true,
    ) ,
);

// Request for data
$donor_data = file_get_contents("https://api.foldingathome.org/user/".$donor, false, stream_context_create($arr_context_options)) or die("api failed");
$donor_object = json_decode($donor_data, true);
if ($DEBUG) var_dump($donor_object);

$team_data = file_get_contents("https://api.foldingathome.org/team/".$_GET["team"], false, stream_context_create($arr_context_options)) or die("api failed");
$team_object = json_decode($donor_data, true);
if ($DEBUG) var_dump($team_object);

// Load canvas
$canvas = imagecreatetruecolor(465, 92);
// Set background color
$background = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
// Fill with TRN
imagefill($canvas, 0, 0, $background);
imagecolortransparent($canvas, $background);
// Set font
$text_fonts = 'fonts/HarmonyOS_Sans_SC_Bold.ttf'; // font
$color = imagecolorallocate($canvas, 255, 255, 255); // color
$text_size = 7; // size
// Set background picture
$logo_url = 'backs/'.$lang.'.png';
$logo = file_get_contents($logo_url);
$logo_img = imagecreatefromstring($logo);
imagecopyresampled($canvas, $logo_img, 0, 0, 0, 0, 465, 92, imagesx($logo_img), imagesy($logo_img));

// For team
imagettftext($canvas, $text_size, 0, 90, 13, $color, $text_fonts, $team_object["name"]); // name
imagettftext($canvas, $text_size, 0, 100, 30, $color, $text_fonts, $team_object["id"]); // id
imagettftext($canvas, $text_size, 0, 100, 48, $color, $text_fonts, $team_object["rank"]); // rank
imagettftext($canvas, $text_size, 0, 110, 65, $color, $text_fonts, $team_object["wus"]); // wus
imagettftext($canvas, $text_size, 0, 90, 82, $color, $text_fonts, $team_object["score"]); // score

// For donor
imagettftext($canvas, $text_size, 0, 280, 13, $color, $text_fonts, $team_object["name"]); // name
imagettftext($canvas, $text_size, 0, 290, 32, $color, $text_fonts, $team_object["rank"]); // rank
imagettftext($canvas, $text_size, 0, 300, 50, $color, $text_fonts, $team_object["wus"]); // wus
imagettftext($canvas, $text_size, 0, 280, 68, $color, $text_fonts, $team_object["score"]); // score
imagettftext($canvas, $text_size, 0, 310, 82, $color, $text_fonts, $team_object["last"]); // last done

if (!$DEBUG) header("content-type:image/png");
imagepng($canvas);
imagedestroy($canvas);