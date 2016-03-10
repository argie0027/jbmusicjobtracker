<?php 

include '../../include.php';

include '../include.php';
$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){



    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $selectoldremark = "SELECT remarks FROM jb_joborder WHERE jobid = '".$id."'";

     $query2  = $db->ReadData($selectoldremark);

     $str = $query2[0]['remarks'] . "<br> -- Disapprove<br>";

    $sql = "UPDATE jb_joborder SET repair_status = 'Ready for Claiming', remarks ='".$str."', jobclear = '1' WHERE jobid = '".$id."'";

    $query = $db->InsertData($sql);

   $notif = split(',', NOTIF);

    $nofi = "INSERT INTO `notitemp`(`jobid`, `branch_id`, `user`, `status_type`, `isViewed`,`created_at`) VALUES ('".$id."','".$_SESSION['Branchid']."','".$_SESSION['Branchname']."','".$notif[7]."','0',NOW())";
    $notif2 = $db->InsertData($nofi);
    
    $data['jobid'] = $id;
    $data['branch_id'] =$_SESSION['Branchid'];
    $data['name'] = $_SESSION['Branchname'];
    $data['message'] = $notif[7];
    $data['kanino'] = '0';

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
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description[5]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$id ."',NOW())";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

}

?>