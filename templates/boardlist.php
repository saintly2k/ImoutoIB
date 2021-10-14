<?php 

$output_html .= '<a name="top"></a>
<div id="boardlist">[<a href="' . $prefix_folder . '/' . $main_file . '">Home</a>]&nbsp;[';

	foreach ($config['boards'] as $boards) {
		if (($boards['hidden'] === 0) || ($config['mod']['mod_only'] <= $mod_level)) {
			if ($boards['url'] != array_key_first($config['boards'])) {
		        $output_html .= ' / ';
		    }
		    if ($boardlist == "title") {
			 	$output_html .= '<a href="' . $prefix_folder . '/' . $main_file . '?board=';
				$output_html .= $boards['url'];
				$output_html .= '">' . $boards["title"] . '</a>';
			} else {
			 	$output_html .= '<a href="' . $prefix_folder . '/' . $main_file . '?board=';
				$output_html .= $boards['url'];
				$output_html .= '">' . $boards["url"] . '</a>';	
			}
		}
	}

$output_html .= ']</div>';