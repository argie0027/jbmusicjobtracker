<?php
	include '../../include.php';
	include '../include.php';
	$request = filter_input(INPUT_POST, 'action');
	$password = hash('sha256', HASH_AUSER.trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
	if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
		if($password == $_SESSION['password']) {
			echo "success";
		}else {
			echo "false";
		}
	}
?>