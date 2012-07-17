<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');


//create the head section
$head = new Head('Member Past');
$head->close();

//create top header
$header = new Header();

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_member_past(3);


?>

<div id='never_container'>
</div>

<div id='did_container'>
</div>
        
<?php $body->display_activities();
