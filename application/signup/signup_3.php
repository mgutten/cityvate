<?php

//position in signup process
$pos = 3;

$head = new Head_signup('Signup Package',1);
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Package',$pos);

$form = new Form_signup($pos);
$form->radio('package','budget');
?>
<p class='package_title text'>Budget</p><p class='text package_cost'>($25/mo)</p>

<?php
$form->radio('package','basic');
?>
<p class='package_title text'>Basic</p><p class='text package_cost'>($50/mo)</p>
<?php
$form->radio('package','premium');
?>
<p class='package_title text'>Premium</p><p class='text package_cost'>($100/mo)</p>

<p class='when_title text'>When would you like your subscription to start?</p>
<select name='start' class='text drop' id='when'>
	<?php
		signup_date_options(5);
	?>
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
