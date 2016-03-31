<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $brand = trim(strtoupper(filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));

    $checker = "SELECT * FROM `jb_brands` WHERE brandname = '".$brand."'";
    $checkerQuery = $db->ReadData($checker);

    if( !$checkerQuery ) {
	    $insertbrand = "INSERT INTO `jb_brands`(`brandname`, `created_at`) VALUES ('".$brand."', '".dateToday()."')";
	    $query = $db->InsertData($insertbrand);

	    /* Insert History */
        $description = 'Brand Created';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$brand."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

		if($query){
	        echo "success";
		}else {
	        echo $db->GetErrorMessage();
	        echo "error";
	    }
	} else {
		echo "error";
	}
}
?>