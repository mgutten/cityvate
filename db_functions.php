<?php

class User {
	
	var $username = 'root';
	var $password = '';
	var $db = 'cityvate_main';
	var $con;
	var $user = array();
	var $salt = 'nHVxL3lgwRNMZ9p6';
	
	function __construct() {
		
			$this->con = new MySQLi('localhost',$this->username,$this->password,$this->db);
			
	}
	
	function login($username,$password,$checking=0) {
		
			$pw = $this->password($password);
			
			$query = "SELECT uID,username FROM users WHERE username='".$username."' ";
			
			//if we arent simply checking if username exists, test password also
			if($checking==0)
				$query.= "and password='".$pw."'";
			
			$result = $this->con->query($query);
			
			$num_results = $result->num_rows;
			
			//if find no username with that password, redirect back to login page
			if($num_results < 1) {
				//if checking for password also ($checking=0)
				//send back to login page
				if($checking==0){
				header('location:login.php?login=fail');
				exit;
				}
				
			}
			//if find result store info to session and login
			else {
				//if found results and not checking for pw ($checking=1),
				//then username is taken, send back to signup page
				if($checking!=0){
					$_SESSION['exists']=TRUE;
					header('location:signup_2.php');
					exit;
				}
					
				//loop through and store necessary user information
				while($row = $result->fetch_array()){
						
						$_SESSION['user']['uID'] = $row['uID'];
						$_SESSION['user']['username'] = $row['username'];
						
				}
				
				//login to member page
				header('location:member/member.php');
			}
			
			$result->free();
			
	}
	
	//hash the password
	function password($password) {
			
			$salt = md5($this->salt);
			return md5($salt . $password);
	}
	
	
}

class Activities extends User {
	var $activities=array();
	var $reserved=array();
	var $limit=array();
	
	function __construct() {
		//run parent construct to establish connection to db
		parent::__construct();
	}
	
	function activities($selected_month = '',$calendar = 0,$done = 'regular'){
		
			if($selected_month == '' || $selected_month == date('n')) 
				$date_period = 'MONTH(CURDATE())';
			else
				$date_period = '"'.$selected_month.'"';
			
			//test to see if we need the activities by month it was used
			//or by when it expires
			if($calendar==0)
				$month_test = "MONTH(`activities`.`month_in_use`) =";
			
			
			//test to see if we need the activities that are done or that are
			//currently active
			$where = 'WHERE `u_activities`.`uID` = "'.$_SESSION['user']['uID'].'"';
			if($done == 'regular')
				$where = ' AND '.$month_test.' '.$date_period;
				//AND `activities`.expire >= CURDATE()';
				
			elseif($done == 'done')
				$where = ' AND `u_activities`.done = "1" AND MONTH(`activities`.month_in_use)>=MONTH(CURDATE())-2';
				
			elseif($done == 'expire')
				$where = ' AND `activities`.expire < CURDATE()';
				
			
			//form query to retrieve all activity names 
			//for user with session uid and where month
			//in use >= current month
			$query = 'SELECT `activities`.`name` as name,
							`activities`.`expire` as expire,
							`u_activities`.`reserve_date` as reserve_date, 
							`u_activities`.`done` as done, 
							`activities`.`type` as type, 
							`activities`.`aID` as aID ,
							`activities`.`reserve_needed` as reserve_needed
						FROM `u_activities` 
						INNER JOIN `activities` 
						ON `u_activities`.`aID` = `activities`.`aID` 
						'.$where.'
						ORDER BY expire DESC';
									
			
			//result set
			$result = $this->con->query($query);
			
			//loop through array and set results to activities array
			$i=0;
			while($row = $result->fetch_array()){
						
						$this->activities[$i]['name'] = $row['name'];
						$this->activities[$i]['reserve_date'] = $row['reserve_date'];
						$this->activities[$i]['type'] = $row['type'];
						$this->activities[$i]['aID'] = $row['aID'];
						$this->activities[$i]['done'] = $row['done'];
						$this->activities[$i]['reserve_needed'] = $row['reserve_needed'];
						$this->activities[$i]['expire'] = $row['expire'];
						$i++;
						
				}
			return $this->activities;
	}
	
	function upcoming($num_days = 7) {
	
	//figure out what reserved events are coming in the next week
	for($i=0;$i<count($this->activities);$i++) {
	
		if(!empty($this->activities[$i]['reserve_date'])){
			$date_now = time();
			$date_event = strtotime($this->activities[$i]['reserve_date']);
			$diff = abs($date_event-$date_now);
			if($diff < 60*60*24*$num_days)
				$upcoming_event[] = $i;
		}
	}
	
	if(!empty($upcoming_event))
		return $upcoming_event;
	}
	
	function remove_activity($aid,$to_current = '1'){
			
			//where clause for both sets of queries
			$query_where = " WHERE `u_activities`.`uID` = '".$_SESSION['user']['uID']."' AND `u_activities`.`aID` = '".$aid."'";
			
			//if we want the activity to move to current
			//activity list, then change 'done' to false and
			//reserve_date to null
			if($to_current == 1)
				$query = "UPDATE `cityvate_main`.`u_activities` SET `done` = '0', reserve_date = NULL";
			//else we want to delete that activity from
			//the user's activity database entirely
			else
				$query = "DELETE FROM `u_activities`";
			
			//add the where clause to query
			$query .= $query_where;
			
			//query the database
			$this->con->query($query);
			
			
	}
	
	function change_reserve($aid,$new_date) {
			//if cancel reserve (ie NULL) then no quotes
			//but if changing date then need quotes
			if($new_date != 'NULL')
				$new_date = "'".$new_date."'";
			
			$query = "UPDATE `u_activities` 
						SET `reserve_date` = ".$new_date."
						WHERE `aID` = '".$aid."'";
							 
			$result = $this->con->query($query);
			
	}
	
	function activity_desc($activity_aid) {
		
			$query = "SELECT `activities`.name,
							DATE_FORMAT(`activities`.expire,'%m/%d/%Y') as expire,
							`activities`.g_maps,
							`activities`.type,
							`activities`.cost,
							`activities`.tokens,
							`activities`.reserve_needed,
							`activities`.month_in_use,
							`activities`.save,
							`activities`.desc,
							`activities`.aID,
							`u_activities`.done,
							DATE_FORMAT(`u_activities`.reserve_date,'%m/%d/%Y') as reserve_date
					FROM activities
					INNER JOIN u_activities
					ON `u_activities`.aID = `activities`.aID
					WHERE `u_activities`.`uID` = '".$_SESSION['user']['uID']."'
						AND activities.aID = '".$activity_aid."'";
						
			$result = $this->con->query($query);
			
			while($row = $result->fetch_array()){
					
					$activities['name'] = $row['name'];
					$activities['aID'] = $row['aID'];
					$activities['expire'] = $row['expire'];
					$activities['g_maps'] = $row['g_maps'];
					$activities['type'] = $row['type'];
					$activities['cost'] = $row['cost'];
					$activities['tokens'] = $row['tokens'];
					$activities['reserve_needed'] = $row['reserve_needed'];
					$activities['month_in_use'] = $row['month_in_use'];
					$activities['save'] = $row['save'];
					$activities['desc'] = $row['desc'];
					$activities['done'] = $row['done'];
					$activities['reserve_date'] = $row['reserve_date'];
					
			}
			
			return $activities;
	}
	
	
}

?>