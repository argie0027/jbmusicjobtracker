<?php 

include '../../include.php';

include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $getTechId = "SELECT technicianid FROM jb_joborder WHERE jobid = ".$id;
    $getTechIdQuery = $db->ReadData($getTechId);

    if( $getTechIdQuery ) {
        $update_tech = "UPDATE jb_technicians SET `status` = '1' WHERE `tech_id` = '". $getTechIdQuery[0]['technicianid']."'";
        $update_techstatus = $db->ExecuteQuery($update_tech); 
    }

    $sql = "UPDATE jb_joborder SET repair_status = 'Ongoing Repair', date_delivery = '0000-00-00' WHERE jobid = '".$id."'";
    $query = $db->InsertData($sql);
    $notif = split(',', NOTIF);
    $nofi = "INSERT INTO `notitemp`(`jobid`, `branch_id`, `user`, `status_type`, `isViewed`,`created_at`) VALUES ('".$id."','".$_SESSION['Branchid']."','".$_SESSION['Branchname']."','".$notif[4]."','0',NOW())";
    $dsdf = $db->InsertData($nofi);
    $data['jobid'] = $id;
    $data['name'] = $_SESSION['Branchname'];
    $data['message'] = 'Set Ongoing Repair';
    $pusher->trigger('test_channel', 'my_event', $data);
    if($query) {
        echo "success";
    }else {
    	echo "error out";
    }

    /* Insert History */
    $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$id;
    $resultBranchId = $db->ReadData($getBranchId);

    $description = explode(",",ACT_NOTIF);
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description[11]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$id ."',NOW())";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */
}
?>