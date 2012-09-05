<?php

//position in signup process
$pos = 3;

$head = new Head_signup('Signup Package',1);
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Package',$pos);

$form = new Form_signup($pos);

$package_array = array('budget','basic','premium');

$form->radio_package_display($package_array);
?>

<!--
<p class='when_title text'>When would you like your subscription to start?</p>
<select name='start' class='text drop when' id='when'>
	<?php
		signup_date_options(5);
	?>
</select>
-->

<p class='when_title text' id='end_date'>How long would you like your subscription to last?</p>

<select name='end' class='text drop when' id='when_end'>
<option value='1'>1 month</option>
<option value='2' selected='selected'>2 months</option>
<option value='3'>3 months</option>
<option value='4'>4 months</option>
</select>

<?php
echo $form->input(array('type'=>'checkbox',
					'id'=>'auto_renew',
					'class'=>'auto',
					'value'=>'Yes'));
?>

<p class='text renew'>Automatically Renew</p>



<?php


$form->back();
$form->next_button();
$form->close();
