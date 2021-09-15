<?php 

require dirname(__FILE__) . '/require.php';

//DONT FORGET TO CHECK LOGIN/SIGNUP PAGE
//ADD GPG LOGINS
//CHECK MOD PRIVILEGES, NOT SIGNED IN = "", USER/DEMODDED = 0, JANNY = 10, MOD = 20, BO = 30, ADMIN = 9001

//todo: get cookies, verify cookies, set mod power. if logged in false & post isset
$logged_in = false;

//CREATE FOLDER + DEFAULT USER
if (!file_exists(__dir__ . '/' . $database_folder . '/users')) {
	mkdir(__dir__ . '/' . $database_folder . '/users', 0755);

	$default_user = '<?php ';
	$default_user .= '$username = "admin"; ';
	$default_user .= '$password = "' . crypt('password' , $secure_hash) . '"; '; //reminder that changing hash in config will literally btfo'd all existing poster IDs and passwords lol
	$default_user .= '$gpg_key = ""; ';
	$default_user .= '$gpg_enabled = "0"; ';
	$default_user .= '$mod_level = "9001"; ';
	$default_user .= ' ?>';

	file_put_contents(__dir__ . '/' . $database_folder . '/users/0.php', $default_user); //create default admin user
	file_put_contents(__dir__ . '/' . $database_folder . '/users/counter.php', 0); //create user count

}

//LOGIN
if ($logged_in == false) {
	
	$title = 'Login - ' . $site_name;
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
	echo '<div class="page-info"><h1>Login Page</h1><div class="small">Permission required.</div></div><br><br>';
	echo '<div class="main first"><h2>Login.</h2>';
	echo '<p>
			<div id="post-form">
			<form name="login" action="' . $prefix_folder . '/mod.php" method="post">
				<table id="login" style="margin:auto;">
					<tr><th>Username</th><td><input type="text" name="username" size="25" maxlength="256" autocomplete="off" placeholder="Username"></td></tr>
					<tr><th>Password</th><td><input type="password" name="password" size="25" maxlength="256" autocomplete="off" placeholder="Password"></td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="post" value="Login" style="float: right;"></td></tr>
				</table>
			</form>
			</div>
		  </p>';
	echo '</div>';
	echo '<div class="message">Onii-Chan Powerlevel Required</div>';

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}


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