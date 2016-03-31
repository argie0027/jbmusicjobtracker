<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $techid = trim(filter_input(INPUT_POST, 'techid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT *, DATE_FORMAT(date_hired, '%d %b %Y') as date_hired FROM `jb_technicians` WHERE tech_id = '".$techid."'";
    $query  = $db->ReadData($checker);
    if($query) {
        $checkifstat = "SELECT * FROM tech_statistic where techid = '".$techid."'";
        $tcheck  = $db->ReadData($checkifstat);


        $repaired = "(SELECT COUNT(jobclear) FROM jb_joborder WHERE jobclear = 0 AND technicianid = '".$techid."' AND repair_status != 'Waiting for SOA Approval' AND repair_status != 'Approved' ) AS repaired";
        $canrepair = "(SELECT COUNT(jobclear) FROM jb_joborder WHERE jobclear = 1 AND technicianid = '".$techid."' AND repair_status != 'Waiting for SOA Approval' AND repair_status != 'Approved' ) AS cantrepair";

        if($tcheck){
            $queryjobs = "SELECT a.jobid, a.techid, b.totalpartscost, b.service_charges, b.total_charges, b.less_deposit, b.less_discount, c.item, c.repair_status, c.jobclear, DATE_FORMAT(a.date_start, '%d %b %Y') as date_start , DATE_FORMAT(a.date_done, '%d %b %Y') as date_done, ".$repaired.", ".$canrepair." FROM tech_statistic a, jb_cost b, jb_joborder c WHERE a.jobid = b.jobid AND a.jobid = c.jobid AND a.techid = '".$techid."'";
        }else{
            $queryjobs = "SELECT b.jobid, b.totalpartscost, b.service_charges, b.total_charges, b.less_deposit, b.less_discount, c.item, c.repair_status, c.jobclear FROM jb_cost b, jb_joborder c WHERE b.jobid = c.jobid AND c.technicianid = '".$techid."' AND b.repair_status != 'Waiting for SOA Approval' AND b.repair_status != 'Approved'";
        }
    	$query3 = $db->ReadData($queryjobs);


    	// $querycurrenttasks = "SELECT jobid FROM `jb_joborder` WHERE technicianid = '".$techid."'";

    	// $query2  = $db->ReadData($querycurrenttasks);

        $querycurrenttasks = "SELECT * FROM `jb_joborder` WHERE technicianid = '".$techid."' ORDER BY created_at DESC";
        $query2 = $db->ReadData($querycurrenttasks);
        $currenttast = "";

        if($query2) {
            if($query2[0]['repair_status'] != 'Ongoing Repair'){
                $currenttast['jobid'] = "-";
            } else {
                $currenttast['jobid'] = $query2[0]['jobid'] . " (".$query2[0]['item'] . ")";
            }
        } else {
            $currenttast['jobid'] = "-";
        }

    	echo "{\"response\":".json_encode($query) . ", \"response2\":".json_encode($currenttast) . ", \"response3\":".json_encode($query3) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>