// JavaScript Document
var height=65;
var left;
var right;


$(function() {
	
	//determine height of main body element
	if($('.month_body_0').length > 0){
		
		$('.month_body_0').each(function() {
			left = parseInt($(this).css('height'));
			right = parseInt($(this).next('.month_body_1').css('height'));
			height += (left > right ? left : right);
			//add in the margin-top for left_body/right and 
			//also the month_back for each
			height += 55;
		})
	}
	else 
		height += 100;
	
	height += 60;
	
	$('#body_main').css('height',height);
	
	
})
		