<?php 


//inits
$isImage_ = '';
$isAudio_ = '';
$isVideo_ = '';
$isDownload_ = '';

$file_type = '';
$original_filename = '';
$new_filename = '';
$upload_resolution = '';
//$isSpoiler_ = '';
$filesize_ = '';


function isAllowedFile($file_upload, $array) {
	if (in_array($file_upload, $array)) {
		return true;
	}
	else {
		return false;
	}
}

if ($allow_files !== true && isset($_FILES['file'])) {
		error("Files are not be uploaded on this board.");
}

//function handleFile() 
	if(!isset($_FILES['file']) || $_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) {
	    //$output_html .= "No file selected"; 
	} else {

		$filesize_ = $_FILES['file']['size'];
	    $filename_ = phpClean($_FILES["file"]["name"]);
	    $tmpname_ = $_FILES["file"]["tmp_name"];
		$fileext_ = '.' . pathinfo($filename_, PATHINFO_EXTENSION);

		//is it too big?
		if ($_FILES['file']['size'] > $max_filesize) {
			$output_html .= formatBytes($_FILES['file']['size']) . 'is too big. Maximum filesize is' . formatBytes($max_filesize) . '.';
			echo $output_html;
			exit();
		}
	  	
	  	//is it supported?
		if (!isAllowedFile($fileext_, $config['allowed_ext']['img']) === true && !isAllowedFile($fileext_, $config['allowed_ext']['audio']) === true && !isAllowedFile($fileext_, $config['allowed_ext']['video']) === true && !isAllowedFile($fileext_, $config['allowed_ext']['downloads']) === true) {
			$output_html .= $fileext_ . ' is not supported.';
			echo $output_html;
			exit();
		}

	  	//is downloads?
		if (isAllowedFile($fileext_, $config['allowed_ext']['downloads']) === true) {
			$isDownload_ = true;
			$file_type = 'download';
		}

		// IS THIS AUDIO?
		if (isAllowedFile($fileext_, $config['allowed_ext']['audio']) === true) {

			$info = new finfo(FILEINFO_MIME);
			$type = $info->buffer(file_get_contents($_FILES['file']['tmp_name']));
			$type = preg_replace('/;(.*)/', '', $type);
			
			//dunno if im gonna add anything here
			switch ($type) {
			    case 'audio/mpeg':
			    break;
			  	case 'audio/mp3':
			    break;
			    case 'audio/ogg':
			    break;
			    case 'audio/wav':
			    break;
			    case 'audio/x-matroska':
			    break;
			    case 'audio/mp4':
			    break;
			    default:
			        error('Format not supported!');
			    break;
			}

			$isAudio_ = true;
			$file_type = $type;
		}

		// IS THIS VIDEO?
		if (isAllowedFile($fileext_, $config['allowed_ext']['video']) === true) {

			$info = new finfo(FILEINFO_MIME);
			$type = $info->buffer(file_get_contents($_FILES['file']['tmp_name']));
			$type = preg_replace('/;(.*)/', '', $type);
			
			//this is where something like imagemagick support could be added
			switch ($type) {
			    case 'video/x-msvideo': //.avi
			    break;
			  	case 'video/mp4': //mp4
			    break;
			    case 'video/mpeg': //mpeg
			    break;
			    case 'video/ogg': //.ogv
			    break;
			    case 'video/webm': //.webm
			    break;
			    case 'video/x-matroska': //.mkv
			    break;
			    default:
			        error('Format not supported!');
			    break;
			}

			$isVideo_ = true;
			$file_type = $type;
		}		


	    //IS THIS IMAGE?
	    if (isAllowedFile($fileext_, $config['allowed_ext']['img']) === true) {
		    //is valid image?
		    if(!exif_imagetype($tmpname_)) {
		    	$output_html .= 'This is an invalid image MIME...';
		    	echo $output_html;
		    	exit();
			}
			//is valid resolution?
			$imagedetails_ = getimagesize($_FILES['file']['tmp_name']);
			$width = $imagedetails_[0];
			$height = $imagedetails_[1];
			if ($image_max_res < $width || $image_max_res < $height) {
				$output_html .= 'Maximum image resolution is ' . $image_max_res . 'x' . $image_max_res . '.';
		    	echo $output_html;
		    	exit();
			}

			$upload_resolution = $width . 'x' . $height;

			$isImage_ = true;
			$file_type = 'image';
		}

		//OK EVERYTHING CHECKS OUT FOR THIS FILE. PROCEED WITH POSTING

		//DOES UPLOAD FOLDER EXIST?
		if (!file_exists($path . '/' . $uploads_folder)) {
			mkdir($path . '/' . $uploads_folder, 0755, true);
		}
		//DOES BOARD FOLDER EXIST?
		if (!file_exists($path . '/' . $uploads_folder . '/' . $post_board)) {
			mkdir($path . '/' . $uploads_folder . '/' . $post_board, 0755, true);
		}

		//MOVE AND RENAME FILE

		if (strlen($filename_) < 512) {
        	$original_filename = $filename_;
        } else {
        	$original_filename = substr($filename_, 0, 512) . '(...)' . $fileext_; //cut to 512 for saving purposes
        }

		

		function getFilename ($method, $ext_) {
			if ($method == 'unix') {
				return time() . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) . $ext_;
			}
			if ($method == 'uniq') {
				return uniqid() . time() . $ext_;
			}
		}


		$new_filename = getFilename($filename_method, $fileext_);
		$new_thumbname = 'thmb_' . preg_replace('/.[^.]*$/', '', $new_filename) . '.jpg';

		//thumbnail?
		if ($isImage_ == true) {
        	if (isset($_POST['index'])) {
        		$new_height = $thumb_res_op;
		    } else {
		    	$new_height = $thumb_res_reply;
		    }

		    $new_width = floor( $width * ( $new_height / $height ) );

		    $ratio = $width / $height;

		    if (isset($_POST['thread'])) { //resize again if width too big
				if ($new_width > $thumb_res_reply) {
					$new_width = $thumb_res_reply;
					$new_height =  $new_width / $ratio;
				}
			}
			if (isset($_POST['index'])) {
				if ($new_width > $thumb_res_op) {
					$new_width = $thumb_res_op;
					$new_height = $new_width / $ratio;
				}
			}

			//prevent 0px from resized weirdass files (like 1x1000)
			if ($new_width == 0) {
				$new_width = 1;
			}
			if ($new_height == 0) {
				$new_height = 1;
			}
		    
		    $thmb_res = floor($new_width) . 'x' . floor($new_height);

			if ($fileext_ == '.jpg' || $fileext_ == '.jpeg') {
				$old_image = ImageCreateFromJPEG($_FILES['file']['tmp_name']);
				$new_thumb = imagecreatetruecolor($new_width, $new_height);
				$color = imagecolorallocate($new_thumb, $thumbnail_bg_red, $thumbnail_bg_green, $thumbnail_bg_blue);
				imagefill($new_thumb, 0, 0, $color);
				imagecopyresampled($new_thumb, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				ImageJpeg($new_thumb, $path . '/' . $uploads_folder . '/' . $post_board . '/' . $new_thumbname);
			}
			if ($fileext_ == '.png') {
				$old_image = ImageCreateFromPNG($_FILES['file']['tmp_name']);
				$new_thumb = imagecreatetruecolor($new_width, $new_height);
				$color = imagecolorallocate($new_thumb, $thumbnail_bg_red, $thumbnail_bg_green, $thumbnail_bg_blue);
				imagefill($new_thumb, 0, 0, $color);
				imagealphablending($new_thumb, true);
				imagesavealpha($new_thumb, true);
				imagecopyresampled($new_thumb, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				ImageJpeg($new_thumb, $path . '/' . $uploads_folder . '/' . $post_board . '/' . $new_thumbname);
			}
			if ($fileext_ == '.gif') {
				$old_image = ImageCreateFromGIF($_FILES['file']['tmp_name']);
				$new_thumb = imagecreatetruecolor($new_width, $new_height);
				$color = imagecolorallocate($new_thumb, $thumbnail_bg_red, $thumbnail_bg_green, $thumbnail_bg_blue);
				imagefill($new_thumb, 0, 0, $color);
				imagealphablending($new_thumb, true);
				imagesavealpha($new_thumb, true);
				imagecopyresampled($new_thumb, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				ImageJpeg($new_thumb, $path . '/' . $uploads_folder . '/' . $post_board . '/' . $new_thumbname);
			}
			if ($fileext_ == '.webp') {
				$old_image = ImageCreateFromWEBP($_FILES['file']['tmp_name']);
				$new_thumb = imagecreatetruecolor($new_width, $new_height);
				$color = imagecolorallocate($new_thumb, $thumbnail_bg_red, $thumbnail_bg_green, $thumbnail_bg_blue);
				imagefill($new_thumb, 0, 0, $color);
				imagealphablending($new_thumb, true);
				imagesavealpha($new_thumb, true);
				imagecopyresampled($new_thumb, $old_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				ImageJpeg($new_thumb, $path . '/' . $uploads_folder . '/' . $post_board . '/' . $new_thumbname);
			}

		}


		move_uploaded_file($_FILES['file']['tmp_name'], $path . '/' . $uploads_folder . '/' . $post_board . '/' . $new_filename);

		
}

//SAVE POST INFORMATION is in post.php



?>