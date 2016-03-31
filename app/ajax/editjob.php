<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $customerID = trim(filter_input(INPUT_POST, 'customerID', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $joborderid = trim(filter_input(INPUT_POST, 'joborderid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $name = trim(ucwords(strtolower(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $number = trim(filter_input(INPUT_POST, 'number', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $address = trim(ucwords(strtolower(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $branchid = trim(filter_input(INPUT_POST, 'branchid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $customertype = trim(filter_input(INPUT_POST, 'customertype', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $itemname = trim(ucwords(strtolower(filter_input(INPUT_POST, 'itemname', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $technician = trim(filter_input(INPUT_POST, 'technician', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $diagnosis = trim(filter_input(INPUT_POST, 'diagnosis', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $remarks = trim(ucwords(strtolower(filter_input(INPUT_POST, 'remarks', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $referenceno = trim(filter_input(INPUT_POST, 'referenceno', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $servicefee = trim(filter_input(INPUT_POST, 'servicefee', FILTER_SANITIZE_FULL_SPECIAL_CHARS));


    $isunder_warranty = trim(filter_input(INPUT_POST, 'isunder_warranty', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $warranty_date = trim(filter_input(INPUT_POST, 'warranty_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $maincategory = trim(filter_input(INPUT_POST, 'maincategory', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $sql = "UPDATE jb_customer SET customer_type_id='". $customertype ."', name='". $name ."',email='". $email ."',address='". $address ."',number='". $number ."', `updated_at` = '".dateToday()."' WHERE customerid = '". $customerID ."'";
    $query = $db->InsertData($sql);

    /* Insert History */
    $description = 'Customer Edited';
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$name."','".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */

    /* Insert History */
    $getBranchId = "SELECT branchid FROM jb_joborder WHERE jobid = ".$joborderid;
    $resultBranchId = $db->ReadData($getBranchId);

    $description = explode(",",ACT_NOTIF);
    $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
    $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description[1]."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$resultBranchId[0]['branchid']."', '".$joborderid ."','".dateToday()."')";
    $query = $db->InsertData($insertHistory);
    /* End of Insert History */
    
    if($query) {
        
    	$joborder_update = "UPDATE jb_joborder SET item='".$itemname."',diagnosis='".$diagnosis."',remarks='".$remarks."', isunder_warranty='".$isunder_warranty."', referenceno='".$referenceno."', servicefee='".$servicefee."', catid='".$maincategory."', `updated_at` = '".dateToday()."' WHERE jobid = '".$joborderid."'";
        $updatejobs = $db->InsertData($joborder_update);
        if($updatejobs){
            $deleteWarranty = "DELETE FROM `jb_warranty` WHERE jobid = '" .$joborderid. "'";

            if($warranty_date) { 
                $warranty_out = $db->InsertData($deleteWarranty);

                // $warranty_query = "UPDATE jb_warranty SET warranty_date='".$warranty_date."', `updated_at` = '".dateToday()."' WHERE jobid = '".$joborderid."'";
                $warranty_query = "INSERT INTO `jb_warranty`(`jobid`, `warranty_date`, created_at) VALUES ('".$joborderid."','".$warranty_date."','".dateToday()."')";
                $warranty_in = $db->InsertData($warranty_query);
                if($warranty_in){
                    echo "success";
                }else {
                    echo $db->GetErrorMessage();
                    echo "error warranty";
                }
            } else {
                $warranty_out = $db->InsertData($deleteWarranty);
                echo "success";
            }

        }else {
            echo "error job order";
        }
    }else {
    	echo "error out";
    }
}


?>