<?php 

	$title = 'Account - ' . $site_name;
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

?>