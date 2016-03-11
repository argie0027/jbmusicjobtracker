<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $feedback = trim(filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $adminemail = trim(filter_input(INPUT_POST, 'adminemail', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $type = trim(filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $insertbranch = "SELECT * FROM `jb_email` WHERE branchid = '".$branchid."'";
    $query = $db->ReadData($insertbranch);
    $branchtype = "";

	if($query){
        if($type == '1'){
            $branchtype = "1";
        }else{
            $branchtype = "0";
            $branchid = "-1";
        }
        $addemail = "UPDATE `jb_email` SET `feedback`='".$feedback."',`admin`='".$adminemail."', `updated_at` = '".dateToday()."' WHERE branchid = '".$branchid."'";
        $addemailquery = $db->ExecuteQuery($addemail);
        
        if($addemailquery){
            echo "success";
        }else{
            echo $db->GetErrorMessage();
            echo "error inner";
        }
	}else {
        if($type == '1'){
            $branchtype = "1";
        }else{
            $branchtype = "0";
            $branchid = "-1";
        }
          $addemail = "INSERT INTO `jb_email`(`feedback`, `admin`, `branchid`, `isbranch`,`created_at`) ".
        " VALUES ('".$feedback."','".$adminemail."','".$branchid."','".$branchtype."','".dateToday()."')";
        $addemailquery = $db->InsertData($addemail);
        if($addemailquery){
            echo "success";
        }else{
            echo $db->GetErrorMessage();
            echo "error inner";
        }
    }
}
?>