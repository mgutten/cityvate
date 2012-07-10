<?php

$login = 'out';

require_once('classes.php');


$head = new Head($login,'Signup');
$head->close();

$header = new Header($login);

$body = new Body();

$form = new Form('signup_set.php','POST');
$form->input_diff('image','package','budget','select','images/signup/select_button.png','budget');
$form->input_diff('image','package','basic','select','images/signup/select_button.png','basic');
$form->input_diff('image','package','premium','select','images/signup/select_button.png','premium');
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

<a href="preview/member.php" alt="Preview Cityvate"><img src="images/signup/preview.png" id="preview"/></a>

<div class='fade_box' id='fade2'>
	<div class='text title' id='text2'>
    	How it Works
    </div>
</div>

<?php
$why = new Alert('why','why_alert');

if(empty($_SESSION['signup_visit'])) {
	$calm = new Alert('calm','calm_alert');
	$_SESSION['signup_visit'] = 'set';
}

//set signup session var as blank array
$_SESSION['signup']=array();

	