<?php

require_once('../html_display.php');
require_once('../db_functions.php');

//page should require logged in user
$login = 'in';

//create the head section
$head = new Head($login,'Member Calendar');
$head->close();

//create top header
$header = new Header($login);

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_member(2);

?>