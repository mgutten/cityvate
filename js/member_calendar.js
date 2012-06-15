// JavaScript Document

//var to be set onclick left and right arrows
//when changing month
var new_month_id;

//var set for use in dragging element to
//set the activity's name when element dragged
var dragging_name;
var dragging_aid;

//var set to select with draggable's $(this) and 
//interchange between the functions
//for draggable and droppable
//*fixes complication with selecting both
//draggable and droppable with $(this)*
var dragging_tag;

var date = new Date();
var year= date.getFullYear();


$(function(){
		$('#left_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2))-1;
			change_month(new_month_id);
	})
	
	$('#right_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2))+1;
			change_month(new_month_id);
	})

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
	
	//display right arrow if shown month is before current date
	var n = date.getMonth()+1;
	(n != new_month_id ? $('#right_arrow').css('display','block') : $('#right_arrow').css('display','none'))

}

function ready_fns() {
	
	
	$('p.activity_bar').draggable({
			containment: "#body_main",
			zIndex:5,
			revert: true,
			start: function() {
				dragging_name = $(this).text();
				dragging_tag = $(this);
				dragging_aid = $(this).attr('id');
			}
			
	});
	
	$(".droppable").droppable({
			hoverClass: "drophover",
			drop: function( event, ui ) {
				reserve_needed($(this))
				//alert(dragging_name + ' was dropped on '+$(this).text());
			}
		});
}

//check if a reservation is needed
function reserve_needed(dropping_tag) {

	//reserve_needed attr from db is stored
	//in ID of container div for each draggable
	//activity, so test against that
	//if ID == 0, no reservation needed
	var parent_id = dragging_tag.parent().attr('id');
	if(parent_id==0) {
		alert('No reservation needed');
	}
	else {
		//run check to see if the selected day is
		//less than the needed days to reserve
		//in advance
		//***add 1 so that we count today as 
		//one of the required days***
		if(parseInt(parent_id) <= (parseInt(dropping_tag.text())-parseInt($('.today').text())+1)){
			window.location = 'activity_top.php?date=' + dragging_name + '&aid=' + dragging_aid ;///////////////////////////////////////////////
		}
		else{
			alert(dragging_name + ' requires at least ' + parent_id + ' days');
		}
	}
}
	