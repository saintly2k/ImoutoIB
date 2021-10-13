<?php 

	$title = 'Account - ' . $site_name;
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
	$output_html .= '<h2>Account</h2>';
	$output_html .= '<div class="box-content">';
	$output_html .= '<p>';
	$output_html .= 'Username: ' . $username;
	$output_html .= '</p>';

	//CHANGE PASSWORD
	$output_html .= '<details><summary>Edit Password</summary>';
	$output_html .= '		<form name="edit-password" action="' . $prefix_folder . '/mod.php" method="post">
				<table id="post-form" style="width:initial;">
					<tr><th>Current Password:</th><td><input type="password" name="old-password" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th>New Password:</th><td><input type="password" name="new-password" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th>New Password x2:</th><td><input type="password" name="new-password2" size="25" maxlength="256" autocomplete="off" placeholder="Password" required></td></tr>
					<tr><th style="visibility:hidden;"></th><td><input type="submit" name="post" value="Edit Password" style="float: right;"></td></tr>
				</table>
			</form>';
	$output_html .= '</details>';
	$output_html .= '</div>';
	$output_html .= '</div>';

	$output_html .= '</div>';
	$output_html .= '<br>';
	$output_html .= '</div>';

	include $path . '/templates/footer.php';
	$output_html .= '</body>';
	$output_html .= '</html>';
	echo $output_html;
	exit();

?>