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

?>

<div id='activity_title_container'>
    <p id='activity_title' class='text'><?php echo $a['name'];?></p>
	<p id='activity_type' class='text'><?php echo $a['type'];?></p>
</div>

<p id='savings' class='text'>You saved <?php echo $a['save'];?>%</p>

<div id='body_left'>

	<!--"the short" section-->
	<div class='subtitle_container'>
       	 <p class='text subtitle'>The short</p>
         <p class='text subtitle_value'>
			<?php
            //retrieve .txt doc of activity description
            //located in /txt/description/month(int)/activity_name.txt
			echo $body->txt_file('description');
            ?> 
         </p>
    </div>
    
	<!--"the details" section-->
	<div class='subtitle_container'>
    
    	<p class='text box_deselect box_select'>What</p>
        <p class='text box_deselect' id='details' >Details</p>
        <p class='text box_deselect' id='need' style='display:none;'>Need</p>
        
        <div id='box_background'>
        <?php echo $body->what();?>
        </div>
    <!--
		<p class='text subtitle'>The details:</p>
    	<div class='subtitle_value'>
        
        	<p class='text details_title'>Yelp Rating:</p> 
            	<img src='/images/activity/rating/<?php echo intval($a['business_rating']);?>.png' class='rating'/>
                <a href='<?php echo $a['business_yelp_address'];?>' class='text details_link'>Check it</a>
                
            <p class='text details_title'>Location:</p>
            	<p class='text details_subtitle' id='street_address'><?php echo $a['business_street_address'];?></p>
                <p class='text details_subtitle details_second_line' id='city_address'><?php echo $a['business_city_address'];?></p>
                <a href='http://www.maps.google.com/maps?q=
					<?php echo str_replace(' ','+',$a['business_street_address']) . '+' . str_replace(' ','+',$a['business_city_address']);?>
                 ' class='text details_subtitle details_second_line' id='directions'>Directions</a>
                 
           	<p class='text details_title'>Contact:</p>
            	<p class='text details_subtitle' id='phone'><?php echo $a['business_phone'];?></p>
                <p class='text details_subtitle details_second_line' id='email'><?php echo $a['business_email'];?></p>
                
            <p class='text details_title'>Website:</p>
            	<a href='<?php echo $a['business_website'];?>' class='text details_subtitle' id='website'>
					<?php 
						//clean up url by stripping http and trailing slash
						$replace = array('http://','/');
						echo str_replace($replace,'',$a['business_website']);
					?></a>
             <p class='text details_title'>Expires:</p>
             	<p class='text details_subtitle' id='expire'>
					<?php 
						$date = DateTime::createFromFormat('m/d/Y', $a['expire']);
						echo $date->format('F j, Y');
					?></p>
              <p class='text details_title'>Tip:</p>
              	<p class='text details_subtitle ' id='tip'>
                	<?php
						//retrieve .txt doc of activity description
						//located in /txt/tip/month(int)/activity_name.txt
						echo $body->txt_file('tip');
					?> </p>
         
         </div> 
         -->
         </div>
         
         <!--
         <!--redemption section
        <div class='subtitle_container'>
       	 <p class='text subtitle'>Redemption</p>
         <p class='text subtitle_value details_redemption'>
			<?php
            //retrieve .txt doc of activity description
            //located in /txt/descriptions/month(int)/activity_name.txt
			echo $body->txt_file('redemption');
            ?> 
         </p>
    	</div>-->
    
    	 <!--redemption section-->
        <div class='subtitle_container'>
       	 <p class='text subtitle'>Questions?</p>
         <p class='text subtitle_value details_redemption'>
			Feel free to email us at support@cityvate.com anytime.  We will answer any questions you may have.
         </p>
    	</div>

<!--end body_left -->        
</div>

<div id='body_right'>
<?php
	//determine which button to display
	if($body->a['reserve_date'] != '')
		$button = 'cancel_reserve';
	else
		$button = 'reserve';
?>
<a href='/member/calendar'><img src='/images/activity/<?php echo $button;?>_button.png' class='button' /></a>

<img src='/images/activities/<?php echo $body->a['month_in_use'];?>/<?php echo $body->a_name;?>.jpg' id='activity_image' />

<img src='/images/maps/g_maps_test.jpg' id='maps_image' />
</div>