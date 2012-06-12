<?php

require_once('html_display.php');

$login = 'out';
$pos=0;

$head = new Head_signup($login,'Login',1);
$head->script('login');
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Login',$pos);

$form = new Form("login_check.php","POST","return validate()");
?>
<p class="box_title text box_title_close login">Username/Email</p>
<?php
$form->input('text','username','drop text');
?>
<p class="box_title text">Password</p>
<?php
$form->input('password','password','drop text');
?>
<p class='text inspire'>"It is never too late to be what you might have been."</p>
<p class='text inspire' id='author'>-George Eliot</p>
<a href='forgot.php' class='forgot text'>Forgot your password?</a>
<?php
$form->input('image','submit','submit','images/login_button_green.png');
$form->close();
?>