<?php 


function error($error) {
	require 'default.php'; //sets defaults
	require 'inits.php'; //defines possibly unused variables
	require 'custom.php'; // only change this, it will replace the default initialized settings.
	echo '<html data-stylesheet="'. $current_theme .'">';
	echo '<head>';
	echo '<title>Error!</title>';
	echo '<link rel="icon" type="image/png" href="' . $prefix_folder  . '/assets/img/favicon.png"/>';
	foreach ($config['css'] as $css) {
		echo '<link rel="stylesheet" type="text/css" href="' . $prefix_folder . '/assets/css/' . $css . '.css">';
	}
	foreach ($config['js'] as $js) {
		echo '<script type="text/javascript" src="' . $prefix_folder . '/assets/js/' . $js . '"></script>';
	}

	echo '</head>';
	echo '<body current_page="message">';
	echo '<div class="message">Gomen nasai... An error occurred: ' . $error . '</div>';
	echo '</body>';
	echo '</html>';

	exit();
}

function dir_is_empty($dir) {
	$dir = opendir($dir);
	while (false !== ($entry = readdir($dir))) {
		if ($entry != "." && $entry != "..") {
		closedir($dir); // <= I added this
		return FALSE;
	}
	}
	closedir($dir);
	return TRUE;
}

function timeago($unix) { //blatantly taken and modified from https://phppot.com/php/php-time-ago-function/
	   $timestamp = $unix;	
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");
	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}
			$diff = round($diff);
			if ($diff > 1) {
				return $diff . " " . $strTime[$i] . "s ago";
			} else {
				return $diff . ' ' . $strTime[$i] . ' ago';
			}
	   }
	}

function timeConvert($unix, $method = false) {
	if ($method === 'iso') {
		return date('Y-m-d\TH:i:sO', $unix);
	}
	elseif ($method === 'human') {
		return date('jS F H:i (D), Y', $unix);
	}
	elseif ($method === 'since') {
		return timeago($unix);
	}
	elseif ($method === false) {
		return $unix;
	}

}

function PostSuccess($redirect = false, $auto = true) {

	//TO DO: redirect to $post_board+thread parameter
	require 'default.php'; //sets defaults
	require 'inits.php'; //defines possibly unused variables
	require 'custom.php'; // only change this, it will replace the default initialized settings.

	echo '<html data-stylesheet="'. $current_theme .'">';
	echo '<head>';
	echo '<title>Success!</title>';
	echo '<link rel="icon" type="image/png" href="' . $prefix_folder  . '/assets/img/favicon.png"/>';
	foreach ($config['css'] as $css) {
		echo '<link rel="stylesheet" type="text/css" href="' . $prefix_folder . '/assets/css/' . $css . '.css">';
	}
	foreach ($config['js'] as $js) {
		echo '<script type="text/javascript" src="' . $prefix_folder . '/assets/js/' . $js . '"></script>';
	}

	if (($redirect !== false) && $auto == true) {
	echo '<meta http-equiv="refresh" content="2; URL=' . $redirect . '" />';
	}

	echo '</head>';
	echo '<body current_page="message">';
	echo '<div class="message">Sugoi!! Post success!!</div>';
	echo '</body>';
	echo '</html>';

	exit();
}

?>