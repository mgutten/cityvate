<?php
session_start();
require_once('../db_functions.php');

$activities_call = new Activities();

if(!empty($_GET['month'])){
	$new_month = $_GET['month'];
	
	$activities = $activities_call->activities($new_month);
	//add month to activities array
	array_unshift($activities,$new_month);
	
	echo json_encode($activities);
}

