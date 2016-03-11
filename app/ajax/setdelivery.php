<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $datedelivery = trim(filter_input(INPUT_POST, 'datedelivery', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $checker = "SELECT date_delivery FROM jb_joborder WHERE date_delivery = '".$datedelivery."' AND jobid = '".$id."'";
    $checkerQuery = $db->ReadData($checker);

    $response = array();

    if (!$checkerQuery) {

        $sql = "UPDATE jb_joborder SET date_delivery = '". $datedelivery . "', `updated_at` = '".dateToday()."' WHERE jobid = '".$id."'";
        $query = $db->InsertData($sql);
        if($query) {
            $response['status'] = 200;
            $response['message'] = 'Success';
        }else {
            $response['status'] = 101;
            $response['message'] = 'Failed to set date.';
        }

        /* Insert History */
        $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$id;
        $resultBranchId = $db->ReadData($getBranchId);

        $description = explode(",",ACT_NOTIF);
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description[3]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$id ."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

    } else {
        $response['status'] = 101;
        $response['date_delivery'] = true;
        $response['message'] = 'Failed to set date.';
    }

    echo json_encode($response);
    
}
?>