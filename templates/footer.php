<?php 

$output_html .= '<hr class="footer';
if (!isset($current_board) || $current_board == '') { 
	$output_html .= ' static'; 
} 
$output_html .= '">';

if (!isset($current_board) || $current_board == '') {
	$output_html .= '<div class="static footer">';
	$output_html .= '<ul>';
	foreach ($config['footer'] as $link) {
		$output_html .= '<li><a href="' . $link[1] . '">' . $link[0] . '</a></li>';
	}
	$output_html .= '</ul>';
	$output_html .= '</div>';
}

$output_html .= '<div class="abovefooter">';

if (isset($current_board) && $current_board != '') {
	if (isset($current_page) && $current_page == 'index' && $pages > 1) {
		$output_html .= '<div class="float-left pager">';
			$output_html .= '<table>';
				$output_html .= '<tr>';
					$output_html .= '<td>' . $prevlink . '</td>';
					$output_html .= '<td>' . $all_pages . '</td>';
					$output_html .= '<td>'  . $nextlink . '</td>';
				$output_html .= '</tr>';
			$output_html .= '</table>';
		$output_html .= '</div>';
	}
}

$output_html .= '<div class="float-right"><select id="themes">';

if (isset($current_board) && $current_board != '') {
	if ($config["boards"][$current_board]["type"] == "txt") {
		foreach($config['css_text'] as $theme) {
			$output_html .= '<option value="' . $theme . '">' . $theme . '</option>';
		}
	} else {
		foreach($config['css'] as $theme) {
			$output_html .= '<option value="' . $theme . '">' . $theme . '</option>';
		}
	}
} else {
		foreach($config['css'] as $theme) {
			$output_html .= '<option value="' . $theme . '">' . $theme . '</option>';
		}			
}

$output_html .=	'	
		</select>
		<noscript>
			<style>select#themes { display: none; }</style>
			<details><summary style="text-align:right">Themes</summary>';

		if (isset($current_board) && $current_board != '') {
			if ($config["boards"][$current_board]["type"] == "txt") {
				foreach($config['css_text'] as $theme) {
					$output_html .= '&nbsp;[<a href="' . $prefix_folder . $main_file . '/?board=' . $current_board . '&thread=' . $current_thread . '&theme=' . rawurlencode($theme) . '">' . $theme . '</a>]';
				}
			} else {
				foreach($config['css'] as $theme) {
					$output_html .= '&nbsp;[<a href="' . $prefix_folder . $main_file . '/?board=' . $current_board . '&thread=' . $current_thread . '&theme=' . rawurlencode($theme) . '">' . $theme . '</a>]';
				}
			}
		} else {
			foreach($config['css'] as $theme) {
				$output_html .= '&nbsp;[<a href="' . $prefix_folder . $main_file . '/?board=' . $current_board . '&thread=' . $current_thread . '&theme=' . rawurlencode($theme) . '">' . $theme . '</a>]';
			}
		}
$output_html .='</details>
		</noscript>
	</div>
</div>';


$output_html .= '<div class="footer">
	<p>ImoutoIB-plus</p>';
	if ($display_version == true) { $output_html .= '<p>' . $version . '</p>'; } 
		if ($config['generated_in'] === true) {
			$end_time = microtime(true);
			$generation_time = round($end_time - $start_time, 5);
			$output_html .= '<p class="small">Page generated in ' . $generation_time . ' seconds.</p>';
		}
$output_html .= '
	<br>
<a name="bottom"></a>
</div>';

