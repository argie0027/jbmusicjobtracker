<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $techid = trim(filter_input(INPUT_POST, 'techid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT * FROM `jb_technicians` WHERE tech_id = '".$techid."'";
    $query  = $db->ReadData($checker);
    if($query) {
        $checkifstat = "SELECT * FROM tech_statistic where techid = '".$techid."'";
        $tcheck  = $db->ReadData($checkifstat);
        if($tcheck){
            $queryjobs = "SELECT a.jobid, a.techid, b.totalpartscost, b.service_charges, b.total_charges, c.item, c.repair_status FROM tech_statistic a, jb_cost b, jb_joborder c WHERE a.jobid = b.jobid AND a.jobid = c.jobid AND a.techid = '".$techid."'";
        }else{
           $queryjobs = "SELECT b.jobid, b.totalpartscost, b.service_charges, b.total_charges, c.item, c.repair_status FROM jb_cost b, jb_joborder c WHERE b.jobid = c.jobid AND c.technicianid = '".$techid."'";
        }
    	$query3  = $db->ReadData($queryjobs);


    	// $querycurrenttasks = "SELECT jobid FROM `jb_joborder` WHERE technicianid = '".$techid."'";

    	// $query2  = $db->ReadData($querycurrenttasks);

        $querycurrenttasks = "SELECT * FROM `jb_joborder` WHERE technicianid = '".$techid."' ORDER BY created_at DESC";
        $query2  = $db->ReadData($querycurrenttasks);
        $currenttast = "";

        if($query3) {
            if($query3[0]['repair_status'] == 'Done-Ready for Delivery' || $query3[0]['repair_status'] == 'Claimed'){
                $currenttast['jobid'] = "-";
            } else {
                $currenttast = $query3[0]['jobid'] . " (".$query3[0]['item'] . ")"; 
            }
        } else {
            $currenttast = "-";
        }

    	echo "{\"response\":".json_encode($query) . ", \"response2\":".json_encode($currenttast) . ", \"response3\":".json_encode($query3) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>