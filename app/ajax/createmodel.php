<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $modelname = trim(ucfirst(filter_input(INPUT_POST, 'modelname', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    $modeldescription = trim(ucwords(strtolower(filter_input(INPUT_POST, 'modeldescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $brandid = $_POST['brandid'];
    $categoryid = $_POST['categoryid'];
    $subcategoryid = $_POST['subcategoryid'];

    $insertCategory = "INSERT INTO `jb_models` (`modelname`, `description`, `brandid`, `cat_id`, `sub_catid`,`created_at`) VALUES ('".$modelname."', '".$modeldescription."', '".$brandid."', '".$categoryid."', '".$subcategoryid."',NOW())";
    $query = $db->InsertData($insertCategory);

	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>