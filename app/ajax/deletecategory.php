<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $sql = "DELETE FROM `jb_partscat` WHERE cat_id = '" . $id . "'";
    $query = $db->InsertData($sql);

	$sqlSubcat = "SELECT * FROM `jb_partssubcat` WHERE cat_id = '".$id."'";
	$querySubcat = $db->ReadData($sqlSubcat);
	foreach ($querySubcat as $key => $value) {
		$sql = "DELETE FROM `jb_partssubcat` WHERE cat_id = '" . $value['cat_id'] . "'";
    	$query = $query = $db->InsertData($sql);
	}
	
    if($query) {
    		echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>