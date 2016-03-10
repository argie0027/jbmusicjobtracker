<?php 

include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $history_id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql_jobhistory = "SELECT * FROM jb_history WHERE id = ".$history_id;
    $query_jobhistory = $db->ReadData($sql_jobhistory);

    //check if main branch create job order
    $sql_joborder = "SELECT branchid FROM jb_joborder WHERE jobid = ".$query_jobhistory[0]['jobnumber'];
    $query_joborder = $db->ReadData($sql_joborder);

    $sql = "SELECT a.jobid, a.customerid, a.branchid, a.isunder_warranty,b.*";

    if( $query_joborder[0]['branchid'] != 0 ) $sql .= ', c.branch_id, c.branch_name, c.address as branch_address, c.contactperson as contact_person, c.email as contact_email, c.number as branch_number';
    $sql .= ' FROM jb_joborder a, jb_customer b';
    if( $query_joborder[0]['branchid'] != 0 ) $sql .= ', jb_branch c';
    $sql .= ' WHERE a.customerid = b.customerid';
    if( $query_joborder[0]['branchid'] != 0 ) $sql .= ' AND a.branchid = c.branch_id';
    $sql .= ' AND a.jobid = '.$query_jobhistory[0]['jobnumber'];

    $query = $db->ReadData($sql);
    if($query) {
        echo "{\"response\":".json_encode($query) . ",\"response2\":".json_encode($query_jobhistory) . "}";
    }else {

        echo $db->GetErrorMessage();
        echo "error out";
    }
}

?>