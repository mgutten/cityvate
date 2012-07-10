// JavaScript for logged in
//declare set month array for member.php
//and calendar.php
  var month_array = new Array();
	  month_array[01]="January";
	  month_array[02]="February";
	  month_array[03]="March";
	  month_array[04]="April";
	  month_array[05]="May";
	  month_array[06]="June";
	  month_array[07]="July";
	  month_array[08]="August";
	  month_array[09]="September";
	  month_array[10]="October";
	  month_array[11]="November";
	  month_array[12]="December";


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
						$("#dropdown").stop().animate({top:'-100px'},300);
				}
	})
	
	//change any input back to regular colors if they are red
	$('input').focus(function() {
		if($(this).is('.red_back')){
			$(this).removeClass('red_back');
		}
	});
	
	
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
				var subtract = parseInt($("#body_main").css("height"),10)+parseInt($("#body_main").css("margin-top"),10)+parseInt($("#header2_back").css("height"),10)+parseInt($("#header2_back").css("margin-top"),10)+134;
				var div_height = height - subtract;
				$("#body_bottom").css('height',div_height);
				
				}
				
		}
		
function validate(form_name) {
		var returning = 1;
		
		$('#' + form_name + ' input[type=text],input[type=password]').each(function(){
			
			if($.trim($(this).val()) == ''){
				returning = 0;
				$(this).addClass('red_back');
			}
			
		});
		
		if(returning == 0){
			return false;
		}
		else
			return true;
		
		
}
	
	
		

		