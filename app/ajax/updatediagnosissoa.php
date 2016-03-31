<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $dataid = trim(filter_input(INPUT_POST, 'dataid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $typetoedit = trim(filter_input(INPUT_POST, 'typetoedit', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $spliderdataid = split("-", $dataid);
    
    if($spliderdataid[0] == "default") {
    	if($typetoedit == "diagnosis"){
    		$selectedItem = trim(filter_input(INPUT_POST, 'selectedItem', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

	    	$udpatemainjod = "UPDATE `jb_joborder` SET `diagnosis` = '".$selectedItem."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "item"){

    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		// echo $dataid;
    		// echo $itemvalue;
			$udpatemainjod = "UPDATE `jb_joborder` SET `item` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "remarks"){

    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		// echo $dataid;
    		// echo $itemvalue;
			$udpatemainjod = "UPDATE `jb_joborder` SET `remarks` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "tech"){

    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		// echo $dataid;
    		// echo $itemvalue;

    		// get previous tech id
    		$jobsql = "SELECT technicianid FROM jb_joborder WHERE jobid='".$jobid."'";
    		$jobsqlQuery = $db->ReadData($jobsql);

    		// get status of previous tech
    		$tech_status = "SELECT status, tech_id FROM jb_technicians WHERE tech_id='".$jobsqlQuery[0]['technicianid']."'";
    		$tech_statusQuery = $db->ReadData($tech_status);

	 		if($tech_statusQuery) {

	 			// upadte new tech status
	    		$updatenewtech = "UPDATE `jb_technicians` SET `status` = '".$tech_statusQuery[0]['status']."', `updated_at` = '".dateToday()."' WHERE `tech_id` = '" . $itemvalue . "'";
		 		$updatetech = $db->ExecuteQuery($updatenewtech);

		 		// update tech statistic
		 		$updatenewtechstatistic = "UPDATE `tech_statistic` SET `techid` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `techid` = '" . $tech_statusQuery[0]['tech_id'] . "' AND jobid='".$jobid."'";
		 		$updatetechstatistic = $db->ExecuteQuery($updatenewtechstatistic);

		 		if( $updatetech ) {

		 			// upadte previous tech status
		    		$udpateprevtech = "UPDATE `jb_technicians` SET `status` = '0', `updated_at` = '".dateToday()."' WHERE `tech_id` = '" . $tech_statusQuery[0]['tech_id'] . "'";
			 		$update = $db->ExecuteQuery($udpateprevtech);

					$udpatemainjod = "UPDATE `jb_joborder` SET `technicianid` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
			 		$query = $db->ExecuteQuery($udpatemainjod);
			 		if($query) {
			 			echo "success";
			 		}else{
			 			echo "error while updating diagnosis";
			 		}
		 		} else {
		 			echo "error to update new tech";
		 		}

	 		} else {
	 			echo "error to update previous tech";
	 		}

    	}else if($typetoedit == "totalcharges"){
    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			$udpatemainjod = "UPDATE `jb_cost` SET `total_charges` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "lessdeposit"){
    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			$udpatemainjod = "UPDATE `jb_cost` SET `less_deposit` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "lessdiscount"){
    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			$udpatemainjod = "UPDATE `jb_cost` SET `less_discount` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "updateparts"){

    		$partprince = trim(filter_input(INPUT_POST, 'partprice', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		// $total_charges = trim(filter_input(INPUT_POST, 'total_charges', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$itemvalue = trim(filter_input(INPUT_POST, 'items', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$product_id = trim(filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$balance = trim(filter_input(INPUT_POST, 'balance', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

			$reme =  str_replace("# ", "", $product_id);
			$reme = substr($reme, 0, -1);
			echo substr($reme, 1);

    		$udpatemainjod = "UPDATE `jb_joborder` SET `partsid` = '".substr($reme, 1)."', `parts` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			$udpatemainjod = "UPDATE `jb_cost` SET totalpartscost ='".$partprince."', `balance` = '".$balance."', `updated_at` = '".dateToday()."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
		 		$query = $db->ExecuteQuery($udpatemainjod);
		 		if($query) {
		 			echo "success";
		 		}else{
		 			echo "error while updating diagnosis";
		 		}
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "notify"){

            var_dump($_POST);
            exit;

    		$partname = trim(filter_input(INPUT_POST, 'partname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$partcost = trim(filter_input(INPUT_POST, 'partcost', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$totalcharge = trim(filter_input(INPUT_POST, 'totalcharge', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$lessdeposit = trim(filter_input(INPUT_POST, 'lessdeposit', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $subjob = trim(filter_input(INPUT_POST, 'subjob', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

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

                                                                            The Service Team is update with the computation of the repair for your item: <br><br>
                                                                            '.$partname.'<br><br>
                                                                            Below is the breakdown of the computation:
                                                                            <br><br>
                                                                            <strong style="color: #222222;">Total Parts Cost:</strong> '.$partcost.' <br>
                                                                            <strong style="color: #222222;">Total Charges: </strong>  '.$totalcharge.'  <br>
                                                                            <strong style="color: #222222;">Less Deposit :</strong> '.$lessdeposit.'  <br>
                                                                            <strong style="color: #222222;">Less Discount :</strong> '.$lessdiscount.'  <br>
                                                                            <strong style="color: #222222;">Subjob Cost :</strong> '.$subjob.'  <br>
                                                                            <strong style="color: #222222;">Balance :</strong> '.$subjob.'  <br>
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

    	}
    }else{
 		echo "success";
		// $udpatemainjod = "UPDATE `subjoborder` SET `subdiagnosis` = '".$selectedItem."' WHERE `subjobid` = '" . $spliderdataid[1] . "'";
 	// 	$query = $db->ExecuteQuery($udpatemainjod);
 	// 	if($query) {
 	// 		echo "success";
 	// 	}else{
 	// 		echo "error while updating diagnosis";
 	// 	}
    }
}
?>