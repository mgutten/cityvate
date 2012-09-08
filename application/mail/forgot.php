<?php
/* location of file: application/mail/forgot.php */
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

if(empty($_REQUEST['username']))
	header('location:' . $_SERVER['HTTP_REFERER']);

//check if username exists in database, redirect if doesn't exist
$user = new User();
$user_exists = $user->check_username($_REQUEST['username']);
if($user_exists === false)
	header('location:' . $_SERVER['HTTP_REFERER']);
		

	$password = genPassword(7);

	$to = $_REQUEST['username'];
	$subject = 'Cityvate.com Password Reset';
	$message = '<html><body>A request has been made to reset the password for your Cityvate account.  Your new password is:</br></br>
					<b>' . $password . '</b>
					</br></br>
					You can change your password when you log in by going to My Account.</br>
					If you did not issue this request, please contact Cityvate support (support@cityvate.com).</body></html>';
	//additional headers that will be imploded with necessary "\r\n" in mail function
	$headers = array();
	$headers[] = 'From: support@cityvate.com' ;
	$headers[] = 'Reply-to: support@cityvate.com';
	

if(mail($to, $subject, $message, implode("\r\n", $headers)) == true){
	
	$_SESSION['mail']['success'] = $_REQUEST['username'];
	
	//change password in db
	$user->change(array('password'=>$password), $user_exists);
	header('location:' . $_SERVER['HTTP_REFERER']);
}
else{
	$_SESSION['mail']['fail'] = true;
	header('location:' . $_SERVER['HTTP_REFERER']);
}
