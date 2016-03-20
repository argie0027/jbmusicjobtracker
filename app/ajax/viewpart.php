 <?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $id = trim(filter_input(INPUT_POST, 'jobid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    
    $checker = "SELECT *, ( (bacth_quantity-quantity-quantityfree)*cost ) AS totalprice FROM `jb_part` WHERE  part_id = '".$id."'";
    $query  = $db->ReadData($checker);

    $sqlmodel = "SELECT m.modelname, m.description, b.brandname, c.category, s.subcategory FROM jb_models m, jb_brands b, jb_partscat c, jb_partssubcat s WHERE m.brandid = b.brandid AND m.cat_id = c.cat_id AND m.sub_catid = s.subcat_id AND modelid = '".$query[0]['modelid']."'";
    $querymodel = $db->ReadData($sqlmodel);

    if($query) {
    	echo "{\"response\":".json_encode($query) . ", \"response2\":".json_encode($querymodel) . "}";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>