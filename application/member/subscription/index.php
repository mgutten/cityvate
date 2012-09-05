<?php
/* location member/subscription/index.php */


require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/classes.php');


//create the head section
$head = new Head('Member Subscription');
$head->close();

//create top header
$header = new Header();

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_account(2);

//if not set, retrieve package info from db
if(empty($_SESSION['user']['package'])
	|| empty($_SESSION['user']['end_date']))
		$body->get_user_info();

?>

<div class='title_back'><p class='text title'>Package Info</p></div>

<div id='package_main'>
	<p class='package_subtitle text'>Current Package:</p>
    <p class='package_val text'><?php echo ucwords($_SESSION['user']['package']);?></p>
    <p class='package_subtitle text'>Cost:</p>
    <p class='package_val text'>$<?php echo $body->package_cost[$_SESSION['user']['package']];?>/mo</p>
    <p class='package_subtitle text'>Subscription ends:</p>
    <p class='package_val text'><?php echo $_SESSION['user']['end_date'];?></p>
</div>



<div id='subscription_expired'>
	<p class='text red_back'>
    	Your subscription has expired.
    </p>
    <a href='
</div>

<a href='<?php echo $url['subscription'];?>/change/package'><img src="/images/subscription/upgrade_button.png" class='button first'/></a>
<a href='<?php echo $url['subscription'];?>/change/end_date'><img src="/images/subscription/end_button.png" class='button'/></a>
<a href='<?php echo $url['subscription'];?>/change/payment'><img src="/images/subscription/pay_button.png" class='button'/></a>
<a href='<?php echo $url['subscription'];?>/change/cancel'><img src="/images/subscription/cancel_button.png" class='button'/></a>