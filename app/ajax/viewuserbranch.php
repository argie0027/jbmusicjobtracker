<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT * FROM `jb_user` WHERE branch_id = '".$id."' AND position = '2'";
    // echo $checker;
    $query  = $db->ReadData($checker);
    if($query) {
        $qu = "SELECT * FROM `jb_branch` WHERE branch_id = '".$id."' ";
        $queryin  = $db->ReadData($qu);
    		echo "{\"response\":".json_encode($query) . ",\"response2\":".json_encode($queryin) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>