<?php

$login = 'out';

require_once('../classes.php');

//position var to show what place in signup process we are at
$pos = 1;

$head = new Head_signup($login,'Signup City',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Select Your Location',$pos);

$form = new Form_signup($pos);
?>

<div class='box_title text'>Your City</div>
<select name="city" id='city' class='text drop' onChange="if(this.value=='Where\'s my city?'){window.location='unavailable'}">
	<option selected="selected">San Francisco</option>
    <option >Where's my city?</option>
</select>

<div class='box_title text'>Your Neighborhood</div>
<select name='neighborhood' id='neighborhood' class='text drop'>
	<?php
		$body->signup_options();
	?>
</select>	

<?php
$form->back();
$form->next_button();
$form->close();

	
