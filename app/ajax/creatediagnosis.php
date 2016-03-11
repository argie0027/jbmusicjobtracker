<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $diagnosis = trim(ucwords(strtolower(filter_input(INPUT_POST, 'diagnosis', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $checker = "SELECT * FROM `jb_diagnosis` WHERE diagnosis = '".$diagnosis."'";
    $checkerQuery = $db->ReadData($checker);

    if( !$checkerQuery ) {

        $insert = "INSERT INTO `jb_diagnosis`(`diagnosis`,`created_at`) VALUES ('".$diagnosis."','".dateToday()."')";
        $query = $db->InsertData($insert);

        $lastbranchid = $db->GetLastInsertedID();
    	if($query){
            echo "success";
    	}else {
            echo $db->GetErrorMessage();
            echo "error inner";
        }

    } else {
        echo "error";
    }
}
?>