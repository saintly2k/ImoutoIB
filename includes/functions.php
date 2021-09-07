<?php 


function error($error) {
	require 'default.php'; //sets defaults
	require 'inits.php'; //defines possibly unused variables
	require 'custom.php'; // only change this, it will replace the default initialized settings.

	require __dir__ . '/../' . $database_folder . '/boards.php';

	echo '<html data-stylesheet="'. $current_theme .'">';
	echo '<head>';
	echo '<title>Error!</title>';

	echo '<script>';
	if (isset($_POST['board'])) {
		if ($config['boards'][$_POST['board']]['type'] == 'txt') {
			echo 'var board_type = "txt";';
		} else {
			echo 'var board_type = "img";';
		}
	} else {
		echo 'var board_type = "img";';
	}
	echo '</script>';

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

function getExt($name) {
	$n = strrpos($name,".");
	return ($n===false) ? "" : substr($name,$n+1);
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
	elseif ($method === 'human') { //universally readable without DMY or MDY standard
		return date('d F Y (D) H:i:s', $unix);
	}
	elseif ($method === 'compact') {
		return date('d/m/y (D) H:i:s', $unix);
	}
	elseif ($method === 'since') {
		return timeago($unix);
	}
	elseif ($method === 'compactsince') {
		return date('d/m/y (D) H:i:s', $unix) . ' (' . timeago($unix) . ')';
	}
	elseif ($method === false) {
		return $unix;
	}

}

function formatBytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1000 && $i < 4; $i++)
        $size /= 1000;   
    if($units[$i] == ' B' || $units[$i] == ' KB')
        return round($size, 0).$units[$i];
    else
        return round($size, 1).$units[$i];
}

function PostSuccess($redirect = false, $auto = true) {

	//TO DO: redirect to $post_board+thread parameter
	require 'default.php'; //sets defaults
	require 'inits.php'; //defines possibly unused variables
	require 'custom.php'; // only change this, it will replace the default initialized settings.
	require __dir__ . '/../' . $database_folder . '/boards.php';

	echo '<html data-stylesheet="'. $current_theme .'">';
	echo '<head>';

	echo '<script>';
	if ($config['boards'][$_POST['board']]['type'] == 'txt') {
		echo 'var board_type = "txt";';
	} else {
		echo 'var board_type = "img";';
	}
	echo '</script>';

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

function UpdateOP($database_folder, $board, $thread, $page, $replies, $bumped, $uniqueids) {
	$info_ = '<?php ';
	$info_ .= '$info_page' . '=' . '""' . ';';
	$info_ .= '$info_replies' . '=' . $replies . ';';
	$info_ .= '$info_bumped' . '=' . $bumped . ';';
	$info_ .= '$info_uniqueids' . '=' . $uniqueids . ';';
	$info_ .= '?>';

	file_put_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . 'info.php', $info_);

}

function DeletePost($database_folder, $uploads_folder, $board, $thread, $post, $fileonly = false) {

	//wip
	//add moderator checks to bypass pw check later

	if ($thread == $post) { // IF THREAD
		//IF THREAD EXISTS
		if (!file_exists(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread)) {
			error('Deletion logic set to thread, but thread does not exist.');
		}
		include __dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/OP.php';

		//CHECK PASSWORD
		if (md5(phpClean($_POST['password'])) != $op_password) {
			error('Wrong password...');
		}
		if ($fileonly == true) {
			if ($op_file[0][0] == '' || $op_file[0][0] == 'deleted') {
				error ('Thread has no file.');
			}

			if ($op_file[0][0] = 'image') {
				//delete thumb
				//delete file
			}

			//change OP info to file00 = deleted (fileget, change, fileput)
			error('rest not coded');
		}
		//else
			//DELETE ALL FILES IN ALL REPLIES OF THREAD (for each #.php, read, if exist delete)
			//delete whole folder with replies
		}

	if ($thread != $post) { // IF REPLY
		if (!file_exists(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php')) {
			error('Deletion logic set to reply, but specified reply does not exist in this thread.');
		}
		include __dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php';
		if (md5(phpClean($_POST['password'])) != $reply_password) {
			error('Wrong password...');
		} else {
			error('Correct password. Rest not coded yet.'); //just testing
		}

		//if fileonly
			//find file, delete file, open postinfo for thread/post, set to file deleted
		//else
			//delete file in array if exists, delete reply

	}

	error('This shouldnt happen...');
}




//https://stackoverflow.com/a/18568222
function getTotalSize($dir)
{
    $dir = rtrim(str_replace('\\', '/', $dir), '/');

    if (is_dir($dir) === true) {
        $totalSize = 0;
        $os        = strtoupper(substr(PHP_OS, 0, 3));
        // If on a Unix Host (Linux, Mac OS)
        if ($os !== 'WIN') {
            $io = popen('/usr/bin/du -sb ' . $dir, 'r');
            if ($io !== false) {
                $totalSize = intval(fgets($io, 80));
                pclose($io);
                return $totalSize;
            }
        }
        // If on a Windows Host (WIN32, WINNT, Windows)
        if ($os === 'WIN' && extension_loaded('com_dotnet')) {
            $obj = new \COM('scripting.filesystemobject');
            if (is_object($obj)) {
                $ref       = $obj->getfolder($dir);
                $totalSize = $ref->size;
                $obj       = null;
                return $totalSize;
            }
        }
        // If System calls did't work, use slower PHP 5
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }
        return $totalSize;
    } else if (is_file($dir) === true) {
        return filesize($dir);
    }
}

function phpClean($string, $special = true) {
	if ($special == true) {
		$string = htmlspecialchars($string);
	}
	$string = preg_replace('/\\\\/','&#92;', $string); //replace backslash
	$string = preg_replace('/\$/','&#36;', $string); //replace $
	return $string;
}

?>