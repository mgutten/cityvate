// JavaScript Document
var activities = new Array();
var cur_slide = 1;
var activities_next = 2;

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
		
		//start sliding effect	
		var slide = setInterval(function(){
									sliding()
								}, 5000)
								
		$(window).focus(function() {
			
			if(!slide){
				slide = setInterval(function() {
									sliding()
								}, 5000);
			}
		});
		
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
		
        $('<img/>')[0].src = '/images/activities/mini/' + this.type.replace(/ /g,'_') + '.png';
        
    });
	
}

//function to fade out, change, and fade in quotes
//function quote_fade() {
//		$(".quote_box").animate({opacity:0},800);
//		
//		setTimeout('$("#first").html($("#second").html())',800);
//		setTimeout('$("#second").html($("#first").html())',800);
//		
//		$("#first").animate({opacity:1},800);
//		$("#second").animate({opacity:1},800);
//		
//}

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
function sliding() {
	var margin_first,margin_second,animation;
	var activity_name = activities[activities_next].type;
	var activity_save = activities[activities_next].save;
	
	if(cur_slide == 1){
		animation = '0px';
		margin_first = '570px';
		margin_second = '-485px';
	}
	else{
		animation = '285px';
		margin_second = '85px';
	}
	
	//animate first sliding container
	$('#activities_slide1').animate({'margin-left': animation},1200);

	if(cur_slide == 1){
		setTimeout(function() {
				//"reload" image back to beginning for continuous animation effect
				$('#activities_slide1').css('margin-left',margin_first);
				$('#activities_slide2').css('margin-left',margin_second);
				
				//change name of offscreen activity
				$('#activity_name1').html(activity_name)
				$('#activity_img1').attr('src','/images/activities/mini/' + activity_name.replace(/ /g,'_') + '.png')
				
				//if at end of activities slide, restart at first
				if(activities_next == (activities.length-1))
					activities_next = 0
				else
					activities_next++
				cur_slide++;
		},1300)
	}
	else{
		setTimeout(function() {
			
				$('#activities_slide2').css('margin-left',margin_second);
				
				//change name of offscreen activity
				$('#activity_name2').html(activity_name)
				$('#activity_img2').attr('src','/images/activities/mini/' + activity_name.replace(/ /g,'_') + '.png')
				
				//if at end of activities array, restart at beginning
				if(activities_next == (activities.length-1))
					activities_next = 0
				else
					activities_next++
				//change cur_slide to first
				cur_slide = 1;
		},1300)
	}
	
	
}

