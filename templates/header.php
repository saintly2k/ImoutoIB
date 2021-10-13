<?php 

$output_html .= '<title>' . $title . '</title>';

$output_html .= '<!--SETTINGS--> <script>';

if ($captcha_requires = true) { 
	$output_html .= 'var captcha_required = true;'; 
}

$output_html .= 'var install_location = "' . $prefix_folder . '";';
$output_html .= 'var default_theme = "' . $default_theme . '";';

	if (isset($current_board) && $current_board != '') {
		if ($config["boards"][$current_board]["type"] == "txt") {
			$output_html .= 'var board_type = "txt";'; 
		} else {
			$output_html .= 'var board_type = "img";'; 
		}
	} else { 
		$output_html .= 'var board_type = "img";'; 
	} 
$output_html .= '</script>';

$output_html .= '<!--LOAD THEME IMMEDIATELY--> <script>';

if (isset($current_board) && $current_board != '') {
	if ($config["boards"][$current_board]["type"] == "txt") {
		$output_html .= 'if (localStorage.theme !== undefined) {';
		$output_html .= 'document.documentElement.setAttribute("data-stylesheet", localStorage.text_theme);';
		$output_html .= '};';
	} else {
		$output_html .= 'if (localStorage.theme !== undefined) {';
		$output_html .= 'document.documentElement.setAttribute("data-stylesheet", localStorage.theme);';
		$output_html .= '};';		
	}
} else {
	$output_html .= 'if (localStorage.theme !== undefined) {';
	$output_html .= 'document.documentElement.setAttribute("data-stylesheet", localStorage.theme);';
	$output_html .= '};';
}

$output_html .= '
</script>';
 
$output_html .= '<link rel="icon" type="image/png" href="' . $prefix_folder  . '/assets/img/favicon.png"/>';

	foreach ($config['css'] as $css) {
		$output_html .= '<link rel="stylesheet" type="text/css" href="' . $prefix_folder . '/assets/css/' . rawurlencode($css) . '.css">';
	}

	foreach ($config['js'] as $js) {
		$output_html .= '<script type="text/javascript" src="' . $prefix_folder . '/assets/js/' . $js . '"></script>';
	}


