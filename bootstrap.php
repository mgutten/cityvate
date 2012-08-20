<?php
//do not cache new_activities page
if(strpos($_SERVER['REQUEST_URI'],'member/new') !== false){
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
}

session_start();

//if we are in the member directory, you must be logged in
if(strpos($_SERVER['REQUEST_URI'],'member') != false)
	$login = 'in';

else
	$login = 'out';

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes.php');

//if page is in the member/ajax_calls section, require db_functions
if(strpos($_SERVER['REQUEST_URI'],'ajax_calls') !== false)
	require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

//define constants for how many tokens each package is worth (exchange rate: $2.50/1 token)
define('EXCHANGE',2.5);
define('BUDGET_TOKENS',10);
define('BASIC_TOKENS',20);
define('PREMIUM_TOKENS',40);
define('CHARITIES', 'The Red Cross, Salvation Army, and Toys for Tots');

