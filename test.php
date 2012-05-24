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
				
				
//function to display how/what blocks and label
//appropriately
function how_what($howorwhat) {
	
				global $text_num;
				global $text;
				
				$block = "<div class='how_what' id='%s'>\n<img src='images/%s.png' id='%s_img'/>\n";
							
				foreach($text[$howorwhat] as $key=>$value){
					
					$block .= "<p class='title text $text_num %s'>$key</p>\n <p class='text $text_num %s'>$value</p>\n";
					
					$text_num++;	
				}
				$block .= "</div>\n";
							
				$block = str_replace('%s',$howorwhat,$block);
				
				echo $block;		

}
		
		
?>

<img src="images/what_box.png" id="what_box" class="how_what_box"/>
<img src="images/how_box.png" id="how_box" class="how_what_box"/>

    <?php 
		how_what('what');

		$text_num=1; 
		how_what('how');
	?>
