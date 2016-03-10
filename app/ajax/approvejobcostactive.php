<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    
    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $reference = trim(filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $query = "UPDATE jb_joborder SET repair_status = 'Approved', referenceno = '".$reference."' WHERE jobid = '".$jobid."'";
    $updatejob = $db->ExecuteQuery($query); 
    if($updatejob){
        $query1 = "UPDATE jb_cost SET ispaid = '1' WHERE jobid = '".$jobid."'";
        $updatecost = $db->ExecuteQuery($query1);
        if($updatecost){
            echo "success";
        }else{
            echo "error cost";
        }
    }else{
        echo "error job order";
    }
}
?>