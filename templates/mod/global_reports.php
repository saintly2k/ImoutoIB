<?php 

	if ($user_mod_level < $config['mod']['global_reports']) {
		error('You don\'t have permission to view this page.');
	}

	if (!file_exists($path . '/' . $database_folder . '/reportsglobal')) {
		mkdir($path . '/' . $database_folder . '/reportsglobal');
	}

	//recount
	ReportCounter($database_folder, 'global');

	$title = 'Global Reports - ' . $site_name;
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
	echo '<h2>Global Reports</h2>';
	echo '<div class="box-content">';

	echo '<table style="width:100%">';
	echo '<thead> <td>Board</td> <td>Post</td> <td>Report IP</td> <td>Reason</td> <td>View</td> <td>Actions</td>';
	echo '<tbody>';

	//FIND REPORTS
		$reports = [];
		$reports = glob($path . '/' . $database_folder . '/reportsglobal/*'); //find reports
			foreach ($reports as $report) { //for each report
				if (is_numeric(basename($report, '.php'))) {
						include $report;

						//dismiss report if thread/reply no longer exists and go to next report in loop
						if ((($report_thread == $report_reply) && (!file_exists($path . '/' . $database_folder . '/boards/' . $report_board . '/' . $report_thread))) || (($report_thread != $report_reply) && (!file_exists($path . '/' . $database_folder . '/boards/' . $report_board . '/' . $report_thread . '/' . $report_reply . '.php')))) {
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

?>