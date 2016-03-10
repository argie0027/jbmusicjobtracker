<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $subjobid = trim(filter_input(INPUT_POST, 'subjobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $updatebranch = "DELETE FROM `subjoborder` WHERE subjobid = '".$subjobid."'";
    $query = $db->ExecuteQuery($updatebranch);
    if($query){
            echo "success";
    }else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>