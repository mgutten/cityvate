// Signup account js for signup_2.php

$(document).ready(function() {
	
//signup form page clearing box onclick
	$('#input_fullname').click(function(){
		fullname_check()
		});
		
	if($('#input_fullname').val()=='e.g. John Smith'){
		$('#input_fullname').css('color','#555');
	}
	
	//toggle checkmark as person types for fullname
	$('#input_fullname,#input_usernameemail,#input_password2').keyup(function() {
		
		check_input($(this));
		
	})
	
	$('#input_fullname,#input_usernameemail,#input_password2').blur(function() {
		
		check_input($(this));
		
	})

	check_input('#input_fullname');
	check_input('#input_usernameemail');
	check_input('#input_password2');
	
})

function check_input(tag) {
		
		var pattern;
		var value = tag.val();
		var id = tag.attr('id');
		
		//if statement to set pattern for use later
		if(id == 'input_fullname')
		 	pattern = /^[a-z]+\s+[a-z]+(-)*[a-z]*$/i;
		else if(id == 'input_usernameemail')
			pattern = /^[a-zA-Z0-9_\-\.]+@[a-z]+\.[com|net|edu|org|biz]+$/i;
		//else it's password and needs no match but length requirement
		else{
			if(value.length > 5 && value.length < 13){
				tag.next('.checkmark').show();
				tag.nextAll('.x_img:first').hide();
			}
			else{
				tag.next('.checkmark').hide();
				tag.nextAll('.x_img:first').show();
			}
			
			return;
		}
			
		//if statement for match of fullname and username boxes to pattern
		if(value.match(pattern)){
			tag.next('.checkmark').show();
			tag.nextAll('.x_img:first').hide();
		}
		else{
			tag.next('.checkmark').hide();
			tag.nextAll('.x_img:first').show();
		}
}

//code for signup form "fullname" category
function fullname_check() {
		
		var val = $("#input_fullname").val();
		if(val=='e.g. John Smith'){
			$("#input_fullname").val('');
			$('#input_fullname').css('color','#fff');
		}

}

function validate() {	
	var val = document.getElementById('input_fullname').value
	val = $.trim(val);
	var matching = val.match(/ /g);
	var returning;
	
	if($("#input_fullname").val()=='e.g. John Smith' ||
		$("#input_fullname").val()=='' ||
		matching == null ||
		matching.length >1) {
			empty_box($("#input_fullname"))
			returning=0
		}
		
	if($("#input_usernameemail").val()=='') {
			empty_box($("#input_usernameemail"));
			returning=0

		}
	if($("#input_password2").val()=='' ||
			$("#input_password2").val().length < 6 ||
			$("#input_password2").val().length > 12) {
			empty_box($("#input_password2"));
			returning=0

	}
			
	if(returning==0){
			return false;
	}
	else {
			return true;
	}
		
}

function empty_box(str) {
	
	str.css('background-color','#950000');
		
}