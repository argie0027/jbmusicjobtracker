<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $name = trim(ucwords(strtolower(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $customertype = trim(filter_input(INPUT_POST, 'customertype', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql = "INSERT INTO jb_customer(`branchid`,`customer_type_id`,`name`, `email`, `address`, `number`,`created_at`) " . 
                    "VALUES ('".$branchid."','".$customertype."','".$name."','".$email."','".$address."','".$number."', '".dateToday()."')";
    $query = $db->InsertData($sql);
    $customerid = $db->GetLastInsertedID();

    /* Insert History */
    $description = 'Customer Created';
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