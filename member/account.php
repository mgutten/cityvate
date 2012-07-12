<?php

$login = 'in';

require_once('../db_functions.php');
require_once('../classes.php');

//create the head section
$head = new Head($login,'Member Account');
$head->close();

//create top header
$header = new Header($login);

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_account(1);

$body->my_account_boxes();

if(!empty($_SESSION['user']['change_success'])){
	//alert for neighborhood/name b/c can't take effect for another month
	if($_SESSION['user']['change_success'] == 'neighborhood' 
		|| $_SESSION['user']['change_success'] == 'name')
		
			$alert = new Alert('note','note_alert');

	//immediate effect of pw and username
	else {
		$alert = new Alert_w_txt('success');
		$alert->generic('Success!','Your ' . $_SESSION['user']['change_success'] . ' has been changed.');
	}
	
	unset($_SESSION['user']['change_success']);
}

