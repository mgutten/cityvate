<?php
class Head {
//class to create the head section of a page (including 
//stylesheets and scripts)
	
	var $script = array("jquery-1.6.min");
	var $style= array();
	var $title;
	
		function __construct($login_status, $title) {
				
				//set title for page and add title css and 
				//stylesheet to respective arrays
				$this->title = $title;
				array_push($this->script,strtolower($title));
				array_push($this->style,strtolower($title));
				
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
				$form->input('text','username','','Username/email');
				$form->input('password','pw','','password');
				echo "<a href='forgot.php' id='forgot'>Forgot?</p></a>";
				$form->input('image','login','images/login_button.png');
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

class Form {
	
	//create form tag
	function __construct($action, $method, $onsubmit='') {
		
			echo "<form action='$action' method='$method' onsubmit='$onsubmit'>\n";
			
	}
	
	//create custom input for form
	function input($type,$name,$src='',$value=''){
			
			if(!empty($src))
					$src = "src='$src'";
			if(!empty($value))
					$value = "value='$value'";
					
			echo "<input type='$type' name='$name' id='input_$name' class='username' $src $value/>\n";
					
	}
	
	function close() {
			echo "</form>";
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
				$block .= "</div>\n";
							
				$block = str_replace('%s',$howorwhat,$block);
				
				echo $block;	
				
				$text_num=1;	

}

function quote_box($div_number,$text,$name,$city = 'SF') {
				
				$block = "<div class='quote_box' id='$div_number'>\n<p class='quote text'>\"$text\"</p>\n<p class='text quote_name'>$name, $city</p>\n</div>\n";
				
				echo $block;
				
}

			
?>




			