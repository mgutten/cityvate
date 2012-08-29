// JavaScript Document

var old_qty;
var total_spent = 0;
var charities;

$(function() {

	//adjust height of body_left background to match height of body_left
	//also adjust margin-top of body_left to overlay body_left_background
	var body_left_height = parseInt($('#body_left').css('height'),10)+20;
	$('#body_left_back,#separator').css('height',body_left_height + 'px' );
	$('#bottom_right_container,#body_left').css('margin-top','-' + body_left_height + 'px');
	
	//onload if there are reserved checkboxes change the token balance to reflect selections
	$('.body_left_checkbox').each(function() {
		
		if($(this).prop('checked') == true)
			change_balance($(this).parent());
		
	});
	
	$('.leftover_button').hover(
	
		function(){

			var title;
			
			if($(this).attr("id") == 'refund')
				title = 'A cash refund of $' + (parseInt($('#token_balance').html(),10)*2.5).toFixed(2) + ' will be credited to your credit/debit card.'
			else
				title = $(this).attr('aTitle');
				
			var top = parseInt($(this).offset().top,10);
			var left = parseInt($(this).offset().left,10);
			
			$("#tooltip")
				.html(title)
				.css("top",(top + 40) + 'px')
				.css('left',(left - 20) + 'px')
				.stop()
				.show()
				.animate({'opacity':'1'},300);
		},
		function() {
			$('#tooltip')
				.stop()
				.animate({'opacity':'0'},300)
	});
		
		
	
	//trigger functions on checkbox change
	$('.body_left_checkbox').change(function() {
		
		toggle_class($(this));
		
	})
	
	//allows selection of activities by clicking on whole bar, not just checkbox
	$('.body_left_bar').click(function() {
		
		$('.body_left_bar_solo').removeClass('body_left_bar_solo');
		$(this).addClass('body_left_bar_solo');
		
		//since it's the parent of the tag we need(checkbox), adjust tag variable
		var aid = $(this).children('.body_left_checkbox').val();
		bottom_right_load(aid)
		
	})
	
	//prevent changing of background bar color onclick when background is selected
	$('.body_left_qty').click(function(e) {
		if($(this).parent().is('.body_left_bar_selected'))
			e.stopPropagation();
	})
	
	//store old qty in var when focus on qty box
	$('.body_left_qty').focus(function() {
		if($(this).val() == '')
			old_qty = null;
		else
			old_qty = $(this).val();
	})
	
	//change total amount when change qty
	$('.body_left_qty').change(function() {
		//limit max qty to 4
		if($(this).val() > 4)
			$(this).val(4)
			
		//limit input to positive integers
		if(isNaN($(this).val()) || $(this).val() < 0)
			$(this).val(1)
		
		//if this activity is selected and we changed the qty, run qty change fn	
		if($(this).parent().is('.body_left_bar_selected') && $(this).val() != old_qty){
			qty_change($(this));
		}
		
	})
	
	$('.body_left_qty').keyup(function() {
		
		//if parent is not selected and input is a positive integer
		//toggle activity to selected
		if(!$(this).parent().is('.body_left_bar_selected') && !isNaN($(this).val()) && $(this).val() != '') {
			$(this).parent().addClass('body_left_bar_selected');
			$(this).siblings('.body_left_checkbox').prop('checked',true);
		}
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
	
	$('.leftover_button').click(function() {
		
		$('#input_leftover').attr('value',$(this).attr('id'));
		$('#tooltip').hide();
		submit_form();
	})
		
	
})

//tag is checkbox_tag
function toggle_class(tag) {

	var aid = tag.attr('value');	
	tag = tag.parent();
	
	//if checkbox is now checked, make background of bar green
	if(tag.children('.body_left_checkbox').is(':checked')){
		tag.addClass('body_left_bar_selected');
		tag.children('.body_left_qty').val(1);
	}
	else
		tag.removeClass('body_left_bar_selected');
	
	
	//change balance of tokens
	change_balance(tag);
		
	bottom_right_load(aid);
		
}

//change the balance of tokens after an activity is chosen/unchosen
function change_balance(parent_tag) {
	
	var tag = parent_tag;
	var cost = (tag.children('.body_left_cost').html() == 'Free' ? 0 : parseInt(tag.children('.body_left_cost').html(),10));
	var balance = $('#token_balance').html();
	
	if(cost > 0){
		if(!tag.children('.body_left_checkbox').is(':checked')){
			total_spent += cost = cost * -1 * parseInt(tag.children('.body_left_qty').val());
			tag.children('.body_left_qty').val('');
		}
		else
			total_spent += cost = cost * parseInt(tag.children('.body_left_qty').val(),10);
	}
	
	//if negative value of tokens, make balance red
	if(balance - cost < 0)
		$('#top_left_balance').addClass('red')
	else
		$('#top_left_balance').removeClass('red')
		
	$('#token_balance').html(balance-cost)
	//$('#input_total_spent').val(total_spent);
}

//validate balance of tokens and display appropriate alert
function validate() {
	
	var tokens_left = $('#token_balance').html();
	
	$('#input_refund_amt').val(tokens_left)
	
	if(tokens_left < 0){
		$('.too_many').show();
		return false;
	}
	
	if(tokens_left > 0){
		$('#leftover_tokens').html(tokens_left);
		//$('#refund').attr('title','A cash refund of $' + (tokens_left*2.5).toFixed(2) + ' will be credited to your account')
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
										'&zoom=13&size=240x160&maptype=roadmap&markers=color:green%7C'
										+ business_address + '&sensor=false')	
	
	$('#bottom_right_map_link').attr('href','http://www.google.com/maps?q=' + business_address);
		
	/*//reservation needed
	var reserve = (activity.reserve_needed > 0 ? 'Reservation needed ' + activity.reserve_needed + ' day(s) in advance' : 'Reservation not needed');
	
	$('#bottom_right_reserve').text(reserve);	*/
	
	//what it's good for txt
	$('#bottom_right_reserve').text(activity.what);
	
	$('#bottom_right_expire').text(activity.expire);
									
	
}

//tag is qty box
function qty_change(tag) {
	
	//if qty of 0 entered, deselect this activity
	if(tag.val() == 0){
		tag.parent().removeClass('body_left_bar_selected');
		tag.parent().children('.body_left_checkbox').prop('checked',false);
		tag.val('');
	}
	
	var balance = parseInt($('#token_balance').html(),10);
	var cost = tag.parent().children('.body_left_cost').html();
	
	(old_qty == null ? old_qty = 0 : old_qty = old_qty);

	balance = balance + parseInt((old_qty * cost),10);
	
	
	balance = balance - parseInt((tag.val() * cost),10);
	
	//change hidden input of total spent
	total_spent += parseInt(tag.val() * cost,10) - parseInt((old_qty * cost),10);
	//$('#input_total_spent').val(total_spent);

	//if negative value of tokens, make balance red
	if(balance < 0)
		$('#top_left_balance').addClass('red')
	else
		$('#top_left_balance').removeClass('red')

	$('#token_balance').html(balance);	
	
}

//submit activities form to process.php with ajax
function submit_form(){
	
	var activities_list = new Array();
	var qty = new Array();
	var qty_temp
	
	//store checked checkboxes in array
	$('.body_left_checkbox').each(function() {
		
		if($(this).is(':checked')){
			
			//add id to activities list array
			activities_list.push($(this).val());
			//save qty to qty array
			qty_temp = ($(this).parent().children('.body_left_qty').length > 0 ? $(this).parent().children('.body_left_qty').val() : 'NULL');
			qty[parseInt($(this).parent().attr('id'),10)] = qty_temp;
		
			
		}
	})

	var leftover = $('#input_leftover').val();
	var refund_amt = $('#token_balance').html();
	
	//determine response alert statement
	var success_text;
	if(leftover == 'refund')
		success_text = 'A refund will be issued to your credit/debit card several days after the 1st.';
	else if(leftover == 'carryover')
		success_text = 'Your leftover tokens will be carried over to the next month.';
	else 		
		success_text = '100% of your refund will be split equally between ' + charities;

	
	
	
	//note conversion of activities_list and qty params to array
	$.ajax({
		type: 'POST',
		url: '/member/new/process',
		data: {'activities_list[]': activities_list,
				'qty[]': qty,
				leftover: leftover,
				refund_amt: refund_amt,
				total_spent: total_spent
				},
		
		success: function(data){
				$('.leftover').hide();
				$('#success_alert_refund').html(success_text);
				$('.success').show();
		}
	})
}
		
