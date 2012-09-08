<?php

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
	
	//if checkbox for auto renew is unchecked, create false
	if(!isset($_POST['auto_renew']) && isset($_POST['package']))
			$_SESSION['signup']['auto_renew'] = 0;
				
	//if username/password has been submitted
	//send to check against database
	if(!empty($_POST['usernameemail'])){
		
		$user = new User();
		
		unset($_SESSION['signup']['email_fail']);
		unset($_SESSION['signup']['username_fail']);
		
		//check if username is valid email format
		if(!preg_match('/^[a-zA-Z0-9_\-\.]+@[a-z]+\.[com|net|edu|org|biz]+$/i',$_POST['usernameemail'])){
			$_SESSION['signup']['email_fail'] = true;
			header('location:/signup/step2');
			exit;
		}
		//check if username is already taken
		elseif($user->check_username($_POST['usernameemail']) !== false){
			unset($_SESSION['user']['username_fail']);
			$_SESSION['signup']['username_fail'] = true;
			header('location:/signup/step2');
			exit;
		}
		unset($_SESSION['user']['username_fail']);
		
	}
	
header('location:' . $url['signup'] . '/step'.$next.'');
