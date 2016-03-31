<?php
include '../../include.php';
include '../include.php';
$query = $_GET["querytogenerate"];
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

// echo '<pre>';
// var_dump($db->ReadData(str_replace("~~", "+", $query )));
// exit;
header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=".$filename.".xls");
if($type == "soa2"){
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
                           echo "<b>P</b> ".number_format($quercost[0]['total'],2);
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
}else if($type == "branchcustomer"){
    ?>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>
            <?php 
                $m = 0;
                $selectparts = $db->ReadData(str_replace("~~", "+", $query));
                foreach ($selectparts as $key => $value) {
                    $m++;
                    ?>
                    <tr>
                        <td><?php echo $m; ?></td>
                        <td><?php echo $value['name']; ?></td>
                        <td><?php echo $value['number']; ?></td>
                        <td><?php echo $value['email']; ?></td>
                    </tr>
            <?php
                }
            ?>
        </thead>
    </table>
    <?php 
}else if($type == "salesreportbranch"){
    
    $currtYear = $year;
    $range = '';
    $range2 = '';
    if(isset($_GET['daterange'])){
        $range = " AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
        $range2 = " AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
    }

    $getalljobforbranch2 = "SELECT * FROM jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid = '".$id."' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
    $jobcounter = $db->GetNumberOfRows();

    $ongoing = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."'  AND repair_status = 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $ongoingquery  = $db->ReadData($ongoing);
    $ongoingre = $db->GetNumberOfRows();

    $pending = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."'  AND repair_status <> 'Claimed'  AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range2."";
    $getalljobfosdfrbranch  = $db->ReadData($pending);
    $pendingre = $db->GetNumberOfRows();

    $claimed = "SELECT *  from jb_joborder WHERE isdeleted = 0 AND jobclear = 0 AND branchid  = '".$id."' AND (repair_status = 'Claimed' OR repair_status = 'Ready for Claiming') AND YEAR(created_at) = '".$currtYear."' ".$range2."";
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

    ?>

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
                $selecttechvalue = "SELECT SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND branchid  = '".$id."' AND a.jobid = b.jobid AND YEAR(b.created_at) = '".$currtYear."' ".$range."";

                $totald = $db->ReadData($selecttechvalue);
                echo "<b>P </b> ".number_format($totald[0]['total'],2);
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
}
?>
