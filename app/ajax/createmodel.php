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

    $insertCategory = "INSERT INTO `jb_models` (`modelname`, `description`, `brandid`, `cat_id`, `sub_catid`,`created_at`) VALUES ('".$modelname."', '".$modeldescription."', '".$brandid."', '".$categoryid."', '".$subcategoryid."','".dateToday()."')";
    $query = $db->InsertData($insertCategory);

	if($query){

        /* Insert History */
        $description = 'Model Created';
        $branchName = ( $_SESSION['Branchname'] == 'Admin') ? 'Main Office' : $_SESSION['Branchname'];
        $insertHistory = "INSERT INTO `jb_history`(`description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`,`created_at`)". " VALUES ('".$description."', '".$branchName."', '".$_SESSION['nicknake']."', '".$_SESSION['Branchid']."', '".$_SESSION['Branchid']."', '".$modelname."','".dateToday()."')";
        $query = $db->InsertData($insertHistory);
        /* End of Insert History */

        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>