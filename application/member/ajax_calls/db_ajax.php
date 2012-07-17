<?php

$activities_call = new Activities();

//for changing month on member homepage
if(!empty($_GET['month'])){
	$new_month = $_GET['month'];
	//subtract 1 to convert to array values
	$start = $_GET['start'] - 1;
	
	$activities = $activities_call->activities($new_month,0,'regular');
	$activities_new = array();
	$activities_done = array();
	$activities_not = array();
	$activities_new[1] = 0;
	
	$b = 2;
		
	//convert date (e.g. 2012-05-19) to unix timestamp
	for($i=0; $i < count($activities); $i++){
		
		$activities[$i]['expire'] = strtotime($activities[$i]['expire']);
		
		if($activities[$i]['done'] == 1){
			array_push($activities_done,$activities[$i]);
			continue;
		}
		else
			array_push($activities_not,$activities[$i]);
	}
	
	//find ending amount (either 5 bars or til the end of array)
	if(count($activities_not) - $start > 5){
		 $end = 5;
		 
	}
	else{
		$end = count($activities_not);
		$activities_new[1] = 0;
	}
	
	for($i=$start; $i < $end; $i++) {
		//convert old array to final array
		$activities_new[$b] = $activities_not[$i];
		$b++;
		
	}
	//place done array in first index of final array
	array_unshift($activities_new, $activities_done);
	
	//if there are more than 5 activities, then set
	//final array value to show that there are more pages
		 $activities_new[1] = ceil(count($activities_not) / 5);
	
	//var_dump($activities_new);
	echo json_encode($activities_new);
}

//for retrieving information on clicked activity calendar.php
elseif(!empty($_POST['activity'])){
	
	$activity_desc = $activities_call->activity_desc($_POST['activity']);

	echo json_encode($activity_desc);
}

//removing activity from user's activity
//table (from member.php clicked x button)
elseif(!empty($_POST['aid'])){
	$remove_activity = new Activities();
	$remove_activity->remove_activity($_POST['aid']);
}
