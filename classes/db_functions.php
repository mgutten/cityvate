<?php

class User {
	
	var $username = 'root';
	var $password = '';
	var $db = 'cityvate_main';
	var $con;
	var $user = array();
	var $salt = 'nHVxL3lgwRNMZ9p6';
	var $query;
	var $url;
	
	function __construct() {
			//set paths from bootstrap.php
			global $url;
			$this->url = $url;
			
			$this->con = new MySQLi('localhost',$this->username,$this->password,$this->db);
			
	}
	
	function login($username,$password,$checking=0) {
			
			
			$_SESSION['user']['username'] = $username;
			
			$pw = $this->password($password);
			
			//array of wanted information
			$array = array('uID','username','fname','lname','neighborhood','city','tokens_balance');
			$last = end($array);
			
			//check if username exists in database
			$user_exists = $this->check_username($username);
			
			//if doesn't exist, set fail session and redirect to login.php
			if($user_exists === false && $checking == 0) {
				header('location:' . $this->url['login']);
				exit;
			}
			else{
				//case when username exists but password entered later is incorrect
				//notify user that it is wrong password
				$_SESSION['user']['password_fail'] = true;

			}
			
			
			//start building query
			$query = "SELECT ";
			
			//loop through column array and build query
			foreach($array as $key){
				$query .= $key;
				if($key != $last)
					$query .= ',';
			}
			
			$query .= " FROM users WHERE username='".$username."' ";
	

			//if we arent simply checking if username exists, test password also
			if($checking==0)
				$query.= "and password='".$pw."'";
			else
				return;
			
			$result = $this->con->query($query);
			
			$num_results = $result->num_rows;
			
			//if find no username with that password, redirect back to login page
			if($num_results < 1) {
				//if checking for password also ($checking=0)
				//send back to login page
				if($checking==0){
					header('location:' . $this->url['login']);
					exit;
				}
				
			}
			//if find result store info to session and login
			else {
				//unset password fail session var
				unset($_SESSION['user']['password_fail']);
				//loop through and store necessary user information
								
				while($row = $result->fetch_array()){
						
						foreach($array as $val){
							
							$_SESSION['user'][$val] = $row[$val];
						}
						
				}
				
				
				//login to member page
				header('location:' . $this->url['member']);
			}
			
			$result->free();
			
	}
	
	function check_username($username) {
		
		$query = "SELECT uID FROM users WHERE username = '" . $username . "'";
		$result = $this->con->query($query);
		
		
		if($result->num_rows > 0){
			while($row = $result->fetch_array()){
				
				return $row['uID'];
				
			}
		}
		else{
			
			$_SESSION['user']['username_fail'] = $username;
			return false;
			
		}
	}
	
	function check_password($password) {
		$username = $_SESSION['user']['username'];
		$password = $this->password($password);
		
		$query = 'SELECT uID FROM users WHERE username = "' . $username . '" AND
					password = "' . $password . '"';
					
		$result = $this->con->query($query);
		
		//if num_rows>0 then results found and user exists
		if($result->num_rows > 0) 
			return true;
		else
			return false;
	}
		
	
	//hash the password
	function password($password) {
			
			$salt = md5($this->salt);
			return md5($salt . $password);
	}
	
	public function get_user_info() {
		
		//if session var is not empty, take from there
		//else die
		if(!empty($_SESSION['user']['uID']))
			$uid = $_SESSION['user']['uID'];
		else
			return;
		
		//set array of column values
		$array = array('pID','package','gID','package','end_date');
		
		$query = "SELECT ";
			
			//loop through array and append column name to query
			foreach($array as $val) {
				if($val == end($array)){
					$query .= "DATE_FORMAT(end_date,'%M %e, %Y') as end_date";
					break;
				}
					
				$query .= $val . ',';

			}
		
		//finish query
		$query .= " FROM u_subscriptions
					WHERE uID = '" . $uid . "'";
					
					
		$result = $this->con->query($query);
		
		//loop through array of columns and set session vars for each
		while($row = $result->fetch_array()){
			
				foreach($array as $val) {
					
					$_SESSION['user'][$val] = $row[$val];
					
				}
		}
		
		
	}
	
	//fn to change value of column for individual user from change_authenticate.php and from_cv.php
	function change($column_array, $user = ''){
		
		$query = "UPDATE users 
					SET ";
					
		$last_key = end(array_keys($column_array));			
		
		foreach($column_array as $column => $new){
			
			//hash pw
			if($column == 'password')
				$new = $this->password($new);
			
		 	$query .= $column . " = '" . $new . "'";
			
			if($column != $last_key)
				$query .= ",";
				
			//set updated session var
			$_SESSION['user'][$column] = $new;
			
		}
		$query .= " WHERE uID = '";
		$query .=  (!empty($user) ? $user : $_SESSION['user']['uID']);
		$query .=  "'";
		
		$result = $this->con->query($query);
		
	}
	
	//insert info into table with table_name and column_array
	function insert($table_name,$column_array){
		
		$query = 'INSERT INTO ' . $table_name . ' (';

		//if we have a multi dimensional array, then we are inserting
		//several values to same table
		if(!empty($column_array[0]) && is_array($column_array[0])){
			$last = end(array_keys($column_array[0]));
			
			//create column name paren
			foreach($column_array[0] as $key=>$val){
				$query .= $key;
				
				if($key != $last)
					$query .= ',';
			}
			
			$query .= ') VALUES ';
			
			//create values statement
			for($i = 0; $i < count($column_array); $i++){
				
				//open current value set
				$query .= '(';
				foreach($column_array[$i] as $key=>$val){
					
					//if $val contains CURDATE or NULL no quotes around
					if(strpos($val,'CURDATE') !== false || 
						strpos($val,'NULL') !== false ||
						strpos($val, '()') !== false ||
						strpos($val, '@tid') !== false)
						$query .= $val;
					else
						$query .= "'" . $val . "'";
					
					if($key != $last)
						$query .= ',';
				}
				//close current value set and append comma
				$query .= ')';
				if($i != (count($column_array)-1))
					$query .= ',';
			}
		}				
		else{					
		
		$val_addition = '';
		$last = end(array_keys($column_array));
		
		//loop through column array and create proper insert
		//statement for single row
		foreach($column_array as $key=>$val){
			$query .= $key;
			
			//if $val contains CURDATE or NULL no quotes around
			if(strpos($val,'CURDATE') !== false || 
				strpos($val,'NULL') !== false ||
				strpos($val, '()') !== false ||
				strpos($val, '@tid') !== false)
				$val_addition .= $val;
			else
				$val_addition .= "'" . $val . "'";
			
			if($key !== $last){
				$query .= ',';
				$val_addition .= ',';
			}

		}
		
		$query .= ') VALUES (' . $val_addition . ')';
		
		}
		
		$query .= ';';
		
		
		$this->query .= $query;
	}
	
	//update rows of $table_name with associative array
	function update($table_name, $column_array, $and_statement = '') {
		
		$query = 'UPDATE ' . $table_name . ' SET ';
		
		
		$last = end(array_keys($column_array));
		
		foreach($column_array as $key=>$val){
			
			//no quotes for mysql functions like CURDATE()
			if(strpos($val,'()') !== false)
				$query .= $key . ' = ' . $val;
			else
				$query .= $key . ' = "' . $val . '"';
			
			if($key != $last) 
				$query .= ',';
			
		}
		
		$query .= ' WHERE uID = "' . $_SESSION['user']['uID'] . '"';
		
		if($and_statement != '')
			$query .= ' ' . $and_statement;
		
		$query .= ';';
		
		$this->query .= $query;
	}
	
	function delete($table_name, $and_statement = '') {
		
		$query = 'DELETE FROM ' . $table_name . ' WHERE 
					uID = "' . $_SESSION['user']['uID'] . '"';
		
		if($and_statement != '')
			$query .= ' AND ' . $and_statement;
			
		$query .= ';';
			
		$this->query .= $query;
		
	}
	
	function execute() {
		
		if(substr_count($this->query,';') > 1)
			$result = $this->con->multi_query($this->query);
		else
			$result = $this->con->query($this->query);
		
		$this->query = '';
		
	}	
	
	//check if subscription is still good next month
	function check_subscription() {
		
		$query = 'SELECT package FROM u_subscriptions 
					WHERE uID = "' . $_SESSION['user']['uID'] . '" AND
					(MONTH(end_date) > MONTH(CURDATE()) AND
					MONTH(start_date) <= MONTH(CURDATE()) OR
					auto_renew = "1") LIMIT 1';
		
		$result = $this->con->query($query);
		
		//if there is a result, store package type in session
		if($result->num_rows > 0){
			while($row = $result->fetch_array()){
				$_SESSION['user']['package'] = $row['package'];
			}
			return true;
		}
		else
			return false;
			
	}
	
	function check_new_activities() {

		$query = 'SELECT tID,aID,qty FROM a_transactions WHERE
					uID  = "' . $_SESSION['user']['uID'] . '" AND
					MONTH(date_processed) = "' . date("n") . '"';
		
		$result = $this->con->query($query);
		
		
		if($result->num_rows > 0){
				
			$reserved_activities = array();	
				
			while($row = $result->fetch_array()) {
				$reserved_activities[$row['aID']] = array('tID'=>$row['tID'],
															'qty'=>$row['qty']);
			}
			
			return $reserved_activities;
			
		}
		else
			return false;
	}
			
	
	
	function loop_results($array,$counter = 0) {
		
			$res_array = array();
			if($this->result->num_rows > 0){
				
				//if counter is set to true, initiate counter
				if($counter == 1) 
					$i=0;
				
				while($row = $this->result->fetch_array()){
					
					//if counter is set, create multi-dimensional array
					if($counter == 1){
					
						foreach($array as $value) {
							$res_array[$i][$value] = $row[$value];
						}
						$i++;
						continue;
						
					}
					
					//else we just need one result of associative array
					foreach($array as $value) {
						
						$res_array[$value] = $row[$value];
						
					}
					
					
				}
				
				return $res_array;
			}
				
	}
	
	
}

class Activities extends User {
	var $activities=array();
	var $reserved=array();
	var $limit=array();
	var $result;
	
	function __construct() {
		//run parent construct to establish connection to db
		parent::__construct();
	}
	
	function activities($selected_month = '',$calendar = 0,$done = 'regular'){
		
			if($selected_month == '' || $selected_month == date('n')) 
				$date_period = 'MONTH(CURDATE())';
			else
				$date_period = '"'.$selected_month.'"';
			
			
			$month_test = "MONTH(`activities`.`month_in_use`) =";
			
			//test to see if we need the activities that are done or that are
			//currently active
			$where = 'WHERE `u_activities`.`uID` = "'.$_SESSION['user']['uID'].'"';
			//$order_by = 'ORDER BY expire DESC';
			$order_by = "ORDER BY CASE WHEN expire < CURDATE() THEN 10 else 0 END, reserve_date ASC";
			if($done == 'regular'){
				$where = ' AND '.$month_test.' '.$date_period;
				//$order_by = "ORDER BY CASE WHEN expire < CURDATE() THEN 10 else 0 END, reserve_date ASC";
			}
				
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
						' . $where . '
						' . $order_by;
									
			
			//result set
			$this->result = $this->con->query($query);
			
				$array = array('name','aID','expire','type',
								'reserve_needed',
								'done','reserve_date');
								
				return $this->loop_results($array,1);
			
	}
	
	//find next month's activities for any user
	function next_month_activities() {
		
		$query = 'SELECT type,save FROM activities
					WHERE MONTH(month_in_use) = "' . date('n',strtotime('+1 month')) . '"
					ORDER BY RAND() LIMIT 10';
				
					
		$this->result = $this->con->query($query);
		
		return $this->loop_results(array('type','save'),1);
	}
	
	function upcoming($num_days = 7) {
			
	$query = "SELECT u_activities.aID, 
					u_activities.reserve_date,
					activities.name as name
				FROM u_activities
				INNER JOIN activities
				ON u_activities.aid = activities.aid	
				WHERE DATEDIFF(reserve_date,CURDATE()) <= 7
				AND DATEDIFF(reserve_date,CURDATE()) >= 0
				AND u_activities.uID = '" . $_SESSION['user']['uID'] . "'";
				
	$this->result = $this->con->query($query);
		
		
		$array = array('aID','reserve_date','name');
							
		return $this->loop_results($array,1);
	
	}
	
	//find new activities for this user
	function new_activities($month) {
		
		$check_subscription = $this->check_subscription();
		
		//if package type is not stored and subscription 
		//check fails (ie no valid subscription) redirect to homepage
		if(empty($_SESSION['user']['package']) && 
			$check_subscription === false &&
			empty($_SESSION['user']['tokens_balance'])){
				
				header('location:' . $this->url['member']);
			}
		
		if($check_subscription == false)
			$package = false;
		else
			$package = $_SESSION['user']['package'];
		
		//set token limit for activities used in sql statemnt
		//(ie activity that costs 30 can not be seen by budget)
		if($package == 'budget')
			$token_limit = 20;
		elseif($package == 'basic')
			$token_limit = 45;
		elseif($package == 'premium')
			$token_limit = 400;
		elseif($package == false)
			$token_limit = 400;
			
		
		$query = "SELECT a.aID as aID, 
							GROUP_CONCAT(a_p.interest) as interest,
							a.name as name,
							a.tokens as tokens,
							a.save as save,
							a.type as type
					FROM 
						u_preferences as u_p,a_preferences as a_p  
					JOIN 
						activities as a
					ON 
						a_p.aID = a.aID
					WHERE 
						u_p.uID = '" . $_SESSION['user']['uID'] . "' 
					AND 
						MONTH(a.month_in_use) = (MONTH(CURDATE()) + 1)
					AND
						(a.tokens < " . $token_limit . ")
					GROUP BY 
						a_p.aID
					ORDER BY
						a.tokens DESC";
							
		$this->result = $this->con->query($query);
		
		$array = array('aID','name','tokens','save','type');
		
		return $this->loop_results($array,1);
				
			
	}
	
	function reserved_new_activities($aid_array){
		
		$query = "SELECT aID, name, type 
					FROM activities
					WHERE aID IN (";
					
		$last = end($aid_array);
		
		foreach($aid_array as $val){
			
			$query .= $val;
			
			if($val != $last)
				$query .= ',';
		}
		
		$query .= ")";
		
		$this->result = $this->con->query($query);
		
		$array = array('aID','name','type');
		
		return $this->loop_results($array,1);
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
	
	function activity_desc($activity_aid, $business_info = 0, $user_info = 1) {
		
			$query = "SELECT `a`.name,
							DATE_FORMAT(`a`.expire,'%m/%d/%Y') as expire,
							`a`.type,
							`a`.cost,
							`a`.tokens,
							`a`.reserve_needed,
							MONTH(`a`.month_in_use) as month_in_use,
							`a`.save,
							`a`.aID";
			
			//if want user related info, add it to query		
			if($user_info == 1){
				$query .= ",`u_a`.done,
							DATE_FORMAT(`u_a`.reserve_date,'%m/%d/%Y') as reserve_date";
			}
			
			//if looking for business info also ($business info > 0)
			if($business_info != 0){
				$query .= ",`b`.`name` as business_name,
							`b`.`email` as business_email,
							`b`.`phone` as business_phone,
							`b`.`street_address` as business_street_address,
							`b`.`city_address` as business_city_address,
							`b`.`website` as business_website,
							`b`.`yelp_address` as business_yelp_address,
							`b`.`yelp_rating` as business_rating";
			}
			
			
			$query .= " FROM activities a ";
			
			if($user_info == 1){
				$query .= "INNER JOIN u_activities u_a
						ON `u_a`.aID = `a`.aID ";
			}
			
			//join businesses table if looking for business info		
			if($business_info != 0){
				$query .= "JOIN businesses b 
							ON `a`.`aID` = `b`.`aID` ";
			}
			
			$query .= "WHERE ";
			
			if($user_info == 1)
				$query .= "`u_a`.`uID` = '".$_SESSION['user']['uID']."' AND ";
			
			$query .= "a.aID = '".$activity_aid."'";
			
			
			$this->result = $this->con->query($query);
			
			//default array of columns
			$array = array('name','aID','expire','type','cost',
							'tokens','reserve_needed','month_in_use',
							'save');
			
			//add business_info columns to requested rows			
			if($business_info != 0){
				$array_add = array('business_name','business_email','business_street_address',
									'business_city_address','business_website',
									'business_yelp_address','business_rating','business_phone');
									
				$array = array_merge($array,$array_add);
			}
			
			//add user info to requested rows
			if($user_info == 1){
				$array_add = array('done','reserve_date');
				
				$array = array_merge($array,$array_add);
			}
			
										
			return $this->loop_results($array);
			
	}
	
	function past_activities() {
		
			$query = "SELECT activities.name,
							activities.aID,
							activities.expire,
							MONTHNAME(activities.month_in_use) as month_in_use,
							u_activities.done
						FROM activities
						INNER JOIN u_activities
						ON activities.aID = u_activities.aID
						WHERE u_activities.uID = '" . $_SESSION['user']['uID'] . "'
						AND MONTH(activities.month_in_use) < MONTH(CURDATE())
						ORDER BY u_activities.done";
			
			$this->result = $this->con->query($query);
			
			$array = array('name','aID','expire','month_in_use','done');
			
			return $this->loop_results($array,1);
			
			
	}
	
	//cronjob operation to convert temporarily stored activity reservations to permanent
	//additions on the first of the month
	function commit_reservations() {
		
			$query = "INSERT INTO u_activities (uID, aID, qty) 
						(SELECT a_t.uID,a_t.aID,a_t.qty 
							FROM a_transactions as a_t 
							WHERE MONTH(a_t.date_processed) = (MONTH(CURDATE()) - 1)
						)";
						
			$this->con->query($query);
	}
	
	function commit_tokens() {
			
			//change carryover calls
			$query = "UPDATE users u
						SET u.tokens_balance = (SELECT t.amount 
							FROM transactions t
							WHERE type = 'carryover' AND
							MONTH(date_processed) = (MONTH(CURDATE())-1) AND
							u.uID = t.uID
							);";
			
			//change tokens_left to 0 for cash refund and donation users
			$query .= "UPDATE users u,transactions t
						SET u.tokens_balance = 0
						WHERE t.uID = u.uID
						AND (t.type = 'refund'
						OR t.type = 'donate')
						AND MONTH(t.date_processed) = (MONTH(CURDATE())-1);";
			
			$this->con->multi_query($query);
	}
					
}
