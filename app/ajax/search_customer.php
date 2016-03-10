<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $mainoffice = trim(filter_input(INPUT_POST, 'mainoffice', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    if($mainoffice == "1"){
         $toSearch = trim(filter_input(INPUT_POST, 'toSearch', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $sql = "SELECT * FROM `jb_customer` WHERE name LIKE '%".$toSearch."%'";
        $query = $db->ReadData($sql);
        if($query) {
                echo "{\"response\":".json_encode($query) . "}";
        }else {
            echo $db->GetErrorMessage();
            echo "error";
        }
    }else{
        $toSearch = trim(filter_input(INPUT_POST, 'toSearch', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $sql = "SELECT * FROM `jb_customer` WHERE branchid = '".$branchid."' AND name LIKE '%".$toSearch."%' ";
        $query = $db->ReadData($sql);
        if($query) {
                echo "{\"response\":".json_encode($query) . "}";
        }else {
            echo $db->GetErrorMessage();
            echo "error";
        }
    }
}
?>