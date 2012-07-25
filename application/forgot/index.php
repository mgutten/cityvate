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
$body->create_input($array, '/mail', 'submit', 'return validate("forgot")','forgot');
