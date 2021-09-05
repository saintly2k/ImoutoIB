<?php 


//just copy paste one and edit it

$config['boards']['d'] = array(
	'url' => 'd',
	'title' => 'demo',
	'description' => 'Please don\'t break me, I\'ll be sad...',
	'locked' => 0,
	'hidden' => 0,
	'type' => 'img'
);

$config['boards']['txt'] = array(
	'url' => 'txt',
	'title' => 'textboard',
	'description' => 'bulletin board system',
	'locked' => 0,
	'hidden' => 0,
	'type' => 'txt'
);

$config['boards']['test'] = array(
	'url' => 'test',
	'title' => 'test',
	'description' => 'QA? I\'m not getting paid!!',
	'locked' => 0,
	'hidden' => 1,
	'type' => 'img'
);

$config['boards']['1'] = array(
	'url' => '1',
	'title' => '1GET',
	'description' => '1st!!',
	'locked' => 1,
	'hidden' => 0,
	'type' => 'img'
);


foreach ($config['boards'] as $boards) {
	$config['boardlist'][] = $boards['url'];
}


?>