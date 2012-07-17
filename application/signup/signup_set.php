<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');
	//set default next to step 1
	$next = 1;

	
	//if past default, then go to next step
	if(!empty($_GET['pos']))
		$next = $_GET['pos'];

	
	//loop through post var and set session var
	foreach($_POST as $key=>$value) {
		$_SESSION['signup'][$key]=$value;
	}
	

	
	//if checkbox for auto renew is unchecked, create "no"
	if(!isset($_POST['auto_renew']) && isset($_POST['package']))
			$_SESSION['signup']['auto_renew']='No';
				
	//if username/password has been submitted
	//send to check against database
	if(!empty($_POST['usernameemail'])){

		if(!preg_match('/^[a-zA-Z0-9_\-\.]+@[a-z]+\.[com|net|edu|org|biz]+$/i',$_POST['usernameemail'])){
			$_SESSION['user']['email_fail'] = true;
			header('location:signup_2.php');
			exit;
		}
		
		$conn = new User();
		$conn->login($_POST['usernameemail'],'',1);

	}
	
header('location:../signup/step'.$next.'');
