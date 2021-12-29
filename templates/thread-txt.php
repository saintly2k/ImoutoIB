<?php 

$output_html .= '<div class="thread" data-thread="' . $post_number_op . '">';

$output_html .= '<div class="post op" id="' . $post_number_op . '">';

	if ($op_file[0][0] == "deleted") {
		$output_html .= '<div class="post-file"><div class="file-info deleted">File Deleted</div><div class="post-image"><img class="file-deleted" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_deleted . '"/></div></div>';
	}
	elseif ($op_file[0][0] != '') {
		
			$output_html .= '<div class="post-file" data-file="'.$op_file[0][0].'">';
				$output_html .= '<div class="file-info">';
				if ($original_filename == true && strlen($op_file[0][2]) > $max_filename ) {
					$output_html .= 'File: <a title="' . $op_file[0][2] . '" href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';
					$output_html .= substr($op_file[0][2], 0, $max_filename) . '(...).' . getExt($op_file[0][2]);
					$output_html .= '</a>';
				} elseif ($original_filename == true) {
					$output_html .= 'File: <a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';
					$output_html .= $op_file[0][2];
					$output_html .= '</a>';	
				} else {
					$output_html .= 'File: <a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';
					$output_html .= $op_file[0][1];
					$output_html .= '</a>';
				}

				if (isset($file_download)) {
					switch($file_download) {
						case 'original':
							$output_html .= '&nbsp;<a title="Download as ' . $op_file[0][2] . '" class="download" href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '" download="' . $op_file[0][2] . '">➜</a>';
							break;
						case 'server':
							$output_html .= '&nbsp;<a title="Download as ' . $op_file[0][1] . '" class="download" href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '" download="' . $op_file[0][1] . '">➜</a>';
							break;
						default:
							break;
					}
				}

				if ($op_file[0][0] == 'image') {
				$output_html .= ' (' . formatBytes($op_file[0][4]) . ', ' . $op_file[0][3] . ')';
				$output_html .= '</div>';
				$output_html .= '<div class="post-image" data-file="'.$op_file[0][0].'">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';

						$thmb_width = preg_replace('/x[^x]*$/', '', $op_file[0][7]);
						$thmb_height = preg_replace('/^[^x]*x/', '', $op_file[0][7]);
						$full_width = preg_replace('/x[^x]*$/', '', $op_file[0][3]);
						$full_height = preg_replace('/^[^x]*x/', '', $op_file[0][3]);

						if ($op_file[0][5] != "1") {
						$output_html .= '<img img-id="'.$post_number_op.'" class="thumb" width="'.$thmb_width.'" height="'.$thmb_height.'" src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][6] . '"/>';
						} else {
						$output_html .= '<img img-id="'.$post_number_op.'" class="thumb" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_spoiler . '"/>';
						}
						$output_html .= '<img img-id="'.$post_number_op.'" class="expand dnone" data-width="'.$full_width.'" data-height="'.$full_height.'" img-src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '"/>';
						//image full

					$output_html .= '</a>';
				$output_html .= '</div>';
				}
				if (preg_match('/audio/', $op_file[0][0])) {
					$output_html .= ' (' . formatBytes($op_file[0][4]) . ')';
					$output_html .= '</div>';
					$output_html .= '<div class="post-image" data-file="audio">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';
					$output_html .= '<img class="thumb" audio-id="'.$post_number_op.'" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_audio . '"/>';
					$output_html .= '</a>';
					$output_html .= '<audio class="dnone" audio-type="'.$op_file[0][0].'" audio-src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '" audio-id="'.$post_number_op.'" controls autoplay loop> Your browser does not support the audio tag.</audio>';
					$output_html .= '</div>';
				}
				if (preg_match('/video/', $op_file[0][0])) {
					$output_html .= ' (' . formatBytes($op_file[0][4]) . ')';
					$output_html .= '</div>';
					$output_html .= '<div class="post-image" data-file="video">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';
					$output_html .= '<img class="thumb" vid-id="'.$post_number_op.'" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_video . '"/>';
					$output_html .= '</a>';
					$output_html .= '<video class="dnone" vid-type="'.$op_file[0][0].'" vid-src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '" vid-id="'.$post_number_op.'" controls autoplay loop> Your browser does not support the video tag.</video>';
					$output_html .= '</div>';
				}
				if ($op_file[0][0] == 'download') {
					$output_html .= ' (' . formatBytes($op_file[0][4]) . ')';
					$output_html .= '</div>';
					$output_html .= '<div class="post-image" data-file="'.$op_file[0][0].'">';
					$output_html .= '<a href="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][1] . '">';
					$output_html .= '<img width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_download . '"/>';
					$output_html .= '</a>';
					$output_html .= '</div>';
				}				
			$output_html .= '</div>';
		}
		
		//reset files
		$op_file = false;
	

$output_html .= '<div class="post-info">';

		if ($op_subject != '') { 
			if ($current_page === 'index') {
				$output_html .= '<h2 class="subject"><a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#' . $post_number_op . '">' . $op_subject;

				$output_html .= ' <small>';
				$output_html .= ' (' . $info_replies . ') ';
				if ($info_sticky == 1) {
					$output_html .= '(sticky) ';
				}
				if ($info_locked == 1) {
					$output_html .= '(locked) ';
				}
				if ($info_autosage == 1) {
					$output_html .= '(permasaged) ';
				}
				$output_html .= '</small>';

				$output_html .= '</a></h2>'; 
			} else {
				$output_html .= '<h3 class="subject">' . $op_subject;

				$output_html .= ' (' . $info_replies . ') ';

				if ($info_sticky == 1 || $info_locked == 1 || $info_autosage == 1) {
					if ($info_sticky == 1) {
						$output_html .= '(sticky) ';
					}
					if ($info_locked == 1) {
						$output_html .= '(locked) ';
					}
					if ($info_autosage == 1) {
						$output_html .= '(permasaged) ';
					}
				}

				$output_html .= '</h3>';
			}
		} else {

			if ($current_page === 'index') {
				$output_html .= '<h2 class="subject"><a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#' . $post_number_op . '">no subject';

				$output_html .= ' <small>';
				$output_html .= ' (' . $info_replies . ') ';
				if ($info_sticky == 1) {
					$output_html .= '(sticky) ';
				}
				if ($info_locked == 1) {
					$output_html .= '(locked) ';
				}
				if ($info_autosage == 1) {
					$output_html .= '(permasaged) ';
				}
				$output_html .= '</small>';

				$output_html .= '</a></h2>'; 
			} else {
				$output_html .= '<h3 class="subject">no subject';

				$output_html .= ' (' . $info_replies . ') ';

				if ($info_sticky == 1 || $info_locked == 1 || $info_autosage == 1) {
					if ($info_sticky == 1) {
						$output_html .= '(sticky) ';
					}
					if ($info_locked == 1) {
						$output_html .= '(locked) ';
					}
					if ($info_autosage == 1) {
						$output_html .= '(permasaged) ';
					}
				}

				$output_html .= '</h3>';
			}
		}

		$output_html .= '<span class="post-number"><b>';
		//$output_html .= '<a class="anchor" name="' . $post_number_op . '" href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#' . $post_number_op . '">•</a> ';
		$output_html .= '<a class="click" num="' . $post_number_op . '" id="cite_' . $post_number_op . '" href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#q' . $post_number_op . '">' . $post_number_op . '</a>';
		$output_html .= '&nbsp;</b></span>';

		$output_html .= ' Name: ';
		
		
		if (($op_email != '') && ($show_email != false)) { 
			$output_html .= '<a href="mailto:' . $op_email . '">';
		} 
		$output_html .= '<span class="'; 
		if(($op_email != '') && ($show_email != false)) { 
			$output_html .= 'link '; 
		}
		$output_html .= 'name">' . $op_name . '</span> :'; 
		if ($op_email != '') { 
			$output_html .= '</a> :';
		}
		$output_html .= '<span class="post-time" data-timestamp="' . $op_time . '" data-tooltip="' . timeConvert($op_time, $time_method_hover) . '"> ' . timeConvert($op_time, $time_method) . '</span>';
		
		if ($display_id == true) {
			$output_html .= '<span class="id"> ID:';
			if ($op_email == "sage") {
				$output_html .= 'Heaven';
			} else {
				$idhash = md5($current_board . $post_number_op);
				$op_id = crypt($op_ip, $idhash);
				$op_id = preg_replace("/\./", "", $op_id);
				$output_html .= substr($op_id, -8);
			}
			$output_html .= '</span>';
		}

		if ($post_buttons == true) {
			include 'post-buttons-op.php';
		}

$output_html .= '</div>
	<blockquote class="post-content">';
$output_html .=	$op_body;

		if (($current_page == 'index') && ($replies_omitted > 1)) {
		$output_html .= '<br><br><div class="omitted"> ' . $replies_omitted . ' Replies omitted. Click <a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#' . $post_number_op . '">here</a> to view.</div>'; 
		} elseif (($current_page == 'index') && ($replies_omitted > 0)) { 
		$output_html .= '<br><br><div class="omitted"> ' . $replies_omitted . ' Reply omitted. Click <a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '#' . $post_number_op . '">here</a> to view.</div>'; 
		}
$output_html .= '
	</blockquote>
</div>

</div>';

//reset stuff
$info_locked = 0;
$info_sticky = 0;
$info_autosage = 0;

