<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $techname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'techname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $nickname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $insertbranch = "UPDATE `jb_technicians` SET `name`='".$techname."',`email`='".$email."',`address`='".$address."',`number`='".$number."',`nickname`='".$nickname."' WHERE tech_id = '".$id."'";
    $query = $db->ExecuteQuery($insertbranch);
	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>