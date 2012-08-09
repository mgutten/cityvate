<?php

//ajax call from /member/new/index.php to load bottom_right activity info
if(!empty($_POST['aid'])){
		
	$activities_call = new Activities();
	$activity = $activities_call->activity_desc($_POST['aid'],0,0);
	
	//convert month_in_use to 0 format (ie 02, 03, 09, 11)
	($activity['month_in_use'] < 10 ? $activity['month_in_use'] = '0' . $activity['month_in_use'] : '');
	
	//store description txt in desc value
	$activity['desc'] = txt_file('description', $activity['month_in_use'], $activity['name']);
	
	
	//return encoded array to new.js
	echo json_encode($activity);
	
}
	