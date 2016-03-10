<?php
include '../../include.php';
include '../include.php';

// $query = $_POST["querytogenerate"];
// $type = $_POST["type"];

// The function header by sending raw excel
header("Content-type: application/vnd-ms-excel");
 
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=codelution-export.xls");

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
		$selectparts = $db->ReadData("SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges + e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Done-Ready for Delivery' ORDER BY a.created_at DESC");
		foreach ($selectparts as $key => $value) {
			?>
            <tr>
                <td><?php echo $value['jobid']; ?></td>
                <td><?php echo $value['item']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['name']; ?></td>
            </tr>
        <?php 
		}
	?>
</table>
