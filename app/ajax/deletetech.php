<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $updatebranch = "UPDATE `jb_technicians` SET `isdeleted`='1', `updated_at` = '".dateToday()."' WHERE tech_id = '".$id."'";
    $query = $db->ExecuteQuery($updatebranch);

    $techinfo = "SELECT name FROM jb_technicians WHERE tech_id='".$id."'";
    $techinfo = $db->ReadData($techinfo);

     /* Insert History */
    $description = 'Technician Deleted';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$techinfo[0]['name']."','".dateToday()."')";
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