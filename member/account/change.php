<?php

/* file location member/account/change.php */

$login = 'in';

require_once('../../classes.php');

$pos=0;

$type = $_GET['type'];
$type_uc = ucwords($type);

//if someone types in url to change city, redirect back to account
if($type == 'city')
	header('location:' . $GLOBALS['file_adj'] . 'member/account.php');
//if type is subscription, redirect to subscription page
elseif($type == 'subscription')
	header('location:' . $GLOBALS['file_adj'] . 'member/subscription.php');

$head = new Head_signup($login,'Change Info',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Change ' . $type_uc,$pos);

//$form = new Form('change_authenticate.php','POST','','change_info');

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
					
$body->create_input($final_array,'change_authenticate.php?type=' . $type,'','change_info');

/*
?>

<p class='box_title_lower fail red text'><?php
	//if password failed, show text to indicate failure
	if(!empty($_SESSION['user']['password_fail']))
		echo "Failed";
		?></p>

<div class='box_title_close change_subtitle text'>New <?php echo $type_uc;?></div>

<?php
//if changing neighborhood, create dropdown
if($type == 'neighborhood'){
	echo "<select name='change' id='input_change' class='drop text'>";
	
	signup_options();
	
	echo "</select>";
}
//else if password then create password input box
elseif($type == 'password')
	$form->input('password','change','drop text');
//else regular input box
else{
	$class = 'drop text';
	
	//if username was not in email format, give box red back
	if(!empty($_SESSION['user']['email_fail'])){
		$class .= ' red_back';
		$username_lower .= 'Please enter a valid email';
		unset($_SESSION['user']['email_fail']);
	}
	
	if(!empty($_SESSION['user']['name_fail'])){
		$class .= 'red_back';
		$username_lower .= 'Names must be in the format "John Smith"';
		unset($_SESSION['user']['name_fail']);
	}
	$form->input('text','change',$class);
}

?>
<p class='box_title_lower text red'><?php echo $username_lower;?></p>

<div class='box_title text'>Current Password</div>

<?php
//if password was incorrect, change box to red
	$class = 'drop text';
if(!empty($_SESSION['user']['password_fail'])){
	$class .= ' red_back';
	unset($_SESSION['user']['password_fail']);
}
	
$form->input('password','password',$class);
?>

<a href='../forgot.php' class='text box_title_lower' id='change_forgot'>Forgot Password?</a>

<?php
$form->input('hidden','type','','',$type);

$form->input('image','submitter','next_button','../images/change/update_button.png');

$form->close();
*/

$body->close();
$alert = new Alert_w_txt('confirm');
$alert->confirm($type);
