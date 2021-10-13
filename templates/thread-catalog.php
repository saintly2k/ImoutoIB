<?php

$output_html .= '<div class="thread' . $post_number_op . '">';

$output_html .= '<span class="icons">';

	if ($info_sticky == 1) {
		$output_html .= '<span title="Sticky" class="icon"><img width="16" height="16" src="'. $prefix_folder .'/assets/img/' . $icon_sticky . '"/></span>';
	}
	if ($info_locked == 1) {
		$output_html .= '<span title="Locked" class="icon"><img width="16" height="16" src="'. $prefix_folder .'/assets/img/' . $icon_locked . '"/></span>';
	}
	if ($info_autosage == 1) {
		$output_html .= '<span title="Autosage" class="icon"><img width="16" height="16" src="'. $prefix_folder .'/assets/img/' . $icon_autosage . '"/></span>';
	}

$output_html .= '</span>';

$output_html .= '<div class="post op ' . $post_number_op . '" id="' . $post_number_op . '">';

	if ($op_file[0][0] == "deleted") {
		$output_html .= '<div class="post-file"><img class="file-deleted" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_deleted . '"/></div>';
	}
	elseif ($op_file[0][0] != '') {
			$output_html .= '<div class="post-file">';
				if ($op_file[0][0] == 'image') {
				$output_html .= '<div class="post-image">';

						$thmb_width = preg_replace('/x[^x]*$/', '', $op_file[0][7]);
						$thmb_height = preg_replace('/^[^x]*x/', '', $op_file[0][7]);
						$full_width = preg_replace('/x[^x]*$/', '', $op_file[0][3]);
						$full_height = preg_replace('/^[^x]*x/', '', $op_file[0][3]);

						if ($op_file[0][5] != "1") {
						$output_html .= '<img id="'.$post_number_op.'" class="thumb" width="'.$thmb_width.'" height="'.$thmb_height.'" src="' . $prefix_folder . '/' . $uploads_folder . '/' . $current_board . '/' . $op_file[0][6] . '"/>';
						} else {
						$output_html .= '<img id="'.$post_number_op.'" class="thumb" width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_spoiler . '"/>';
						}

				$output_html .= '</div>';
				}
				if (preg_match('/audio/', $op_file[0][0])) {
					$output_html .= '<div class="post-image">';
					$output_html .= '<img width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_audio . '"/>';
					$output_html .= '</div>';
				}
				if (preg_match('/video/', $op_file[0][0])) {
					$output_html .= '<div class="post-image">';
					$output_html .= '<img width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_video . '"/>';
					$output_html .= '</div>';
				}
				if ($op_file[0][0] == 'download') {
					$output_html .= '<div class="post-image">';
					$output_html .= '<img width="'.$thumb_res_reply.'" height="'.$thumb_res_reply.'" src="' . $prefix_folder . '/assets/img/' . $thumb_download . '"/>';
					$output_html .= '</div>';
				}				
			$output_html .= '</div>';
		}
		

		//reset files
		$op_file = false;
	
$output_html .= '<div class="post-info" data-tooltip="' . timeConvert($op_time, $time_method_hover) . '">';

	if ($op_subject != '') { $output_html .= '<span class="subject">' . $op_subject . '</span><br>'; }
	if (($op_email != '') && ($show_email != false)) { $output_html .= '<a href="mailto:' . $op_email . '">';} 
	
	$output_html .= '<span class="';
	if(($op_email != '') && ($show_email != false)) { $output_html .= 'link '; } $output_html .= 'name">' . $op_name . '</span>'; if ($op_email != '') { $output_html .= '</a>'; } $output_html .= '</div>';


$output_html .='
	<div class="stats">
		Replies: ' . $info_replies . ' Posters: ' . $info_uniqueids . '
	</div>';


$output_html .= '
	<blockquote class="post-content">
		' . $op_body;

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

