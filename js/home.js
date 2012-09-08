// JavaScript Document
var activities = new Array();
var cur_slide = 2;
var activities_cur = 1;
var running = false;

$(function() {
		
		//delay "fade" on mouseover so do not
		//accidentally trigger onload
		setTimeout('delay_fade("what")',1500);
		setTimeout('delay_fade("how")',1500);
		
		//"fade" onclick
		click_fade('what');
		click_fade('how');
		
		$("#preview").hover(
			function() {
				$(this).css('opacity','.85')
			},
			function() {
				$(this).css('opacity','1');
				
			})
		
		$('#prev_arrow').click(function() {
			
			if(running)
				return;
			clearInterval(slide);
			sliding(-1);
			
			setTimeout(function() {
					
					slide = setInterval(function(){
									sliding(1)
								}, 3000)
			}, 1200);
			
		});
		
		$('#next_arrow').click(function() {
			
			if(running)
				return;			
			clearInterval(slide);
			sliding(1);
			
			setTimeout(function() {
					
					slide = setInterval(function(){
									sliding(1)
								}, 3000)
			}, 1200);
			
		});
			
		
		//start sliding effect
		var slide = setInterval(function(){
									sliding(1)
								}, 3000)
										
		
		//restart sliding when refocus on page					
		$(window).focus(function() {
			
			if(!slide){
				slide = setInterval(function() {
									sliding(1)
								}, 3000);
			}
		});
		
		//stop sliding when change tabs in browser
		$(window).blur(function() {
			clearInterval(slide);
			slide = 0;
		});
				
		//preload mini images for sliding effect
		preload(activities)
	
		//set interval for fading and changing quotes
		//var s = setInterval('quote_fade()',12000);
					
});

//function to preload all of the images for smooth fade effect
function preload(arrayOfImages) {
    $(arrayOfImages).each(function(){
		
        $('<img/>')[0].src = '/images/activities/mini/' + this.replace(/ /g,'_') + '.png';
        
    });
	
}


//function to trigger "fade" on click
function click_fade(div) {
	
		$("#"+div).one("click",
			function() {
				fade(div)
		})
}

//function to trigger "fade" on mouseover
function delay_fade(div) {	

		$('#'+div).one('mouseover',
			function(){
				fade(div)
			}
		);
}

//startup animation to fade out what/how img
//and fade in text below		
function fade(div) {
			
			
			//fade out img and set display to none
			$('#'+div+'_img').animate({opacity: 0},700,function(){ $(this).css('display','none')});
			$('#'+div+'_box').delay(800).animate({opacity:1},700);
			
			//loop through text classes and fade in one by one			
			for(i=1;i<4;i++){
				$('.'+i+'.'+div).delay(200+(i*600)).animate({opacity: 1},700);
			}
			
			if(div=='how'){
				setTimeout('$("#preview").css("display","block")',2500)
				$("#preview").delay(2501).animate({opacity:1},700)
			}
			
			
}

//sliding effect for next month's activities
function sliding(prevOrNext) {
	/*value of prevOrNext = +1 or -1
		prev = -1
		next = +1
	
	 diagram of sample movement
		[ 1 ] [ 2 ] [ 3 ]
	**click next so slide left**
		[ 2 ] [ 3 ] [ 1 ]
	*/
	//inner divs that will actually slide
	var first_div = $('#activities_slide1');
	var second_div = $('#activities_slide2');
	var third_div = $('#activities_slide3');
	//width of inner divs
	var div_width = 200;
	//width of window div
	var window_div_width = 370;	
	
	//separation of inner divs to allow for centering of center div/hiding of
	//outer divs
	var inner_separation = (window_div_width - div_width)/2;
	
	var margin_first = null;
	var margin_second = second_div.css('margin-left');
	var margin_third = third_div.css('margin-left');
	
	var animation = parseInt(first_div.css('margin-left'),10) + (-prevOrNext * (inner_separation + div_width));
	var offscreen_div;
	
	//set var to determine if fn is running
	running = true;
	
	/*cur_slide = middle slide*/
	if(cur_slide == 1){
		if(prevOrNext > 0){
			margin_third = inner_separation + 'px';
			offscreen_div = third_div;
		}
		else{
			margin_second = ((2 * div_width) + window_div_width) * -1 + 'px';
			margin_third = (div_width + window_div_width - inner_separation) * -1 + 'px';
			offscreen_div = second_div;
		}
	}
	else if(cur_slide == 2){
		if(prevOrNext > 0){
			margin_first = div_width + window_div_width + inner_separation + 'px';
			margin_second = ((2 * div_width) + window_div_width) * -1 + 'px';
			margin_third = (div_width + window_div_width - inner_separation) * -1 + 'px';
			offscreen_div = first_div;
		}
		else{
			margin_third = ((2 * div_width) + window_div_width) * -1 + 'px';
			offscreen_div = third_div;
		}
	}
	else{
		if(prevOrNext > 0){
			margin_second = inner_separation + 'px';
			margin_third = ((2 * div_width) + window_div_width) * -1 + 'px';
			offscreen_div = second_div;
		}
		else{
			//return to starting values
			margin_first = inner_separation + 'px';
			margin_second = inner_separation + 'px';
			margin_third = inner_separation + 'px';
			offscreen_div = first_div;
			
		}
	}
	//animate first sliding container
	first_div.animate({'margin-left': animation + 'px'},1200);
	
	//reset container divs to allow for next slide
	setTimeout(function() {
			
			//change margins to allow for next slide
			if(margin_first !== null){
				first_div.css('margin-left',margin_first);
			}
			second_div.css('margin-left',margin_second);
			third_div.css('margin-left',margin_third);
			
			//change info for inner divs (CUSTOMIZE)
			new_activity(prevOrNext,offscreen_div);	

			//determine new cur_slide
			//if slide is in position 2,3,1 and next slide would be 4
			if((cur_slide + (prevOrNext)) > 3)
				cur_slide =  1;
			//if slide is in position 3,1,2 and next slide would be 0
			else if((cur_slide + (prevOrNext)) < 1)
				cur_slide = 3;
			else
				cur_slide += prevOrNext;
			
			//unset var to show fn is running
			running = false;
			
	}, 1300)
				
	
}

//populate new_activity for sliding conveyor animation
function new_activity(prevOrNext,offscreen_div){
	
			var new_activity;
			var activity_name;
			
			/* determine new activities location in activities array */
			//if in upper bounds of activities array and moving forwards
			if(activities_cur > (activities.length - 3) && prevOrNext > 0)
				new_activity = (activities_cur == activities.length - 2 ? 0 : 1);
			//if in the lower bounds of activities array and moving backwards
			else if(activities_cur < 2 && prevOrNext < 0){
				new_activity = (activities_cur == 1 ? activities.length - 1 : activities.length - 2);
			}
			else
				new_activity = activities_cur + (2 * prevOrNext);
				
			activity_name = activities[new_activity];
			
			//change activity name and img source
			offscreen_div.children('.activity_name').html(activity_name);	
			
			offscreen_div.children('.activity_img').attr('src','/images/activities/mini/' + activity_name.replace(/ /g,'_') + '.png');
			
			//find next activities pic to load
			if(activities_cur >= activities.length - 1 && prevOrNext > 0)
				activities_cur = 0;
			else if(activities_cur == 0 && prevOrNext < 0)
				activities_cur = activities.length - 1;
			else
				activities_cur = activities_cur + (prevOrNext);
			
}
