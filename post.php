<?php

require dirname(__FILE__) . '/require.php';


//POST FIELDS
	$post_board = htmlspecialchars($_POST['board']);
	$post_name = htmlspecialchars($_POST['name']);
	if ($disable_email !== true) {
		$post_email = htmlspecialchars($_POST['email']);
	} else { 
		$post_email = '';
	}
	if ($post_email == 'sage') {
		$_POST['sage'] = true;
	}
	if ($post_email == 'spoiler') {
		$isSpoiler_ = true;
	}
	$post_subject = htmlspecialchars($_POST['subject']);
	$post_body = htmlspecialchars($_POST['body']);

	if (isset($_POST['file'])) {

	}

//Requirements met?

if ($captcha_required == true) {
	if(isset($_POST['captcha'])){
		session_start();
		if (($captcha_required == true) && ($_SESSION['captcha_text'] != strtolower($_POST['captcha']))) {
			error('Wrong captcha!! How annoying...');
		} else {
		session_destroy();
		}
	} else {
		error('No captcha entered.');
	}
}

if ($post_name === '') {
	$post_name = $default_name;
}
if (strlen($post_name) > 256) {
	error('Name too long. Max 256.');
}
if (strlen($post_email) > 256) {
	error('Email too long. Max 256.');
}
if (strlen($post_subject) > 256) {
	error('Subject too long. Max 256.');
}

//IF NEW THREAD
if (isset($_POST['index'])) {
	if (strlen($post_body) > $config['post_body_max']) {
		error('Post too long. Max: 4000.');
	}
	if (strlen($post_body) < $config['post_body_min']) {
		error('Comment too short. Min: 10.');
	} 
}

//IF NEW REPLY
if (isset($_POST['thread'])) {
	
	if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
		if (strlen($post_body) < $config['reply_body_min']) {
			error('Reply too short. Min: 10.');
		}
	} else {
		if ($config['reply_file_only'] == false) {
			if (strlen($post_body) < $config['reply_body_min']) {
				error('Reply too short. Min: 10.');
			}
		}
	}
	if (strlen($post_body) > $config['reply_body_max']) {
		error('Reply too long. Max: 4000.');
	}
}



//ARE WE POSTING?
if ((isset($post_board)) && (isset($_POST['index']))) {
	//SHOULD IT EXIST?
	if (!isset($config['boards'][$post_board])) {
		error('This board shouldn\'t exist...');
	}
	//IF NOT EXIST, CREATE DIRECTORY
	if (!file_exists(__dir__ . '/' . $database_folder . '/boards')) {
		mkdir(__dir__ . '/' . $database_folder . '/boards', 0755, true);
	}
	if ((!file_exists(__dir__ . '/' . $database_folder . '/boards/' . $post_board) && (isset($config['boards'][$post_board])) === true)) {
		mkdir(__dir__ . '/' . $database_folder . '/boards/' . $post_board, 0755, true);
	}
	//IS THIS OUR FIRST THREAD?
	

	// if no file in folder
	if (dir_is_empty(__dir__ . '/' . $database_folder . '/boards/' . $post_board)) {
		file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/counter.php', 1); //create post count
	}
	//CREATE THREAD FOLDER
	$counter = file_get_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/counter.php');
	//CHECK FOR AND HANDLE FILES
	include __dir__ . '/includes/filehandler.php';
	$current_count = $counter;
	mkdir(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $current_count, 0755, true); //create thread folder

	//COLLECT POST INFORMATION
	$create_OP = '<?php $op_name = "' . $post_name . '";';
	$create_OP .= '$op_email = "' . $post_email . '";';
	$create_OP .= '$op_subject = "' . $post_subject . '";';
	$create_OP .= '$op_body = "' . $post_body . '";';
	$create_OP .= '$op_password = "' . $post_password . '";';

	$create_OP .= '$op_file = array( array("' . $file_type . '","' . $new_filename . '","' . $original_filename . '","' . $upload_resolution . '","' . $filesize_ . '","' . $isSpoiler_ . '") );'; //array in array to prepare for multifiles later, easy upgrade i guess


	$create_OP .= '$op_ip = "' . crypt($_SERVER['REMOTE_ADDR'] , $secure_hash) . '";';
	$create_OP .= '$op_time = "' . time() . '"; ?>';

	//SAVE POST INFORMATION
	$current_count = $counter;
	file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $current_count . '/OP.php', $create_OP);
	
	//INCREMENT COUNTER
	$counter = file_get_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/counter.php');
	$newcount = $counter + 1;
	file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/counter.php', $newcount);

	//

	UpdateOP($database_folder, $post_board, $current_count, 1, 0, $current_count, 1); //information about thread and replies
	include __dir__ . '/includes/update-frontpage.php';
	PostSuccess($prefix_folder . $main_file . '/?board=' . $post_board . '&thread=' . $counter . '#' . $counter, true);
	
	}

if ((isset($post_board)) && (isset($_POST['thread']))) {
	$post_is_thread = htmlspecialchars($_POST['thread']);
	$post_thread_number = htmlspecialchars($_POST['thread_number']);
	//board exists?
	if (!isset($config['boards'][$post_board])) {
		error('This board shouldn\'t exist...');
	}
	//thread exists?
	if (($post_is_thread == 'thread') && (file_exists(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $post_thread_number . '/OP.php'))) {
		//THREAD EXISTS


		//CREATE/INCREASE COUNTER+LAST BUMPED. to do: (reset bump on post deletion by user or mod, do elsewhere)
			$counter = file_get_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/counter.php');
		//CHECK FOR AND HANDLE FILES
			include __dir__ . '/includes/filehandler.php';
			$newcount = $counter + 1;
			//save it as last bumped if not sage tho
			if (!isset($_POST['sage'])) {
			file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $post_thread_number . '/bumped.php', $counter);
			}
			//save it as last post number
			file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/counter.php', $newcount);
		//counter handled... moving on:

		//POST STUFF
		//COLLECT POST INFORMATION
		$create_reply = '<?php $reply_name = "' . $post_name . '";';
		$create_reply .= '$reply_email = "' . $post_email . '";';
		$create_reply .= '$reply_subject = "' . $post_subject . '";';
		$create_reply .= '$reply_body = "' . $post_body . '";';
		$create_reply .= '$reply_password = "' . $post_password . '";';

		$create_reply .= '$reply_file = array( array("' . $file_type . '","' . $new_filename . '","' . $original_filename . '","' . $upload_resolution . '","' . $filesize_ . '","' . $isSpoiler_ . '") );'; //array in array to prepare for multifiles later, easy upgrade i guess

		$create_reply .= '$reply_ip = "' . crypt($_SERVER['REMOTE_ADDR'] , $secure_hash) . '";';
		$create_reply .= '$reply_time = "' . time() . '"; ?>';

		//SAVE POST INFORMATION
		$current_count = $counter;
		file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $post_thread_number . '/' . $current_count . '.php', $create_reply);

		//how many replies do we have?
			//FIND REPLIES
		$replies_ = [];
		$replies_ = glob(__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $post_thread_number . "/*");
		$reply_counter = 0;
		foreach ($replies_ as $reply) {
			if (basename($reply) != ('OP.php') && basename($reply) != ('info.php') && basename($reply) != ('bumped.php')) {
				$reply_counter += 1;
			}
		}
		//how many unique posters do we have?
		$ip_counter = 1;
		$ips_ = [];

		//Get OP IP
		include (__dir__ . '/' . $database_folder . '/boards/' . $post_board . '/' . $post_thread_number . "/OP.php");
		$ips_ = [];
		$ips_[] = $op_ip;
		//Get replies ips
		foreach ($replies_ as $reply) {
		include ($reply);
		$ips_[] = $reply_ip;
		}
		$ip_counter = count(array_unique($ips_)); 


		UpdateOP($database_folder, $post_board, $post_thread_number, 0, $reply_counter, $current_count, $ip_counter);
		include __dir__ . '/includes/update-frontpage.php';
		PostSuccess($prefix_folder . $main_file . '/?board=' . $post_board . '&thread=' . $post_thread_number . '#' . $current_count, true);
		
		}
}


error('This shouldn\'t happen..');
?>