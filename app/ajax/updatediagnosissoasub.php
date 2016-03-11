<?php 
include '../../include.php';
include '../include.php';

$request = filter_input(INPUT_POST, 'action');

if($request == "MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q="){
    $dataid = trim(filter_input(INPUT_POST, 'dataid', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $typetoedit = trim(filter_input(INPUT_POST, 'typetoedit', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $spliderdataid = split("-", $dataid);
    
    if($spliderdataid[0] != "default") {
    	if($typetoedit == "diagnosis"){
    		$selectedItem = trim(filter_input(INPUT_POST, 'selectedItem', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

	    	$udpatemainjod = "UPDATE `subjoborder` SET `subdiagnosis` = '".$selectedItem."', `updated_at` = '".dateToday()."' WHERE `subjobid` = '" . $dataid . "'";
	 		// echo $udpatemainjod;
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
    	}
    }if($typetoedit == "remove_diagnosis"){
    		$selectedItem = trim(filter_input(INPUT_POST, 'selectedItem', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	    	$udpatemainjod = "UPDATE `subjoborder` SET `subdiagnosis` = '', `updated_at` = '".dateToday()."' WHERE `subjobid` = '" . $dataid . "'";
	 		// echo $udpatemainjod;
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
    	}
    }else if($typetoedit == "tech"){
    	    $itemvalue = trim(filter_input(INPUT_POST, 'itemvalue2', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			
			$udpatemainjod = "UPDATE `subjoborder` SET `subtech` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `subjobid` = '" . $dataid . "'";
	 		// echo $udpatemainjod;
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    }else if($typetoedit == "remarks"){

    		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		// echo $dataid;
    		// echo $itemvalue;
			$udpatemainjod = "UPDATE `subjoborder` SET `subremarks` = '".$itemvalue."', `updated_at` = '".dateToday()."' WHERE `subjobid` = '" . $dataid . "'";
	 		// echo $udpatemainjod;
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
	 			echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
    	}else if($typetoedit == "updateparts"){
    		$partprice = trim(filter_input(INPUT_POST, 'partprice', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$parts = trim(filter_input(INPUT_POST, 'parts', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    		$udpatemainjod = "UPDATE `subjoborder` SET `subparts` = '".$parts."', `subcost` = '".$partprice."', `updated_at` = '".dateToday()."' WHERE `subjobid` = '" . $dataid . "'";
	 		echo $udpatemainjod;
	 		$query = $db->ExecuteQuery($udpatemainjod);
	 		if($query) {
		 		echo "success";
	 		}else{
	 			echo "error while updating diagnosis";
	 		}
	 		echo "bakit wala??";
    	}else{

    	}

   //  	else if($typetoedit == "item"){
   //  		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		// echo $dataid;
   //  		// echo $itemvalue;
			// $udpatemainjod = "UPDATE `jb_joborder` SET `item` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		echo "success";
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else if($typetoedit == "remarks"){

   //  		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		// echo $dataid;
   //  		// echo $itemvalue;
			// $udpatemainjod = "UPDATE `jb_joborder` SET `remarks` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		echo "success";
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else if($typetoedit == "tech"){

   //  		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		// echo $dataid;
   //  		// echo $itemvalue;
			// $udpatemainjod = "UPDATE `jb_joborder` SET `technicianid` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		echo "success";
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else if($typetoedit == "totalcharges"){
   //  		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			// $udpatemainjod = "UPDATE `jb_cost` SET `total_charges` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		echo "success";
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else if($typetoedit == "lessdeposit"){
   //  		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			// $udpatemainjod = "UPDATE `jb_cost` SET `less_deposit` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		echo "success";
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else if($typetoedit == "lessdiscount"){
   //  		$itemvalue = trim(filter_input(INPUT_POST, 'itemvalue', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
			// $udpatemainjod = "UPDATE `jb_cost` SET `less_discount` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		echo "success";
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else if($typetoedit == "updateparts"){

   //  		$partprince = trim(filter_input(INPUT_POST, 'partprice', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		// $total_charges = trim(filter_input(INPUT_POST, 'total_charges', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		$itemvalue = trim(filter_input(INPUT_POST, 'items', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		$product_id = trim(filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
   //  		$balance = trim(filter_input(INPUT_POST, 'balance', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

			// $reme =  str_replace("# ", "", $product_id);
			// $reme = substr($reme, 0, -1);
			// echo substr($reme, 1);

   //  		$udpatemainjod = "UPDATE `jb_joborder` SET `partsid` = '".substr($reme, 1)."', `parts` = '".$itemvalue."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
	 	// 	$query = $db->ExecuteQuery($udpatemainjod);
	 	// 	if($query) {
	 	// 		$udpatemainjod = "UPDATE `jb_cost` SET totalpartscost ='".$partprince."', `balance` = '".$balance."' WHERE `jobid` = '" . $spliderdataid[1] . "'";
		 // 		$query = $db->ExecuteQuery($udpatemainjod);
		 // 		if($query) {
		 // 			echo "success";
		 // 		}else{
		 // 			echo "error while updating diagnosis";
		 // 		}
	 	// 	}else{
	 	// 		echo "error while updating diagnosis";
	 	// 	}
   //  	}else{

   //  	}
    }else{
 		echo "success";
		// $udpatemainjod = "UPDATE `subjoborder` SET `subdiagnosis` = '".$selectedItem."' WHERE `subjobid` = '" . $spliderdataid[1] . "'";
 	// 	$query = $db->ExecuteQuery($udpatemainjod);
 	// 	if($query) {
 	// 		echo "success";
 	// 	}else{
 	// 		echo "error while updating diagnosis";
 	// 	}
    }
}
?>