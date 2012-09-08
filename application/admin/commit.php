<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . '/classes/db_functions.php');

$activities_call = new Activities();

if(@$_GET['pass'] !== $activities_call->salt)
	die('<p>Permission Denied.</p><a href="/"><button>Go Back</button>');

echo 'success';
exit;

$activities_call->commit_reservations();
$activities_call->commit_tokens();