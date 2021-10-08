<?php

echo '
<style>
.ok { color: green; }
.error { color: red; }
</style>
';

echo 'Checking PHP version... ';

echo '...PHP ' . phpversion() . ' is installed. ';
if ((phpversion() < 8) && (phpversion() >= 7.3)) {
	echo '<span class="ok">OK!</span> - This was detected as equal or above 7.3, but below 8.';
} else {
	echo '<span class="error">Not OK.</span> This was NOT detected as equal or above 7.3, but below 8.';
	$error = true;
}

echo '<hr>';
echo 'Checking ImoutoIB...';
$version = file_get_contents('version');
echo ' ...version ' . $version . ' is installed. ';
echo '<span class="ok">OK</span> - but be aware this is dev version.';



echo '<hr>';
echo 'Checking read permissions for includes & database &templates...<br>
If these are wrong, your moderator panel is vulnerable to malicious post forms in the templates/mod location.<br>
This file will attempt to change them if they are wrong.';


echo '<br>';
if (chmod(__dir__ . '/includes', 0750)) {
	echo 'Includes: 0750. <span class="ok">OK!</span>';
} else {
	echo 'Includes: Couldn\'t change permissions. <span class="error">NOT OK!</span>';
	$error = true;
}

echo '<br>';
if (chmod(__dir__ . '/templates', 0750)) {
	echo 'Templates: 0750. <span class="ok">OK!</span>';
} else {
	echo 'Templates: Couldn\'t change permissions. <span class="error">NOT OK!</span>';
	$error = true;
}

echo '<br>';
if (chmod(__dir__ . '/database', 0750)) {
	echo 'Database: 0750. <span class="ok">OK!</span>';
} else {
	echo 'Database: Couldn\'t change permissions. <span class="error">NOT OK!</span>';
	$error = true;
}

echo '<hr>';
if (!isset($error)) {
	echo '<span class="ok">No <b>known</b> errors found.</span> Be sure to read the README file for your configuration for the software!';
} else {
	echo '<span class="error">Something seems to be wrong! You should check it.</span>';
}

?>