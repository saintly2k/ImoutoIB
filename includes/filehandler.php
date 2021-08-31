<?php 


//inits
$isImage_ = '';
$isAudio_ = '';
$isVideo_ = '';
$isDownload_ = '';


function isAllowedFile($file_upload, $array) {
	if (in_array($file_upload, $array)) {
		return true;
	}
	else {
		return false;
	}
}

//function handleFile() 
	if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
	    //echo "No file selected"; 
	} else {

	    $filename_ = $_FILES["file"]["name"];
	    $tmpname_ = $_FILES["file"]["tmp_name"];
		$fileext_ = '.' . pathinfo($filename_, PATHINFO_EXTENSION);

		//is it too big?
		if ($_FILES['file']['size'] > $max_filesize) {
			echo formatBytes($_FILES['file']['size']) . 'is too big. Maximum filesize is' . formatBytes($max_filesize) . '.';
			exit();
		}
	  	
	  	//is it supported?
		if (!isAllowedFile($fileext_, $config['allowed_ext']['img']) === true && !isAllowedFile($fileext_, $config['allowed_ext']['audio']) === true && !isAllowedFile($fileext_, $config['allowed_ext']['video']) === true && !isAllowedFile($fileext_, $config['allowed_ext']['downloads']) === true) {
			echo $fileext_ . ' is not supported.';
			exit();
		}

	  	//is audio, video, or downloads?
		if (isAllowedFile($fileext_, $config['allowed_ext']['audio']) === true || isAllowedFile($fileext_, $config['allowed_ext']['video']) === true || isAllowedFile($fileext_, $config['allowed_ext']['downloads']) === true) {
			echo 'havent coded these files yet lol but i will';
			exit();
		}


	    //IS THIS IMAGE?
	    if (isAllowedFile($fileext_, $config['allowed_ext']['img']) === true) {
		    //is valid image?
		    if(!exif_imagetype($tmpname_)) {
		    	echo 'This is an invalid image MIME...';
		    	exit();
			}

			//TODO check image dimensions

			$isImage_ = true;
		}

		//OK EVERYTHING CHECKS OUT FOR THIS FILE. PROCEED WITH POSTING

		//DOES UPLOAD FOLDER EXIST?
		if (!file_exists(__dir__ . '/../' . $uploads_folder)) {
			mkdir(__dir__ . '/../' . $uploads_folder, 0755, true);
		}
		//DOES BOARD FOLDER EXIST?
		if (!file_exists(__dir__ . '/../' . $uploads_folder . '/' . $post_board)) {
			mkdir(__dir__ . '/../' . $uploads_folder . '/' . $post_board, 0755, true);
		}

		//MOVE AND RENAME FILE
		$original_filename = $filename_;

		if ($filename_method == 'unix') {
			$new_filename = time() . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . $fileext_;
		}
		if ($filename_method == 'uniq') {
			$new_filename = uniqid() . time() . $fileext_;
		}

		move_uploaded_file($_FILES['file']['tmp_name'], __dir__ . '/../' . $uploads_folder . '/' . $post_board . '/' . $new_filename);

}



?>