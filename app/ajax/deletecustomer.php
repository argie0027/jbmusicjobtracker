<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql = "DELETE FROM `jb_customer` WHERE customerid = '".$id."'";
    
    $query = $db->InsertData($sql);
    if($query) {
    		echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}


?>