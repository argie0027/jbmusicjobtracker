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
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $contact = trim(filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

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

        $emailChecker = "SELECt * FROM jb_user WHERE email='".$email."'";
        $emailQuery = $db->ReadData($emailChecker);

        if( $emailQuery ) {
            $response['emailaddress'] = true;
        }

        if( !$validfirstname && !$validmidname && !$validlastname && !$validnickname && !$emailQuery ) {

            $sql = "INSERT INTO `jb_user`(`username`, `password`, `email`, `name`, `firstname`, `lastname`, `midname`, `nicknake`, `address`, `contact_number`, `position`, `level`, `job_title`, `branch_id`, `status`, `created_at`) " . "VALUES ('".$username."','".$password."','".$email."','".$fullname."','".$firstname."','".$lastname."','".$midname."','".$nickname."','".$address."','".$contact."','3','3','".$jobtitle."','".$branchid."','inactive','".dateToday()."')";
            $query = $db->InsertData($sql);

            /* Insert History */
            $description = 'Branch Staff Created';
            $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
            $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$fullname."','".dateToday()."')";
            $query = $db->InsertData($insertHistory);
            /* End of Insert History */

            if($query) {
               $response['status'] = 200;
               $response['message'] = 'Success';
            } else {
               $response['status'] = 101;
               $response['message'] = 'Failed';
            }

        } else {

            $response['status'] = 101;
            $response['firstname'] = $validfirstname;
            $response['midname'] = $validmidname;
            $response['lastname'] = $validlastname;
            $response['nickname'] = $validnickname;

        }
    }

    echo json_encode($response);
}
?>