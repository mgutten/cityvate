<?php
session_start();
require_once('../../db_functions.php');

$password = trim($_POST['password']);
$type = $_GET['type'];

$user = new User();

if($user->check_password($password) === false){
	$_SESSION['user']['password_fail'] = true;
	header('location:change.php?type=' . $type);
}


	
	