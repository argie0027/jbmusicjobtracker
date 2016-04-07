<?php

include '../../include.php';

include '../include.php';



$request = filter_input(INPUT_POST, 'action');



if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

	$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

	$forgot_code = $utility->random_string("1234567890abcdefghijklmnopqrstvuwxyzABCEFGHIJKLMNOPQRSTUVWXYZ",12);

	

	$checker = "SELECT * FROM jb_user WHERE email='".$email."' AND status='active'";

    $checkerQuery = $db->ReadData($checker);



    if ( $checkerQuery ) {

		$sql = "UPDATE `jb_user` SET `forgot_code`='".$forgot_code."', `updated_at` = '".dateToday()."' WHERE id='".$checkerQuery[0]['id']."'";

		$query = $db->InsertData($sql);



		$response = array();



		if ( $query ) {

			$subject = 'JB MUSIC & SPORTS';

			$message = '<html> <head> <title>JB MUSIC & SPORTS</title> <style type="text/css"> div, p, a, li, td{-webkit-text-size-adjust: none;}</style> </head> <body bgcolor="#FFFFFF"> <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="600"> <tbody> <tr> <td align="center" valign="top" width="650" style="padding:0px"> <table border="0" cellpadding="0" cellspacing="0" width="540"> <tbody> <tr> <td align="left" valign="middle" height="100" bgcolor="#3e4095" width="100%" style="padding-top: 15px; padding-right: 10px; padding-bottom: 10px; text-align: center; padding-left: 10px;"> <img src="'.SITE_IMAGES_DIR.'logo2.png" width="400"> </td></tr></tbody> </table> <table border="0" cellpadding="0" cellspacing="0" width="540"> <tbody> <tr> <td align="center" valign="middle" width="540" bgcolor="#3e4094" style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-top: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 16px; color: #FFFFFF;" > </td></tr></tbody> </table> <table border="0" cellpadding="0" cellspacing="0" width="540"> <tbody> <tr> <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding: 20px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" > <table border="0" cellpadding="0" cellspacing="0" width="500"> <tbody> <tr> <td align="left" valign="top" width="540" colspan="2" bgcolor="#FFFFFF" style="padding-bottom:5px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" > <strong style="color: #222222;"><br>Hi '.$checkerQuery[0]['firstname'].' '.$checkerQuery[0]['midname'].'. '.$checkerQuery[0]['lastname'].'!</strong> <p> We received your request to reset your password!<br>You can now proceed by clicking this link: </p><p> <b>Link: </b> <a target="new" href="'.SITE_URL.'reset.php?member='.sha1($checkerQuery[0]['email']).'&code='.$forgot_code.'&email=true">'.SITE_URL.'reset.php?member='.sha1($checkerQuery[0]['email']).'&code='.$forgot_code.'&email=true</a> </p><p> <br><br>Have a great day. <br>All the best,<br>JB Music and Sports Team </p></td></tr><tr> <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding-bottom:14px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" > </td></tr></tbody> </table> </td></tr></tbody> </table> <br><table width="540" border="0" cellpadding="0" cellspacing="0"> <tbody> <tr> <td width="540" align="left" valign="middle" bgcolor="#3e4094 " style="padding-top: 15px; padding-right: 10px; padding-bottom: 15px; padding-left: 20px; border-bottom: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 12px; line-height: 14px; color: #FFFFFF;" > This e-mail was sent as a reset password for your account with <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a> </td></tr></tbody> </table> </td></tr></tbody> <tfoot> <tr> <td align="center" valign="top" width="650" height="5" style="padding:0px">&nbsp; </td></tr></tfoot> </table> </body> </html>';

			

			$headers = "From: JB MUSIC & SPORTS <system@jbmusicjobtracker.com>\r\n";

		    $headers .= "Reply-To: ". "jbmusicjobtracker@gmail.com" . "\r\n";

		    $headers .= "MIME-Version: 1.0\r\n";

		    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";



		    $retval =  mail($email, $subject, $message, $headers);
            //$retval = sendMail($email, $subject, $message, $headers);



		    if( $retval ){

		        $response['status'] = 200;

		        $response['message'] = 'Success';

		    } else {

		        $response['status'] = 101;

		        $response['message'] = 'Failed';

		    }



		} else {

			$response['status'] = 101;

		    $response['message'] = 'Please try again.';

		}



	} else {

		$response['status'] = 404;

		$response['message'] = 'Invalid email address.';

	}



	echo json_encode($response);

}

?>