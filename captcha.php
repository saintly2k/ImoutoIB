<?php
session_start();

//
if (!isset($permitted_chars)) { //allow for changing this in config.php i guess
  $permitted_chars = '1234567ACDEFGHJKLMNPRSRUVWXY';
}

function generate_string($input, $strength = 10) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    $random_string = strtolower($random_string);
    return $random_string;
}

$string_length = 6;
$captcha_string = generate_string($permitted_chars, $string_length);

//$fonts = [__dir__ .'/assets/fonts/sfautomaton-regular.ttf', __dir__ .'/assets/fonts/sfautomaton-oblique.ttf', __dir__ .'/assets/fonts/sfautomaton-bold.ttf', __dir__ .'/assets/fonts/sfautomaton-boldoblique.ttf',];
$fonts = [__dir__ .'/assets/fonts/atarist8x16systemfont.ttf', __dir__ .'/assets/fonts/atarist8x16systemfont.ttf'];

$_SESSION['captcha_text'] = $captcha_string;

$captcha_string = strtoupper($captcha_string); //this shit gets converted to lower in the post.php anyways


$captcha_width = 198;
$captcha_height = 50;
$captcha_image = imagecreatetruecolor($captcha_width, $captcha_height);
$captcha_bg_color = imagecolorallocate($captcha_image, 249, 249, 249); //#F9F9F9
imagefill($captcha_image, 0, 0, $captcha_bg_color);

$black = imagecolorallocate($captcha_image, 0, 0, 0);
$white = imagecolorallocate($captcha_image, 255, 255, 255);
$textcolors = [$black, $black];

//annoying lines
for ($i = 0; $i < 30; $i++) {
    imagesetthickness($captcha_image, rand(1, 2));
    imagearc(
        $captcha_image,
        rand(1, 300), // x-coordinate of the center.
        rand(1, 300), // y-coordinate of the center.
        rand(1, 200), // The arc width.
        rand(1, 200), // The arc height.
        rand(1, 300), // The arc start angle, in degrees.
        rand(1, 300), // The arc end angle, in degrees.
        (rand(0, 1) ? $black : $white) // A color identifier.
    );
}

//noise background
$color1 = imagecolorallocate($captcha_image,0,0,0);
$color2 = imagecolorallocate($captcha_image,0,0,0);
for($i = 0; $i < $captcha_width; $i++) {
    if ($i % 2 == 0) {
        continue;
      }
    for($j = 0; $j < $captcha_height; $j++) {
      if ($j % 3 == 0) {
        continue;
      }
        if (mt_rand(0,1) == 1) imagesetpixel($captcha_image, $i, $j, $color2);
    }
}

//text
for($i = 0; $i < $string_length; $i++) {
  $letter_space = 170/$string_length;
  $initial = 15; 
  imagettftext($captcha_image, 30, rand(-15, 15), $initial + $i*$letter_space, rand(35, 45), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
}

//noise foreground
$color1 = imagecolorallocate($captcha_image, 200, 240, 242);
$color2 = imagecolorallocate($captcha_image,220,220,220);
for($i = 0; $i < $captcha_width; $i++) {
    if ($i % 2 == 0) {
        continue;
      }
    for($j = 0; $j < $captcha_height; $j++) {
      if ($j % 3 == 0) {
        continue;
      }
        if (mt_rand(0,1) == 1) imagesetpixel($captcha_image, $i, $j, $color2);
    }
}


header('Content-type: image/png');
imagepng($captcha_image);
imagedestroy($captcha_image);

?>