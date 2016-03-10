<?php
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $password = sha1( hash('sha256', HASH_AUSER.trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS))) );

    $query = "SELECT * FROM jb_user WHERE username='$username' AND password='$password' AND isdeleted = '0' AND status = 'active'";
    $validate = $db->loginModule($query);

    if( $validate != null )
    {
        foreach ($validate as $key => $value) {

            $_SESSION['id'] = $value['id'];
            $_SESSION['username'] = $value['username'];
            $_SESSION['email'] = $value['email'];
            $_SESSION['name'] = $value['name'];
            $_SESSION['nicknake'] = $value['nicknake'];
            $_SESSION['position'] = $value['position'];
            $_SESSION['job_title'] = $value['job_title'];
            $_SESSION['Branchid'] = $value['branch_id'];

            $time = strtotime($value['created_at']);
            $_SESSION['created_at'] = date("F d, Y", $time);
            $_SESSION['password'] = $value['password'];
            if($value['position'] == 0 || $value['position'] == -1){
                echo "true";
                $_SESSION['Branchname'] = "Admin";
                unset($_SESSION['password']);
            }else {
                $selectbrac = "SELECT * FROM `jb_branch` WHERE branch_id = " . $value['branch_id'];
                $selectbrac  = $db->ReadData($selectbrac);
                $_SESSION['Branchname'] = $selectbrac[0]['branch_name'];
                unset($_SESSION['password']);
                echo "true1";
            }
        }
    }else{
        echo $db->GetErrorMessage();
        echo 'false';
    }
}else {
    header('location: ../');
    exit;
}
?>