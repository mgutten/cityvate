<?php

require_once('../html_display.php');
require_once('../db_functions.php');

$login = 'in';

$head = new Head($login,'Member Home');
$head->close();

$header = new Header($login);

$body = new Body_member(1);

?>

<div id='body_top'>

<?php
	activity_home_top();
	?>		
      
</div>