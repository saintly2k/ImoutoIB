<?php 

// INSTALL LOCATION
$path = dirname(__FILE__);

//is API call?
if (isset($_GET['api'])) {
	$api = true;
} else {
	$api = false;
}

// CONFIGURATIONS
require $path . '/includes/default.php'; //sets defaults

// DATABASE
require $path . '/database/boards.php'; //boardlist

//if text board defaults:
if (isset($_GET["board"]) && $_GET["board"] != '') {
	if ($config['boards'][htmlspecialchars($_GET["board"])]['type'] == 'txt') {
		$allow_files = false;
		$default_theme = $default_text_theme;
	}
}

if (isset($_POST["board"]) && $_POST["board"] != '') {
	if ($config['boards'][htmlspecialchars($_POST["board"])]['type'] == 'txt') {
		$allow_files = false;
		$default_theme = $default_text_theme;
	}
}

require $path . '/includes/custom.php'; // only change this, it will replace the default initialized settings.
require $path . '/includes/inits.php'; //defines possibly unused variables
require $path . '/includes/functions.php'; //defines functions



//per board config main.php
if (isset($_GET["board"]) && $_GET["board"] != '') {
	if (file_exists($path . '/' . $database_folder . '/boards/' . htmlspecialchars($_GET["board"]) . '/config.php')) {
		@include $path . '/' . $database_folder . '/boards/' . htmlspecialchars($_GET["board"]) . '/config.php';
	}
}
//per board config post.php
if (isset($_POST["board"]) && $_POST["board"] != '') {
	if (file_exists($path . '/' . $database_folder . '/boards/' . htmlspecialchars($_POST["board"]) . '/config.php')) {
		@include $path . '/' . $database_folder . '/boards/' . htmlspecialchars($_POST["board"]) . '/config.php';
	}
}

//is moderator?
if (isset($_COOKIE['mod_user']) && isset($_COOKIE['mod_session'])) {
	if ($_COOKIE['mod_user'] == "") {
		error('No username given.');
	}
	if ($_COOKIE['mod_user'] == "counter" || ctype_alnum($_COOKIE['mod_user']) != true) {
		error('Invalid Username.');
	}
	if (!file_exists($path . '/' . $database_folder . '/users/' . $_COOKIE['mod_user'] . '.php')) {
		error('User doesn\'t exist.');
	}

	include $path . '/' . $database_folder . '/users/' . $_COOKIE['mod_user'] . '.php';

	if ($_COOKIE['mod_session'] != $user_session) {
		setcookie("mod_user", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		setcookie("mod_session", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		error('Invalid or expired cookie session');
	} else {
		$logged_in = true;
		$mod_level = $user_mod_level;
		$logged_in_user = $username;
	}

	if (($user_remember + 86400) < time()) { //1day if not remember me, otherwise using the +30days from remember time for 31days total
		setcookie("mod_user", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		setcookie("mod_session", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
		$logged_in = false;
	}

}



?>