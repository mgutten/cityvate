// JavaScript Document
var timeout=[];
var reset_list;
var date = new Date();
var cur_month = date.getMonth()+1;
//convert date time to seconds, not milli
//subtract a day to make sure coupon is truly expired
var cur_time = Math.round(date.getTime()/1000)-(60*60*24);
var month_limit = (date.getMonth()-5 <= 0 ? date.getMonth()+7 : date.getMonth()-5);

//activities array
var a_array;

var selected_pag = 1;



$(function(){
	ready_functions();	
	
	//start image scrolling onload
	set_fade_effect();
	
});

//all of the functions that need to be run on ready
//(and rerun for ajax calls)
function ready_functions() {
	
	//perform custom image fade onclick
	$('.activity').click(function() {
		clearTimeout(timeout[1])
		timeout[1] = null
		bar_select($(this));
		
	})
	
	//pause image scrolling onhover
	$('.activity,#picture').hover(
	function() {
		clearTimeout(timeout[1]);
		timeout[1] = null
	},
	function() {
		set_fade_effect();
	})
		
	
	//remove finished activity from "done" list
	//on click of x button
	$(".activity_done_x").click(function() {
		remove_done($(this).attr("id"));
	})
	
	
	//pagination page clicks
	$('.pag_nums').click(function() {
				
		var start = (parseInt($(this).text()) - 1) * 5 + 1;
		//run pagination function to determine which page was selected
		selected_pag = parseInt($(this).text());
		
		change_month(cur_month,start);
		
		
	})
	
}
	
function arrow_click(val) {
		//reset selected pag num to 1
		selected_pag = 1;
	
		clearTimeout(timeout[1]);
		set_fade_effect();
		cur_month += val;
		
		//deal with january,dec switch
		if(cur_month <= 0)
			cur_month += 12
		else if(cur_month > 12)
			cur_month -= 12
			
		//extract numerical val for date from id and subtract one
		//(ie move to month before)
		change_month(cur_month, 1);
		
		
}

function remove_done(id) {
	//ajax call to remove activity
	//from done list and move back to
	//current list, then reload
	$.ajax({
		url:'db_ajax.php',
		type: 'POST',
		data: { aid : id},
		success: function() {
			window.location.reload(true);
		}
	})
}

function change_month(new_month, start_range) {
	//ajax call to change the month when
	//the arrows next to month name are clicked
	//then return data and populate html
		$.ajax({
			url:'db_ajax.php',
			dataType:'json',
			data: {month: new_month , start : start_range},
			success: function(data) {
				
					a_array = eval(data)
					
					//if there are no activities for this month, say so
					if(a_array.length < 3)
						no_activities();
					else
						populate_html(a_array)	
					
					//check if we are at the end of our month_limit
					if(new_month == month_limit)
						$('#left_arrow').css('display','none')
					else
						$('#left_arrow').css('display','block');
					
					
					//reeval ready functions for newly generated html
					ready_functions();	
					
							}
		})
		
}

//populate html with returned json data from ajax call
function populate_html(array) {
		
		//find new month from json array, add 0 if needed
		//month = month(str) month_num = month(int)
		var month;
		
		cur_month = cur_month + '';
		(cur_month.length==1 ? month='0'+cur_month : month = cur_month)
		month_num=parseInt(month)
		cur_month = parseInt(cur_month);
		
			
		//change url of image
		$('#picture_link').attr('href','activity.php?num='+array[2]['aID']);
		
		//change first shown image
		$('#picture_shown').attr('src','../images/activities/'+month+'/'+array[2]['name'].replace(/ /g,'_')+'.jpg');
		
		//change banner title to first of array
		$("#picture_banner_text").text(array[2]['name'])
		
		//change month id so function can work next time
		$('.activity_month').attr('id',month+month_array[month_num])
		
		//change month name
		$('.activity_month').text(month_array[month_num]+"'s Activities")
		
		//show arrow if before current month
		var date = new Date();
		var n = date.getMonth()+1;
			(n != month ? $('#right_arrow').css('display','block') : $('#right_arrow').css('display','none'))
		
		//loop through array and display number of bars
		looping();
		
			
}

//loop through json array and create activity bars
function looping() {
	
	var array = a_array;
	//create done array
	var done = array[0];
	var i;
	var b = 0;
	var c = 0;
	var text='';
	var classy = '';
	var reserve;
	
	//start at 2 because 0 is reserved for done and pag_num
	for(i=2;i<array.length;i++) {
		//if first bar, then give it selected class
		if(i==2) {
			classy = "activity text selected";
		}
		else {
			classy = 'text activity';
		}
		//if no reserve_date, create plus
		if(array[i]['reserve_date'] == null){
			reserve = '<a href="calendar.php"><img src="../images/member/plus.png" title="Add to Calendar" class="activity_reserve plus"/></a>'
		}
		//else parse through the reserve_date and pull out month
		//and day, then compare vs month_array for month name
		else {
			//pull month from reserve_date
			var month_temp = parseInt(array[i]['reserve_date'].substring(5,7));
			//pull first 3 letters of month name
			month_temp = month_array[month_temp].substring(0,3);
			//pull day from reserve_date
			var day_temp = array[i]['reserve_date'].substring(8,10);
			reserve = "<a href='calendar.php'><p class='activity_reserve'>"+month_temp+' '+day_temp+"</p></a>";
		}

		//if it's expired, show no calendar link
		if(array[i]['expire'] < cur_time) {
			reserve = '<p class="activity_expired" title="Expired">exp</p>';
		}
		
		
		text = text+"<div class='"+classy+"' id='"+array[i]['aID']+"'>\
						<p class='activity_name'>"+array[i]['name']+"</p>\
						<p class='activity_type'>"+array[i]['type']+"</p>\
						"+reserve+"</div>";
		
	}
	
	
	$('#top_right_activities').html(text);
	
	//reset text var for 'done' array
	text ='';
	
	if(done.length > 0){
		//loop through and create 'finished activities' section
		for(b=0; b<done.length; b++){
			text = text+"<div class='activity_done text' >\
							<a href='activity.php?num="+done[b]['aID']+"' class='activity_link'>\
								<p class='activity_name'>"+done[b]['name']+"</p>\
								<p class='activity_type'>"+done[b]['type']+"</p></a>\
							<p class='activity_reserve activity_done_x' id='"+done[b]['aID']+"'>X</p>\
						</div>"
		}
	}
	else {
		text = "<p class='text' id='no_activity_done'>You haven't done any activities this month.</p>";
	}
	
	$('#activity_done_lower').html(text);
	
	pagination(selected_pag);
}

function pagination(selected) {
	
	
	if(a_array[1] != 1) {
		
		var i;
		var text = "<p class='pag_nums text'>Page: </p>";
		
		
		for(i=1; i<=a_array[1]; i++){
			
			var classy = 'pag_nums text';
			//if this page is selected, give it that class
					if(i == selected)
						classy += ' pag_nums_selected';
					
					
					text += "<p class='" + classy + "'>" + i + "</p>";
					//if on last iteration, do not place a "|"
					if(i == a_array[1])
						continue;
					text += "<p class='pag_nums_separate'>|</p>";
				}
				
		$('#pag_nums').html(text);
		
	}
	else {
		$('#pag_nums').html('')
	}
}
			
			
			

//case when there are no activities for the selected month
function no_activities() {
	
	$('#top_right_activities').html(
					"<p class='text' id='no_activity_done'>There are no activities for this month.</p>"
					)
	$('#activity_done_lower').html(
					"<p class='text' id='no_activity_done'>You haven't done any activities this month.</p>"
					);
					
	//change month info
		//find new month from json array, add 0 if needed
		//month = month(str) month_num = month(int)
		var month;
		(cur_month<10 ? month='0'+cur_month : month = cur_month)
		var month_num=parseInt(month)
		
		
		//change month id so function can work next time
		$('.activity_month').attr('id',month+month_array[month_num])
		
		//change month name
		$('.activity_month').text(month_array[month_num]+"'s Activities")
					
}

function set_fade_effect() {
	
		timeout[1] = setInterval(function(){bar_select($('.activity.selected').next('.activity'));},4000)
	
}

function bar_select(tag) {
		
		if($('.activity').length <= 1 ){
			return;
		}
		
		if(reset_list == 1) {
			tag = $('.activity:first-child');
			reset_list = 0;
		}
		if(tag.next('.activity').length == 0 && timeout[1]!=null){
			reset_list = 1;
		}
		//change color of background for activity bar onclick
		$('.activity').removeClass('selected');
		tag.addClass('selected');
		
		//replace title of banner
		var header = tag.children('.activity_name').html();
		$('#picture_banner_text').html(header);
		
		//change url of image to aID (id) of new selected bar
		$('#picture_link').attr('href','activity.php?num='+tag.attr('id'));
		
		//change image
		image_fade(header);
}
	

function image_fade(header) {
		
		//replace spaces with underscore to use id identifiers
		header = header.replace(/ /g,'_');
		//find selected month from id of activity_month
		var month = $('.activity_month').attr('id').substr(0,2);
		//change source of hidden
		$('#picture_hidden').attr('src','../images/activities/'+month+'/'+header+'.jpg');
		//animate out top pic
		if($('#picture_shown').css('opacity')==1){
		$('#picture_shown').animate({opacity:0},500);
		}
		clearTimeout(timeout[0]);
		timeout[0] = setTimeout(function(){image_reset(header,month)},520);
		
			
}

//reset top image to new image so loop of fades can be achieved
function image_reset(header,month) {

$('#picture_shown').css('opacity',1);
$('#picture_shown').attr('src','../images/activities/'+month+'/'+header+'.jpg');

}

