<?php 

if ($config['display_banner'] === true) {
	include $path . '/assets/img/banner.php';
}


$output_html .= '<br><div class="page-info-txt"><div class="page-info-txt-inner">
<h1>' . $board_title . ' @ ' . $site_name . '</h1>
' . $board_slogan;

if ($logged_in == true) {
	$output_html .= '<hr>You are logged in.<br>[<a href="' . $prefix_folder . '/mod.php">Return to Dashboard</a>]';
}

$output_html .= '
</div></div><br>';

?>