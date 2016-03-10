<?php
include '../../include.php';
include '../include.php';
$query = $_GET["querytogenerate"];
$query = str_replace("percentage", "%", $query);
$filename = $_GET["filename"];

header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=".$filename.".xls");
?>
<table border="1">
    <tr>
    	<th>Job ID.</th>
		<th>Description</th>
		<th>Branch</th>
		<th>Name</th>
		<th>Date</th>
	</tr>
	<?php
	$selectparts = $db->ReadData(str_replace("~~", "+", $query ));
	foreach ($selectparts as $key => $value) {
	?>
	<tr>
        <td><?php echo $value['jobnumber']; ?></td>
        <td><?php echo $value['description']; ?></td>
        <td><?php echo $value['branch']; ?></td>
        <td><?php echo $value['name']; ?></td>
        <td><?php echo $value['created_at']; ?></td>
    </tr>
   	<?php } ?>
</table>