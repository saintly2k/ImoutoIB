<?php
	if ($user_mod_level < $config['mod']['edit_user']) {
		error('You don\'t have permission to view this page.');
	}
	$title = 'Manage Users - ' . $site_name;
	if (isset($_GET['theme'])) {
		$output_html .= '<html data-stylesheet="'. htmlspecialchars($_GET['theme']) .'">';
	} else {
		$output_html .= '<html data-stylesheet="'. $current_theme .'">';	
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
	$output_html .= '<h2>Create User</h2>';
	$output_html .= '<div class="box-content">';
	$output_html .= '<p>';
	$output_html .= '<details><summary>Create User</summary>';
	//CREATE USER
	$output_html .= '<form name="create-user" action="' . $prefix_folder . '/mod.php?page=users" method="post">
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
	$output_html .= '</details>';
	$output_html .= '</p>';
	$output_html .= '</div>';
	$output_html .= '</div>';

	$output_html .= '<br>';
	$output_html .= '<div class="box right">';
	$output_html .= '<h2>Manage Users</h2>';
	$output_html .= '<div class="box-content">';
	
	//foreach
	
	$output_html .= '<table><thead> <td>ID</td> <td>Username</td> <td>Mod Level</td> <td>Actions</td></thead>';
	$output_html .= '<tbody>';

	//TO DO: multiarray and sort by ID, alternatively use JS.
	// I should also first take the admins, sort them by id, then the mods by id, then the jannies by id, etc.
	// Basically sorted by mod level, and each modlevel sorted by ID.

	$userlist = glob($path . '/' . $database_folder . '/users/*'); 
	foreach ($userlist as $user) {
		if (basename($user) == 'counter.php') {
			continue; //not a user, go next iteration
		}
		include $user;
		$output_html .= '<tr>';
		$output_html .= '<td>' . $user_id . '</td>';
		$output_html .= '<td>' . $username . '</td>';
		$output_html .= '<td>';
		switch ($user_mod_level) {
			case 9001:
				$output_html .= 'Admin';
				break;
			case 40:
				$output_html .= 'Mod';
				break;
			case 10:
				$output_html .= 'Janitor';
				break;
			case 0:
				$output_html .= 'User';
				break;
			default:
				$output_html .= 'Unknown';
				break;
		}
		$output_html .= ' (' . $user_mod_level . ')</td>';
		$output_html .= '<td><details><summary>More</summary>';
		$output_html .= '<details><summary style="font-size:smaller;">Edit</summary>';

		//EDIT USER
		$output_html .= '<form name="edit-user" action="' . $prefix_folder . '/mod.php?page=users" method="post">
				<table id="post-form" style="width:initial;">
					<tbody><tr><th>Username:</th><td><input type="hidden" name="edit-username" value="' . $username . '"><input type="text" name="edit-username-view" size="25" maxlength="32" autocomplete="off" value="' . $username . '" disabled></td></tr>
					<!---<tr><th>Password:</th><td><input type="password" name="edit-password" size="25" maxlength="256" autocomplete="off" placeholder="Leave Empty To Not Change"></td></tr>
					<tr><th>Password x2:</th><td><input type="password" name="edit-password2" size="25" maxlength="256" autocomplete="off" placeholder="Leave Empty To Not Change"></td></tr>-->
					<tr><th>User Level:</th><td>
					<select name="edit-level">';

		switch ($user_mod_level) {
			case 9001:
				$output_html .= '<option value="9001" selected>Admin (9001)</option>
					  <option value="40">Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0">User (0)</option>';
				break;
			case 40:
				$output_html .= '<option value="9001">Admin (9001)</option>
					  <option value="40" selected>Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0">User (0)</option>';
				break;
			case 10:
				$output_html .= '<option value="9001">Admin (9001)</option>
					  <option value="40">Moderator (40)</option>
					  <option value="10" selected>Janitor (10)</option>
					  <option value="0">User (0)</option>';
				break;
			default:
				$output_html .= '<option value="9001">Admin (9001)</option>
					  <option value="40" selected>Moderator (40)</option>
					  <option value="10">Janitor (10)</option>
					  <option value="0" selected>User (0)</option>';
				break;
		}

		$output_html .= '			</select>
					</td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="edit-user" value="Edit User" style="float: right;"></td></tr>
				</tbody></table>
			</form>';

		$output_html .= '</details>';
		$output_html .= '<details><summary style="font-size:smaller;">Delete</summary><details><summary>Are you sure you want to delete this user ('.$username.')?</summary><details><summary>Yes!</summary><form name="delete-user" action="' . $prefix_folder . '/mod.php?page=users" method="post"><input type="hidden" id="delete-username" name="delete-username" value="' . $username . '"><input type="Submit" name="delete-user" value="Delete"></form></details></details></details>';
		$output_html .= '</details></td>';
		$output_html .= '</tr>';
		
	}
	$output_html .= '</tbody></table>';
	
	$output_html .= '</div>';
	$output_html .= '</div>';

	$output_html .= '</div>';
	$output_html .= '<br>';
	$output_html .= '</div>';

	if ($user_created == true) {
		$output_html .= '<div class="message" style="margin-top:0;">User created.</div>';
	}
	if ($user_edited == true) {
		$output_html .= '<div class="message" style="margin-top:0;">User edited.</div>';
	}
	if ($user_deleted == true) {
		$output_html .= '<div class="message" style="margin-top:0;">User deleted.</div>';
	}

	include $path . '/templates/footer.php';
	$output_html .= '</body>';
	$output_html .= '</html>';
	echo $output_html;
	exit();

?>