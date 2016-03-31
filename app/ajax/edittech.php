<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $techname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'techname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $nickname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $date_hired = trim(filter_input(INPUT_POST, 'datehired', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $tech_status = trim(filter_input(INPUT_POST, 'techstatus', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $insertbranch = "UPDATE `jb_technicians` SET `name`='".$techname."',`email`='".$email."',`address`='".$address."',`number`='".$number."',`nickname`='".$nickname."',`date_hired`='".$date_hired."',`tech_status`='".$tech_status."',`updated_at` = '".dateToday()."' WHERE tech_id = '".$id."'";
    $query = $db->ExecuteQuery($insertbranch);


    /* Insert History */
    $description = 'Technician Edited';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$techname."','".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>