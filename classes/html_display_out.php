<?php


//form class for signup forms
class Form_signup extends Form {
	
	var $last;
	var $url;
	
	function __construct($pos) {
		
			global $url;
			$this->url = $url;
			
			$this->last = $pos-1;
			$next = $pos+1;
			
			($pos == 2 ? $onsubmit = 'onsubmit = "return validate()"' : $onsubmit='');
			echo "<form action='signup_set.php?pos=$next' name='signup_form' method='POST' $onsubmit>\n";
			
	}
	
	function back() {
			//if on first page of signup, go back to index
			if($this->last==0) 
				$loc = $this->url['signup'];
			//else back button should go to last step
			else
				$loc = 'step'.$this->last;
			
			($this->last==3 ? $id='back' : $id='');
			
			$back = "<a href='".$loc."' alt='Back to step ".$this->last."'><p class='back text' id='$id'>Back</p></a>\n";
			
			echo $back;
			
	}
	
	function next_button() {			
			echo $this->input(array('type'=>'image',
								'id'=>'next',
								'class'=>'next_button',
								'src'=>'/images/signup/next_button.png'));
	}
	
	function radio($name,$value,$checked) {
			
			echo "<input type='radio' name='$name' class='$name' value='$value' $checked />";
	}
	
	//radio button display for packages in step3 of signup
	function radio_package_display($package_array) {
		
			foreach($package_array as $val){
				
				echo "<div class='radios'>";
				
				$checked='';
				$class = '';
				
				if(!empty($_SESSION['signup']['package']) && $val==$_SESSION['signup']['package']){
					$checked='checked';
					$class = 'green';
				}
				
				$this->radio('package',$val,$checked);
				
				echo "<p class='package_title text $class'>" . ucwords($val) . "</p><p class='text package_cost $class'>($" . $this->package_cost[$val] . "/mo)</p>";
				
				echo "</div>";
			}
	}
				
				
				
			
}

//function to display how/what text blocks
//on homepage
function how_what($howorwhat) {
	
				global $text_num;
				global $text;
				global $url;
				
				$block = "<div class='how_what' id='%s'>\n<img src='/images/home/%s.png' id='%s_img'/>\n";
							
				foreach($text[$howorwhat] as $key=>$value){
					
					$block .= "<p class='title text how_what_text $text_num %s'>$key</p>\n <p class='text how_what_text $text_num %s'>$value</p>\n";
					
					$text_num++;	
				}
				//insert preview img
				if($howorwhat=='how')
					$block .= "<a href='" . $url['preview'] . "' alt='Preview Cityvate' title='Check out Cityvate'><img src='/images/home/preview.png' id='preview'/></a>";

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
							echo $form->input(array('type'=>'password',
													'id'=>'password2',
													'class'=>$class));
						else{

							//case when username has failed
							if($key=='Username/email' && (!empty($_SESSION['signup']['username_fail']) || !empty($_SESSION['signup']['email_fail']))){
									
								$class .= ' red_back';
								$lower = ' red';
								
								//case when username is taken
								if(!empty($_SESSION['signup']['username_fail']))
									$value = 'That username is already taken.';
								//case when username not in email format
								elseif(!empty($_SESSION['signup']['email_fail']))
									$value = $value = 'Please enter a valid email address.';
									
							}
							//case when username is taken
							if($lower == ' red' && !empty($_SESSION['exists']))
								$value = 'That username is already taken';
								
							echo $form->input(array('type'=>'text',
													'id'=>$id,
													'class'=>$class,
													'value'=>$val));
						}
						
						echo "<img src='/images/signup/checkmark.png' class='checkmark'/>";
						echo "<img src='/images/signup/x.png' class='x_img'/>";
						
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
			if($title != 'Personal Info')
				$edit_cnt += 1;
				
			//echo title portion of review
			echo "<div class='review_box'><p class='text review_box_text'>$title</p></div>";
			
			//loop through second dimension of array ("key to val")
			foreach($second as $key=>$value) {
				
				$class = 'value text';
				
				if($key == 'Total')
					$class .= ' total yellow';
				if($key == 'Package')
					$value = ucwords($value);
									
				$block = "<p class='key text'>$key:</p>";
				$block .= "<p id='$key' class='$class'>$value</p>";
				
				//add question mark for popup tooltip
				if($key == 'Start Date'){
					$tooltip = 'You will be able to choose activities immediately, but this is the date that your first activities will become active.'; 
					$block .= "<p class='text green tooltip_question' tooltip ='$tooltip'>?</p>";
				}
				elseif($key == 'End Date'){
					$tooltip = ($value == 'TBD' ? 'Your subscription will automatically renew at the beginning of each month.  You may cancel at any time.' 
								: 'After this date you will not receive new tokens, but you will still be able to access your account.  You can always renew your subscription.'); 
					$block .= "<p class='text green tooltip_question' tooltip ='$tooltip'>?</p>";
				}

				
				
				echo $block;
			}
			
			//display "edit" button for each page
			echo "<a href='step{$edit_cnt}' alt='Edit $title information' class='text edit'>edit</a>";
			
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

//display images and titles for step4 interests
function signup_interests($interests_list) {
	
		if(!empty($_SESSION['signup']['interests']))
			$saved_interests = json_decode($_SESSION['signup']['interests']);
		
		foreach($interests_list as $val) {
			
				//if val is stored in signup session, it has been chosen before,
				//so select it outright
				if(!empty($saved_interests) && in_array($val,$saved_interests)){
						$class_title = 'preference_title_up';
						$class_img = 'preference_img_down';
						$img_src = '_color';
				}
				else{
						$class_title = '';
						$class_img = '';
						$img_src = '';
				}
				
				echo "<div class='preference'>
							<p class='text preference_title preference_toggle $class_title'>"
								. $val . 
							"</p>
							<div class='preference_back preference_toggle'>
							</div>
							<img src='/images/signup/preferences/" 
								. strtolower(str_replace(' ','_',$val)) . $img_src . 
							".png' class='preference_img $class_img'/>
						</div>";
		}
}

