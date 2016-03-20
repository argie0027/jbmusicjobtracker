<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $toSearch = trim(filter_input(INPUT_POST, 'toSearch', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    # Get generic category
    $getGenericCat = "SELECT * FROM jb_models m, jb_partscat c WHERE m.cat_id = c.cat_id AND c.generic='yes' GROUP BY c.cat_id";
    $getGenericCat = $db->ReadData($getGenericCat);

    $catId = '';

    if($getGenericCat) {
        foreach ($getGenericCat as $key => $genericCatId) {
            $catId .= " OR m.cat_id='".$genericCatId['cat_id']."'";
        } 
    } 

    if (!empty($_POST['categoryid'])){
        $sql = "SELECT p.*, s.parts_free, s.diagnostic_free FROM jb_part p, jb_models m, jb_partscat c, jb_partssubcat s  WHERE p.quantity != 0 AND p.modelid = m.modelid AND m.sub_catid = s.subcat_id AND ( m.cat_id = '".$_POST['categoryid']."' ".$catId.") AND p.name LIKE '%".$toSearch."%' GROUP BY p.part_id ORDER BY p.created_at DESC";
    } else {
        $sql = "SELECT * FROM `jb_part` WHERE name LIKE '%".$toSearch."%' GROUP BY part_id ORDER BY created_at DESC";
    }

    $query = $db->ReadData($sql);
    $response2 = '';
    if(!empty($_POST['categoryid']) && !empty($_POST['jobid'])) {
    	/* Get Warraty date to excute of free parts and diagnosis */
        $sqlWarranty = "SELECT warranty_date FROM jb_warranty WHERE jobid = '".$_POST['jobid']."'";
        $queryWarranty = $db->ReadData($sqlWarranty);

        if ($queryWarranty) {
        	
	        $purchase_date = strtotime($queryWarranty[0]['warranty_date']);
	        $current_date = strtotime(date("Y-m-d")); 
	        $datediff = $current_date - $purchase_date; 
	        $days = floor($datediff/(60*60*24));

	        foreach ($query as $key => $value) {
                $parts_free = explode(",",$value['parts_free']);
                $diagnostic_free = explode(",",$value['diagnostic_free']);

                $parts_free = ( $days <= $parts_free[0] ) ? 0 : $value['cost'];
                $diagnostic_free = ( $days <= $diagnostic_free[0] ) ? 0 : 800;

                $response2[] = array(
                    'parts_free' => $parts_free,
                    'diagnostic_free' => $diagnostic_free
                );
            }

    	} else {
    		foreach ($query as $key => $value) {
                $response2[] = array(
                    'parts_free' => $value['cost'],
                    'diagnostic_free' => 800
                );
            }

    	} 
    }

    if($query) {
    		echo "{\"response\":".json_encode($query) . ", \"response2\":".json_encode($response2) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error";
    }
}
?>