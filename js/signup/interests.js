// JavaScript Document

$(function() {
	
	//move next button
	$('.next_button').css({'margin-left':'125px','margin-top':'170px'});
	
	//change preference image and back bar on click
	$('.preference').click(function() {
		
		var src = $(this).children('.preference_img').attr('src');
		
		//if preference is selected, show title bar
		//and change to black and white pic
		if(src.match('_color')){
			$(this).children('.preference_img')
				.attr('src',src.replace('_color',''))
				.css('margin-top','-16px');
			$(this).children('.preference_title')
				.css('margin-top','0px');
		}
		//else hide title bar and change to color pic
		else{
			$(this).children('.preference_img')
				.attr('src',src.replace('.png','_color.png'))
				.css('margin-top','0px');
			$(this).children('.preference_title')
				.css('margin-top','-18px');
		}
	})
	
	//change back bar to show onhover if preference is selected
	$('.preference').hover(function() {
		
		var src = $(this).children('.preference_img').attr('src');
		
		if(src.match('_color')){
			$(this).children('.preference_img')
				.css('margin-top','-16px');
			$(this).children('.preference_title')
				.css('margin-top','0px');
		}
	},
	function() {
		
		var src = $(this).children('.preference_img').attr('src');
		
		if(src.match('_color')){
			$(this).children('.preference_img')
				.css('margin-top','0px');
			$(this).children('.preference_title')
				.css('margin-top','-18px');
		}
	})

	
})
		