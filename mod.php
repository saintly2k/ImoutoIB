<?php 

require dirname(__FILE__) . '/require.php';

//DONT FORGET TO CHECK LOGIN/SIGNUP PAGE
//ADD GPG LOGINS
//CHECK MOD PRIVILEGES, NOT SIGNED IN = "", USER/DEMODDED = 0, JANNY = 10, MOD = 20, BO = 30, ADMIN = 9001

//todo: get cookies, verify cookies, set mod power. if logged in false & post isset

if (isset($_POST['logout'])) {
	setcookie("mod_user", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	setcookie("mod_session", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	$logged_in = false;
}

//todo: check mod cookie in require.php

if (isset($_POST['username']) && isset($_POST['password'])) {
	if ($_POST['username'] == "") {
		error('No username given.');
	}
	if ($_POST['username'] == "counter" || ctype_alnum($_POST['username']) != true) {
		error('Invalid Username.');
	}
	if (!file_exists(__dir__ . '/' . $database_folder . '/users/' . $_POST['username'] . '.php')) {
		error('User doesn\'t exist.');
	}

	include __dir__ . '/' . $database_folder . '/users/' . $_POST['username'] . '.php';

	if (crypt($_POST['password'] , $secure_hash) != $password) {
		error('Wrong password.');
	}

	$new_session = crypt(md5(random_bytes(10) . $_POST['password']) , $secure_hash);

	//set session in user file
	setcookie("mod_user", $_POST['username'], 0,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true); //not bothering setting expiry, they'll be replaced anyways if old.
	setcookie("mod_session", $new_session, 0,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);

	if (isset($_POST['remember'])) {
		$remember_time = time(); //basically just says when the login session was created
	} else {
		$remember_time = time() + 2505600; // remember time +29days (for 1day login)
	}

	//todo: set session in user file
	//todo: set remember in user file

	$user_info = file_get_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['username'] . '.php');
	$user_info = preg_replace('/\$user_remember = ".*?";/i', '$user_remember = "' . $remember_time . '";', $user_info);
	$user_info = preg_replace('/\$user_session = ".*?";/i', '$user_session = "' . $new_session . '";', $user_info);
	file_put_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['username'] . '.php', $user_info);

	$logged_in = true;
}

//CREATE FOLDER + DEFAULT USER
if (!file_exists(__dir__ . '/' . $database_folder . '/users')) {
	mkdir(__dir__ . '/' . $database_folder . '/users', 0755);

	$default_user = '<?php ';
	$default_user .= '$user_id = "0"; ';
	$default_user .= '$username = "admin"; ';
	$default_user .= '$password = "' . crypt('password' , $secure_hash) . '"; '; //reminder that changing hash in config will literally btfo'd all existing poster IDs and passwords lol
	$default_user .= '$gpg_key = ""; '; 
	$default_user .= '$gpg_enabled = "0"; '; //if enabled, don't check password but instead send a gpg decryption test. use php session.
	$default_user .= '$user_mod_level = "9001"; ';
	$default_user .= '$user_mod_boards = "*"; '; //add board specifics or all.
	$default_user .= '$user_remember = "' . time() . '"; '; //add a +30 days check or delete session and go to login screen
	$default_user .= '$user_session = ""; '; //login session key, set on login.
	$default_user .= ' ?>';

	file_put_contents(__dir__ . '/' . $database_folder . '/users/admin.php', $default_user); //create default admin user
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
					<tr><th style="visibility:hidden;"></th><td><input type="checkbox" id="remember" name="remember"
         checked><label for="remember">Remember Me</label><input type="submit" name="post" value="Login" style="float: right;"></td></tr>
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

//Navigation


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
	echo '<div class="page-info"><h1>Dashbord</h1><div class="small">Try not to ruin everything.</div>';
	echo '<br>Logged in as: (ID:' . $user_id . ', Username: ' . $username . ', Level: ' . $user_mod_level . ')<br><form name="logout" action="' . $prefix_folder . '/mod.php" method="post"><input type="hidden" id="logout" name="logout" value="logout"><input type="Submit" value="Logout"></form>';
	echo '</div>';
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