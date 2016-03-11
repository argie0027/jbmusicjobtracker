<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $otherremarks = trim(filter_input(INPUT_POST, 'otherremarks', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $techid = trim(filter_input(INPUT_POST, 'techid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $selectoldremark = "SELECT remarks, technicianid FROM jb_joborder WHERE jobid = '".$id."'";
    $query2  = $db->ReadData($selectoldremark);

    $str = $query2[0]['remarks'] . "<br> -- ".$otherremarks."<br>";

    //$sql = "UPDATE jb_joborder SET repair_status = 'Ready for Claiming', remarks ='".$str."', jobclear = '1' WHERE jobid = '".$id."'";
    
    $sql = "UPDATE jb_joborder SET repair_status = 'Done-Ready for Delivery', remarks ='".$str."', jobclear = '1', `updated_at` = '".dateToday()."' WHERE jobid = '".$id."'";
    $query = $db->InsertData($sql);
    
    $update_tech = "UPDATE jb_technicians SET `status` = '0', `updated_at` = '".dateToday()."' WHERE `tech_id` = '". $query2[0]['technicianid']."'";
    $update_techstatus = $db->ExecuteQuery($update_tech); 

    $notif = split(',', NOTIF);
    $nofi = "INSERT INTO `notitemp`(`jobid`, `branch_id`, `user`, `status_type`, `isViewed`, `created_at`) VALUES ('".$id."','".$_SESSION['Branchid']."','".$_SESSION['name']."','".$notif[7]."','0', '".dateToday()."')";
    $notif = $db->InsertData($nofi);

    if($notif) {
        echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }

    /* Insert History */
    $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$id;
    $resultBranchId = $db->ReadData($getBranchId);

    $description = explode(",",ACT_NOTIF);
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`, `created_at`)". " VALUES ('".$description[12]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$id ."', '".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */
}
?>