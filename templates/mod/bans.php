<?php 

	if ($user_mod_level < $config['mod']['ban']) {
		error('You don\'t have permission to view this page.');
	}

	if (!file_exists($path . '/' . $database_folder . '/bans')) {
		mkdir($path . '/' . $database_folder . '/bans');
	}

	$title = 'Manage Bans - ' . $site_name;
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

	$banfolder = glob($path . '/' . $database_folder . '/bans/*', GLOB_ONLYDIR); 
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

?>