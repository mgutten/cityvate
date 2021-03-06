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
//if nearing end of current month, change to next month
//and display new activities
$valid_subscription = $activities_call->check_subscription();
if($body->check_new_activities() === true && ($valid_subscription === true || $_SESSION['user']['tokens_balance'] > 0)){
	$time = strtotime('+1 month');
	$selected_month = date('m',strtotime('+1 month'));
}
else{
	$time = time();
	$selected_month = date('m');
}

$activities = $activities_call->activities($selected_month);
//store upcoming events for coming week
$upcoming_event = $activities_call->upcoming();

if(empty($activities) && $time > time() && ($valid_subscription === true || $_SESSION['user']['tokens_balance'] > 0))
	$new_activities = true;
else
	$new_activities = false;

//if loading page and there are new activities
//and user has valid subscription
if($new_activities === true){
	
	//store filenames of images from next month's activities in array
	$image_filenames = array_slice(array_filter(scandir('X:/Program Files (x86)/wamp/www/Local_site/cityvate/images/activities/' . date('m',strtotime('+1 month')) . '/')), 0, 10);
	
	//create inline script to define new_activities js array
	echo "<script type='text/javascript'>";
	
	//set a_array[2] (i.e. whether subscription is valid or not) to true
	echo "a_array[2] = true;";
	
	//loop through image filename's array and remove .jpg
	//then store in new_activities js array
	$c = 0;
	for($i = 2; $i < count($image_filenames); $i++){
		echo "new_activities[$c] = \"" . str_replace('.jpg','',$image_filenames[$i]) . "\";";
		$c++;
	}
	
	echo "</script>";
	
}

?>

<div id='body_top'>
	<a href='
	<?php
    	if(!empty($activities))
			echo $url['activity'] . "/" . $activities[0]['aID'];
		elseif($new_activities === true)
			echo $url['new'];
			
	?>' id='picture_link'>
          <div id='picture'>
                  <div id='picture_shown_outer'>
                      <img src="<?php
		//standard case when activities were purchased, display first activities picture
        if(!empty($activities))
		 	echo "/images/activities/" . date('m') . "/" . str_replace(' ','_',strtolower($activities[0]['name'])) . ".jpg";
		//case when we are in last 5 days of month and their subscription is still valid
		elseif($new_activities === true){
			$image_filenames = array_slice(array_filter(scandir('X:/Program Files (x86)/wamp/www/Local_site/cityvate/images/activities/' . date('m',strtotime('+1 month')) . '/')), 0, 10);
			echo "/images/activities/" . date('m', strtotime("+1 month")) . "/" . $image_filenames[2];
		}
		//case when no activities exist and either subscription no longer valid
		//or no activities were purchased
		else
			echo "/images/activities/no_activities.png";?>" class='activity_picture shown' id='picture_shown'/>
                   </div>
                   <div id='picture_hidden_outer'>
                      <img src='/images/blank.png' id='picture_hidden' class='picture_toggle'/>
                   </div>
                   
      		<div id='picture_top'>
         <?php
		 //if no activities, toggle the picture bars
    	if(!empty($activities)) 
			$display = $display_lower =  '';
		//else if new activities are available and valid subscrip
		//display top bar only 
		elseif($new_activities === true){
			$display = '';
			$display_lower = 'style="display:none"';
		}
		else{
			$display = 'style="display:none;"';
			$display_lower = 'style="display:none;"';
		}
			
		?>
			                          
                      <div class='picture_banner picture_toggle' id='picture_top_banner' <?php echo $display;?>>
                      </div>
                      <p class='text banner picture_toggle' id='picture_banner_text' <?php echo $display;?>>
                            <?php 
									if(!empty($activities))
							  			echo $activities[0]['name'];
									elseif($new_activities === true)
										echo 'New Activities Available';
										
							?>
                      </p>
                      <div class='picture_banner picture_toggle' id='picture_bottom_banner' <?php echo $display_lower;?>>
                      </div>
                      <p class='text banner picture_toggle' id='click_banner' <?php echo $display_lower;?>>
                              Click for details
                      </p>
                   
          

        	</div>
        </div>
        </a>
						
         <div id='top_right_month'>
                  <div id='left_arrow' class='arrow'></div>
                  <div id='right_arrow' class='arrow grey_arrow'></div>
          		  <p id='<?php echo date('m',$time).date('F',$time);?>' class='text activity_month'><?php echo date('F',$time);?>'s Activities</p>
      
          </div>
          
              
             <?php
              //populate with list of activities from array
                      $body->member_activity($activities,$valid_subscription);
             ?>

      		
            <div id='activity_done' class='top_right_activities'>
            
            <?php
				//hide finished activities if there are new activities,
				//but need to show them for ajax calls
				if($new_activities === true)
					$display = 'style="display:none;"';
				else
					$display = '';
			?>
            <p class='text activity_done_hide' id='activity_done_title' <?php echo $display;?>>Finished Activities</p>
            <div id='activity_done_lower' class='activity_done_hide' <?php echo $display;?>>
            <?php
			
				$body->member_finished();
				
			?>
            </div>
            <?php 
				if($new_activities === true && $activities_call->check_new_activities() !== false){
					
					echo "<a href='" . $url['new'] . "' class='text green'>Edit your activities</a>";
				}
					
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
