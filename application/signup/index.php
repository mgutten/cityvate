<?php
/* location of file: signup/index.php */


$head = new Head('Signup');
$head->close();

$header = new Header();

$body = new Body();

$form = new Form(array('action'=>'/signup/signup_set',
						'method'=>'POST'));
echo $form->input(array('type'=>'image',
					'name'=>'package',
					'id'=>'budget',
					'class'=>'select',
					'src'=>'/images/signup/select_button.png',
					'value'=>'budget'));

echo $form->input(array('type'=>'image',
					'name'=>'package',
					'id'=>'basic',
					'class'=>'select',
					'src'=>'/images/signup/select_button.png',
					'value'=>'basic'));
				
echo $form->input(array('type'=>'image',
					'name'=>'package',
					'id'=>'premium',
					'class'=>'select',
					'src'=>'/images/signup/select_button.png',
					'value'=>'premium'));
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

<a href="preview/member.php" alt="Preview Cityvate"><img src="/images/signup/preview.png" id="preview"/></a>

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

	