<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $diagnosis = trim(ucwords(strtolower(filter_input(INPUT_POST, 'diagnosis', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    
    $checker = "SELECT * FROM `jb_diagnosis` WHERE id = '".$id."'";
    $checkerQuery = $db->ReadData($checker);

    $sql = "UPDATE `jb_diagnosis` SET `diagnosis` = '".$diagnosis."', `updated_at` = '".dateToday()."' WHERE `id` = '".$id."'";

    if( $checkerQuery[0]['diagnosis'] == $diagnosis ) {
    	$query = $db->ExecuteQuery($sql);
    } else {
    	$newName = "SELECT * FROM `jb_diagnosis` WHERE diagnosis = '".$diagnosis."'";
    	$newNameQuery = $db->ReadData($newName);

    	if( !$newNameQuery ) {
    		$query = $db->ExecuteQuery($sql);
    	}
    }

    if( isset($query) ) {

        /* Insert History */
        $description = 'Diagnosis Edited';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$diagnosis."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */
    
    	echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error";
    }



}
?>