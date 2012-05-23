<?php
include('head.php');

class Header {
	
	var $links=array('Home' => 'index.php','About' => 'about.php');
	var $drop=array('Login' =>'login.php');
	
	function __construct($login_status) {
			
			//if logged out, add to array of header links
			if($login_status == 'out') 
					$this->links['Signup'] = 'signup.php';
			
			//if logged in, change dropdown variable
			else 
					$this->drop = array('My Account' => 'myaccount.php');
					
			//display components of header
			echo "<body>\n<img src='images/city_back.jpg' class='bg'/><div id='body'>\n<div id='header_bar'>";
			
			//create links
			$this->link();
			
			//display logo
			echo "<a href='".$this->links['Home']."' alt='Home'><div id='logo'></div></a>";
			
			//close html and body TESTING *********************************************************
			echo '<div id="body_main"></div></div></body></html>';
			
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
					
					echo "<a href='$value'><div class='$class' id='drop'>$key</div></a>\n</div>\n";
					
			}
	
	//end link()
	}
	
}

$log = 'out';
$head = new Head($log,'Home');
$head->close();

$header = new Header($log);

				
?>


			