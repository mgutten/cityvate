<?php

include('html_display.php');

$log = 'out';
$head = new Head($log,'Home');
$head->close();

$header = new Header($log);

$body = new Body();

$text_num=1;
$text=array('what'=>array(
				"Cityvate is your \"activity agent.\""=>'We find awesome local activities just for you. Your free time is valuable, so let us do the research for you and help you balance work and play.',
				"We save you money."=>"We don't just sell coupons and ship you off to fend for yourself.  We find the local hot spots, reserve them for you, and give you fresh ideas for some easy fun.",
				"Make memories worth having."=>"This is your life.  Do you want to look back on it and regret never enjoying it?  You ever want to try bungee jumping? How about cooking classes? Acting? Kayaking?  At Cityvate, the sky's the limit."
				),
			'how'=>array(
				"It's your budget for fun."=>'We reinvest your money in your own life, giving you monthly options of exciting places to go and things to do, all already paid for!',
				"No commitment."=>"At the beginning of each month you are given a variety of local activities that you can reserve.  If you don't like any, then you get a full refund, no questions asked.",
				"It's not about the money."=>"We don't want you to worry about the cost of fun.  As such, your budget is converted into \"tokens\" which can be used to redeem activites."
				)
				);
					
		
?>

<img src="images/what_box.png" id="what_box" class="how_what_box"/>
<img src="images/how_box.png" id="how_box" class="how_what_box"/>

    <?php 
		how_what('what');

		how_what('how');
		
	?>
<a href="signup.php" alt="Signup"><img src="images/button_pricing.png" id="pricing" /></a>

<?php
		quote_box('first',"I found myself caught in a rut, sitting around bored out of my mind.  But then I signed up for Cityvate, and now have something to look forward to every week!","John B");
		quote_box('second',"I work all day and don't have time to find fun things to do in my free time.  Cityvate does the hard work for me and leaves me smiling every time.","Emily S");
		
?>