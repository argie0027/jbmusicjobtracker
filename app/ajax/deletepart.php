<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $updatebranch = "DELETE FROM `jb_part` WHERE part_id = '".$id."'";
    $query = $db->ExecuteQuery($updatebranch);
	if($query){
            echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>