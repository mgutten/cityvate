<?php

if(empty($_SESSION['signup']['package']))
	header('location:/signup/step1');
	
//position in signup process
$pos = 5;

$head = new Head_signup('Signup Review',1);
$head->close();

$header = new Header();

$body = new Body_signup();
$body->background('Review',$pos);

//save signup session to shorter var
$signup = $_SESSION['signup'];
//set vars
$neighborhood = $signup['neighborhood'];
$city = $signup['city'];
$username = $signup['usernameemail'];
$package = $signup['package'];
$start = date('F 1, Y',strtotime('+1 months'));
$auto = $signup['auto_renew'];
//if autorenew is set to true,do not display end date
$end = ($auto > 0 ? 'TBD' : date('F t, Y',strtotime("+" . $signup['end'] . " months")));

//explode fullname into first and last
$fullname = explode(' ',$signup['fullname']);
$first = ucwords($fullname[0]);
$last = ucwords($fullname[1]);

//add day to start date
$start = str_replace(',',' 1,',$start);
//find total
$total = $body->package_cost[$package];
	

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
					"Start Date"=>date('F 1, Y',strtotime("+1 month")),
					"End Date"=>$end,
					"Auto-renew"=>($auto == '1' ? 'Yes' : 'No'),
					"Total"=>'$'.$total.'.00'
					)
					
				);
signup_review($textarray);

?>

<form name="_s-xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" class='pay_button' id='paypal'>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="LPEJQQ563NWHN">
<input type="hidden" name="first_name" value="<?php echo $first;?>">
<input type="hidden" name="last_name" value="<?php echo $last;?>">
<input type="hidden" name="city" value="<?php echo $_SESSION['signup']['city'];?>">
<input type="hidden" name="state" value="CA">
<input type="hidden" name="email" value="<?php echo $_SESSION['signup']['usernameemail'];?>">
<input type='hidden' name='amount' value='30' />
<input type='hidden' name='currency_code' value='USD' />
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
<!--<a href="paypal_checkout" alt="Checkout with PayPal"><img src="" class='pay_button' id='paypal' /></a>-->



	