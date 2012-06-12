// JavaScript Document
function validate() {
	if($("#input_username").val()=='' ||
		$("#input_password").val()==''){
			return false;
		}
	else{
		return true;
	}
}