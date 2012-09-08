<?php
//do not cache new_activities page
if(strpos($_SERVER['REQUEST_URI'],'member/new') !== false){
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
}

session_start();

//set global paths
$url = array('member'=>'/member',
						'new'=>'/member/new',
						'calendar'=>'/member/calendar',
						'past'=>'/member/past',
						'activity'=>'/member/activity',
						'contact'=>'/member/contact',
						'account'=>'/member/account',
						'subscription'=>'/member/subscription',
						'interests'=>'/member/interests',
						'purchase'=>'/member/purchase',
						'logout'=>'/member/logout.php',
					'preview'=>'/preview',
					'home'=>'/',
						'about'=>'/about',
						'signup'=>'/signup',
						'login'=>'/login',
						'mail_to'=>'/mail/to_cv.php',
						'mail_from_forgot'=>'/mail/forgot.php',
						'forgot'=>'/forgot'
					);
					
//prepend preview to each url when in preview directory
$preview = strpos($_SERVER['REQUEST_URI'],'/preview');
if($preview !== false){
	
	foreach($url as $key=>$val){
		//skip urls that are outside of member folder
		if(strpos($val, 'member') === false)
			continue;
		
		$url[$key] = '/preview' . $val;
	}
	
	//create preview array
	if(empty($_SESSION['preview'])){
		$_SESSION['preview'] = array('uID'=>1,
									'username'=>'preview@cityvate.com',
									'tokens_balance'=>10,
									'package'=>'basic',
									'city'=>'SF',
									'neighborhood'=>'Marina',
									'fname'=>'John',
									'lname'=>'Everyman');
	}	
}
//unset preview session to prohibit transfer to actual member home
else
	unset($_SESSION['preview']);

//if we are in the member directory, you must be logged in
if(strpos($_SERVER['REQUEST_URI'],'member') != false)
	$login = 'in';
else
	$login = 'out';

//include general class file
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes.php');

//if page is in the member/ajax_calls section, require db_functions
if(strpos($_SERVER['REQUEST_URI'],'ajax_calls') !== false ||
	$login == 'in')
	require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

//if in preview mode, require preview bootstrap
if($preview !== false)
	require_once($_SERVER['DOCUMENT_ROOT'] . '/application/preview/preview_bootstrap.php');

	

//define constants for how many tokens each package is worth (exchange rate: $2.50/1 token)
define('EXCHANGE',2.5);
define('BUDGET_TOKENS',10);
define('BASIC_TOKENS',20);
define('PREMIUM_TOKENS',40);
define('CHARITIES', 'The Red Cross, Salvation Army, and Toys for Tots');

