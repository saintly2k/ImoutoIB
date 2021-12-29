<?php

		if (htmlspecialchars($_GET["page"]) === "catalog") {
			$current_page = "catalog";
			$title = $board_title . ' @ ' . $site_name . ' - Catalog';

			if (isset($_GET["theme"])) {
				$output_html .= '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
			} else {
				$output_html .= '<html data-stylesheet="'. $current_theme .'">';	
			}
			$output_html .= '<head>';
			$output_html .= '<link rel="stylesheet" type="text/css" href="' . $prefix_folder . '/assets/css/catalog.css">';
			include 'header.php';
			$output_html .= '</head>';
			$output_html .= '<body class="' . $current_page . '">';
			include 'boardlist.php';
			$output_html .= '<div class="textboard"><br>'; //start textboard margin container
			//include 'page-info-txt.php';

			$output_html .= '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;';
			$output_html .= '[<a href="#bottom">Bottom</a>]&nbsp;';
			$output_html .= '<br>';
			if (!file_exists($path . '/' . $database_folder . '/boards/' . $current_board)) {
				$output_html .= 'This board has no threads yet.';
				include $path . '/templates/footer.php';
				echo $output_html;
				exit();
			}

			if (file_get_contents($path . '/' . $database_folder . '/boards/' . $current_board . '/counter.php') === "1") {
				$output_html .= 'This board has no threads yet.';
				include '/footer.php';
				echo $output_html;
				exit();
			}
			include $path . '/' . $database_folder . '/boards/' . $current_board . '/threads.php';

			//i should make this a function instead of reusing code, i'll do that later lol
			$original_list = $threads;
			$filter = "1";
			$stick_ = array_filter($threads, function($var) use ($filter){ //get all sticky threads
	    	return ($var['sticky'] == $filter);
			});
			
			$count_stickied_threads = count($stick_);

			if ($count_stickied_threads > 0) {
				$keys_ = array_column($original_list, 'sticky');
				array_multisort($keys_, SORT_DESC, $original_list); //sort by sticky
				$stickied_threads = array_slice($original_list, 0, $count_stickied_threads); //this can be sorted again by oldest vs newest? i think is fine like this tho

				$not_sticky_threads = array_slice($original_list, $count_stickied_threads); //get non stickies, then we sort them by bumped
				$keys_ = array_column($not_sticky_threads, 'bumped');
				array_multisort($keys_, SORT_DESC, $not_sticky_threads); //sort by bumped
				$threads = array_merge($stickied_threads, $not_sticky_threads);
			}

			$output_html .= '<br>Displaying ' . count($threads) . ' threads in total.';
			$output_html .= '<br><br>';


			$output_html .= '<div class="catalog-threads">';

			//IF TEXTBOARD:
			if ($config["boards"][$current_board]["type"] == "txt") {
				$output_html .= '<div class="textboard-catalog"><div class="page-info-txt-inner">';
					$output_html .= '<table>';
						$output_html .= '<thead>';
						$output_html .= '<tr><td>Num</td> <td>Title</td>	<td>Posts</td>	<td>Last Reply</td> <td>Bumped</td></tr>';
						$output_html .= '</thead>';
						$output_html .= '<tbody>';
									foreach (array_keys($threads) as $key => $value) {
										include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/OP.php';
										include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/info.php';
										include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/recents.php';
										$post_number_op = $threads[$key]['id'];

										$output_html .= '<tr>';
										$output_html .= '<td>' . ($key+1) . '</td>';
										$output_html .= '<td><a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '">';
														if ($op_subject == '') {
															$output_html .= substr(strip_tags($op_body),0,120);
														} else {
															$output_html .= $op_subject;
														}

										if ($info_sticky == 1 || $info_locked == 1 || $info_autosage == 1) {
											$output_html .= ' <small>';
											if ($info_sticky == 1) {
												$output_html .= '(sticky) ';
											}
											if ($info_locked == 1) {
												$output_html .= '(locked) ';
											}
											if ($info_autosage == 1) {
												$output_html .= '(permasaged) ';
											}
											$output_html .= '</small>';
										}

										$output_html .= '</a></td>';
										$output_html .= '<td>' . $info_replies . '</td>';

										$last_reply = end($recents);
										if ($last_reply == '') {
											$output_html .= '<td><span class="post-time" data-timestamp="' . $op_time . '" data-tooltip="' . timeConvert($op_time, $time_method_hover) . '"> ' . timeConvert($op_time, $time_method) . '</span></td>';
										} else {
											include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/' . $last_reply . '.php';
											$output_html .= '<td><span class="post-time" data-timestamp="' . $reply_time . '" data-tooltip="' . timeConvert($reply_time, $time_method_hover) . '"> ' . timeConvert($reply_time, $time_method) . '</span></td>';
										}
										
										if (file_exists($path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/' . 'bumped.php')) {
											$bumped = file_get_contents($path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/' . 'bumped.php');
										} else {
											$bumped =  $post_number_op;
										}
										if ($bumped == $post_number_op) {
											$output_html .= '<td><span class="post-time" data-timestamp="' . $op_time . '" data-tooltip="' . timeConvert($op_time, $time_method_hover) . '"> ' . timeConvert($op_time, $time_method) . '</span></td>';
										} else {
											include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/' . $bumped . '.php';
											$output_html .= '<td><span class="post-time" data-timestamp="' . $reply_time . '" data-tooltip="' . timeConvert($reply_time, $time_method_hover) . '"> ' . timeConvert($reply_time, $time_method) . '</span></td>';
										}

									   	$output_html .= '</tr>';
								   	}
						$output_html .= '</tbody>';
					$output_html .= '</table>';
				$output_html .= '</div></div><br>';
			}

			/*foreach (array_keys($threads) as $key => $value) {
				include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/OP.php';
				include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/info.php';
				include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/recents.php';
				$post_number_op = $threads[$key]['id'];

				$output_html .= '<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '">';
				$output_html .= '<div data-thread="' . $post_number_op . '" class="container">';
				include 'thread-catalog.php';
			   	$output_html .= '</div>';
			   	$output_html .= '</a>';
		   	}*/

		   	$output_html .= '</div>';

		   	$output_html .= '<div class="catalog-footer">';
			$output_html .= '<br>';
			$output_html .= '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;';
			$output_html .= '[<a href="#top">Top</a>]&nbsp;<br><br>';
			include 'footer.php';
			$output_html .= '</div>';
			$output_html .= '</div>'; //end textboard margin container
			$output_html .= '</body>';
			$output_html .= '</html>';
			echo $output_html;
			exit();
		}
?>