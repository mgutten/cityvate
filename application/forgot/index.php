<?php
/* location of file: application/forgot/index.php */

$head = new Head_signup('Forgot',1);
$head->close();
$header = new Header();

$body = new Body_signup();
$body->background('Forgot Password',0);

$array = array('Username'=>array('type'=>'text',
									'lower'=>'Enter your username/email'
								)
				);
$body->create_input($array, $url['mail_from_forgot'], 'submit', '','forgot');
$body->close();

$alert = new Alert_w_txt('confirmation');
$alert->confirm('reset your password?');

if(!empty($_SESSION['mail']['success'])){
	$success = new Alert_w_txt('success');
	$success->generic('Success!',
						'Your password was reset.  An email has been sent to ' . $_SESSION["mail"]["success"] . '.');
	unset($_SESSION['mail']['success']);
}
	


