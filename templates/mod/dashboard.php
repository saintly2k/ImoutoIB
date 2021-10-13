<?php

	$title = 'Mod Dashboard - ' . $site_name;
	if (isset($_GET["theme"])) {
		$output_html .= '<html data-stylesheet="' . htmlspecialchars($_GET['theme']) . '">';
	} else {
		$output_html .= '<html data-stylesheet="' . $current_theme . '">';	
	}
	$output_html .= '<head>';
	include $path . '/templates/header.php';
	$output_html .= '</head>';
	$output_html .= '<body class="frontpage">';
	include $path . '/templates/boardlist.php';
	$output_html .= '<div class="page-info"><h1>Dashbord</h1><div class="small">Try not to ruin everything.</div>';
	$output_html .= $logged_in_as;
	$output_html .= '</div>';
	$output_html .= $dashboard_notifications;
	$output_html .= '<br>';
	$output_html .= '<div class="box flex">';
	$output_html .= $mod_navigation;
	$output_html .= '<div class="container-right">';
	$output_html .= '<div class="box right">';
	$output_html .= '<h2>Content</h2>';
	$output_html .= '<div class="box-content">';
	$output_html .= '<p>Welcome to the moderator dashboard.</p>';
	$output_html .= '</div>';
	$output_html .= '</div>';
	$output_html .= '</div>';
	$output_html .= '<br>';
	$output_html .= '</div>';

	include $path . '/templates/footer.php';
	$output_html .= '</body>';
	$output_html .= '</html>';
	echo $output_html;
	exit();

?>