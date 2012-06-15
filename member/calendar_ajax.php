<?php
session_start();
require_once('../html_display.php');
require_once('../db_functions.php');

$selected_month = $_POST['month'];
$year = $_POST['year'];

$calendar = new Calendar();
$calendar->calendar_calls($selected_month,$year);

$days=array('Su','M','T','W','Th','F','S');

$calendar->create_calendar();
?>