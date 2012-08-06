<?php
/* location of file: application/mail/from_cv.php */
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

if(empty($_REQUEST['username']))
	header('location:' . $_SERVER['HTTP_REFERER']);

//check if username exists in database, redirect if doesn't exist
$user = new User();
$user_exists = $user->check_username($_REQUEST['username']);
if($user_exists === false)
	header('location:' . $_SERVER['HTTP_REFERER']);
else
	$_SESSION['user']['uID'] = $user_exists;
	
//generate random password
function genPassword($length=8)
{
	$pass = '';
    for($i=0; $i<$length; $i++) {
		
		$probab = mt_rand(1,10); 
    
        if($probab <= 8)   // a-z probability is 80%
            $pass .= chr(mt_rand(65,90));
        else            // 0-9 probability is 20%
            $pass .= chr(mt_rand(48, 57));
	}
    return $pass;
}
	
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
	
	$_SESSION['mail']['success'] = true;
	
	//change password in db
	$user->change(array('password'=>$password));
	header('location:' . $_SERVER['HTTP_REFERER']);
}
else{
	$_SESSION['mail']['success'] = 'fail';
	header('location:' . $_SERVER['HTTP_REFERER']);
}
