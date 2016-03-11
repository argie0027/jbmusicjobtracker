<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $username = clearspaces(trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    $password = sha1( hash('sha256', HASH_AUSER.clearspaces(trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS)))) );
    $jobtitle = trim(ucwords(strtolower(filter_input(INPUT_POST, 'jobtitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $firstname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $lastname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $midname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'midname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $fullname = $firstname.' '.$midname.' '.$lastname;
    $nickname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $email = trim(filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $contact = trim(filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $branchname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'branchname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $branchaddress = trim(ucwords(strtolower(filter_input(INPUT_POST, 'branchaddress', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $branchemail = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    // check username if existing
    $checker = "SELECT * FROM jb_user WHERE username='".$username."'";
    $checkerQuery = $db->ReadData($checker);

    $response = array();

    if ( $checkerQuery ) {
        $response['status'] = 101;
        $response['username'] = true;
    } else {
        $validfirstname = checkspecialchars($firstname);
        $validmidname = checkspecialchars($midname);
        $validlastname = checkspecialchars($lastname);
        $validnickname = checkspecialchars($nickname);
        $validbranchname = checkspecialchars($branchname);

        $emailChecker = "SELECT * FROM jb_user WHERE email='".$email."'";
        $emailQuery = $db->ReadData($emailChecker);

        if( $emailQuery ) {
            $response['emailaddress'] = true;
        }

        if( !$validfirstname && !$validmidname && !$validlastname && !$validnickname && !$validbranchname && !$emailQuery ) {

            $insertbranch = "INSERT INTO `jb_branch`(`branch_name`, `contactperson`, `email`, `address`, `number`, `customer_type`, `created_at`) " .
               " VALUES ('".$branchname."','".$fullname."','".$branchemail."','".$branchaddress."','".$number."','0', '".dateToday()."')";
            $query = $db->InsertData($insertbranch);
            $lastbranchid = $db->GetLastInsertedID();

            if($query){
                $inseruser = "INSERT INTO `jb_user`(`username`, `password`, `email`, `name`, `firstname`, `lastname`, `midname`, `nicknake`, `address`, `contact_number`,".
                                    " `position`, `level`, `job_title`, `branch_id`, `customer_type_id`, `status`, `created_at`) ". 
                    " VALUES ('".$username."', '".$password."' ,'".$email."','".$fullname."','".$firstname."','".$lastname."','".$midname."','".$nickname."','".$address."','".$contact."','2','1','".$jobtitle."','".$lastbranchid."','0', 'active', '".dateToday()."')";

                

                $userquery = $db->InsertData($inseruser);

                if($userquery) {
                   $response['status'] = 200;
                   $response['message'] = 'Success';
                } else {
                   $response['status'] = 101;
                   $response['message'] = 'Failed insert user';
                }

            } else {
                $response['status'] = 101;
                $response['message'] = 'Failed insert branch';
            }

        } else {
            $response['status'] = 101;
            $response['firstname'] = $validfirstname;
            $response['midname'] = $validmidname;
            $response['lastname'] = $validlastname;
            $response['nickname'] = $validnickname;
            $response['branchname'] = $validbranchname;
        }
    }

    echo json_encode($response);

}
?>