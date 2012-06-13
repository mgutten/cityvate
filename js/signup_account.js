// Signup account js

$(document).ready(function() {
	
//signup form page clearing box onclick
	$('#input_fullname').click(function(){
		fullname_check()
		});
	if($('#input_fullname').val()=='e.g. John Smith'){
		$('#input_fullname').css('color','#555');
	}
	
	$('input[type=text],input[type=password]').focus(function(){
			$(this).css('background-color','#313131');
		});
	
})

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