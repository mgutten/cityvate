<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

unset($_SESSION['user']);

//loop through post array to fix discrepancy between
//username & pw boxes on login.php
foreach($_POST as $key=>$value){
	if(strpos($key,'username') !== false)
		$user = $value;
	elseif(strpos($key,'pass') !== false)
		$pw = $value;
}


$check = new User();
echo $check->login($user,$pw);
