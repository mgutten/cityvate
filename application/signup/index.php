<?php
/* location of file: signup/index.php */


$head = new Head('Signup');
$head->close();

$header = new Header();

$body = new Body();

$form = new Form('signup/signup_set','POST');
$form->input_diff('image','package','budget','select',$GLOBALS['file_adj'] . 'images/signup/select_button.png','budget');
$form->input_diff('image','package','basic','select',$GLOBALS['file_adj'] . 'images/signup/select_button.png','basic');
$form->input_diff('image','package','premium','select',$GLOBALS['file_adj'] . 'images/signup/select_button.png','premium');
$form->close();

?>

<div onclick='$(".why").toggle()' class="text" id="why_button">
	Why pay up front?
</div> 

<div class='fade_box' id='fade1'>
	<div class='text title' id='text1'>
    	Types of Activities
    </div>
</div>

<a href="preview/member.php" alt="Preview Cityvate"><img src="<?php echo $GLOBALS['file_adj'];?>images/signup/preview.png" id="preview"/></a>

<div class='fade_box' id='fade2'>
	<div class='text title' id='text2'>
    	How it Works
    </div>
</div>

<?php
$why = new Alert('why','why_alert');

if(empty($_SESSION['signup_visit'])) {
	$calm = new Alert('calm','calm_alert');
	$_SESSION['signup_visit'] = 'set';
}

//set signup session var as blank array
$_SESSION['signup']=array();

	