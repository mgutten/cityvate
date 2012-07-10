<?php

$login = 'in';

require_once('../classes.php');

$pos=0;

$type = $_GET['type'];
$type_uc = ucwords($type);


$head = new Head_signup($login,'Change Info',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Change ' . $type_uc,$pos);

$form = new Form('change_authenticate.php','POST','return validate()');

?>

<div class='box_title_close change_subtitle text'>New <?php echo $type_uc;?></div>

<?php

$form->input('text','change','drop text');


$form->close();
$body->close();
