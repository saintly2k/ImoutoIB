<?php

$config['debug'] = true; // enables error logging, soykaf code, you'll want this enabled until you're sure everything works as should lol
$config['generated_in'] = true; //enables time spend generating in page footer
$prefix_folder = ''; // leave empty if in directory root, example 'ib' if in /ib/
$main_file = 'main.php'; //leave empty if using handlers like apache to hide filename example: /ib/?boards= vs /ib/main.php?boards=
$post_file = 'post.php'; //i cant imagine any reason to change this, but i suppose it could be in a different folder if you want to

$site_name = 'ImoutoIB';
$site_slogan = 'As if it were written by a literal child.';
$domain = '3dpd.moe'; //MUST BE SET FOR COOKIES
$captcha_required = true; //there is no anti-flood setting so you really should...
$boardlist = "title"; //"url" for a shortening boardlist.

$secure_hash = "SQp3FaEgyMyHe3=Zc!-vS%ya6W!JAt+9fqwdbGk&ev!hbG!nSMgN_KUbLrmRpCQy"; //Will be used to hash your post passwords. You should change this.

$time_method = 'compactsince'; //(iso:iso8601 unix:numberstamp since:howlongsince human:easy readable without MDY DMY confusion, compact uses DMY because MDY is a sin.
$time_method_hover = "human"; //iso, human, compact, since, compactsince

$forced_anon = false;
$default_name = 'Anonymous';

$display_id = true;

$catalog_enable = true;

$disable_email = false; //Disables the email field. Checkboxes will still work.
$show_email = true; //shows email in post name

$recent_replies = 5; //how many recent replies to show on index
$threads_page = 10; //how many threads per page

$max_filesize = 1024*1000*8; //default 8mb
$max_filename = 28; //longer than this will be trimmed visually, absolute max is set to 512 at which point it is trimmed when saved as well. you can see untrimmed below 512 by hovering over name

$thumb_method =  'GD'; //i probably wont implement any others
$thumb_ext = '.jpg'; //add support for transparent png, would use webp if apple stops shilling HEIC already and enables webp+webm support
$thumb_res_op =  250; //250x250
$thumb_res_reply =  125; //125x125
$thumb_spoiler = 'spoiler.png';
$thumb_audio = 'audio.png';
$thumb_video = 'video.png';
$thumb_download = 'download.png';
$thumb_deleted = 'deleted.png';
$spoiler_enabled = true;

$icon_locked = 'lock.png';
$icon_autosage = 'anchor.png';
$icon_sticky = 'pin.png';

$thumbnail_bg_red = 255;  //rgb - yotsuba default, 238,242,255 for yotsuba b.
$thumbnail_bg_green = 255;  //can probably make a toggle for transparent png thumbnails
$thumbnail_bg_blue = 238; //for those who dont mind bandwidth

$original_filename = true;

$image_max_res = 9999; //9999x9999

$uploads_folder = 'uploads';
$filename_method = 'unix'; //unix = Time()+ 3 random digits 000-999 - uniqid for a random generation+time
$allow_files = true; //false for text only board

$config['allowed_ext']['img'][] = '.jpg';
$config['allowed_ext']['img'][] = '.jpeg';
$config['allowed_ext']['img'][] = '.gif';
$config['allowed_ext']['img'][] = '.png';
$config['allowed_ext']['img'][] = '.webp';

$config['allowed_ext']['audio'][] = '.mp3';
$config['allowed_ext']['audio'][] = '.wav';
$config['allowed_ext']['audio'][] = '.ogg';

$config['allowed_ext']['video'][] = '.mp4';
$config['allowed_ext']['video'][] = '.webm';
$config['allowed_ext']['video'][] = '.ogv';
$config['allowed_ext']['video'][] = '.avi';
$config['allowed_ext']['video'][] = '.mkv';

$config['allowed_ext']['downloads'][] = '.pdf';
$config['allowed_ext']['downloads'][] = '.txt';
$config['allowed_ext']['downloads'][] = '.zip';
$config['allowed_ext']['downloads'][] = '.rar';
$config['allowed_ext']['downloads'][] = '.7z';



$config['display_banner'] = true;

$post_buttons = true; //adds a no-JS friendly post button on each post for delete/report using html5 details


// STYLESHEETS
$config['css'][] = 'Yotsuba B'; //mandatory, foundation for all other styles.
$config['css'][] = 'Yotsuba';
$config['css'][] = 'Burichan';
$config['css'][] = 'Futaba';

$config['css'][] = 'Cyanide';

$config['css'][] = 'Tomorrow';
$config['css'][] = 'Late';
$config['css'][] = 'Kind';


//text styles
$config['css'][] = 'Kareha';
$config['css'][] = 'Sankarea';

//selectable styles on text boards, you may add IB styles if you want?
$config['css_text'][] = 'Kareha';
$config['css_text'][] = 'Sankarea';


$default_theme = 'Yotsuba';
$default_text_theme = 'Kareha';

// JAVASCRIPTS
$config['js'][] = 'main.js'; //mandatory
//$config['js'][] = 'extensions.js';

//POST SETTINGS
$config['post_body_min'] = 10; //minimum characters, 0 to allow
$config['post_body_max'] = 4000; //maximum characters

$config['reply_file_only'] = true; //allows zero text if contains file
$config['reply_body_min'] = 10; //minimum characters
$config['reply_body_max'] = 4000; //maximum characters

$config['max_lines'] = 40;

$config['wordfilters'][] = array('/little sister/i', 'imouto'); //regex


//MODERATOR CONFIGURATION
$config['mod']['ip'] = "40"; //see IP, or in this case hashed IP similarly to IDs, even if ID is disabled for visitors.
$config['mod']['ban'] = "40";
$config['mod']['reports'] = "10"; //dismiss reports
$config['mod']['global_reports'] = "40"; //dismiss global reports
$config['mod']['thread_sticky'] = "40";
$config['mod']['thread_lock'] = "40";
$config['mod']['thread_autosage'] = "40";
$config['mod']['post_edit'] = "40";
$config['mod']['post_delete'] = "10";
$config['mod']['post_in_locked'] = "40";

$config['mod']['edit_user'] = "9001"; //create,edit,delete
$config['mod']['mod_log'] = "40"; //look at modlog

$config['footer'] = []; //Display name, URL
$config['footer'][] = array('Home', '?page=');
$config['footer'][] = array('News', '#');
$config['footer'][] = array('Rules', '?page=rules');
$config['footer'][] = array('Help', '#');
$config['footer'][] = array('About', '#');
$config['footer'][] = array('Contact', '#');
$config['footer'][] = array('Legal', '#');
$config['footer'][] = array('Manage', 'mod.php');


//DATABASE CONFIGURATION
$config['db']['type'] = 'flat'; // flat, mysql
// Flat file (No Database)
$database_folder = 'database';

//(MySQL) -- Not implemented yet.
//$config['db']['server'] = 'localhost';
//$config['db']['username'] = 'username';
//$config['db']['password'] = 'password';



?>