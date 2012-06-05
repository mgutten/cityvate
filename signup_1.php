<?php

require_once('html_display.php');

$login = 'out';
$pos = 1;

$head = new Head_signup($login,'Signup City',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Select Your Location',$pos);

$form = new Form_signup($pos);

$neighborhoods = array('SOMA','Castro','Chinatown','Fisherman\'s Wharf','Haight',
					'Japantown','Marina','Mission','North Beach','Pacific Heights',
					'Presidio','Panhandle','Tenderloin','Union Square');
?>

<div class='box_title text'>Your City</div>
<select name="city" id='city' class='text drop' onChange="if(this.value=='Where\'s my city?'){window.location='signup_unavailable.php'}">
	<option selected="selected">San Francisco</option>
    <option >Where's my city?</option>
</select>

<div class='box_title text'>Your Neighborhood</div>
<select name='neighborhood' id='neighborhood' class='text drop'>
	<?php
		signup_options($neighborhoods);
	?>
</select>	

<?php
$form->back();
$form->next_button();
$form->close();

?>	
