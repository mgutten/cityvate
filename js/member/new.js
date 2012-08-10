// JavaScript Document

$(function() {
	
	
	//adjust height of body_left background to match height of body_left
	//also adjust margin-top of body_left to overlay body_left_background
	var body_left_height = parseInt($('#body_left').css('height'),10);
	$('#body_left_back').css('height',body_left_height + 'px');
	$('#bottom_right_container,#body_left').css('margin-top','-' + body_left_height + 'px');
	
	//set all checkboxes to unchecked on page load
	$('input[type=checkbox]').each(function() {
		$(this).attr('checked',false)
	})
	
	//trigger functions on checkbox change
	$('.body_left_checkbox').change(function() {
		
		toggle_class($(this));
		
	})
	
	//prevent trigger of "click" function for parent div .body_left_bar
	$('.body_left_checkbox').click(function(e) {
		e.stopPropagation();
	})
	
	//allows selection of activities by clicking on whole bar, not just checkbox
	$('.body_left_bar').click(function() {
		
		//since it's the parent of the tag we need(checkbox), adjust tag variable
		var tag = $(this).children('.body_left_checkbox');
			
		//change checkbox value
		if(tag.is(':checked')){
			tag.prop('checked',false)
		}
		else{
			tag.prop('checked',true)
		}
		
		toggle_class(tag);
		
	})
	
	
	//run ajax for activity description and cancel the selection of this activity
	$('.body_left_details').click(function(e) {
		
		e.stopPropagation();
		
		var aid = $(this).parent().children('.body_left_checkbox').attr('value');
		bottom_right_load(aid);
			
	})

	
	//confirmation buttons of alert
	$('#no').click(function(){
		$('.too_many').hide();
	})
	
	$('#refund,#carryover,#donate').click(function() {
		$('#input_leftover').attr('value',$(this).attr('id'));
		$('#activities').submit();
	})
	
	
})


function toggle_class(tag) {
	
	//if checkbox is now checked, make background of bar green
	if(tag.is(':checked'))
		tag.parent().addClass('body_left_bar_selected');
	else
		tag.parent().removeClass('body_left_bar_selected');
	
	//change balance of tokens
	change_balance(tag);
	
	//check bars to see if cost is greater than balance
	//if so change to red
	//check_bars();
	
	bottom_right_load(tag.attr('value'));
		
}

//change the balance of tokens after an activity is chosen/unchosen
function change_balance(tag) {
	
	var cost = (tag.parent().children('.body_left_cost').html() == 'Free' ? 0 : tag.parent().children('.body_left_cost').html());
	var balance = $('#token_balance').html();
	
	if(!tag.is(':checked'))
		cost = cost * -1;
	
	//if negative value of tokens, make balance red
	if(balance - cost < 0)
		$('#top_left_balance').addClass('red')
	else
		$('#top_left_balance').removeClass('red')
		
	$('#token_balance').html(balance-cost)
	
}

/*
//change the number of activities in top_right section
function check_bars() {
	
	$(".body_left_bar").each(function() {
		var cost = parseInt($(this).children('.body_left_cost').html());
		var balance = parseInt($('#token_balance').html());
		
		if(cost > balance && !$(this).is('.body_left_bar_selected')){
			$(this).children('.body_left_name,.body_left_cost').addClass('red')
		}
		else {
			$(this).children('.body_left_name,.body_left_cost').removeClass('red')
		}
		
	})
			
}
*/

//validate balance of tokens and display appropriate alert
function validate() {
	
	var tokens_left = $('#token_balance').html();
	
	if(tokens_left < 0){
		$('.too_many').show();
		return false;
	}
	
	if(tokens_left > 0){
		$('#leftover_tokens').html(tokens_left);
		$('#refund').attr('title','A cash refund of $' + (tokens_left*2.5).toFixed(2) + ' will be credited to your account')
		$('.leftover').show();
		return false;
	}
		
}

//ajax call to retrieve description/info for activity
function bottom_right_load(aid) {

	$.ajax({
		url: '/member/ajax_calls/new_ajax.php',
		type: 'POST',
		data: {aid: aid},
		success: function(data) {
			
			var activity = $.parseJSON(data);
			
			body_right_populate(activity);
			
		}
		
	})
	
}

//populate bottom_right div with activity description
function body_right_populate(activity){
	
	//hide default and show ajax results
	$('#bottom_right_default').hide();
	$('#bottom_right_ajax').show();
	
	//activity name
	$('#bottom_right_name').text(activity.name);
	
	//activity type
	$('#bottom_right_type').text(activity.type);
	
	//activity img
	$('#bottom_right_img').attr('src','/images/activities/' 
								+ activity.month_in_use + '/'
								+ activity.name.replace(/ /g,'_') + '.jpg');
	//description						
	$('#bottom_right_text').text(activity.desc);
	
	//map
	var business_address = activity.business_street_address + ' ' 
							+ activity.business_city_address;
							
	$('#bottom_right_map').attr('src','http://maps.googleapis.com/maps/api/staticmap?center=' 
										+ business_address + 
										'&zoom=14&size=240x160&maptype=roadmap&markers=color:green%7C'
										+ business_address + '&sensor=false')	
	
	$('#bottom_right_map_link').attr('href','http://www.google.com/maps?q=' + business_address);
		
	/*//reservation needed
	var reserve = (activity.reserve_needed > 0 ? 'Reservation needed ' + activity.reserve_needed + ' day(s) in advance' : 'Reservation not needed');
	
	$('#bottom_right_reserve').text(reserve);	*/
	
	//what it's good for txt
	$('#bottom_right_reserve').text(activity.what);
	
	$('#bottom_right_expire').text(activity.expire);
									
	
}

