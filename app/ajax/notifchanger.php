<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

	$sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
	$query2 = $db->ReadData($sql2);

	$counterviewed = "SELECT * FROM notitemp WHERE  branch_id <> '0' AND isViewed <> '1' ORDER BY `created_at` DESC";
	$counterviewed = $db->ReadData($counterviewed);
	
	echo "{\"nofitication\":".json_encode($query2) . ", \"counter\":".count($counterviewed) . "}";

}
