<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $permission = trim(filter_input(INPUT_POST, 'permission', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $checker = "SELECT * FROM `jb_user` WHERE id = '".$id."'";
    $query = $db->ReadData($checker);

    $permissionQuery = array();

    if( $permission ) {

        $permissiondata = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$id."'";
        $permissionQuery = $db->ReadData($permissiondata);

        foreach ($query as $key => $value) {
            $response['firstname'] = $value['firstname'];
            $response['lastname'] = $value['lastname'];
            $response['status'] = $value['status'];
        }

    } else {

        foreach ($query as $key => $value) {
        	$response['firstname'] = $value['firstname'];
        	$response['midname'] = $value['midname'];
        	$response['lastname'] = $value['lastname'];
        	$response['nicknake'] = $value['nicknake'];
        	$response['address'] = $value['address'];
        	$response['contact_number'] = $value['contact_number'];
        	$response['email'] = $value['email'];
        	$response['name'] = $value['name'];
            $response['username'] = $value['username'];
        	$response['job_title'] = $value['job_title'];
            $response['status'] = ucwords($value['status']);
        	$response['created_at'] = date_format(date_create($value['created_at']), 'd M Y');
        }
    }

    if($query) {
    		echo "{\"response\":".json_encode($response) . ", \"response2\":".json_encode($permissionQuery) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>