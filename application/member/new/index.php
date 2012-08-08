<?php
/*location of file: member/new/index.php*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

$head = new Head('New Activities',1);
$head->style('member/new');
$head->script('member/new');
$head->close();

$header = new Header();

$body = new Body();

$activities = new New_activities();
$activities->get_activities();
?>

<div id='top_right'>
	<p class='text right_centered yellow top_right_large' id='top_right_new'>
    	NEW ACTIVITIES
    </p>
    <p class='text right_centered yellow top_right_small' id='top_right_month'>
    	<?php echo strtoupper(date('F',strtotime("+1 month")));?>
    </p>
    
   	<p class='text right_centered green' id='top_right_balance'>
    	Amount left to spend: <span id='token_balance'>
			<?php 
				//add amount of package tokens(constant defined in bootstrap) to remaining balance
				echo $_SESSION['user']['tokens_balance'] + constant(strtoupper($_SESSION['user']['package']) . '_TOKENS');
			?></span> tokens
    </p>
    <p class='text right_centered green top_right_small' id='top_right_number'>
    	Number of activities: <span id='number_activities'>0</span>
    </p>
</div>

<div id='body_left_back'>
</div>

<div id='body_left'>
	<p class='text yellow top_right_small' id='body_left_directions'>
    	Pick and choose from your options below:
    </p>
	<div id='body_left_title_bar'>
    	<p class='text body_left_title' id='body_left_title_activity'>
        	Activity
        </p>
        <p class='text body_left_title' id='body_left_title_cost'>
        	Cost
        </p>
        <p class='text body_left_title' id='body_left_title_save'>
        	You save
        </p>
        <p class='text body_left_title' id='body_left_title_details'>
        	Details
        </p>
    </div>
    
    <?php
		$form = new Form('/member/new/process.php','POST','','activities');
		echo $activities->create_bars();
	?>
    <img src='/images/new/cancel_button.png' id='cancel' class='button'/>
    
    <?php
		$form->input('image','submitter','button','/images/new/accept_button.png','return validate()');
		$form->input('hidden','leftover');
		$form->close();
	?>
</div>


<div id='bottom_right'>

</div>


<?php
	/*alert box when too many tokens are spent*/
	$too_many = new Alert_w_txt('too_many');
?>
	<p class='alert_title'>You've spent too many tokens!</p>
    <p class='alert_main'>Would you like to purchase more tokens?</p>
    <a href='/payment'><img src="/images/change/yes_button.png" id='yes' class='confirm_button'/></a>
    <img src="/images/change/no_button.png" id='no' class='confirm_button'/>
<?php
	$too_many->close();
?>

<?php
	/*alert box when everything checks out and tokens are leftover*/
	$leftover = new Alert_w_txt('leftover');
?>
	<p class='alert_title'>You have tokens left over</p>
    <p class='alert_main'>How would you like to use your <span id='leftover_tokens'></span> tokens?</p>
    
    <img src='/images/new/refund_button.png' class='leftover_button button' id="refund" title=''/>
    <img src='/images/new/carryover_button.png' class='leftover_button button' id="carryover" title='Tokens will be carried over to next month'/>
    <img src='/images/new/donate_button.png' class='leftover_button button' id="donate" title='100% of cash refund will be donated to charity'/>
<?php
	$leftover->close();
?>
