// JavaScript Document
var timeout=[];
var reset_list;

var date = new Date();
//if within last 5 days of month, change to new activities for next month
var cur_month = date.getMonth()+1;
var cur_month_str = (cur_month < 10 ? '0' + cur_month : cur_month);
var start_month;

//convert date time to seconds, not milli
//subtract a day to make sure coupon is truly expired
var cur_time = Math.round(date.getTime()/1000)-(60*60*24);
var month_limit = (date.getMonth()-5 <= 0 ? date.getMonth()+7 : date.getMonth()-5);

//activities array
var a_array = new Array();
/* order of returned a_array from ajax call
[0] = done_activities array
[1] = number of pagination pages
[2] = subscription check, is it valid and are there new activities
[3]+ = individual activity information
*/

var selected_pag = 1;

var selected_pag_done = 1;

//var to determine if activities array is empty or not (0 = full, 1 = empty)
var empty = 0;

var image_array = new Array;

//array to be set on index.php with php values of images
var new_activities = new Array;
//counter to determine which image is shown next
var new_next = 1;


$(function(){
		
	//adjust for case when new_activities are available and php has already changed month name
	if(parseInt($('.activity_month').attr('id').substr(0,2),10) != cur_month)
		cur_month += 1;
	
	//set starting_month
	start_month = cur_month;
	
	//arrow click to change month
	$('#left_arrow').click(function() {
		
		if($(this).is('.grey_arrow'))
			return;
		
		arrow_click(-1);
		
	})
	
	$('#right_arrow').click(function() {
		
		if($(this).is('.grey_arrow'))
			return;
		
		arrow_click(1);
	})
	
	ready_functions();	

	//start image scrolling onload
	set_fade_effect();
	
	//pause image scrolling while browser is elsewhere
	$(window).focus(function() {
		if(!timeout[1]){
				set_fade_effect()
			}
	});
		
	$(window).blur(function() {
			clearInterval(timeout[1]);
			timeout[1] = 0;
	});
	

	
});

//all of the functions that need to be run on ready
//(and rerun for ajax calls)
function ready_functions() {
	

	//perform custom image fade onclick
	$('#top_right_activities').on('click','.activity',function() {
		reset_list = 0;
		bar_select($(this));
		
	})
	

	//remove finished activity from "done" list
	//on click of x button
	$(".activity_done").on('click','.activity_done_x',function() {
		remove_done($(this).attr("id"));
	})
	
	
	//pagination page clicks
	$('#pag_nums').on('click','.pag_nums',function() {
		
		var start = (parseInt($(this).text()) - 1,10) * 5 + 1;
		//run pagination function to determine which page was selected
		selected_pag = parseInt($(this).text(),10);
		
		change_month(cur_month,start);
		
		timeout[3] = setTimeout(function() {
							looping_act();
							
					},100);
					
		clearTimeout(timeout[1]);
		
		timeout[4] = setTimeout(function() {
							set_fade_effect();
							bar_select($('.activity').first());
					},120);
	})
	
	//pagination for finished activities
	$('.pag_nums').on('click','.pag_nums_done',function() {
		$('.pag_nums_selected_done').removeClass('pag_nums_selected_done');
		$(this).addClass('pag_nums_selected_done');
		
		//if a_array has not been set yet, set it
		if(a_array.length < 1){
			change_month(cur_month,1)
		}
		
		selected_pag_done = parseInt($(this).text(),10);
		
		//let ajax run completely before looping new text
		timeout[5] = setTimeout(function() {
						looping_done();
					},50);
		
	})
	
	//pause image scrolling onhover
	$('#body_top').on('mouseenter mouseleave','.activity,#picture',function(e){
			
		if(e.type == 'mouseenter'){
				clearTimeout(timeout[1]);
				clearTimeout(timeout[6]);
			}
		else{
				timeout[6] = setTimeout(function() {
								set_fade_effect();
					}, 520);
			
		}
	});
	
	//preload new images for smoother fade effect
	preload_images();
		
	
}

//function to preload all of the images for smooth fade effect
function preload(arrayOfImages) {
    $(arrayOfImages).each(function(){
        $('<img/>')[0].src = this;
        
    });
	
}

//create array of images, then run preload fn
function preload_images() {
	var img;
	var month = cur_month_str;
	//push img tags into array
		$('.activity_name').each(function() {
			
			img = '/images/activities/' + month + '/' + $(this).text().replace(/ /g,'_') + '.jpg';
			image_array.push(img);
		})
	preload(image_array);	
}
	
function arrow_click(val) {
		//reset new_next for new_activities array
		new_next = 1;
		
		//reset selected pag num to 1
		selected_pag = 1;
		selected_pag_done = 1;
		
		
		cur_month += val;
		
		//deal with january,dec switch
		if(cur_month <= 0)
			cur_month += 12
		else if(cur_month > 12)
			cur_month -= 12
			
		//extract numerical val for date from id and subtract one
		//(ie move to month before)
		change_month(cur_month, 1);
		
		//if there are no activities for this month, say so
		timeout[2] = setTimeout(function() {
			    if(a_array.length < 4)
				    empty = 1;
				else
					empty = 0;
					
					reset_list = 0;
			  		populate_html(a_array);
					looping_act();
					looping_done();
					preload_images();
					
					//reeval ready functions for newly generated html
					clearTimeout(timeout[1]);
					
					set_fade_effect();
					
					
		}, 100);
					
}

function remove_done(id) {
	//ajax call to remove activity
	//from done list and move back to
	//current list, then reload
	$.ajax({
		url:'/member/ajax_calls/db_ajax.php',
		type: 'POST',
		data: { aid : id},
		success: function() {
			window.location.reload(true);
		}
	})
}

function change_month(new_month, start_range) {
	//initiate loading cursor
	$("*").css('cursor','wait');
	
	//set str var of current month to new val
	cur_month_str = (cur_month < 10 ? '0' + cur_month : cur_month);
	
	//ajax call to change the month when
	//the arrows next to month name are clicked
	//then return data and populate html
		$.ajax({
			url:'/member/ajax_calls/db_ajax.php',
			dataType:'json',
			data: {month: new_month , start : start_range},
			success: function(data) {
				
					a_array = eval(data)
					
					//check if we are at the end of our month_limit
					if(new_month == month_limit){
						$('#left_arrow').addClass('grey_arrow');
						$('#left_arrow').css('background-image','url(/images/member/left_arrow_grey.png)');
					}
					else{
						$('#left_arrow').removeClass('grey_arrow');
						$('#left_arrow').css('background-image','url(/images/member/left_arrow.png)');
					}
					
					//cancel loading cursor
					$("*").css('cursor','auto');
					$("#left_arrow").css('cursor','pointer');
					$("#right_arrow").css('cursor','pointer');
					
			}
		})
		
}

//populate html with returned json data from ajax call
function populate_html(array) {
		
		//set 2 vars 1 to handle str (ie 07 not 7) and other to handle arrays (3 not 03)
		var month = cur_month_str;
		var month_num=cur_month;
				
		//change month id so function can work next time
		$('.activity_month').attr('id',month+month_array[month_num])
		
		//change month name
		$('.activity_month').text(month_array[month_num]+"'s Activities")
		
		//change the pic	
		change_picture();
		
		//show arrow if before current month
		if(start_month != month){
			$('#right_arrow').css('background-image','url(/images/member/right_arrow.png)')
			$('#right_arrow').removeClass('grey_arrow');
		}
		else{
			$('#right_arrow').css('background-image','url(/images/member/right_arrow_grey.png)');	
			$('#right_arrow').addClass('grey_arrow');
		}
}

function change_picture() {
		
		var month = cur_month_str;
		var month_num=cur_month;
		var src;
		
		//show "Finished Activities" section
		$('.activity_done_hide').show();
		
		
		//if no activities purchased for cur_month
		if(empty == 1) {
			
			clearTimeout(timeout[0]);
			$('.picture_toggle').hide();
			$('#picture_shown').stop().css('opacity',1);
			
			//if returned value from change_month shows that valid subscription and
			//no activities have been purchased yet, display new activities info
			if(a_array[2] === true){
				
				$('.activity_done_hide').hide();
				
				//change link to new activities page
				$('#picture_link').attr('href','/member/new');
				
				image_fade(new_activities[0]);
			}
			//else there are simple no activities for this month
			else{
				$('#picture_shown').attr('src','/images/activities/no_activities.png');
				//disable linking when clicking on picture
				$('#picture_link').attr('onclick','return false');
			}
			
		}
		else {
			
			//enable linking when clicking on picture
			$('#picture_link').attr('onclick','return true');
			
			$('.picture_toggle').show();
			
			//change url of image
			$('#picture_link').attr('href','/member/activity/'+a_array[3]['aID']);
					
			//change first shown image
			$('#picture_hidden').attr('src','/images/activities/'+month+'/'+a_array[3]['name'].replace(/ /g,'_').toLowerCase()+'.jpg');
			
			image_fade(a_array[3]['name']);
								
			//change banner title to first of array
			$("#picture_banner_text").text(a_array[3]['name'])
			
		}
}

//loop through json array and create activity bars
function looping_act() {

	var array = a_array;
	var i;
	var b = 0;
	var c = 0;
	var text='';
	var classy = '';
	var reserve;
	
	if(empty == 1) {
		
		if(a_array[2] === true){
			$('#top_right_activities').html(
					"<a href='/member/new'><img src='/images/member/new_activities_button.png' class='new_activity' /></a></div>\
						<div id='pag_nums'></div>"
					)
		}
		else{
			$('#top_right_activities').html(
						"<p class='text no_activity' id='no_activity'>There are no activities for this month.</p>"
						)
			$('#pag_nums').html('');
		}
	}
	else {

	//start at 3 because 0 is reserved for done and pag_num
	for(i=3;i<array.length;i++) {
		//if first bar, then give it selected class
		if(i==3) {
			classy = "activity text selected";
		}
		else {
			classy = 'text activity';
		}
			
		//if no reserve_date, create plus
		if(array[i]['reserve_date'] == null){
			reserve = '<a href="/member/calendar"><img src="/images/member/plus.png" title="Add to Calendar" class="activity_reserve plus"/></a>'
		}
		//else parse through the reserve_date and pull out month
		//and day, then compare vs month_array for month name
		else {
			//pull month from reserve_date
			var month_temp = parseInt(array[i]['reserve_date'].substring(5,7),10);
			//pull first 3 letters of month name
			month_temp = month_array[month_temp].substring(0,3);
			
			//pull day from reserve_date
			var day_temp = array[i]['reserve_date'].substring(8,10);
			reserve = "<a href='/member/calendar'><p class='activity_reserve' title='Change Reservation'>"+month_temp+' '+day_temp+"</p></a>";
		}

		//if it's expired, show no calendar link
		if(array[i]['expire'] < cur_time) {
			reserve = '<p class="activity_expired" title="Expired">exp</p>';
		}
		
		
		text += "<div class='"+classy+"' id='"+array[i]['aID']+"'>\
						<p class='activity_name'>"+array[i]['name']+"</p>\
						<p class='activity_type'>"+array[i]['type']+"</p>\
						"+reserve+"</div>";
		
				
		
	}
	
	$('#top_right_activities').html(text);
	
	//deal with display of pagination nums after arrow click
	if(array[1] > 0){
		
		text = "<p class='pag_nums_page text'>Page: </p>" ;
		
		for(i = 1; i <= array[1]; i++){
			text += "<p class='pag_nums text'>" + i + "</p>";
			if(i == array[1])
				continue;
			text += "<p class='pag_nums_separate'>|</p>";
		}
		
		$('#pag_nums').html(text);
	}
	
	
	pagination(selected_pag);
	}
}

//populate finished activities
function looping_done() {
	
	var done = a_array[0];
	
	//reset text var for 'done' array
	var text ='';
	
	if(done.length > 0){
		
		var starting_done = ((selected_pag_done - 1) * 3);
		var ending_done = (done.length - starting_done > 3 ? starting_done + 3 : done.length);
		//loop through and create 'finished activities' section
		for(b=starting_done; b<ending_done; b++){
			text = text+"<div class='activity_done text' >\
							<a href='/member/activity/"+done[b]['aID']+"' class='activity_link'>\
								<p class='activity_name'>"+done[b]['name']+"</p>\
								<p class='activity_type'>"+done[b]['type']+"</p></a>\
							<p class='activity_reserve activity_done_x' id='"+done[b]['aID']+"'>X</p>\
						</div>"
		}
	}
	else {
		text = "<p class='text no_activity' id='no_activity_done'>You haven't done any activities this month.</p>";
	}
	
	$('#activity_done_lower').html(text);
	
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
			

function set_fade_effect() {
		
		clearTimeout(timeout[1]);
		
		if(new_activities.length > 0 && 
			a_array.length < 4 &&
			a_array[2] === true){
				
				$('#picture_link').attr('href','/member/new');
				$('#picture_top_banner,#picture_banner_text').show();
				$('#picture_banner_text').html('New Activities Available');
				
				timeout[1] = setInterval(function(){
										
										new_activities_fade();
										
								},3500)
			}
		else{
				timeout[1] = setInterval(function(){
									if($('.activity').length <= 1){
										return;
									}
									bar_select($('.activity.selected').next('.activity'));
							},4000)
		}
						
	
}

function bar_select(tag) {

		if(reset_list == 1) {
			tag = $('.activity:first-child');
			reset_list = 0;
		}
		if(tag.next('.activity').length == 0){
			reset_list = 1;
		}
		//change color of background for activity bar onclick
		$('.activity').removeClass('selected');
		tag.addClass('selected');
		
		
		//replace title of banner
		var header = tag.children('.activity_name').html();
		$('#picture_banner_text').html(header);
		
		
		//change url of image to aID (id) of new selected bar
		$('#picture_link').attr('href','/member/activity/'+tag.attr('id'));
		
		//change image
		image_fade(header);
}
	

function image_fade(header) {
		
		//replace spaces with underscore to use id identifiers
		header = header.replace(/ /g,'_').toLowerCase();
		//find selected month from id of activity_month
		var month = $('.activity_month').attr('id').substr(0,2);
		
		//create smooth fade for case when no_activities image is shown
		if($('#picture_shown').attr('src') == '/images/activities/no_activities.png'){
			//change hidden pic to no_activities image
			$('#picture_hidden').attr('src',$('#picture_shown').attr('src'));
			//hide shown pic and change to activities pic
			$('#picture_shown').css('opacity',0);
			$('#picture_shown').attr('src','/images/activities/'+month+'/'+header+'.jpg')
			$('#picture_shown').animate({opacity:1},500);
			
		}
		else{
			
			//change source of hidden
			$('#picture_hidden').attr('src','/images/activities/'+month+'/'+header+'.jpg');
			$('#picture_hidden').css('display','block');
			
			//animate out top pic
			if($('#picture_shown').css('opacity') == 1){
				
				$('#picture_shown').animate({opacity:0},500);
			}
					
			
			clearTimeout(timeout[0]);
			
			timeout[0] = setTimeout(function(){
					image_reset(header,month)
					
				},550);
		}
		
			
}

//reset top image to new image so loop of fades can be achieved
function image_reset(header,month) {

	$('#picture_shown').attr('src','/images/activities/'+month+'/'+header+'.jpg');
	$('#picture_shown').css('opacity',1);	

}


function new_activities_fade() {
	
	image_fade(new_activities[new_next]);
	
	
	if(new_next == new_activities.length - 1)
		new_next = 0;
	else
		new_next += 1;
}

