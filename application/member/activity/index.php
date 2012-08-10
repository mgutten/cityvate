<?php
/* location of file: activity/member/activity/index.php */

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

//if navigating to page without aid #, redirect to member home
if(empty($_GET['activity']) || !is_numeric($_GET['activity']))
	header('location:/member');

$aid = $_GET['activity'];

$head = new Head('Activity #' . $aid ,1);
$head->script('member/activity');
$head->style('member/activity');
$head->close();

$header = new Header();

$body = new Body_individual_activity();
$body->display_body();
$body->get_activity_desc($aid);

//shorten var length
$a = $body->a;

//combined street and city address for links
$business_address = $a['business_street_address'] . ' ' . $a['business_city_address'];

//determine if additional information is required for this activity
if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/txt/additional/" . $body->a['month_in_use'] . "/" . $body->a_name . ".txt"))
	$additional = true;

?>

<div id='activity_title_container'>
    <p id='activity_title' class='text'><?php echo $a['name'];?></p>
	<p id='activity_type' class='text'><?php echo $a['type'];?></p>
</div>

<p id='savings' class='text'>
	<?php 
		//determine if free activity or not
		if($a['save'] == 0)
			echo "Free Activity";
		else
			echo "You saved " . $a['save'] . "%</p>";
	?>

<div id='body_left'>

	<!--"the short" section-->
	<div class='subtitle_container'>
       	 <p class='text subtitle yellow'>The short</p>
         <p class='text subtitle_value'>
			<?php
            //retrieve .txt doc of activity description
            //located in /txt/description/month(int)/activity_name.txt
			echo $body->txt_file('description');
			
            ?> 
         </p>
    </div>
    
	<!--"the box" section-->
	<div class='subtitle_container' id='the_box'>
    
    	<p class='text box_deselect box_select'>What</p>
        <p class='text box_deselect'>Details</p>
        <p class='text box_deselect'>Need</p>
        <?php if(!empty($additional)){
					echo "<p class='text box_deselect'>Additional</p>";
		}
		?>
        
        <div id='box_background'>
        	<div class='box_name select' id='what'>
				<p class='text box_inner yellow'><?php echo $body->txt_file('what');?></p>
                <p class='text box_inner small box_second_line'>Tip: <?php echo $body->txt_file('tip');?></p>
            </div>
            
            <div class='box_name' id='details'>
            	<div class='details_left'>
                    <p class='text details_title'>Yelp Rating:</p> 
                        <a href='<?php echo $a['business_yelp_address'];?>'><img src='/images/activity/rating/<?php echo intval($a['business_rating']);?>.png' class='rating'/></a>
                        <!--<a href='<?php echo $a['business_yelp_address'];?>' class='text details_subtitle details_second_line' id='yelp_link'>Check it</a>-->
                        
                    <p class='text details_title'>Location:</p>
                        <p class='text details_subtitle' id='street_address'><?php echo $a['business_street_address'];?></p>
                        <p class='text details_subtitle details_second_line' id='city_address'><?php echo $a['business_city_address'];?></p>
                        <a href='http://www.maps.google.com/maps?q=<?php echo $business_address;?>'
                          class='text details_subtitle details_second_line' id='directions' target='_blank'>Directions</a>
                         
                    <p class='text details_title'>Contact:</p>
                        <p class='text details_subtitle' id='phone'><?php echo $a['business_phone'];?></p>
                        <p class='text details_subtitle details_second_line' id='email'><?php echo $a['business_email'];?></p>
                        
                    <p class='text details_title'>Website:</p>
                        <a href='<?php echo $a['business_website'];?>' class='text details_subtitle' id='website' target='_blank'>
                            <?php 
                                //strip url of http://www and trailing filenames
								//so final product is (e.g. savoryorient.com)
								$pattern = '/http:\/\/([www]*\.*)(.*)(.com|.net|.edu|.gov|.org)\/*(.*)/';
								echo preg_replace($pattern, '$2$3', $a['business_website']);
                            ?></a>
                     <p class='text details_title'>Expires:</p>
                        <p class='text details_subtitle' id='expire'>
                            <?php 
                                $date = DateTime::createFromFormat('m/d/Y', $a['expire']);
                                echo $date->format('F j, Y');
                            ?></p>
                  </div>
                  <div class='details_right'>
                  		 <p class='text details_title'>Location:</p>
                        <p class='text details_subtitle' id='street_address'><?php echo $a['business_street_address'];?></p>
                        <p class='text details_subtitle details_second_line' id='city_address'><?php echo $a['business_city_address'];?></p>
                        <a href='http://www.maps.google.com/maps?q=<?php echo $business_address;?>'
                          class='text details_subtitle details_second_line' id='directions'>Directions</a>
                  </div>

               </div>
               
            <div class='box_name' id='need'>
            	<div id='box_need_left' class='box_need_container'>
                	<p class='text box_need_title yellow'>Checklist:</p>
					<p class='text box_need_val' id='checklist'> <?php echo $body->txt_file('need');?></p>
                </div>
                <div id='box_need_right' class='box_need_container'>
                    <p class='text box_need_title_right yellow'>Reservation:</p>
                    <p class='text box_need_val_right' id='reservation_needed'>
                    	<?php
							//if reservation not needed, display not needed
							if($a['reserve_needed'] == 0)
								$reserve = 'Not Needed';
							else
								$reserve = $a['reserve_needed'] . ($a['reserve_needed'] == 1 ? ' day' : ' days') . ' in advance';
								
							echo $reserve;
						?>
                    <p class='text box_need_title_right yellow'>To Redeem:</p>
                    <p class='text box_need_val_right' id='redeem'>Show a picture ID to the help desk.</p>
                    </p>
                </div>
            </div>
            
           	<?php
            //display additional info box if need it
			if(!empty($additional)){
				echo "<div class='box_name' id='additional'>";
				echo "<p class='text box_inner'>" . $body->txt_file('additional') . "</p>";
				echo "</div>";
			}
			?>
        </div>
        
      </div>
         
    	 <!--redemption section-->
        <div class='subtitle_container'>
       	 <p class='text subtitle yellow' di>Questions?</p>
         <p class='text subtitle_value details_redemption'>
			Feel free to email us at support@cityvate.com anytime.  We will answer any questions you may have.
         </p>
    	</div>

<!--end body_left -->        
</div>

<div id='body_right'>
<?php
	//determine which button to display
	//if reserve_date is set and coupon not expired
	if($body->a['reserve_date'] != '' && strtotime($a['expire']) > (time()-60*60*24)){
		//display cancel button and current reservation date
		echo "<img src='/images/activity/cancel_reserve_button.png' class='button' id='cancel'/>";
		
		echo "<p class='text reservation_date'>";
		
		echo date_to_str($a['reserve_date'],'F j, Y @ g a');
        
		echo "</p>";
	}
	//elseif not expired/done, link to calendar for reservation
	elseif(strtotime($a['expire']) > (time()-60*60*24) || $a['done'] == 1){
		echo "<a href='/member/calendar'><img src='/images/activity/reserve_button.png' class='button' /></a>
				<p class='text reservation_date'></p>";
	}
	//else it is already done/expired
	else{
		echo "<p class='text reservation_date'>EXPIRED</p>";
	}
?>

<img src="/images/activities/<?php echo $body->a['month_in_use'];?>/<?php echo strtolower($body->a_name);?>.jpg" id='activity_image' />

<a href='http://www.maps.google.com/maps?q=<?php echo $business_address;?>' target='_blank'
                          ><img src='http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $business_address;?>&zoom=13&size=280x180&maptype=roadmap
&markers=color:green%7C<?php echo $business_address;?>&sensor=false' id='maps_image' /></a>
</div>

<!-- hidden span to store aID for javascript usage-->
<span id='aid' class='<?php echo $aid;?>'></span>

<?php

$alert = new Alert_w_txt('confirmation');
$alert->confirm('cancel');

