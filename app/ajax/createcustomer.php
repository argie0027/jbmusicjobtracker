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

    $date = date('Y-m-d H:i:s');
    $generated = $utility->random_string("abcdefghijklmnopqrstvuwxyzABCEFGHIJKLMNOPQRSTUVWXYZ",60);
    $linkgen = sha1($date . $generated);
    $insertclient = "INSERT INTO `tb_client`(`linkgen`,`customer_id`,`created_at`) VALUES ('".$linkgen."','".$customerid."','".dateToday()."')";
    $queryinserclient = $db->InsertData($insertclient); 

    $subject = 'JB MUSIC & SPORTS';
    $message = '<html>
                    <head>
                        <title>JB MUSIC & SPORTS</title>
                        <style type="text/css">
                            div, p, a, li, td {
                                -webkit-text-size-adjust: none;
                            }
                        </style>
                    </head>
                    <body bgcolor="#FFFFFF">
                        <!-- Table Wrap  -->
                        <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="600">
                            <!-- Content Container -->
                            <tbody>
                               <tr>
                                    <td align="center" valign="top" width="650" style="padding:0px">

                                        <!-- Table for Banner -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="middle" height="100" bgcolor="#3e4095" width="100%" style="padding-top: 15px; padding-right: 10px; padding-bottom: 10px; text-align: center; padding-left: 10px;">
                                                        <img src="'.SITE_IMAGES_DIR.'logo2.png" width="400">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table><!-- Table for Banner END -->

                                        <!-- Table for One Column -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="center" valign="middle" width="540" bgcolor="#3e4094" style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-top: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 16px; color: #FFFFFF;" >

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table><!-- Table for One Column END -->

                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding: 20px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" >
                                                        <table border="0" cellpadding="0" cellspacing="0" width="500">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="left" valign="top" width="540" colspan="2" bgcolor="#FFFFFF" style="padding-bottom:5px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" >
                                                                        <strong style="color: #222222;"><br>Hi '.$name.'!</strong>
                                                                        <p>
                                                                            You have successfully registered with JB Music & Sports – Client Portal.
                                                                            <br>
                                                                            You can get latest updates regarding your item by visiting the Client Portal <a target="new" href="http://clientportal.jbmusicjobtracker.com/?access_code='.$linkgen.'">http://clientportal.jbmusicjobtracker.com/?access_code='.$linkgen.'</a>
                                                                        </p>
                                                                        <p>
                                                                            Should you require further assistance, please contact us.
                                                                        </p>
                                                                        <p>

                                                                            Have a great day.
                                                                            <br>
                                                                            All the best,<br>
                                                                            JB Music and Sports Team

                                                                        </p>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding-bottom:14px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" >
                                                                        </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <br>
                                        <table width="540" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td width="540" align="left" valign="middle" bgcolor="#3e4094 " style="padding-top: 15px; padding-right: 10px; padding-bottom: 15px; padding-left: 20px; border-bottom: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 12px; line-height: 14px; color: #FFFFFF;" >
                                                    This e-mail was sent as a notication for your account status with  <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody><!-- Content Container END -->
                            <!-- Bottom Spacer -->
                            <tfoot>
                                <tr>
                                    <td align="center" valign="top" width="650" height="5" style="padding:0px">&nbsp;
                                    </td>
                                </tr>
                            </tfoot><!-- Bottom Spacer END -->
                        </table><!-- Table Wrap END -->
                    </body>
                    </html>';
    $headers = "From: JB MUSIC & SPORTS <system@jbmusicjobtracker.com>\r\n";
    $headers .= "Reply-To: ". "jbmusicjobtracker@gmail.com" . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $retval =  mail($email, $subject, $message, $headers);
    //$retval = sendMail($email, $subject, $message);

    if($query && $retval) {
        /* Insert History */
        $description = 'Customer Created';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$name."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */
        
        echo "success";
    }else {
    	echo "error out";
    }
}
?>