<?php
session_start();

if($_POST['budget'])
	$_SESSION['package']='budget';
if($_POST['basic'])
	$_SESSION['package']='basic';
if($_POST['premium'])
	$_SESSION['package']='premium';
	
header('location:signup_city.php');

?>