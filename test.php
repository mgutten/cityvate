<?php

include('html_display.php');

$log = 'out';
$head = new Head($log,'Home');
$head->close();

$header = new Header($log);

$body = new Body();

$text_num=1;
//function to display <p> tags and label
//appropriately
function how_what($title,$text,$howorwhat) {
	
				global $text_num;
	
				echo "<p class='title text $text_num $howorwhat'>$title</p>\n";
				echo "<p class='text $text_num $howorwhat'>$text</p>";
				
				$text_num++;
				
}
		
		
?>

<img src="images/what_box.png" id="what_box" class="how_what_box"/>
<img src="images/how_box.png" id="how_box" class="how_what_box"/>
<div class='how_what' id='what'>
	<img src="images/what.png" id='what_img'>
    <?php how_what("Cityvate is your \"activity agent.\"",'We find awesome local activities just for you. Your free time is valuable, so let us do the research for you and help you balance work and play. ','what');
	how_what("We save you money.","We don't just sell coupons and ship you off to fend for yourself.  We find the local hot spots, reserve them for you, and give you fresh ideas for some easy fun.",'what');
	how_what("Make memories worth having.","This is your life.  Do you want to look back on it and regret never enjoying it?  You ever want to try bungee jumping? How about cooking classes? Acting? Kayaking?  At Cityvate, the sky's the limit.",'what');
	?>
</div>
<div class='how_what' id='how'>
    <img src="images/how.png" id='how_img'>
    <?php
	$text_num=1; 
	how_what("Cityvate is your budget for fun.",'We reinvest your money in your own life, giving you monthly options of exciting places to go and things to do, all already paid for!','how');
	how_what("No commitment.","At the beginning of each month you are given a variety of local activities that you can reserve.  If you don't like any, then you get a full refund, no questions asked.",'how');
	how_what("It's not about the money.","We don't want you to worry about the cost of fun.  As such, your budget is converted into \"tokens\" which can be used to redeem activites.",'how');
	?>
</div>