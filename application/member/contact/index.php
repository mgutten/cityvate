<?php
/* location of file: application/member/contact/index.php */

$body = new Information('Member Contact', 'Contact Us');

$form = new Form('/mail','POST','return validate("contact")','contact');
?>

<p class='text information_section_title center'>
	What is your question regarding?
</p>

<select class='text drop center' name='subject'>
	<option>My Account</option>
    <option>My Activities</option>
    <option>Subscription</option>
    <option>Expired/Done Activities</option>
    <option>How to Redeem</option>
    <option>Other</option>
</select>

<p class='text information_section_title center'>
	Please type your message below
</p>

<textarea class='text drop center' name='message' id='message' maxlength='800'>
<?php
	//if message was input before and failed, fill in message box
	if(!empty($_SESSION['mail']['message'])){
		echo $_SESSION['mail']['message'];
		
		unset($_SESSION['mail']['message']);
	}
?>
</textarea>
	
<?php

$form->input('image','submitter','submitter','/images/information/submit_button.png');


//if message has already been sent and redirected back here, set alert box
if(!empty($_SESSION['mail']['success'])){
	if($_SESSION['mail']['success'] === true){
			$alert = new Alert_w_txt('success');
			$alert->generic('Thank you','Your message has been sent.  A response will be sent to <span class="yellow">' . $_SESSION['user']['username'] . '</span> shortly.');
	}
	else{
			$alert = new Alert_w_txt('success');
			$alert->generic('Message failure','Your message was unable to send.  Please try again.');
	}
	
	unset($_SESSION['mail']['success']);
}

