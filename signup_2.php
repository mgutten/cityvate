<?php

require_once('html_display.php');

$login = 'out';
$pos = 2;

$head = new Head_signup($login,'Signup Account',0);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Account Info',$pos);

$form = new Form_signup($pos);

$title_array = array('full name'=>'Must be accurate to redeem deals','username/email'=>'Must be a valid email','password'=>'6-12 characters');
signup_boxes($title_array);


$form->back();
$form->next_button();
$form->close();

?>