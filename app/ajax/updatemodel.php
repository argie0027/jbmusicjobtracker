<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
	$modelid = trim(filter_input(INPUT_POST, 'modelid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $modelname = trim(ucfirst(filter_input(INPUT_POST, 'modelname', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    $modeldescription = trim(ucwords(strtolower(filter_input(INPUT_POST, 'modeldescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $brandid = $_POST['brandid'];
    $categoryid = $_POST['categoryid'];
    $subcategoryid = $_POST['subcategoryid'];
    
    $checker = "UPDATE `jb_models` SET `modelname` = '".$modelname."', `description` = '".$modeldescription."', `brandid` = '".$brandid."', `cat_id` = '".$categoryid."', `sub_catid` = '".$subcategoryid."' WHERE `modelid` = '".$modelid."'";
 	$query = $db->ExecuteQuery($checker);
    if($query) {
    	echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>