<?php 

$title = 'News' . ' - ' . $site_name;
$h1 = 'News';
$description = '';

$page_content = '

<br>
<div class="main first">
	<h2>News</h2>
	<p>
		This page has not been modified.<br>
		The owner can update this page in /templates/pages/news.php or remove it from the footer in the configuration.
	</p>
</div>

<br>
<br>

<div class="main">
	<h2>First <span class="small"> — <span data-tooltip="' . timeConvert(0, $time_method_hover) . '">' . timeConvert(0, $time_method) . '</span></span></h2>
	<p>
	First news.
	</p>
</div>

<div class="message"></div>


';


?>