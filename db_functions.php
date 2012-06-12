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
	
	function __construct() {
		//run parent construct to establish connection to db
		parent::__construct();
	}
	
	function activities($selected_month = ''){
		
			if($selected_month == '') 
				$date_period = '>= MONTH(CURDATE())';
			else
				$date_period = '= "'.$selected_month.'"';
			
			//form query to retrieve all activity names 
			//for user with session uid and where month
			//in use >= current month
			$query = 'SELECT `activities`.`name` as name,`u_activities`.`reserve_date` as reserve_date, `activities`.`type` as type, `activities`.`aID` as aID FROM `u_activities` 
						INNER JOIN `activities` 
						ON `u_activities`.`aID` = `activities`.`aID` 
						WHERE `u_activities`.`uID` = "'.$_SESSION['user']['uID'].'" AND
						MONTH(`activities`.`month_in_use`) '.$date_period.'
						ORDER BY name ASC';
			//result set
			$result = $this->con->query($query);
			
			//loop through array and set results to activities array
			$i=0;
			while($row = $result->fetch_array()){
						
						$this->activities[$i]['name'] = $row['name'];
						$this->activities[$i]['reserve_date'] = $row['reserve_date'];
						$this->activities[$i]['type'] = $row['type'];
						$this->activities[$i]['aID'] = $row['aID'];
						$i++;
						
				}
			return $this->activities;
	}
	
	
}

?>