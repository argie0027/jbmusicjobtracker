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
    
    $checker = "UPDATE `jb_models` SET `modelname` = '".$modelname."', `description` = '".$modeldescription."', `brandid` = '".$brandid."', `cat_id` = '".$categoryid."', `sub_catid` = '".$subcategoryid."', `updated_at` = '".dateToday()."' WHERE `modelid` = '".$modelid."'";
 	$query = $db->ExecuteQuery($checker);
    if($query) {

        /* Insert History */
        $description = 'Model Edited';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$modelname."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

    	echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>