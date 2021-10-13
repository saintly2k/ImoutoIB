<?php
if ($ban['duration'] == 'warning') {
	$title = 'Uh oh! Warning...';
} else {
	$title = 'Uh oh! Banned...';
}

$current_board = phpClean($_POST["board"]);

include $path . '/templates/header.php';

$output_html .= '<div class="main first banned">
	<h2>';
	if ($ban['duration'] == 'warning') {
		$output_html .= 'Warning!';
	} else {
		$output_html .= 'Banned!';
	}
$output_html .= '</h2>';

		$output_html .= '<img class="banned" src="' . $prefix_folder . '/assets/img/banned.png' . '">';

		$output_html .= '<p>How did you manage this...</p>';

		$output_html .= '<div class="post reply banned">';
		if ($ban['post-body'] != false ) {
			$output_html .= '<div class="post-info">';
			if ($ban['post-subject'] != '') {
				$output_html .= '<span class="subject">'.$ban['post-subject'].'&nbsp;</span>';
			}
			if ($ban['post-email'] != '') {
				$output_html .= '<span class="name"><a href="mailto:'.$ban['post-email'].'">'.$ban['post-name'].'</a>&nbsp;</span>';
			} else {
				$output_html .= '<span class="name">'.$ban['post-name'].'&nbsp;</span>';
			}
			$output_html .= '<span class="post-time" data-tooltip="'.timeConvert($ban['post-time'], $time_method_hover).'" data-timestamp="'.$ban['post-time'].'">'.timeConvert($ban['post-time'], $time_method).'&nbsp;</span>';
			$output_html .= '<span class="post-number">No.'.$ban['reply'].'</span>';
			$output_html .= '</div><blockquote class="post-content">'.$ban['post-body'].'</blockquote>';
		} else {
			$output_html .= 'Manual ban.';
		}


	$output_html .= '</div>';
	
	$output_html .= '<p style="word-break:break-word;">';

	if ($ban['duration'] != 'warning') {
		if ($ban['duration'] == 'permanent') {
			$output_html .= 'You were permanently banned on ' . timeConvert($ban['time'], $method = "compactsince") . '. This ban will not expire.';
		} elseif ($ban['is_active'] == "1") {
			$output_html .= 'You were banned on ' . timeConvert($ban['time'], $method = "compactsince") . ' and your ban expires on ' .  timeConvert($ban['time'] + $ban['duration'], $method = "compact") . ' (in ' . timeuntil($ban['time'] + $ban['duration']) . ').';
		} else {
			$output_html .= 'You were banned on ' . timeConvert($ban['time'], $method = "compactsince") . ' and your ban <b>expired</b> ' . timeago($ban['time']) . '. You may now continue posting.';
		}
	} else {
		$output_html .= 'You received a warning on ' . timeConvert($ban['time'], $method = "compactsince") . '. You may now continue posting.';
	}
	$output_html .= '</p>';
	$output_html .= '<p><b>Reason:</b> ' . $ban['reason'] . '</p>';

	$output_html .= '<p><b>IP:</b> ' . $ban['original_ip'] . '</p>';

$output_html .= '
	<br>
</div>
<br><br>
<div class="message" style="margin-top:0;">[<a href="' . $prefix_folder . '/' . $main_file . '">Return</a>]</div>';
