
<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $notif = split(',', NOTIF);
    $conforme = trim(filter_input(INPUT_POST, 'conforme', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $parts = trim(filter_input(INPUT_POST, 'parts', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $reference = trim(filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $spliter = split("#", $parts);

        $parts = "UPDATE `jb_soa` SET `status` = '1', `updated_at` = '".dateToday()."' WHERE `jobid` = '".$jobid."'";
        $queryparts = $db->InsertData($parts);

        $repair_status = "UPDATE `jb_joborder` SET repair_status = 'Approved', conforme = '".$conforme."', referenceno = '".$reference."', `updated_at` = '".dateToday()."' WHERE `jobid` = '".$jobid."'";
        $queryparts = $db->InsertData($repair_status);

        $selectsoa = "select * from jb_soa WHERE jobid = '" .$jobid. "'";
        $querysoa =$db->ReadData($selectsoa); 
        // ExecuteQuery

        $nofi = "INSERT INTO `notitemp`(`jobid`, `branch_id`, `user`, `status_type`, `isViewed`, `created_at`) VALUES ('".$jobid."','".$_SESSION['Branchid']."','".$_SESSION['Branchname']."','".$notif[1]."','0', '".dateToday()."')";
        $notifd = $db->InsertData($nofi);

        $data['jobid'] = $jobid;
        $data['name'] = $_SESSION['Branchname'];
        $data['message'] = $notif[1];
        $data['kanino'] = '1';

        /* Insert History */
    $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$jobid;
    $resultBranchId = $db->ReadData($getBranchId);

    $description = explode(",",ACT_NOTIF);
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`, `created_at`)". " VALUES ('".$description[4]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$jobid ."', '".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */
        
        $pusher->trigger('test_channel', 'my_event', $data);

        if($queryparts) {

        // $update_tech = "UPDATE jb_technicians SET `status` = '1' WHERE `tech_id` = '". $querysoa[0]['technicianid']."'";
        // $update_techstatus = $db->ExecuteQuery($update_tech); 

            for ($i=1; $i < sizeof($spliter); $i++) {
                $partid = split("-", $spliter[$i]);
                $quantity = split('~',str_replace(")", "", str_replace("*", "~",  $spliter[$i])));

                $selectparts  = "SELECT * FROM `jb_part` WHERE `quantity` != 0 AND `part_id` = '".$partid[0]."' LIMIT 1";
                $selectparts =$db->ReadData($selectparts); 
                if(!$selectparts){
                echo $db->GetErrorMessage();
                }

                //Cheack Warranty
                $checker = "SELECT * FROM jb_warranty WHERE jobid = '".$jobid."'";
                $query_checker = $db->ReadData($checker);

                $purchase_date = strtotime($query_checker[0]['warranty_date']);
                $current_date = strtotime(date("Y-m-d")); 
                $datediff = $current_date - $purchase_date; 
                $days = floor($datediff/(60*60*24));

                $getpart = "SELECT s.parts_free FROM jb_part p, jb_models m, jb_partssubcat s WHERE p.quantity != 0 AND p.modelid = m.modelid AND m.sub_catid = s.subcat_id AND p.part_id = '".$selectparts[0]['part_id']."' LIMIT 1";
                $querygetpart = $db->ReadData($getpart);
                $parts_free = explode(",",$querygetpart[0]['parts_free']);
                if( $days <= $parts_free[0] ) {
                    $qf = $selectparts[0]['quantityfree'] + $quantity[1];
                    $updatequantityfree =  "UPDATE jb_part SET quantityfree ='".$qf."', `updated_at` = '".dateToday()."' WHERE `quantity` != 0 AND part_id = '".$partid[0]."' LIMIT 1";
                    $db->ExecuteQuery($updatequantityfree);
                }
                //End Checker
                
                $q = $selectparts[0]['quantity'] - $quantity[1];
                
                $updatepartsquantity = "UPDATE jb_part SET quantity ='".$q."', `updated_at` = '".dateToday()."' WHERE `quantity` != 0 AND part_id = '".$partid[0]."' LIMIT 1";
                $updatepr = $db->ExecuteQuery($updatepartsquantity);
                    
                if(!$updatepr){
                echo $db->GetErrorMessage();
                }
                
                $updatecostaccepted = "UPDATE `jb_cost` SET ispaid = '1', `accepted_by` = '".$_SESSION['name']."', `updated_at` = '".dateToday()."' WHERE `jb_cost`.`jobid` = '".$jobid."'";
                $updatecostaccepted2 = $db->ExecuteQuery($updatecostaccepted);
                if(!$updatecostaccepted2){
                echo $db->GetErrorMessage();
                }

            }
            echo "success";
        }else {
        echo $db->GetErrorMessage();
            echo "error out";
        }
}
?>