<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $diagnosisinfo = "SELECT diagnosis FROM jb_diagnosis WHERE id = '" . $id . "'";
    $diagnosisinfo = $db->ReadData($diagnosisinfo);

    $sql = "DELETE FROM `jb_diagnosis` WHERE id = '" . $id . "'";
    $query = $db->InsertData($sql);

    /* Insert History */
    $description = 'Diagnosis Deleted';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$diagnosisinfo[0]["diagnosis"]."','".dateToday()."')";
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