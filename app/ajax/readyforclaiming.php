<?php 

include '../../include.php';

include '../include.php';

$request = filter_input(INPUT_POST, 'action');



if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $emailaddress = trim(filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $branch = trim(filter_input(INPUT_POST, 'branch', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $itemname = trim(filter_input(INPUT_POST, 'item', FILTER_SANITIZE_FULL_SPECIAL_CHARS));



    $email = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql = "UPDATE jb_joborder SET repair_status = 'Ready for Claiming', done_date_delivery = '0000-00-00', `updated_at` = '".dateToday()."' WHERE jobid = '".$id."'";



    $query = $db->InsertData($sql);



    /* Insert History */

    $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$id;

    $resultBranchId = $db->ReadData($getBranchId);



    $description = explode(",",ACT_NOTIF);

    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];

    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description[6]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$id ."','".dateToday()."')";

    $query = $db->InsertData($insertHistory);

    /* End of Insert History */

    

    if($query) {



    	$subject = 'Repaired Item (JB SPORTS & MUSIC)';

                            $message = '<html>

                                        <head>

                                            <title>JB SPORTS & MUSIC</title>

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

                                                                                              Good News!

<br><br>

																							The repair of your item is now done.

<br>

																							We will notify you as soon as your item is ready for pick up at our store.

<br><br>

																							Should you require further assistance, please contact us.

                                            

                                                                                            </p>

                                                                                                <br>

                                                                                                Have a great day.<br><br>

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

                                                                        This e-mail was sent as a notification for your job order status <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong>@ JB SPORTS & MUSIC</strong></a>

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
        //$retval = sendMail($email, $subject, $message, $headers);



        if($retval){

            echo "success";

        }else{

            echo "false";

        }



    }else {

    	echo "error out";

    }

}

?>