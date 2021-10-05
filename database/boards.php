<?php 


//just copy paste one and edit it

$config['boards']['img'] = array(
	'url' => 'img',
	'title' => 'imageboard',
	'description' => 'Imageboard Demo',
	'locked' => 0,
	'hidden' => 0,
	'type' => 'img'
);

$config['boards']['txt'] = array(
	'url' => 'txt',
	'title' => 'textboard',
	'description' => 'Bulletin Board System',
	'locked' => 0,
	'hidden' => 0,
	'type' => 'txt'
);

foreach ($config['boards'] as $boards) {
	$config['boardlist'][] = $boards['url'];
}


?>