<?php 

// INSTALL LOCATION
$path = dirname(__FILE__);

// CONFIGURATIONS

require $path . '/includes/default.php'; //sets defaults
require $path . '/includes/custom.php'; // only change this, it will replace the default initialized settings.
require $path . '/includes/inits.php'; //defines possibly unused variables
require $path . '/includes/functions.php'; //defines functions
//require $path . '/includes/filehandler.php'; //uploads and image conversions
	// require per board setting?

// DATABASE
require $path . '/database/boards.php'; //boardlist
require $path . '/database/users.php'; //moderators

?>