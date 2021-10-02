<?php

require 'require.php';


//if captcha required?

if (empty($_POST)) {
	echo 'No post request received.';
	exit();
}

//cleanse
if (isset($_POST["delete"])) {
	$delrep_delete = phpClean($_POST["delete"]);
}
if (isset($_POST["report"])) {
	$delrep_report = phpClean($_POST["report"]);
}
if (isset($_POST["board"])) {
	$delrep_board = phpClean($_POST["board"]);
}
if (isset($_POST["thread"])) {
	$delrep_thread = phpClean($_POST["thread"]);
}
if (isset($_POST["reply"])) {
	$delrep_reply = phpClean($_POST["reply"]);
}
if (isset($_POST["password"])) {
	$delete_password = phpClean($_POST["password"]);
}
if (isset($_POST["file"])) {
	$delete_file = phpClean($_POST["file"]);
}
if (isset($_POST["reason"])) {
	$report_reason = phpClean($_POST["reason"]);
	if (strlen($report_reason) > 256) {
		error('Report too long. Maximum 256.');
	}
}
if (isset($_POST["global"])) {
	$report_global = phpClean($_POST["global"]);
}

if (ctype_alnum($delrep_board) != true || ctype_alnum($delrep_thread) != true || ctype_alnum($delrep_reply) != true) {
	error('Invalid board, thread, or reply. Must be alphanumeric.');
}


//DOES BOARD EXIST?
if (!in_Array($delrep_board, $config['boardlist'])) {
	echo 'Board ' . $delrep_board . ' does not exist.';
	exit();
}
//DOES REPLY EXIST
if ($delrep_reply != $delrep_thread) {
	if (isset($delrep_reply) && (!file_exists($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/' . $delrep_reply . '.php'))) {
		echo 'Reply ' . $delrep_reply . ' does not exist.';
		exit();
	}
}
//DOES THREAD EXIST?
if (isset($delrep_thread) && (!file_exists($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/OP.php'))) {
		echo 'Thread ' . $delrep_thread . ' does not exist.';
		exit();
}

//OK THEN CONTINUE:


//MOD THREAD BUTTONS
if ($logged_in == true) {
	
	//STICKY
	if (($config['mod']['thread_sticky'] <= $mod_level) && isset($_POST['sticky'])) {
		$thread_info = file_get_contents($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/info.php');
		if (preg_match('/\$info_sticky=1;/i', $thread_info) == true) {
			$thread_info = preg_replace('/\$info_sticky=1;/i', '$info_sticky=0;', $thread_info);
			$thread_sticky = false;
		} else {
			$thread_info = preg_replace('/\$info_sticky=0;/i', '$info_sticky=1;', $thread_info);
			$thread_sticky = true;
		}
		file_put_contents($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/info.php', $thread_info);
		if ($thread_sticky == true) {
		error("Thread has been stickied.", true);
		} else {
		error("Thread has been unstickied.", true);
		}
	}

	//LOCK
	if (($config['mod']['thread_lock'] <= $mod_level) && isset($_POST['lock'])) {
		$thread_info = file_get_contents($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/info.php');
		if (preg_match('/\$info_locked=1;/i', $thread_info) == true) {
			$thread_info = preg_replace('/\$info_locked=1;/i', '$info_locked=0;', $thread_info);
			$thread_locked = false;
		} else {
			$thread_info = preg_replace('/\$info_locked=0;/i', '$info_locked=1;', $thread_info);
			$thread_locked = true;
		}
		file_put_contents($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/info.php', $thread_info);
		if ($thread_locked == true) {
		error("Thread has been locked.", true);
		} else {
		error("Thread has been unlocked.", true);
		}
	}

	//AUTOSAGE
	if (($config['mod']['thread_autosage'] <= $mod_level) && isset($_POST['autosage'])) {
		$thread_info = file_get_contents($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/info.php');
		if (preg_match('/\$info_autosage=1;/i', $thread_info) == true) {
			$thread_info = preg_replace('/\$info_autosage=1;/i', '$info_autosage=0;', $thread_info);
			$thread_autosage = false;
		} else {
			$thread_info = preg_replace('/\$info_autosage=0;/i', '$info_autosage=1;', $thread_info);
			$thread_autosage = true;
		}
		file_put_contents($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/info.php', $thread_info);
		if ($thread_autosage == true) {
		error("Thread has been autosaged.", true);
		} else {
		error("Thread has been unautosaged.", true);
		}
	}

}



if (isset($_POST["delete"]) && $_POST["delete"] != "") {
	if (isset($_POST['file'])) { //file only?
		DeletePost($database_folder, $uploads_folder, $delrep_board, $delrep_thread, $delrep_reply, true, $secure_hash, $recent_replies, $config['mod']['post_delete'], $mod_level);
	} else {
		DeletePost($database_folder, $uploads_folder, $delrep_board, $delrep_thread, $delrep_reply, false, $secure_hash, $recent_replies, $config['mod']['post_delete'], $mod_level);
	}
}

if (isset($_POST["report"]) && $_POST["report"] != "") {

	//CREATE GLOBAL REPORT
	if (isset($_POST["global"]) && $_POST["global"] == "on") {

		if (!file_exists($path . '/' . $database_folder . '/reportsglobal')) {
			mkdir($path . '/' . $database_folder . '/reportsglobal');
		}
		if (!file_exists($path . '/' . $database_folder . '/reportsglobal/counter.php')) {
			file_put_contents($path . '/' . $database_folder . '/reportsglobal/counter.php', 0);
		}
		$counter = file_get_contents($path . '/' . $database_folder . '/reportsglobal/counter.php');
		$newcount = $counter + 1;

		$create_report = '<?php ';
		$create_report .= '$report_reason = "' . $report_reason . '";';
		$create_report .= '$report_ip = "' . crypt($_SERVER['REMOTE_ADDR'] , $secure_hash) . '";';
		$create_report .= '$report_board = "' . $delrep_board . '";';
		$create_report .= '$report_thread = "' . $delrep_thread . '";';
		if (isset($delrep_reply)) {
		$create_report .= '$report_reply = "' . $delrep_reply . '";';
		}
		$create_report .= '?>';

		file_put_contents($path . '/' . $database_folder . '/reportsglobal/' . $newcount . '.php', $create_report);
		file_put_contents($path . '/' . $database_folder . '/reportsglobal/counter.php', $newcount);
		//increment counter
		ReportCounter($database_folder, 'global'); //refresh report counter
		//done
		if (file_exists($path . '/' . $database_folder . '/reportsglobal/' . $newcount . '.php')) {
		echo 'Global Report Created!';
		} else {
		echo 'Failed generating Global Report...';
		}
		exit();	
	}

	//CREATE BOARD REPORT
	//create reports folder
	if (!file_exists($path . '/' . $database_folder . '/reports')) {
		mkdir($path . '/' . $database_folder . '/reports');
	}
	//create board reports folder
	if (!file_exists($path . '/' . $database_folder . '/reports/' . $delrep_board)) {
		mkdir($path . '/' . $database_folder . '/reports/' . $delrep_board);
	}
	//create counter
	if (!file_exists($path . '/' . $database_folder . '/reports/' . $delrep_board . '/counter.php')) {
		file_put_contents($path . '/' . $database_folder . '/reports/' . $delrep_board . '/counter.php', 0);
	}
	$counter = file_get_contents($path . '/' . $database_folder . '/reports/' . $delrep_board . '/counter.php');
	$newcount = $counter + 1;

	$create_report = '<?php ';
	$create_report .= '$report_reason = "' . $report_reason . '";';
	$create_report .= '$report_ip = "' . crypt($_SERVER['REMOTE_ADDR'] , $secure_hash) . '";';
	$create_report .= '$report_board = "' . $delrep_board . '";';
	$create_report .= '$report_thread = "' . $delrep_thread . '";';
	if (isset($delrep_reply)) {
	$create_report .= '$report_reply = "' . $delrep_reply . '";';
	}
	$create_report .= '?>';

	file_put_contents($path . '/' . $database_folder . '/reports/' . $delrep_board . '/' . $newcount . '.php', $create_report);
	file_put_contents($path . '/' . $database_folder . '/reports/' . $delrep_board . '/counter.php', $newcount);
	//increment counter
	ReportCounter($database_folder, 'normal'); //refresh report counter
	//done
	if (file_exists($path . '/' . $database_folder . '/reports/' . $delrep_board . '/' . $newcount . '.php')) {
	echo 'Board Report Created!';
	} else {
	echo 'Failed generating Board Report...';
	}
	exit();	


}


/*foreach($_POST as $key => $value) {
  echo "POST parameter '$key' has '$value'<hr>";
}*/

echo 'uh... supposed to exit before this';

?>