<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT * FROM `jb_technicians` WHERE  tech_id = '".$id."'";
    $query  = $db->ReadData($checker);
    if($query) {
    	echo "{\"response\":".json_encode($query) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>