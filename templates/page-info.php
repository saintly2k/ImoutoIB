<?php 

if ($config['display_banner'] === true) {
	include $path . '/assets/img/banner.php';
}


$output_html .= '<div class="page-info">
<h1>/' . $current_board . '/ - ' . $board_title . '</h1>
<span class="small">' . $board_slogan . '</span>';

if ($logged_in == true) {
	$output_html .= '<hr>You are logged in.<br>[<a href="' . $prefix_folder . '/mod.php">Return to Dashboard</a>]';
}

$output_html .= '
</div>
<hr>';