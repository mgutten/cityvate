<?php

class Body_member extends Body {
	
	var $links = array("Activities"=> '/member',
						"Calendar"=>'/member/calendar',
						"History"=>'/member/past');
	var $done = array();
	var $activities=array();
	
	
	function __construct($selected_tab) {
			
			echo "<div id='header2_back'>";
			
			$tabs = '';
			$cur_tab=1;
			
			foreach($this->links as $key=>$value) {
					//make selected tab darker and longer
					($selected_tab==$cur_tab ? $class = 'header_dark' : $class = 'header_light');
									
					
					$tabs.= "<a href='" . $value . "' alt='Navigate to ".$key."'><div class='".$class." text header2'>$key</div></a>";
				
					$cur_tab += 1;
			}
			
			echo $tabs;
			
			echo "</div>";
			
			parent::__construct();
	}
	
	//check if there are new activities available
	function check_new_activities() {
		
		//if today is within the last 7 days of month
		/*TESTING, CHANGE 27 TO 7*/
		if(date('j') >= (date('t')-27))
			return true;
		else
			return false;
			
	}
	
	//populate member/index.php with current activity bars
	function member_activity($activities){
		
			echo "<div id='top_right_activities' class='top_right_activities'>";
			$this->activities = $activities;
			
			if(empty($this->activities)){
				//check if there are new activities available
				//check_new_activities is not a db call, but a 
				//test against a fixed date set as the lower bound
				//to alert of new activities
				if($this->check_new_activities() === true){
					echo "<a href='" . $this->url['new'] . "'><img src='/images/member/new_activities_button.png' class='new_activity' /></a></div>
						<div id='pag_nums'></div>";
				}
				//else there are no activities and no new activities
				else{
					echo "<div class='text no_activity'>There are no activities for this month.</div></div>
						<div id='pag_nums'></div>";
				}
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
                                  $reserve_date = '<a href="' . $this->url['calendar'] . '"><img src="../images/member/plus.png" title="Add to Calendar" class="activity_reserve plus"/></a>';
							  }
							  else{
								  //change date to format Mon dd
                                  $date = DateTime::createFromFormat('Y-m-d', substr($activities[$i]['reserve_date'],0,10));
                                  $reserve_date = "<a href='" . $this->url['calendar'] . "'><p class='activity_reserve' title='Change Reservation'>".$date->format('M j')."</p></a>";
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
			
			echo "<div id='pag_nums'>";
		
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
							<a href='" . $this->url['activity'] . "/" .$value['aID']. "' class='activity_link'>
								<p class='activity_name'>".$value['name']."</p>
								<p class='activity_type'>".$value['type']."</p>
							</a>
							<p class='activity_reserve activity_done_x' id='".$value['aID']."' title='Never Did This'>X</p>
							</div>";
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
				
				$block .= "<a href='" . $this->url['activity'] . "/" . $upcoming_event[$i]['aID'] . "'><p class='text upcoming_name'>"
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
									
									
									$block .= "<a href='" . $this->url['activity'] . "/" . $value['aID'] . "'>
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

//class to use template from Body_member class in account/subscription.php
class Body_account extends Body_member {
	var $links = array("My Account"=>'/member/account',
						"Subscription"=>'/member/subscription',
						"Preferences"=>'/member/preferences'
						);
						
	var $title_array = array("Account Info"=>array(
									"Email/Username"=>'username',
									"Password"=>false,
									"Subscription"=>array('package','end_date'),
									"Balance"=>'tokens_balance'),
							"Personal Info" =>array(
									"Name"=>array('fname','lname'),
									"City"=>'city',
									"Neighborhood"=>'neighborhood')
							);
	
	public function __destruct() {
		
		//close divs and create "Questions?" bottom
		echo "</div><div id='body_bottom_account' class='text'>Questions?  Contact us at support@cityvate.com</div></div></body></html>";
		
	}
	
	public function my_account_boxes() {
			
			//retrieve all user info and set to session vars
			$this->get_user_info();
			
			$array = $this->title_array;
			$block = '';
			
			//loop through outer array to display title_back
			foreach($array as $title=>$sub){
				
						$block .= "<div class='title_back'><p class='text title'>" . $title . "</p></div>";
						
					//loop through inner to grab individual categories
					foreach($sub as $val=>$key){
							
							$block .= "<p class='text sub_title'>" . $val . ":</p>
										<p class='text sub_title_val'>";
							
							if($key == false){
								$block .= '**********';
								$key = strtolower($val);
							}
							//if key has a sub array of session associative indexes
							elseif(is_array($key)){
								//if it's the name category, concatenate fname and lname
								if($val == 'Name'){
									$block .= ucwords($_SESSION['user'][$key[0]] . ' ' . $_SESSION['user'][$key[1]]);
									//convert key from array to string
									$key = strtolower($val);
								}
									
								//else separate to 2 lines
								else{
									$block .= ucwords($_SESSION['user'][$key[0]]) . '</br>ends ' . ucwords($_SESSION['user'][$key[1]]);
									//exit 2 levels to escape the generic href down below(back out of account/change and into subscription)
									$key = '../../subscription';
								}
								
								
							}
							//else it is a regular index and display regular key
							else{
								$block .= ucwords($_SESSION['user'][$key]);	
								if($key == 'tokens_balance')
									$block .= ' tokens';
							}
								
							$block .= "</p>";
							
							//do not display change option for city
							if($key == 'city' || $key == 'tokens_balance')
								continue;
							$block .= "<a href='" . $this->url['account'] . "/change/" . $key . "' class='text sub_link'>change</a>";
						
					}
			}
			
			echo $block;
	}
	
	public function get_user_info() {
		
			$user = new User();
			$user->get_user_info();
			
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

class Body_individual_activity extends Body {
	
	//cancel parent's construct call 
	function __construct() {
	}
	
	//function to display body_main
	function display_body() {
		
		parent::__construct();
	}
	
	function get_activity_desc($aid) {
		
		$activity_call = new Activities();
		//store activity description + business info (ie 1 not 0) in the $a var
		$this->a = $activity_call->activity_desc($aid,1);
		
		//change name to file_friendly
		$this->a_name = str_replace(' ','_',$this->a['name']);
		//change month to folder_friendly (eg 07 not 7)
		$this->a['month_in_use'] = str_pad($this->a['month_in_use'],2,'0',STR_PAD_LEFT);
		
		return $this->a;
		
	}
	
	function txt_file($type) {
		
			
			$a_name = $this->a_name;

			//retrieve .txt doc of activity description
			//located in /txt/$type/month(int)/activity_name.txt
			  $filename = $_SERVER['DOCUMENT_ROOT'] . '/txt/' . $type . '/' . $this->a['month_in_use'] . '/' . $a_name . '.txt';
			  
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
	
	function what() {
						
			$block = "<p class='box_name text'>";
			
			$block .= $this->txt_file('what');
			
			$block .= "</p>";
			
			return $block;
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

class New_activities {
	
	function get_activities() {
		
		$this->activities_call = new Activities();
		$this->activities = $this->activities_call->new_activities(date('n',strtotime("+1 month")));
		
	}
	
	function create_bars() {
		
		$reserved_activities = $this->activities_call->check_new_activities();
		
		global $form;
		
		$block = '';
		
		for($i = 0; $i < count($this->activities); $i++){
			
			//check if current activity is already reserved
			if(!empty($reserved_activities[$this->activities[$i]['aID']]))
				$reserved = true;
			else
				$reserved = false;
			
			$block .= "<div class='body_left_bar";
			
			//if reserved, change background color of bar
			if($reserved === true)
				$block .= " body_left_bar_selected";
			
			$block .= "' id='" . $this->activities[$i]['aID'] . "'>";
			
			$checkbox_array = array('type'=>'checkbox',
									'id'=>'check' . $this->activities[$i]['aID'],
									'autocomplete'=>'off',
									'class'=>'body_left_checkbox',
									'value'=>$this->activities[$i]['aID'],
									'name'=>'activities_list[]'
									);
			//if reserved, check the checkbox
			if($reserved === true)
				$checkbox_array['checked'] = 'checked';
				
			$block .= $form->input($checkbox_array);
			
			
			$block .= "<p class='text green body_left_val body_left_name'>" . $this->activities[$i]['type'] . "</p>";
			$block .= "<p class='text body_left_val body_left_cost'>" . 
						($this->activities[$i]['tokens'] == 0 ? 'Free' : $this->activities[$i]['tokens']) . "</p>";
			$block .= "<p class='text green body_left_val body_left_save'>" . 
						($this->activities[$i]['save'] > 100 ? "N/A" : $this->activities[$i]['save'] . "%") . "</p>";
			//$block .= "<p class='text body_left_val body_left_details'>Read about it</p>";
			$block .= "<p class='text body_left_val body_left_details'></p>";
			
			//if this is not a free activity, set quantity dropdown
			if($this->activities[$i]['tokens'] > 0){
				
				$qty_array = array('type'=>'text',
									'id'=>'qty' . $this->activities[$i]['aID'],
									'name'=>'qty[' . $this->activities[$i]['aID'] . ']',
									'class'=>'text body_left_qty drop',
									'maxlength'=>'2',
									'autocomplete'=>'off');
				//if reserved, set qty to previous qty					
				if($reserved === true)
					$qty_array['value'] = $reserved_activities[$this->activities[$i]['aID']]['qty'];
				
				$block .= $form->input($qty_array);
			}
			
			$block .= "</div>";
			
		}
		
		return $block;
		
			
	}
}