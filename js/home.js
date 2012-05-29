// JavaScript Document

$(function() {
		
		
		//delay "fade" on mouseover so do not
		//accidentally trigger onload
		setTimeout('delay_fade("what")',1500);
		setTimeout('delay_fade("how")',1500);
		
		//"fade" onclick
		click_fade('what');
		click_fade('how');
		
		
		//set interval for fading and changing quotes
		//var s = setInterval('quote_fade()',12000);
					
});


//function to fade out, change, and fade in quotes
//function quote_fade() {
//		$(".quote_box").animate({opacity:0},800);
//		
//		setTimeout('$("#first").html($("#second").html())',800);
//		setTimeout('$("#second").html($("#first").html())',800);
//		
//		$("#first").animate({opacity:1},800);
//		$("#second").animate({opacity:1},800);
//		
//}

//function to trigger "fade" on click
function click_fade(div) {
	
		$("#"+div).click(function() {
			fade(div)
		})
}

//function to trigger "fade" on mouseover
function delay_fade(div) {		
		$('#'+div).mouseover(
			function(){
				fade(div);
			}
			);
}

//startup animation to fade out what/how img
//and fade in text below		
function fade(div) {
			
			//fade out img and set display to none
			$('#'+div+'_img').animate({opacity: 0},700,function(){ $(this).css('display','none')});
			$('#'+div+'_box').delay(800).animate({opacity:1},700);
			
			//loop through text classes and fade in one by one			
			for(i=1;i<4;i++){
				$('.'+i+'.'+div).delay(200+(i*600)).animate({opacity: 1},700);
			}
			
			
}
