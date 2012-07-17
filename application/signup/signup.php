<?php
/* location of file: signup/signup.php 

	handles all pretty url requests (ie signup/step1)=>signup/signup_1.php
	
	*/
if(!empty($_GET['page'])){
	require_once('signup_' . $_GET['page'] . '.php');
	exit;
}


	