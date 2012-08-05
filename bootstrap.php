<?php
session_start();
//if we are in the member directory, you must be logged in
if(strpos($_SERVER['REQUEST_URI'],'member') != false)
	$login = 'in';

else
	$login = 'out';

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes.php');

//if page is in the member/ajax_calls section, require db_functions
if(strpos($_SERVER['REQUEST_URI'],'ajax_calls') != false)
	require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

;
	
