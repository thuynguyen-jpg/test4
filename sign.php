<?php
error_reporting(0);
ini_set('display_errors', 0);

$bgPath  = __DIR__ . "/img/sign.jpg";
$charDir = __DIR__ . "/chars/";

if (!file_exists($bgPath)) exit;

$bg = imagecreatefromjpeg($bgPath);
$W  = imagesx($bg);
$H  = imagesy($bg);

$lineY = [120, 200, 280, 360];
$letterSpacing = 40;

// Minimal special char mapping (add more if needed)
$charMap = [" "=>null];

for ($i=1; $i<=4; $i++) {
    $line = isset($_GET["l$i"]) ? strtoupper($_GET["l$i"]) : "";
    $chars = preg_split('//u', $line, -1, PREG_SPLIT_NO_EMPTY);

    // Horizontal centering
    $totalWidth = count($chars) * $letterSpacing;
    $x = ($W - $totalWidth)/2;

    foreach ($chars as $ch) {
        $tileFile = $charDir . $ch . ".png";
        if (file_exists($tileFile)) {
            $tile = imagecreatefrompng($tileFile);
            imagealphablending($tile,true);
            imagesavealpha($tile,true);
            imagecopy($bg, $tile, $x, $lineY[$i-1],0,0,imagesx($tile),imagesy($tile));
            $x += imagesx($tile) + $letterSpacing;
            imagedestroy($tile);
        } else {
            $x += $letterSpacing;
        }
    }
}

header("Content-Type: image/png");
imagepng($bg);
imagedestroy($bg);
exit;
