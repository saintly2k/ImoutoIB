<?php 

require 'require.php';


//FRONTPAGE
if ((!isset($_GET["board"])) || ($_GET["board"] == '') && $_GET["page"] == '') {
	
	$title = $site_name;

	$total_posts = 0;

	if (!file_exists($path . '/' . $database_folder . '/frontpage.php')) { //no frontpage? lets create boards folder
		if (!file_exists($path . '/' . $database_folder . '/boards')) {
			mkdir($path . '/' . $database_folder . '/boards');
		}
	}
	foreach ($config['boards'] as $boards) {
		//now lets create counters for each board, no matter if frontpage exists or not in case of new boards being made
		if (!file_exists($path . '/' . $database_folder . '/boards/' . $boards['url'] . '/counter.php')) {
			mkdir($path . '/' . $database_folder . '/boards/' . $boards['url']);
			file_put_contents($path . '/' . $database_folder . '/boards/' . $boards['url'] . '/counter.php', 1);
		}
		//
		$total_posts += file_get_contents($path . '/' . $database_folder . '/boards/' . $boards['url'] . '/counter.php');
		$total_posts -= 1; //idk how i fucked up the counter.php in post.php this badly.
	}
	
	if (isset($_GET["theme"])) {
		$output_html .= '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
	} else {
		$output_html .= '<html data-stylesheet="'. $current_theme .'">';	
	}
	$output_html .= '<head>';
	include $path . '/templates/header.php';
	$output_html .= '</head>';
	$output_html .= '<body class="frontpage">';
	include $path . '/templates/boardlist.php';
	include $path . '/templates/frontpage.php';
	include $path . '/templates/footer.php';
	$output_html .= '</body>';
	$output_html .= '</html>';
	echo $output_html;
	exit();
}

// PAGES
if ((!isset($_GET["board"])) || ($_GET["board"] == '') && $_GET["page"] != '') {
	if (!ctype_alnum($_GET["page"])) {
		error('Invalid page.');
	}
	if (!file_exists($path . '/templates/pages/' . $_GET["page"] . '.php')) {
		http_response_code(404);
		error('Page does not exist.');
	}
	include $path . '/templates/pages/' . $_GET["page"] . '.php';

	if (isset($_GET["theme"])) {
		$output_html .= '<html data-stylesheet="'. htmlspecialchars($_GET["theme"]) .'">';
	} else {
		$output_html .= '<html data-stylesheet="'. $current_theme .'">';	
	}
	$output_html .= '<head>';
	include $path . '/templates/header.php';
	$output_html .= '</head>';
	$output_html .= '<body class="page">';
	include $path . '/templates/boardlist.php';

	if ($config['display_banner'] === true) {
		include $path . '/assets/img/banner.php';
	}

	$output_html .= '<div class="page-info">';
	$output_html .= '<h1>' . $h1 . '</h1>';
	$output_html .= '<span class="small">' . $description . '</span>';
	$output_html .= '</div>';

	$output_html .= $page_content; //taken from the file

	include $path . '/templates/footer.php';
	$output_html .= '</body>';
	$output_html .= '</html>';
	echo $output_html;
	exit();

}	


//
// IF BOARD EXISTS
if (in_Array(htmlspecialchars($_GET["board"]), $config['boardlist'])) {

	$current_board = htmlspecialchars($_GET["board"]);
	$board_description = $config['boards'][$current_board]['description'];
	$board_slogan = $config['boards'][$current_board]['slogan'];
	$board_title = $config['boards'][$current_board]['title'];

	//if modonly
	if ($config['boards'][$current_board]['mod_only'] == 1) {
		if ($config['mod']['mod_only'] > $mod_level) {
			error('Permission denied. Authenticated staff only.');
		}
		$forced_anon = true; //all names will be username in post.php
	}


	if ($config["boards"][$current_board]["type"] == "img") { //IMAGEBOARD INDEX+CATALOG
		if ($catalog_enable == true) {
			include $path . '/templates/catalog.php';
		}
		include $path . '/templates/index.php';


	}

	if ($config["boards"][$current_board]["type"] == "txt") { //TEXTBOARD INDEX+CATALOG+LIST
		if ($catalog_enable == true) {
			include $path . '/templates/catalog-txt.php';
		}
		include $path . '/templates/index-txt.php';
	}

}

//NOT A BOARD
if ((htmlspecialchars($_GET["board"]) !== '') && (!in_Array(htmlspecialchars($_GET["board"]), $config['boardlist']))) {
	error('This board doesn\'t exist.. You\'re not trying anything funny — are you, Anon-san??');
}

?>