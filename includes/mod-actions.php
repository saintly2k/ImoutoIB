<?php

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
    if (!file_exists($path . '/' . $database_folder . '/reports/' . $_POST['board'] . '/' . $_POST['report'])) {
        error('This report doesn\'t exist. Maybe someone else dismissed it before you.');
    }
    //ok everything checks out, delete report.
    unlink($path . '/' . $database_folder . '/reports/' . $_POST['board'] . '/' . $_POST['report']);
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
    if (!file_exists($path . '/' . $database_folder . '/reportsglobal/' . $_POST['report'])) {
        error('This report doesn\'t exist. Maybe someone else dismissed it before you.');
    }
    //ok everything checks out, delete report.
    unlink($path . '/' . $database_folder . '/reportsglobal/' . $_POST['report']);
    ReportCounter($database_folder, 'global');
    //save to log?

}

//LOGOUT
if (isset($_POST['logout'])) {
    setcookie("mod_user", "", time() - 3600, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
    setcookie("mod_session", "", time() - 3600, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
    $logged_in = false;
    $logged_in_as = false;
    $mod_level = 0;
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
    $password_salt = crypt(md5(random_bytes(30)), $secure_hash);
    $password = crypt($_POST['new-password'], $password_salt);

    $user_info = file_get_contents($path . '/' . $database_folder . '/users/' . $username . '.php');
    $user_info = preg_replace('/\$password_salt = ".*?";/i', '$password_salt = "' . $password_salt . '";', $user_info);
    $user_info = preg_replace('/\$password = ".*?";/i', '$password = "' . $password . '";', $user_info);
    $user_info = preg_replace('/\$user_session = ".*?";/i', '$user_session = "";', $user_info); //clear outdated session
    file_put_contents($path . '/' . $database_folder . '/users/' . $username . '.php', $user_info);

    //ok we changed password now logout
    $logged_in = false;
    $changed_password = true;
    setcookie("mod_user", null, time() - 3600, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
    setcookie("mod_session", null, time() - 3600, $cookie_location, $domain, isset($_SERVER["HTTPS"]), true);
}

//CREATE USER
if (isset($_POST['create-user'])) {
    if ($user_mod_level < $config['mod']['edit_user']) {
        error('You don\'t have permission to edit users.');
    }
    if (!is_numeric($_POST['create-level']) || ($_POST['create-level'] > 9001) || ($_POST['create-level'] < 0)) {
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

    if (file_exists($path . '/' . $database_folder . '/users/' . $_POST['create-username'] . '.php')) {
        error('User already exists or is unavailable.');
    }

    $password_salt = crypt(md5(random_bytes(30)), $secure_hash);
    $current_count = file_get_contents($path . '/' . $database_folder . '/users/counter.php');
    $new_count = $current_count + 1;

    $new_user = '<?php ';
    $new_user .= '$user_id = "' . $new_count . '"; ';
    $new_user .= '$username = "' . $_POST['create-username'] . '"; ';
    $new_user .= '$password_salt = "' . $password_salt . '"; ';
    $new_user .= '$password = "' . crypt($_POST['create-password'], $password_salt) . '"; ';
    $new_user .= '$gpg_key = ""; ';
    $new_user .= '$gpg_enabled = "0"; '; //if enabled, don't check password but instead send a gpg decryption test. use php session.
    $new_user .= '$user_mod_level = "' . $_POST['create-level'] . '"; ';
    $new_user .= '$user_mod_boards = "*"; '; //add board specifics or all.
    $new_user .= '$user_remember = "' . time() . '"; '; //add a +30 days check or delete session and go to login screen
    $new_user .= '$user_session = ""; '; //login session key, set on login.
    $new_user .= ' ?>';

    file_put_contents($path . '/' . $database_folder . '/users/' . $_POST['create-username'] . '.php', $new_user);
    file_put_contents($path . '/' . $database_folder . '/users/counter.php', $new_count); //+1 user id

    $user_created = true;
}

//EDIT USER
if (isset($_POST['edit-user'])) {
    if ($user_mod_level < $config['mod']['edit_user']) {
        error('You don\'t have permission to edit users.');
    }
    if (!is_numeric($_POST['edit-level']) || ($_POST['edit-level'] > 9001) || ($_POST['edit-level'] < 0)) {
        error('Invalid mod level.');
    }
    if (!file_exists($path . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php')) {
        error('User doesn\'t exist.');
    }

    $check_user = file_get_contents($path . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php');
    if (preg_match('/\$user_id = "0";/', $check_user) == true) {
        error('You cannot edit user ID 0.');
    }

    $edit_user = file_get_contents($path . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php');
    $edit_user = preg_replace('/\$user_mod_level = "[0-9]+";/', '$user_mod_level = "' . $_POST['edit-level'] . '";', $edit_user);

    file_put_contents($path . '/' . $database_folder . '/users/' . $_POST['edit-username'] . '.php', $edit_user);

    $user_edited = true;
}

//DELETE USER
if (isset($_POST['delete-user'])) {
    if ($user_mod_level < $config['mod']['edit_user']) {
        error('You don\'t have permission to edit users.');
    }
    if (!file_exists($path . '/' . $database_folder . '/users/' . $_POST['delete-username'] . '.php')) {
        error('User doesn\'t exist.');
    }

    $check_user = file_get_contents($path . '/' . $database_folder . '/users/' . $_POST['delete-username'] . '.php');
    if (preg_match('/\$user_id = "0";/', $check_user) == true) {
        error('You cannot edit user ID 0.');
    }

    unlink($path . '/' . $database_folder . '/users/' . $_POST['delete-username'] . '.php');

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
    if (!file_exists($path . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip'] . '/' . $_POST['delete-ban-id'] . '.php')) {
        error('This ban doesn\'t exist.');
    }
    unlink($path . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip'] . '/' . $_POST['delete-ban-id'] . '.php');
    if (!glob($path . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip'] . '/*')) {
        rmdir($path . '/' . $database_folder . '/bans/' . $_POST['delete-ban-ip']); //Delete folder if no bans exist anymore. Expired bans count as existing.
    }

    $ban_removed = true;
}

//CREATE BAN
if (isset($_POST['create-ban'])) {
    if ($user_mod_level < $config['mod']['ban']) {
        error('You don\'t have permission to create bans.');
    }

    //check ban form requirements isnt manipulated (duration, reason, etc) and set stuff
    if (!isset($_POST['create-ban-expire'])) {
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
    $new_ban['ip'] = preg_replace('/(\/|\.)/i', '', $_POST['create-ban-ip']); //remove dots and slashes from ip
    if (!ctype_alnum($new_ban['ip'])) {
        error('Invalid IP');
    }

    //create folder for bans if doesnt exist
    if (!file_exists($path . '/' . $database_folder . '/bans')) {
        mkdir($path . '/' . $database_folder . '/bans');
    }
    if (!file_exists($path . '/' . $database_folder . '/bans/' . $new_ban['ip'])) {
        mkdir($path . '/' . $database_folder . '/bans/' . $new_ban['ip']);
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
    $create_ban .= '$ban[\'id\'] = "' . $new_ban['id'] . '"; ';
    $create_ban .= '$ban[\'ip\'] = "' . $new_ban['ip'] . '"; ';
    $create_ban .= '$ban[\'original_ip\'] = "' . $new_ban['original_ip'] . '"; ';
    $create_ban .= '$ban[\'thread\'] = ""; ';
    $create_ban .= '$ban[\'reply\'] = ""; ';
    $create_ban .= '$ban[\'reason\'] = "' . $ban_reason . '"; ';
    $create_ban .= '$ban[\'post-filename\'] = ""; ';
    $create_ban .= '$ban[\'post-time\'] = ""; ';
    $create_ban .= '$ban[\'post-name\'] = ""; ';
    $create_ban .= '$ban[\'post-email\'] = ""; ';
    $create_ban .= '$ban[\'post-subject\'] = ""; ';
    $create_ban .= '$ban[\'post-body\'] = false; ';
    $create_ban .= '$ban[\'time\'] = "' . $new_ban['time'] . '"; ';
    $create_ban .= '$ban[\'duration\'] = "' . $new_ban['duration'] . '"; ';
    $create_ban .= '$ban[\'is_active\'] = "' . $new_ban['is_active'] . '"; ';
    $create_ban .= '$ban[\'is_read\'] = "' . $new_ban['is_read'] . '"; ';
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

    if ($captcha_required == true) {
        if (isset($_POST['captcha'])) {
            session_start();
            if (($captcha_required == true) && (isset($_SESSION["captcha_text"]) && $_SESSION['captcha_text'] != strtolower($_POST['captcha']))) {
                error('Wrong captcha.');
            } else {
                session_destroy();
            }
        } else {
            error('No captcha entered.');
        }
    }

    if ($_POST['username'] == "") {
        error('No username given.');
    }
    if ($_POST['username'] == "counter" || ctype_alnum($_POST['username']) != true) {
        error('Invalid Username.');
    }
    $_POST['username'] = strtolower($_POST['username']);
    if (!file_exists($path . '/' . $database_folder . '/users/' . $_POST['username'] . '.php')) {
        error('User doesn\'t exist.');
    }

    include $path . '/' . $database_folder . '/users/' . $_POST['username'] . '.php';

    if (crypt($_POST['password'], $password_salt) != $password) {
        error('Wrong password.');
    }

    $new_session = crypt(md5(random_bytes(10) . $_POST['password']), $secure_hash);

    //set session in user file
    setcookie("mod_user", $_POST['username'], 0, $cookie_location, $domain, isset($_SERVER["HTTPS"])); //not bothering setting expiry, they'll be replaced anyways if old.
    setcookie("mod_session", $new_session, 0, $cookie_location, $domain, isset($_SERVER["HTTPS"]));

    if (isset($_POST['remember'])) {
        $remember_time = time(); //basically just says when the login session was created
    } else {
        $remember_time = time() + 2505600; // remember time +29days (for 1day login)
    }

    //todo: set session in user file
    //todo: set remember in user file

    $user_info = file_get_contents($path . '/' . $database_folder . '/users/' . $_POST['username'] . '.php');
    $user_info = preg_replace('/\$user_remember = ".*?";/i', '$user_remember = "' . $remember_time . '";', $user_info);
    $user_info = preg_replace('/\$user_session = ".*?";/i', '$user_session = "' . $new_session . '";', $user_info);
    file_put_contents($path . '/' . $database_folder . '/users/' . $_POST['username'] . '.php', $user_info);

    $logged_in = true;
    $logged_in_as = $username;
    $mod_level = $user_mod_level;
    header("Refresh: 0");
}

//CREATE FOLDER + DEFAULT USER
if (!file_exists($path . '/' . $database_folder . '/users')) {
    mkdir($path . '/' . $database_folder . '/users', 0755);

    $password_salt = crypt(md5(random_bytes(30)), $secure_hash);

    $default_user = '<?php ';
    $default_user .= '$user_id = "0"; ';
    $default_user .= '$username = "admin"; ';
    $default_user .= '$password_salt = "' . $password_salt . '"; ';
    $default_user .= '$password = "' . crypt('password', $password_salt) . '"; ';
    $default_user .= '$gpg_key = ""; ';
    $default_user .= '$gpg_enabled = "0"; '; //if enabled, don't check password but instead send a gpg decryption test. use php session.
    $default_user .= '$user_mod_level = "9001"; ';
    $default_user .= '$user_mod_boards = "*"; '; //add board specifics or all.
    $default_user .= '$user_remember = "' . time() . '"; '; //add a +30 days check or delete session and go to login screen
    $default_user .= '$user_session = ""; '; //login session key, set on login.
    $default_user .= ' ?>';

    file_put_contents($path . '/' . $database_folder . '/users/admin.php', $default_user); //create default admin user
    file_put_contents($path . '/' . $database_folder . '/users/counter.php', 0); //create user count

}

//LOGIN PAGE
if ($logged_in == false) {

    $title = 'Login - ' . $site_name;
    if (isset($_GET["theme"])) {
        $output_html .= '<html data-stylesheet="' . htmlspecialchars($_GET["theme"]) . '">';
    } else {
        $output_html .= '<html data-stylesheet="' . $current_theme . '">';
    }
    $output_html .= '<head>';
    include $path . '/templates/header.php';
    $output_html .= '</head>';
    $output_html .= '<body class="frontpage">';
    include $path . '/templates/boardlist.php';
    $output_html .= '<div class="page-info"><h1>Login Page</h1><div class="small">Permission required.</div></div><br><br>';
    $output_html .= '<div class="main first"><h2>Login.</h2>';
    $output_html .= '<div id="post-form">
			<form name="login" action="' . $prefix_folder . '/mod.php" method="post">
				<table id="login" style="margin:auto;">
					<tr><th>Username</th><td><input type="text" name="username" size="25" maxlength="256" autocomplete="off" placeholder="Username"></td></tr>
					<tr><th>Password</th><td><input type="password" name="password" size="25" maxlength="256" autocomplete="off" placeholder="Password"></td></tr>';
    if ($captcha_required == true) {
        $output_html .= '
		<tr>
			<th>Verification</th>
			<td>
				<span class="js-captcha" id="load-captcha" style="max-width:200px">
				<span class="js-captcha">
					<img title="Click Here To Refresh" height="50" width="198" id="captcha" src="' . $prefix_folder . '/captcha.php' . '" js-src="' . $prefix_folder . '/captcha.php' . '"/><br>
				</span>
				</span>
				<noscript>
					<style>.js-captcha { display:none }</style>
					<img height="50" width="198" id="no-js-captcha" src="' . $prefix_folder . '/captcha.php' . '"/><br>
				</noscript>
				<input id="captcha-field" type="text" name="captcha" minlength="6" maxlength="6" autocomplete="off" required>
				</span>
			</td>
		</tr>';
    }
    $output_html .= '<tr><th style="visibility:hidden;"></th><td><input type="checkbox" id="remember" name="remember"
         checked><label for="remember">Remember Me</label><input type="submit" name="post" value="Login" style="float: right;"></td></tr>
				</table>
			</form>
			</div>';
    $output_html .= '</div>';

    if ($changed_password == true) {
        $output_html .= '<div class="message" style="margin-top:0;">Password has been changed.</div>';
    } else {
        $output_html .= '<div class="message"></div>';
    }

    include $path . '/templates/footer.php';
    $output_html .= '</body>';
    $output_html .= '</html>';

    echo $output_html;
    exit();
}
