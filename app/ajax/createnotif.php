<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    
    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $useraccount = trim(filter_input(INPUT_POST, 'useraccount', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $typenotif = trim(filter_input(INPUT_POST, 'typenotif', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

     $sql = "INSERT INTO `notitemp`(`jobid`, `branch_id`, `user`, `status_type`, `isViewed`,`created_at`) VALUES ('".$jobid."','".$branchid."','".$useraccount."','".$typenotif."','0','".dateToday()."')";

    $query = $db->InsertData($sql);
    if($query) {
        echo "success";
    }else {
        echo "error out";
    }

}
?>