// JavaScript Document

$(function() {
	/*
	//center the "center" classes
	$('.center').each(function() {
		var margin = -(parseInt($(this).css('width'),10)/2);
		$(this).css('margin-left',margin);
	})
	*/
	
	//populate #body_bottom with "Questions" text
	$('#body_bottom').addClass('text').html('Questions?  Contact us at support@cityvate.com');
	
	//dynamically change values of element's properties
	var total_width = parseInt($('#body').width(),10);
	var inner_width = parseInt($('#body_main').innerWidth(),10);
	var margins = Math.round((total_width-inner_width)/2);
	
	$('#body_main,#body_bottom').css('margin-left',margins);
	$('#body_bottom').css('width',inner_width);
	
	
	
})