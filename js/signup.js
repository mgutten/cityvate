// Signup js

//create inner, outer, and timeout vars for
//the fade_signup function
var inner = ["","types","how"];
var outer = ["","Types of Activities","How it Works"];
var timeout = [];

$(function() {
	
	fade_signup(1);
	fade_signup(2);
	
	
})


//function to fade out and in types/how section
function fade_signup(num) {
	var outer_temp = outer[num];
	
	$("#fade"+num).hover(		
		//set outer var to current html, fade out,
		//add class to move div, change html, then 
		//fade in
		function() {
			$(this).stop().animate({opacity:0},500);
			timeout[0] = setTimeout('$("#text'+num+'").addClass("types")',549);
			timeout[1] = setTimeout('$("#text'+num+'").html("<img src=\'../images/signup/'+inner[num]+'.png\'/>")',549);
			timeout[2] = setTimeout('$("#fade'+num+'").stop().animate({opacity:1},500)',550);
		},
		
		//if onmouseout text is still set to original,
		//fade back in without changing html,
		//else reverse actions done above
		function() {
			if($("#text"+num).html() == outer_temp){
				for(i=0;i<3;i++){
					clearTimeout(timeout[i]);
				}
			$(this).stop().animate({opacity:1},500);
			}
			else {
				$(this).stop().animate({opacity:0},500);
				setTimeout('$("#text'+num+'").removeClass("types")',549);
				setTimeout('$("#text'+num+'").html("'+outer_temp+'")',549);
				setTimeout('$("#fade'+num+'").stop().animate({opacity:1},500)',550);
			}
		}
		)

}
