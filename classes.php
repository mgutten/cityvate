<?php

//check login var to see whether logged in or not
//then include corresponding class file
if($login == 'in')
	include($_SERVER['DOCUMENT_ROOT'] . '/classes/html_display_in.php');
else
	include($_SERVER['DOCUMENT_ROOT'] . '/classes/html_display_out.php');
	

//general class to hold info that must be available to all classes
class General {
	var $package_cost = array('budget'=>25,
								'basic'=>50,
								'premium'=>100);
}

class Preview {
	
	function __construct() {
		
		$_SESSION['user'] = $_SESSION['preview'];
		
	}
	
	function __destruct() {
		
		unset($_SESSION['user']);
	}
}

class Tooltip {
	
	var $tooltip_array;
	
	//tooltip_array expected to be in order of showing
	//and contain text for each tooltip as well as id
	//of item it applies to
	/*
	ex: $tooltip_array = array([0]=>array('#month,.day',
											'This is the tooltip text.',
											'#body_left_activity')));
	*/
	function create() {
		
		$tooltip_array = $this->tooltip_array;
		
		echo "<script type='text/javascript'>";
		
		for($i = 0; $i < count($tooltip_array); $i++){
			
			echo "tooltip[$i] = new Array('" . $tooltip_array[$i][0] . "', '" . $tooltip_array[$i][1] . "'";
			
			//if this tooltip requires interactive part from user
			//then display response id/class in tooltip array
			if(!empty($tooltip_array[$i][2]))
				echo ",'" . $tooltip_array[$i][2] . "'";
				
			echo ");";
			
		}
		
		echo "</script>";
		
		echo "<div class='darken tooltip' id='tooltip_darken'></div>";
	}
	
}

//declare common classes between logged in vs. logged out
class Head extends General {
//class to create the head section of a page (including 
//stylesheets and scripts)
	
	var $script = array("jquery-1.8.min");
	var $style= array();
	var $title;
	var $title_css;
	var $url;
	
		function __construct($title, $default_js = 0) {
				date_default_timezone_set('America/Los_Angeles');
				
				global $login; //defined in bootstrap
				global $url;  //defined in bootstrap
				
				//set url paths from boostrap.php
				$this->url = $url;
				

				//if login status "in" then check to see if session
				//vars are set.  If no, send to login page
				if($login=='in' && empty($_SESSION['user']) && empty($_SESSION['preview']))
						header('location:' . $this->url['login']);

						
				//set title for page
				$this->title = $title;
				
				//separate title into directory (ie Member Home => member/home)
				$title = explode(' ', strtolower($title));
				$last = end($title);
				
				//loop through and concatenate new title for scripts and css
				foreach($title as $val) {
					
					$this->title_css .= $val;
					
					if($val != $last)
						$this->title_css .= '/';
				}
				
				
				//echo up to <head> of doc
				echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n<html xmlns='http://www.w3.org/1999/xhtml'>\n<head>\n";
			
				
				//define meta tags, adding title to keywords
				$meta_desc = 'Cityvate is your personal activity agent, helping you to find awesome local and motivating you to get out and try something new!';
				$meta_key = 'cityvate, fun life, exciting life, local activities, san francisco activities, ' . $title;
					
				//if header is for logged out page, display
				//appropriate css stylesheet/script else logged
				//in stylesheet/script
				if($login == 'out') {
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
					array_push($this->script,$this->title_css);
					array_push($this->style,$this->title_css);
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
									echo "<link rel='stylesheet' href='/css/" . $value . ".css' media='screen' />\n";
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
									echo "<link rel='stylesheet' href='/css/" . $style_array . ".css' media='screen' />\n";
								else
									echo "<link rel='stylesheet' href='" . $style_array . ".css' media='screen' />\n";
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
									echo "<script type='text/javascript' src='/js/".$value.".js'></script>\n";
								else
									echo "<script type='text/javascript' src='" . $value . ".js'></script>\n";
															
						}
				}
				else {
						//test if external link
						$pos = strpos($script_array,'http');
						//replace spaces for underscores
						$script_array = str_replace(' ','_',$script_array);
						
						if($pos===false)
								echo "<script type='text/javascript' src='/js/".$script_array.".js'></script>\n";
						else
								echo" <script type='text/javascript' src='".$script_array.".js'></script>\n";
				}
			
		}
		
		
		function close() {
				
				//if in preview, add preview js and css
				if(!empty($_SESSION['preview'])){
					$this->script('preview');
					$this->style('preview');
				}
				
				//show title
				echo "<title>Cityvate | $this->title</title>\n";
				
				//close <head> section
				echo "</head>";
				
		}
	
}


class Header extends General{
	
	var $links=array();
	var $drop=array();
	var $url;
	
	function __construct() {
			
			global $login;
			global $url;
			global $preview; //defined in bootstrap; if true then in preview mode
			
			$this->url = $url;
			
			$this->links['Home'] = $this->url['home'];
			
			//if logged out, add to array of header links
			if($login == 'out' && empty($_SESSION['user']['uID'])) {
					$this->links['Signup'] = $this->url['signup'];
					$this->links['About'] = $this->url['about'];
					$this->drop['Login']= $this->url['login'];

			}
			
			//if logged in, change dropdown variable
			else {
					$this->links['My Activities'] = $this->url['member'];
					$this->drop['My Account'] = $this->url['account'];
					$this->links['Contact'] = $this->url['contact'];
					$this->links['Home'] = $this->url['home'];
			}
					
			//display components of header as well as background img
			echo "<body>\n<img src='/images/city_back.jpg' class='bg'/><div id='header_back'></div><div id='body'>\n<div id='header_bar'>";
			
			//create links
			$this->link();
			
			$id = 'logo';
			//if in preview mode, show preview logo
			if($preview !== false)
				$id = 'preview_logo';
			//display logo
			echo "<a href='".$this->links['Home']."' alt='Home'><div id='$id'></div></a>";
			
			//display dropdown
			
			//if not logged in, display form
			if(!empty($this->drop['Login'])) {
				
					
				echo "<div id='dropdown_container'><div id='dropdown'>\n";
				
				$form = new Form(array('action'=>'/login/login_authenticate.php',
										'method'=>'POST',
										'onsubmit'=>'return validate("login_top")',
										'id'=>'login_top'));
				echo $form->input(array('type'=>'text',
										'id'=>'username',
										'class'=>'username',
										'value'=>'Username/email'));
				echo $form->input(array('type'=>'password',
										'id'=>'password',
										'class'=>'username',
										'value'=>'password'));
				echo "<a href='" . $this->url['forgot'] . "' id='forgot'>Forgot?</p></a>";
				echo $form->input(array('type'=>'image',
										'id'=>'login',
										'src'=>'/images/login_button.png'));
				$form->close();
			}
			else {
				echo "<div id='dropdown_container_activities'><div id='dropdown_activities' class='dropdown'>";
						$my_activities = "<a href='" . $this->url['member'] . "' alt='My Activities'><p class='my_account text'>Current Activities</p></a>";
						$calendar = "<a href='" . $this->url['calendar'] . "' alt='Calendar'><p class='my_account text'>Calendar</p></a>";
						$new_activities = "<a href='" . $this->url['new'] . "' alt='New Activities'><p class='my_account text logout'>New Activities</p></a>";
				echo $my_activities.$calendar;
				
				//check to see if new activities should be displayed
				$user = new User();	
				if($user->check_subscription() == true ||
						$_SESSION['user']['tokens_balance'] > 0)
								echo $new_activities;
							
				echo "</div></div>";
				
				echo "<div id='dropdown_container'><div id='dropdown' class='dropdown_account dropdown'>\n";
				
				$account_info = "<a href='" . $this->url['account'] . "' alt='My Account'><p class='my_account text'>Account Info</p></a>";
				//$calendar = "<a href='" . $this->url['calendar'] . "' alt='My calendar'><p class='my_account text'>Calendar</p></a>";
				$interests = "<a href='" . $this->url['interests'] . "' alt='Interests'><p class='my_account text'>Interests</p></a>";
				$subscription = "<a href='" . $this->url['subscription'] . "' alt='Subscription'><p class='my_account text'>Subscription</p></a>";
				$logout = "<a href='" . $this->url['logout'] . "' alt='Logout'><p class='my_account text logout'>Logout</p></a>";
				echo $account_info.$subscription.$interests.$logout;
			}
			echo "</div></div>\n";
						
	//end __construct()
	}
	
	function link() {
			
			//display username if logged in
			
			if(!empty($_SESSION['user']['uID']))
				echo "<p class='text green header_username'>" . $_SESSION['user']['username'] . "</p>";
			else
				echo "<p class='text green header_username'></p>";
			
			//create header links
			foreach($this->links as $key=>$value) {
									
					//base class for header links
					$class = 'header_link';
					
					//if home button, apply header_first class
					if($key == 'Home') 
							$class .= ' header_first';
					if($key == 'My Activities'){
						echo "<a href='". $value . "'><div class='$class' id='drop_activities'>" . $key . "<img src='/images/home/arrow.png' id='arrow'/></div></a>\n";
						continue;
					}
					
												
					echo "<a href='" . $value . "' alt='$key'><div class='$class'>" . $key . "</div></a>\n";
					
					
			}
			
			//create dropdown menu and close header_bar div
			foreach($this->drop as $key=>$value) {
				//if dropdown is myaccount, do not adjust out of member folder
				$class = 'header_link';
				if($key == 'Login')
					$class .= ' login_right';
				
					echo "<a href='". $value . "'><div class='$class' id='drop'>" . $key . "<img src='/images/home/arrow.png' id='arrow'/></div></a>\n";
					
			}
			
			echo "</div>\n";
	
	//end link()
	}
	
}


class Body extends General{
	
	var $url;
	
	function __construct() {
		
		global $url;
		
		$this->url = $url;
		
		echo "<div id='body_main'>";
	
	//end __construct	
	}
	
	function __destruct() {
		
		//close body_main and body divs along with document
		//create body_bottom div which connects main body to
		//bottom of window
		echo "</div><div id='body_bottom'></div></div>
				<div id='tooltip' class='text tooltip'></div>";
				
		//if in preview, create tooltips in js
		if(!empty($_SESSION['preview'])){

			$tooltip = new Tooltip();
			$tooltip->tooltip_array = array(array('#top_left_balance','This is your wallet, showing how many tokens you have left to spend.'),
									array('.body_left_bar','And these are the activities you can spend your tokens on.  You may reserve as many of these as you\
															would like, so long as you do not overspend your budget. Try clicking on one of the activities that interests you.',
															'.body_left_bar'),
									array('#bottom_right',"Voila! A brief description and details as to what your deal is good for."),
									array('#input_submitter',"It\'s as simple as that!  When you are done choosing your activities, accept these\
															selections and choose how to spend your leftover tokens.  Click \"Close\" to try it out!")
									);
			$tooltip->create();
		}
									
		echo	"</body></html>";
	
	//end __destruct	
	}
	
	
}

class Information {
	
	var $head;
	
	function __construct($title, $page_title, $own_js = 1) {
		
		$this->head($title,$own_js);
		
		$this->header_body($page_title);
		
	}
	
	function head($title, $own_js = 1){
		
		$this->head = new Head($title,$own_js);
		$this->head->script('information');
		$this->head->style('information');
		$this->head->close();
		
	}
	
	function header_body($page_title) {
		
		global $login;
		
		//create header
		$this->header = new Header();
		
		//create top title for page
		echo "<div class='information_title text'>" . $page_title . "</div>";
		
		//display questions info at bottom

			$this->body = new Body();
		
	}
		
}


class Form extends General {
	
	//create form tag
	function __construct($array) {
		
			$block = "<form ";
			
			foreach($array as $key=>$val){
				$block .= $key . "='" . $val . "'";
			}
			
			$block .= ">";
			
			echo $block;
			
	}
	
	//create custom input for form
	function input($array){
					
			if($array['type']=='checkbox' && !empty($_SESSION['signup']['auto_renew']) && $_SESSION['signup']['auto_renew']=='Yes')
				$checked = 'checked';
			else
				$checked='';
				
			$name_checker = false;
			
			$block= "<input ";
			
			foreach($array as $key=>$val){
				if($key == 'name')
					$name_checker === true;
				if($key == 'id')
					$val = 'input_' . $val;
				
				$block .= $key . "='" . $val . "' ";
			}
			
			if($name_checker === false)
				$block .= "name='" . $array['id'] . "'";
			
			$block .= $checked;
			
			$block .= "/>";
			
			return $block;
	}
	
	
	function close() {
			echo "</form>";
	}
	
}

class Alert {
	
	function __construct($name,$src) {
			
			$darken="<div class='darken $name' onclick='$(\".$name\").toggle()'></div>";
			$img = "<img class='centered $name' src='/images/alert/$src.png' />";
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
		$div = "<div class='$name text centered alert_back' id='alert_$name'>";
		
		echo $darken.$div;
	}
	
	function calendar_alert($title, $button_src){
		$class = $this->name;
		//adjust for being out of root directory (within /member)
				
		$title = "<p class='alert_title'>$title</p>";
		$activity_name = "<p id='alert_activity_name' class='alert_toggle'></p>";
		$time = "<p id='alert_what_time' class=''>What time would you like your reservation?</p>";
		$form = new Form(array('action'=>'/member/ajax_calls/calendar_ajax.php',
								'method'=>'POST'));
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
		echo $select_hours.$select_minutes.$select_ampm.$date;
		echo $form->input(array('type'=>'image',
								'id'=>'alert_button',
								'class'=>'alert_toggle',
								'src'=>$button_src));
		echo $form->input(array('type'=>'hidden',
								'id'=>'aid'));
		echo $form->input(array('type'=>'hidden',
								'id'=>'date'));
		$form->close();
	}
					
	function confirm($message) {
		$block = "<p class='alert_title	text'>Confirmation</p>";
		
		$block .= "<p class='alert_main_confirm text'>Are you sure you want to $message</p>";
		
		$block .= "<p class='alert_main_val'></p>";
		
		$block .= "<img src='/images/change/yes_button.png' class='confirm_button' id='yes'/>
					<img src='/images/change/no_button.png' class='confirm_button' onclick='$(\".$this->name\").toggle()'/>";
		
		echo $block;
		
		$this->close();
		
	}
	
	function generic($header,$main) {
		
		$block = "<p class='alert_title	text'>$header</p>";
		
		$block .= "<p class='alert_main text'>$main</p>";
		
		echo $block;
		
		$this->close();
		
	}
		
	
	//close divs of alert box and add x
	function close(){
		
		$x = "<div class='x $this->name text' onclick='$(\".$this->name\").toggle()'>X</div>";
		
		echo "</div>" . $x;
	}
}

class Head_signup extends Head {
	
	var $style = array('signup/form');
	
	
}

class Body_signup extends Body {
	
	var $neighborhoods = array('SOMA','Castro','Chinatown','Fisherman\'s Wharf','Haight',
					'Japantown','Marina','Mission','North Beach','Pacific Heights',
					'Presidio','Panhandle','Tenderloin','Union Square');
	
	function background($title, $position) {
			
			//if at last page for signup,
			//load large background
			if($position==4 || $position ==5) {
				$smallorbig = 'big';
				$id = 'review';
			}
			else{
				$id='';
				$smallorbig = 'small';
			}
				
			$background = "<div id='background_$smallorbig'>";
			$status = "<img src='/images/signup/signup_$position.png' alt='Step $position of 4' class='position'/>";
			
			if($position==0)
				$status='';
			
			$header = "<p class='text title' id='$id'>$title</p>";
			
			echo $background.$status.$header;
			
	}
	
	function create_input($array,$action,$button_type = 'update',$onsubmit = '',$id = '') {
			
			$this->form = $form = new Form(array('action'=>$action,
													'method'=>'POST',
													'onsubmit'=>$onsubmit,
													'id'=>$id));
			$input_cnt = count($array);
			
			foreach($array as $title => $box_type){
				$class = 'drop text';
				$title_class = 'box_title';
				$lower_class = 'text box_title_lower';
				
				//if we have more than 2 input boxes, make distance closer
				if($input_cnt > 2)
					$title_class = 'box_title_close';
				elseif($input_cnt < 2)
					$title_class = 'box_title_one';
				
				//create title for input box
				echo "<div class='$title_class text'>$title</div>";
				
				$title = str_replace(' ','_',strtolower($title));
				
				//if fail var is set for this input box, turn to red
				if(!empty($_SESSION['user'][$title . '_fail'])){
					$class .= ' red_back';
					$lower_class .= ' red';
					unset($_SESSION['user'][$title . '_fail']);
				}
				//if box_type is array, then we are using a select input and must create
				if(is_array($box_type['type'])){
					echo "<select name='$title' class='$class'>";
					//loop through select array and create options
					foreach($box_type['type'] as $val){
						echo "<option>$val</option>";
					}
					//close select option
					echo "</select>";
				}
				//if not array, then display regular input box 
				else{
					
					echo $form->input(array('type'=>$box_type['type'],
											'id'=>strtolower($title),
											'class'=>$class));
					
				}
				
				//display lower text below the input box
				echo "<p class='$lower_class'>" . $box_type['lower'] . "</p>";
			}
			
			echo $form->input(array('type'=>'image',
								'id'=>'submitter',
								'class'=>'next_button',
								'src'=>'/images/change/' . $button_type . '_button.png'));
			$form->close();
			
			//return up one directory to parent file (ie account/change.php routes to account/)
			if(strpos($_SERVER['PHP_SELF'],'index.php') == true){
				$refering_url = '../';
			}
			else{
				$refering_url = str_replace('application/','',dirname($_SERVER['PHP_SELF']));
			}
			
			echo "<a href='$refering_url' class='back text'>Back</a>";
	}
	
	//function to generate neighborhood options during signup_1 and member/change.php
	function signup_options() {
	
		$neighborhood_array = $this->neighborhoods;
	
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
	
	function close(){
		
			echo "</div>";
			
	}
	
}

//general function to open txt file given type of txt (ie description, tip) and activity name
function txt_file($type, $month_in_use, $a_name) {
	
	$a_name = str_replace(' ','_',$a_name);
	
	$filename = $_SERVER['DOCUMENT_ROOT'] . '/txt/' . $type . '/' . $month_in_use . '/' . $a_name . '.txt';
	
	if(!file_exists($filename)){
				  //if activity file does not exist, display default txt
				  $filename = $_SERVER['DOCUMENT_ROOT'] . '/txt/' . $type . '/default.txt';
				
				  $file = fopen($filename,'r');
				  $contents = fread($file, filesize($filename));
				  
			  }
				  
			  else{
				  //elseif file exists, display activity's text
				  $file = fopen($filename,'r');
				  $contents = fread($file, filesize($filename));
			  }
			  
	return $contents;
}

//convert date to str given format (mo/dd/yyyy)
function date_to_str($date, $format = '') {
	
		$date_call = DateTime::createFromFormat('m/d/Y', $date);
		
		if($format == '') 
			return $date_call->format('F j, Y');
		else
			return $date_call->format($format);
			
		
}

	
