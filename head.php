<?php 

class Head {
//class to create the head section of a page (including 
//stylesheets and scripts)
	
	var $script = "jquery.min.1.6";
	var $title;
	
		function __construct($login_status, $title) {
				
				$this->title = $title;
				
				//echo up to <head> of doc
				echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n<html xmlns='http://www.w3.org/1999/xhtml'>\n<head>\n";
			
				
				//define meta tags, adding title to keywords
				$meta_desc = 'Cityvate is your personal activity agent, helping you to find awesome local and motivating you to get out and try something new!';
				$meta_key = 'cityvate, fun life, exciting life, local activities, san francisco activities, '.$title;
					
				//if header is for logged out page, display
				//appropriate css stylesheet else logged
				//in stylesheet
				if($login_status == 'out') 
					$header = 'header_out';
				
				else
					
					$header = 'header_in';
					
				//echo base <meta>, styles, scripts
				echo "<meta http-equiv='Content-Type' name='description' content='".$meta_desc."'  />\n<meta http-equiv='Content-Type' name='keywords' content='".$meta_key."'  />\n";
				$this->style($header);
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



?>
