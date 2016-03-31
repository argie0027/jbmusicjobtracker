<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $customerID = trim(filter_input(INPUT_POST, 'customerID', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $joborderid = trim(filter_input(INPUT_POST, 'joborderid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $name = trim(ucwords(strtolower(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $customertype = trim(filter_input(INPUT_POST, 'customertype', FILTER_SANITIZE_FULL_SPECIAL_CHARS));


    $sql = "UPDATE jb_customer SET customer_type_id='". $customertype ."', name='". $name ."',email='". $email ."',address='". $address ."',number='". $number ."', `updated_at` = '".dateToday()."' WHERE customerid = '". $customerID ."'";
    $query = $db->InsertData($sql);

     /* Insert History */
    $description = 'Customer Edited';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$name."','".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

    if($query) {
        echo "success";
    }else {
    	echo "error out";
    }
}


?>