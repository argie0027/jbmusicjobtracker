<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

	    $sql2 = "SELECT a.*, b.jobid as joborderid, b.branchid as branchidmo FROM notitemp a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid = '".$_SESSION['Branchid']."' AND a.branch_id = '0' ORDER BY `created_at` DESC";
        $query2 = $db->ReadData($sql2);

        $counterviewed = "SELECT a.*, b.jobid as joborderid, b.branchid as branchidmo FROM notitemp a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid = '".$_SESSION['Branchid']."' AND a.branch_id = '0' AND isViewed <> '1' ORDER BY `created_at` DESC ";
        $counterviewed = $db->ReadData($counterviewed);

	
	echo "{\"nofitication\":".json_encode($query2) . ", \"counter\":".count($counterviewed) . "}";

}
