<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

//declare User class
$user = new User();

//if new activities have already been reserved/chosen and recorded
//do not allow for processing to happen again
/*if(!empty($_COOKIE['new_activities']) ||
	$user->check_new_activities() === true)
	die('You have already redeemed your new activities.');*/

$user_balance = $_SESSION['user']['tokens_balance'] + constant(strtoupper($_SESSION['user']['package']) . '_TOKENS');
$total_spent = $_POST['total_spent'];
$leftover = $_POST['leftover'];
$refund_amt = $_POST['refund_amt'];

/*
//create str to be added to a_transactions of qty
$qty_addon = '';
$last = end(array_keys($_POST['activities_list']));

foreach($_POST['activities_list'] as $key=>$val){
	
	$qty_addon .= $_POST['qty'][$val];

	if($key !== $last)
		$qty_addon .= ',';
}
*/

//protect from hackers either spending too much or asking for too much refund
if($total_spent > $user_balance || $refund_amt > $user_balance 
	|| $refund_amt != ($user_balance - $total_spent)){
		echo "<button onclick='window.location=\"/member/new\"'>Back</button>";
		die(' Incorrect balance of tokens detected, please try again.');
	}
	
//calculate cash refund to be used later 
$cash_refund = $refund_amt * EXCHANGE;

//determine if inserting new rows or updating old ones
if($user->check_new_activities() === true) {
	//update old rows
	$user->update('transactions',
					array('date_processed'=>'CURDATE()',
						  'amount'=>-$cash_refund,
						  'type'=>$leftover),
					'AND MONTH(date_processed) = "' . date('n') . '"'
					);
	
}
else{
//record monetary transaction
$user->insert('transactions',array('tid'=>'',
									  'uid'=>$_SESSION['user']['uID'],
									  'date_processed'=>'CURDATE()',
									  'amount'=>-$cash_refund,
									  'type'=>$leftover));


//loop through activities list and record info to a_transactions
//and u_activities
//solve issues w/ standardizing tid
$user->query .= 'SET @tid = last_insert_id();';

$val_array = array();
foreach($_POST['activities_list'] as $key=>$val){		

	array_push($val_array, array('atid'=>'',
					  'tid'=>'@tid',
					  'uid'=>$_SESSION['user']['uID'],
					  'aid'=>$val,
					  'date_processed'=>'CURDATE()',
					  'qty'=>(!empty($_POST['qty'][$val]) ? $_POST['qty'][$val] : '1')));
					  
										  
}

$user->insert('a_transactions',$val_array);

/*
//record activity transaction in db
$last = end(array_keys($_POST['activities_list']));
$query = 'INSERT INTO a_transactions (atid,
										tid,
										uid,
										aid,
										qty,
										date_processed) VALUES ';

foreach($_POST['activities_list'] as $key=>$val){
	$query .= '("",
				"' . $user->con->insert_id . '",
				"' . $_SESSION['user']['uID'] . '",
				"' . $val . '",
				"' . $_POST['qty'][$val] . '",
				CURDATE())';
				
	if($key != $last)
		$query .= ',';
						
}

$query .= ';';


/*
//$user->insert('a_transactions',array('atid'=>'',
										'tid'=>$user->con->insert_id,
										'uid'=>$_SESSION['user']['uID'],
										'aids'=>implode($_POST['activities_list'],','),
										'qty'=>$qty_addon,
									//	'date_processed'=>'CURDATE()'));
										//

//insert reserved activities into u_activities										
$query .= 'INSERT INTO u_activities VALUES ';								

foreach($_POST['activities_list'] as $key=>$val){	

	$query .= '("' . $_SESSION['user']['uID'] . '","' . $val . '", NULL, "0", "' . $_POST['qty'][$val] . '")';
	
	if($key != $last)
		$query .= ',';
}
										

$user->con->multi_query($query);
*/
}

$user->execute();

setcookie('new_activities',true,time()+(3600*72));


