<?php

if(!empty($_SESSION['user']))
	unset($_SESSION['user']);
if(!empty($_COOKIE['user']))
	unset($_COOKIE['user']);
	
header('location:' . $url['home']);
