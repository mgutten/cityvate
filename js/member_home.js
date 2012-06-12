// JavaScript Document
var timeout=[];
var reset_list;
//declare set month array
		var month_array = new Array();
			month_array[01]="January";
			month_array[02]="February";
			month_array[03]="March";
			month_array[04]="April";
			month_array[05]="May";
			month_array[06]="June";
			month_array[07]="July";
			month_array[08]="August";
			month_array[09]="September";
			month_array[10]="October";
			month_array[11]="November";
			month_array[12]="December";

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
		clearTimeout(timeout[1]);
		set_fade_effect();

		//extract numerical val for date from id and subtract one
		//(ie move to month before)
		change_month(parseInt($('.activity_month').attr('id').substring(0,2))-1);
	})
	$('#right_arrow').click(function() {
		clearTimeout(timeout[1]);
		set_fade_effect();

		//extract numerical val for date from id and subtract one
		//(ie move to month before)
		change_month(parseInt($('.activity_month').attr('id').substring(0,2))+1);
	})
	
});


function change_month(new_month) {
		
		$.ajax({
			url:'activity_top.php',
			dataType:'json',
			data: {month: new_month},
			success: function(data) {
					var a_array = eval(data)
					populate_html(a_array)	
					
				//reeval the click function on newly generated bars
					$('.activity').click(function() {
						clearTimeout(timeout[1])
						timeout[1] = null
						bar_select($(this));
					})				
			}
		})
		
}

//populate html with returned json data from ajax call
function populate_html(array) {
		
		//find new month from json array, add 0 if needed
		//month = month(str) month_num = month(int)
		var month;
		(array[0].length==1 ? month='0'+array[0] : month = array[0])
		month_num=parseInt(month)
		
				
		//change url of image
		$('#picture_link').attr('href','activity.php?num='+array[0]['aID']);
		
		//change first shown image
		$('#picture_shown').attr('src','../images/activities/'+month+'/'+array[1]['name'].replace(/ /g,'_')+'.jpg');
		
		//change banner title to first of array
		$("#picture_banner_text").text(array[1]['name'])
		
		//change month id so function can work next time
		$('.activity_month').attr('id',month+month_array[month_num])
		
		//change month name
		$('.activity_month').text(month_array[month_num]+"'s Activities")
		
		//show arrow if before current month
		var date = new Date();
		var n = date.getMonth()+1;
			(n != month ? $('#right_arrow').css('display','block') : $('#right_arrow').css('display','none'))
		
		//loop through array and display number of bars
		looping(array);
		
		
		
	
}

//loop through json array and create activity bars
function looping(array) {
	var i;
	var text='';
	var classy = '';
	var reserve;
	for(i=1;i<array.length;i++) {
		//if first bar, then give it selected class
		if(i==1) {
			classy = "activity text selected";
		}
		else {
			classy = 'text activity';
		}
		//if no reserve_date, create plus
		if(array[i]['reserve_date']==null){
			reserve = '<img src="../images/member/plus.png" title="Add to Calendar" class="activity_reserve plus"/>'
		}
		//else parse through the reserve_date and pull out month
		//and day, then compare vs month_array for month name
		else {
			 var month_temp = parseInt(array[i]['reserve_date'].substring(5,7));
			 month_temp = month_array[month_temp].substring(0,3);
			 var day_temp = array[i]['reserve_date'].substring(8,10);
			reserve = "<p class='activity_reserve'>"+month_temp+' '+day_temp+"</p>";
		}
		
		
		text = text+"<div class='"+classy+"' id='"+array[i]['aID']+"'><p class='activity_name'>"+array[i]['name']+"</p><p class='activity_type'>"+array[i]['type']+"</p><a href='calendar.php'>"+reserve+"</a></div>";
	}
	$('#top_right_activities').html(text);
}

function set_fade_effect() {
	
		timeout[1] = setInterval(function(){bar_select($('.activity.selected').next());},4000)
	
}

function bar_select(tag) {
	
		if($('.activity').length <= 1 ){
			return;
		}
		
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

