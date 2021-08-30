<?php

$config['debug'] = true; // enables error logging, soykaf code, you'll want this enabled until you're sure everything works as should lol
$config['generated_in'] = true; //enables time spend generating in page footer
$prefix_folder = ''; // leave empty if in directory root, example 'ib' if in /ib/
$main_file = 'main.php'; //leave empty if using handlers like apache to hide filename example: /ib/?boards= vs /ib/main.php?boards=
$post_file = 'post.php'; //i cant imagine any reason to change this, but i suppose it could be in a different folder if you want to

$site_name = 'ImoutoIB';
$domain = ''; //MUST BE SET FOR COOKIES
$captcha_required = false;

$secure_hash = "SQp3FaEgyMyHe3=Zc!-vS%ya6W!JAt+9fqwdbGk&ev!hbG!nSMgN_KUbLrmRpCQy"; //Will be used to hash your post passwords. You should change this.

$time_method = 'since'; //(iso:iso8601 unix:numberstamp since:howlongsince human:humanreadable
$time_method_hover = "human"; //unix will always be in data-timestamp for potential js use

$forced_anon = false;
$default_name = 'Anonymous';

$disable_email = false; //Disables the email field. Checkboxes will still work.
$show_email = true; //shows email in post name

$config['display_banner'] = true;

$post_buttons = true; //adds a no-JS friendly post button on each post for delete/report using html5 details


// STYLESHEETS
$config['css'][] = 'Yotsuba B'; //mandatory, foundation for all other styles.
$config['css'][] = 'Yotsuba';
$config['css'][] = 'Burichan';
$config['css'][] = 'Futaba';

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


//DATABASE CONFIGURATION
$config['db']['type'] = 'flat'; // flat, mysql
// Flat file (No Database)
$database_folder = 'database';

//(MySQL) -- Not implemented yet.
//$config['db']['server'] = 'localhost';
//$config['db']['username'] = 'username';
//$config['db']['password'] = 'password';



?>