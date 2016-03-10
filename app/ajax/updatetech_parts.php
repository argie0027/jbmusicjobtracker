<?php
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');
$idcost = "";
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $techid = trim(filter_input(INPUT_POST, 'techID', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $partnid = trim(filter_input(INPUT_POST, 'partsID', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $dd =  str_replace("# ", "", substr($partnid, 1));

    $dd = substr($dd, 0, -1);

    $parts = trim(ucwords(strtolower(filter_input(INPUT_POST, 'parts', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $itemname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'itemname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));

    $partcost = trim(filter_input(INPUT_POST, 'partcost', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $servicescharge = trim(filter_input(INPUT_POST, 'servicescharge', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $chargetotal = trim(filter_input(INPUT_POST, 'chargetotal', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $lessdeposit = trim(filter_input(INPUT_POST, 'lessdeposit', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $lessdiscount = trim(filter_input(INPUT_POST, 'lessdiscount', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $balancecharge = trim(filter_input(INPUT_POST, 'balancecharge', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $parts = "UPDATE `jb_joborder` SET `partsid` = '".$dd."', parts = '".$parts."', repair_status = 'Waiting for SOA Approval' WHERE jobid = '".$jobid."'";
    $queryparts = $db->InsertData($parts);

    $notif = split(',', NOTIF);
    $nofi = "INSERT INTO `notitemp`(`jobid`, `branch_id`, `user`, `status_type`, `isViewed`,`created_at`) VALUES ('".$jobid."','".$_SESSION['Branchid']."','".$_SESSION['Branchname']."','".$notif[2]."','0',NOW())";
    $notifd = $db->InsertData($nofi);

    $data['jobid'] = $jobid;
    $data['branch_id'] =$_SESSION['Branchid'];
    $data['name'] = $_SESSION['Branchname'];
    $data['message'] = $notif[2];
    $data['kanino'] = '0';

    /* Insert History */
    $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$jobid;
    $resultBranchId = $db->ReadData($getBranchId);

    $description = explode(",",ACT_NOTIF);
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(description, branch, name, branchid, isbranch, jobnumber,`created_at`)". " VALUES ('".$description[9]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$jobid ."',NOW())";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

    $pusher->trigger('test_channel', 'my_event', $data);

    if($queryparts) {

        $tech = "UPDATE `jb_joborder` SET `technicianid` = '".$techid."' WHERE jobid = '".$jobid."'";
        $querytech = $db->InsertData($tech);

        // $updsatetechstatus = "UPDATE `jb_technicians` SET `status` = '1' WHERE `jb_technicians`.`tech_id` = '".$techid."'";
        // $updsatetechquery = $db->InsertData($updsatetechstatus);

        if($querytech){
            $selectjobdetails = "SELECT customerid, branchid FROM jb_joborder WHERE jobid = '".$jobid."'";
            $selectjob = $db->ReadData($selectjobdetails);

            if( $selectjob != null )
            {
                foreach ($selectjob as $key => $value) {

                    $soaid = $utility->random_string("1234567890",5);
                    $check = "SELECT jobid FROM `jb_cost` WHERE jobid = '".$jobid."'";
                    $ifhavecostalready = $db->ReadData($check);

                    if($ifhavecostalready) {
                        $updatecost = "UPDATE `jb_cost` SET `totalpartscost`='".$partcost."',`service_charges`='".$servicescharge."',`total_charges`='".$chargetotal."',`less_deposit`='".$lessdeposit."',`less_discount`='".$lessdiscount."',`balance`='".$balancecharge."',`computed_by`='".$_SESSION['name']."' WHERE jobid = '".$jobid."'";
                        $updatecosts = $db->InsertData($updatecost);

                        if($updatecosts){
                            $subject = 'JB SPORTS & MUSIC SOA Approval';
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
                                <img src="http://jbmusicjobtracker.com/resources/img/logo2.png" width="400">
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
                                                <strong style="color: #222222;"><br>Message:</strong>
                                                <p>

                                                The Service Team is done with the computation of the repair for your item: <br><br>
                                                '.$itemname.'<br><br>
                                                Below is the breakdown of the computation:
                                                <br><br>
                                                <strong style="color: #222222;">Total Parts Cost:</strong> '.$partcost.' <br>
                                                <strong style="color: #222222;">Service Charges :</strong> '.$servicescharge.'  <br>
                                                <strong style="color: #222222;">Total Charges: </strong>  '.$chargetotal.'  <br>
                                                <br>

                                                Should you decide to pursue with the repair, please visit the branch for the down-payment and conforme.
                                                <br><br>
                                                Will be waiting for you to visit us before proceeding with the repair.
                                                <br><br>
                                                Hope to see you soon!

                                                <br><br>
                                                Regards,<br>
                                                JB Music and Sports Repair Service Team
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
                           This e-mail was sent as a notication for your Job Order Status with  <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a></td>
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
                            //$retval =  sendMail($email, $subject, $message);

                            if($retval){
                                echo "success";
                            }else{
                                echo "false";
                            }
                        }else{
                            echo "error in";
                        }
                    }else{
                        $cost = "INSERT INTO `jb_cost`(`jobid`,`totalpartscost`, `service_charges`, `total_charges`, `less_deposit`,`less_discount`, `balance`, `computed_by`, `accepted_by`,`created_at`) ".
                            "VALUES ('".$jobid."','".$partcost."','".$servicescharge."','".$chargetotal."','".$lessdeposit."','".$lessdiscount."','".$balancecharge."','".$_SESSION['name']."','',NOW())";
                        $insertcost = $db->InsertData($cost);
                        $idcost = $db->GetLastInsertedID();

                        if($insertcost){
                            $soa = "INSERT INTO `jb_soa`(`soa_id`, `jobid`, `customerid`, `branchid`, `technicianid`, `cost_id`, `status`, `conforme`,`created_at`) " .
                                "VALUES ('SOA-".$soaid."','".$jobid."','".$value['customerid']."','".$value['branchid']."','".$techid."','".$idcost."','0','0',NOW())";
                            $addtosoa = $db->InsertData($soa);
                            if($addtosoa){
                                $subject = 'JB SPORTS & MUSIC SOA Approval';
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
                                <img src="http://jbmusicjobtracker.com/resources/img/logo2.png" width="400">
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
                                                <strong style="color: #222222;"><br>Message:</strong>
                                                <p>

                                                The Service Team is done with the computation of the repair for your item: <br><br>
                                                '.$itemname.'<br><br>
                                                Below is the breakdown of the computation:
                                                <br><br>
                                                <strong style="color: #222222;">Total Parts Cost:</strong> '.$partcost.' <br>
                                                <strong style="color: #222222;">Service Charges :</strong> '.$servicescharge.'  <br>
                                                <strong style="color: #222222;">Total Charges: </strong>  '.$chargetotal.'  <br>
                                                <br>
                                                
                                                Should you decide to pursue with the repair, please visit the branch for the down-payment and conforme.
                                                <br><br>
                                                Will be waiting for you to visit us before proceeding with the repair.
                                                <br><br>
                                                Hope to see you soon!

                                                <br><br>
                                                Regards,<br>
                                                JB Music and Sports Repair Service Team
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
                           This e-mail was sent as a notication for your Job Order Status with  <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a></td>
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
                                
                                $headers = "From: JB MUSIC & SPORTS\r\n";
                                $headers .= "Reply-To: ". "jbmusicjobtracker@gmail.com" . "\r\n";
                                $headers .= "MIME-Version: 1.0\r\n";
                                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                                $retval =  mail($email, $subject, $message, $headers);
                            
                                //$retval =  sendMail($email, $subject, $message);

                                if($retval){
                                    echo "success";
                                }else{
                                    echo "false";
                                }
                            }
                        }else{
                            echo "error out";
                        }
                    }
                }
            }
        }
    }else {
        echo "error out";
    }
}
?>