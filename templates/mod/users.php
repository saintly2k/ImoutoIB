<?php
	if ($user_mod_level < $config['mod']['edit_user']) {
		error('You don\'t have permission to view this page.');
	}
	$title = 'Manage Users - ' . $site_name;
	if (isset($_GET['theme'])) {
		echo '<html data-stylesheet="'. htmlspecialchars($_GET['theme']) .'">';
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

	$userlist = glob($path . '/' . $database_folder . '/users/*'); 
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

?>