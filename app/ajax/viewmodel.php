<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $modelid = trim(filter_input(INPUT_POST, 'modelid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    if ( !empty($_POST['all']) ){
    	$checker = "SELECT m.description, b.brandname, c.category, s.subcategory FROM jb_models m, jb_brands b, jb_partscat c, jb_partssubcat s WHERE m.brandid = b.brandid AND m.cat_id = c.cat_id AND m.sub_catid = s.subcat_id AND modelid = '".$modelid."'";
    } else {
    	$checker = "SELECT * FROM `jb_models` WHERE modelid = '".$modelid."'";
    }
    
    $query  = $db->ReadData($checker);

    if($query) {
    		echo "{\"response\":".json_encode($query) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>