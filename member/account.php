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


