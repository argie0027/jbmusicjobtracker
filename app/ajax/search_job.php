<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $toSearch = trim(filter_input(INPUT_POST, 'toSearch', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql = "SELECT a.jobid, b.name FROM jb_joborder a, jb_customer b WHERE a.customerid = b.customerid AND  jobid LIKE '%".$toSearch."%'";
    $query = $db->ReadData($sql);
    if($query) {
    		echo "{\"response\":".json_encode($query) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error";
    }
}
?>