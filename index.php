<?php

if(empty($_GET['route']))
	$route = '';
else
	$route = $_GET['route'];
	
	echo $_GET['route'];


if(file_exists('application/' . rtrim($route . '.php'))){
	$file = 'application/' . rtrim($route,'/') . '.php';
	
	if(!empty($_GET['action']))
	{
		$file .= '?pos='.$_GET['action'];
	}
	include_once($file);
}
elseif(is_dir('application/' . $route)){
	$file = 'application/' . rtrim($route,'/') . '/index.php';
	echo $file;
	include_once($file);
}