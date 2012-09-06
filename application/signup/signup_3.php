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

<?php
$checkbox_array = array('type'=>'checkbox',
					'id'=>'auto_renew',
					'class'=>'auto',
					'value'=>'1');

//determine previous state of checkbox and appropriate display of lower boxes
if(!empty($_SESSION['signup']['auto_renew']) && $_SESSION['signup']['auto_renew'] == '1'){
	$display_upper = 'style="display:none"';
	$display_lower = 'style="display:block"';
	$checkbox_array['checked'] = 'true';
}
else{
	$display_upper = '';
	$display_lower = '';	
}

echo $form->input($checkbox_array);
					

?>

<p class='text renew'>Automatically Renew</p>

<p class='when_title text when_toggle' id='end_date' <?php echo $display_upper;?>>How long would you like your subscription to last?</p>

<select name='end' class='text drop when when_toggle' id='when_end' <?php echo $display_upper;?>>
    <option value='1'>1 month</option>
    <option value='2' selected='selected'>2 months</option>
    <option value='3'>3 months</option>
    <option value='4'>4 months</option>
</select>

<p class='text drop when_toggle auto_renew_desc' <?php echo $display_lower;?>>
	Your subscription will be automatically renewed at the beginning of each month.
</p>

<?php


$form->back();
$form->next_button();
$form->close();
