<?php

require 'require.php';


if (isset($_GET["board"])) {
	$rep_board = phpClean($_GET["board"]);
	if (!in_Array(htmlspecialchars($_GET["board"]), $config['boardlist'])) {
		error('invalid board, what are you even trying to do it checks for this in the other file too btw');
	}
}
if (isset($_GET["thread"])) {
	$rep_thread = phpClean($_GET["thread"]);
}
if (isset($_GET["reply"])) {
	$rep_reply = phpClean($_GET["reply"]);
}

if (!isset($_GET["board"]) || !isset($_GET["thread"]) || !isset($_GET["reply"]) ) {
	error('missing parameter board/thread/reply');
}

if (ctype_alnum($rep_board) != true || ctype_alnum($rep_thread) != true || ctype_alnum($rep_reply) != true) {
	error('Invalid board, thread, or reply. Must be alphanumeric.');
}

$title = 'Report Post No.' . $rep_reply;
$current_board = htmlspecialchars($_GET["board"]);

$output_html .= '<html data-stylesheet="'. $current_theme .'">';	
$output_html .= '<head>';
include $path . '/templates/header.php';
$output_html .= '</head>';
$output_html .= '<body class="report" style="max-width:400px">';

$output_html .= '<form action="' . $prefix_folder . '/delete-report.php' . '" method="POST">';

//reason?
//global?

$output_html .= '<table>
    <tr>
    	<th>Report</th>
    	<td><input type="text" id="reason_' . $rep_reply . '" name="reason" maxlength="256" autocomplete="off" value="" placeholder="Reason">
    			<label for="global_' . $rep_reply . '"><input type="checkbox" id="global_' . $rep_reply . '" name="global">Global</label>
    			<hr>
    	</td>
    </tr>';

if ($captcha_required == true) {
	$output_html .= '
	<tr>
		<th>Verification</th>
		<td>
			<span class="js-captcha" id="load-captcha" style="max-width:200px">
			<span class="js-captcha">
				<img title="Click Here To Refresh" height="50" width="198" id="captcha" src="' . $prefix_folder . '/captcha.php' .'" js-src="' . $prefix_folder . '/captcha.php' .'"/><br>
			</span>
			</span>
			<noscript>
				<style>.js-captcha { display:none }</style>
				<img height="50" width="198" id="no-js-captcha" src="' . $prefix_folder . '/captcha.php' .'"/><br>
			</noscript>
			<input id="captcha-field" type="text" name="captcha" minlength="6" maxlength="6" autocomplete="off" required>
			</span>
		</td>
	</tr>';
}

$output_html .= '</table>';

$output_html .= '<div class="rules">Submitting false or misclassified reports <i>may</i> result in a ban.</div>';  

$output_html .= '	<input type="hidden" name="board" value="' . $rep_board . '"/>
					    		<input type="hidden" name="thread" value="' . $rep_thread . '"/>
					    		<input type="hidden" name="reply" value="' . $rep_reply . '"/>';
$output_html .= '<input type="submit" name="report" value="Report">';
$output_html .= '</table></form>';

$output_html .= '</body>';
$output_html .= '</html>';
echo $output_html;
exit();
?>