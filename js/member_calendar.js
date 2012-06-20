// JavaScript Document

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


$(function(){
	
	//fire functions when click left/right arrow for 
	//changing the month
	$('#left_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2))-1;
			change_month(new_month_id);
	})
	
	$('#right_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2))+1;
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
		url: 'calendar_ajax.php',
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
	
	if(new_month_id.length <2) {
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
	
	
	$('p.activity_bar').draggable({
			containment: "#body_main",
			zIndex:5,
			revert: true,
			start: function() {
				dragging_exp = $(this).parent().children('span').attr('id');
				dragging_name = $(this).text();
				dragging_tag = $(this);
				dragging_aid = $(this).attr('id');
				ondrag();
				
			},
			stop: function() {
				$(".red").children('p.nono').html('');
				$('.red').removeClass('red');
			}
			
	});
	
	$(".droppable").droppable({
			hoverClass: "drophover",
			drop: function( event, ui ) {
				reserve_needed($(this))
				
			}
		});
	
	//change color of calendar day where activity is set
	$(".activity_desc").hover(
		function(){
			$(this).parent().addClass('drophover')
		},
		function() {
			$(this).parent().removeClass('drophover')
		})
	
	//cancel selected reservation	
	$("#cancel_reserve").click(function() {
		window.location='calendar_ajax.php?aID='+selected_aid;
	})
}

//determine which calendar days are no good for reservations
function ondrag() {
	var today =  parseInt($('.today').attr('id'));
	var last_day = parseInt($('.droppable').last().attr('id'));
	var min_reserve = parseInt(dragging_tag.parent().attr('id'))+today;
	var month = parseInt($('.activity_month').attr('id'));
	
	//if we are in the future, then loop through all days
	if(month>date.getMonth()+1){
		today = 0;
	}
	for(i=today;i<last_day+1;i++){
			//if day is before min required reserve time
			if(i < (min_reserve)){
				$("#"+i).addClass('red');
				$("#"+i).children('p.nono').html('reserve min');
			}
			
			//if viewed month is greater than expire month or if day is 
			//greater than expire day
			else if((month==parseInt(dragging_exp.substr(0,2)) && i > parseInt(dragging_exp.substr(2,4))) 
						|| month>parseInt(dragging_exp.substr(0,2))){
				$("#"+i).addClass('red');
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
	var parent_id = parseInt(dragging_tag.parent().attr('id'));
	//create date formatted mo/dd/yyyy
	var date_conc = $('.activity_month').attr('id').substring(0,2)+'/'+parseInt(dropping_tag.text())+'/'+year;
	var date_formatted = year +'-'+ $('.activity_month').attr('id').substring(0,2) +'-'+ parseInt(dropping_tag.text());
	if(dropping_tag.is('.red')){
		return;
	}
	
	if(parent_id==0) {
			//toggle writing within alert box if it is set to
			//display none due to case when reservation is
			//needed and minimum days not reached
			if($('#alert_activity_name').css('display')=='none'){
					$('.alert_toggle').toggle();
					$('#alert_what_time').css('margin-top','10px');
			}
			
			$('#input_aid').val(dragging_aid);
			$('#input_date').val(date_formatted);
			$('.alert_title').html('No Reservation Required');
			$('#alert_what_time').html('At what time?');
			$('#alert_activity_name').html(dragging_name);
			$('#alert_date').html(date_conc)
			//toggle alert box
			$('.reserve_required').toggle();
	}
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
		
		var diff_days = parseInt(dropping_tag.text())-parseInt(today);
		if(parseInt(parent_id) <= diff_days){
			
			//toggle writing within alert box if it is set to
			//display none due to case when reservation is
			//needed and minimum days not reached
			if($('#alert_activity_name').css('display')=='none'){
					$('.alert_toggle').toggle();
					$('#alert_what_time').css('margin-top','10px');
			}
			
			//populate hidden input values with dragging aID
			//and the date
			$('#input_aid').val(dragging_aid);
			$('#input_date').val(date_formatted);
			$('.alert_title').html('Reservation Required');
			$('#alert_activity_name').html(dragging_name);
			$('#alert_what_time').html('What time would you like your reservation?');
			//create date formatted mo/dd/yyyy
			$('#alert_date').html(date_conc)
			//toggle alert box
			$('.reserve_required').toggle();
		}
		else{
			var days;
			if(parent_id==1)
				days = 'day'
			else
				days = 'days';
			
			$('.darken,.centered,.x').toggle();
			if($('#alert_activity_name').css('display')=='block'){
				$('.alert_toggle').toggle();
			}
			
			$('.alert_title').html('Reservation Required');
			
			$('#alert_what_time').html(dragging_name+' requires at least '+parent_id+' '+days+' advance reservation.');
			$('#alert_what_time').css('margin-top','85px');
		}
	}
}

//function to populate body_right with clicked activity's
//information
function activity_desc(activity_aid,reserve_set){
	
		$.ajax({
			url: 'db_ajax.php',
			type:'POST',
			dataType:'json',
			data: {activity : activity_aid},
			success: function(data) {
				
				var activity_array = eval(data);
				body_right_populate(activity_array,reserve_set);
				
			}
			
		})
		
}

//populate lower half of body_right with returned json results
//from activity_desc ajax
function body_right_populate(array,reserve_set){
		//change acitivity title
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
		$('#activity_full_info').html('<a href="activity.php?num='+array.aID+'">here</a>');
		
		//change selected aid for use in cancel reservation button
		selected_aid = array.aID;
		
		//toggle cancel reservation button
		if(reserve_set == '1'){
			$('#cancel_reserve').css('display','block');
		}
		else{
			$('#cancel_reserve').css('display','none');
		}
		
}
	
//function to load ajax of body_right my activities from done - current
function change_done(status){
				
		$.ajax({
			url: 'calendar_ajax.php',
			type:'POST',
			data: {done : status},
			success: function(data) {
				
				$('#my_activities_container').html(data);
				ready_fns();
				
			}
			
		})
}
	