<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $customerinfo = "SELECT name FROM jb_customer WHERE customerid='".$id."'";
    $customerinfo = $db->ReadData($customerinfo);

    $sql = "DELETE FROM `jb_customer` WHERE customerid = '".$id."'";
    $query = $db->InsertData($sql);

    /* Insert History */
    $description = 'Customer Deleted';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$customerinfo[0]["name"]."','".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

    if($query) {
    		echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}


?>