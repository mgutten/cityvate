<?php

require_once('../html_display.php');
require_once('../db_functions.php');

//page should require logged in user
$login = 'in';

//create the head section
$head = new Head($login,'Member Home');
$head->close();

//create top header
$header = new Header($login);

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_member(1);

//make call to retrieve activities list
$activities_call = new Activities();
$activities = $activities_call->activities();
//store upcoming events for coming week
$upcoming_event = $activities_call->upcoming();

//declare array for activites that have
//been done already
$done=array();

?>

<div id='body_top'>

	<a href='activity.php?num=<?php echo $activities[0]['aID'];?>' id='picture_link'>
          <div id='picture'>
                  <div id='picture_shown_outer'>
                      <img src='../images/activities/<?php echo date('m').'/'.str_replace(' ','_',$activities[0]['name']);?>.jpg' class='activity_picture shown' id='picture_shown'/>
                   </div>
                   <div id='picture_hidden_outer'>
                      <img src='' id='picture_hidden'/>
                   </div>
      
                      
                  <div id='picture_top'>
                      <div class='picture_banner' id='picture_top_banner'>
                      </div>
                      <p class='text banner' id='picture_banner_text'>
                              <?php echo $activities[0]['name'];?>
                      </p>
                      <div class='picture_banner' id='picture_bottom_banner'>
                      </div>
                      <p class='text banner' id='click_banner'>
                              Click for details
                      </p>
                   </div>
          </div>
	</a>
						
         <div id='top_right_month'>
                  <div id='left_arrow' class='arrow'></div>
                  <div id='right_arrow' class='arrow' style='display:none'></div>
          		  <p id='<?php echo date('m').date('F');?>' class='text activity_month'><?php echo date('F');?>'s Activities</p>
      
          </div>
          <div id='top_right_activities' class='top_right_activities'>
              
              <?php
              //populate with list of activities from array
                      $body->member_activity($activities);
              ?>
              
      		</div>
            <div id='activity_done' class='top_right_activities'>
				<p class='text' id='activity_done_title'>Finished Activities</p>
					<div id='activity_done_lower'>
               
					<?php
						$body->member_finished();
					?>			
						
					</div>
            </div>
						
      
</div>

<div id='body_bottom_left'>
	<p class='text upcoming_title'>
    	Coming Up
    </p>
    <?php
		
		$body->member_upcoming($upcoming_event);
		
		?>
    
</div>

<div id="body_bottom_right">
	<p class='text upcoming_title'>
    	Activity of the Day
    </p>
    <p class="text d_activity" id='d_activity_title'>
    	Doug Dimmadome's Grand Opening Dimmadale
    </p>
    <p class='text d_activity' id='d_activity_description'>
    	Come get some eats at this lovely little place down on Ocean Ave.  Learn how to ride and run all at the same time.  First 10 customers get a free shot.
    </p>
    
    <p class='text' id='d_activity_price'>
    	Cost: FREE
    </p>
    
    <a href='http://www.maps.google.com' id='d_activity_map_link'><img src="../images/maps/g_maps_test.jpg" id='d_activity_map' /></a>
    	
</div>