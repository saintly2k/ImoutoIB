<?php 


function error($error, $success = false) {
	require 'default.php'; //sets defaults
	require 'inits.php'; //defines possibly unused variables
	require 'custom.php'; // only change this, it will replace the default initialized settings.

	require __dir__ . '/../' . $database_folder . '/boards.php';

	echo '<html data-stylesheet="'. $current_theme .'">';
	echo '<head>';
	if ($success == false) {
		echo '<title>Error!</title>';
	} else {
		echo '<title>Success!</title>';
	}
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
	if ($success == true) {
	echo '<div class="message">' . $error . '</div>';
	} else {
	echo '<div class="message">Gomen nasai... ' . $error . '</div>';		
	}
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

function timeuntil($unix) { //blatantly taken and modified from https://phppot.com/php/php-time-ago-function/
	   $timestamp = $unix;	
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");
	   $currentTime = time();
	   if($currentTime <= $timestamp) {
			$diff     = $timestamp - time();
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}
			$diff = round($diff);
			if ($diff > 1) {
				return $diff . " " . $strTime[$i] . "s";
			} else {
				return $diff . ' ' . $strTime[$i];
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

function PostSuccess($redirect = false, $auto = true, $time= false) {

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
	echo '<div class="footer" style="position:absolute;bottom:20;width:99%;">';
	if ($time != false) {
		$end_time = microtime(true);
		$generation_time = round($end_time - $time, 5);
		echo '<p class="small">Post generated in ' . $generation_time . ' seconds.</p>';
	}
	echo '</div>';
	echo '</html>';

	exit();
}

function UpdateOP($database_folder, $board, $thread, $page, $replies, $bumped, $uniqueids, $sticky = false, $locked = false, $autosage = false) {
	$info_ = '<?php ';
	$info_ .= '$info_page' . '=' . '""' . ';';
	$info_ .= '$info_replies' . '=' . $replies . ';';
	$info_ .= '$info_bumped' . '=' . $bumped . ';';
	$info_ .= '$info_uniqueids' . '=' . $uniqueids . ';';
	$info_ .= '$info_sticky' . '=' . $sticky . ';';
	$info_ .= '$info_locked' . '=' . $locked . ';';
	$info_ .= '$info_autosage' . '=' . $autosage . ';';
	$info_ .= '?>';

	file_put_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . 'info.php', $info_);

	//activate updateBumps.php?
}

function UpdateRecents($database_folder, $board, $thread, $recent_replies = 5) {
	//update recents.php in thread to sort replies, use main.php to read this from now on

	//FIND REPLIES
		$replies_full = [];
		$replies_full = glob(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . "/*");
	//SORTING
		$replies = [];
		foreach ($replies_full as $reply) {
			if (is_numeric(basename($reply, '.php'))) {
				$replies[] = basename($reply, '.php');
			}
		}
	sort($replies);
	$omitted = '';
	//take only the most 
	if (count($replies) > $recent_replies) {
		$omitted = count($replies) - $recent_replies;
		$replies = array_slice($replies, count($replies) - $recent_replies);
	}
	//ok now we have x amount of most recent replies
	//lets add them to a file, main.php will read it and show those only instead of checking every file every view.

	//organize the file
	$recents_ = '<?php ';
	$recents_ .= '$recents = []; ';
	foreach ($replies as $reply) {
		$recents_ .= '$recents[] = "' . $reply . '";';
	}
	$recents_ .= '$replies_omitted = "'. $omitted . '";';
	$recents_ .= ' ?>';

	//fileput recents in thread folder
	file_put_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/recents.php' , $recents_);


}

function UpdateThreads($database_folder, $board, $thread) {

	//FIND THREADS
	$threads_full = [];
	$threads_full = glob(__dir__ . '/../' . $database_folder . '/boards/' . $board . "/*", GLOB_ONLYDIR);
	
	//SORTING
	foreach ($threads_full as $key => $thread_) {
		$threadz= basename($thread_);
		if (!file_exists(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . basename($thread_) . '/bumped.php')) {
			$bumped = basename($thread_);
		}
		if (file_exists(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . basename($thread_) . '/bumped.php')) {
			$bumped = file_get_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . basename($thread_) . '/bumped.php');
		}
		$sticky = file_get_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . basename($thread_) . '/info.php');
		$sticky = preg_match('/^.+info_sticky=1/i', $sticky);

		$threads[$key] = [];
		$threads[$key]['id'] = $threadz;
		$threads[$key]['bumped'] = $bumped;
		$threads[$key]['sticky'] = $sticky;
	}
	$keys_ = array_column($threads, 'bumped');
	array_multisort($keys_, SORT_DESC, $threads);

	//organize the file
	$threads_ = '<?php ';
	$threads_ .= '$threads = []; ';
	foreach ($threads as $key => $value) {
		$threads_ .= '$threads["'.$key.'"]["id"] = "' . $threads[$key]['id'] . '";';
		$threads_ .= '$threads["'.$key.'"]["bumped"] = "' . $threads[$key]['bumped'] . '";';
		$threads_ .= '$threads["'.$key.'"]["sticky"] = "' . $threads[$key]['sticky'] . '";';
	}
	$threads_ .= ' ?>';

	//fileput recents in thread folder
	file_put_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/threads.php' , $threads_);
}

function ReportCounter($database_folder, $type = 'normal') {
	$counter = 0;

	if ($type == 'normal') { //normal reports
		$report_boards = glob(__dir__ . '/../' . $database_folder . '/reports/*', GLOB_ONLYDIR); //find boards
	
		foreach ($report_boards as $board ) { //for each board
		$reports = [];
		$reports = glob($board . "/*"); //find reports
			foreach ($reports as $report) { //for each report
				if (is_numeric(basename($report, '.php'))) {
					$counter = $counter + 1;
				}
			}
		}
		file_put_contents(__dir__ . '/../' . $database_folder . '/reports/current.php', $counter);

	} else { //global reports
		$reports = [];
		$reports = glob(__dir__ . '/../' . $database_folder . '/reportsglobal/*');
			foreach ($reports as $report) {
				if (is_numeric(basename($report, '.php'))) {
					$counter = $counter + 1;
				}
			}
		file_put_contents(__dir__ . '/../' . $database_folder . '/reportsglobal/current.php', $counter);
	}
}

function DeletePost($database_folder, $uploads_folder, $board, $thread, $post, $fileonly = false, $secure_hash, $recent_replies = 5, $mod_delete = false, $mod_level = false) {

	//wip
	//add moderator checks to bypass pw check later

	if ($thread == $post) { // IF THREAD
		//IF THREAD EXISTS
		if (!file_exists(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread)) {
			error('Deletion logic set to thread, but thread does not exist.');
		}
		include __dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/OP.php';

		//CHECK PASSWORD
		if ($mod_level < $mod_delete) {
			if (crypt(htmlspecialchars($_POST['password']), $secure_hash) != $op_password) {
				error('Wrong password...');
			}
		}
		if ($fileonly == true) {
			if ($op_file[0][0] == '' || $op_file[0][0] == 'deleted') {
				error ('Thread has no file.');
			}

			if ($op_file[0][0] == 'image') { //add or video here too if thumbnailing video function is made, prob wont.
				unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $op_file[0][1]); //delete file
				unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $op_file[0][6]); //delete thumb
			} else {
				unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $op_file[0][1]); //delete file
			}
			$op_info = file_get_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/OP.php');
			$op_info = preg_replace('/\$op_file = .+"\) \);/i', '$op_file = array( array("deleted") );', $op_info);
			file_put_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/OP.php', $op_info);
			error('File deleted.', true);
		} else {


			$replies_ = glob(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . "/*");
			foreach ($replies_ as $reply) {
				if (is_numeric(basename($reply, '.php'))) {
					include $reply;
					if ($reply_file[0][0] == 'image') { //add or video here too if thumbnailing video function is made, prob wont.
						unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $reply_file[0][1]); //delete file
						unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $reply_file[0][6]); //delete thumb
					} elseif ($reply_file[0][0] != '' && $reply_file[0][0] != 'deleted') {
						unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $reply_file[0][1]); //delete file
					}
					$reply_file[0][0] = ""; //reset for next iteration in case is not set for some reason
					unlink($reply); //delete post
				}
			}
			//DELETE ALL CONTENT IN FOLDER (for each file in folder, unlink), info.php, op.php, etc
			$files_ = glob(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . "/*");
			foreach ($files_ as $file) {
				unlink($file);
			}
			//DELETE FOLDER
			rmdir(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread);

			//success!
			UpdateThreads($database_folder, $board, $thread); //avoid error on page read
			error('Thread and all its content has been deleted!', true);
		}

	}

	if ($thread != $post) { // IF REPLY
		if (!file_exists(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php')) {
			error('Deletion logic set to reply, but specified reply does not exist in this thread.');
		}
		include __dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php';
		
		if ($mod_level < $mod_delete) {
			if (crypt(htmlspecialchars($_POST['password']), $secure_hash) != $reply_password) {
				error('Wrong password...');
			}
		}

		//delete files if exist.
		//if file only is true and no file:
		if ($fileonly == true) {
			if ($reply_file[0][0] == '' || $reply_file[0][0] == 'deleted') {
				error ('Post has no file.');
			}
		}
		//delete files

		if ($reply_file[0][0] == 'image') { //add or video here too if thumbnailing video function is made, prob wont.
			unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $reply_file[0][1]); //delete file
			unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $reply_file[0][6]); //delete thumb
		} elseif ($reply_file[0][0] != '' && $reply_file[0][0] != 'deleted') {
			unlink(__dir__ . '/../' . $uploads_folder . '/' . $board . '/' . $reply_file[0][1]); //delete file
		}

		//save file deletion if file only
		if ($fileonly == true) {
		$reply_info = file_get_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php');
		$reply_info = preg_replace('/\$reply_file = .+"\) \);/i', '$reply_file = array( array("deleted") );', $reply_info);
		file_put_contents(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php', $reply_info);
		error('File deleted.', true);
		}

		//delete reply if not fileonly
		unlink(__dir__ . '/../' . $database_folder . '/boards/' . $board . '/' . $thread . '/' . $post . '.php'); //delete post
		UpdateRecents($database_folder, $board, $thread, $recent_replies); //update omitted and stuff
		error('Post and file deleted.', true);
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