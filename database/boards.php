<?php 


//just copy paste one and edit it

$config['boards']['test'] = array(
	'url' => 'test',
	'title' => 'Test',
	'description' => 'QA? I\'m not getting paid!!',
	'locked' => 0,
	'hidden' => 0
);

$config['boards']['d'] = array(
	'url' => 'd',
	'title' => 'Demo',
	'description' => 'Just a second test board...',
	'locked' => 0,
	'hidden' => 0
);

$config['boards']['2d'] = array(
	'url' => '2d',
	'title' => '2D Girls',
	'description' => 'We don\'t need hurtful 3DPD.',
	'locked' => 0,
	'hidden' => 0
);


foreach ($config['boards'] as $boards) {
	$config['boardlist'][] = $boards['url'];
}


?>