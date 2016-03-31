<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $brandid = trim(filter_input(INPUT_POST, 'brandid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $brandinfo = "SELECT brandname FROM jb_brands WHERE brandid = '" . $id . "'";
    $brandinfo = $db->ReadData($brandinfo);

    $sql = "DELETE FROM `jb_brands` WHERE brandid = '" . $brandid . "'";
    $query = $db->InsertData($sql);

    /* Insert History */
    $description = 'Brand Deleted';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$brandinfo[0]["brandname"]."','".dateToday()."')";
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