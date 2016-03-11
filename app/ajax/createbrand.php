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