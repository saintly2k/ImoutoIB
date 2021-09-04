<?php 


//just copy paste one and edit it
//hidden board should be up top for now

$config['boards']['d'] = array(
	'url' => 'd',
	'title' => 'demo',
	'description' => 'Please don\'t break me, I\'ll be sad...',
	'locked' => 0,
	'hidden' => 0
);

$config['boards']['test'] = array(
	'url' => 'test',
	'title' => 'test',
	'description' => 'QA? I\'m not getting paid!!',
	'locked' => 0,
	'hidden' => 1
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