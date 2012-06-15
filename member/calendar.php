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
            <div id='right_arrow' class='arrow' style='display:none'></div>
          	<p id='<?php echo date('m').date('F');?>' class='text activity_month'><?php echo date('F');?></p>
    </div>
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
	
		calendar_my_activites()
		?>
    </div>
</div>