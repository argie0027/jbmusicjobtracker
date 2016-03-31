<?php
include '../../include.php';
include '../include.php';
$query = $_GET["querytogenerate"];
$query = str_replace("--", "-", $query);   
$query = str_replace("percentage", "%", $query);   

$type = $_GET["type"];
$year = isset($_GET["year"]) ? $_GET["year"] : '';
$id = isset($_GET["id"]) ?  $_GET["id"] : '';

$filename = $_GET["filename"];

if(isset($_GET['daterange'])){
$month = $_GET['daterange'];
$bydate = split ("to", $month);
}else{
$month = "to";
$bydate = split ("to", " to ");
}
 
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=".$filename.".xls");
if($type == "soa"){
?>
<table border="1">
    <tr>
    	<th>Job ID.</th>
		<th>Item</th>
		<th>Customer</th>
		<th>Diagnosis</th>
		<th>Parts</th> 
		<th>Total Cost</th> 
		<th>Repair Status</th> 
	</tr>
	<?php
		$selectparts = $db->ReadData(str_replace("~~", "+", $query ));
		foreach ($selectparts as $key => $value) {
			?>
            <tr>
                <td><?php echo $value['jobid']; ?></td>
                <td><?php echo $value['item']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['diagnosisitem']; ?></td>
                <td><?php echo str_replace("&lt;br&gt;","<br>",$value['parts']); ?></td>
                <td><?php
                if(isset($_GET['type'])){
                    if($type == "ready_for_claiming" OR $type == "unclaimed" ){
                        $quercost = "SELECT (totalpartscost + service_charges+ total_charges) as total FROM jb_cost WHERE  jobid = '".$value['jobid']."'";
                        $quercost =$db->ReadData($quercost);
                        if($quercost){
                            echo $quercost[0]['total'];
                        }
                    }else{
                        $quercost2 = "SELECT sum(subcost) as total FROM subjoborder WHERE  mainjob = '".$value['jobid']."'";
                        $quercost2 =$db->ReadData($quercost2);
                        $totalc =  (int) $quercost2[0]['total'] + $value['totalcost'];
                        echo "<b>P</b> ". number_format($totalc,2);   
                    }
                    }else{
                      $quercost2 = "SELECT sum(subcost) as total FROM subjoborder WHERE  mainjob = '".$value['jobid']."'";
                        $quercost2 =$db->ReadData($quercost2);
                        $totalc =  (int) $quercost2[0]['total'] + $value['totalcost'];
                        echo "<b>P</b> ". number_format($totalc,2);   
                    }
                ?></td>
                 <?php
	                $return = "";
	                if($value['repair_status'] == "Ready for Delivery") {
	                 $return = $return . "<td><small class=\"badge col-centered mrorange\">Ready for Delivery</small></td>";
	                }else if($value['repair_status'] == "Waiting for SOA Approval") {
	                    $return = $return . "<td><small class=\"badge col-centered bg-yellow\">".$value['repair_status']."</small></td>";
	                }else if($value['repair_status'] == "Ongoing Repair") {
	                    $return = $return . "<td><small class=\"badge col-centered bg-teal\">".$value['repair_status']."</small></td>";
	                }else if($value['repair_status'] == "Done-Ready for Delivery") {
	                    $return = $return . "<td><small class=\"badge col-centered mredilive\">Ready for Pickup</small></td>";
	                }else if($value['repair_status'] == "Claimed") {
	                    $return = $return . "<td><small class=\"badge col-centered bg-green\">Claimed</small></td>";
	                }else if($value['repair_status'] == "Ready for Claiming") {
	                    $return = $return . "<td><small class=\"badge col-centered mdone\">Ready for Claiming</small></td>";
	                } else{
	                    $return = $return . "<td><small class=\"badge col-centered approvedme\">".$value['repair_status']."</small></td>";
	                }
	                echo $return;
	            ?>
            </tr>
        <?php 
		}
	?>
</table>
<?php
}
else if($type == "joborder"){
?>
<table border="1">
    <tr>
    	<th>Job ID.</th>
		<th>Customer Name</th>
		<th>Branch</th>
		<th>Item Name</th>
		<th>Assign Tech</th> 
		<th>Remarks</th> 
		<th>Repair Status</th> 
	</tr>
		<?php 
			$selectparts = $db->ReadData(str_replace("~~", "+", $query));
			foreach ($selectparts as $key => $value) {
				?>
				<tr>
	                <td><?php echo $value['jobid']; ?></td>
	                <td><?php echo $value['name']; ?></td>
	                <td><?php echo $value['branch_name']; ?></td>
	                <td><?php echo $value['item']; ?></td>
	                <td><?php echo $value['technam']; ?></td>
	                <td><?php echo $value['remarks']; ?></td>
	                <td><?php  echo $value['repair_status']; ?></td>
                <tr>
				<?php
			}
		?>
</table>
<?php
} else if($type == "parts") {

?>
<table border="1">
    <tr>
    	<th>Stock #</th>
        <th>Part Name</th>
        <th>Model</th>
        <th>Description</th>
        <th>Brand</th>
        <th>Main Category</th>
        <th>Subcategory</th>
        <th>Stocks</th>
        <th>Cost</th>
	</tr>
		<?php 
			$selectparts = $db->ReadData(str_replace("~~", "+", $query));
			foreach ($selectparts as $key => $value) {

                $sqlmodel = "SELECT m.modelname, m.description, b.brandname, c.category, s.subcategory FROM jb_models m, jb_brands b, jb_partscat c, jb_partssubcat s WHERE m.brandid = b.brandid AND m.cat_id = c.cat_id AND m.sub_catid = s.subcat_id AND modelid = '".$value['modelid']."'";
                $querymodel = $db->ReadData($sqlmodel);
				?>
				<tr>
                    <td><?php echo $value['stocknumber']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td>
                        <?php 
                        if ($querymodel) {
                            echo $querymodel[0]['modelname'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($querymodel) {
                            echo $querymodel[0]['description'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($querymodel) {
                            echo $querymodel[0]['brandname'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($querymodel) {
                            echo $querymodel[0]['category'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($querymodel) {
                            echo $querymodel[0]['subcategory'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $sql = "SELECT sum(quantity) as quantity FROM jb_part WHERE part_id = '".$value['part_id']."'";
                        $queryd = $db->ReadData($sql);
                        if ($queryd[0]['quantity'] < 5) {
                            ?>
                            <small
                                class="badge col-centered bg-red"><?php
                                 echo $queryd[0]['quantity']; 
                                 ?></small>
                            <?php
                        } else if ($queryd[0]['quantity'] < 21) {
                            ?>
                            <small
                                class="badge col-centered bg-yellow"><?php
                                 echo $queryd[0]['quantity']; 
                                 ?></small>
                            <?php
                        } else {
                            ?>
                            <small
                                class="badge col-centered bg-green">
                                <?php
                                    echo $queryd[0]['quantity']; 
                                ?>
                            </small>
                            <?php
                        }
                        ?>
                    </td>
                    <td><?php echo "<b>P </b>" . number_format($value['cost'],2); ?></td>
                </tr>
				<?php
			}
		?>
</table>
<?php
} else if($type == "batchpart") {
    $selectparts = $db->ReadData(str_replace("~~", "+", $query));
    $sqlmodel = "SELECT m.modelname, m.description, b.brandname, c.category, s.subcategory FROM jb_models m, jb_brands b, jb_partscat c, jb_partssubcat s WHERE m.brandid = b.brandid AND m.cat_id = c.cat_id AND m.sub_catid = s.subcat_id AND modelid = '".$selectparts[0]['modelid']."'";
    $querymodel = $db->ReadData($sqlmodel);
?>
<table border="1">
    <thead>
        <tr>
            <th class="text-center">Stocknumber</th>
            <th class="text-center">Part Name</th>
            <th class="text-center">Model</th>
            <th class="text-center">Brand</th>
            <th class="text-center">Main Category</th>
            <th class="text-center">Subcategory</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $selectparts[0]['stocknumber']; ?></td>
            <td><?php echo $selectparts[0]['name']; ?></td>
            <td><?php echo $querymodel[0]['modelname']; ?></td>
            <td><?php echo $querymodel[0]['brandname']; ?></td>
            <td><?php echo $querymodel[0]['category']; ?></td>
            <td><?php echo $querymodel[0]['subcategory']; ?></td>
        </tr>
        <tr></tr>
    </tbody>
</table>

<table border="1">
    <thead>
        <tr>
            <th class="text-center">Batch Date</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Quantity Under Warranty</th>
            <th class="text-center">Batch Quantity</th>
            <th class="text-center">Cost</th>
            <th class="text-center">Total Price</th>
        </tr>                                    
    </thead>
    <tbody>
        <?php foreach ($selectparts as $key => $value) : ?>
        <tr>
            <td><?php echo $value['date']; ?></td>
            <td><?php echo $value['quantity']; ?></td>
            <td><?php echo $value['quantityfree']; ?></td>
            <td><?php echo $value['bacth_quantity']; ?></td>
            <td><?php echo $value['cost']; ?></td>
            <td><?php echo '<strong>P</strong> '.number_format($value['totalprice'],2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
}else if($type == "customer"){
?>
<table border="1">
    <tr>
    	<th>#</th>
        <th>Customer Name</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Branch</th>
        <th>Status</th>
	</tr>
		<?php 
			$selectparts = $db->ReadData(str_replace("~~", "+", $query));
			$m = 0;
			foreach ($selectparts as $key => $value) {

                $getStatus = "SELECT repair_status FROM jb_joborder WHERE customerid = '".$value['customerid']."' ORDER BY created_at DESC LIMIT 1";
                $getStatusQuery = $db->ReadData($getStatus); 

				$m++;
				?>
				<tr>
				<td><?php echo $m; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['number']; ?></td>
                <td><?php echo $value['email']; ?></td>
                <td><?php echo $value['branch_name']; ?></td>
                <td><?php if($getStatusQuery) { echo $getStatusQuery[0]['repair_status']; } ?></td>
                </tr>
				<?php
			}
		?>
</table>
<?php
}else if($type == "tech"){
?>
<table border="1">
<thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Current Tasks</th>
        <th>Total Earnings</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
    <?php 
          $selectparts = $db->ReadData(str_replace("~~", "+", $query));

         foreach ($selectparts as $key => $value) {

            $query  ="SELECT * FROM jb_joborder WHERE technicianid = '".$value['tech_id']."' ORDER BY created_at DESC";
            $query =$db->ReadData($query);

            $currenttast = "";

            if($query) {
                if($query[0]['repair_status'] != 'Ongoing Repair'){
                    $currenttast = "-";
                } else {
                    $currenttast =  $query[0]['jobid'] . " (". $query[0]['item'] . ")"; 
                }
            } else {
                $currenttast = "-";
            }

            $selecttechvalue = "SELECT (a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total, b.repair_status FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND b.technicianid = '".$value['tech_id']."' AND b.repair_status != 'Waiting for SOA Approval' AND b.repair_status != 'Approved' ";
            $totald =$db->ReadData($selecttechvalue);
            ?>
                <tr id="<?php echo $value['tech_id']; ?>" class="clickable">
                    <td><?php echo $value['tech_id']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><?php echo $currenttast; ?> </td>
                    <td><?php echo "<b>P </b>" . number_format($totald[0]['total'],2);?></td>
                    <td>
                        <?php
                            if($value['status'] == 1) {
                                ?>   <small class="badge col-centered bg-yellow">Not Available</small>
                                <?php 
                            }else{
                                ?>  <small class="badge col-centered bg-green">Available</small>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
            <?php 
        }
    ?>
</table>

<?php
}else if($type == "techindividual"){  
?>

<h3 class="box-title">Technician Info</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Current Tasks</th>
        <th>Status</th>
    </tr>
        <?php 
            $selectparts = $db->ReadData(str_replace("~~", "+", $query));
            foreach ($selectparts as $key => $value) {
                $techid = $value['tech_id'];
                $query  ="SELECT item, jobid FROM jb_joborder WHERE technicianid = '".$value['tech_id']."'";
                $query = $db->ReadData($query); 
                $selecttechvalue = "SELECT (a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total, b.repair_status FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND b.technicianid = '".$value['tech_id']."' AND b.repair_status != 'Waiting for SOA Approval' AND b.repair_status != 'Approved' ";
                $totald =$db->ReadData($selecttechvalue);

                $queryStatTech  ="SELECT * FROM jb_joborder WHERE technicianid = '".$value['tech_id']."' ORDER BY created_at DESC";
                $queryStatTech =$db->ReadData($queryStatTech);

                $currenttast = "";

                if($queryStatTech) {
                    if($queryStatTech[0]['repair_status'] != 'Ongoing Repair'){
                        $currenttast = "-";
                    } else {
                        $currenttast =  $queryStatTech[0]['jobid'] . " (". $queryStatTech[0]['item'] . ")"; 
                    }
                } else {
                    $currenttast = "-";
                }                    
                ?>
                <tr>
                    <td><?php echo $value['tech_id']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><?php echo $currenttast; ?> </td>
                    <td>
                        <?php
                            if($value['status'] == 1) {
                                ?>   <small class="badge col-centered bg-yellow">Not Available</small>
                                <?php 
                            }else{
                                ?>  <small class="badge col-centered bg-green">Available</small>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php
            }
        ?>
</table>
<?php

    $checkifstat = "SELECT * FROM tech_statistic where techid = '".$techid."'";
    $tcheck  = $db->ReadData($checkifstat);

    $repaired = "(SELECT COUNT(jobclear) FROM jb_joborder WHERE jobclear = 0 AND technicianid = '".$techid."' AND repair_status != 'Waiting for SOA Approval' AND repair_status != 'Approved' ) AS repaired";
    $canrepair = "(SELECT COUNT(jobclear) FROM jb_joborder WHERE jobclear = 1 AND technicianid = '".$techid."' AND repair_status != 'Waiting for SOA Approval' AND repair_status != 'Approved' ) AS cantrepair";

    if($tcheck){
        $queryjobs = "SELECT a.jobid, a.techid, b.totalpartscost, b.service_charges, b.total_charges, c.item, c.repair_status, c.jobclear, DATE_FORMAT(a.date_start, '%d %b %Y') as date_start , DATE_FORMAT(a.date_done, '%d %b %Y') as date_done, ".$repaired.", ".$canrepair." FROM tech_statistic a, jb_cost b, jb_joborder c WHERE a.jobid = b.jobid AND a.jobid = c.jobid AND a.techid = '".$techid."'";
    }else{
        $queryjobs = "SELECT b.jobid, b.totalpartscost, b.service_charges, b.total_charges, c.item, c.repair_status, c.jobclear FROM jb_cost b, jb_joborder c WHERE b.jobid = c.jobid AND c.technicianid = '".$techid."' AND b.repair_status != 'Waiting for SOA Approval' AND b.repair_status != 'Approved'";
    }
    $query3  = $db->ReadData($queryjobs);
?>

<h3 class="box-title">Summary total</h3>
<table border="1">
    <tr>
        <td>Earnings:</td>
        <td class="text-right"><strong>P </strong><?php echo number_format($totald[0]['total'], 2);?></td>
    </tr>
    <tr>
        <td>Job Orders: </td>
        <td class="text-right"><?php echo count($query3); ?></td>
    </tr>
    <tr>
        <td>Successfully Repaired: </td>
        <td class="text-right"><?php echo $query3[0]['repaired']; ?></td>
    </tr>
    <tr>
        <td>Can't Repair </td>
        <td class="text-right"><?php echo $query3[0]['cantrepair']; ?></td>
    </tr>
</table>

<h3 class="box-title">Job Order History</h3>
<table border="1">
    <tbody>
        <tr>
            <th style="width: 60px">Job ID</th>
            <th>Item</th>
            <th>Start Repair</th>
            <th>Done Repair</th>
            <th>Cost</th>
            <th style="width: 90px">Cant Repair</th>
            <th style="width: 40px">Status</th>
        </tr>

            <?php foreach ($query3 AS $value): ?>
            <?php $cantre = ($value['jobclear'] == 0 ) ? 'No' : 'Yes'; ?>
            <tr>
                <td><?php echo $value['jobid']; ?></td>
                <td><?php echo $value['item']; ?></td>
                <td><?php echo $value['date_start']; ?></td>
                <td><?php echo $value['date_done']; ?></td>
                <td><b>P </b><?php echo number_format($value['totalpartscost'] + $value['service_charges'], 2); ?></td>
                <td><?php echo $cantre;?></td>
                <td><span class="badge bg-green"><?php echo ($value['repair_status'] == 'Done-Ready for Delivery' ) ? 'Ready for Pickup' : $value['repair_status']; ?></span></td>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>
<?php
}else if($type  == "revenuew"){

    $currtYear = $year;
    $range = '';
    $range2 = '';
    if(isset($_GET['daterange'])){
        $range = " AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
        $range2 = " AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
    }

	$getalljobforbranch2 = "SELECT * FROM `jb_joborder` WHERE  isdeleted = 0 AND jobclear = 0 AND branchid = '".$id."' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
    $jobcounter = $db->GetNumberOfRows();


    $ongoing = "SELECT *  from `jb_joborder` WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."'  AND repair_status = 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $ongoingquery  = $db->ReadData($ongoing);
    $ongoingre = $db->GetNumberOfRows();

    $pending = "SELECT *  from `jb_joborder` WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."'  AND repair_status <> 'Claimed'  AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobfosdfrbranch  = $db->ReadData($pending);
    $pendingre = $db->GetNumberOfRows();


    $claimed = "SELECT *  from `jb_joborder` WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getallsdfjobforbranch  = $db->ReadData($claimed);
    $claimedre = $db->GetNumberOfRows();


	 $jan = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $jan  = $db->ReadData($jan);
    $jan = $db->GetNumberOfRows();
    $feb = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $feb  = $db->ReadData($feb);
    $feb = $db->GetNumberOfRows();
    $mar = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $mar  = $db->ReadData($mar);
    $mar = $db->GetNumberOfRows();
    $apr = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $apr  = $db->ReadData($apr);
    $apr = $db->GetNumberOfRows();
    $may = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $may  = $db->ReadData($may);
    $may = $db->GetNumberOfRows();
    $jun = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $jun  = $db->ReadData($jun);
    $jun = $db->GetNumberOfRows();
    $jul = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $jul  = $db->ReadData($jul);
    $jul = $db->GetNumberOfRows();
    $aug = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $aug  = $db->ReadData($aug);
    $aug = $db->GetNumberOfRows();
    $sep = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $sep  = $db->ReadData($sep);
    $sep = $db->GetNumberOfRows();
    $oct = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $oct  = $db->ReadData($oct);
    $oct = $db->GetNumberOfRows();
    $nov = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $nov  = $db->ReadData($nov);
    $nov = $db->GetNumberOfRows();
    $dev = "SELECT * FROM jb_joborder  WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."'".$range2."";
    $dev  = $db->ReadData($dev);
    $dev = $db->GetNumberOfRows();

    $jobcounter = $db->GetNumberOfRows();
    $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."'    AND repair_status = 'Ongoing Repair' ".$range2."";

	?>

    <h3>Branch Info</h3>
    <table class="table table-condensed" border="1">
        <tr>
            <th>Branch Name</th>
            <th>Contact Number</th>
            <th>Email Address</th>
        </tr>
        <?php
            $brachInfo = "SELECT * FROM jb_branch WHERE branch_id='".$id."'";
            $brachInfo = $db->ReadData($brachInfo);
        ?>
        <tr>
            <td><?php echo $brachInfo[0]['branch_name'];?></td>
            <td><?php echo $brachInfo[0]['number'];?></td>
            <td><?php echo $brachInfo[0]['email'];?></td>
        </tr>
        
    </table>

    <h3>Contact Person</h3>
    <table class="table table-condensed" border="1">
        <tr>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Email Address</th>
            <th>Address</th>
        </tr>
        <?php
            $contactInfo = "SELECT * FROM `jb_user` WHERE branch_id = '".$id."' AND position = '2'";
            $contactInfo = $db->ReadData($contactInfo);
        ?>
        <tr>
            <td><?php echo $contactInfo[0]['name'];?></td>
            <td><?php echo $contactInfo[0]['contact_number'];?></td>
            <td> <?php echo $contactInfo[0]['email'];?></td>
            <td><?php echo $contactInfo[0]['address'];?></td>
        </tr>
        
    </table>

    <h1></h1>
	<table class="table table-condensed" border="1">
        <tr>
            <th>Total Job Orders</th>
            <th>Revenue</th>
            <th>Pending Job Orders</th>
            <th>Ongoing Job Orders</th>
            <th>Done Job Orders</th>
        </tr>
        <tr>
            <td><?php echo $jobcounter;?></td>
            <td>
              <?php 

                $selecttechvalue = "SELECT SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' YEAR(b.created_at) = '".$currtYear."' ".$range."";

                $totald =$db->ReadData($selecttechvalue);
                echo "<b>P</b> ". number_format($totald[0]['total'],2);
              ?>
            </td>
            <td><?php echo $pendingre;?></td>
            <td><?php echo $ongoingre;?></td>
            <td> <?php echo $claimedre;?></td>
        </tr>
        
    </table>

	<table class="table table-condensed" border="1">
        <tr>
            <th style="width: 10px">Month</th>
            <th>Total Job Orders</th>
            <th>Monthly Revenue</th>
            <th>Pending Job Orders</th>
            <th>Ongoing Job Orders</th>
            <th>Done Job Orders</th>
        </tr>
        <tr>
            <td>January</td>
            <td><?php echo $jan;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 01 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
        <tr>
            <td>February</td>
            <td><?php echo $feb;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 02 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
           <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
        <tr>
            <td>March</td>
            <td><?php echo $mar;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 03 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
        <tr>
            <td>April</td>
            <td><?php echo $apr;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 04 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>May</td>
            <td><?php echo $may;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 05 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php

                    $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                    $ongoingquery  = $db->ReadData($ongoing);
                    echo $db->GetNumberOfRows();

                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>June</td>
            <td><?php echo $jun;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 06 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>July</td>
            <td><?php echo $jul;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 07 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>August</td>
            <td><?php echo $aug;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 08 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>September</td>
            <td><?php echo $sep;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 09 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>October</td>
            <td><?php echo $oct;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 10 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>November</td>
            <td><?php echo $nov;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 11 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>December</td>
            <td><?php echo $dev;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 12 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$_GET['id']."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
    </table>
</div><!-- /.box-body -->
<?php
}else if($type  == "salesreport"){

    $currtYear = $year;
    $range = '';
    $range2 = '';
    if(isset($_GET['daterange'])){
        $range = " AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
        $range2 = " AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
    }

    $getalljobforbranch2 = "SELECT * FROM isdeleted = 0 AND jobclear = 0 AND jb_joborder AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
    $jobcounter = $db->GetNumberOfRows();


    $ongoing = "SELECT *  from jb_joborder WHERE  isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $ongoingquery  = $db->ReadData($ongoing);
    $ongoingre = $db->GetNumberOfRows();

    $pending = "SELECT *  from jb_joborder WHERE  isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobfosdfrbranch  = $db->ReadData($pending);
    $pendingre = $db->GetNumberOfRows();

    $claimed = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getallsdfjobforbranch  = $db->ReadData($claimed);
    $claimedre = $db->GetNumberOfRows();


    $jan = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $jan  = $db->ReadData($jan);
    $jan = $db->GetNumberOfRows();
    $feb = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $feb  = $db->ReadData($feb);
    $feb = $db->GetNumberOfRows();
    $mar = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $mar  = $db->ReadData($mar);
    $mar = $db->GetNumberOfRows();
    $apr = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $apr  = $db->ReadData($apr);
    $apr = $db->GetNumberOfRows();
    $may = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $may  = $db->ReadData($may);
    $may = $db->GetNumberOfRows();
    $jun = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $jun  = $db->ReadData($jun);
    $jun = $db->GetNumberOfRows();
    $jul = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $jul  = $db->ReadData($jul);
    $jul = $db->GetNumberOfRows();
    $aug = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $aug  = $db->ReadData($aug);
    $aug = $db->GetNumberOfRows();
    $sep = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $sep  = $db->ReadData($sep);
    $sep = $db->GetNumberOfRows();
    $oct = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $oct  = $db->ReadData($oct);
    $oct = $db->GetNumberOfRows();
    $nov = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $nov  = $db->ReadData($nov);
    $nov = $db->GetNumberOfRows();
    $dev = "SELECT * FROM jb_joborder  WHERE  isdeleted = 0 AND jobclear = 0 AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $dev  = $db->ReadData($dev);
    $dev = $db->GetNumberOfRows();

    $getalljobforbranch2 = "SELECT * FROM jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
    $jobcounter = $db->GetNumberOfRows();

    ?>

    <table border="1">
        <tr>
            <th>Total Job Orders</th>
            <th>Revenue</th>
            <th>Pending Job Orders</th>
            <th>Ongoing Job Orders</th>
            <th>Done Job Orders</th>
        </tr>
        <tr>
            <td><?php echo $jobcounter;?></td>
            <td>
                <?php 
                $selecttechvalue = "SELECT SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND YEAR(b.created_at) = '".$currtYear."' ".$range."";

                $totald =$db->ReadData($selecttechvalue);
                echo "<b>P</b> ". number_format($totald[0]['total'],2);

                ?>
            </td>
            <td><?php echo $pendingre;?></td>
            <td><?php echo $ongoingre;?></td>
            <td> <?php echo $claimedre;?></td>
        </tr>
    </table>

    <table border="1">
        <tr>
            <th style="width: 10px">Month</th>
            <th>Total Job Orders</th>
            <th>Monthly Revenue</th>
            <th>Pending Job Orders</th>
            <th>Ongoing Job Orders</th>
            <th>Done Job Orders</th>
        </tr>
        <tr>
            <td>January</td>
            <td><?php echo $jan;?></td>
            <td>
                <?php 
                    $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 01 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                    $jant  = $db->ReadData($jant);
                    if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
        <tr>
            <td>February</td>
            <td><?php echo $feb;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 02 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
           <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
        <tr>
            <td>March</td>
            <td><?php echo $mar;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 03 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
        <tr>
            <td>April</td>
            <td><?php echo $apr;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 04 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>May</td>
            <td><?php echo $may;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 05 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php

                    $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                    $ongoingquery  = $db->ReadData($ongoing);
                    echo $db->GetNumberOfRows();

                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>June</td>
            <td><?php echo $jun;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 06 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>July</td>
            <td><?php echo $jul;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 07 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>August</td>
            <td><?php echo $aug;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 08 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>September</td>
            <td><?php echo $sep;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 09 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>October</td>
            <td><?php echo $oct;?></td>
            <td>
                <?php 
                    $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 10 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                    $jant  = $db->ReadData($jant);
                    if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>November</td>
            <td><?php echo $nov;?></td>
            <td>
                <?php 
                    $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 11 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                    $jant  = $db->ReadData($jant);
                    if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr><tr>
            <td>December</td>
            <td><?php echo $dev;?></td>
            <td>
                <?php 
                $jant = "SELECT  SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND  MONTH(b.created_at) = 12 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                $jant  = $db->ReadData($jant);
                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                ?>
            </td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status <> 'Claimed' AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td> <?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
            <td><?php 
                $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                $ongoingquery  = $db->ReadData($ongoing);
                echo $db->GetNumberOfRows();
                ?></td>
        </tr>
    </table>
</div><!-- /.box-body -->
<?php
}else if($type  == "branch"){
?>
<table border="1">
    <thead>
        <tr>
            <th>Branch ID</th>
            <th>Branch Name</th>
            <th>Total Jobs</th>
            <th>Revenue</th>
        </tr>
    </thead>
    <?php
    $selectparts = $db->ReadData(str_replace("~~", "+", $query ));
    foreach ($selectparts as $key => $value) {

        $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.jobclear = 0 AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.branchid = '".$value['branch_id']."' AND a.isdeleted = '0'  ORDER BY created_at DESC";
        $getcountjob = $db->ReadData($qu);
        $jobcount = $db->GetNumberOfRows();

            $selecttechvalue = "SELECT (a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid = '".$value['branch_id']."'";;
        $totald =$db->ReadData($selecttechvalue);
    ?>
    <tr id="<?php echo $value['branch_id']; ?>" data-name = "<?php echo $value['branch_name']; ?>" class="clickable">
        <td><?php echo $value['branch_id']; ?></td>
        <td><?php echo $value['branch_name']; ?></td>
        <td><?php echo $jobcount; ?></td>
        <td><?php echo "<b>P </b>" . number_format($totald[0]['total'],2);?></td>
    </tr>
    <?php } ?>
</table>
<?php
}else if($type  == "stafflists"){
?>
<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Lastname</th>
            <th>Firstname</th>
            <th>Middle Initial</th>
            <th>Nickname</th>
            <th>Address</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Job Title</th>
            <th>Date Added</th>
            <th>Status</th>
        </tr>
    </thead>
<tbody>
    <?php
        $query = $db->ReadData(str_replace("~~", "+", $query ));
        $counter = 0;
    ?>
    <?php foreach ($query as $key => $value): ?>
        <?php $counter++; ?>
        <tr class='clickable text-center' id='<?php echo $value['id']; ?>' class='clickable'>
            <td><?php echo $counter; ?></td>
            <td><?php echo $value['lastname']; ?></td>
            <td><?php echo $value['firstname']; ?></td>
            <td><?php echo $value['midname']; ?></td>
            <td><?php echo $value['nicknake']; ?></td>
            <td><?php echo $value['address']; ?></td>
            <td><?php echo $value['contact_number']; ?></td>
            <td><?php echo $value['email']; ?></td>
            <td><?php echo $value['job_title']; ?></td>
            <td><?php echo $value['created_at']; ?></td>
            <td><?php echo ucfirst($value['status']); ?></td>
         </tr>
    <?php endforeach; ?>
</tbody>
</table>
<?php
}else if($type  == "diagnosis"){
?>
<table border="1">
    <thead>
        <tr>
            <th style="width: 50px">ID #</th>
            <th>Diagnosis</th>
        </tr>
    </thead>
    <?php
    $selectparts = $db->ReadData(str_replace("~~", "+", $query ));
    $counter = 0;
    foreach ($selectparts as $key => $value) {
        $counter++;
    ?>

    <tr>
        <td><?php echo $counter; ?></td>
        <td><?php echo $value['diagnosis']; ?></td>
    </tr>
    <?php } ?>
</table>
<?php
}else if($type  == "brands"){
?>
<table border="1">
    <thead>
        <tr>
            <th style="width: 50px">#</th>
            <th>Name</th>
        </tr>
    </thead>
    <?php
    $selectparts = $db->ReadData(str_replace("~~", "+", $query ));
    $counter = 0;
    foreach ($selectparts as $key => $value) {
        $counter++;
    ?>
    <tr>
        <td><?php echo $counter?></td>
        <td><?php echo $value["brandname"]?></td>
    </tr>
    <?php } ?>
</table>
<?php
}else if($type  == "category"){
?>
<table border="1">
    <thead>
        <tr>
            <th style="width: 50px">#</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Parts Free</th>
            <th>Diagnostic Free</th>
            <th>Generic</th>
        </tr>
    </thead>
    <?php

    $day   = 29; // Per day
    $month = 30; // Per month
    $year  = 365; // Per year

    for ( $i=1; $i<$day+1; $i++ ) { $ttotalDay[$i] = $i; } // Computed value for day
    for ( $i=1; $i<12; $i++ ) { $ttotalMonth[$i] = $i*$month; } // Computed value for month
    for ( $i=1; $i<6; $i++ ) { $ttotalYear[$i] = $i*$year; } // Computed value for year
    $selectparts = $db->ReadData(str_replace("~~", "+", $query ));
    $counter = 0;

    foreach ($selectparts as $key => $value) {
        $parts_free = explode(",",$value['parts_free']);

        if( $parts_free[0] <= $day ) {
            $word = ( $parts_free[1] > 1 ) ? ' Days' : ' Day';
            $parts_free = $parts_free[1].$word;
        } else if ( $parts_free[0] >= $day && $parts_free[0] < $year ) {
            $word = ( $parts_free[1] > 1 ) ? ' Months' : ' Month';
            $parts_free = $parts_free[1].$word;
        } else  {
            $word = ( $parts_free[1] > 1 ) ? ' Years' : ' Year';
            $parts_free = $parts_free[1].$word;
        }

        $diagnostic_free = explode(",",$value['diagnostic_free']);

        if( $diagnostic_free[0] <= $day ) {
            $word = ( $diagnostic_free[1] > 1 ) ? ' Days' : ' Day';
            $diagnostic_free = $diagnostic_free[1].$word;
        } else if ( $diagnostic_free[0] >= $day && $diagnostic_free[0] < $year ) {
            $word = ( $diagnostic_free[1] > 1 ) ? ' Months' : ' Month';
            $diagnostic_free = $diagnostic_free[1].$word;
        } else  {
            $word = ( $diagnostic_free[1] > 1 ) ? ' Years' : ' Year';
            $diagnostic_free = $diagnostic_free[1].$word;
        }
        $counter++;
    ?>
    <tr>
        <td><?php echo $counter; ?></td>
        <td class='dia'><?php echo $value['category']; ?></td>
        <td class='dia'><?php echo $value['subcategory']; ?></td>
        <td class='dia'><?php echo $parts_free; ?></td>
        <td class='dia'><?php echo $diagnostic_free; ?></td>
        <td class='dia'><?php echo ucfirst($value['generic']); ?></td>
    </tr>
<?php } ?>
</table>
<?php
}else if($type  == "models"){
?>
<table border="1">
    <thead>
        <tr>
            <th style="width: 50px">#</th>
            <th>Model Name</th>
            <th>Description</th>
            <th>Brand</th>
            <th>Main Category</th>
            <th>Sub Category</th>
            <th>Generic</th>
        </tr>
    </thead>
    <?php
    $selectparts = $db->ReadData(str_replace("~~", "+", $query ));
    $counter = 0;
    foreach ($selectparts as $key => $model) {
        $counter++;
        $querybrand = $db->ReadData("SELECT brandname FROM jb_brands WHERE brandid = '".$model['brandid']."'");
        $querycategory = $db->ReadData("SELECT category, generic FROM jb_partscat WHERE cat_id = '".$model['cat_id']."'");
        $querysubcategory = $db->ReadData("SELECT subcategory FROM jb_partssubcat WHERE subcat_id = '".$model['sub_catid']."'");
    ?>
    <tr>
        <td><? echo $counter; ?></td>
        <td><? echo $model["modelname"]; ?></td>
        <td><? echo $model["description"]; ?></td>
        <td><?php if ($querybrand) { echo $querybrand[0]['brandname']; } ?></td>
        <td><?php if ($querycategory) { echo $querycategory[0]['category']; } ?></td>
        <td> <?php if ($querysubcategory) {echo $querysubcategory[0]['subcategory']; } ?></td>
        <td> <?php if ($querycategory) {echo ucfirst($querycategory[0]['generic']); } ?></td>
    </tr>
    <?php } ?>
</table>
<?php
}
?>
