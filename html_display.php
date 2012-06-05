<?php
class Head {
//class to create the head section of a page (including 
//stylesheets and scripts)
	
	var $script = array("jquery-1.6.min");
	var $style= array();
	var $title;
	
		function __construct($login_status, $title, $default_js = 0) {
				session_start();
				
				//set title for page and add title css and 
				//stylesheet to respective arrays
				$this->title = $title;
				
				//determine if need the titled js or not
				if($default_js==0){
					array_push($this->script,strtolower($title));
					array_push($this->style,strtolower($title));
				}
				
				//echo up to <head> of doc
				echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n<html xmlns='http://www.w3.org/1999/xhtml'>\n<head>\n";
			
				
				//define meta tags, adding title to keywords
				$meta_desc = 'Cityvate is your personal activity agent, helping you to find awesome local and motivating you to get out and try something new!';
				$meta_key = 'cityvate, fun life, exciting life, local activities, san francisco activities, '.$title;
					
				//if header is for logged out page, display
				//appropriate css stylesheet/script else logged
				//in stylesheet/script
				if($login_status == 'out') {
					array_push($this->style, 'header_out');
					array_push($this->script, 'header_out');
				}
				else {
					array_push($this->style, 'header_in');
					array_push($this->script, 'header_in');
				}
					
				//echo base <meta>, styles, scripts
				echo "<meta http-equiv='Content-Type' name='description' content='".$meta_desc."'  />\n<meta http-equiv='Content-Type' name='keywords' content='".$meta_key."'  />\n";
				$this->style($this->style);
				$this->script($this->script);
				
				
		}
		
		//create stylesheet links
		function style($style_array) {
				
				if (is_array($style_array)) {
					
						//loop through array and create stylesheet links
						foreach($style_array as $value) {
							
								echo "<link rel='stylesheet' href='css/".$value.".css' media='screen' />\n";
								
						}
				}
				else
						echo "<link rel='stylesheet' href='css/".$style_array.".css' media='screen' />\n";
				
		}
		
		//create script links
		function script($script_array) {
				
				if (is_array($script_array)) {
					
						//loop through array and create script links
						foreach($script_array as $value) {
							
								echo "<script type='text/javascript' src='js/".$value.".js'></script>\n";
								
						}
				}
				else
					echo "<script type='text/javascript' src='js/".$script_array.".js'></script>\n";
			
		}
		
		function close() {
			
				//show title
				echo "<title>Cityvate | $this->title</title>\n";
				
				//close <head> section
				echo "</head>";
				
		}
	
}

class Head_signup extends Head {
	
	var $style = array('signup_form');
	
	
}


class Header {
	
	var $links=array('Home' => 'home.php','About' => 'about.php');
	var $drop=array('Login' =>'login.php');
	
	function __construct($login_status) {
			
			//if logged out, add to array of header links
			if($login_status == 'out') 
					$this->links['Signup'] = 'signup.php';
			
			//if logged in, change dropdown variable
			else 
					$this->drop = array('My Account' => 'myaccount.php');
					
			//display components of header as well as background img
			echo "<body>\n<img src='images/city_back.jpg' class='bg'/><div id='header_back'></div><div id='body'>\n<div id='header_bar'>";
			
			//create links
			$this->link();
			
			//display logo
			echo "<a href='".$this->links['Home']."' alt='Home'><div id='logo'></div></a>";
			
			//display dropdown
			echo "<div id='dropdown'>\n";
			//if not logged in, display form
			if($this->drop['Login']) {
				$form = new Form('login_check.php','POST');
				$form->input('text','username','username','','Username/email');
				$form->input('password','pw','username','','password');
				echo "<a href='forgot.php' id='forgot'>Forgot?</p></a>";
				$form->input('image','login','','images/login_button.png');
				$form->close();
			}
			echo "</div>\n";
			
				

			
	//end __construct()
	}
	
	function link() {
			
			//create header links
			foreach($this->links as $key=>$value) {
					
					//base class for header links
					$class = 'header_link';
					
					if($key == 'Home') 
							$class .= ' header_first';
							
					echo "<a href='$value' alt='$key'><div class='$class'>$key</div></a>\n";
					
			}
			
			
			//create dropdown menu and close header_bar div
			foreach($this->drop as $key=>$value) {
				
					
					echo "<a href='$value'><div class='$class' id='drop'>$key <img src='images/home/arrow.png' id='arrow'/></div></a>\n</div>\n";
					
					
			}
	
	//end link()
	}
	
}


class Body {
	
	function __construct() {
		
		echo "<div id='body_main'>";
	
	//end __construct	
	}
	
	function __destruct() {
		
		//close body_main and body divs along with document
		//create body_bottom div which connects main body to
		//bottom of window
		echo "</div><div id='body_bottom'></div></div></body></html>";
	
	//end __destruct	
	}
	
	
}

class Body_signup extends Body {
	
	function background($title, $position) {
			
			//if at last page for signup,
			//load large background
			if($position==4) {
				$smallorbig = 'big';
				$id = 'review';
			}
			else{
				$id='';
				$smallorbig = 'small';
			}
				
			$background = "<div id='background_$smallorbig'>";
			$status = "<img src='images/signup/signup_$position.png' alt='Step $position of 4' class='position'/>";
			
			if($position==0)
				$status='';
			
			$header = "<p class='title text' id='$id'>$title</p>";
			
			echo $background.$status.$header;
			
	}
	
	function close(){
		
			echo "</div>";
			
	}
	
}
	

class Form {
	
	//create form tag
	function __construct($action, $method, $onsubmit='') {
		
			echo "<form action='$action' method='$method' onsubmit='$onsubmit'>\n";
			
	}
	
	//create custom input for form
	function input($type,$name,$class='',$src='',$value=''){
					
					if($type=='checkbox' && !empty($_SESSION['signup']['auto_renew']) && $_SESSION['signup']['auto_renew']=='Yes')
						$checked = 'checked';
					else
						$checked='';
			echo "<input type='$type' name='$name' id='input_$name' class='$class' src='$src' value='$value' $checked/>\n";
					
	}
	
	//create input with diff name/id
	function input_diff($type,$name,$id,$class='',$src='',$value=''){
					
			echo "<input type='$type' name='$name' id='input_$id' class='$class' src='$src' value='$value'/>\n";
					
	}
	
	function close() {
			echo "</form>";
	}
	
}

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
				$loc = '';
			else
				$loc = '_'.$this->last;
			
			($this->last==3 ? $id='back' : $id='');
			
			$back = "<a href='signup".$loc.".php' alt='Back to step ".$this->last."'><p class='back text' id='$id'>Back</p></a>\n";
			
			echo $back;
			
	}
	
	function next_button() {			
			$this->input('image','next','next_button','images/signup/next_button.png');
	}
	
	function radio($name,$value) {
			if(!empty($_SESSION['signup']['package']) && $value==$_SESSION['signup']['package'])
				$checked='checked';
			else
				$checked='';
			
			echo "<input type='radio' name='$name' class='$name' value='$value' $checked />";
	}
				
				
			
}

class Alert {
	
	function __construct($name,$src) {
		
			$darken="<div class='darken $name' onclick='$(\".$name\").toggle()'></div>";
			$img = "<img class='centered $name' src='images/signup/$src.png' />";
			$x = "<div class='x $name' onclick='$(\".$name\").toggle()'></div>";
			echo $darken.$img.$x;
			
	}
	
}

//class to determine basic date functions
class Date {
	
	function __construct() {

		date_default_timezone_set('America/Los_Angeles');
		
	}
	
	//return short/long of current month
	function this_month($fullshortnum){
			
			return date($this->fullorshort($fullshortnum));
			
	}
	
	//return short/long of nth month in future
	function nth_month($fullshortnum,$months_to_advance){
		
			return date($this->fullorshort($fullshortnum),strtotime("+$months_to_advance months"));
		
	}
	
	//determine shorthand or longhand
	function fullorshort($str) {
		
		if($str=='full')
			$type = 'F, Y';
		elseif($str=='short')
			$type= 'M, Y';
		else
			$type='m, Y';
		
		return $type;
	}
}


//function to display how/what text blocks
//on homepage
function how_what($howorwhat) {
	
				global $text_num;
				global $text;
				
				$block = "<div class='how_what' id='%s'>\n<img src='images/home/%s.png' id='%s_img'/>\n";
							
				foreach($text[$howorwhat] as $key=>$value){
					
					$block .= "<p class='title text $text_num %s'>$key</p>\n <p class='text $text_num %s'>$value</p>\n";
					
					$text_num++;	
				}
				//insert preview img
				if($howorwhat=='how')
					$block .= "<a href='preview/member.php' alt='Preview Cityvate' title='Check out Cityvate'><img src='images/home/preview.png' id='preview'/></a>";

				$block .= "</div>\n";
							
				$block = str_replace('%s',$howorwhat,$block);
				
				echo $block;	
				
				$text_num=1;	

}

function quote_box($div_number,$text,$name,$city = 'SF') {
				
				$block = "<div class='quote_box' id='$div_number'>\n<p class='quote text'>\"$text\"</p>\n<p class='text quote_name'>$name, $city</p>\n</div>\n";
				
				echo $block;
				
}

//function to generate neighborhood options during signup_1
function signup_options($neighborhood_array) {
	
		if(is_array($neighborhood_array)){
			
				foreach($neighborhood_array as $key) {
						//test to see if session is set then select it
						if(!empty($_SESSION['signup']['neighborhood']) && $key==$_SESSION['signup']['neighborhood'])
									$selected = "selected='selected'";
								else
									$selected = "";
						echo "<option name='$key' $selected>$key</option>";
						
				}
		}
		else {
			
				echo "<option name='$neighborhood_array'>$neighborhood_array</option>";
				
		}
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
						
						//if session var is not empty, fill box with val
						(!empty($_SESSION['signup'][$id]) ? $val = $_SESSION['signup'][$id] : $val='');
						
						//if empty and full name, give example
						if($key=='Full Name' && $val=='')
							$val='e.g. John Smith';
						
						//title caption for textbox
						echo "<p class='box_title text box_title_close' id='$id'>".$key."</p>";
						
						//if it's password box, make password text
						if($key=='Password')
							$form->input('password',$id,'drop text');
						else
							$form->input('text',$id,'drop text','',$val);
						
						//lower title caption for textbox	
						echo "<p class='text box_title_lower'>$value</p>";

						
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
		//instantiate date class
		$date = new Date();
		
		//loop through and create options for num_months
		for($i=1;$i<=$num_months;$i++) {
			
			$month = $date->nth_month('full',$i);
			//if session var set, set to selected
			($_SESSION['signup']['start']==$month ? $select = 'selected' : $select='');
			
			echo '<option '.$select.'>'.$month.'</option>';
		}
}

	
		
			
?>




			