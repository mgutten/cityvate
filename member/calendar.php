<?php

require_once('../html_display.php');
require_once('../db_functions.php');

//page should require logged in user
$login = 'in';

//create the head section
$head = new Head($login,'Member Calendar');
$head->close();

//create top header
$header = new Header($login);

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_member(2);

$selected_month = date('n');

$activities_call = new Activities;
$activities = $activities_call->activities($selected_month);

$calendar = new Calendar();
$calendar->important_dates($selected_month);
$calendar->reserved_days($activities);

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
	//create calendar
		$c = 1;
		$last_month_day = $calendar->first_day;
		//create 5 weeks of month
		for($i=0; $c<=$calendar->last_day; $i++) {
			//create 7 days of week
			for($b=0; $b<7; $b++) {
				
				$blank = false;
				$class = 'calendar_day';
				
				//if it's the first week and before first
				//day or after last day of month, create blanks
				if($i==0 && $b<$calendar->first_day || $c>$calendar->last_day){
						$day = $calendar->last_month_days-$last_month_day;
						$blank = true;
						$class .= ' transparent';
						$last_month_day--;
				}
				else
					$day = $c;
				
				//test if even or odd day
				if(($b % 2) == 0){
						$class .= ' calendar_light';
				}
				else {
						$class .= ' calendar_dark';
				}
				
				//if first day of week, start it on a new line		
				if($b==0)
						$class .= ' week_first';
				//if today is not set (ie not this month),
				//then make all days transparent
				if(empty($calendar->today))
						$class .= ' transparent';
				
				if($c == $calendar->today)
						$class .= ' today';
						
				$block = "<div class='".$class."'>
						<p class='text calendar_day_text'>".$day."</p>";
				//if running day has a reserved activity, show name
				if(!empty($calendar->reserved_days[$c]))
						$block .= "<p class='text activity'>".$calendar->reserved_days[$c]."</p>";
								
				$block .= "</div>";
				
				echo $block;
				
				//if we are dealing with a blank day, do not 
				//increment our running day var $c
				if($blank === true)
					continue;
					
				$c++;
			}
		}
		
		?>
		</div>						
		
</div>
<div id='body_right'>
</div>