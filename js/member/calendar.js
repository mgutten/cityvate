// JavaScript Document

var timeout;

//var to be set onclick left and right arrows
//when changing month
var new_month_id;

//var set for use in dragging element to
//set the activity's name,aid, and expiration
//when element dragged
var dragging_name;
var dragging_aid;
var dragging_exp;

//var set to select with draggable's $(this) and 
//interchange between the functions
//for draggable and droppable
//*fixes complication with selecting both
//draggable and droppable with $(this)*
var dragging_tag;

var date = new Date();
var year= date.getFullYear();
//add 1 to get off array 0 then subtract 6 months
//to limit calendar to 6 months back = 5
var month_limit = date.getMonth()-5;

//store selected aid in var for use on cancelling
//reservation
var selected_aid;

//deal with popup to explain click and drag after 3 clicks
var no_clicked = 0;

$(function(){
	
	//fire functions when click left/right arrow for 
	//changing the month
	$('#left_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2),10)-1;
			change_month(new_month_id);
	})
	
	$('#right_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2),10)+1;
			change_month(new_month_id);
	})
	
	$('.reserve_required').toggle();
			
		
	ready_fns();
	
});

function change_month(new_month) {
	//deal with January-December switch
	if(new_month==0){
		new_month = new_month_id=12
		year = year-1;
	}

	if(new_month==13){
		new_month = new_month_id = 1
		year = year+1;
	}
	
		
	$.ajax({
		url: '/member/ajax_calls/calendar_ajax.php',
		type: 'POST',
		data: {month : new_month,
				year : year
				},
		success: function(data) {
			
			$('#calendar').html(data);
			change_month_name();
			
			ready_fns();
		}
	});
}

function change_month_name() {

	//find month name from month_array created
	//in header_in.js
	var month_name = month_array[new_month_id];
	
	//turn new_month_id into str to check length
	new_month_id += '';
	
	if(new_month_id.length < 2) {
		new_month_id = '0'+new_month_id;
	}
	
	//change month name and id to new values on page
	$('.activity_month').html(month_name);
	$('.activity_month').attr('id',new_month_id+month_name);
	//change year text
	$('.activity_year').html(year);
	
	
	//display right arrow if shown month is before current date
	//***add 2 to adjust off of array 0 and then allow movement
	//into next month
	var n = date.getMonth()+2;
	(n != new_month_id ? $('#right_arrow').css('display','block') : $('#right_arrow').css('display','none'))
	
	//deal with limit of 6 months back, if our limit is negative or 0,
	//adjust to month value by adding 12
	if(month_limit <= 0) {
		month_limit += 12;
	}
	//if we are 6 months or more back, don't let any more movement into past
	(new_month_id == month_limit ? $('#left_arrow').css('display','none') : $('#left_arrow').css('display','block'));

}

function ready_fns() {
	

	
	$('p.activity_bar,p.draggable').draggable({
			containment: "#body_main",
			zIndex:5,
			revert: true,
			start: function() {
			
				dragging_exp = $(this).next('span').attr('id');
				dragging_name = $(this).text().replace(/\(+[0-9]+[:]+[0-9]+ [pm|am]+\)+/g,'');
				dragging_tag = $(this);
				dragging_aid = parseInt($(this).attr('id'),10);
				ondrag();
				
			},
			stop: function() {
				$(".red,.red_expired").children('p.nono').html('');
				$('.red,.red_expired').removeClass('red red_expired');
				$('.not_transparent').removeClass('transparent');
				$('#red_explanation').css('background-color','none');
				$("#red_explanation").html('')
			}
			
	});
	
	$(".droppable").droppable({
			hoverClass: "drophover",
			over: function() {
				if($(this).is('.red')){
					timeout = setTimeout(function() {
						red_explanation('reserve')},
						2);
				}
				else if($(this).is('.red_expired')){
					timeout = setTimeout(function() {
						red_explanation('expire')},
						2);
				}
			},
			out: function() {
					$('#red_explanation').css('background-color','none');
					$("#red_explanation").html('')
			},
			drop: function( event, ui ) {
				reserve_needed($(this))
				
			}
		});
	
	//change color of calendar day onmouseover
	$(".calendar_light,.calendar_dark").hover(
		function(){
			$(this).addClass('drophover')
		},
		function() {
			$(this).removeClass('drophover')
		})
		
	//leave clicked day selected with highlighted color
	/*
	$('.calendar_light,.calendar_dark').click(function() {
		$('.selected').removeClass('selected');
		$(this).addClass('selected');
	});
	*/
	
	//cancel selected reservation	
	$("#cancel_reserve").click(function() {
		window.location='/member/ajax_calls/calendar_ajax.php?aID='+selected_aid;
	})
	
	
	
		
}

//populate the red explanation onhover
function red_explanation(type){
	//change background of explanation
	$('#red_explanation').css('background-color','#A00');
	
	if(type == 'reserve'){
			$('#red_explanation').html(dragging_name + ' requires at least ' + parseInt(dragging_tag.parent().attr('id'),10) + ' days advance reservation.');
	}
	else{
			$('#red_explanation').html('Your coupon for ' + dragging_name + ' is expired.');
	}
}

//determine which calendar days are no good for reservations
function ondrag() {

	var today =  parseInt($('.today').attr('id'),10);
	var last_day = parseInt($('.droppable').last().attr('id'),10);
	var min_reserve = parseInt(dragging_tag.parent().attr('id'),10)+today;
	var month = parseInt($('.activity_month').attr('id'),10);
	
	//if we are in the future, then loop through all days
	if(month>date.getMonth()+1){
		today = 0;
	}
	else if (month<date.getMonth()+1){
		today = 33;
		last_day = 32;
	}
	
	for(i=0;i<last_day+1;i++){
		
			//grey out days before today
			if(i<today) {
				$("#"+i).addClass('transparent')
			}
			
			//if day is before min required reserve time
			else if(i < (min_reserve)){
				$("#"+i).addClass('red');
				$("#"+i).children('p.nono').html('reserve min');
			}
			
			//if viewed month is greater than expire month or if day is 
			//greater than expire day
			else if((month==parseInt(dragging_exp.substr(0,2),10) && i > parseInt(dragging_exp.substr(2,4),10)) 
						|| month>parseInt(dragging_exp.substr(0,2),10)){
				$("#"+i).addClass('red_expired');
				$("#"+i).children('p.nono').html('expired');
						}
			
	}
		
}

//check if a reservation is needed
function reserve_needed(dropping_tag) {

	//reserve_needed attr from db is stored
	//in ID of container div for each draggable
	//activity, so test against that
	//if ID == 0, no reservation needed
	var parent_id = parseInt(dragging_tag.parent().attr('id'),10);
	//create date formatted mo/dd/yyyy
	var date_conc = $('.activity_month').attr('id').substring(0,2)+'/'+parseInt(dropping_tag.text(),10)+'/'+year;
	var date_formatted = year +'-'+ $('.activity_month').attr('id').substring(0,2) +'-'+ parseInt(dropping_tag.text(),10);
	if(dropping_tag.is('.red') || dropping_tag.is('.red_expired')){
		return;
	}
	
	
	//case when no reservation required
	if(parent_id==0) {
			//toggle writing within alert box
			$('.alert_toggle').toggle();
			
			$('#input_aid').val(dragging_aid);
			$('#input_date').val(date_formatted);
			$('.alert_title').html('No Reservation Required');
			$('#alert_what_time').html('At what time?');
			$('#alert_activity_name').html(dragging_name);
			$('#alert_date').html(date_conc)
			$('#input_alert_button').attr('src','../images/calendar/plan_button.png');
			//toggle alert box
			$('.reserve_required').toggle();
	}
	//case when reservation required
	else {
		
		var today = $('.today').attr('id');
		//if today is not defined, then we are in future
		//so set today to earliest day possible ie 0
			if(today == null)
				today = 0;
		//run check to see if the selected day is
		//less than the needed days to reserve
		//in advance
		//***add 1 if we count today as 
		//one of the required days***
		
			
			//toggle writing within alert box
				$('.alert_toggle').toggle();
			
			//populate hidden input values with dragging aID
			//and the date
			$('#input_aid').val(dragging_aid);
			$('#input_date').val(date_formatted);
			$('.alert_title').html('Reservation Required');
			$('#alert_activity_name').html(dragging_name);
			$('#alert_what_time').html('What time would you like your reservation?');
			//create date formatted mo/dd/yyyy
			$('#alert_date').html(date_conc)
			$('#input_alert_button').attr('src','../images/calendar/reserve_button.png');
			//toggle alert box
			$('.reserve_required').toggle();
		
		
	}
}	

//function to populate body_right with clicked activity's
//information
function activity_desc(activity_aid){
		
		//if day with no activity has been clicked 3 times in a row,
		//toggle help box
		if(no_clicked == 3){
			var counter=0;
			var color;
			
			timeout = setInterval(function() {
				counter += 1;
				if(counter == 8)
					clearInterval(timeout);
				if(counter % 2 == 0)
					color = 'transparent';
				else
					color = '#F00';
				$('.my_activities_title_clarify').css('background-color',color)
			},200)
			
			no_clicked = 0;
		}

		
		if(activity_aid==0)	{
			body_right_populate(0);
		}
		else{

			$.ajax({
				url: '/member/ajax_calls/db_ajax.php',
				type:'POST',
				data: {activity : activity_aid},
				success: function(data) {
					
					var activity_array = $.parseJSON(data);
					
					body_right_populate(activity_array);
					
				}
				
			})
		}
		
}

//populate lower half of body_right with returned json results
//from activity_desc 
function body_right_populate(array){
		//if a day was clicked without an activity
		if(array == 0) {
			$('#activity_title').html('No Activity');
			//clear the description box of old info
			$('#activity_desc_right').children().each(function() {
				$(this).html('');
			});
			$('#cancel_reserve').css('display','none');
		}
		else{
			//populate name category of popup on click of activity	
			$('#alert_activity_name').html(array.name);
			//change activity title
			$('#activity_title').html(array.name);
			
			//change reservation info
			var days = ' day';
			if(array.reserve_needed == 0) {
				$('#activity_reserve_needed').html('No');
				$('#activity_reserve_advance').html('N/A');
			}
			else{
				if(array.reserve_needed>1)
					days += 's';
					
				$('#activity_reserve_needed').html('Yes');
				$('#activity_reserve_advance').html(array.reserve_needed+days);
			}
			
			//change current reservation
			if(array.reserve_date != null){
				$('#activity_reserve_current').html(array.reserve_date);
			}
			else{
				$('#activity_reserve_current').html('None');
			}
			
			//change type
			$('#activity_type').html(array.type);
			
			//change done/not
			if(array.done == 0)
				$('#activity_done').html('No');
			else
				$('#activity_done').html('Yes');
			
				
			//change expires
			$('#activity_expire').html(array.expire);
			
			//change link to full activity page
			$('#activity_full_info').html('<a href="/member/activity/'+array.aID+'">here</a>');
			
			//change selected aid for use in cancel reservation button
			selected_aid = array.aID;
			
			//toggle cancel reservation button
			if(array.reserve_needed > 0 && array.done == 0){
				$('#cancel_reserve').attr('src','../images/calendar/cancel_reserve_button.png');
				$('#cancel_reserve').css('display','block');
			}
			else{
				if(array.reserve_date != null && array.done == 0){
					$('#cancel_reserve').attr('src','../images/calendar/remove_reserve_button.png');
					$('#cancel_reserve').css('display','block');
				}
				else
					$('#cancel_reserve').css('display','none');
			}
		}
		
}

