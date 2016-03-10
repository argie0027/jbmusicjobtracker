<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT c.cat_id, c.category, s.subcategory, s.parts_free, s.diagnostic_free FROM jb_partscat c, jb_partssubcat s WHERE c.cat_id = s.cat_id AND c.cat_id = '".$id."' ORDER BY s.subcat_id ASC";
    $query  = $db->ReadData($checker);

    foreach ($query as $key => $value) {
		$response2[] = '<div class="form-group col-xs-12 form-subcategory"><label>Sub Category:</label><input type="text" name="subcategory" data-customer-id="" class="form-control" placeholder="Sub Category" value="'.$value['subcategory'].'"><label>Parts Free:</label><div class="section-partfree"></div><label>Diagnostic Free:</label><div class="section-diagnosticfree"></div><a href="#" class="subcat-remove">Remove Subcategory</a></div>';
    }

    if($query) {
    	echo "{\"response\":".json_encode($query) . ", \"response2\":".json_encode($response2) . "}";
    } else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>