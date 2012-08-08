<?php
/* location of file: application/mail/to_cv.php */

if(empty($_REQUEST['message']))
	header('location:' . $_SERVER['HTTP_REFERER']);


	$to = 'support@cityvate.com';
	$subject = 'Contact Form: ' . (!empty($_REQUEST['subject']) ? $_REQUEST['subject'] : '');
	$message = $_REQUEST['message'];
	//additional headers that will be imploded with necessary "\r\n" in mail function
	$headers = array();
	$headers[] = 'From: ' . $_SESSION['user']['username'];
	$headers[] = 'Reply-to: ' . $_SESSION['user']['username'];


if(mail($to, $subject, $message, implode("\r\n", $headers)) == true){
	$_SESSION['mail']['success'] = true;
	header('location:' . $_SERVER['HTTP_REFERER']);
}
else{
	$_SESSION['mail']['success'] = 'fail';
	$_SESSION['mail']['message'] = $_REQUEST['input_message'];
	header('location:' . $_SERVER['HTTP_REFERER']);
}
