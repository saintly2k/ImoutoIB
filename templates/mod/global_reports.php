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
	$output_html .= '<h2>Global Reports</h2>';
	$output_html .= '<div class="box-content">';

	$output_html .= '<table style="width:100%">';
	$output_html .= '<thead> <td>Board</td> <td>Post</td> <td>Report IP</td> <td>Reason</td> <td>View</td> <td>Actions</td>';
	$output_html .= '<tbody>';

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

						$output_html .= '<tr>'; 
						$output_html .= '<td>/' . $report_board . '/</td>';
						$output_html .= '<td>' . $report_reply . '@' . $report_thread . '</td>';
						if ($user_mod_level >= $config['mod']['ip']) {
							$output_html .= '<td>' . $report_ip . '</td>';
						} else {
							$output_html .= '<td>No Perm</td>';
						}
						$output_html .= '<td title="' . $report_reason . '"style="white-space:pre;word-wrap:break-word;max-width:150px;overflow:hidden;text-overflow:ellipsis">' . $report_reason . '</td>';
						$output_html .= '<td><a href="' . $prefix_folder . '/' . $main_file . '?board='. $report_board . '&thread=' . $report_thread . '#' . $report_reply . '" target="_blank">View</a></td>';
						$output_html .= '<td><details><summary>More</summary>';

						$output_html .= '	<form name="dismiss-report-global" action="' . $prefix_folder . '/mod.php?page=global_reports" method="post">
								<input type="hidden" name="board" value="' . $report_board . '">
								<input type="hidden" name="report" value="' . basename($report) . '">
								<input type="submit" name="dismiss_global" value="Dismiss"></td>
								</form>';

						$output_html .= '</details><td>';
						$output_html .= '</tr>';
				}
			}
	$output_html .= '</tbody>';
	$output_html .= '</table>';

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