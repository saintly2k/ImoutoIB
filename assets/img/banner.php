<?php 

//function load banners in banner



function getRandomImage($dir_path = NULL){
        $files = scandir($dir_path);
        $count = count($files);
        if($count > 2){
            $index = rand(2, ($count-1));
            $filename = $files[$index];
        return $filename;
        }

}


$banner = getRandomImage(__dir__ . '/banners'); 

echo ' <div class="banner">
	<img class="banner" src="'. $prefix_folder . '/assets/img/banners/' . $banner . '"/>
</div>';


?>