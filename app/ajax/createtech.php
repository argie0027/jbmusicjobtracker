<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $techname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'techname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $nickname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $date_hired = trim(filter_input(INPUT_POST, 'datehired', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $tech_status = trim(filter_input(INPUT_POST, 'techstatus', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $insertbranch = "INSERT INTO `jb_technicians`(`name`, `email`, `address`, `number`, `nickname`, `status`,`created_at`, `date_hired`, `tech_status`) ".
                    " VALUES ('".$techname."','".$email."','".$address."','".$number."','".$nickname."', '0','".dateToday()."' , '".$date_hired."', '".$tech_status."')";
    $query = $db->InsertData($insertbranch);
    $lastbranchid = $db->GetLastInsertedID();
	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>