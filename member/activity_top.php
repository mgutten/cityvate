<?php
session_start();
require_once('../db_functions.php');
require_once('../html_display.php');

$new_month = $_POST['month'];

activity_home_top($new_month);

?>