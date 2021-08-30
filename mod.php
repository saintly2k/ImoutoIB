<?php 

require dirname(__FILE__) . '/require.php';

//DONT FORGET TO CHECK LOGIN/SIGNUP PAGE
//ADD GPG LOGINS
//CHECK MOD PRIVILEGES, NOT SIGNED IN = "", USER/DEMODDED = 0, JANNY = 10, MOD = 20, BO = 30, ADMIN = 9001

//DASHBOARD
if ((!isset($_GET["page"])) || ($_GET["page"] == '')) {
	
	$title = 'Mod Dashboard - ' . $site_name;
	if (isset($_GET["theme"])) {
		echo '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
	} else {
		echo '<html data-stylesheet="'. $current_theme .'">';	
	}
	echo '<head>';
	include $path . '/templates/header.html';
	echo '</head>';
	echo '<body class="frontpage">';
	include $path . '/templates/boardlist.html';
	echo '<div class="page-info"><h1>Dashbord</h1><div class="small">Try not to ruin everything.</div></div><br><br>';
	echo '<div class="main first"><h2>Moderator tools</h2>';
	echo '<p>
			Things go here.
		  </p>';
	echo '</div>';
	echo '<div class="message">There is nothing here yet... No mods??!</div>';

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}




//If literally none of the above activates.
	$title = 'Error! - ' . $site_name;
	if (isset($_GET["theme"])) {
		echo '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
	} else {
		echo '<html data-stylesheet="'. $current_theme .'">';	
	}
	echo '<head>';
	include $path . '/templates/header.html';
	echo '</head>';
	echo '<body class="frontpage">';
	//include $path . '/templates/boardlist.html';
	echo '<div class="message">Gomen nasai... Woah — Unknown Error!<br>Please leave a detailed bug report...</div>';
	//include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();

?>