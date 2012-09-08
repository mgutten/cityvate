<?php
require_once( $_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

$head = new Head_signup('Admin',1);
$header = new Header();

$body = new Body_signup();
$body->background('Admins Only',1);

?>

<p class='text drop' style='margin-top:100px'>
	Nice Try! </br> If only you knew the magic word...
</p>