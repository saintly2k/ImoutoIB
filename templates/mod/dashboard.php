<?php

	$title = 'Mod Dashboard - ' . $site_name;
	if (isset($_GET["theme"])) {
		echo '<html data-stylesheet="' . htmlspecialchars($_GET['theme']) . '">';
	} else {
		echo '<html data-stylesheet="' . $current_theme . '">';	
	}
	echo '<head>';
	include $path . '/templates/header.html';
	echo '</head>';
	echo '<body class="frontpage">';
	include $path . '/templates/boardlist.html';
	echo '<div class="page-info"><h1>Dashbord</h1><div class="small">Try not to ruin everything.</div>';
	echo $logged_in_as;
	echo '</div>';
	echo $dashboard_notifications;
	echo '<br>';
	echo '<div class="box flex">';
	echo $mod_navigation;
	echo '<div class="container-right">';
	echo '<div class="box right">';
	echo '<h2>Content</h2>';
	echo '<div class="box-content">';
	echo '<p>Welcome to the moderator dashboard.</p>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<br>';
	echo '</div>';

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();

?>