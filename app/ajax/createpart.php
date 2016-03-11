<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $isExisting = trim(filter_input(INPUT_POST, 'isExisting', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    if ( $isExisting == "1" ) {

        $quantity = trim(filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $idpart = trim(filter_input(INPUT_POST, 'idpart', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        
        $now = new DateTime();
        $selecttet = "SELECT * FROM `jb_part` WHERE part_id = '".$idpart."'";
        $querys  = $db->ReadData($selecttet);
        $insertbranch = "INSERT INTO `jb_part`(`stocknumber`, `name`,`part_id`,`modelid`, `quantity`, `cost`,`date`,`bacth_quantity`,`created_at`) ".
                        " VALUES ('".$querys[0]['stocknumber']."', '".$querys[0]['name']."','".$idpart."','".$querys[0]['modelid']."','".$quantity."','".$querys[0]['cost']."','".$now->format('Y-m-d')."','".$quantity."','".dateToday()."')";    

        $query = $db->InsertData($insertbranch);
        $lastbranchid = $db->GetLastInsertedID();
        if($query){
            echo "success";
        }else {
            echo $db->GetErrorMessage();
            echo "error inner";
        }

    } else {

        $stocknumber = trim(filter_input(INPUT_POST, 'stocknumber', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $partname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'partname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
        $modelid = trim(filter_input(INPUT_POST, 'modelid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $quantity = trim(filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $cost = trim(filter_input(INPUT_POST, 'cost', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $getlastid = $db->GetLastInsertedID();
        $partid = $utility->random_string("1234567890",8);
        $now = new DateTime();

        $insertbranch = "INSERT INTO `jb_part`(`stocknumber`, `name`,`part_id`,`modelid`, `quantity`, `cost`,`date`,`bacth_quantity`,`created_at`) ".
                        " VALUES ('".$stocknumber."', '".$partname."','".$partid."','".$modelid."','".$quantity."','".$cost."','".$now->format('Y-m-d')."','".$quantity."','".dateToday()."')"; 
        $query = $db->InsertData($insertbranch);
        $lastbranchid = $db->GetLastInsertedID();
        if($query){
            echo "success";
        }else {
            echo $db->GetErrorMessage();
            echo "error inner";
        }
    }
}
?>