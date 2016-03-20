<?php 

include '../../include.php';

include '../include.php';



$request = filter_input(INPUT_POST, 'action');



if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $jobid = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));


    $checker = "SELECT * FROM jb_warranty WHERE jobid = '".$jobid."'";

    $query_checker = $db->ReadData($checker);



    if($query_checker){

        $sql = "SELECT a.conforme, a.jobid, a.soaid, a.customerid, a.branchid, a.catid, a.partsid, a.parts, a.technicianid, a.repair_status, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at as dateadded, a.referenceno, a.servicefee, a.isdeleted,a.date_delivery, a.done_date_delivery,a.isunder_warranty,a.estimated_finish_date,a.jobclear, b.*, c.branch_id, c.branch_name, c.address as branch_address, c.contactperson as contact_person, c.email as contact_email, c.number as branch_number, d.tech_id, d.name as technam, e.warranty_type, e.warranty_date, e.jobid as e_jobID, f.diagnosis as diagnosisitem, g.*, h.* FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d, jb_warranty e, jb_diagnosis f, jb_partscat g, jb_partssubcat h WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND  a.jobid = e.jobid  AND a.diagnosis = f.id AND g.cat_id = a.catid AND g.cat_id = h.cat_id AND a.jobid = '" .$jobid. "'";

    }else {

        $sql = "SELECT a.conforme, a.jobid, a.soaid, a.customerid, a.branchid, a.catid, a.partsid, a.parts, a.technicianid, a.repair_status, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at as dateadded, a.referenceno, a.servicefee,a.isdeleted,a.date_delivery,a.done_date_delivery, a.isunder_warranty,a.estimated_finish_date,a.jobclear, b.*, c.branch_id, c.branch_name, c.address as branch_address, c.contactperson as contact_person, c.email as contact_email, c.number as branch_number, d.tech_id, d.name as technam, f.diagnosis as diagnosisitem, g.*, h.* FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d, jb_diagnosis f, jb_partscat g, jb_partssubcat h WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.diagnosis = f.id AND g.cat_id = a.catid AND g.cat_id = h.cat_id AND a.jobid = '" .$jobid. "'";

    } 

    // echo $sql;

    $query = $db->ReadData($sql);

    if($query) {

          $qu = "SELECT * FROM jb_soa WHERE jobid = '".$jobid."'";

            $queryin  = $db->ReadData($qu);

            $cost = "SELECT * FROM `jb_cost` WHERE  jobid = '".$jobid."'";

            $charges  = $db->ReadData($cost);


            $cost = "SELECT * FROM `subjoborder` WHERE  mainjob = '".$jobid."'";

            $subjoblist  = $db->ReadData($cost);

            if( isset($query[0]['isunder_warranty']) && $query[0]['isunder_warranty'] == 1 ) {
                
                $purchase_date = strtotime($query[0]['warranty_date']);
                $current_date = strtotime(date("Y-m-d")); 
                $datediff = $current_date - $purchase_date; 
                $days = floor($datediff/(60*60*24));

                foreach ($query as $key => $value) {
                    $parts_free = explode(",",$value['parts_free']);
                    $diagnostic_free = explode(",",$value['diagnostic_free']);

                    $parts_free = ( $days <= $parts_free[0] ) ? '<i class="fa fa-check-square-o free">' : '<i class="fa fa-times not-free"></i>';
                    $diagnostic_free = ( $days <= $diagnostic_free[0] ) ? '<i class="fa fa-check-square-o free">' : '<i class="fa fa-times not-free"></i>';

                    $response[] = array(
                        'subcategory' => $value['subcategory'],
                        'parts_free' => $parts_free,
                        'diagnostic_free' => $diagnostic_free
                    );
                }

            } else {

                foreach ($query as $key => $value) {
                    $response[] = array(
                        'subcategory' => $value['subcategory'],
                        'parts_free' => '<i class="fa fa-times not-free"></i>',
                        'diagnostic_free' => '<i class="fa fa-times not-free"></i>'
                    );
                }
                
            }

            echo "{\"response\":".json_encode($query) . ",\"response2\":".json_encode($queryin) . ",\"response3\":".json_encode($charges) . ",\"response4\":".json_encode($subjoblist) . ",\"response5\":".json_encode($response) . "}";

    }else {

        echo $db->GetErrorMessage();

    	echo "error out";
    }
}

?>