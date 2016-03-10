<?php
$request = filter_input(INPUT_POST, 'action');
include '../../include.php';
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $password = sha1(trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    
    $query = "SELECT admin_id, username, password FROM tb_admin WHERE username='$username' AND password='$password'";

    $result = mysqli_query($db,$query);
    $num_row = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if( $num_row == 1 )
    {
        $_SESSION['usernmae'] = $row['username'];
        $_SESSION['id'] = $row['admin_id'];
        echo 'success';
    }else{
        echo 'error';
    }
}
?>