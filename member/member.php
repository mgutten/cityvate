<?php

require_once('../html_display.php');
require_once('../db_functions.php');

$login = 'in';

$head = new Head($login,'Member Home');
$head->close();

$header = new Header($login);

$body = new Body_member(1);

//make call to retrieve activities list
$activities_call = new Activities();
$activities = $activities_call->activities();
$upcoming_event = $activities_call->upcoming();

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
          <div id='top_right_activities'>
              
              <?php
              //populate with list of activities from array
                      for($i=0;$i<count($activities);$i++){
                              $class = 'activity text';
                              //if no reserve date set, echo link to calendar------------------------------------------------
                              if(empty($activities[$i]['reserve_date']))
                                  $reserve_date = '<img src="../images/member/plus.png" title="Add to Calendar" class="activity_reserve plus"/>';
                              else{
                                  $date = DateTime::createFromFormat('Y-m-d', substr($activities[$i]['reserve_date'],0,10));
                                  $reserve_date = "<p class='activity_reserve'>".$date->format('M j')."</p>";
                              }
                              if($i==0)
                                  $class .= ' selected';
                          echo "<div class='$class' id='".$activities[$i]['aID']."'><p class='activity_name'>".$activities[$i]['name']."</p><p class='activity_type'>".$activities[$i]['type']."</p><a href='calendar.php'>$reserve_date</a></div>";
                          
                      }
              ?>
              
      		</div>
      
</div>

<div id='body_bottom_left'>
	<p class='text upcoming_title'>
    	Coming Up
    </p>
    <?php
		$block='';
		
		//display upcoming events
		for($i=0;$i<count($upcoming_event);$i++) {
				$reserve_date = $activities[$upcoming_event[$i]]['reserve_date'];
				$block .= '<p id="upcoming_'.$activities[$upcoming_event[$i]]['name'].'" class="upcoming text">';
				//parse reserve_date var for day, date, and time respectively
				$day = date('D',strtotime($reserve_date));
				$date_reserved = date('m/d',strtotime($reserve_date));
				$time = date('g a', strtotime($reserve_date));
				$block .= $day.' '.$date_reserved.' @ '.$time."</p>";
				
				$block .= "<a href='activity.php?num=".$activities[$upcoming_event[$i]]['aID']."'><p class='text upcoming_name'>"
							.$activities[$upcoming_event[$i]]['name']."</p></a>";
				if($i>=3) {
					break;
				}
				
		}
		echo $block;
		?>
    
</div>