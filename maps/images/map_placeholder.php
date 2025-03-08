<?php
// Set the content type to image/png
header('Content-Type: image/png');

// Get the map ID from the URL parameter
$mapId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Create a 800x600 image
$image = imagecreatetruecolor(800, 600);

// Define colors
$background = imagecolorallocate($image, 40, 40, 40);
$text = imagecolorallocate($image, 255, 255, 255);
$accent = imagecolorallocate($image, 220, 50, 50);
$grid = imagecolorallocate($image, 60, 60, 60);

// Fill the background
imagefill($image, 0, 0, $background);

// Draw grid lines
for ($i = 0; $i < 800; $i += 50) {
    imageline($image, $i, 0, $i, 600, $grid);
}
for ($i = 0; $i < 600; $i += 50) {
    imageline($image, 0, $i, 800, $i, $grid);
}

// Draw a border
imagerectangle($image, 0, 0, 799, 599, $accent);
imagerectangle($image, 1, 1, 798, 598, $accent);

// Add map name text
$mapName = "Map #" . $mapId;
$font = 5; // Built-in font
$textWidth = imagefontwidth($font) * strlen($mapName);
$textHeight = imagefontheight($font);
$x = (800 - $textWidth) / 2;
$y = 50;
imagestring($image, $font, $x, $y, $mapName, $text);

// Add coordinates in each corner
imagestring($image, 2, 10, 10, "X:" . ($mapId * 100) . " Y:" . ($mapId * 100), $text);
imagestring($image, 2, 650, 10, "X:" . ($mapId * 100 + 100) . " Y:" . ($mapId * 100), $text);
imagestring($image, 2, 10, 570, "X:" . ($mapId * 100) . " Y:" . ($mapId * 100 + 100), $text);
imagestring($image, 2, 650, 570, "X:" . ($mapId * 100 + 100) . " Y:" . ($mapId * 100 + 100), $text);

// Draw a compass in the bottom right
$cx = 750;
$cy = 550;
$r = 30;
imagearc($image, $cx, $cy, $r*2, $r*2, 0, 360, $accent);
imageline($image, $cx, $cy, $cx, $cy - $r, $text); // North
imageline($image, $cx, $cy, $cx + $r, $cy, $text); // East
imageline($image, $cx, $cy, $cx, $cy + $r, $text); // South
imageline($image, $cx, $cy, $cx - $r, $cy, $text); // West
imagestring($image, 2, $cx - 5, $cy - $r - 15, "N", $text);
imagestring($image, 2, $cx + $r + 5, $cy - 5, "E", $text);
imagestring($image, 2, $cx - 5, $cy + $r + 5, "S", $text);
imagestring($image, 2, $cx - $r - 15, $cy - 5, "W", $text);

// Output the image
imagepng($image);
imagedestroy($image);
?>