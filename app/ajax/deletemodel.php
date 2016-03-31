<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $modelid = trim(filter_input(INPUT_POST, 'modelid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $modelinfo = "SELECT modelname FROM jb_models WHERE modelid='".$id."'";
    $modelinfo = $db->ReadData($modelinfo);

    $sql = "DELETE FROM `jb_models` WHERE modelid = '" . $modelid . "'";
    $query = $db->InsertData($sql);
    if($query) {

    	/* Insert History */
        $description = 'Branch/Store Deleted';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$modelinfo[0]["modelname"]."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

    	echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>