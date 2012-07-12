<?php

$login = 'in';

require_once('../classes.php');

$pos = 0;

$type = ucwords(str_replace('_',' ',$_GET['type']));

$head = new Head_signup($login,'Member Change Subscription',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background("Change Subscription", $pos);

$first_input_array = array('Package'=>array('type'=>array('Budget',
															'Basic',
															'Premium'),
											'lower'=>'Please select a new package'),
							'End Date'=>array('type'=>array(),
												'lower'=>'Select your final month for Cityvate')
					);
					
for($i=1; $i<5; $i++) {
	array_push($first_input_array['End Date']['type'],date('F Y',strtotime("+" . $i . "months")));
}


$input_array = array('Select ' . $type => $first_input_array[$type],
						'Password' => array('type'=>'password',
											'lower'=>'')
				);

$body->create_input($input_array,'something.php','alert()','change_subscription');
?>
