<?php

require_once('html_display.php');

$login = 'out';
$pos=0;

$head = new Head_signup($login,'City Unavailable',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Sorry!',$pos);

?>

<p class='text unavailable'>Cityvate is currently only available in San Francisco.</p>
<p class='text unavailable' id='unavailable'>Check back at a later date for your city.</p>
<a href='signup_1.php' alt='Back to step 1'><p class='back text'>Back</p></a>