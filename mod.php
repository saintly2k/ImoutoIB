<?php 

require dirname(__FILE__) . '/require.php';


//add a bunch of post functions here before everything else


//DISMISS REPORT
if (isset($_POST['dismiss'])) {
	if ($config['mod']['reports'] > $user_mod_level) {
		error('You don\'t have permission to dismiss reports');
	}
	if (!in_Array($_POST['board'], $config['boardlist'])) {
		error('Invalid board');
	}
	if (!is_numeric(basename($_POST['report'], '.php'))) {
		error('Invalid report number');
	}
	if (!file_exists(__dir__ . '/' . $database_folder . '/reports/' . $_POST['board'] . '/' . $_POST['report'])) {
		error('This report doesn\'t exist. Maybe someone else dismissed it before you.');
	}
	//ok everything checks out, delete report.
	unlink(__dir__ . '/' . $database_folder . '/reports/' . $_POST['board'] . '/' . $_POST['report']);
	ReportCounter($database_folder, 'normal');
	//save to log?

}

//DISMISS GLOBAL
if (isset($_POST['dismiss_global'])) {
	if ($config['mod']['global_reports'] > $user_mod_level) {
		error('You don\'t have permission to dismiss reports');
	}
	if (!in_Array($_POST['board'], $config['boardlist'])) {
		error('Invalid board');
	}
	if (!is_numeric(basename($_POST['report'], '.php'))) {
		error('Invalid report number');
	}
	if (!file_exists(__dir__ . '/' . $database_folder . '/reportsglobal/' . $_POST['report'])) {
		error('This report doesn\'t exist. Maybe someone else dismissed it before you.');
	}
	//ok everything checks out, delete report.
	unlink(__dir__ . '/' . $database_folder . '/reportsglobal/' . $_POST['report']);
	ReportCounter($database_folder, 'global');
	//save to log?

}

//LOGOUT
if (isset($_POST['logout'])) {
	setcookie("mod_user", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	setcookie("mod_session", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	$logged_in = false;
}

//EDIT PASSWORD
if (isset($_POST['old-password'])) {
	//check requirements
	if (($_POST['old-password'] == '') || ($_POST['new-password'] == '') || ($_POST['new-password2'] == '')) {
		error('You must fill in all fields.');
	}
	if (crypt($_POST['old-password'], $password_salt) != $password) {
		error('Old password is incorrect.');
	}
	if ($_POST['new-password'] != $_POST['new-password2']) {
		error('New passwords don\'t match.');
	}
	if (strlen($_POST['new-password']) > 256) {
		error('Password too long. Maximum 256.');
	}
	if (strlen($_POST['new-password']) < 8) {
		error('Password too short. Minimum 8.');
	}
	//ok now change password
	$password_salt = crypt(md5(random_bytes(30)) , $secure_hash);
	$password = crypt($_POST['new-password'] , $password_salt);

	$user_info = file_get_contents(__dir__ . '/' . $database_folder . '/users/' . $username . '.php');
	$user_info = preg_replace('/\$password_salt = ".*?";/i', '$password_salt = "' . $password_salt . '";', $user_info);
	$user_info = preg_replace('/\$password = ".*?";/i', '$password = "' . $password . '";', $user_info);
	$user_info = preg_replace('/\$user_session = ".*?";/i', '$user_session = "";', $user_info); //clear outdated session
	file_put_contents(__dir__ . '/' . $database_folder . '/users/' . $username . '.php', $user_info);

	//ok we changed password now logout
	$logged_in = false;
	$changed_password = true;
	setcookie("mod_user", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
	setcookie("mod_session", null, time() - 3600,  $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
}

//CREATE USER
if (isset($_POST['create-user'])) {
	if ($user_mod_level < $config['mod']['edit_user']) {
		error('You don\'t have permission to edit users.');
	}
	if (!is_numeric($_POST['create-level']) || ($_POST['create-level'] > 9001) || ($_POST['create-level'] < 0) ) {
		error('Invalid mod level.');
	}
	if (!ctype_alnum($_POST['create-username'])) {
		error('Invalid username. Alphanumeric only.');
	}
	if (strlen($_POST['create-username']) > 32) {
		error('Username too long, Maximum 32.');
	}
	if (strlen($_POST['create-username']) < 2) {
		error('Username too short, Minimum 3.');
	}
	if (strlen($_POST['create-password']) > 256) {
		error('Password too long, Maximum 256.');
	}
	if (strlen($_POST['create-password']) < 8) {
		error('Password too short, Minimum 8.');
	}
	if ($_POST['create-password'] != $_POST['create-password2']) {
		error('Passwords don\'t match.');
	}
	$_POST['create-username'] = strtolower($_POST['create-username']); //set lowercase

	if (file_exists(__dir__ . '/' . $database_folder . '/users/' . $_POST['create-username'] . '.php')) {
		error('User already exists or is unavailable.');
	}

	$password_salt = crypt(md5(random_bytes(30)) , $secure_hash);
	$current_count = file_get_contents(__dir__ . '/' . $database_folder . '/users/counter.php');
	$new_count = $current_count + 1;

	$new_user = '<?php ';
	$new_user .= '$user_id = "' . $new_count . '"; ';
	$new_user .= '$username = "' . $_POST['create-username'] . '"; ';
	$new_user .= '$password_salt = "' . $password_salt . '"; ';
	$new_user .= '$password = "' . crypt($_POST['create-password'] , $password_salt) . '"; ';
	$new_user .= '$gpg_key = ""; '; 
	$new_user .= '$gpg_enabled = "0"; '; //if enabled, don't check password but instead send a gpg decryption test. use php session.
	$new_user .= '$user_mod_level = "' . $_POST['create-level'] . '"; ';
	$new_user .= '$user_mod_boards = "*"; '; //add board specifics or all.
	$new_user .= '$user_remember = "' . time() . '"; '; //add a +30 days check or delete session and go to login screen
	$new_user .= '$user_session = ""; '; //login session key, set on login.
	$new_user .= ' ?>';

	file_put_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['create-username'] . '.php', $new_user);
	file_put_contents(__dir__ . '/' . $database_folder . '/users/counter.php', $new_count); //+1 user id

	$user_created = true;
}

//EDIT USER
if (isset($_POST['edit-user'])) {
	if ($user_mod_level < $config['mod']['edit_user']) {
		error('You don\'t have permission to edit users.');
	}
	if (!is_numeric($_POST['edit-level']) || ($_POST['edit-level'] > 9001) || ($_POST['edit-level'] < 0) ) {
		error('Invalid mod level.');
	}
	if (!file_exists(__dir__ . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php')) {
		error('User doesn\'t exist.');
	}

	$check_user = file_get_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php');
	if (preg_match('/\$user_id = "0";/', $check_user) == true) {
		error('You cannot edit user ID 0.');
	}

	$edit_user = file_get_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php');
	$edit_user = preg_replace('/\$user_mod_level = "[0-9]+";/', '$user_mod_level = "' . $_POST['edit-level'] .'";', $edit_user);

	file_put_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php', $edit_user);

	$user_edited = true;
}

//DELETE USER
if (isset($_POST['delete-user'])) {
	if ($user_mod_level < $config['mod']['edit_user']) {
		error('You don\'t have permission to edit users.');
	}
	if (!file_exists(__dir__ . '/' . $database_folder . '/users/' . $_POST['delete-username'] . '.php')) {
		error('User doesn\'t exist.');
	}

	$check_user = file_get_contents(__dir__ . '/' . $database_folder . '/users/' . $_POST['delete-username'] . '.php');
	if (preg_match('/\$user_id = "0";/', $check_user) == true) {
		error('You cannot edit user ID 0.');
	}

	unlink(__dir__ . '/' . $database_folder . '/users/' . $_POST['delete-username'] . '.php');

	$user_deleted = true;
}

//DELETE BAN
if (isset($_POST['delete-ban'])) {
	if ($user_mod_level < $config['mod']['ban']) {
		error('You don\'t have permission to remove bans.');
	}
	if (!ctype_alnum($_POST['delete-ban-ip'])) {
		error('Invalid IP');
	}
	if (!file_exists(__dir__ . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip'] . '/' . $_POST['delete-ban-id'] . '.php')) {
		error('This ban doesn\'t exist.');
	}
	unlink(__dir__ . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip'] . '/' . $_POST['delete-ban-id'] . '.php');
	if (!glob(__dir__ . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip'] . '/*')) {
		rmdir(__dir__ . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip']); //Delete folder if no bans exist anymore. Expired bans count as existing.
	}
 
	$ban_removed = true;
}

//CREATE BAN
if (isset($_POST['create-ban'])) {
	if ($user_mod_level < $config['mod']['ban']) {
		error('You don\'t have permission to create bans.');
	}

	//check ban form requirements isnt manipulated (duration, reason, etc) and set stuff
	if (!isset($_POST['create-ban-expire'])){
		error('Ban expiry form not given.');
	}
	$ban_reason = phpClean($_POST['create-ban-reason']);
	$ban_expire = phpClean($_POST['create-ban-expire']);
	$ban_original_ip = phpClean($_POST['create-ban-ip']);
	if (strlen($ban_reason) > 256) {
		error('Ban reason too long. Maximum 256 characters.');
	}
	if ($ban_reason == '') {
		$ban_reason = 'No reason given.';
	}

	if ($ban_original_ip > 256) {
		error('Suspiciously long IP.');
	}

	//remove dots and slashes
	$new_ban['original_ip'] = $ban_original_ip;
	$new_ban['ip'] = preg_replace('/(\/|\.)/i','' , $_POST['create-ban-ip']); //remove dots and slashes from ip
	if (!ctype_alnum($new_ban['ip'])) {
		error('Invalid IP');
	}

	//create folder for bans if doesnt exist
	if (!file_exists($path . '/' . $database_folder . '/bans')) {
		mkdir($path . '/' . $database_folder . '/bans');
	}
	if (!file_exists($path . '/' . $database_folder . '/bans/' . $new_ban['ip'])) {
		mkdir($path . '/' . $database_folder . '/bans/' .$new_ban['ip']);
	}
	//create counter if doesnt exist
	if (!file_exists($path . '/' . $database_folder . '/bans/counter.php')) {
		file_put_contents($path . '/' . $database_folder . '/bans/counter.php', 0);
	}

	$new_ban['id'] = file_get_contents($path . '/' . $database_folder . '/bans/counter.php');

	$new_ban['time'] = time();
	$new_ban['duration'] = $ban_expire;

	if ($ban_expire == "warning") {
		$new_ban['is_active'] = "0";
	} else {
		$new_ban['is_active'] = "1";
	}
	$new_ban['is_read'] = "0"; //replace on read

	$create_ban = '<?php ';
	$create_ban .= '$ban[\'id\'] = "'.$new_ban['id'].'"; ';
	$create_ban .= '$ban[\'ip\'] = "'.$new_ban['ip'].'"; ';
	$create_ban .= '$ban[\'original_ip\'] = "'.$new_ban['original_ip'].'"; ';
	$create_ban .= '$ban[\'thread\'] = ""; ';
	$create_ban .= '$ban[\'reply\'] = ""; ';
	$create_ban .= '$ban[\'reason\'] = "'.$ban_reason.'"; ';
	$create_ban .= '$ban[\'post-filename\'] = ""; ';
	$create_ban .= '$ban[\'post-time\'] = ""; ';
	$create_ban .= '$ban[\'post-name\'] = ""; ';
	$create_ban .= '$ban[\'post-email\'] = ""; ';
	$create_ban .= '$ban[\'post-subject\'] = ""; ';
	$create_ban .= '$ban[\'post-body\'] = false; ';
	$create_ban .= '$ban[\'time\'] = "'.$new_ban['time'].'"; ';
	$create_ban .= '$ban[\'duration\'] = "'.$new_ban['duration'].'"; ';
	$create_ban .= '$ban[\'is_active\'] = "'.$new_ban['is_active'].'"; ';
	$create_ban .= '$ban[\'is_read\'] = "'.$new_ban['is_read'].'"; ';
	$create_ban .= '?>';

	file_put_contents($path . '/' . $database_folder . '/bans/' . $new_ban['ip'] . '/' . $new_ban['id'] . '.php', $create_ban); //save ban
	file_put_contents($path . '/' . $database_folder . '/bans/counter.php', $new_ban['id'] + 1); //increase counter

	if ($new_ban['duration'] == 'warning') {
		$warning_created = true;
	} else {
		$ban_created = true;
	}
}

//LOGGIN IN?
if (isset($_POST['username']) && isset($_POST['password'])) {
	if ($_POST['username'] == "") {
		error('No username given.');
	}
	if ($_POST['username'] == "counter" || ctype_alnum($_POST['username']) != true) {
		error('Invalid Username.');
	}
	$_POST['username'] = strtolower($_POST['username']);
	if (!file_exists(__dir__ . '/' . $database_folder . '/users/' . $_POST['username'] . '.php')) {
		error('User doesn\'t exist.');
	}

	include __dir__ . '/' . $database_folder . '/users/' . $_POST['username'] . '.php';

	if (crypt($_POST['password'] , $password_salt) != $password) {
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

	$password_salt = crypt(md5(random_bytes(30)) , $secure_hash);

	$default_user = '<?php ';
	$default_user .= '$user_id = "0"; ';
	$default_user .= '$username = "admin"; ';
	$default_user .= '$password_salt = "' . $password_salt . '"; ';
	$default_user .= '$password = "' . crypt('password' , $password_salt) . '"; ';
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

//LOGIN PAGE
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

	if ($changed_password == true) {
		echo '<div class="message" style="margin-top:0;">Password has been changed.</div>';
	} else {
		echo '<div class="message"></div>';
	}

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}

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
	if (file_exists(__dir__ . '/' . $database_folder . '/reports/current.php')) {
		$reports = file_get_contents(__dir__ . '/' . $database_folder . '/reports/current.php');
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
	if (file_exists(__dir__ . '/' . $database_folder . '/reportsglobal/current.php')) {
		$reports_global = file_get_contents(__dir__ . '/' . $database_folder . '/reportsglobal/current.php');
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
}

//ACCOUNT PAGE
if ($_GET["page"] == 'account') {
	
	$title = 'Account - ' . $site_name;
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
	echo $logged_in_as;
	echo '</div>';
	echo $dashboard_notifications;
	echo '<br>';
	echo '<div class="box flex">';
	echo $mod_navigation;
	echo '<div class="container-right">';
	echo '<div class="box right">';
	echo '<h2>Account</h2>';
	echo '<div class="box-content">';
	echo '<p>';
	echo 'Username: ' . $username;
	echo '</p>';

	//CHANGE PASSWORD
	echo '<details><summary>Edit Password</summary>';
	echo '		<form name="edit-password" action="' . $prefix_folder . '/mod.php" method="post">
				<table id="post-form" style="width:initial;">
					<tr><th>Current Password:</th><td><input type="password" name="old-password" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th>New Password:</th><td><input type="password" name="new-password" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th>New Password x2:</th><td><input type="password" name="new-password2" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="post" value="Edit Password" style="float: right;"></td></tr>
				</table>
			</form>';
	echo '</details>';
	echo '</div>';
	echo '</div>';

	echo '</div>';
	echo '<br>';
	echo '</div>';

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}

//USERS PAGE
if ($_GET["page"] == 'users') {
	if ($user_mod_level < $config['mod']['edit_user']) {
		error('You don\'t have permission to view this page.');
	}
	$title = 'Manage Users - ' . $site_name;
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
	echo $logged_in_as;
	echo '</div>';
	echo $dashboard_notifications;
	echo '<br>';
	echo '<div class="box flex">';
	echo $mod_navigation;
	echo '<div class="container-right">';

	echo '<div class="box right">';
	echo '<h2>Create User</h2>';
	echo '<div class="box-content">';
	echo '<p>';
	echo '<details><summary>Create User</summary>';
	//CREATE USER
	echo '<form name="create-user" action="' . $prefix_folder . '/mod.php?page=users" method="post">
				<table id="post-form" style="width:initial;">
					<tbody><tr><th>Username:</th><td><input type="text" name="create-username" size="25" maxlength="32" autocomplete="off" placeholder="Username" required></td></tr>
					<tr><th>Password:</th><td><input type="password" name="create-password" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th>Password x2:</th><td><input type="password" name="create-password2" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th>User Level:</th><td>
					<select name="create-level">
					  <option value="9001">Admin (9001)</option>
					  <option value="40">Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0" selected>User (0)</option>
					</select>
					</td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="create-user" value="Create User" style="float: right;"></td></tr>
				</tbody></table>
			</form>';
	echo '</details>';
	echo '</p>';
	echo '</div>';
	echo '</div>';

	echo '<br>';
	echo '<div class="box right">';
	echo '<h2>Manage Users</h2>';
	echo '<div class="box-content">';
	
	//foreach
	
	echo '<table><thead> <td>ID</td> <td>Username</td> <td>Mod Level</td> <td>Actions</td></thead>';
	echo '<tbody>';

	//TO DO: multiarray and sort by ID, alternatively use JS.
	// I should also first take the admins, sort them by id, then the mods by id, then the jannies by id, etc.
	// Basically sorted by mod level, and each modlevel sorted by ID.

	$userlist = glob(__dir__ . '/' . $database_folder . '/users/*'); 
	foreach ($userlist as $user) {
		if (basename($user) == 'counter.php') {
			continue; //not a user, go next iteration
		}
		include $user;
		echo '<tr>';
		echo '<td>' . $user_id . '</td>';
		echo '<td>' . $username . '</td>';
		echo '<td>';
		switch ($user_mod_level) {
			case 9001:
				echo 'Admin';
				break;
			case 40:
				echo 'Mod';
				break;
			case 10:
				echo 'Janitor';
				break;
			case 0:
				echo 'User';
				break;
			default:
				echo 'Unknown';
				break;
		}
		echo ' (' . $user_mod_level . ')</td>';
		echo '<td><details><summary>More</summary>';
		echo '<details><summary style="font-size:smaller;">Edit</summary>';

		//EDIT USER
		echo '<form name="edit-user" action="' . $prefix_folder . '/mod.php?page=users" method="post">
				<table id="post-form" style="width:initial;">
					<tbody><tr><th>Username:</th><td><input type="hidden" name="edit-username" value="' . $username . '"><input type="text" name="edit-username-view" size="25" maxlength="32" autocomplete="off" value="' . $username . '" disabled></td></tr>
					<!---<tr><th>Password:</th><td><input type="password" name="edit-password" size="25" maxlength="256" autocomplete="off" placeholder="Leave Empty To Not Change"></td></tr>
					<tr><th>Password x2:</th><td><input type="password" name="edit-password2" size="25" maxlength="256" autocomplete="off" placeholder="Leave Empty To Not Change"></td></tr>-->
					<tr><th>User Level:</th><td>
					<select name="edit-level">';

		switch ($user_mod_level) {
			case 9001:
				echo '<option value="9001" selected>Admin (9001)</option>
					  <option value="40">Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0">User (0)</option>';
				break;
			case 40:
				echo '<option value="9001">Admin (9001)</option>
					  <option value="40" selected>Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0">User (0)</option>';
				break;
			case 10:
				echo '<option value="9001">Admin (9001)</option>
					  <option value="40">Moderator (40)</option>
					  <option value="10" selected>Janitor (10)</option>
					  <option value="0">User (0)</option>';
				break;
			default:
				echo '<option value="9001">Admin (9001)</option>
					  <option value="40" selected>Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0" selected>User (0)</option>';
				break;
		}

		echo '			</select>
					</td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="edit-user" value="Edit User" style="float: right;"></td></tr>
				</tbody></table>
			</form>';

		echo '</details>';
		echo '<details><summary style="font-size:smaller;">Delete</summary><details><summary>Are you sure you want to delete this user ('.$username.')?</summary><details><summary>Yes!</summary><form name="delete-user" action="' . $prefix_folder . '/mod.php?page=users" method="post"><input type="hidden" id="delete-username" name="delete-username" value="' . $username . '"><input type="Submit" name="delete-user" value="Delete"></form></details></details></details>';
		echo '</details></td>';
		echo '</tr>';
		
	}
	echo '</tbody></table>';
	
	echo '</div>';
	echo '</div>';

	echo '</div>';
	echo '<br>';
	echo '</div>';

	if ($user_created == true) {
		echo '<div class="message" style="margin-top:0;">User created.</div>';
	}
	if ($user_edited == true) {
		echo '<div class="message" style="margin-top:0;">User edited.</div>';
	}
	if ($user_deleted == true) {
		echo '<div class="message" style="margin-top:0;">User deleted.</div>';
	}

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}

//REPORTS PAGE
if ($_GET["page"] == 'reports') {
	if ($user_mod_level < $config['mod']['reports']) {
		error('You don\'t have permission to view this page.');
	}

	if (!file_exists($path . '/' . $database_folder . '/reports')) {
		mkdir($path . '/' . $database_folder . '/reports');
	}

	//recount
	ReportCounter($database_folder, 'normal');

	$title = 'Reports - ' . $site_name;
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
	echo $logged_in_as;
	echo '</div>';
	echo $dashboard_notifications;
	echo '<br>';
	echo '<div class="box flex">';
	echo $mod_navigation;
	echo '<div class="container-right">';
	echo '<div class="box right">';
	echo '<h2>Reports</h2>';
	echo '<div class="box-content">';

	echo '<table style="width:100%">';
	echo '<thead> <td>Board</td> <td>Post</td> <td>Report IP</td> <td>Reason</td> <td>View</td> <td>Actions</td>';
	echo '<tbody>';

	//FIND REPORTS
	$report_boards = glob(__dir__ . '/' . $database_folder . '/reports/*', GLOB_ONLYDIR); //find boards

	foreach ($report_boards as $board ) { //for each board
		$reports = [];
		$reports = glob($board . "/*"); //find reports
			foreach ($reports as $report) { //for each report
				if (is_numeric(basename($report, '.php'))) {
						include $board . '/' . basename($report);

						//dismiss report if thread/reply no longer exists and go to next report in loop
						if ((($report_thread == $report_reply) && (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . basename($board) . '/' . $report_thread))) || (($report_thread != $report_reply) && (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . basename($board) . '/' . $report_thread . '/' . $report_reply . '.php')))) {
							unlink($report);
							continue;
						}

						echo '<tr>'; 
						echo '<td>/' . basename($board) . '/</td>';
						echo '<td>' . $report_reply . '@' . $report_thread . '</td>';
						if ($user_mod_level >= $config['mod']['ip']) {
							echo '<td>' . $report_ip . '</td>';
						} else {
							echo '<td>No Perm</td>';
						}
						echo '<td title="' . $report_reason . '"style="white-space:pre;word-wrap:break-word;max-width:150px;overflow:hidden;text-overflow:ellipsis">' . $report_reason . '</td>';
						echo '<td><a href="' . $prefix_folder . '/' . $main_file . '?board='. basename($board) . '&thread=' . $report_thread . '#' . $report_reply . '" target="_blank">View</a></td>';
						echo '<td><details><summary>More</summary>';

						echo '	<form name="dismiss-report" action="' . $prefix_folder . '/mod.php?page=reports" method="post">
								<input type="hidden" name="board" value="' . basename($board) . '">
								<input type="hidden" name="report" value="' . basename($report) . '">
								<input type="submit" name="dismiss" value="Dismiss"></td>
								</form>';

						echo '</details><td>';
						echo '</tr>';
				}
			}
	}
	echo '</tbody>';
	echo '</table>';

	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<br>';
	echo '</div>';

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}

//GLOBAL REPORTS PAGE
if ($_GET["page"] == 'global_reports') {
	if ($user_mod_level < $config['mod']['global_reports']) {
		error('You don\'t have permission to view this page.');
	}

	if (!file_exists($path . '/' . $database_folder . '/reportsglobal')) {
		mkdir($path . '/' . $database_folder . '/reportsglobal');
	}

	//recount
	ReportCounter($database_folder, 'global');

	$title = 'Global Reports - ' . $site_name;
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
	echo $logged_in_as;
	echo '</div>';
	echo $dashboard_notifications;
	echo '<br>';
	echo '<div class="box flex">';
	echo $mod_navigation;
	echo '<div class="container-right">';
	echo '<div class="box right">';
	echo '<h2>Global Reports</h2>';
	echo '<div class="box-content">';

	echo '<table style="width:100%">';
	echo '<thead> <td>Board</td> <td>Post</td> <td>Report IP</td> <td>Reason</td> <td>View</td> <td>Actions</td>';
	echo '<tbody>';

	//FIND REPORTS
		$reports = [];
		$reports = glob(__dir__ . '/' . $database_folder . '/reportsglobal/*'); //find reports
			foreach ($reports as $report) { //for each report
				if (is_numeric(basename($report, '.php'))) {
						include $report;

						//dismiss report if thread/reply no longer exists and go to next report in loop
						if ((($report_thread == $report_reply) && (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . $report_board . '/' . $report_thread))) || (($report_thread != $report_reply) && (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . $report_board . '/' . $report_thread . '/' . $report_reply . '.php')))) {
							unlink($report);
							continue;
						}

						echo '<tr>'; 
						echo '<td>/' . $report_board . '/</td>';
						echo '<td>' . $report_reply . '@' . $report_thread . '</td>';
						if ($user_mod_level >= $config['mod']['ip']) {
							echo '<td>' . $report_ip . '</td>';
						} else {
							echo '<td>No Perm</td>';
						}
						echo '<td title="' . $report_reason . '"style="white-space:pre;word-wrap:break-word;max-width:150px;overflow:hidden;text-overflow:ellipsis">' . $report_reason . '</td>';
						echo '<td><a href="' . $prefix_folder . '/' . $main_file . '?board='. $report_board . '&thread=' . $report_thread . '#' . $report_reply . '" target="_blank">View</a></td>';
						echo '<td><details><summary>More</summary>';

						echo '	<form name="dismiss-report-global" action="' . $prefix_folder . '/mod.php?page=global_reports" method="post">
								<input type="hidden" name="board" value="' . $report_board . '">
								<input type="hidden" name="report" value="' . basename($report) . '">
								<input type="submit" name="dismiss_global" value="Dismiss"></td>
								</form>';

						echo '</details><td>';
						echo '</tr>';
				}
			}
	echo '</tbody>';
	echo '</table>';

	echo '</div>';
	echo '</div>';
	echo '</div>';
	echo '<br>';
	echo '</div>';

	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}

//BANS PAGE
if ($_GET["page"] == 'bans') {
	if ($user_mod_level < $config['mod']['ban']) {
		error('You don\'t have permission to view this page.');
	}

	if (!file_exists($path . '/' . $database_folder . '/bans')) {
		mkdir($path . '/' . $database_folder . '/bans');
	}

	$title = 'Manage Bans - ' . $site_name;
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
	echo $logged_in_as;
	echo '</div>';
	echo $dashboard_notifications;
	echo '<br>';
	echo '<div class="box flex">';
	echo $mod_navigation;
	echo '<div class="container-right">';

	echo '<div class="box right">';
	echo '<h2>Ban IP</h2>';
	echo '<div class="box-content">';
	echo '<p>';
	echo '<details><summary>Ban IP</summary>';
	echo '<form name="create-ban" action="' . $prefix_folder . '/mod.php?page=bans" method="post">
				<table id="post-form" style="width:initial;">
					<tbody><tr><th>IP:</th><td><input type="text" name="create-ban-ip" size="25" maxlength="32" autocomplete="off" placeholder="IP (hash)" required></td></tr>
					<tr><th>Reason:</th><td><input type="text" name="create-ban-reason" size="25" maxlength="256" autocomplete="off" placeholder="Reason" required></td></tr>
					<tr><th>Duration:</th><td>
					<select name="create-ban-expire">
					  <option value="permanent">Permanent</option>
					  <option value="31104000">1 Year</option>
					  <option value="7776000">3 Months</option>
					  <option value="2592000">1 Month</option>
					  <option value="1209600">2 Weeks</option>
					  <option value="604800">1 Week</option>
					  <option value="259200">3 Days</option>
					  <option value="86400">1 Day</option>
					  <option value="3600">1 Hour</option>
					  <option value="warning" selected>Warning</option>
					</select>
					</td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="create-ban" value="Create Ban" style="float: right;"></td></tr>
				</tbody></table>
			</form>';
	echo '</details>';
	echo '</p>';
	echo '</div>';
	echo '</div>';

	echo '<br>';
	echo '<div class="box right">';
	echo '<h2>Manage Bans</h2>'; //at some point i will need to rewrite this+reports+users to have pages if it grows large!!!
	echo '<div class="box-content">';
	
	//foreach
	
	echo '<table><thead> <td>ID</td> <td>IP</td> <td>Reason</td> <td>Expires</td> <td>Read</td> <td>Actions</td></thead>';
	echo '<tbody>';

	//TO DO: multiarray and sort by ID, alternatively use JS.
	// I should also first take the admins, sort them by id, then the mods by id, then the jannies by id, etc.
	// Basically sorted by mod level, and each modlevel sorted by ID.

	$banfolder = glob(__dir__ . '/' . $database_folder . '/bans/*', GLOB_ONLYDIR); 
	$banlist_full = [];

	foreach ($banfolder as $banfolder) { //for each folder
		foreach (glob($banfolder . '/*') as $banfile) { //for each file
			if (!is_numeric(basename($banfile, '.php'))) { //not a ban
				continue;
			}
			include $banfile;

			if ($ban['duration'] == 'warning') {
				echo '<tr style="text-decoration:line-through;">';
			} elseif (($ban['duration'] != 'permanent') && (($ban['time'] + $ban['duration']) < time())) { //if warning or expired
				echo '<tr style="text-decoration:line-through;">';
			} else {
				echo '<tr>';
			}

			echo '<td>' . $ban['id'] . '</td>';
			echo '<td>' . $ban['original_ip'] . '</td>';
			echo '<td title="' . $ban['reason'] . '"style="white-space:pre;word-wrap:break-word;max-width:150px;overflow:hidden;text-overflow:ellipsis">' . $ban['reason'] . '</td>';
			
			if ($ban['duration'] == 'warning') {
				echo '<td>---------</td>';
			} elseif ($ban['duration'] == 'permanent') {
				echo '<td>Never</td>';
			} elseif (($ban['time'] + $ban['duration']) < time()) {
				echo '<td>'. timeago($ban['time'] + $ban['duration']) .'</td>';
			} else {
				echo '<td>'. timeuntil($ban['time'] + $ban['duration']) .'</td>';
			}
			
			echo '<td>' . $ban['is_read'] . '</td>';
			echo '<td>';
			echo '<details><summary>More</summary>';

			if ($ban["post-body"] != false) { //manual ban or not?
				echo '<details><summary class="small">View</summary>'; //see post that caused ban
				echo '<div class="post reply banned"><div class="post-info">';
				if ($ban['post-subject'] != '') {
				echo '<span class="subject">'.$ban['post-subject'].'&nbsp;</span>';
				}
				if ($ban['post-email'] != '') {
					echo '<span class="name"><a href="mailto:'.$ban['post-email'].'">'.$ban['post-name'].'</a>&nbsp;</span>';
				} else {
					echo '<span class="name">'.$ban['post-name'].'&nbsp;</span>';
				}
				
				echo '<span class="post-time" data-tooltip="'.timeConvert($ban['post-time'], $time_method_hover).'" data-timestamp="'.$ban['post-time'].'">'.timeConvert($ban['post-time'], $time_method).'&nbsp;</span>';
				echo '<span class="post-number">No.'.$ban['reply'].'</span>';
				echo '</div><blockquote class="post-content">'.$ban['post-body'].'</blockquote></div>';
				echo '</details>'; //"view file"
			} else {
				echo 'Manual ban.';
			}

			//delete
			echo '<details><summary class="small">Delete</summary><details><summary>Are you sure you want to remove this ban?</summary>';
			echo '	<form name="delete-ban" action="' . $prefix_folder . '/mod.php?page=bans" method="post">
								<input type="hidden" name="delete-ban-ip" value="' . $ban['ip'] . '">
								<input type="hidden" name="delete-ban-id" value="' . $ban['id'] . '">
								<input type="submit" name="delete-ban" value="Delete"></td>
								</form>';
			echo '</details></details>';

			echo '</details></td>';
			echo '<tr>';
		}
	}

	echo '</tbody></table>';
	
	echo '</div>';
	echo '</div>';

	echo '</div>';
	echo '<br>';
	echo '</div>';

	if ($ban_removed == true) {
		echo '<div class="message" style="margin-top:0;">Ban has been deleted.</div>';
	}
	if ($ban_created == true) {
		echo '<div class="message" style="margin-top:0;">Ban has been created.</div>';
	}
	if ($warning_created == true) {
		echo '<div class="message" style="margin-top:0;">Warning has been created.</div>';
	}

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
	echo '<div class="message">Gomen nasai... Woah — Unknown Error!<br>Please leave a detailed bug report... Page may not exist, if this was unintended please let me know.</div>';
	//include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();

?>