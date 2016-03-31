<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

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

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $response = array();

    $validfirstname = checkspecialchars($firstname);
    $validmidname = checkspecialchars($midname);
    $validlastname = checkspecialchars($lastname);
    $validnickname = checkspecialchars($nickname);
    $validbranchname = checkspecialchars($branchname);


    if( !$validfirstname && !$validmidname && !$validlastname && !$validnickname && !$validbranchname) {

        $updatebranch = "UPDATE `jb_branch` SET `branch_name`='".$branchname."',`contactperson`='".$fullname."',`email`='".$branchemail."',`address`='".$branchaddress."',`number`='".$number."', `updated_at` = '".dateToday()."' WHERE branch_id = '".$id."'";

        $query = $db->InsertData($updatebranch);

        /* Insert History */
        $description = 'Branch/Store Edited';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$branchname."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

        if( $query ) {


            $sql = "UPDATE `jb_user` SET ";
            if(trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) != "") {
               $sql =  $sql .  "`password`='".$password."',";
            }

            $sql =  $sql .  "`email`='".$email."',`name`='".$fullname."',`firstname`='".$firstname."',`lastname`='".$lastname."',`midname`='".$midname."',`nicknake`='".$nickname."',`address`='".$address."',`contact_number`='".$contact."',`job_title`='".$jobtitle."', `updated_at` = '".dateToday()."' WHERE branch_id = '".$id."' AND position = 2";
            
            // Email Address Checker
            if( $email ) {
                $emailInfo = "SELECT * FROM jb_user WHERE branch_id='".$id."'";
                $emailQuery = $db->ReadData($emailInfo);

                if( $email != $emailQuery[0]['email'] ) {
                    // Check new email
                    $newEmail = "SELECT * FROM jb_user WHERE email='".$email."'";
                    $newEmailQuery = $db->ReadData($newEmail);

                    if( !$newEmailQuery ) {
                        $userquery = $db->InsertData($sql);
                    } else {
                        $response['emailaddress'] = true;
                    }

                } else {
                    $userquery = $db->InsertData($sql);
                }
            }

            if( isset($userquery) ) {
               $response['status'] = 200;
               $response['message'] = 'Success';
            } else {
               $response['status'] = 101;
               $response['message'] = 'Failed update user';
            }

        } else {
            $response['status'] = 101;
            $response['message'] = 'Failed update branch';
        }

    } else {
        $response['status'] = 101;
        $response['firstname'] = $validfirstname;
        $response['midname'] = $validmidname;
        $response['lastname'] = $validlastname;
        $response['nickname'] = $validnickname;
        $response['branchname'] = $validbranchname;
    }

    echo json_encode($response);
}
?>