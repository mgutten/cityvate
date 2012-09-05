<?php

//position in signup process
$pos = 4;

$head = new Head_signup('Signup Preferences');
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Preferences',$pos);

$form = new Form_signup($pos);

?>

<p class='text' id='describe_yourself'>
	How would you describe yourself?
</p>
<p class='text' id='picknchoose'>
	(Pick and choose)
</p>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Gamer
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/gamer.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Outdoorsman
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/outdoorsman.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Romantic
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/romantic.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Party Animal
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/party_animal.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Athlete
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/jock.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Artist
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/artist.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Food Critic
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/food_critic.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Introvert
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/introvert.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Socialite
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/socialite.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Shopper
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/shopper.png' class='preference_img'/>
</div>

<div class='preference' id='last_preference'>
	<p class='text preference_title preference_toggle'>
    	Giver
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/giver.png' class='preference_img'/>
</div>

<div class='preference'>
	<p class='text preference_title preference_toggle'>
    	Sports Fan
    </p>
    <div class='preference_back preference_toggle'>
    </div>
    <img src='/images/signup/preferences/sports_fanatic.png' class='preference_img'/>
</div>

<?php
$form->back();
$form->next_button();
$form->close();

