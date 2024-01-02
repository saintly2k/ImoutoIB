<?php
session_start();

if (!isset($permitted_chars)) { //allow for changing this in config.php i guess
    $permitted_chars = '1234567ACDEFGHJKLMNPRSRUVWXY';
}

function generate_string(string $input, int $strength = 10): string
{
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_character = $input[random_int(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return strtolower($random_string);
}

$string_length = 6;
$captcha_string = generate_string($permitted_chars, $string_length);

$fonts = [__DIR__ . '/assets/fonts/atarist8x16systemfont.ttf'];

$_SESSION['captcha_text'] = $captcha_string;

$captcha_string = strtoupper($captcha_string);

$captcha_width = 198;
$captcha_height = 50;
$captcha_image = imagecreatetruecolor($captcha_width, $captcha_height);
$captcha_bg_color = imagecolorallocate($captcha_image, 249, 249, 249);
imagefill($captcha_image, 0, 0, $captcha_bg_color);

$black = imagecolorallocate($captcha_image, 0, 0, 0);
$textcolors = [$black, $black];

// Annoying lines
for ($i = 0; $i < 30; $i++) {
    imagesetthickness($captcha_image, random_int(1, 2));
    imagearc(
        $captcha_image,
        random_int(1, $captcha_width),
        random_int(1, $captcha_height),
        random_int(1, $captcha_width),
        random_int(1, $captcha_height),
        random_int(1, 300),
        random_int(1, 300),
        (random_int(0, 1) ? $black : $captcha_bg_color)
    );
}

// Noise background
for ($i = 0; $i < $captcha_width; $i++) {
    if ($i % 2 === 0) {
        continue;
    }
    for ($j = 0; $j < $captcha_height; $j++) {
        if ($j % 3 === 0) {
            continue;
        }
        if (random_int(0, 1) === 1) {
            imagesetpixel($captcha_image, $i, $j, $black);
        }
    }
}

// Text
for ($i = 0; $i < $string_length; $i++) {
    $letter_space = 170 / $string_length;
    $initial = 15;
    imagettftext(
        $captcha_image,
        30,
        random_int(-15, 15),
        $initial + $i * $letter_space,
        random_int(35, 45),
        $textcolors[random_int(0, 1)],
        $fonts[array_rand($fonts)],
        $captcha_string[$i]
    );
}

// Noise foreground
$color1 = imagecolorallocate($captcha_image, 200, 240, 242);
$color2 = imagecolorallocate($captcha_image, 220, 220, 220);
for ($i = 0; $i < $captcha_width; $i++) {
    if ($i % 2 === 0) {
        continue;
    }
    for ($j = 0; $j < $captcha_height; $j++) {
        if ($j % 3 === 0) {
            continue;
        }
        if (random_int(0, 1) === 1) {
            imagesetpixel($captcha_image, $i, $j, $color2);
        }
    }
}

header('Content-type: image/png');
imagepng($captcha_image);
imagedestroy($captcha_image);
