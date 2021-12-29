<?php

$output_html .= '
	<details>
	    <summary></summary>
	    <form name="post_button" action="' . $prefix_folder . '/delete-report.php" method="post">
		    <table>
		    	<tbody>';

if ($mod_level > 0) {
	$output_html .= '<tr><td><div class="small"><b>Moderator Tools:</b></div>';
	if ($config['mod']['ban'] <= $mod_level) {
			$output_html .= '<details><summary>Ban</summary><table id="post-form" style="width:initial;">
		<tbody>
		<tr><th>Reason:</th><td><input type="text" name="ban-reason" size="25" maxlength="256" autocomplete="off" placeholder="Reason"></td></tr>
		<tr><th>Duration:</th><td>
		<select name="ban-expire">
		  <option value="permanent">Permanent</option>
		  <option value="31104000">1 Year</option>
		  <option value="7776000">3 Months</option>
		  <option value="2592000">1 Month</option>
		  <option value="1209600">2 Weeks</option>
		  <option value="604800">1 Week</option>
		  <option value="259200">3 Days</option>
		  <option value="86400">1 Day</option>
		  <option value="3600">1 Hour</option>
		  <option value="warning" selected>Warning</option>
		</select>
		</td></tr>
		<tr><th>Public:</th><td><label for="public_' . $post_number_reply . '"><input type="checkbox" id="public_' . $post_number_reply . '" name="public">Public Ban Message</label><input type="text" name="ban-message" size="25" maxlength="256" autocomplete="off" placeholder="(User was banned for this post.)"></td></tr>
		<tr><th style="visibility:hidden;"></th><td><details style="float:right;"><summary style="text-align:right;">Ban</summary><input type="submit" name="create-ban" value="Create Ban"></details></td></tr>
	</tbody></table></details>';
	}

	$output_html .= '</td></tr>';
}



$output_html .= '<tr><td>

				<details><summary>Delete</summary>
					<input type="hidden" name="board" value="' . $current_board . '"/>
		    		<input type="hidden" name="thread" value="' . $post_number_op . '"/>
		    		<input type="hidden" name="reply" value="' . $post_number_reply . '"/>

		    		<input type="password" id="password_' . $post_number_reply . '" name="password" maxlength="256" placeholder="Password" value="' . $_COOKIE['post_password'] . '">
		    		<input type="submit" name="delete" value="Delete">
		    		<label for="file_' . $post_number_reply . '"><input type="checkbox" id="file_' . $post_number_reply . '" name="file">File only</label>

				</details>

				</td></tr>
					<tr>
						<td>

						<details><summary>Report</summary><a href="'.$prefix_folder.'/report.php?board='.$current_board.'&thread='.$post_number_op.'&reply='.$post_number_reply.'" onclick="window.open(this.href,\'targetWindow\',
                       `toolbar=no,
                        location=no,
                        status=no,
                        menubar=no,
                        scrollbars=yes,
                        resizable=yes,
                        width=420,
                        height=220`);
                        return false;">[Report]</a>
                        </details>

						</td>
					</tr>
				</tbody>
			</table>
		</form>
		</details>';

?>