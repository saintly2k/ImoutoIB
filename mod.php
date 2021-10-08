<?php 

require 'require.php';


	//ALL MOD POST FORM ACTIONS CAN BE FOUND HERE
require $path . '/includes/mod-actions.php';

	//NAVIGATION
$mod_navigation =	'<div class="box left">';
$mod_navigation .=	'<h2>Navigation</h2>';
$mod_navigation .=	'<ul class="box-list">';

	//HOME
$mod_navigation .=	'<li><a href="' . $prefix_folder . '/mod.php"';
if ((!isset($_GET["page"])) || ($_GET["page"] == '')) {
	$mod_navigation .= 'class="active"';
}
$mod_navigation .=	'>Home</a></li>';

	//ACCOUNT
$mod_navigation .=	'<li><a href="' . $prefix_folder . '/mod.php?page=account"';
if ($_GET["page"] == 'account') {
	$mod_navigation .=	'class="active"';
}
$mod_navigation .=	'>Account</a></li>';

	//USERS
if ($config['mod']['edit_user'] <= $user_mod_level) {
	$mod_navigation .=	'<li><a href="' . $prefix_folder . '/mod.php?page=users"';
	if ($_GET["page"] == 'users') {
		$mod_navigation .=	'class="active"';
	}
	$mod_navigation .=	'>Manage Users</a></li>';
}

	//REPORTS
if ($config['mod']['reports'] <= $user_mod_level) {
	$mod_navigation .=	'<li><a href="' . $prefix_folder . '/mod.php?page=reports"';
	if ($_GET["page"] == 'reports') {
		$mod_navigation .=	'class="active"';
	}
	if (file_exists($path . '/' . $database_folder . '/reports/current.php')) {
		$reports = file_get_contents($path . '/' . $database_folder . '/reports/current.php');
	} else {
		$reports = 0;
	}
	$mod_navigation .=	'>Reports (' . $reports . ')</a></li>';
}
	//GLOBAL REPORTS
if ($config['mod']['global_reports'] <= $user_mod_level) {
	$mod_navigation .=	'<li><a href="' . $prefix_folder . '/mod.php?page=global_reports"';
	if ($_GET["page"] == 'global_reports') {
		$mod_navigation .=	'class="active"';
	}
	if (file_exists($path . '/' . $database_folder . '/reportsglobal/current.php')) {
		$reports_global = file_get_contents($path . '/' . $database_folder . '/reportsglobal/current.php');
	} else {
		$reports_global = 0;
	}
	$mod_navigation .=	'>Global Reports (' . $reports_global . ')</a></li>';
}

	//BANLIST
if ($config['mod']['ban'] <= $user_mod_level) {
	$mod_navigation .=	'<li><a href="' . $prefix_folder . '/mod.php?page=bans"';
	if ($_GET["page"] == 'bans') {
		$mod_navigation .=	'class="active"';
	}
	$mod_navigation .=	'>Manage Bans</a></li>';
}


$mod_navigation .=	'</ul>';
$mod_navigation .=	'</div>';

	//LOGOUT BUTTON
$logged_in_as = '<br>Logged in as: (ID:' . $user_id . ', Username: ' . $username . ', Level: ' . $user_mod_level . ')<br><form name="logout" action="' . $prefix_folder . '/mod.php" method="post"><input type="hidden" id="logout" name="logout" value="logout"><input type="Submit" value="Logout"></form>';

	//ABOVE DASHBOARD
	//add noticeboard + pm notification here maybe?
$dashboard_notifications = '<div class="main first"><h2>Moderator tools</h2>';
$dashboard_notifications .= '<p>Things like notices or messages may be here later.</p>';
$dashboard_notifications .= '</div>';

	//$dashboard_notifications = ''; //clear it out for now?



	//DASHBOARD
if ((!isset($_GET["page"])) || ($_GET["page"] == '')) {
	include $path . '/templates/mod/dashboard.php';
}

	//ACCOUNT PAGE
if ($_GET["page"] == 'account') {
	include $path . '/templates/mod/account.php';
}

	//USERS PAGE
if ($_GET["page"] == 'users') {
	include $path . '/templates/mod/users.php';
}

	//REPORTS PAGE
if ($_GET["page"] == 'reports') {
	include $path . '/templates/mod/reports.php';
}

	//GLOBAL REPORTS PAGE
if ($_GET["page"] == 'global_reports') {
	include $path . '/templates/mod/global_reports.php';
}

	//BANS PAGE
if ($_GET["page"] == 'bans') {
	include $path . '/templates/mod/bans.php';
}


//If literally none of the above activates.
	$title = 'Error! - ' . $site_name;
	if (isset($_GET['theme'])) {
		echo '<html data-stylesheet="'. htmlspecialchars($_GET['theme']) .'">';
	} else {
		echo '<html data-stylesheet="'. $current_theme .'">';	
	}
	echo '<head>';
	include $path . '/templates/header.html';
	echo '</head>';
	echo '<body class="frontpage">';
	//include $path . '/templates/boardlist.html';
	echo '<div class="message">Gomen nasai... Woah — Unknown Error!<br>Please leave a detailed bug report... Page may not exist, if this was unintended please let me know.</div>';
	//include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();

?>