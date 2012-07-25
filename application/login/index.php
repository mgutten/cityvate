<?php
	/* location of file: application/login/index.php */

	$pos=0;
	
	$head = new Head_signup('Login',1);
	$head->close();
	
	$header = new Header();
	
	$body = new Body_signup();
	$body->background('Login',$pos);
	
	//create form for login that will run js fn validate() for id _main
	$form = new Form("login/login_authenticate.php","POST","return validate(\"login_main\")","login_main");
	
	$class = 'drop text';
	
?>

<p class="box_title text box_title_close login">Username/Email</p>

	<?php
		//if display username if session var has been set previously
        (!empty($_SESSION['user']['username']) ? $user = $_SESSION['user']['username'] : $user = '');
		
		//if username was not found in db, change color of username/pw back
        (!empty($_SESSION['user']['username_fail']) ? $class .= ' red_back' : '');
        $form->input('text','username_main',$class,'',$user);
    ?>

<p class='text box_title_lower red' id='username_lower'>
	<?php 
		//if username was found but password incorrect, display notification
		echo (!empty($_SESSION['user']['username_fail']) ? "\"" . $_SESSION['user']['username_fail'] . "\" does not exist" : '');
    ?>

</p>
<p class="box_title text" id='password_title'>Password</p>
    
    <?php
	//if username was found but password incorrect, change color of pw input
        (!empty($_SESSION['user']['password_fail']) ? $class .= ' red_back' : '');
        $form->input('password','password_main',$class);
    ?>

<p class='text box_title_lower red' id='password_lower'>

	<?php 
	//if username was found but password incorrect, display notification
        echo (!empty($_SESSION['user']['password_fail']) ? "Incorrect password" : '');
    ?>

</p>
<p class='text inspire'>"It is never too late to be what you might have been."</p>
<p class='text inspire' id='author'>-George Eliot</p>
<a href='/application/forgot' class='forgot text'>Forgot your password?</a>

<?php
$form->input('image','submit','submit','/images/login_button_green.png');
$form->close();

//empty fail session vars
if(!empty($_SESSION['user']['username_fail']))
	unset($_SESSION['user']['username_fail']);

if(!empty($_SESSION['user']['password_fail']))
	unset($_SESSION['user']['password_fail']);
	
