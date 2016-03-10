<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $categoryName = trim(ucwords(strtolower(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $subcategory = $_POST['subcategory'];
    $partfree = $_POST['subcategoryPartFree'];
    $diagnosticfree = $_POST['subcategoryDiagnosticFree'];

    $checker = "UPDATE `jb_partscat` SET `category` = '".$categoryName."' WHERE `cat_id` = '".$id."'";
 	$query = $db->ExecuteQuery($checker);

    /* EXCLUDE */
    $sqlSubcat = "SELECT * FROM `jb_partssubcat` WHERE cat_id = '".$id."'";
    $querySubcat = $db->ReadData($sqlSubcat);
    foreach ($querySubcat as $key => $value) {
        $sql = "DELETE FROM `jb_partssubcat` WHERE cat_id = '" . $value['cat_id'] . "'";
        $query = $query = $db->InsertData($sql);
    }

    /* INCLUDE */
    foreach ( $subcategory as $key => $value ) {
        $insertSubcategory = "INSERT INTO `jb_partssubcat`(`subcategory`, `cat_id`, `parts_free`, `diagnostic_free`,`created_at`) VALUES ('".trim(ucwords(strtolower($value)))."', '".$id."', '".$partfree[$key]."', '".$diagnosticfree[$key]."',NOW())";
        $db->InsertData($insertSubcategory);
    }

    if($query) {
    	echo "success";
    }else {
        echo $db->GetErrorMessage();
    	echo "error out";
    }
}
?>