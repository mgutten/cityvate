<?php

//position var to show position in signup process
$pos = 2;

$head = new Head_signup('Signup Account',0);
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Account Info',$pos);

$form = new Form_signup($pos);

$title_array = array('full name'=>'Must be accurate to redeem deals',
					'username/email'=>'Must be a valid email',
					'password'=>'6-12 characters');

//create input boxes
signup_boxes($title_array);


$form->back();
$form->next_button();
$form->close();

//if the username existed and session was set,
//unset it to try again with clean slate
if(!empty($_SESSION['user']['username_fail']))
	unset($_SESSION['user']['username_fail']);
if(!empty($_SESSION['user']['email_fail']))
	unset($_SESSION['user']['email_fail']);
