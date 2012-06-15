// JavaScript Document
var new_month_id;
var dragging_name;


$(function(){
	
	ready_fns();
	
});

function change_month(new_month) {
	
	$.ajax({
		url: 'calendar_ajax.php',
		type: 'POST',
		data: {month : new_month},
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
	var date = new Date();
	var n = date.getMonth()+1;
	(n != new_month_id ? $('#right_arrow').css('display','block') : $('#right_arrow').css('display','none'))

}

function ready_fns() {
	
	$('#left_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2))-1;
			change_month(new_month_id);
	})
	
	$('#right_arrow').click(function() {
			new_month_id = parseInt($('.activity_month').attr('id').substring(0,2))+1;
			change_month(new_month_id);
	})
	
	$('p.activity_bar').draggable({
			containment: "#body_main",
			zIndex:5,
			start: function() {
				dragging_name = $(this).text();
			},
			stop: function() {
				$(this).css({position: 'relative',top:0,left:0})
			}
	});
	
	$(".droppable").droppable({
			hoverClass: "drophover",
			drop: function( event, ui ) {
				alert(dragging_name + ' was dropped on '+$(this).text());
			}
		});
}
	