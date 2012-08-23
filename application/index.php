<?php
	/*location of file: application/index.php 
				HOMEPAGE
	*/
	
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

$head = new Head('Home');
$head->close();

$header = new Header();

$body = new Body();

$text_num=1;
$text=array('what'=>array(
				"Cityvate is your \"activity agent.\""
					=>'We find awesome local activities just for you. Spend your free time having fun, not researching where to go or what to do.  
						That\'s our job: to help you balance work and play.',
				"We save you money."
					=>"Save up to 30% off the hottest local activities, venues, and restaurants your area has to offer. 
						We find the local hot spots, reserve them for you, and give you fresh ideas for some cheap and easy fun.",
				"Make memories worth having."
					=>"This is your life.  Do you want to look back on it and regret never enjoying it? 
						 You ever want to try bungee jumping? How about cooking classes? Acting? Kayaking?  At Cityvate, the sky's the limit."
				),
			'how'=>array(
				"It's your budget for fun."
					=>'We reinvest your money in your own life, giving you monthly options of exciting places to go and things to do, all already paid for!  
						Just show up with an ID and you\'re set.',
				"No commitment."
					=>"At the beginning of each month you are given a variety of local activities that you can reserve. 
						 If you don't like any, then you get a full refund, no questions asked.  Your money remains your money.",
				"It's not about the money."
					=>"Life's a game, and it's time to treat it as such.  Your money is converted into \"tokens\" which can be used to reserve monthly deals.  
						You work too hard and don't play enough.  Let us help you get out and try something new."
				)
			);

//retrieve next month's activities		
$activities_call = new Activities();
$activities = $activities_call->next_month_activities();				
	
?>
<script type='text/javascript'>

<?php
	//loop through next month's activities and store in
	//javascript array for use in slide animation
	for($i = 0; $i < count($activities); $i++){
		
		echo 'activities['.$i.'] = new Object;';
		echo 'activities['.$i.'].type = "' . $activities[$i]['type'] . '";';
		echo 'activities['.$i.'].save = "' . $activities[$i]['save'] . '";';
		
	}
?>

</script>

<img src="images/home/what_box.png" id="what_box" class="how_what_box" />
<img src="images/home/how_box.png" id="how_box" class="how_what_box" />

    <?php 
		how_what('what');

		how_what('how');
		
	?>
<p id='next_month' class='text'>
    Next Month's Activities Include:
</p>


<div id='activities_container'>
	<div id='activities_inner_container'>
        <div id='activities_slide1' class='text activities_slide'>
            <p class='text green activity_name' id='activity_name1'>
                <?php echo $activities[0]['type'];?>
            </p>
            <img src='/images/activities/mini/<?php echo str_replace(' ','_',$activities[0]['type']);?>.png' class='activity_img' id='activity_img1'/>
            <p class='text green savings' id='activity_save1'><?php echo $activities[0]['save'];?>% off</p>
        </div>
        <div id='activities_slide2' class='text activities_slide'>
            <p class='text green activity_name' id='activity_name2'>
                <?php echo $activities[1]['type'];?>
            </p>
            <img src='/images/activities/mini/<?php echo str_replace(' ','_',$activities[1]['type']);?>.png' class='activity_img' id='activity_img2'/>
            <p class='text green savings' id='activity_save2'><?php echo $activities[1]['save'];?>% off</p>
        </div>
     </div>
</div>

<?php
		quote_box('first',"I found myself caught in a rut, sitting around bored out of my mind.  But then I signed up for Cityvate, and now have something to look forward to every week!","John B");
?>
<a href="signup.php" alt="Signup"><img src="/images/home/button_pricing.png" id="pricing" /></a>
<?php
		quote_box('second',"I work all day and don't have time to find fun things to do in my free time.  Cityvate does the hard work for me and leaves me smiling every time.","Emily S");
	?>	
