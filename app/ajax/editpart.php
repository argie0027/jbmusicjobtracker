<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $partid = trim(filter_input(INPUT_POST, 'partid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $stocknumber = trim(filter_input(INPUT_POST, 'stocknumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $partname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'partname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $modelid = trim(filter_input(INPUT_POST, 'modelid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $cost = trim(filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $insertbranch = "UPDATE `jb_part` SET `stocknumber`='".$stocknumber."', `name`='".$partname."',`modelid`='".$modelid."',`cost`='".$cost."', `updated_at` = '".dateToday()."' WHERE part_id = '".$partid."'";
    $query = $db->ExecuteQuery($insertbranch);
    $lastbranchid = $db->GetLastInsertedID();

    /* Insert History */
    $description = 'Part Edited';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$partname."','".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>