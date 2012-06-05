<?php

require_once('html_display.php');

$login = 'out';
$pos = 4;

$head = new Head_signup($login,'Signup Review',1);
$head->close();

$header = new Header($login);

$body = new Body_signup();
$body->background('Review',$pos);

//save signup session to shorter var
$signup = $_SESSION['signup'];
//set vars
$neighborhood = $signup['neighborhood'];
$city = $signup['city'];
$username = $signup['usernameemail'];
$package = ucfirst($signup['package']);
$start = $signup['start'];
$auto = $signup['auto_renew'];

//explode fullname into first and last
$fullname = explode(' ',$signup['fullname']);
$first = $fullname[0];
$last = $fullname[1];

//add day to start date
$start = str_replace(',',' 1,',$start);
//find total
if($package=='Premium')
	$total = 100;
elseif($package=='Basic')
	$total = 50;
elseif($package=='Budget')
	$total = 25;
	
	

$textarray=array(
			"Location"=>array(
					"Neighborhood"=>$neighborhood,
					"City"=>$city
					),
			"Account Info"=>array(
					"Username"=>$username
					),
			"Personal Info"=>array(
					"First Name"=>$first,
					"Last Name"=>$last
					),
			"Package"=>array(
					"Package"=>$package,
					"Start Date"=>$start,
					"Auto-renew"=>$auto,
					"Total"=>'$'.$total
					)
					
					);
signup_review($textarray);


?>
<a href="paypal_checkout" alt="Checkout with PayPal"><img src="" class='pay_button' id='paypal' /></a>
	<p class='text' id='or'>or</p>
<a href="google_checkout" alt="Checkout with GoogleCheckout"><img src="" class='pay_button' id='google_co' /></a>

	