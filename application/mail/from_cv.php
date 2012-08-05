<?php
/* location of file: application/mail/from_cv.php */
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

if(empty($_REQUEST['username']))
	header('location:' . $_SERVER['HTTP_REFERER']);

//check if username exists in database, redirect if doesn't exist
$user = new User();
if($user->check_username($_REQUEST['username']) === false)
	header('location:' . $_SERVER['HTTP_REFERER']);
	

	$to = $_REQUEST['username'];
	$subject = 'Cityvate Password Reset';
	$message = 'Your new password is:';
	//additional headers that will be imploded with necessary "\r\n" in mail function
	$headers = array();
	$headers[] = 'From: support@cityvate.com' ;
	$headers[] = 'Reply-to: support@cityvate.com';


if(mail($to, $subject, $message, implode("\r\n", $headers)) == true){
	$_SESSION['mail']['success'] = true;
	header('location:' . $_SERVER['HTTP_REFERER']);
}
else{
	$_SESSION['mail']['success'] = 'fail';
	header('location:' . $_SERVER['HTTP_REFERER']);
}
