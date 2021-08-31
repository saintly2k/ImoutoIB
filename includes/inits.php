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
$frontpage_active = 0;
$reply = '';
$reply_ip ='';
$op = '';
$op_ip = '';

if ($config['generated_in'] === true) {
	$start_time = microtime(true);
}

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
if (!isset($_COOKIE["theme"])) {
	setcookie("theme", $default_theme, 0,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
}
if (isset($_GET["theme"])) {
	unset($_COOKIE["theme"]);
	setcookie("theme", htmlspecialchars($_GET["theme"]), 0, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
}
	$current_theme = $default_theme;
if (isset($_COOKIE["theme"])) {
	$current_theme = $_COOKIE["theme"];
}

if (isset(($_POST['password'])) && (($_POST['password']) !== '')) {
	$post_password = crypt(htmlspecialchars($_POST['password']), $secure_hash);
} else {
	$post_password = crypt((rand() + time()),$secure_hash); //sets a random hashed password
}

?>