<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $category = trim(filter_input(INPUT_POST, 'categoryid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $sql = "SELECT * FROM jb_partssubcat WHERE cat_id = '".$category."' ORDER BY subcat_id ASC";
    $query  = $db->ReadData($sql);

    if($query) {
    	echo "{\"response\":".json_encode($query) . "}";
    } else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>