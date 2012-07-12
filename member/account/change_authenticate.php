<?php
session_start();
require_once('../../db_functions.php');

$password = trim($_POST['password']);
$type = trim($_GET['type']);
$change = trim($_POST[$type]);

//create column array for change fn below
$column_array = array($type=>$change);

//if name column, explode into fname and lname columns
if($type == 'name'){
	$change = explode(' ', $change);

	if(count($change) != 2){
		$_SESSION['user']['name_fail'] = true;
		header('location:change.php?type=' . $type);
		exit;
	}
	$column_array = array('fname'=>$change[0],
							'lname'=>$change[1]);
}
	

$user = new User();
//if password is correct, change the column to new val
//then redirect to account
if($user->check_password($password) === true){
	//test to see if new username is in email format
	if($type == 'username' && !preg_match('/^[a-zA-Z0-9_\-\.]+@[a-z]+\.[com|net|edu|org|biz]+$/i',$change)){
		$_SESSION['user']['username_fail'] = true;
		header('location:change.php?type=' . $type);
		exit;
	}
	
	//change db value of column to new value
	$user->change($column_array);
	
	$_SESSION['user']['change_success'] = $type;
	header('location:' . $GLOBALS['file_adj'] . 'member/account.php');
	exit;
}
else{
	$_SESSION['user']['password_fail'] = true;
	header('location:change.php?type=' . $type);
}