<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $partid = trim(filter_input(INPUT_POST, 'partid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $stocknumber = trim(filter_input(INPUT_POST, 'stocknumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $partname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'partname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $modelid = trim(filter_input(INPUT_POST, 'modelid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $cost = trim(filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $insertbranch = "UPDATE `jb_part` SET `stocknumber`='".$stocknumber."', `name`='".$partname."',`modelid`='".$modelid."',`cost`='".$cost."' WHERE part_id = '".$partid."'";
    $query = $db->ExecuteQuery($insertbranch);
    $lastbranchid = $db->GetLastInsertedID();

	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>