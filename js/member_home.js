// JavaScript Document
var timeout=[];
var reset_list;

$(function(){
	
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
	
	
	//start image scrolling onload
	set_fade_effect();
	
	
	$('#left_arrow').click(function() {
		//extact numerical val for date from id and subtract one
		//(ie move to month before)
		change_month($('.activity_month').attr('id')[0]-1);
	})
	
});


function change_month(new_month) {
		
		$.ajax({
			url:'activity_top.php',
			type:'post',
			data: {month: new_month},
			success: function(data) {
					$('#body_top').html(data)
			}
		})
}

function set_fade_effect() {
	
		timeout[1] = setInterval(function(){bar_select($('.activity.selected').next());},4000)
	
}

function bar_select(tag) {
		
		if(reset_list == 1) {
			tag = $('.activity:first-child');
			reset_list = 0;
		}
		if(tag.next().length == 0 && timeout[1]!=null){
			reset_list = 1;
		}
		//change color of background for activity bar onclick
		$('.activity').removeClass('selected');
		tag.addClass('selected');
		
		//replace title of banner
		var header = tag.children('.activity_name').html();
		$('#picture_banner_text').html(header);
		
		//change url of image
		$('#picture_link').attr('href','activity.php?num='+tag.attr('id'));
		
		//change image
		image_fade(tag.children('.activity_name').html());
}
	

function image_fade(header) {
		
		//replace spaces with underscore to use id identifiers
		header = header.replace(/ /g,'_');
		//change source of hidden
		$('#picture_hidden').attr('src','../images/activities/'+header+'.jpg');
		//animate out top pic
		if($('#picture_shown').css('opacity')==1){
		$('#picture_shown').animate({opacity:0},500);
		}
		clearTimeout(timeout[0]);
		timeout[0] = setTimeout(function(){image_reset(header)},520);
		
			
}

//reset top image to new image so loop of fades can be achieved
function image_reset(header) {

$('#picture_shown').css('opacity',1);
$('#picture_shown').attr('src','../images/activities/'+header+'.jpg');

}

