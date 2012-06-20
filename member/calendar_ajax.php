<?php
session_start();
require_once('../html_display.php');
require_once('../db_functions.php');
$activities_call = new Activities;

//for changing month when arrows clicked
if(!empty($_POST['month'])){
$selected_month = $_POST['month'];
$year = $_POST['year'];

$calendar = new Calendar();
$calendar->calendar_calls($selected_month,$year);

$calendar->create_calendar();
}

//for post sent from calendar.php when reserve button is clicked
if(!empty($_POST['aid'])){
	
	$date = $_POST['date'];
	$aid = $_POST['aid'];
	$hours = $_POST['alert_hours'];
	$minutes = $_POST['alert_minutes'];
	//if at noon or midnight, adjust hours
	//to 24 hour format
	if($hours == 12)
		$hours -= 12;
	//add 12 hours if in PM to convert to
	//24 hour format
	if($_POST['alert_ampm']=='PM'){
		$hours += 12;
	}
	$time = $hours.':'.$minutes.':00';
	
	$new_date = $date.' '.$time;
	
	$activities_call->change_reserve($aid,$new_date);
	
	header('location:calendar.php');
	exit;	
}

//for changing calendar.php from current to done activities
elseif(!empty($_POST['done'])){
	
	//if current button is clicked, switch to non-done activities
	if($_POST['done'] == 'current')
		$new_status = 'regular';
	elseif($_POST['done'] == 'done')
		$new_status = 'done';
	else
		$new_status = 'expire';
	
	$activities_done = $activities_call->activities('',0,$new_status);
	
	calendar_my_activities($activities_done);
	
}

//for cancel reservation in calendar.php
if(!empty($_GET['aID'])){
	$activities_call->change_reserve($_GET['aID'],'NULL');
	
	header('location:calendar.php');
	exit;
}

?>