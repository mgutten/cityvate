<?php
require_once('html_display.php');

$login = 'out';

$head = new Head($login,'Signup');
$head->close();

$header = new Header($login);

$body = new Body();

$form = new Form('signup_set.php','POST');
$form->input('image','budget','select','images/signup/select_button.png','budget');
$form->input('image','basic','select','images/signup/select_button.png','basic');
$form->input('image','premium','select','images/signup/select_button.png','premium');
$form->close();

?>

<div onclick='$(".why").toggle()' class="text" id="why_button">
	Why pay up front?
</div> 

<div class='fade_box' id='fade1'>
	<div class='text title' id='text1'>
    	Types of Activities
    </div>
</div>

<?php

$preview = new Preview_button('preview');

?>

<div class='fade_box' id='fade2'>
	<div class='text title' id='text2'>
    	How it Works
    </div>
</div>

<?php
$why = new Alert('why','why_alert');

if(empty($_SESSION['signup'])) {
	$calm = new Alert('calm','calm_alert');
	$_SESSION['signup'] = 'set';
}

?>

	