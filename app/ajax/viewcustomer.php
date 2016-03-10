<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT a.*, b.branch_name, b.contactperson, b.email as branch_email, b.address as branch_address, b.number as branch_number, b.customer_type FROM jb_customer a, jb_branch b WHERE customerid = '" .$id. "' AND a.branchid = b.branch_id ";
    // echo $checker;
    $query  = $db->ReadData($checker);
    if($query) {
        $qu = "SELECT a.*, b.diagnosis, c.name FROM jb_joborder as a, jb_diagnosis as b, jb_technicians as c WHERE a.technicianid = c.tech_id AND a.diagnosis =  b.id AND a.customerid = '".$id."' ORDER BY `created_at` DESC";
        $queryin  = $db->ReadData($qu);
    		echo "{\"response\":".json_encode($query) . ",\"response2\":".json_encode($queryin) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>