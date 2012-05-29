// JavaScript Document
$(function() {

//header dropdown animation	
$("#drop,#dropdown").hover(

	function() {
			$("#dropdown").stop().animate({height:'70px'},300);
	},
	function() {
			//if focused on username textbox,
			//don't animate div to closed
			if(!$('.username').is(':focus')){
					$("#dropdown").stop().animate({height:'0px'},300);
			}
})


//expand div when focus on username
//textbox
$('.username').focus(function() {
		$("#dropdown").stop().css({height:'70px'})
})

//clear boxes for username and 
//pw when clicked
$(".username").click(function() {
		if($(this).val()=='Username/email' || $(this).val()=='password'){
				$(this).val('');
		}
})


//end "ready" function
});