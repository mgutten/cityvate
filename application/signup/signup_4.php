<?php

//position in signup process
$pos = 4;

$head = new Head_signup('Signup Interests');
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Interests',$pos);

$form = new Form_signup($pos);

$interests_list = array('Gamer','Outdoorsman','Romantic',
							'Party Animal','Athlete','Artist',
							'Food Critic','Introvert','Socialite',
							'Shopper','Giver','Sports Fan');

if(!empty($_SESSION['signup']['interests'])){
	$interests = json_decode($_SESSION['signup']['interests']);
}

?>

<p class='text' id='describe_yourself'>
	What are you and what would you like to become?
</p>
<p class='text' id='picknchoose'>
	(Pick and choose)
</p>

<?php
	//populate field with interests and pics
	signup_interests($interests_list);

	//hidden interests input
	echo $form->input(array('type'=>'hidden',
						'id'=>'interests_hidden',
						'name'=>'interests'
						));
	
	$form->back();
	$form->next_button();
	$form->close();

