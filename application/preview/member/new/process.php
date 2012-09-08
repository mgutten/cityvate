<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

//protect from direct traffic
if(empty($_POST['total_spent']) &&
	empty($_POST['refund_amt'])){
		
	echo "<button onclick='window.location=\"" . $url['new'] . "\"'>Back</button>";
	die( "You have not filled out the proper fields.  Please try again.");
}

//declare User class
$user = new User();

$user_balance = $_SESSION['user']['tokens_balance'] + constant(strtoupper($_SESSION['user']['package']) . '_TOKENS');
$total_spent = $_POST['total_spent'];
$leftover = $_POST['leftover'];
$refund_amt = $_POST['refund_amt'];
 

//protect from hackers either spending too much or asking for too much refund
if($total_spent > $user_balance || $refund_amt > $user_balance 
	|| $refund_amt != ($user_balance - $total_spent)){
		echo "<button onclick='window.location=\"" . $url['new'] . "\"'>Back</button>";
		die(' Incorrect balance of tokens detected, please try again.');
	}


//if not carrying over, convert to cash amount
if($leftover != 'carryover')
	$refund_amt = $refund_amt * -1 * EXCHANGE;


$tid = $user->check_new_activities();
$val_array = array();

//determine if inserting new rows or updating old ones
if(!empty($tid)) {
	//update old rows
	$user->update('transactions',
					array('date_processed'=>'CURDATE()',
						  'amount'=>$refund_amt,
						  'type'=>$leftover),
					'AND MONTH(date_processed) = "' . date('n') . '"'
					);
	
	//delete old rows in a_transactions	
	$user->delete('a_transactions','tid = "' . $tid[end(array_keys($tid))]['tID'] . '"');
	
	foreach($_POST['activities_list'] as $key=>$val){		
	
		array_push($val_array, array('atid'=>'',
						  'tid'=>$tid[end(array_keys($tid))]['tID'],
						  'uid'=>$_SESSION['user']['uID'],
						  'aid'=>$val,
						  'date_processed'=>'CURDATE()',
						  'qty'=>(!empty($_POST['qty'][$val]) ? $_POST['qty'][$val] : '1')));
						  
											  
	}
	
	//insert several new values of a_transactions at once
	$user->insert('a_transactions',$val_array);
	

}
else{
	//record monetary transaction
	$user->insert('transactions',array('tid'=>'',
										  'uid'=>$_SESSION['user']['uID'],
										  'date_processed'=>'CURDATE()',
										  'amount'=>$refund_amt,
										  'type'=>$leftover
										  ));
	
	
	//loop through activities list and record info to a_transactions
	//and u_activities
	//solve issues w/ standardizing tid
	$user->query .= 'SET @tid = last_insert_id();';
	
	foreach($_POST['activities_list'] as $key=>$val){		
	
		array_push($val_array, array('atid'=>'',
						  'tid'=>'@tid',
						  'uid'=>$_SESSION['user']['uID'],
						  'aid'=>$val,
						  'date_processed'=>'CURDATE()',
						  'qty'=>(!empty($_POST['qty'][$val]) ? $_POST['qty'][$val] : '1')));
						  
											  
	}
	
	$user->insert('a_transactions',$val_array);

}



//execute generated query
$user->execute();
