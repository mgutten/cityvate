<?php
/* location of file: activity/member/activity/index.php */

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

$activity = $_GET['activity'];

$activity_call = new Activities();
$activity_desc =  $activity_call->activity_desc($activity);

$head = new Head($activity_desc['name'],1);
$head->script('member/activity');
$head->style('member/activity');
$head->close();

$header = new Header();

$body = new Body();
?>

<div id='activity_title_container'>
    <p id='activity_title' class='text'><?php echo $activity_desc['name'];?></p>
	<p id='activity_type' class='text'><?php echo $activity_desc['type'];?></p>
</div>

<p id='savings' class='text'>You saved <?php echo $activity_desc['save'];?>%</p>

<div id='body_left'>
	<div class='subtitle_container'>
        <p class='text subtitle'>The short:</p>
        <p class='text subtitle_value'>Here is where the text will go that we retrieve from the database.  You retrieve the description of the activity and then display it here.  It should be inticing and nice to look at.  How does Pelican Bay sound to you?</p>
	</div>

</div>

<div id='body_right'>

</div>