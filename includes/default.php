<?php

$config['debug'] = true; // enables error logging, soykaf code, you'll want this enabled until you're sure everything works as should lol
$config['generated_in'] = true; //enables time spend generating in page footer
$prefix_folder = ''; // leave empty if in directory root, example 'ib' if in /ib/
$database_folder = 'database'; //no need to change this really
$main_file = 'main.php'; //leave empty if using handlers like apache to hide filename example: /ib/?boards= vs /ib/main.php?boards=
$post_file = 'post.php'; //i cant imagine any reason to change this, but i suppose it could be in a different folder if you want to

$site_name = 'ImoutoIB';

$captcha_required = false;

$time_method = 'since'; //(iso:iso8601 unix:numberstamp since:howlongsince human:humanreadable
$time_method_hover = "human"; //unix will always be in data-timestamp for potential js use


$config['display_banner'] = true;
//$config['banner'][] = true;

// STYLESHEETS
$config['css'][] = 'Yotsuba B'; //mandatory
$config['css'][] = 'Yotsuba';
$config['css'][] = 'Tomorrow';

$default_theme = 'Yotsuba';

// JAVASCRIPTS
$config['js'][] = 'main.js'; //mandatory
//$config['js'][] = 'extensions.js';

//POST SETTINGS
$config['post_body_min'] = 10; //minimum characters, 0 to allow
$config['post_body_max'] = 4000; //maximum characters

$config['reply_body_min'] = false; //allow replies with only images
$config['reply_body_max'] = 4000; //maximum characters




?>