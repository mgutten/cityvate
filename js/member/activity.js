// javascript for individual activity page (application/member/activity)

$(function() {
	
	$('.box_deselect').click(function() {
		$('.box_select').removeClass('box_select');
		$(this).addClass('box_select');
		
		$('.select').removeClass('select');
		var id = $(this).text().toLowerCase();
		$('#'+id).addClass('select');
	})
	
	//click on cancel_reserve_button
	$('#cancel').click(function() {
		var reservation = $('.reservation_date').text();
		$('.alert_main_confirm').html('Are you sure you want to cancel your reservation of:');
		$('.alert_main_val').html(reservation);
		$('.confirmation').show();
	})
	
	//confirm cancellation of reservation request
	$('#yes').click(function() {
		
		cancel_reservation();
		
	})
	
	//adjust margin of image to align with box
	align_image();
		
	
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
		
function align_image() {
	//order of numbers here: .subtitle(margin-top), box_deselect(height), adjustment to align
	var goal = parseInt($('.subtitle_container:first-child').css('margin-top'),10)
				+ parseInt($('.subtitle_container:first-child').css('height'),10) + 30 + 25 + 7;
	
	//41 = height of button		
	var right_top = 41 + parseInt($('.button').css('margin-top'),10)
					+ parseInt($('.reservation_date').css('height'),10) + parseInt($('.reservation_date').css('margin-top'),10);
					
	$('#activity_image').css('margin-top',goal-right_top + 'px');
				
}

function cancel_reservation() {
	
	var aid = $('#aid').attr('class');
		
	//ajax call to cancel reservation then reload page to update images
	$.ajax({
		url: '/member/ajax_calls/calendar_ajax.php',
		type: 'GET',
		data: {aID : aid},
		success: function() {
			
			window.location.reload();
		}
	});
}
	