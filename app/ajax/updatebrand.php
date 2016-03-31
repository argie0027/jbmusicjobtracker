<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $brandid = trim(filter_input(INPUT_POST, 'brandid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $brandname = trim(strtoupper(filter_input(INPUT_POST, 'brandname', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    
    $checker = "SELECT * FROM `jb_brands` WHERE brandid = '".$brandid."'";
    $checkerQuery = $db->ReadData($checker);

    $sql = "UPDATE `jb_brands` SET `brandname` = '".$brandname."', `updated_at` = '".dateToday()."' WHERE `brandid` = '".$brandid."'";

 	if( $checkerQuery[0]['brandname'] == $brandname ) {
    	$query = $db->ExecuteQuery($sql);
    } else {
    	$newName = "SELECT * FROM `jb_brands` WHERE brandname = '".$brandname."'";
    	$newNameQuery = $db->ReadData($newName);

    	if( !$newNameQuery ) {
    		$query = $db->ExecuteQuery($sql);
    	}
    }

    if($query) {

        /* Insert History */
        $description = 'Brand Edited';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$brandname."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

    	echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>