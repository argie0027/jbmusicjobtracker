<?php
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
	$password = sha1( hash('sha256', HASH_AUSER.clearspaces(trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS)))) );
	$reset = trim(filter_input(INPUT_POST, 'reset', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$member = trim(filter_input(INPUT_POST, 'member', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

	$checker = "SELECT * FROM jb_user WHERE forgot_code='".$reset."'";
	$checkerQuery = $db->ReadData($checker);

	if ( $checkerQuery ) {
		if ( sha1($checkerQuery[0]['email']) ==	$member ) { 
			$sql = "UPDATE `jb_user` SET `password`='".$password."',`forgot_code`='', `updated_at` = '".dateToday()."' WHERE forgot_code='".$reset."'";
			$query = $db->InsertData($sql);

			$response = array();
			
			if( $query ) {
		       $response['status'] = 200;
		       $response['message'] = 'Success';
		    } else {
		       $response['status'] = 101;
		       $response['message'] = 'Failed';
		    }
		} else {
			$response['status'] = 101;
		    $response['message'] = 'Failed';
		}
	} else {
		$response['status'] = 101;
		$response['message'] = 'Failed';
	}

    echo json_encode($response);
}

?>