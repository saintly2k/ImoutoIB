<?php 


//just copy paste one and edit it

$config['boards']['test'] = array(
	'url' => 'test',
	'title' => 'test',
	'description' => 'QA? I\'m not getting paid!!',
	'locked' => 0,
	'hidden' => 0
);

$config['boards']['d'] = array(
	'url' => 'd',
	'title' => 'demo',
	'description' => 'Just a second test board...',
	'locked' => 0,
	'hidden' => 0
);

$config['boards']['1'] = array(
	'url' => '1',
	'title' => '1GET',
	'description' => '1st!!',
	'locked' => 1,
	'hidden' => 0
);


foreach ($config['boards'] as $boards) {
	$config['boardlist'][] = $boards['url'];
}


?>