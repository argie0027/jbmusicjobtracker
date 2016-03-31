<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $batch_quantity = trim(filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $partid = trim(filter_input(INPUT_POST, 'partid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $check = "SELECT * FROM `jb_part` WHERE part_id='".$partid."' AND id='".$id."'";
    $checker = $db->ReadData($check);

    if ($checker) {

        if($checker[0]['quantity'] == $checker[0]['bacth_quantity']) {
            $update = "UPDATE `jb_part` SET `quantity`='".$batch_quantity."',`bacth_quantity`='".$batch_quantity."',`updated_at`='".dateToday()."' WHERE part_id='".$partid."' AND id='".$id."'";
            $query = $db->ExecuteQuery($update);
            echo "success";
        } else {
            $totalquantity = $checker[0]['quantity'] + ($batch_quantity - $checker[0]['bacth_quantity']);

            if($totalquantity > 0) {
                $update = "UPDATE `jb_part` SET `quantity`='".$totalquantity."',`bacth_quantity`='".$batch_quantity."',`updated_at`='".dateToday()."' WHERE part_id='".$partid."' AND id='".$id."'";
                $query = $db->ExecuteQuery($update);
                echo "success";
            } else {
                echo "Internal Server Error!";
            }
        }
    } else {
        echo "Internal Server Error!";
    }
}
?>