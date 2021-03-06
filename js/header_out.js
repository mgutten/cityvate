// JavaScript Document
var timeout = new Array();
/*
timeout[0] = timeout to hide tooltip
*/

$(function() {
	
	//resize body onload
	body_resize();
		
	//deal with header dropdown animation
	$("#drop,#dropdown").hover(
	
		function() {
				$("#dropdown").css('z-index','2');
				$("#dropdown").stop().animate({top:'0px'},300);
		},
		function() {
				
				//if focused on username textbox,
				//don't animate div to closed
				if(!$('.username').is(':focus')){
						var height = parseInt($('#dropdown').css('height'),10) * -1;
						$("#dropdown").css('z-index','1');
						$("#dropdown").stop().animate({top: height + 'px'},300);
				}
		}
	)
	
	//dropdown for my_activities
	$("#drop_activities,#dropdown_activities").hover(
	
		function() {
				$("#dropdown_activities").css('z-index','2');
				$("#dropdown_activities").stop().animate({top:'0px'},300);
		},
		function() {
				var height = parseInt($('#dropdown_activities').css('height'),10) * -1;
				$("#dropdown_activities").css('z-index','1');
				$("#dropdown_activities").stop().animate({top: height + 'px'},300);
					
	})

	
	//expand div when focus on username
	//textbox
	$('.username').focus(function() {
			$("#dropdown").stop().css({height:'70px'})
	})
	
	//clear boxes for username and 
	//pw when clicked
	$(".username").click(function() {
			if($(this).val()=='Username/email' || $(this).val()=='password'){
					$(this).val('');
			}
	})
	
	//change background color of input boxes in case they have been
	//turned red due to validate()
	$('input[type=text],input[type=password]').focus(function() {
			$(this).removeClass('red_back');
		});
	
	//hide confirmation box for forgot password page
	$('.confirmation').hide();
	
	//submit forgot password when click yes of confirmation box
	$('#yes').click(function() {
		$('form:eq(1)').submit();
	})
	
	//run validate function when click on submit button for form
	$('#input_submitter').click(function() {
		return validate($(this).parent('form').attr('id'),true);
	})
	
	//change color of selected package for step3 signup
	$('input.package').change(function() {
		$('.green.package_title,.green.package_cost').removeClass('green');
		$(this).siblings().addClass('green');
	})
	
	//toggle length of subscription dropdown for step3 signup
	$('#input_auto_renew').change(function() {
		
			$('.when_toggle').toggle();
		
	})
	
	//tooltip
	$('.tooltip_question').hover(function() {
		
		var html = $(this).attr('tooltip');
		var top = parseInt($(this).offset().top,10);
		var left = parseInt($(this).offset().left,10);
		
		clearTimeout(timeout[0]);
		
		$("#tooltip")
					.html(html)
					.css("top",(top + 10) + 'px')
					.css('left',(left + 10) + 'px')
					.stop()
					.show()
					.animate({'opacity':'1'},300);
	},
	function() {
		
		$('#tooltip')
					.stop()
					.animate({'opacity':'0'},300)
		
		timeout[0] = setTimeout(function() {
						$('#tooltip').hide();
						},305);	
		
	});

//end "ready" function
});


//resize bottom body bar on window resize
$(window).resize(function() {
	body_resize();
});	
	

//variable height of body bottom bar so
//it reaches bottom of window
function body_resize() {
				var height = $(window).height();
				if(height >854) {
					//calculate the height of the rest of visible components
					var subtract = parseInt($("#body_main").css("height"),10)+parseInt($("#body_main").css("margin-top"),10)+114;
					var div_height = height - subtract;
					$("#body_bottom").css('height',div_height);
				
				}
				
}
			
//validate function for various login boxes
function validate(form_name, confirmation) {
	
	//give confirm a default value of false except if specified
	confirmation = typeof confirmation !== 'undefined' ? confirmation : false;
	
	
	var returning = 1;
	

		$('#' + form_name + ' input[type=text],#' + form_name + ' input[type=password],textarea').each(function(){
			
			if($.trim($(this).val()) == '' ||
				$(this).val() == 'Username/email'){
					
				returning = 0;
				$(this).addClass('red_back');
				
			}
		});
		
		if(returning == 0)
			return false;
		else{
			
			//toggle confirmation box if confirmation var is set to true
			if(confirmation === true){
				//get first box' selector
				var change_box = $('#change_info :input:first');
				var text = change_box.val();
				
				//test if it's a password and change to *** not letters
				if(change_box.is('input[type=password]'))
					text = '*********';
				
				
				$('.alert_main_val').text(text);
				$('.confirmation').show();
				
				return false;
			}
			
			return true;
		}
	
}		
