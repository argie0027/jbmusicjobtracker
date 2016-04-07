<?php 

include '../../include.php';

include '../include.php';



$request = filter_input(INPUT_POST, 'action');



if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $currentpassword = sha1( hash('sha256', HASH_AUSER.clearspaces(trim(filter_input(INPUT_POST, 'currentpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS)))) );

    $newpassword = sha1( hash('sha256', HASH_AUSER.clearspaces(trim(filter_input(INPUT_POST, 'newpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS)))) );



    $firstname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $lastname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $midname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'midname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $fullname = $firstname.' '.$midname.' '.$lastname;

    $nickname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $contact = trim(filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $image = trim(ucwords(strtolower(filter_input(INPUT_POST, 'image', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));



    $response = array();



    // User Info

    $userinfo = "SELECT * FROM jb_user WHERE id='".$id."'";

    $userinfoQuery = $db->ReadData($userinfo);



    // Update User

    $sql = "UPDATE `jb_user` SET ";

    if(trim(filter_input(INPUT_POST, 'currentpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) != "") {

       $sql =  $sql .  "`password`='".$newpassword."',";

    }



    $sql =  $sql .  "`name`='".$fullname."',`firstname`='".$firstname."',`lastname`='".$lastname."',`midname`='".$midname."',`nicknake`='".$nickname."',`address`='".$address."',`email`='".$email."',`contact_number`='".$contact."',`image`='".$image."', `updated_at` = '".dateToday()."' WHERE id = '".$id."'";



    // Email notify



    $subject = 'JB MUSIC & SPORTS';

    $message = '<html> <head> <title>JB MUSIC & SPORTS</title> <style type="text/css"> div, p, a, li, td{-webkit-text-size-adjust: none;}</style> </head> <body bgcolor="#FFFFFF"> <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="600"> <tbody> <tr> <td align="center" valign="top" width="650" style="padding:0px"> <table border="0" cellpadding="0" cellspacing="0" width="540"> <tbody> <tr> <td align="left" valign="middle" height="100" bgcolor="#3e4095" width="100%" style="padding-top: 15px; padding-right: 10px; padding-bottom: 10px; text-align: center; padding-left: 10px;"> <img src="'.SITE_IMAGES_DIR.'logo2.png" width="400"> </td></tr></tbody> </table> <table border="0" cellpadding="0" cellspacing="0" width="540"> <tbody> <tr> <td align="center" valign="middle" width="540" bgcolor="#3e4094" style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-top: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 16px; color: #FFFFFF;" > </td></tr></tbody> </table> <table border="0" cellpadding="0" cellspacing="0" width="540"> <tbody> <tr> <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding: 20px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" > <table border="0" cellpadding="0" cellspacing="0" width="500"> <tbody> <tr> <td align="left" valign="top" width="540" colspan="2" bgcolor="#FFFFFF" style="padding-bottom:5px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" > <strong style="color: #222222;"><br>Hi '.$firstname.' '.$midname.'. '.$lastname.'!</strong> <p> Your profile has been successfully updated! </p><p> <br><br>Have a great day. <br>All the best,<br>JB Music and Sports Team </p></td></tr><tr> <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding-bottom:14px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" > </td></tr></tbody> </table> </td></tr></tbody> </table> <br><table width="540" border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td width="540" align="left" valign="middle" bgcolor="#3e4094 " style="padding-top: 15px; padding-right: 10px; padding-bottom: 15px; padding-left: 20px; border-bottom: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 12px; line-height: 14px; color: #FFFFFF;" > This e-mail was sent as a updated for your account with <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a> </td></tr></tbody> </table> </td></tr></tbody> <tfoot> <tr> <td align="center" valign="top" width="650" height="5" style="padding:0px">&nbsp; </td></tr></tfoot> </table> </body> </html>';

    $headers = "From: JB MUSIC & SPORTS <system@jbmusicjobtracker.com>\r\n";

    $headers .= "Reply-To: ". "jbmusicjobtracker@gmail.com" . "\r\n";

    $headers .= "MIME-Version: 1.0\r\n";

    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    

    // checker for special characters

    $validfirstname = checkspecialchars($firstname);

    $validmidname = checkspecialchars($midname);

    $validlastname = checkspecialchars($lastname);

    $validnickname = checkspecialchars($nickname);



    if( trim(filter_input(INPUT_POST, 'currentpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) != "" ) {

        //check current password

        $checker = "SELECT * FROM jb_user WHERE password='".$currentpassword."' AND id='".$id."'";

        $checkerQuery = $db->ReadData($checker);



        if ( $checkerQuery ) {



            if( !$validfirstname && !$validmidname && !$validlastname && !$validnickname ) {



                // Email Address Checker

                if( $email != $userinfoQuery[0]['email'] ) {

                    // Check new email

                    $newEmail = "SELECT * FROM jb_user WHERE email='".$email."'";

                    $newEmailQuery = $db->ReadData($newEmail);



                    if( !$newEmailQuery ) {

                        $query = $db->InsertData($sql);

                        $retval =  mail($email, $subject, $message, $headers);

                        //$retval = sendMail($email, $subject, $message);

                        unset($_SESSION['nicknake']);

                        $_SESSION['nicknake'] = $nickname;

                    } else {

                        $response['emailaddress'] = true;

                    }



                } else {

                    $query = $db->InsertData($sql);

                    $retval =  mail($email, $subject, $message, $headers);

                    //$retval = sendMail($email, $subject, $message);

                    unset($_SESSION['nicknake']);

                    unset($_SESSION['name']);

                    unset($_SESSION['email']);



                    $_SESSION['nicknake'] = $nickname;

                    $_SESSION['name'] = $fullname;

                    $_SESSION['email'] = $email;

                }

                

            } else {

                $response['status'] = 101;

                $response['firstname'] = $validfirstname;

                $response['midname'] = $validmidname;

                $response['lastname'] = $validlastname;

                $response['nickname'] = $validnickname;

            }



        } else {

            $response['status'] = 101;

            $response['currentpassword'] = true;

        }



    } else {



        if( !$validfirstname && !$validmidname && !$validlastname && !$validnickname ) {



            // Email Address Checker

            if( $email != $userinfoQuery[0]['email'] ) {

                // Check new email

                $newEmail = "SELECT * FROM jb_user WHERE email='".$email."'";

                $newEmailQuery = $db->ReadData($newEmail);



                if( !$newEmailQuery ) {

                    $query = $db->InsertData($sql);

                    // $retval =  mail($email, $subject, $message, $headers);
             $retval = sendMail($email, $subject, $message, $headers);

                } else {

                    $response['emailaddress'] = true;

                }



            } else {

                $query = $db->InsertData($sql);

                // $retval =  mail($email, $subject, $message, $headers);
             $retval = sendMail($email, $subject, $message, $headers);



                unset($_SESSION['nicknake']);

                unset($_SESSION['name']);

                unset($_SESSION['email']);



                $_SESSION['nicknake'] = $nickname;

                $_SESSION['name'] = $fullname;

                $_SESSION['email'] = $email;

            }

            

        } else {

            $response['status'] = 101;

            $response['firstname'] = $validfirstname;

            $response['midname'] = $validmidname;

            $response['lastname'] = $validlastname;

            $response['nickname'] = $validnickname;

        }



    }



    // Proccess Complete

    if( isset($query) && $retval ) {



        /* Insert History */

        $description = 'Profile Updated';

        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];

        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$fullname."','".dateToday()."')";

        $query = $db->InsertData($insertHistory);

        /* End of Insert History */



        $response['status'] = 200;

        $response['message'] = 'Success';

    } else {

        $response['status'] = 101;

        $response['message'] = 'Failed';

    }





    echo json_encode($response);

}