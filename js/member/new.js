// JavaScript Document
var num_activities = 0;

$(function() {
	
	//adjust height of body_left background to match height of body_left
	//also adjust margin-top of body_left to overlay body_left_background
	var body_left_height = parseInt($('#body_left').css('height'),10);
	$('#body_left_back').css('height',body_left_height + 'px');
	$('#body_left').css('margin-top','-' + body_left_height + 'px');
	$('#bottom_right').css('margin-top','-' + (body_left_height - 119) + 'px');
	
	//set all checkboxes to unchecked on page load
	$('input[type=checkbox]').each(function() {
		$(this).attr('checked',false)
	})
	
	//change background bar's class on checkbox change
	$('.body_left_checkbox').change(function() {
		
		toggle_class($(this));
		
	})
	
	//allows selection of activities by clicking on whole bar, not just checkbox
	$('.body_left_bar').click(function() {
		//since it's the parent of the tag we need(checkbox), adjust tag variable
		var tag = $(this).children('.body_left_checkbox');
		
		//change checkbox value
		if(tag.is(':checked'))
			tag.attr('checked',false)		
		else
			tag.attr('checked',true)
			
		
		toggle_class($(this).children('.body_left_checkbox'));
		
	})
	
	//run ajax for activity description and cancel the selection of this activity
	$('.body_left_details').click(function() {
		return false;
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
	if(tag.is(':checked')){
		tag.parent().addClass('body_left_bar_selected');
		num_activities += 1;
	}
	else{
		tag.parent().removeClass('body_left_bar_selected');
		num_activities -= 1;
	}
	
	//change balance of tokens
	change_balance(tag);
	
	//change num of activities
	count_activities();
		
}

//change the balance of tokens after an activity is chosen/unchosen
function change_balance(tag) {
	
	var cost = (tag.parent().children('.body_left_cost').html() == 'Free' ? 0 : tag.parent().children('.body_left_cost').html());
	var balance = $('#token_balance').html();
	
	if(!tag.is(':checked'))
		cost = cost * -1;
	
	//if negative value of tokens, make balance red
	if(balance - cost < 0)
		$('#top_right_balance').addClass('red')
	else
		$('#top_right_balance').removeClass('red')
		
	$('#token_balance').html(balance-cost)	
	
}

//change the number of activities in top_right section
function count_activities() {
	
	$("#number_activities").html(num_activities);
	
}

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

