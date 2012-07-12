<?php

//form class for signup forms
class Form_signup extends Form {
	
	var $last;
	
	function __construct($pos) {
			$this->last = $pos-1;
			$next = $pos+1;
			
			($pos == 2 ? $onsubmit = 'onsubmit = "return validate()"' : $onsubmit='');
			echo "<form action='signup_set.php?pos=$next' name='signup_form' method='POST' $onsubmit>\n";
			
	}
	
	function back() {
			if($this->last==0) 
				$loc = '../signup';
			else
				$loc = 'signup_'.$this->last;
			
			($this->last==3 ? $id='back' : $id='');
			
			$back = "<a href='".$loc."' alt='Back to step ".$this->last."'><p class='back text' id='$id'>Back</p></a>\n";
			
			echo $back;
			
	}
	
	function next_button() {			
			$this->input('image','next','next_button',$GLOBALS['file_adj'] . 'images/signup/next_button.png');
	}
	
	function radio($name,$value) {
			if(!empty($_SESSION['signup']['package']) && $value==$_SESSION['signup']['package'])
				$checked='checked';
			else
				$checked='';
			
			echo "<input type='radio' name='$name' class='$name' value='$value' $checked />";
	}
				
				
			
}

//function to display how/what text blocks
//on homepage
function how_what($howorwhat) {
	
				global $text_num;
				global $text;
				
				$block = "<div class='how_what' id='%s'>\n<img src='" . $GLOBALS['file_adj'] . "images/home/%s.png' id='%s_img'/>\n";
							
				foreach($text[$howorwhat] as $key=>$value){
					
					$block .= "<p class='title text $text_num %s'>$key</p>\n <p class='text $text_num %s'>$value</p>\n";
					
					$text_num++;	
				}
				//insert preview img
				if($howorwhat=='how')
					$block .= "<a href='preview/member.php' alt='Preview Cityvate' title='Check out Cityvate'><img src='" . $GLOBALS['file_adj'] . "images/home/preview.png' id='preview'/></a>";

				$block .= "</div>\n";
							
				$block = str_replace('%s',$howorwhat,$block);
				
				echo $block;	
				
				$text_num=1;	

}

function quote_box($div_number,$text,$name,$city = 'SF') {
				
				$block = "<div class='quote_box' id='$div_number'>\n<p class='quote text'>\"$text\"</p>\n<p class='text quote_name'>$name, $city</p>\n</div>\n";
				
				echo $block;
				
}

//function to generate text boxes signup_2
function signup_boxes($title_array) {
		
		//declare global $form var to use from script
		global $form;
		
		//loop through array and form boxes
		if(is_array($title_array)){
			
				foreach($title_array as $key=>$value) {
						//eliminate spaces and slashes from $key
						//then convert $key to uppercase
						$id = str_replace(' ','',$key);
						$id = str_replace('/','',$id);
						$key=ucwords($key);
						$class = 'drop text';
						$lower = '';
						
						//if session var is not empty, fill box with val
						(!empty($_SESSION['signup'][$id]) ? $val = $_SESSION['signup'][$id] : $val='');
						
						//if empty and full name, give example
						if($key=='Full Name' && $val=='')
							$val='e.g. John Smith';
						
						//title caption for textbox
						echo "<p class='text box_title_close' id='$id'>".$key."</p>";
						
						//if it's password box, make password text
						if($key=='Password')
							$form->input('password','password2',$class);
						else{
							//case when username is not in email format
							if($key=='Username/email' && 
								(!empty($_SESSION['user']['email_fail']) || !empty($_SESSION['exists']))){
								$class .= ' red_back';
								$lower = ' red';
								$value = 'Please enter a valid email address.';
							}
							//case when username is taken
							if($lower == ' red' && !empty($_SESSION['exists']))
								$value = 'That username is already taken';
								
							$form->input('text',$id,$class,'',$val);
						}
						
						//lower title caption for textbox	
						echo "<p class='text box_title_lower $lower'>$value</p>";

						
				}
		}
		
}

//for signup_4 display
function signup_review($textarray) {
		
		$edit_cnt = 0;
		//loop through first dimension of array ("title" layer)
		foreach($textarray as $title=>$second) {
			if($title!='Personal Info')
				$edit_cnt += 1;
			//echo title portion of review
			echo "<div class='review_box'><p class='text review_box_text'>$title</p></div>";
			
			//loop through second dimension of array ("key to val")
			foreach($second as $key=>$value) {
				
				$block = "<p class='key text'>$key:</p>";
				$block .= "<p id='$key' class='value text'>$value</p>";
				
				echo $block;
			}
			
			//display "edit" button for each page
			echo "<a href='signup_$edit_cnt.php' alt='Edit $title information' class='text edit'>edit</a>";
			
		}
		
}

//date option function for signup_3 form
function signup_date_options($num_months) {
		
		//loop through and create options for num_months
		for($i=1;$i<=$num_months;$i++) {
			
			$month = date('F, Y',strtotime('+' . $i . ' months'));
			//if session var set, set to selected
			($_SESSION['signup']['start']==$month ? $select = 'selected' : $select='');
			
			echo '<option '.$select.'>'.$month.'</option>';
		}
}
