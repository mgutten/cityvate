// JavaScript for logged in
$(function() {

//resize body onload
		body_resize();

//code to deal with header dropdown
	//header dropdown animation	
	$("#drop,#dropdown").hover(
	
		function() {
				$("#dropdown").stop().animate({height:'100px'},300);
		},
		function() {
				//if focused on username textbox,
				//don't animate div to closed
				if(!$('.username').is(':focus')){
						$("#dropdown").stop().animate({height:'0px'},300);
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
		