<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $toSearch = trim(filter_input(INPUT_POST, 'toSearch', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql = "SELECT * FROM `jb_technicians` WHERE name LIKE '%".$toSearch."%' AND isdeleted <> 1 AND status <> 1 AND tech_id <> 1";
    $query = $db->ReadData($sql);
    if($query) {
    		echo "{\"response\":".json_encode($query) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error";
    }
}
?>