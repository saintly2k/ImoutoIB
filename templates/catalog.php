<?php

		if (htmlspecialchars($_GET["page"]) === "catalog") {
			$current_page = "catalog";
			$title = '/' . $current_board . '/ - ' . $config['boards'][$current_board]['title'] . ' - Catalog - ' . $site_name;

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
			include 'page-info.php';
			if ($config['boards'][$current_board]['locked'] != 1) {
			include 'post-form.php';
			} else {
				$output_html .= '<div class="blotter">This board is locked by the board owner.</div><hr>';
			}
			$output_html .= '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;';
			$output_html .= '[<a href="#bottom">Bottom</a>]&nbsp;';
			$output_html .= '<hr>';
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

			$output_html .= 'Displaying ' . count($threads) . ' threads in total.'; //maybe at some point add a limit to IB catalog, or just no thumbnails after 100 threads.
			$output_html .= '<hr>';


			$output_html .= '<div class="catalog-threads">';

			foreach (array_keys($threads) as $key => $value) {
				include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/OP.php';
				include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/info.php';
				include $path . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/recents.php';
				$post_number_op = $threads[$key]['id'];

				$output_html .= '<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '&thread=' . $post_number_op . '">';
				$output_html .= '<div data-thread="' . $post_number_op . '" class="container">';
				include 'thread-catalog.php';
			   	$output_html .= '</div>';
			   	$output_html .= '</a>';
		   	}

		   	$output_html .= '</div>';

		   	$output_html .= '<div class="catalog-footer">';
			$output_html .= '<hr>';
			$output_html .= '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;';
			$output_html .= '[<a href="#top">Top</a>]&nbsp;';
			include 'footer.php';
			$output_html .= '</div>';
			$output_html .= '</body>';
			$output_html .= '</html>';
			echo $output_html;
			exit();
		}
?>