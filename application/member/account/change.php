<?php

/* file location member/account/change.php */

$pos=0;

$type = $_GET['type'];
$type_uc = ucwords($type);

//if someone types in url to change city, redirect back to account
if($type == 'city')
	header('location:/member/account');
//if type is subscription, redirect to subscription page
elseif($type == 'subscription')
	header('location:/member/subscription');

$head = new Head_signup('Change Info',1);
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Change ' . $type_uc,$pos);

$username_lower = '';

$type_array = array('Neighborhood'=>array('type'=>$body->neighborhoods,
											'lower'=>'Select your new neighborhood'),
					'New Password'=>array('type'=>'password',
										'lower'=>'Enter your new password'),
					'Username'=>array('type'=>'text',
										'lower'=>'Must be a valid email address'),
					'Name'=>array('type'=>'text',
									'lower'=>'Names must be in the format "John Smith"')
				);
				
if($type_uc == 'Password')
	$type_uc = 'New Password';

				
$final_array = array($type_uc => $type_array[$type_uc],
					'Password' => array('type'=>'password',
										'lower'=>'Enter your current password')
					);
					
$body->create_input($final_array,'/member/account/change_authenticate.php?type=' . $type,'','change_info');


$body->close();
$alert = new Alert_w_txt('confirmation');
$alert->confirm($type);
