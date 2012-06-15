<?php
session_start();
require_once('../db_functions.php');

//removing activity from user's activity
//table (from member.php clicked x button)

	$remove_activity = new Activities();
	$remove_activity->remove_activity($_POST['aid']);
	

?>
	
	
	