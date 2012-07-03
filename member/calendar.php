<?php

require_once('../html_display.php');
require_once('../db_functions.php');

//page should require logged in user
$login = 'in';

//create the head section
$head = new Head($login,'Member Calendar');
//drag and drop script
$head->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min');
$head->close();

//create top header
$header = new Header($login);

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_member(2);

$selected_month = date('n');

$calendar = new Calendar();
$calendar->calendar_calls($selected_month);

$days=array('Su','M','T','W','Th','F','S');

?>

<div id='body_left'>
	<div id='month'>
    		<div id='left_arrow' class='arrow'></div>
            <div id='right_arrow' class='arrow'></div>
          	<p id='<?php echo date('m').date('F');?>' class='text activity_month'><?php echo date('F');?></p>
    </div>
    <p class='text activity_year'><?php echo date('Y');?></p>
    <div id='days_of_week'>
    	<?php
			for($i=0;$i<7;$i++){
				echo "<p class='day_of_week text'>".$days[$i]."</p>";
			}
		?>
    </div>
	<div id='calendar'>
	<?php
		$calendar->create_calendar();
		
		?>
	</div>		
        <p id='red_explanation' class='text'>
        </p>
</div>
<div id='body_right'>
	<p class='text my_activities_title'>
    	My Activities
    </p>
    <p class='text my_activities_title_clarify'>
    	drag and drop to add/change
    </p>
    <div id='my_activities_container'>
    	<?php
		
		//function to create draggable activities bars
		//located in html_display.php
		calendar_my_activities($calendar->activities);
		
		?>
    </div>
    <div id='my_activities_done_container' class='text'> 
    </div>
    <div id='activity_desc_container' class='text'>
    	<p id='activity_title'>
        Click an Activity/Day
         </p>
         <p id='activity_desc_left'>
         	Reservation Needed:</br>
            Reserve in Advance:</br>
            Current Reservation:</br>
            Type of Activity:</br>
            Did It:</br>
            Expires:</br>
            Full Info:</br>
         </p>
         <div id='activity_desc_right'>
         	<p id='activity_reserve_needed' class='activity_desc_ajax'>
            	
            </p>
            <p id='activity_reserve_advance' class='activity_desc_ajax'>
            	
            </p>
            <p id='activity_reserve_current' class='activity_desc_ajax'>
            	
            </p>
            <p id='activity_type' class='activity_desc_ajax'>
            	
            </p>
            <p id='activity_done' class='activity_desc_ajax'>
            	
            </p>
            <p id='activity_expire' class='activity_desc_ajax'>
            	
            </p>
            <p id='activity_full_info' class='activity_desc_ajax'>
            	<a href=''></a>
            </p>
         </div>        
         	<img src="../images/calendar/cancel_reserve_button.png" id='cancel_reserve'/>
         	
    </div>
</div>

<?php
	//create alert button for when a reservation is
	//required and draggable activity is dropped on
	//droppable calendar day
	$alert = new Alert_w_txt('reserve_required');
	$alert->calendar_alert('Reservation Required','images/calendar/reserve_button.png');
	$alert->close();
?>
	