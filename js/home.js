// JavaScript Document

$(document).ready(function() {
		
		body_resize();
		
		setTimeout('fade("what")',1500);
		
		//mouseover fade
		//$('#what').mouseover(
		//	function(){
		//		fade('what');
		//	}
		//	);
		
});

//resize bottom body bar on window resize
$(window).resize(function() {
	body_resize()
	});	

//variable height of body bottom bar so
//it reaches bottom of window
function body_resize() {
				
				var height = $(window).height();
				var div_height = height - 854;
				
				$("#body_bottom").css('height',div_height);
				
		}

//startup animation to fade out what/how img
//and fade in text below		
function fade(div) {
			
			//fade out img and set display to none
			$('#'+div+'_img').animate({opacity: 0},700,function(){ $(this).css('display','none')});
			$('#'+div+'_box').delay(800).animate({opacity:1},700);
			
			var fade;
			//loop through text classes and fade in one by one			
			for(i=1;i<4;i++){
				$('.'+i+'.'+div).delay(200+(i*600)).animate({opacity: 1},700);
			}
			
			setTimeout('fade("how")',2500);
			
}
