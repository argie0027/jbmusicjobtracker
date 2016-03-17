<?php 
include '../../include.php';
include '../include.php';
$request = filter_input(INPUT_POST, 'action');
if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){

    $category = trim(ucwords(strtolower(filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS))));
    $generic = trim(filter_input(INPUT_POST, 'generic', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $subcategory = $_POST['subcategory'];
    $partfree = $_POST['subcategoryPartFree'];
    $diagnosticfree = $_POST['subcategoryDiagnosticFree'];

    $insertCategory = "INSERT INTO `jb_partscat`(`category`, `generic`, `created_at`) VALUES ('".$category."','".$generic."','".dateToday()."')";
    $query = $db->InsertData($insertCategory);
    $lastcategoryid = $db->GetLastInsertedID();

    foreach ( $subcategory as $key => $value ) {
        $insertSubcategory = "INSERT INTO `jb_partssubcat`(`subcategory`, `cat_id`, `parts_free`, `diagnostic_free`,`created_at`) VALUES ('".trim(ucwords(strtolower($value)))."', '".$lastcategoryid."', '".$partfree[$key]."', '".$diagnosticfree[$key]."', '".dateToday()."')";
        $db->InsertData($insertSubcategory);
    }

	if($query){
        echo "success";
	}else {
        echo $db->GetErrorMessage();
        echo "error inner";
    }
}
?>