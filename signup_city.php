<?php

require_once('html_display.php');

$login = 'out';

$head = new Head_signup($login,'Signup City');
$head->close();

$header = new Header($login);


$body = new Body_signup();
$body->background('small','Select Your Location','1');

?>