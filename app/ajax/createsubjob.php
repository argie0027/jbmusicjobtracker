<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $subjobid = trim(filter_input(INPUT_POST, 'subjobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $inseruser = "INSERT INTO `subjoborder`(`subjobid`, `mainjob`,`created_at`) VALUES ('".$subjobid."','".$jobid."',NOW())";
    $userquery = $db->InsertData($inseruser);
    if($userquery){
        echo "success";
    }else {
        echo $db->GetErrorMessage();
        echo "error d";
    }
}
?>