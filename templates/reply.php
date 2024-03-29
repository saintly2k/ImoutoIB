<?php

$output_html .= '<div class="post-arrows">&gt;&gt;</div>
<div class="post reply" id="' . $post_number_reply . '">
	<div class="post-info">';

		if ($post_buttons == true) {
			include 'post-buttons-reply.php';
		}

		if ($reply_subject != '') { $output_html .= '<span class="subject">' . $reply_subject . ' </span>'; }
		if (($reply_email != '') && ($show_email != false)) { $output_html .= '<a href="mailto:' . $reply_email . '">';} $output_html .= '<span class="'; if(($reply_email != '') && ($show_email != false)) { $output_html .= 'link '; } $output_html .= 'name">' . $reply_name . '</span>'; if (($reply_email != '') && ($show_email != false)) { $output_html .= '</a>'; }
		$output_html .= '<span class="post-time" data-timestamp="' . $reply_time . '" data-tooltip="' . timeConvert($reply_time, $time_method_hover) . '"> ' . timeConvert($reply_time, $time_method) . '</span>';
	
		if ($display_id == true) {
			$output_html .= '<span class="id"> ID:&nbsp;';
			if ($reply_email == "sage") {
				$output_html .= 'Heaven';
			} else {
				$idhash = md5($current_board . $post_number_op);
				$reply_id = crypt($reply_ip, $idhash);
				$reply_id = preg_replace("/\./", "", $reply_id);
				$output_html .= substr($reply_id, -8);
			}
			$output_html .= '</span>';
		}

		$output_html .= '<span class="post-number">';
		$output_html .= '<a class="anchor" name="' . $post_number_reply . '" href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#' . $post_number_reply . '"> No.</a>';
		$output_html .= '<a class="click" num="' . $post_number_reply . '" id="cite_' . $post_number_reply . '" href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#q' . $post_number_reply . '">' . $post_number_reply . '</a>';
		$output_html .= '&nbsp;</span>';

$output_html .=	'</div>';

	if ($reply_file[0][0] == "deleted") {
		$output_html .= '<div class="post-file"><div class="file-info deleted">File Deleted</div><div class="post-image"><img class="file-deleted" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_deleted . '"/></div></div>';
	}
	elseif ($reply_file[0][0] != '') {
		
			$output_html .= '<div class="post-file" data-file="'.$reply_file[0][0].'">';
				$output_html .= '<div class="file-info">';
				if ($original_filename == true && strlen($reply_file[0][2]) > $max_filename ) {
					$output_html .= 'File: <a title="' . $reply_file[0][2] . '" href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';
					$output_html .= substr($reply_file[0][2], 0, $max_filename) . '(...).' . getExt($reply_file[0][2]);
					$output_html .= '</a>';
				} elseif ($original_filename == true) {
					$output_html .= 'File: <a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';
					$output_html .= $reply_file[0][2];
					$output_html .= '</a>';	
				} else {
					$output_html .= 'File: <a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';
					$output_html .= $reply_file[0][1];
					$output_html .= '</a>';
				}

				if (isset($file_download)) {
					switch($file_download) {
						case 'original':
							$output_html .= '&nbsp;<a title="Download as ' . $reply_file[0][2] . '" class="download" href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '" download="' . $reply_file[0][2] . '">➜</a>';
							break;
						case 'server':
							$output_html .= '&nbsp;<a title="Download as ' . $reply_file[0][1] . '" class="download" href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '" download="' . $reply_file[0][1] . '">➜</a>';
							break;
						default:
							break;
					}
				}

				if ($reply_file[0][0] == 'image') {
				$output_html .= ' (' . formatBytes($reply_file[0][4]) . ', ' . $reply_file[0][3] . ')';
				$output_html .= '</div>';
				$output_html .= '<div class="post-image" data-file="'.$reply_file[0][0].'">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';

						$thmb_width = preg_replace('/x[^x]*$/', '', $reply_file[0][7]);
						$thmb_height = preg_replace('/^[^x]*x/', '', $reply_file[0][7]);
						$full_width = preg_replace('/x[^x]*$/', '', $reply_file[0][3]);
						$full_height = preg_replace('/^[^x]*x/', '', $reply_file[0][3]);

						if ($reply_file[0][5] != "1") {
						$output_html .= '<img img-id="'.$post_number_reply.'" class="thumb" width="'.$thmb_width.'" height="'.$thmb_height.'" src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][6] . '"/>';
						} else {
						$output_html .= '<img img-id="'.$post_number_reply.'" class="thumb" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_spoiler . '"/>';
						}
						$output_html .= '<img img-id="'.$post_number_reply.'" class="expand dnone" data-width="'.$full_width.'" data-height="'.$full_height.'" img-src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '"/>';
						//image full

					$output_html .= '</a>';
				$output_html .= '</div>';
				}
				if (preg_match('/audio/', $reply_file[0][0])) {
					$output_html .= ' (' . formatBytes($reply_file[0][4]) . ')';
					$output_html .= '</div>';
					$output_html .= '<div class="post-image" data-file="audio">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';
					$output_html .= '<img class="thumb" audio-id="'.$post_number_reply.'" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_audio . '"/>';
					$output_html .= '</a>';
					$output_html .= '<audio class="dnone" audio-type="'.$reply_file[0][0].'" audio-src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '" audio-id="'.$post_number_reply.'" controls autoplay loop> Your browser does not support the audio tag.</audio>';
					$output_html .= '</div>';
				}
				if (preg_match('/video/', $reply_file[0][0])) {
					$output_html .= ' (' . formatBytes($reply_file[0][4]) . ')';
					$output_html .= '</div>';
					$output_html .= '<div class="post-image" data-file="video">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';
					$output_html .= '<img class="thumb" vid-id="'.$post_number_reply.'" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_video . '"/>';
					$output_html .= '</a>';
					$output_html .= '	<video class="dnone" vid-type="'.$reply_file[0][0].'" vid-src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '" vid-id="'.$post_number_reply.'" controls autoplay loop>Your browser does not support the video tag.</video>';
					$output_html .= '</div>';
				}
				if ($reply_file[0][0] == 'download') {
					$output_html .= ' (' . formatBytes($reply_file[0][4]) . ')';
					$output_html .= '</div>';
					$output_html .= '<div class="post-image" data-file="'.$reply_file[0][0].'">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $reply_file[0][1] . '">';
					$output_html .= '<img width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_download . '"/>';
					$output_html .= '</a>';
					$output_html .= '</div>';
				}
			$output_html .= '</div>';
		}
		

		//reset files
		$reply_file = false;
$output_html .= '
	<blockquote class="post-content">' . $reply_body . '</blockquote>
</div>';

