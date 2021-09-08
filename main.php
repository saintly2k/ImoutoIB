<?php 

require dirname(__FILE__) . '/require.php';


//FRONTPAGE
if ((!isset($_GET["board"])) || ($_GET["board"] == '')) {
	
	$title = $site_name;

	$total_posts = 0;
	foreach ($config['boards'] as $boards) {
		//prevent error on new board with 0 posts. you need to visit frontpage at least once otherwise board throws an error, but its not fatal or anything....
		if (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . $boards['url'] . '/counter.php')) {
			@mkdir(__dir__ . '/' . $database_folder . '/boards/' . $boards['url']);
			file_put_contents(__dir__ . '/' . $database_folder . '/boards/' . $boards['url'] . '/counter.php', 1);
		}
		//
		$total_posts += file_get_contents(__dir__ . '/' . $database_folder . '/boards/' . $boards['url'] . '/counter.php');
		$total_posts -= 1; //idk how i fucked up the counter.php in post.php this badly.
	}
	
	if (isset($_GET["theme"])) {
		echo '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
	} else {
		echo '<html data-stylesheet="'. $current_theme .'">';	
	}
	echo '<head>';
	include $path . '/templates/header.html';
	echo '</head>';
	echo '<body class="frontpage">';
	include $path . '/templates/boardlist.html';
	include $path . '/templates/pages/frontpage.html';
	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();
}

//ADD PAGES HERE



//
// IF BOARD EXISTS
if (in_Array(htmlspecialchars($_GET["board"]), $config['boardlist'])) {

	$current_board = htmlspecialchars($_GET["board"]);
	$board_description = $config['boards'][$current_board]['description'];
	$board_title = $config['boards'][$current_board]['title'];


	//IF INDEX
	if (htmlspecialchars($_GET["thread"]) === "") {
		$current_page = "index";
		$title = '/' . $current_board . '/ - ' . $config['boards'][$current_board]['title'] . ' - ' . $site_name;

	if (isset($_GET["theme"])) {
		echo '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
	} else {
		echo '<html data-stylesheet="'. $current_theme .'">';	
	}
	echo '<head>';
	include $path . '/templates/header.html';
	echo '</head>';
	echo '<body class="' . $current_page . '">';
	include $path . '/templates/boardlist.html';
	include $path . '/templates/page-info.html';
	if ($config['boards'][$current_board]['locked'] != 1) {
	include $path . '/templates/post-form.html';
	} else {
		echo '<div class="blotter">This board is locked by the board owner.</div><hr>';
	}
	echo '[<a href="#bottom">Bottom</a>]&nbsp;';
	echo '<hr>';

			//if zero threads aka new board
		if (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . $current_board)) {
			echo 'This board has no threads yet.';
			include $path . '/templates/footer.html';
			exit();
		}

		if (file_get_contents(__dir__ . '/' . $database_folder . '/boards/' . $current_board . '/counter.php') === "1") {
			echo 'This board has no threads yet.';
			include $path . '/templates/footer.html';
			exit();
		}

		include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/threads.php';
		
		//put stickies up front (not doing this in the saved list, if wanna have frontpage with recent threads)

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

		if (count($threads) > $threads_page) {
			$total_threads = count($threads);
			$final_page_threads = $threads_page - ($total_threads % $threads_page); //how many to offset the arrayslice by for last page

			//create pages
			$pages = ceil($total_threads / $threads_page) - 1;

			if ($_GET['page'] == '') {
			$number_page = 1;
			} elseif (!is_numeric($_GET['page'])) {
				error('Page number must be a number.');
			} elseif ($_GET['page'] > $pages || $_GET['page'] < 1) {
				error('This number is higher or lower than page count...');
			} else {
				$number_page = $_GET['page'];
			}
			$offset_ = ($number_page - 1 * $threads_page);
			$start_ = $offset_ + 1;
			$end_ = min(($offset_ + $threads_page), $total_threads);

			    // The "back" link
    		$prevlink = ($number_page > 1) ? '<form method="get"><button type="submit" value="' . $current_board . '" name="board">Previous</button>
<input type="hidden" name="page" value="' . ($number_page - 1) . '"></form>' : '<span class="disabled">Previous</span>';
    		$all_pages = '';

    		for ($i = 0; $i < $pages; $i++) {
    			$currentp = $i + 1;
    			if ($currentp == $number_page) {
    			$all_pages .= '[<a href="' . $prefix_folder . '/?board=' . $current_board . '&page=' . $currentp. '"><b>' . $currentp . '</a></b>] ';
    			} else {
    			$all_pages .= '[<a href="' . $prefix_folder . '/?board=' . $current_board . '&page=' . $currentp. '">' . $currentp . '</a>] ';
    			}
    		}

    		$nextlink = ($number_page < $pages) ? '<form method="get"><button type="submit" value="' . $current_board . '" name="board">Next</button>
<input type="hidden" name="page" value="' . ($number_page + 1) . '"></form>' : '<span class="disabled">Next</span>';

			//page 1
			if ($number_page == 1) {
			$threads = array_slice($threads, 0, $threads_page);
			} elseif ($number_page == $pages) { //if final page
				$threads = array_slice($threads, - $threads_page, $threads_page);
				$threads = array_slice($threads, $final_page_threads);
			} else {
			$threads = array_slice($threads, ($threads_page * $number_page) - $threads_page);
			$threads = array_slice($threads, 0, $threads_page);
			}
			//$hidden_threads = $total_threads - $threads_page;
			//echo 'There are ' . $hidden_threads . ' undisplayed threads. I\'ll make a pagination for them all...' ;

		}


		//SHOW THEM
		foreach (array_keys($threads) as $key => $value) {
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/OP.php';
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/info.php';
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $threads[$key]['id'] . '/recents.php';
			$post_number_op = $threads[$key]['id'];

			echo '<div data-thread="' . $post_number_op . '" class="container">';
			//SHOW THREADS
			include $path . '/templates/thread.html';
			//SHOW SHOW REPLIES

			foreach (array_keys($recents) as $rkey => $value) {
				include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $post_number_op . '/' . $recents[$value] . '.php';
				$post_number_reply = $recents[$value];
				include $path . '/templates/reply.html';
		   	}
		   	echo '</div>';
			if ($key != array_key_last($threads)) {
		        echo '<hr data-thread="' . $post_number_op . '">';
		    }
	   	}
	echo '<hr>';
	echo '[<a href="#top">Top</a>]&nbsp;';
	include $path . '/templates/footer.html';
	echo '</body>';
	echo '</html>';
	exit();


	}

	// IF THREAD
	if (htmlspecialchars($_GET["thread"]) != '') {
		if (isset($_GET["theme"])) {
			echo '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
		} else {
			echo '<html data-stylesheet="'. $current_theme .'">';	
		}
		//IF DOESNT EXIST
		if (!file_exists(__dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . htmlspecialchars($_GET["thread"]))) {
			$title = "Oh no!! A 404...";
			echo '<head>';
			include $path . '/templates/header.html';
			echo '</head>';
			echo '<body class="' . $current_page . '">';
			include $path . '/templates/boardlist.html';
			include $path . '/templates/page-info.html';
			echo '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;';
			echo '<hr>';
			echo '<div class="message">This thread doesn\'t exist.. Did the glowies get it — or worse, a janny??<br><img style="height: 500px;width: 500px;margin-top: 5px;" src="'. $prefix_folder . '/assets/img/404.png" width="" height=""></div><style>.message { margin-top: 0!important }</style>';
			echo '<div class="message">[<a href="' . $prefix_folder . $main_file . '?board=' . $current_board . '">Return</a>]</div>';
			echo '<hr>';
			echo '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;';
			include $path . '/templates/footer.html';
			exit();

		} else {
		//IF DOES EXIST
			$current_page = "thread";
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . htmlspecialchars($_GET["thread"]) . '/OP.php';
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . htmlspecialchars($_GET["thread"]) . '/info.php';
			$post_number_op = htmlspecialchars($_GET["thread"]);
			if ($op_subject == '') {
				$title = '/' . $current_board . '/' . ' - ' . substr(strip_tags($op_body),0,30) . ' - ' . $config['boards'][$current_board]['title'] . ' - ' . $site_name;
			} else {
				$title = '/' . $current_board . '/' . ' - ' . $op_subject . ' - ' . $config['boards'][$current_board]['title'] . ' - ' . $site_name;
			}

			echo '<head>';
			include $path . '/templates/header.html';
			echo '</head>';
			echo '<body class="' . $current_page . '">';
			include $path . '/templates/boardlist.html';
			include $path . '/templates/page-info.html';
			include $path . '/templates/post-form.html';
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $post_number_op . "/info.php";
			$thread_stats = '<span class="thread-stats">Replies: ' . $info_replies . ' Posters: ' . $info_uniqueids . '</span>';
			echo '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;[<a href="#bottom">Bottom</a>]&nbsp;';
			echo $thread_stats;
			echo '<hr>';
	
			include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . htmlspecialchars($_GET["thread"]) . '/OP.php';
			$post_number_op = htmlspecialchars($_GET["thread"]);
			echo '<div data-thread="' . $post_number_op . '" class="container">'; //start thread
			include $path . '/templates/thread.html';
			$current_thread = $post_number_op;


			//ADD ALL REPLIES HERE
			//FIND REPLIES
				$replies_full = [];
				$replies_full = glob(__dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $post_number_op . "/*");
			//SORTING
				$replies = [];
				foreach ($replies_full as $reply) {
					if (is_numeric(basename($reply, '.php'))) {
						$replies[] = basename($reply, '.php');
					}
				}
			sort($replies);
			//SHOW THEM
			foreach (array_keys($replies) as $key => $value) {
				include __dir__ . '/' . $database_folder . '/boards/' . $current_board . '/' . $post_number_op . '/' . $replies[$value] . '.php';
				$post_number_reply = $replies[$value];
				include $path . '/templates/reply.html';
		   	}
		   	echo '</div>'; //end thread

			echo '<hr>';
			echo '[<a href="' . $prefix_folder . '/' . $main_file . '?board=' . $current_board . '">Return</a>]&nbsp;[<a href="#top">Top</a>]&nbsp;';
			echo $thread_stats;
			include $path . '/templates/footer.html';
			echo '</body>';
			echo '</html>';
	}	
	}
	



}

//NOT A BOARD
if ((htmlspecialchars($_GET["board"]) !== '') && (!in_Array(htmlspecialchars($_GET["board"]), $config['boardlist']))) {
	error('This board doesn\'t exist.. You\'re not trying anything funny — are you, Anon-san??');
}

?>