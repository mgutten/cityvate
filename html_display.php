<?php
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
					array_push($this->style, 'header_out');
					array_push($this->script, 'header_out');
				}
				else {
					array_push($this->style, 'header_in');
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

class Head_signup extends Head {
	
	var $style = array('signup_form');
	
	
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
					$this->drop['My Account'] = 'myaccount.php';
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

class Body_member extends Body {
	
	var $links = array("My Activities"=>'member.php',
						"Calendar"=>'calendar.php',
						"Past Activities"=>'past.php');
	var $done = array();
	var $activities=array();
	
	
	function __construct($selected_tab) {
		
			echo "<div id='header2_back'>";
			
			$tabs = '';
			$cur_tab=1;
			
			foreach($this->links as $key=>$value) {
				
					($selected_tab==$cur_tab ? $class = 'header_dark' : $class = 'header_light');
					
					$tabs.= "<a href='".$value."' alt='Navigate to ".$key."'><div class='".$class." text header2'>$key</div></a>";
				
					$cur_tab += 1;
			}
			
			echo $tabs;
			
			echo "</div>";
			
			parent::__construct();
	}
	
	//populate member.php with current activity bars
	function member_activity($activities){
		
			echo "<div id='top_right_activities' class='top_right_activities'>";
			$this->activities = $activities;
			
			if(empty($this->activities)){
				echo "<div class='text no_activity'>There are no activities for this month.</div></div>
						<div id='pag_nums'></div>";
				return;
			}
				
			
			//subtract a day to make sure the coupon is definitely expired
			$cur_time = time()-(60*60*24);
			$b = 0;
			for($i = 0; $i < count($activities);$i++):
				//if we have more than 5 activities, do pagination
							if($b >= 5)
								break;
							
						  	if($activities[$i]['done']==true){
								array_push($this->done,$activities[$i]);
								continue;
							}
                              $class = 'activity text';
                              //if no reserve date set, echo link to calendar
                              if(empty($activities[$i]['reserve_date'])){
                                  $reserve_date = '<a href="calendar.php"><img src="../images/member/plus.png" title="Add to Calendar" class="activity_reserve plus"/></a>';
							  }
							  else{
                                  $date = DateTime::createFromFormat('Y-m-d', substr($activities[$i]['reserve_date'],0,10));
                                  $reserve_date = "<a href='calendar.php'><p class='activity_reserve' title='Change Reservation'>".$date->format('M j')."</p></a>";
							  }
							  
							  //if coupon is expired, show no link to calendar
							  if(strtotime($activities[$i]['expire']) < $cur_time) {
								  $reserve_date = '<p class="activity_expired" title="Expired">exp</p>';
							  }
                              
                              if($i == 0)
                                  $class .= ' selected';
                          echo "<div class='$class' id='".$activities[$i]['aID']."'>
						  			<p class='activity_name'>".$activities[$i]['name']."</p>
									<p class='activity_type'>".$activities[$i]['type']."</p>
									$reserve_date</div>";
						$b++;
                          
            endfor;
			
			echo "</div>";
			
			echo "<div id='pag_nums" . $id . "'>";
		
			//if we have more than 5 active activities, create pagination
			if(count($activities)-count($this->done) > 5)
					$this->pagination_fn($activities, 5);
					
			echo "</div>";
	}
	
	function pagination_fn( $array, $limit_amt, $id = '') {
			
			echo "<p class='pag_nums_page text'>Page: </p> ";
				
				$pag_num = ceil(count($array)/$limit_amt);
				for($i = 1; $i <= $pag_num; $i++){
					$class = 'pag_nums' . $id . ' text';
					
					if($i == 1)
						$class .= ' pag_nums_selected' . $id;
					
					echo "<p class='$class'>$i</p>";
					//if on last iteration, do not place a "|"
					if($i == $pag_num)
						continue;
					echo "<p class='pag_nums_separate'>|</p>";
				}
				echo "</div>";
	}
		
	
	//populate member.php with finished activities
	function member_finished() {
		echo "<div id='activity_done_lower'>";
		
			if(!empty($this->done)){
				
				$b = 0;
				//loop through done array and create a new
				//line for each finished activity
				foreach($this->done as $key=>$value){
					//limit to 3 activities
					if($b >= 3) 
						break;
					echo "<div class='activity_done text' >
							<a href='activity.php?num=".$value['aID']."' class='activity_link'>
								<p class='activity_name'>".$value['name']."</p>
								<p class='activity_type'>".$value['type']."</p>
							</a>
							<p class='activity_reserve activity_done_x' id='".$value['aID']."' title='Never Did This'>X</p></div>";
					$b++;
				}
				
				
				
			}
			else
				echo "<p class='text no_activity' id='no_activity_done'>You haven't done any activities this month.</p>";
		echo "</div>";
		
		if(count($this->done) > 3)
					$this->pagination_fn($this->done,3,'_done');
	}
	
	//populate "Coming Up" section of member.php
	function member_upcoming($upcoming_event) {
				
		$block='';
		
		if(empty($upcoming_event)){
			$block .= '<p class="upcoming text">No activities this week!</p>';
			$block .= '<p class="upcoming text">Either add one from your calendar.</p>';
			$block .= '<p class="upcoming text">Or check out the free activity of the day!</p>';
			
			echo $block;
			return;
		}
		
		for($i=0;$i<count($upcoming_event);$i++) {
			
				//shorten our reserve_date var
				$reserve_date = $upcoming_event[$i]['reserve_date'];
				$block .= '<p id="upcoming_'.$upcoming_event[$i]['name'].'" class="upcoming text">';
				//parse reserve_date var for day, date, and time respectively
				$day = date('D',strtotime($reserve_date));
				$date_reserved = date('m/d',strtotime($reserve_date));
				$time = date('g a', strtotime($reserve_date));
				//format the date,time,day appropriately
				$block .= $day.' '.$date_reserved.' @ '.$time."</p>";
				
				$block .= "<a href='activity.php?num=".$upcoming_event[$i]['aID']."'><p class='text upcoming_name'>"
							.$upcoming_event[$i]['name']."</p></a>";
				if($i>=3) {
					break;
				}
				
		}
		echo $block;
	}
	
						
}

//class for past activities
class Body_member_past extends Body_member {	
	
	//function to call all respective fns that will display activities
	//for past.php
	public function display_activities() {
			
			$this->get_activities();
			$array = $this->separate_activities();	
			$this->html_activities($array);
	}
	
	//retrieve activities from all months prior to current month
	public function get_activities() {
			
			//store activities call in public var
			$this->activities_call = new Activities();
			//store activities array from every month before this one
			$this->activities = $this->activities_call->past_activities();
								
	}	
	
	//separate retrieved activity array into associative arrays for
	//use in html_activities looping
	public function separate_activities() {
			
			//our return array created with 2 indexes
			//0 = not done activities, 1 = done activities
			$activities = array();

			$cur_time = time() - (60*60*24);
			
			
			//separate activities into separate arrays
			for($i = 0; $i < count($this->activities); $i++){
					
					//if the selected month is not yet an array,
					//create the array for both done and not done
					if(empty($activities[$this->activities[$i]['month_in_use']])){
							$activities[$this->activities[$i]['month_in_use']] = array(0=>array(),
																						1=>array()
																					);
					}
					
					//create multidimensional array of format $activities['done']['month_in_use'][array(aid,name)]
					//so activities are split first into done and not done, then into month
					
					//case when expired, push activity to end of array
					if(strtotime($this->activities[$i]['expire']) < $cur_time){
						array_push($activities[$this->activities[$i]['month_in_use']][$this->activities[$i]['done']], 
									array('aID'=>$this->activities[$i]['aID'],
										'name'=>$this->activities[$i]['name'],
										'expire'=>true
										));					
						continue;
					}
					//case when not expired, unshift onto beginning of array
						array_unshift($activities[$this->activities[$i]['month_in_use']][$this->activities[$i]['done']], 
									array('aID'=>$this->activities[$i]['aID'],
										'name'=>$this->activities[$i]['name'],
										'expire'=>false
										));
	
			}
			
			return $activities;
	}
	

	public function html_activities($array) {
			
			$block = '';		
			if(!empty($array)){
				//loop through outermost layer (months)
				foreach($array as $month=>$v) {
					
					
						$block .= "<div class='month_back'></div>        
									<p class='month_title text'>
									" . $month . "
									</p>";
						
						//loop through done array					
						foreach($v as $done=>$inner) {
							
							$block .= "<div class='month_body_" . $done . "'>";
							
							//loop through individual activity arrays
							foreach($inner as $key=>$value) {
								//loop through each activity to pull out $name
									$class= 'text activity';
									$title = '';
									
									if($value['expire'] == true){
										$class .= ' expired';
										$title = 'Expired';
									}
									
									
									$block .= "<a href='activity.php?num=" . $value['aID'] . "'>
													<p class='" . $class . "' title = '" . $title . "'>
														" . $value['name'] . "
													</p>
												</a>";
												
								}
						
							$block .= "</div>";
							
						}
						
				}
				
			}
		else {
			$block .= "<p class='text activity' id='no_activities'>You have no past activities.</br>Check back later!</p>";
			
			
		}
	
		echo $block;
	
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

class Calendar {
	var $last_day;
	var $today;
	var $first_day;
	var $month;
	var $reserved_days = array();
	var $last_month_days;
	var $activities;
	
	function calendar_calls($selected_month,$year=0) {
		
			$activities_call = new Activities;
			$this->activities = $activities_call->activities($selected_month,1,'none');
			$this->important_dates($selected_month,$year);
			$this->reserved_days($this->activities);
			
	}
	
	function important_dates($selected_month,$year=0) {
		
			$this->month = $selected_month;
			//if it is current month, set 'today'
			//so we can use it later
			if($selected_month == date('n')) {
				$month = date('n');
				$this->today = date('j');
			}
			//else if we are in next month, then
			//set today at 0 so other functions 
			//recognize that we are in the future
			elseif($selected_month == date('n')+1){
				$month = $selected_month;
				$this->today = 0;
			}
			//else we are in a past month and so
			//set today at high number so fns know
			//that the selected month is in past
			else{
				$this->today = 100;
				$month = $selected_month;
			}
			
			//if we have changed year, then get calendar
			//for corresponding year
			if($year == 0)
				$year = date('Y');
										
			$this->first_day = date('w',mktime(0,0,0,$month,1,$year));
			$this->last_day = date('t',mktime(0,0,0,$month,1,$year));
			
			//find the prior months number of days
			$this->last_month_days = date('t',mktime(0,0,0,($month-1),1,$year));
		
			
	}
	
	function reserved_days($activities_array) {
						
			for($i=0;$i<count($activities_array);$i++){
				//if reserve date is set, then take the day from it
				//and store it in the reserved_days array
				if(!empty($activities_array[$i]['reserve_date'])){
						$date = new DateTime($activities_array[$i]['reserve_date']);
						$reserve_date = $date->format('mj');
						//find time that reservation is at to be displayed
						//on calendar
						$reserve_time = date('g:i a',strtotime($activities_array[$i]['reserve_date']));
						$expire = date('md',strtotime($activities_array[$i]['expire']));
						$name = $activities_array[$i]['name'];
						$aid = $activities_array[$i]['aID'];
						$reserve_needed = $activities_array[$i]['reserve_needed'];
						//store result into array with name, reserve_date, and aid
						//for future use in create_calendar
						$this->reserved_days[$reserve_date] = array($name,$reserve_time,$aid,$expire,$reserve_needed);
						
				}
			}
	}
	
	function create_calendar() {
		global $calendar;
		
		//create calendar
		$c = 1;
		//subtract 1 to deal with offset created by
		//date function's array (0-6 = days of week)
		$last_month_day = $calendar->first_day-1;
		$next_month_day = 1;
		$month = '0'.$this->month;
		
		//create 5 weeks of month
		for($i=0; $c<=$calendar->last_day; $i++) {
			//create 7 days of week
			for($b=0; $b<7; $b++) {
				
				$blank = false;
				$class = 'calendar_day';
				
				//if it's the first week and before first
				//day or after last day of month, create blanks
				if($i==0 && $b<$calendar->first_day || $c>$calendar->last_day){
					//if its the month before then use last months days
					if($i==0){
						$day = $calendar->last_month_days-$last_month_day;
						$last_month_day--;
					}
					//else its next month and should start at 1
					else {
						$day = $next_month_day;
						$next_month_day++;
					}
					
					$blank = true;
					$class .= ' transparent';
						
				}
				//if running day is after today, then make it droppable
				elseif($c>=$calendar->today) {
					$class .= ' droppable';
					$day = $c;
				}
				//else not droppable
				else {
					$class .= ' not_transparent';
					$day = $c;
				}
				
				//test if even or odd day
				if(($b % 2) == 0){
						$class .= ' calendar_light';
				}
				else {
						$class .= ' calendar_dark';
				}
				
				//if first day of week, start it on a new line		
				if($b==0)
						$class .= ' week_first';

				if($c == $calendar->today)
						$class .= ' today';
				
				//if we are at the end of the month, stop giving
				//ids to the blank days
				if($blank == true)
					$id = '';
				else
					$id = $c;
				
				//give each calendar day an id of that day's value for
				//use in js fn ondrag
				$block = "<div class='".$class."' id='".$id."'>
						<p class='text calendar_day_text'>".$day."</p>";
				//if running day has a reserved activity, show name
				//format for array from reserved_activities fn is monthday(eg. 0619)
				if(!empty($calendar->reserved_days[$month.$c])){
						if($c<$calendar->today)
							$block .= "<p class='text activity activity_old' onclick='activity_desc(".$calendar->reserved_days[$month.$c][2].",1)'>".$calendar->reserved_days[$month.$c][0]."</br>(".$calendar->reserved_days[$month.$c][1].")</p>";
						else
							$block .= "<div class='draggable_container' id='0" . $calendar->reserved_days[$month.$c][4] . "'>
										<p id='000" . $calendar->reserved_days[$month.$c][2] . "' class='text activity draggable' onclick='activity_desc(".$calendar->reserved_days[$month.$c][2].",1)'>
											".$calendar->reserved_days[$month.$c][0]."</br>(".$calendar->reserved_days[$month.$c][1].")
										</p>
										<span id='" . $calendar->reserved_days[$month.$c][3] . "'></span>
										</div>";
				}
				//else give an empty block so all calendar days are standardized
				else
					$block .= "<p class='text activity' onclick='no_clicked += 1; activity_desc(0,0);'></p>";
					
				//add p that will contain "reservation needed" or "expired" on drag
					$block .= "<p class='text nono'></p>";
				
								
				$block .= "</div>";
				
				echo $block;
				
				//if we are dealing with a blank day, do not 
				//increment our running day var $c
				if($blank === true)
					continue;
					
				$c++;
			}
		}
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
						$class = 'drop text';
						$lower = '';
						
						//if session var is not empty, fill box with val
						(!empty($_SESSION['signup'][$id]) ? $val = $_SESSION['signup'][$id] : $val='');
						
						//if empty and full name, give example
						if($key=='Full Name' && $val=='')
							$val='e.g. John Smith';
						
						//title caption for textbox
						echo "<p class='box_title text box_title_close' id='$id'>".$key."</p>";
						
						//if it's password box, make password text
						if($key=='Password')
							$form->input('password','password2',$class);
						else{
							//case when username is not in email format
							if($key=='Username/email' && 
								(!empty($_SESSION['user']['email']) || !empty($_SESSION['exists']))){
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

function calendar_my_activities($array) {
		//subtract a day to ensure that it is actually expired
		$cur_time = time()-(60*60*24);
		
		for($i=0;$i<count($array);$i++) {
			//if expired, do not show
			if(strtotime($array[$i]['expire']) < $cur_time || $array[$i]['done'] == 1)
				continue;
			
			$class = 'text activity_bar';
			
			if(!empty($array[$i]['reserve_date'])){
				$class .= ' activity_reserved';
				$reserve_set = 1;
			}
			else
				$reserve_set = 0;
			//populate with draggable bars
			//***add the 0s in front to give them unique ids from other divs
			echo "<div class='activity_holder' id='0".$array[$i]['reserve_needed']."'>
					<p class='".$class."' id='00".$array[$i]['aID']."' onclick='activity_desc(".$array[$i]['aID'].",".$reserve_set.")'>
						".$array[$i]['name']."
					</p>
					<span id='".date('md',strtotime($array[$i]['expire']))."'></span>
					</div>";
		}
}

	
		
			
?>
