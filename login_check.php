<?php
session_start();
require_once('db_functions.php');

$check = new User();
$check->login($_POST['username'],$_POST['password']);
?>