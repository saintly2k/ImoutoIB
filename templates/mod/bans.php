<?php 

	if ($user_mod_level < $config['mod']['ban']) {
		error('You don\'t have permission to view this page.');
	}

	if (!file_exists($path . '/' . $database_folder . '/bans')) {
		mkdir($path . '/' . $database_folder . '/bans');
	}

	$title = 'Manage Bans - ' . $site_name;
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
	$output_html .= '<h2>Ban IP</h2>';
	$output_html .= '<div class="box-content">';
	$output_html .= '<p>';
	$output_html .= '<details><summary>Ban IP</summary>';
	$output_html .= '<form name="create-ban" action="' . $prefix_folder . '/mod.php?page=bans" method="post">
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
	$output_html .= '</details>';
	$output_html .= '</p>';
	$output_html .= '</div>';
	$output_html .= '</div>';

	$output_html .= '<br>';
	$output_html .= '<div class="box right">';
	$output_html .= '<h2>Manage Bans</h2>'; //at some point i will need to rewrite this+reports+users to have pages if it grows large!!!
	$output_html .= '<div class="box-content">';
	
	//foreach
	
	$output_html .= '<table><thead> <td>ID</td> <td>IP</td> <td>Reason</td> <td>Expires</td> <td>Read</td> <td>Actions</td></thead>';
	$output_html .= '<tbody>';

	//TO DO: multiarray and sort by ID, alternatively use JS.
	// I should also first take the admins, sort them by id, then the mods by id, then the jannies by id, etc.
	// Basically sorted by mod level, and each modlevel sorted by ID.

	$banfolder = glob($path . '/' . $database_folder . '/bans/*', GLOB_ONLYDIR); 
	$banlist_full = [];

	foreach ($banfolder as $banfolder) { //for each folder
		foreach (glob($banfolder . '/*') as $banfile) { //for each file
			if (!is_numeric(basename($banfile, '.php'))) { //not a ban
				continue;
			}
			include $banfile;

			if ($ban['duration'] == 'warning') {
				$output_html .= '<tr style="text-decoration:line-through;">';
			} elseif (($ban['duration'] != 'permanent') && (($ban['time'] + $ban['duration']) < time())) { //if warning or expired
				$output_html .= '<tr style="text-decoration:line-through;">';
			} else {
				$output_html .= '<tr>';
			}

			$output_html .= '<td>' . $ban['id'] . '</td>';
			$output_html .= '<td>' . $ban['original_ip'] . '</td>';
			$output_html .= '<td title="' . $ban['reason'] . '"style="white-space:pre;word-wrap:break-word;max-width:150px;overflow:hidden;text-overflow:ellipsis">' . $ban['reason'] . '</td>';
			
			if ($ban['duration'] == 'warning') {
				$output_html .= '<td>---------</td>';
			} elseif ($ban['duration'] == 'permanent') {
				$output_html .= '<td>Never</td>';
			} elseif (($ban['time'] + $ban['duration']) < time()) {
				$output_html .= '<td>'. timeago($ban['time'] + $ban['duration']) .'</td>';
			} else {
				$output_html .= '<td>'. timeuntil($ban['time'] + $ban['duration']) .'</td>';
			}
			
			$output_html .= '<td>' . $ban['is_read'] . '</td>';
			$output_html .= '<td>';
			$output_html .= '<details><summary>More</summary>';

			if ($ban["post-body"] != false) { //manual ban or not?
				$output_html .= '<details><summary class="small">View</summary>'; //see post that caused ban
				$output_html .= '<div class="post reply banned"><div class="post-info">';
				if ($ban['post-subject'] != '') {
				$output_html .= '<span class="subject">'.$ban['post-subject'].'&nbsp;</span>';
				}
				if ($ban['post-email'] != '') {
					$output_html .= '<span class="name"><a href="mailto:'.$ban['post-email'].'">'.$ban['post-name'].'</a>&nbsp;</span>';
				} else {
					$output_html .= '<span class="name">'.$ban['post-name'].'&nbsp;</span>';
				}
				
				$output_html .= '<span class="post-time" data-tooltip="'.timeConvert($ban['post-time'], $time_method_hover).'" data-timestamp="'.$ban['post-time'].'">'.timeConvert($ban['post-time'], $time_method).'&nbsp;</span>';
				$output_html .= '<span class="post-number">No.'.$ban['reply'].'</span>';
				$output_html .= '</div><blockquote class="post-content">'.$ban['post-body'].'</blockquote></div>';
				$output_html .= '</details>'; //"view file"
			} else {
				$output_html .= 'Manual ban.';
			}

			//delete
			$output_html .= '<details><summary class="small">Delete</summary><details><summary>Are you sure you want to remove this ban?</summary>';
			$output_html .= '	<form name="delete-ban" action="' . $prefix_folder . '/mod.php?page=bans" method="post">
								<input type="hidden" name="delete-ban-ip" value="' . $ban['ip'] . '">
								<input type="hidden" name="delete-ban-id" value="' . $ban['id'] . '">
								<input type="submit" name="delete-ban" value="Delete"></td>
								</form>';
			$output_html .= '</details></details>';

			$output_html .= '</details></td>';
			$output_html .= '<tr>';
		}
	}

	$output_html .= '</tbody></table>';
	
	$output_html .= '</div>';
	$output_html .= '</div>';

	$output_html .= '</div>';
	$output_html .= '<br>';
	$output_html .= '</div>';

	if ($ban_removed == true) {
		$output_html .= '<div class="message" style="margin-top:0;">Ban has been deleted.</div>';
	}
	if ($ban_created == true) {
		$output_html .= '<div class="message" style="margin-top:0;">Ban has been created.</div>';
	}
	if ($warning_created == true) {
		$output_html .= '<div class="message" style="margin-top:0;">Warning has been created.</div>';
	}

	include $path . '/templates/footer.php';
	$output_html .= '</body>';
	$output_html .= '</html>';
	echo $output_html;
	exit();

?>