<?php

//position is off of signup process, but requires same template,
//so set position to 0
$pos=0;

$head = new Head_signup('City Unavailable',1);
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Sorry!',$pos);

?>

<p class='text unavailable'>Cityvate is currently only available in San Francisco.</p>
<p class='text unavailable' id='unavailable'>Check back at a later date for your city.</p>
<a href='step1' alt='Back to step 1'><p class='back text'>Back</p></a>