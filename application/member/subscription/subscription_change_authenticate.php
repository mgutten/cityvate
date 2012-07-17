<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

$password = trim($_POST['password']);
$type = $_GET['type'];

$user = new User();

if($user->check_password($password) === false){
	$_SESSION['user']['password_fail'] = true;
	header('location:change/' . $type);
}


	
	