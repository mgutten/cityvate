<?php
session_start();
require_once('../db_functions.php');

$activities_call = new Activities();

//for changing month on member homepage
if(!empty($_GET['month'])){
	$new_month = $_GET['month'];
	
	$activities = $activities_call->activities($new_month);
	//add month to activities array
	array_unshift($activities,$new_month);
	
	echo json_encode($activities);
}

//for retrieving information on clicked activity calendar.php
elseif(!empty($_POST['activity'])){
	
	$activity_desc = $activities_call->activity_desc($_POST['activity']);

	echo json_encode($activity_desc);
}




?>