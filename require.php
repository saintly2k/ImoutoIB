<?php 

// INSTALL LOCATION
$path = dirname(__FILE__);

// CONFIGURATIONS

require $path . '/includes/default.php'; //sets defaults
require $path . '/includes/custom.php'; // only change this, it will replace the default initialized settings.
require $path . '/includes/inits.php'; //defines possibly unused variables
require $path . '/includes/functions.php'; //defines functions

//per board config main.php
if (isset($_GET["board"]) && $_GET["board"] != '') {
	if (file_exists(__dir__ . '/' . $database_folder . '/boards/' . htmlspecialchars($_GET["board"]) . '/config.php')) {
		@include __dir__ . '/' . $database_folder . '/boards/' . htmlspecialchars($_GET["board"]) . '/config.php';
	}
}
//per board config post.php
if (isset($_POST["board"]) && $_POST["board"] != '') {
	if (file_exists(__dir__ . '/' . $database_folder . '/boards/' . htmlspecialchars($_POST["board"]) . '/config.php')) {
		@include __dir__ . '/' . $database_folder . '/boards/' . htmlspecialchars($_POST["board"]) . '/config.php';
	}
}

// DATABASE
require $path . '/database/boards.php'; //boardlist
require $path . '/database/users.php'; //moderators

?>