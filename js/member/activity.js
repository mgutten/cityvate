// javascript for individual activity page (application/member/activity)

$(function() {
	
	$('.box_deselect').click(function() {
		$('.box_select').removeClass('box_select');
		$(this).addClass('box_select');
	})
	
	
});

//variable height of body bottom bar so
//it reaches bottom of window
function body_resize() {
				var height = $(window).height();
				if(height >854) {
				//calculate the height of the rest of visible components
				var subtract = parseInt($("#body_main").css("height"),10)+parseInt($("#body_main").css("margin-top"),10)+134;
				var div_height = (height - subtract) + 'px';
				$("#body_bottom").css('height',div_height);
				
				}
				
		}