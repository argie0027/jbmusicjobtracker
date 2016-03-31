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

        $branchinfo = "SELECT branch_name FROM jb_branch WHERE branch_id='".$id."'";
        $branchinfo = $db->ReadData($branchinfo);

        /* Insert History */
        $description = 'Branch/Store Deleted';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$branchinfo[0]["branch_name"]."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

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