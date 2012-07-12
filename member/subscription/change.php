<?php
/* file location member/subscription/change.php */

$login = 'in';

require_once('../../classes.php');

$pos = 0;

$type = ucwords(str_replace('_',' ',$_GET['type']));

$head = new Head_signup($login,'Member Change Subscription',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background("Change Subscription", $pos);

//set package_type array from standard package_cost array
$package_type = $body->package_cost;

//array of first input box given specific $get var values
//to be pushed into $input_array lower down
$first_input_array = array('Package'=>array('type'=>array(),
											'lower'=>'Please select a new package'),
							'End Date'=>array('type'=>array(),
												'lower'=>'Select your final month for Cityvate')
					);
	

//loop through package type array and format values in first_input_array									
foreach($package_type as $key=>$val){
	
	//if key is user's current package, do not display
	if($key == $_SESSION['user']['package'])
		continue;
	
	array_push($first_input_array['Package']['type'],ucwords($key) . ' ($' . $val . '/mo)');
}
					
//create array for next 4 months in case of End Date selected					
for($i=1; $i<5; $i++) {
	array_push($first_input_array['End Date']['type'],date('F Y',strtotime("+" . $i . "months")));
}

//final array to be submitted to create_input fn
$input_array = array($type => $first_input_array[$type],
						'Password' => array('type'=>'password',
											'lower'=>'Enter your current password')
				);

$body->create_input($input_array,'subscription_change_authenticate.php?type=' . $_GET["type"],'return validate("change_subscription")','change_subscription');

