<?php 

if ($config['debug'] === true) { 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$title = '$title';
$current_page = '$current_page';
$current_board = '';
$current_thread = '';
$board_title = '$board_title';
$board_description = '$board_title';
$post_number_op = '';

$reply = '';
$reply_ip ='';
$op = '';
$op_ip = '';

$isSpoiler_ = '';
$reply_file = '';
$op_file = '';
$new_thumbname = '';
$thmb_res = '';

$info_locked = 0;
$info_sticky = 0;
$info_autosage = 0;

$frontpage_uniqueids = 0;
$frontpage_active = 0;

$pages = '';

$logged_in = false;
$logged_in_user = false;
$mod_level = false;
$changed_password = false;
$user_created = false;
$user_edited = false;
$user_deleted = false;
$ban_removed = false;
$ban_created = false;
$warning_created = false;

$is_banned = false;

$post_locked = false;
$post_sticky = false;
$post_autosage = false;


if ($config['generated_in'] === true) {
	$start_time = microtime(true);
}

$prevlink = '';
$all_pages = '';
$nextlink = '';

//params
if (!isset($_GET["thread"])) {
	$_GET["thread"] = '';
}
if (!isset($_GET["page"])) {
	$_GET["page"] = '';
}
if (!isset($_GET["board"])) {
	$_GET["board"] = '';
}

if ($prefix_folder == '') {
	$cookie_location = '/';
} else {
	$cookie_location = $prefix_folder;
}


// SET THEME COOKIE FOR NO-JS USERS (CUZ IM COOL LIKE DAT)
if (isset($_GET["board"]) && htmlspecialchars($_GET["board"]) != '') {
	if ($config["boards"][htmlspecialchars($_GET["board"])]["type"] == "txt") {
		if (!isset($_COOKIE["text_theme"])) {
			setcookie("text_theme", $default_theme, 0,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		}
		if (isset($_GET["theme"])) {
			setcookie("text_theme", htmlspecialchars($_GET["theme"]), 0, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		}
			$current_theme = $default_theme;
		if (isset($_COOKIE["text_theme"])) {
			$current_theme = $_COOKIE["text_theme"];
		}

	} else {
		if (!isset($_COOKIE["theme"])) {
			setcookie("theme", $default_theme, 0,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		}
		if (isset($_GET["theme"])) {
			setcookie("theme", htmlspecialchars($_GET["theme"]), 0, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		}
			$current_theme = $default_theme;
		if (isset($_COOKIE["theme"])) {
			$current_theme = $_COOKIE["theme"];
		}
	}
} else {
	if (!isset($_COOKIE["theme"])) {
		setcookie("theme", $default_theme, 0,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	}
	if (isset($_GET["theme"])) {
		setcookie("theme", htmlspecialchars($_GET["theme"]), 0, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	}
		$current_theme = $default_theme;
	if (isset($_COOKIE["theme"])) {
		$current_theme = $_COOKIE["theme"];
	}
}



if (isset(($_POST['password'])) && (($_POST['password']) !== '')) {
	$post_password = crypt(htmlspecialchars($_POST['password']), $secure_hash);
} else {
	$post_password = crypt((rand() + time()),$secure_hash); //sets a random hashed password
}

?>