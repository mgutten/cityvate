<?php

$con = new mysqli('localhost','root','','cityvate_main');
$query = 'SELECT * FROM users';
$result = $con->query($query);


while($row = $result->fetch_array()){
	
	echo $row['username'];
	
}
?>
