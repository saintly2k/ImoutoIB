<?php


//updateFrontpage
if (isset($post_board)) {
	//FIND THREADS
		$all_threads_ = [];
		foreach ($config['boards'] as $boards_) {
			$all_threads_[$boards_['url']][] = glob(__dir__ . '/../' . $database_folder . '/boards/' . $boards_['url'] . "/*", GLOB_ONLYDIR);
		}
		$all_ips_ = [];
		foreach ($config['boards'] as $boards_) {
			foreach ($all_threads_[$boards_['url']][0] as $thread => $val) {
				//for each file
				$all_files_ = [];
				$all_files_[] = glob($val . "/*");
				foreach ($all_files_[0] as $file => $name) {
					//if op
					if (basename($all_files_[0][$file]) == ('OP.php')) {
						include $all_files_[0][$file];
						$all_ips_[] = $op_ip;
					}
					//if reply
					if (basename($all_files_[0][$file]) != ('OP.php') && basename($all_files_[0][$file]) != ('info.php') && basename($all_files_[0][$file]) != ('bumped.php') && basename($reply) != ('counter.php')) {
						include $all_files_[0][$file];
						$all_ips_[] = $reply_ip;
					}
				}
			}
		}

	//count unique
	$uniqueids_ = count(array_unique($all_ips_)); 

	$frontpage_ = '<?php ';
	$frontpage_ .= '$frontpage_uniqueids' . '=' . $uniqueids_ . ';';
	$frontpage_ .= '$frontpage_active' . '= 0;';
	$frontpage_ .= '?>';

	file_put_contents(__dir__ . '/../' . $database_folder . '/frontpage.php', $frontpage_);

	exit();

	}
?>