<?php 

if ($current_page === 'thread' && $config["boards"][$current_board]["type"] != "txt") { 
	$output_html .= '<div class="postingmode">Posting mode: Reply</div>'; 
}
if ($current_page != ('thread') && $current_page != ('index') && $current_page != ('catalog')) {
	$output_html .= '<style>#post-form { display: none; }</style><div class="blotter">(Locked!)</div>';
}

$output_html .= '<div id="post-form"><a name="postform"></a>
<form enctype="multipart/form-data" name="post" action="' . $prefix_folder . '/post.php" method="post">
	<table>';

$output_html .= '<!--Hidden Inputs-->';
	$output_html .= '<input type="hidden" id="board" name="board" value="' . $current_board .'">';
	if ($current_page === 'thread') { $output_html .= '<input type="hidden" id="thread" name="thread" value="thread">'; }
	if ($current_page === 'thread') { $output_html .= '<input type="hidden" id="thread_number" name="thread_number" value="' . $post_number_op . '">'; }
	if ($current_page === 'index' || $current_page === 'catalog') { $output_html .= '<input type="hidden" id="index" name="index" value="index">'; }
	
	if ($forced_anon !== true) { 
	$output_html .= '<tr>';
	$output_html .=	'<th>Name</th>';
	if ($info_locked == 1 && $config['mod']['post_in_locked'] > $mod_level) { 
		$output_html .= '<td><input type="text" name="name" size="25" maxlength="256" autocomplete="off" placeholder="' . $default_name . '" disabled=""></td>';
	} else {
		$output_html .= '<td><input type="text" name="name" size="25" maxlength="256" autocomplete="off" placeholder="' . $default_name . '"></td>';
	}
	$output_html .= '</tr>'; 
	} 

	if ($disable_email !== true) { 
		$output_html .= '<tr>';
		$output_html .= '<th>Email</th>';
	if ($info_locked == 1 && $config['mod']['post_in_locked'] > $mod_level) { 
		$output_html .= '<td><input type="text" name="link" size="25" maxlength="256" autocomplete="off" disabled></td>';
	} else {
		$output_html .= '<td><input type="text" name="link" size="25" maxlength="256" autocomplete="off"></td>';
	}
		$output_html .= '</tr>';
	}

	$output_html .= '<tr>';
	$output_html .= '<th>Subject</th>';
	if ($info_locked == 1 && $config['mod']['post_in_locked'] > $mod_level) {
		$output_html .= '<td><input type="text" name="subject" size="25" maxlength="256" autocomplete="off" disabled>&nbsp;<input type="submit" name="post" value="Locked" disabled></td>';
	} elseif ($info_locked == 1 && $config['mod']['post_in_locked'] <= $mod_level) {
		$output_html .= '<td><input type="text" name="subject" size="25" maxlength="256" autocomplete="off">&nbsp;<input type="submit" name="post" value="Locked"></td>';
	} else {
		if (($config['post_require_subject'] == true) && ($current_page === 'index')) {
			$output_html .= '<td><input type="text" name="subject" size="25" maxlength="256" autocomplete="off" required>&nbsp;';
		} else {
			$output_html .= '<td><input type="text" name="subject" size="25" maxlength="256" autocomplete="off">&nbsp;';
		}
		if ($current_page == 'index' || $current_page == 'catalog') {
			$output_html .= '<input type="submit" name="post" value="Submit"></td>';
		}
		if ($current_page == 'thread') {
			$output_html .= '<input type="submit" name="post" value="Reply"></td>';
		}
		$output_html .= '</tr>';
	}

	$output_html .= '<tr>';
	$output_html .= '<th>Comment</th>';
	$output_html .= '<td>';
	if ($info_locked == 1 && $config['mod']['post_in_locked'] > $mod_level) {
		$output_html .= '<textarea name="body" id="body" rows="5" cols="30" disabled></textarea>';
	} else {
		$output_html .= '<textarea name="body" id="body" rows="5" cols="30"></textarea>';
	}
	$output_html .= '<input style="height:1px;width:1px;z-index:-10;margin-left:-100px;margin-top:5px;position: absolute;" type="text" id="username" name="username" value=""></td></tr>';

	if ($captcha_required == (true) && ($current_page == ('thread') && ($info_locked != 1 || $config['mod']['post_in_locked'] <= $mod_level) || $current_page == ('index') || $current_page == ('catalog'))) {
		$output_html .= '<tr><th>Verification</th><td>
			<details class="js-captcha"><summary id="load-captcha">View Verification</summary>
			<span class="js-captcha">
				<img title="Click Here To Refresh" height="50" width="198" id="captcha" js-src="' . $prefix_folder . '/captcha.php' .'"/><br>
			</span>
			</details>
			<noscript>
				<style>.js-captcha { display:none }</style>
				<img height="50" width="198" id="no-js-captcha" src="' . $prefix_folder . '/captcha.php' .'"/><br>
			</noscript>
			<input id="captcha-field" type="text" name="captcha" minlength="6" maxlength="6" autocomplete="off" required>
		</td>
		';
	}

	if ($allow_files == true) {
		$output_html .= '<tr>';
		$output_html .= '<th>File</th>';
		if ($info_locked == 1 && $config['mod']['post_in_locked'] > $mod_level) {
		$output_html .= '<td><input type="file" name="file" id="upload" disabled></td>';
		} else {
		$output_html .= '<td><input type="file" name="file" id="upload"></td>';
		}
		$output_html .= '</tr>';
	}

$output_html .= '
	</table>
	<details>
	    <summary>More</summary>
	    <table>
	    	<tr>
	    		<th>Password</th>
	    		<td><input id="post_password" type="password" name="password" size="25" maxlength="256" value="' . $_COOKIE['post_password'] . '"></td>
	    	</tr>
	    	<tr>
	    		<th>Options</th>
	    		<td>';
	    			if (($spoiler_enabled == true) && ($allow_files == true)) {
	    				$output_html .= '
	    				<label for="spoiler"><input type="checkbox" id="spoiler" name="spoiler" autocomplete="off"> Spoiler Image</label>
	    				<div class="small">You may also type <i>spoiler</i> in the email field.</div>';
	    			}
	    			$output_html .= '<label for="sage"><input type="checkbox" id="sage" name="sage" autocomplete="off"> No Bump</label>
	    			<div class="small">You may also type <i>sage</i> in the email field.</div>';

	    			if ($current_page != 'thread' && $mod_level > 0) {
	    				$output_html .= '<div class="small"><b>Moderator tools:</b></div>';
	    				if ($config['mod']['thread_sticky'] <= $mod_level) {
		    				$output_html .= '<label for="sticky"><input type="checkbox" id="sticky" name="sticky" autocomplete="off"> Sticky Thread</label><br>';
	    				}
	    				if ($config['mod']['thread_lock'] <= $mod_level) {
		    				$output_html .= '<label for="lock"><input type="checkbox" id="lock" name="lock" autocomplete="off"> Lock Thread</label><br>';
	    				}
	    				if ($config['mod']['thread_autosage'] <= $mod_level) {
		    				$output_html .= '<label for="autosage"><input type="checkbox" id="autosage" name="autosage" autocomplete="off"> Autosage Thread</label><br>';
	    				}
	    			}

    				if ($config['mod']['public_mod_level'] <= $mod_level) {
	    				$output_html .= '
	    				<label for="mod_level"><input type="checkbox" id="mod_level" name="mod_level" autocomplete="off"> Display Mod Level</label>
	    				<div class="small">You may also type <i>showlevel</i> in the email field.</div>';
    				}

$output_html .= '
	    		</td>
	    	</tr>
	    </table>
	</details>
</form>
</div>';

if ($config["boards"][$current_board]["type"] != "txt") {
$output_html .= '<hr>';
}

