<?php
session_start();

	//set default next to step 1
	$next = 1;
	
	//if past default, then go to next step
	if(!empty($_GET['pos']))
		$next = $_GET['pos'];
	
	foreach($_POST as $key=>$value) {
		$_SESSION['signup'][$key]=$value;
	}
	
	//if checkbox for auto renew is unchecked, create "no"
	if(!isset($_POST['auto_renew']) && isset($_POST['package']))
			$_SESSION['signup']['auto_renew']='No';
				
	//if username/password has been submitted
	//send to check against database
	if(!empty($_POST['usernameemail'])){
////////////////////////////////////////////////$conn = new User_check($_POST['username'],$_POST['password']);
	}
	
	
header('location:signup_'.$next.'.php');
?>