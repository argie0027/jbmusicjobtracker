<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $partid = trim(filter_input(INPUT_POST, 'partid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $check = "SELECT * FROM `jb_part` WHERE part_id='".$partid."' AND id='".$id."'";
    $checker = $db->ReadData($check);

    if ($checker) {
        echo "{\"response\":".json_encode($checker) . "}";
    } else {
        echo "error";
    }
}
?>