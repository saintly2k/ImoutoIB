<?php 


//just copy paste one and edit it

$config['cat']['10'] = array(
	'id' => '10',
	'name' => 'Testing Boards'
);

/*$config['cat']['20'] = array(
	'id' => '20',
	'name' => 'Category 2'
);*/

/*$config['cat']['30'] = array(
	'id' => '30',
	'name' => 'Other'
);*/


$config['boards']['img'] = array(
	'url' => 'img',
	'title' => 'imageboard',
	'slogan' => 'Imageboard Demo',
	'description' => 'You can test the imageboard functions here.',
	'locked' => 0,
	'nsfw' => 0,
	'cat' => 10,
	'hidden' => 0,
	'mod_only' => 0,
	'type' => 'img'
);

$config['boards']['txt'] = array(
	'url' => 'txt',
	'title' => 'textboard',
	'slogan' => 'Textboard Demo',
	'description' => 'You can test the textboard functions here.',
	'locked' => 0,
	'nsfw' => 0,
	'cat' => 10,
	'hidden' => 0,
	'mod_only' => 0,
	'type' => 'txt'
);

$config['boards']['mod'] = array(
	'url' => 'mod',
	'title' => 'Moderator Board',
	'slogan' => 'They fear the janny.',
	'description' => 'Only readable and writeable by moderators.',
	'locked' => 0,
	'nsfw' => 0,
	'cat' => 30,
	'hidden' => 1,
	'mod_only' => 1,
	'type' => 'img'
);

$config['boards']['hidden'] = array(
	'url' => 'hidden',
	'title' => 'hidden',
	'slogan' => 'shh.',
	'description' => 'testing.',
	'locked' => 0,
	'nsfw' => 0,
	'cat' => 30,
	'hidden' => 1,
	'mod_only' => 0,
	'type' => 'txt'
);

foreach ($config['boards'] as $boards) {
	$config['boardlist'][] = $boards['url'];
}


?>