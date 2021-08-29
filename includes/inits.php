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
// SET THEME COOKIE FOR NO-JS USERS (CUZ IM COOL LIKE DAT)
if (!isset($_COOKIE["theme"])) {
	setcookie("theme", $config['css'][0], time() + (60 * 60 * 24 * 365 )); // 1 year expiry, default to first theme in default.php.
}
if (isset($_GET["theme"])) {
	unset($_COOKIE["theme"]);
	setcookie("theme", htmlspecialchars($_GET["theme"]), time() + (60 * 60 * 24 * 365 ));
}
$current_theme = ''; //prevent some cookie blockers throwing notice errors
if (isset($_COOKIE["theme"])) {
$current_theme = $_COOKIE["theme"];
}

?>