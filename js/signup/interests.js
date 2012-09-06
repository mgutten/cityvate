// JavaScript Document

var interests = new Array();

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
				.removeClass('preference_img_down');
			$(this).children('.preference_title')
				.removeClass('preference_title_up');
		}
		//else hide title bar and change to color pic
		else{
			$(this).children('.preference_img')
				.attr('src',src.replace('.png','_color.png'))
				.addClass('preference_img_down');
			$(this).children('.preference_title')
				.addClass('preference_title_up');

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
	
	//add chosen interests to array on submit
	$('form[name="signup_form"]').submit(function() {
		
		$('.preference_img').each(function() {
			//if it's color, it's chosen so push to array
			if($(this).attr('src').match('_color'))
				interests.push(jQuery.trim($(this).siblings('.preference_title').text()));
			
		})
					
		$('#input_interests_hidden').val(JSON.stringify(interests));
		return true;
	});

	
})
		