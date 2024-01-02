<?php

//ONLY MAKE CONFIGURATIONS IN THIS FILE

$prefix_folder = '/ImoutoIB'; // empty for root dir
$main_file = ''; //empty with handler using main.php as index

$site_name = 'ImoutoIB';
$domain = 'localhost'; //MUST BE SET FOR COOKIES
$secure_hash = "SQp3FaEgyMyHe3=Zc!-vS%ya6W!JAt+9fqwdbGk&ev!hbG!nSMgN_KUbLrmRpCQy"; //Will be used to hash your post passwords. You should change this.

$config['footer'] = []; //Display name, URL
$config['footer'][] = array('Home', $prefix_folder . '/' . $main_file);
$config['footer'][] = array('News', $prefix_folder . '/' . $main_file . '?page=news');
$config['footer'][] = array('Rules', $prefix_folder . '/' . $main_file . '?page=rules');
$config['footer'][] = array('Help', $prefix_folder . '/' . $main_file . '?page=help');
$config['footer'][] = array('About', $prefix_folder . '/' . $main_file . '?page=about');
$config['footer'][] = array('Contact', $prefix_folder . '/' . $main_file . '?page=contact');
$config['footer'][] = array('Legal', $prefix_folder . '/' . $main_file . '?page=legal');
$config['footer'][] = array('Manage', $prefix_folder . '/mod.php');
