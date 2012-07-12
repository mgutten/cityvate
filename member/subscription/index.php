<?php
/* location member/subscription/index.php */

//if button has been clicked, load change page into this one and exit
if(!empty($_GET['type'])){
	if($_GET['type'] == 'cancel'){
		require_once('../cancel_subscription.php');
		exit;
	}
	require_once('change.php');
	exit;
}

$login = 'in';

require_once('../../db_functions.php');
require_once('../../classes.php');


//create the head section
$head = new Head($login,'Member Subscription');
$head->close();

//create top header
$header = new Header($login);

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_account(2);

//if not set, retrieve package info from db
if(empty($_SESSION['user']['package']))
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

<a href='change.php?type=package'><img src="../../images/subscription/upgrade_button.png" class='button first'/></a>
<a href='change.php?type=end_date'><img src="../../images/subscription/end_button.png" class='button'/></a>
<a href='change.php?type=payment'><img src="../../images/subscription/pay_button.png" class='button'/></a>
<a href='change.php?type=cancel'><img src="../../images/subscription/cancel_button.png" class='button'/></a>