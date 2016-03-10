<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $category = trim(filter_input(INPUT_POST, 'maincategory', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $warraty_date = trim(filter_input(INPUT_POST, 'warranty_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    $purchase_date = strtotime($warraty_date);
    $current_date = strtotime(date("Y-m-d")); 
    $datediff = $current_date - $purchase_date; 
    $days = floor($datediff/(60*60*24)); 

    $sqlSubcat = "SELECT * FROM `jb_partssubcat` WHERE cat_id = '".$category."' ORDER BY subcat_id DESC";
    $querySubcat = $db->ReadData($sqlSubcat);

    foreach ($querySubcat as $key => $value) {
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


	
    if($querySubcat) {
    	echo "{\"response\":".json_encode($response) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>