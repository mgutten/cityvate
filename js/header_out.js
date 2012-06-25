// JavaScript Document
$(function() {

//resize body onload
		body_resize();

//code to deal with header dropdown
	//header dropdown animation	
	$("#drop,#dropdown").hover(
	
		function() {
				$("#dropdown").css('z-index','2');
				$("#dropdown").stop().animate({top:'0px'},300);
		},
		function() {
				
				//if focused on username textbox,
				//don't animate div to closed
				if(!$('.username').is(':focus')){
						$("#dropdown").css('z-index','-1');
						$("#dropdown").stop().animate({top:'-70px'},300);
				}
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
	
	

//end "ready" function
});


//resize bottom body bar on window resize
$(window).resize(function() {
	body_resize()
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
		
		
