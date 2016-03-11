<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $sql = "UPDATE `jb_branch` SET isdeleted = 1, `updated_at` = '".dateToday()."' WHERE branch_id = '".$id."'";
    
    $query = $db->InsertData($sql);
    if($query) {
	 			$sql = "UPDATE `jb_user` SET isdeleted = 1, `updated_at` = '".dateToday()."' WHERE branch_id = '".$id."'";

    	 	   $query2 = $db->InsertData($sql);
    	 	   if($query2){
    				echo "success";
    	 	   }else{
    				echo "error";
    	 	   }	
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}


?>