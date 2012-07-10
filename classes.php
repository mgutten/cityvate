<?php

//check login var to see whether logged in or not
//then include corresponding class file
if($login == 'in')
	include('html_display_in.php');
else
	include('html_display_out.php');
	

//declare common classes between logged in vs. logged out
class Head {
//class to create the head section of a page (including 
//stylesheets and scripts)
	
	var $script = array("jquery-1.6.min");
	var $style= array();
	var $title;
	var $file_adj;
	
		function __construct($login_status, $title, $default_js = 0) {
				session_start();
				date_default_timezone_set('America/Los_Angeles');

				
				//if login status "in" then check to see if session
				//vars are set.  If no, send to login page
				if($login_status=='in' && empty($_SESSION['user']))
						header('location:../login.php');

				
				//make paths relative to current file location
				$file_loc = $_SERVER['PHP_SELF'];
				$file_loc = explode('cityvate',$file_loc);
				$count = substr_count($file_loc[1],'/');
				for($i=1;$i<$count;$i++){
					$this->file_adj .= '../';
				}
				$GLOBALS['file_adj']=$this->file_adj;
				
				//set title for page and add title css and 
				//stylesheet to respective arrays
				$this->title = $title;
				
				
				//echo up to <head> of doc
				echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n<html xmlns='http://www.w3.org/1999/xhtml'>\n<head>\n";
			
				
				//define meta tags, adding title to keywords
				$meta_desc = 'Cityvate is your personal activity agent, helping you to find awesome local and motivating you to get out and try something new!';
				$meta_key = 'cityvate, fun life, exciting life, local activities, san francisco activities, '.$title;
					
				//if header is for logged out page, display
				//appropriate css stylesheet/script else logged
				//in stylesheet/script
				if($login_status == 'out') {
					array_unshift($this->style, 'header_out');
					//jquery file needs to remain above header js so push
					array_push($this->script, 'header_out');
				}
				else {
					array_unshift($this->style, 'header_in');
					//jquery file needs to remain above header js so push
					array_push($this->script, 'header_in');
				}
				
				//determine if need the titled js or not
				if($default_js==0){
					array_push($this->script,strtolower($title));
					array_push($this->style,strtolower($title));
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
								//replace spaces for underscores
								$value = str_replace(' ','_',$value);
								//test if this is an external link
								$pos = strpos($value,'http');
								
								if($pos===false)
									echo "<link rel='stylesheet' href='".$this->file_adj."css/".$value.".css' media='screen' />\n";
								else
									echo "<link rel='stylesheet' href='".$value.".css' media='screen' />\n";
								
								
						}
				}
				else {
						//replace spaces for underscores
						$style_array = str_replace(' ','_',$style_array);
						//test if this is an external link
						$pos = strpos($style_array,'http');
							if($pos===false)
									echo "<link rel='stylesheet' href='".$this->file_adj."css/".$style_array.".css' media='screen' />\n";
								else
									echo "<link rel='stylesheet' href='".$style_array.".css' media='screen' />\n";
				}
				
		}
		
		//create script links
		function script($script_array) {
				
				if (is_array($script_array)) {
					
						//loop through array and create script links
						foreach($script_array as $value) {
								
								//replace spaces for underscores
								$value = str_replace(' ','_',$value);
								//test if external link
								$pos = strpos($value,'http');
								
								if($pos===false)
									echo "<script type='text/javascript' src='".$this->file_adj."js/".$value.".js'></script>\n";
								else
									echo "<script type='text/javascript' src='".$value.".js'></script>\n";
															
						}
				}
				else {
						//test if external link
						$pos = strpos($script_array,'http');
						//replace spaces for underscores
						$script_array = str_replace(' ','_',$script_array);
						
						if($pos===false)
								echo "<script type='text/javascript' src='".$this->file_adj."js/".$script_array.".js'></script>\n";
						else
								echo" <script type='text/javascript' src='".$script_array.".js'></script>\n";
				}
			
		}
		
		
		function close() {
			
				//show title
				echo "<title>Cityvate | $this->title</title>\n";
				
				//close <head> section
				echo "</head>";
				
		}
	
}


class Header {
	
	var $links=array('Home' => 'home.php');
	var $drop=array();
	
	function __construct($login_status) {
	
			//set file_adj var from Head class
			$file_adj = $GLOBALS['file_adj'];
			
			//if logged out, add to array of header links
			if($login_status == 'out') {
					$this->links['Signup'] = 'signup.php';
					$this->links['About'] = 'about.php';
					$this->drop['Login']='login.php';
			}
			
			//if logged in, change dropdown variable
			else {
					$this->drop['My Account'] = 'account.php';
					$this->links['Contact'] = 'contact.php';
					$this->links['Home'] = 'member.php';
			}
					
			//display components of header as well as background img
			echo "<body>\n<img src='".$file_adj."images/city_back.jpg' class='bg'/><div id='header_back'></div><div id='body'>\n<div id='header_bar'>";
			
			//create links
			$this->link();
			
			//display logo
			echo "<a href='".$this->links['Home']."' alt='Home'><div id='logo'></div></a>";
			
			//display dropdown
			echo "<div id='dropdown_container'><div id='dropdown'>\n";
			//if not logged in, display form
			if(!empty($this->drop['Login'])) {
				$form = new Form($file_adj.'login_check.php','POST','return validate("")');
				$form->input('text','username','username','','Username/email');
				$form->input('password','password','username','','password');
				echo "<a href='".$file_adj."forgot.php' id='forgot'>Forgot?</p></a>";
				$form->input('image','login','',$file_adj.'images/login_button.png');
				$form->close();
			}
			else {
				$my_activities = "<a href='my_activities.php' alt='My Deals'><p class='my_account text'>My Deals</p></a>";
				$calendar = "<a href='calendar.php' alt='Calendar'><p class='my_account text'>Calendar</p></a>";
				$subscription = "<a href='subscription.php' alt='Subscription'><p class='my_account text'>Subscription</p></a>";
				$logout = "<a href='logout.php' alt='Logout'><p class='my_account text logout'>Logout</p></a>";
				echo $my_activities.$calendar.$subscription.$logout;
			}
			echo "</div></div>\n";
			
				

			
	//end __construct()
	}
	
	function link() {
			
			$file_adj = $GLOBALS['file_adj'];
			$file_adj2 = $file_adj;
			
			//create header links
			foreach($this->links as $key=>$value) {
									
					//base class for header links
					$class = 'header_link';
					
					//if home button, apply header_first class
					if($key == 'Home') 
							$class .= ' header_first';
					
					//if logged in (ie member.php homepage), no file adjust		
					if($value == 'member.php')
							$file_adj2 = '';
							
					echo "<a href='".$file_adj2.$value."' alt='$key'><div class='$class'>$key</div></a>\n";
					
					
			}
			
			//create dropdown menu and close header_bar div
			foreach($this->drop as $key=>$value) {
				//if dropdown is myaccount, do not adjust out of member folder
				
					echo "<a href='".$file_adj2.$value."'><div class='$class' id='drop'>".$key."<img src='".$file_adj."images/home/arrow.png' id='arrow'/></div></a>\n</div>\n";
					
					
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

class Alert {
	
	function __construct($name,$src) {
			$file_adj ='';
			if(!empty($GLOBALS['file_adj']))
				$file_adj = $GLOBALS['file_adj'];
			
			$darken="<div class='darken $name' onclick='$(\".$name\").toggle()'></div>";
			$img = "<img class='centered $name' src='".$file_adj."images/signup/$src.png' />";
			$x = "<div class='x $name' onclick='$(\".$name\").toggle()'></div>";
			echo $darken.$img.$x;
			
	}
	
}

//for alert popup with text
class Alert_w_txt {
	
	var $name;
	
	function __construct($name) {
		
		$this->name = $name;
			
		$darken = "<div class='darken $name' onclick='$(\".$name\").toggle()'></div>";
		$div = "<div class='$name text centered' id='alert_$name'>";
		
		echo $darken.$div;
	}
	
	function calendar_alert($title, $button_src){
		$class = $this->name;
		//adjust for being out of root directory (within /member)
		$file_adj ='';
		if(!empty($GLOBALS['file_adj']))
				$file_adj = $GLOBALS['file_adj'];
				
		$title = "<p class='alert_title'>$title</p>";
		$activity_name = "<p id='alert_activity_name' class='alert_toggle'></p>";
		$time = "<p id='alert_what_time' class=''>What time would you like your reservation?</p>";
		$form = new Form('calendar_ajax.php','POST');
		$select_hours = "<select id='alert_hours' name='alert_hours' class='alert_toggle'>
							<option>12</option>";
			for($i=1;$i<12;$i++) {
				$select_hours .= "<option>$i</option>";
			}
		$select_hours .= "</select>";
		$select_minutes = "<select id='alert_minutes' name='alert_minutes' class='alert_toggle'>
							<option>00</option>
							<option>15</option>
							<option>30</option>
							<option>45</option>
						</select>";
		$select_ampm = "<select id='alert_ampm' name='alert_ampm' class='alert_toggle'>
							<option>PM</option>
							<option>AM</option>
						</select>";
		$date = "<p id='alert_date' class='alert_toggle'></p>";
		
		
		
		echo $title.$activity_name.$time;
		$form = new Form('db_ajax.php','POST');
		echo $select_hours.$select_minutes.$select_ampm.$date;
		$form->input('image','alert_button','alert_toggle',$file_adj.$button_src);
		$form->input('hidden','aid','');
		$form->input('hidden','date','');
		$form->close();
	}
							
						
		
		
	
	//close divs of alert box and add x
	function close(){
		
		$x = "<div class='x $this->name' onclick='$(\".$this->name\").toggle()'></div>";
		
		echo "</div></div>".$x;
	}
}

class Head_signup extends Head {
	
	var $style = array('signup_form');
	
	
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
			
			$header = "<p class='text title' id='$id'>$title</p>";
			
			echo $background.$status.$header;
			
	}
	
	function close(){
		
			echo "</div>";
			
	}
	
}

	
