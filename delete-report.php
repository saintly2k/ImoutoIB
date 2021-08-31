<?php

require 'require.php';


//if captcha required?

if (empty($_POST)) {
	echo 'No post request received.';
	exit();
}

//cleanse
if (isset($_POST["delete"])) {
	$delrep_delete = htmlspecialchars($_POST["delete"]);
}
if (isset($_POST["report"])) {
	$delrep_report = htmlspecialchars($_POST["report"]);
}
if (isset($_POST["board"])) {
	$delrep_board = htmlspecialchars($_POST["board"]);
}
if (isset($_POST["thread"])) {
	$delrep_thread = htmlspecialchars($_POST["thread"]);
}
if (isset($_POST["reply"])) {
	$delrep_reply = htmlspecialchars($_POST["reply"]);
}
if (isset($_POST["password"])) {
	$delete_password = htmlspecialchars($_POST["password"]);
}
if (isset($_POST["file"])) {
	$delete_file = htmlspecialchars($_POST["file"]);
}
if (isset($_POST["reason"])) {
	$report_reason = htmlspecialchars($_POST["reason"]);
}
if (isset($_POST["global"])) {
	$report_global = htmlspecialchars($_POST["global"]);
}


//DOES BOARD EXIST?
if (!in_Array($delrep_board, $config['boardlist'])) {
	echo 'Board ' . $delrep_board . ' does not exist.';
	exit();
}
//DOES REPLY EXIST
if (isset($delrep_reply) && (!file_exists($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/' . $delrep_reply . '.php'))) {
		echo 'Reply ' . $delrep_reply . 'does not exist.';
		exit();
}
//DOES THREAD EXIST?
if (isset($delrep_thread) && (!file_exists($path . '/' . $database_folder . '/boards/' . $delrep_board . '/' . $delrep_thread . '/OP.php'))) {
		echo 'Thread ' . $delrep_thread . 'does not exist.';
		exit();
}

//OK THEN CONTINUE:



if (isset($_POST["delete"]) && $_POST["delete"] != "") {
	//DELETE
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