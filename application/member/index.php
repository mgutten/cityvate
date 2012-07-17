<?php
/* location of file member/index.php */

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

//create the head section
$head = new Head('Member Home');
$head->close();

//create top header
$header = new Header();

//create body object with first tab
//selected(i.e. my_activities)
$body = new Body_member(1);

//make call to retrieve activities list
$activities_call = new Activities();
$activities = $activities_call->activities();
//store upcoming events for coming week
$upcoming_event = $activities_call->upcoming();

?>

<div id='body_top'>
	<a href='
	<?php
    	if(!empty($activities))
			echo "/member/activity/" . $activities[0]['aID'];
	?>' id='picture_link'>
          <div id='picture'>
                  <div id='picture_shown_outer'>
                      <img src='<?php
         if(!empty($activities))
		 	echo "/images/activities/" . date('m') . "/" . str_replace(' ','_',$activities[0]['name']) . ".jpg";
		else
			echo "/images/activities/no_activities.png";?>' class='activity_picture shown' id='picture_shown'/>
                   </div>
                   <div id='picture_hidden_outer'>
                      <img src='' id='picture_hidden' class='picture_toggle'/>
                   </div>
                   
      		<div id='picture_top'>
         <?php
		 //if no activities, toggle the picture bars
    	if(!empty($activities)) 
			$display = '';
		else
			$display = 'style="display:none;"';
			
		?>
			        
                  
                      <div class='picture_banner picture_toggle' id='picture_top_banner' <?php echo $display;?>>
                      </div>
                      <p class='text banner picture_toggle' id='picture_banner_text' <?php echo $display;?>>
                              <?php if(!empty($activities))
							  			echo $activities[0]['name'];?>
                      </p>
                      <div class='picture_banner picture_toggle' id='picture_bottom_banner' <?php echo $display;?>>
                      </div>
                      <p class='text banner picture_toggle' id='click_banner' <?php echo $display;?>>
                              Click for details
                      </p>
                   
          

        	</div>
        </div>
        </a>
						
         <div id='top_right_month'>
                  <div id='left_arrow' class='arrow' onclick='arrow_click(-1)'></div>
                  <div id='right_arrow' class='arrow' onclick='arrow_click(1)' style='display:none'></div>
          		  <p id='<?php echo date('m').date('F');?>' class='text activity_month'><?php echo date('F');?>'s Activities</p>
      
          </div>
          
              
              <?php
              //populate with list of activities from array
                      $body->member_activity($activities);
              ?>

      		
            <div id='activity_done' class='top_right_activities'>
				<p class='text' id='activity_done_title'>Finished Activities</p>
					
               
					<?php
						$body->member_finished();
					?>			
						
					
            </div>
						
      
</div>

<div id='body_bottom_left'>
	<p class='text upcoming_title'>
    	Coming Up
    </p>
    <?php
		//function to display upcoming reservations
		$body->member_upcoming($upcoming_event);
		
		?>
    
</div>

<div id="body_bottom_right">
	<p class='text upcoming_title'>
    	Free Activity of the Day
    </p>
    <p class="text d_activity" id='d_activity_title'>
    	Doug Dimmadome's Grand Opening Dimmadale
    </p>
    <p class='text d_activity' id='d_activity_description'>
    	Come get some eats at this lovely little place down on Ocean Ave.  Learn how to ride and run all at the same time.  First 10 customers get a free shot.
    </p>
    
    <a href='http://www.maps.google.com' id='d_activity_map_link'><img src="/images/maps/g_maps_test.jpg" id='d_activity_map' /></a>
    	
</div>