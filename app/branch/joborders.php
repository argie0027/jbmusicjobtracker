<?php
include '../../include.php';
include '../ui_branch.php';
htmlHeader('dashboard');
global $url;

$queryforexcel = "";

    # Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

    if($_SESSION['position'] != 2) {
        foreach ($permission as $key => $value) {
            if($value['name'] == 'job_orders') {
                $job_orders = true;
            }
        }

        if(!isset($job_orders)) {
            echo '<script>window.location = "dashboard.php";</script>';
            exit();
        }
    }
?>
<!-- header logo: style can be found in header.less -->
<?php
 $name = $_SESSION['Branchid'];
 
        if($_SESSION['position'] == 0) {
            $name = "JB Main Office";    
        }else {
            $sql = "SELECT branch_name FROM jb_branch WHERE branch_id = '" .$name. "'";
            
             $query = $db->ReadData($sql);
             $name =  $query[0]['branch_name'];
        }
        
        $sql2 = "SELECT a.*, b.jobid as joborderid, b.branchid as branchidmo FROM notitemp a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid = '".$_SESSION['Branchid']."' AND a.branch_id = '0' ORDER BY `created_at` DESC";
        $query2 = $db->ReadData($sql2);

        $counterviewed = "SELECT * FROM notitemp WHERE  branch_id <> '0' AND isViewed <> '1' ORDER BY `created_at` DESC";
        $counterviewed = $db->ReadData($counterviewed);

        headerDashboard($name, $query2, count($counterviewed)); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <?php sidebarHeader(); ?>
            <?php
            $qu = "";
            $headertitle = "";
        if(isset($_GET['daterange'])){
            $bydate = split ("to", $_GET['daterange']);
            if(!isset($_GET['type'])){
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.branchid = '".$_SESSION['Branchid']."' AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                    $query = $db->ReadData($qu);
                     $queryforexcel = $qu;
                    // echo $qu;
                    $_SESSION['jobcount'] = $db->GetNumberOfRows();
                    sidebarMenu($db->GetNumberOfRows());
                    $headertitle = "Job Orders";
            }else {
                $type = $_GET['type'];
                if($type == "today") {
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id) AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  AND a.done_date_delivery = CURDATE() AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                    $headertitle = "Expected Job Order Arriving Today";
                }else if($type == "waiting_for_soa_approval"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting for SOA Approval' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                     $headertitle = "Waiting for Soa Approval";
                }else if($type == "waiting_list"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting List' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                     $headertitle = "Waiting for Soa Approval";
                }else if($type == "ready_for_delivery"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ready for Delivery' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                    $headertitle = "Ready for Delivery";
                }else if($type == "ongoing_repair"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ongoing Repair' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                    $headertitle = "Ongoing Repair";
                }else if($type == "unclaimed"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Unclaimed' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                    $headertitle = "Unclaimed";
                }else if($type == "Claimed"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Claimed' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                    $headertitle = "Claimed Job Order";
                }else if($type == "finish"){
                    $headertitle = "Job Orders";
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0 AND a.branchid = '".$_SESSION['Branchid']."' AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                }else {
                    $headertitle = "Job Orders";
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0' AND a.branchid = '".$_SESSION['Branchid']."' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                } 
                $queryforexcel = $qu;
                $query = $db->ReadData($qu);
                sidebarMenu($db->GetNumberOfRows());
            }

        }else{

            if(!isset($_GET['type'])){
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.branchid = '".$_SESSION['Branchid']."' AND a.isdeleted = '0'  ORDER BY created_at DESC";
                     $queryforexcel = $qu;
                    $query = $db->ReadData($qu);
                    $_SESSION['jobcount'] = $db->GetNumberOfRows();
                    sidebarMenu($db->GetNumberOfRows());
                    $headertitle = "Job Orders";
            }else {
                $type = $_GET['type'];
                if($type == "today") {
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id) AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  AND a.done_date_delivery = CURDATE() ORDER BY created_at DESC";
                    $headertitle = "Expected Job Order Arriving Today";
                }else if($type == "waiting_for_soa_approval"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting for SOA Approval' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                     $headertitle = "Waiting for Soa Approval";
                }else if($type == "waiting_list"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting List' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                     $headertitle = "Waiting for Soa Approval";
                }else if($type == "ready_for_delivery"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ready for Delivery' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                    $headertitle = "Ready for Delivery";
                }else if($type == "ongoing_repair"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ongoing Repair' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                    $headertitle = "Ongoing Repair";
                }else if($type == "unclaimed"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Unclaimed' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                    $headertitle = "Unclaimed";
                }else if($type == "Claimed"){ 
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Claimed' AND a.branchid = '".$_SESSION['Branchid']."'  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                    $headertitle = "Claimed Job Order";
                }else if($type == "finish"){
                    $headertitle = "Job Orders";
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0 AND a.branchid = '".$_SESSION['Branchid']."' AND a.isdeleted = '0'  ORDER BY created_at DESC";
                }else {
                    $headertitle = "Job Orders";
                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0' AND a.branchid = '".$_SESSION['Branchid']."'  ORDER BY created_at DESC";
                } 
                $queryforexcel = $qu;
                $query = $db->ReadData($qu);
                sidebarMenu($db->GetNumberOfRows());
            }


        }
             ?>
        </section>
        <!-- /.sidebar -->
    </aside>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <?php breadcrumps($headertitle); ?>
        <!-- Main content -->
<section class="content">
<div class="form-group pull-right exportoexcel">
    <div class="input-group">
        <button class="btn  btn-default pull-right" id="createexcel">
          <i class="fa fa-file-text-o"></i> Export to Excel
        </button>
    </div>
</div><!-- /.form group -->
 <div class="form-group daterange-btn">
    <div class="input-group">
        <button class="btn btn-default pull-right" id="daterange-btn">
            <i class="fa fa-calendar"></i> Select by Date Range
            <i class="fa fa-caret-down"></i>
        </button>
    </div>
</div><!-- /.form group -->
<div class="clear"></div>

<div class="box">
    <div class="box-body table-responsive">
        <table id="example1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Customer Name</th>
                    <th>Branch</th>
                    <th>Item Name</th>
                    <th>Assing Tech</th>
                    <th>Remarks</th>
                    <th><small>Repair Status</small></th>
                </tr>
            </thead>
<tbody>
    <?php

    foreach ($query as $key => $value) {
        $return = "<tr id='".$value['jobid']."' class='clickable'>
            <td>".$value['jobid']."</td>
            <td>".$value['name']."</td>
            <td>".$value['branch_name']."</td>
            <td>".$value['item']."</td>
            <td>".$value['technam']."</td>
            <td>".$value['remarks']."</td>";
                        if($value['repair_status'] == "Ready for Delivery") {
                            $return = $return . "<td><small class=\"badge col-centered mlightred\">Ready for Pickup</small></td>";
                        }else if($value['repair_status'] == "Waiting for SOA Approval") {
                            $return = $return . "<td><small class=\"badge col-centered mrorange\">Waiting for Customer Approval</small></td>";
                        }else if($value['repair_status'] == "Waiting List") {
                            $return = $return . "<td><small class=\"badge col-centered morange\">".$value['repair_status']."</small></td>";
                        }else if($value['repair_status'] == "Ongoing Repair") {
                            $return = $return . "<td><small class=\"badge col-centered bg-teal\">".$value['repair_status']."</small></td>";
                        }else if($value['repair_status'] == "Done-Ready for Delivery") {
                            $return = $return . "<td><small class=\"badge col-centered mredilive\">Job Order Arriving Today</small></td>";
                        }else if($value['repair_status'] == "Claimed") {
                            $return = $return . "<td><small class=\"badge col-centered bg-green\">Claimed</small></td>";
                        }else if($value['repair_status'] == "Ready for Claiming") {
                            $return = $return . "<td><small class=\"badge col-centered mred\">Unclaimed</small></td>";
                        }else{
                            $return = $return . "<td><small class=\"badge col-centered approvedme\">".$value['repair_status']."</small></td>";
                        }
        $return = $return . "</tr>";
        echo $return;
    }
    ?>
</tbody>
</table>
</div><!-- /.box-body -->
</div><!-- /.box -->
</section><!-- /.content -->
</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
<!-- COMPOSE MESSAGE MODAL -->
<div class="modal fade" id="create-joborder" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Create Job Order</h4>
        </div>
        <div class="modal-body">
            <?php
            $sql = "SELECT tech_id, Name FROM jb_technicians";
            $query = $db->ReadData($sql);
            $re = "";
            foreach ($query as $key => $value) {
            $re = $re . "<option value='".$value['tech_id']."'>".$value['Name']."</option>";
            }
            
             $diagnosis = "SELECT * FROM `jb_diagnosis`";
            $diagnosisquery = $db->ReadData($diagnosis);
            $diag = "";
            foreach ($diagnosisquery as $key => $value) {
            $diag = $diag . "<option value='".$value['id']."'>".$value['diagnosis']."</option>";
            }

            createJobForm($re,  $diag);
            ?>
        </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="edit-joborder" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Update Job Order</h4>
        </div>
        <div class="modal-body">
            <?php
            $sql = "SELECT tech_id, Name FROM jb_technicians";
            $query = $db->ReadData($sql);
            $re = "";
            foreach ($query as $key => $value) {
            $re = $re . "<option value='".$value['tech_id']."'>".$value['Name']."</option>";
            }

            $diagnosis = "SELECT * FROM `jb_diagnosis`";
            $diagnosisquery = $db->ReadData($diagnosis);
            $diag = "";
            foreach ($diagnosisquery as $key => $value) {
            $diag = $diag . "<option value='".$value['id']."'>".$value['diagnosis']."</option>";
            }

            editjoborderform($re,  $diag);

            ?>
        </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- COMPOSE MESSAGE MODAL -->
        <div class="modal fade" id="create-payment" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-credit-card"></i> Partial Payment</h4>
                    </div>
                    <div class="modal-body">
                         <form id="payment_process" name="createjob" method="post" role="form">

                         <div class="form-group password-container"> 
                            <label>Password</label>
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                
                                <input type="password" id="validatepasword" name="password" class="form-control" placeholder="Enter password to verify">
                            </div>
                            <center><img id="loader" height="27" class="pull-center" src="<?php echo SITE_IMAGES_DIR; ?>loading_spinner.gif"></center>
                            <br>
                        </div>

                        <div class="alert alert-warning alert-dismissable password_wrong">
                            <i class="fa fa-warning"></i>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <b>Alert!</b> Invalid Password, Please try again.
                        </div>

                        <div class="alert alert-success alert-dismissable password_notifiear">
                            <i class="fa fa-check"></i>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <b>Success!</b> Your password is correct.
                        </div>

                        <div class="sdfsdf">
                            
                        </div>
                        <div class="clear"></div>
                    
                    </div>
                    
                    <div class="modal-footer clearfix">
                        <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                        <button type="submit" id="savejob" class="btn btn-success showhide"><i class="fa fa-plus"></i> Submit </button>
                    </div>
                    </form>
                    </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                 <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete <br>Job order No.: <span id="idhere2"></span><br> Customer Name: <span id="customerhere2"></span><br> Item: <span id="itemhere2"></span></h4>
                    </div>
                    <div class="modal-body text-right">
                         <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         <button type="submit" id="deleteitem" class="btn btn-danger cancel-delet"><i class="fa fa-plus"></i> Delete </button>
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                 <div class="modald">
                    <img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif">
                </div>
                <?php 
                    modald();
                    selectrecord();
                 ?>

        <script type="text/javascript">
            $(function() {

            var ID = "";
            var customerID = "";
            var joborderid = "";
            var idSelectedCustomer = "";

            //get parameters on url
            var getUrlParameter = function getUrlParameter(sParam) {
                var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : sParameterName[1];
                    }
                }
            };

            $('#createexcel').on('click', function(){

                <?php if(isset($_GET['daterange'])) { ?>
                    var daterange = getUrlParameter('daterange').split('to');
                    var filter = $('#example1_filter label input').val();

                    <?php if(!isset($_GET['type'])) { ?>
                        if ( filter.length ) {
                            var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND a.isdeleted = '0'  ORDER BY a.created_at DESC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }
                    <?php } else { ?>

                        <?php $type = $_GET['type'];
                        if($type == "today") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id) AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  AND a.done_date_delivery = CURDATE() ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "waiting_for_soa_approval") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting for SOA Approval' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "waiting_list"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting List' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ready_for_delivery"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ready for Delivery' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ongoing_repair"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ongoing Repair' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "unclaimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Unclaimed' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "Claimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Claimed' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "finish"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0 AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } else { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0' AND a.branchid = <?php echo $_SESSION['Branchid'] ?> ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } ?>

                    <?php } ?>

                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcelbranch.php?querytogenerate='+query+"&&type=joborder&&filename=joborder_branch_excel";
                    window.location = page;// you can use window.open also

                <?php } else { ?>
                    var filter = $('#example1_filter label input').val();
                    <?php if(!isset($_GET['type'])) { ?>
                        if ( filter.length ) {
                            var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND a.isdeleted = '0'  ORDER BY created_at DESC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }
                    <?php } else { ?>
                        <?php $type = $_GET['type']; 
                        if($type == "today") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id) AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND a.isdeleted = '0'  AND a.done_date_delivery = CURDATE() ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "waiting_for_soa_approval") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting for SOA Approval' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "waiting_list"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Waiting List' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ready_for_delivery"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ready for Delivery' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ongoing_repair"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Ongoing Repair' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "unclaimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Unclaimed' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "Claimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.repair_status = 'Claimed' AND a.branchid = <?php echo $_SESSION['Branchid'] ?>  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "finish"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0 AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND a.isdeleted = '0'  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } else { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0' AND a.branchid = <?php $_SESSION['Branchid'] ?>  ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } ?>

                    <?php } ?>

                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcelbranch.php?querytogenerate='+query+"&&type=joborder&&filename=joborder_branch_excel";
                    window.location = page;// you can use window.open also

                <?php } ?>
            });


            $('#daterange-btn').daterangepicker(
            {
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last 7 Days': [moment().subtract('days', 6), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            startDate: moment().subtract('days', 29),
            endDate: moment()
            },
            function(start, end) {
                    // alert(document.URL);
                    <?php 
                        if(isset($_GET['daterange'])){
                            ?>
                                var newurl =  document.URL.split("daterange");
                                var newu = newurl[0].replace("&&", "");
                                window.location.assign("" + newu + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                            <?php
                        }else{
                            if(isset($_GET['type'])){
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>branch/joborders.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>branch/joborders.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );

                                <?php 
                            }
                            ?>
                            <?php
                        }
                    ?>
                   
            }
            );

            $("#search_customers").keyup(function(){
                var toSearch = $("#search_customers").val();
                $('.search-list').html("");
                $('.search-list-result').slideDown('fast');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/search_customer.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        toSearch: toSearch,
                        branchid: '<?php echo $_SESSION['Branchid'];?>'
                    },
                    success: function(e){
                            
                            if(e != 'error'){
                                var obj = jQuery.parseJSON(e);
                                var data = "";
                                for (var i = 0; i < obj.response.length; i++) {
                                    $('.search-list').append("<option value='"+obj.response[i].customerid+"~"+obj.response[i].name+"~"+obj.response[i].number+"~"+obj.response[i].email+"~"+obj.response[i].address+"~"+obj.response[i].customer_type_id+"'>"+obj.response[i].name+"</option>");
                                };
                            }
                    }
                });


            });
            $('.search-list').change(function(){
                console.log($(this).val());
                $("#search_customers").value =$(this).val();
                 // $('.search-list').fadeOut('fast');
                 var m = $(this).val();
                 var re  = m.toString().split('~');
                     $("#search_customers").val(re[1]);
                        idSelectedCustomer = re[0];
                        // $('input[name="name"]').val(obj.response[0].created_at);
                        $('input[name="name"]').val(re[1]);
                        $('input[name="address"]').val(re[4]);
                        $('input[name="number"]').val(re[2]);
                        $('input[name="email"]').val(re[3]);
                        $('select[name="customertype"]').val(re[5]);
                            
                     $('.search-list-result').slideUp('fast');
                     $('.hide_existing_second').slideDown('fast');
            });
            $("#existingc").change(function(){
                if($(this).val() == 1) {
                     $('.hide_existing_first').slideDown('fast');
                     $('.hide_existing_second').slideUp('fast');
                }else if($(this).val() == 0){
                     $('.hide_existing_first').slideUp('fast');
                     $('.hide_existing_second').slideDown('fast');
                }

            });
            $(".add").on('click',function(){
                $("#create-joborder").modal('show');
                $('input[name="name"]').val("");
            });
            $("select[name='isjbitem']").change(function(){
                if ($(this).val() == 0) {
                    $('[name="servicefee"]').val(800.00);
                    $("input[name='warranty_date']").val('');
                    $('#tableinfocard tbody').html('');
                    $('.hideshow.warranty-date, .hideshow.info-card').fadeOut('fast');
                } else {
                    $('[name="servicefee"]').val(0.00);
                    $('.hideshow.warranty-date, .hideshow.info-card').fadeIn('fast');
                }
            });
            $("select[name='maincategory']").change(function(){
                $('#tableinfocard tbody').html('');

                if($(this).val() != 0 && $("select[name='isjbitem']").val() == 1 ) {
                    $('.hideshow.warranty-date').fadeIn('fast');
                }else {
                    $('.hideshow.warranty-date, .hideshow.info-card').fadeOut('fast');
                }
            });
            $("input[name='warranty_date']").change(function(){
                var maincategory = $("select[name='maincategory']").val();
                var warranty_date = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: '../ajax/viewinfocard.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        maincategory: maincategory,
                        warranty_date: warranty_date
                    },
                    success: function(e){
                        var obj = jQuery.parseJSON(e);
                        $('#tableinfocard tbody').html('');
                        $.each(obj.response, function(key, value){
                            var trtable = '<tr><td>'+value.subcategory+'</td><td class="center">'+value.parts_free+'</td><td class="center">'+value.diagnostic_free+'</td></tr>';
                            $('#tableinfocard tbody').prepend(trtable);
                        });
                    }
                });

                if(warranty_date != '') {
                    $('.hideshow.info-card').fadeIn('fast');
                }else {
                    $('.hideshow.info-card').fadeOut('fast');
                }
            });

            $("#deleteitem").on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletejoborder.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        jobid: ID
                    },
                    success: function(e){
                            
                        if(e == "success"){
                            $("#delete-modal").modal('hide');
                            location.reload();
                        }else {

                        }
                    }
                });
            });
            $('.view').on('click',function(){

                $('.datepickerfordatedelivery').slideUp('fast');
                $('.ishaveammount').slideUp('fast');
                $('.savesetdate').attr("id", "sdfsdf");
                $('#saveseteddate').html("<i class='fa fa-plus'></i> Ok");

                if(ID){
                    $('.modald').fadeIn('slow');
                    $("#view-modal").modal("show");
                    $("#idhere").html(ID);
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewjoborder.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            

                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $('.idhere').html(obj.response[0].jobid);
                            
                            var now = moment(obj.response[0].dateadded);
                            $('.datehere').html(now.format("MMMM D, YYYY"));

                            $('.namehere').html(obj.response[0].name);
                            $('.addresshere').html(obj.response[0].address);
                            $('.contacthere').html(obj.response[0].number);
                            $('.emailhere').html(obj.response[0].email);

                            $('.maincategoryhere').html(obj.response[0].category);
                            $('#tableinfocard tbody').html('');
                            $.each(obj.response5, function(key, value){
                                var trtable = '<tr><td>'+value.subcategory+'</td><td class="center">'+value.parts_free+'</td><td class="center">'+value.diagnostic_free+'</td></tr>';
                                $('#tableinfocard tbody').append(trtable);
                            });

                            var customertype_temp = "";
                            if(obj.response[0].customer_type_id == 1){
                                customertype_temp = "Customer Unit";
                            }else if(obj.response[0].customer_type_id == 2) {
                                customertype_temp = "Dealers Unit";
                            }else if(obj.response[0].customer_type_id == 3) {
                                customertype_temp = "Branch Unit";
                            }

                            $('.ctypehere').html(customertype_temp);
                            $('.branchnamehere').html(obj.response[0].branch_name);
                            $('.branchaddresshere').html(obj.response[0].branch_address);
                            $('.branchcontacthere').html(obj.response[0].contact_person);
                            $('.branchphonehere').html(obj.response[0].branch_number);

                            $('.span-item').html(obj.response[0].item);
                            $('.span-diagnosis').html(obj.response[0].diagnosisitem);
                            $('.span-parts').html(obj.response[0].partsid);
                            
                            var removebr = obj.response[0].parts.split("&lt;br&gt;");
                            var tempremover  = "";

                            for (var i = 0; i < removebr.length; i++) {
                                tempremover = tempremover + removebr[i] + "<br>";
                            };
                            
                            $('.span-parts').html(tempremover);

                            $('.span-tech').html(obj.response[0].technam);
                            $('.span-remarks').html(obj.response[0].remarks);

                             var dat = obj.response[0].date_delivery.split("-");
                                $('input[name="datedelivery"]').val(dat[0] + "-" + dat[1] + "-"+ dat[2].substring(0,2));
  
                            if(obj.response[0].repair_status == 'Ready for Delivery'){
                                $('.savesetdate').attr("id", "saveseteddate");
                                $('.datepickerfordatedelivery').show();
                                $('.ishaveammount').slideDown('fast');
                                $('#saveseteddate').html("<i class='fa fa-plus'></i> Save Delivery Date");
                                $('.span-status').html('<small class="badge col-centered mlightred">Ready for Pickup</small>');
                            }else if(obj.response[0].repair_status == 'Waiting for SOA Approval'){
                                $('.ishaveammount').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered bg-yellow">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Ongoing Repair'){
                                $('.ishaveammount').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered bg-teal">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Done-Ready for Delivery'){
                                $('.ishaveammount').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered mredilive">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Waiting List'){
                                $('.ishaveammount').slideUp('fast');
                                $('.span-status').html('<small class="badge col-centered morange">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Ready for Claiming'){
                                $('.ishaveammount').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered mred">Unclaimed</small>');
                            }else if(obj.response[0].repair_status == 'Approved'){
                                $('.ishaveammount').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered approvedme">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Claimed'){
                                $('.ishaveammount').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered bg-green">'+obj.response[0].repair_status+'</small>');
                            }

                            //clear set date field if JO is not disapproved and cant repair
                            if(obj.response[0].jobclear == '1') {
                                $('.datepickerfordatedelivery').hide();

                                if(obj.response[0].done_date_delivery == "0000-00-00"){
                                    $('.setdatedeliverymain').html("Delivery date is not available.");
                                    $("#setitemarrivedmain").hide();
                                }else{
                                    $('.setdatedeliverymain').html(obj.response[0].done_date_delivery);
                                    $("#setitemarrivedmain").show();
                                }

                                if(obj.response[0].repair_status == 'Done-Ready for Delivery' ) {
                                    $('.span-status small').text('Job Order Arriving Today');
                                }

                                if(obj.response[0].repair_status == 'Ready for Claiming' ) {
                                    $('.dateliveryfrommain').hide();
                                    $('#claimedjoborder').show();
                                    $('.span-status small').text(obj.response[0].repair_status);

                                    $('.span-status').html('<small class="badge col-centered mdone">Ready for Claiming</small>');
                                } else {
                                    $('.dateliveryfrommain').show();
                                    $('#claimedjoborder').hide();
                                }

                                if(obj.response[0].repair_status == 'Claimed' ) {
                                    $('.dateliveryfrommain').hide();
                                }
                                //$('.span-status small').text('Job Order Arriving Today');
                            } else {
                                $('.dateliveryfrommain').hide();
                                $('#claimedjoborder').hide();
                            }
                            //

                             $('.partcost').html("<b>P </b>"+formatNumber(obj.response3[0].totalpartscost));
                            $('.servicescharge').html("<b>P </b>"+formatNumber(obj.response3[0].service_charges));
                            $('.chargetotal').html("<b>P </b>"+formatNumber(obj.response3[0].total_charges));
                            $('.lessdeposit').html("<b>P </b>"+formatNumber(obj.response3[0].less_deposit));
                            $('.lessdiscount').html("<b>P </b>"+formatNumber(obj.response3[0].less_discount));
                            $('.balancecharge').html("<b>P </b>"+formatNumber(obj.response3[0].balance));
                        }
                    });
                    
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });

            $("#claimedjoborder").on('click',function(){
                $("#claimjobordermodal").modal('show');
            });

            $("#setClaimedjob").on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/claimed.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        jobid: ID,
                        name: $('.namehere').text(),
                        emailaddress: $('.emailhere').text(),
                        branch: $('.branchnamehere').text(),
                        item: $('.span-item').text()
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            location.reload();
                        }
                    }
                });
            }); 

            $('#yestoclaiminng').on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/readyforclaiming.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        jobid: ID,
                        name: $('.namehere').text(),
                        emailaddress: $('.emailhere').text(),
                        branch: $('.branchnamehere').text(),
                        item: $('.span-item').text()
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            location.reload();
                        }
                    }
                });
            });

            $('#setitemarrivedmain').click( function(){
                $("#jobarrivedfrommain").modal('show');
            });

            $("[name=datedelivery]").on('change',function(){
                $("#saveseteddate").html('<i class="fa fa-plus"></i> Save Delivery Date');
            });

            $("#setdeliverydate").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
                "datedelivery":{
                    required: true,
                    minlength:1
                }
            },
            // Specify the validation error messages
            messages: {
                datedelivery:{
                required: "Please provide a date",
                minlength: "Your date must be at least 2 characters long",
                }
            },
            submitHandler: function(form) {
                $("#saveseteddate").html('<i class="fa fa-plus"></i> Saving..');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/setdelivery.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        id: ID,
                        datedelivery: $("[name=datedelivery]").val()
                    },
                    success: function(e){
                        var obj = jQuery.parseJSON(e);

                        $('.modald').fadeIn('slow');

                        if(obj.status == 200){
                            $('.modald').fadeOut('slow');
                            $("#saveseteddate").html('<i class="fa fa-plus"></i> Delivery Date Saved');
                        } else {
                            $('.modald').fadeOut('slow');
                            if(obj.status == 101) {
                                if( $.type(obj.date_delivery) != 'undefined' && obj.date_delivery == true ) {
                                    $('input[name="datedelivery"]').parent().find('p.error').remove();
                                    $('input[name="datedelivery"]').parent().append('<p for="datedelivery" generated="true" class="error">Date is already set.</p>');
                                }

                                $("#saveseteddate").html('<i class="fa fa-plus"></i>  Save Delivery Date');
                            }
                        }
  
                    }
                });
                return false;
            }
            });

            $('.viewsetdate').on('click',function(){
            if(ID){
                $("#view-modal2").modal("show");
                $("#idhere").html(ID);
                $.ajax({
                    type: 'POST',
                    url: '../ajax/viewjoborder.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        jobid: ID
                    },
                    success: function(e){
                        
                        var obj = jQuery.parseJSON(e);
                        $('.idhere').html(obj.response[0].jobid);
                        $('.datehere').html(obj.response[0].created_at);
                        $('.namehere').html(obj.response[0].name);
                        $('.addresshere').html(obj.response[0].address);
                        $('.contacthere').html(obj.response[0].number);
                        $('.emailhere').html(obj.response[0].email);
                        var isunderwarantyd = "";
                        if(obj.response[0].isunder_warranty ==1){
                            isunderwarantyd = "Yes";
                        } else {
                            isunderwarantyd = "No";
                        }
                        $('.isunder_warranty').html(isunderwarantyd);
                        var customertype_temp = "";
                        if(obj.response[0].customer_type_id == 1){
                            customertype_temp = "Customer Unit";
                        }else if(obj.response[0].customer_type_id == 2) {
                            customertype_temp = "Dealers Unit";
                        }else if(obj.response[0].customer_type_id == 3) {
                            customertype_temp = "Branch Unit";
                        }
                        $('.ctypehere').html(customertype_temp);
                        $('.branchnamehere').html(obj.response[0].branch_name);
                        $('.branchaddresshere').html(obj.response[0].branch_address);
                        $('.branchcontacthere').html(obj.response[0].contact_person);
                        $('.branchphonehere').html(obj.response[0].branch_number);

                        $('.span-item').html(obj.response[0].item);
                        $('.span-diagnosis').html(obj.response[0].diagnosis);
                        $('.span-parts').html(obj.response[0].partsid);
                        $('.span-tech').html(obj.response[0].technam);
                        $('.span-remarks').html(obj.response[0].remarks);
                        $('.span-status').html('<small class="badge col-centered bg-navy">'+obj.response[0].repair_status+'</small>');
                    }
                });
            }else {
                $("#selecrecord-modal").modal("show");
            }
            });
            $('.edit').on('click',function(){
                if(ID){
                    $('#edit-joborder').modal('show');
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewjoborder.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            var obj = jQuery.parseJSON(e);
                            $('.modald').fadeOut('fast');
                            $('input[name="name"]').val(obj.response[0].jobid);
                            $('input[name="name"]').attr('data-customer-id', obj.response[0].customerid);
                            // $('input[name="name"]').val(obj.response[0].created_at);
                            $('input[name="ename"]').val(obj.response[0].name);
                            $('input[name="eaddress"]').val(obj.response[0].address);
                            $('input[name="enumber"]').val(obj.response[0].number);
                            $('input[name="eemail"]').val(obj.response[0].email);
                            $('input[name="ereferenceno"]').val(obj.response[0].referenceno);
                            $('input[name="eservicefee"]').val(obj.response[0].servicefee);
                            $('select[name="ecustomertype"]').val(obj.response[0].customer_type_id);
                            //var dat1 = obj.response[0].warranty_date.split("-");

                            $('[name="eisjbitem"] option[value="'+obj.response[0].isunder_warranty+'"]').attr('selected', 'selected');

                            var formattedDate = new Date(obj.response[0].warranty_date);
                            var d = ( formattedDate.getDate() < 10 ) ? '0'+formattedDate.getDate() : formattedDate.getDate() ;
                            var m = formattedDate.getMonth();
                                m += 1;
                                m = ( m < 10 ) ? '0'+m : m;
                            var y = formattedDate.getFullYear();

                            $('input[name="ewarranty_date"]').val(y+"-"+m+"-"+d);

                            if($('input[name="ewarranty_date"]').val() != 0) {
                                $('.hideshow.einfo-card').fadeIn('fast');
                            }else {
                                $('.hideshow.einfo-card').fadeOut('fast');
                            }

                            $('#emaincategory option[value="'+obj.response[0].catid+'"]').attr('selected', 'selected');

                            if($('#emaincategory').val() != 0 && $('[name="eisjbitem"]').val() == 1) {
                                $('.hideshow.ewarranty-date').fadeIn('fast');
                            }else {
                                $('.hideshow.ewarranty-date, .hideshow.info-card').fadeOut('fast');
                            }

                            $('#etableinfocard tbody').html('');
                            $.each(obj.response5, function(key, value){
                                var trtable = '<tr><td>'+value.subcategory+'</td><td class="center">'+value.parts_free+'</td><td class="center">'+value.diagnostic_free+'</td></tr>';
                                $('#etableinfocard tbody').append(trtable);
                            });

                            customerID  = obj.response[0].customerid;
                            joborderid  = obj.response[0].jobid;

                            $('input[name="eitemname"]').val(obj.response[0].item);
                            $('#eremarks').val(obj.response[0].remarks);
                            $('select[name="ediagnosis"]').val(obj.response[0].diagnosis);

                            $('.branchnamehere').html(obj.response[0].branch_name);
                            $('.branchaddresshere').html(obj.response[0].branch_address);
                            $('.branchcontacthere').html(obj.response[0].contact_person);
                            $('.branchphonehere').html(obj.response[0].branch_number);



                        }
                    });
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });

            $("select[name='eisjbitem']").change(function(){
                if ($(this).val() == 0) {
                    $('[name="eservicefee"]').val(800.00);
                    $("input[name='ewarranty_date']").val('');
                    $('#etableinfocard tbody').html('');
                    $('.hideshow.ewarranty-date, .hideshow.einfo-card').fadeOut('fast');
                } else {
                    $('[name="eservicefee"]').val(0.00);
                    $('.hideshow.ewarranty-date, .hideshow.einfo-card').fadeIn('fast');
                }
            });            
            $("select[name='emaincategory']").change(function(){
                $('#etableinfocard tbody').html('');

                if($(this).val() != 0 && $("select[name='eisjbitem']").val() == 1 ) {
                    $('.hideshow.ewarranty-date').fadeIn('fast');
                }else {
                    $('.hideshow.ewarranty-date, .hideshow.einfo-card').fadeOut('fast');
                }
            });
            $("input[name='ewarranty_date']").change(function(){
                var maincategory = $("select[name='emaincategory']").val();
                var warranty_date = $(this).val();

                $.ajax({
                    type: 'POST',
                    url: '../ajax/viewinfocard.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        maincategory: maincategory,
                        warranty_date: warranty_date
                    },
                    success: function(e){
                        var obj = jQuery.parseJSON(e);
                        $('#etableinfocard tbody').html('');
                        $.each(obj.response, function(key, value){
                            var trtable = '<tr><td>'+value.subcategory+'</td><td class="center">'+value.parts_free+'</td><td class="center">'+value.diagnostic_free+'</td></tr>';
                            $('#etableinfocard tbody').prepend(trtable);
                        });
                    }
                });

                if($(this).val() != 0) {
                    $('.hideshow.einfo-card').fadeIn('fast');
                }else {
                    $('.hideshow.einfo-card').fadeOut('fast');
                }

            });

            $('.delete').on('click',function(){
                if(ID) {
                    $("#delete-modal").modal('show');
                    $("#idhere2").html(ID);
                    $("#customerhere2").html($('tr#'+ID+' td:nth-child(2)').text());
                    $("#itemhere2").html($('tr#'+ID+' td:nth-child(4)').text());
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });

        $("#payment_process").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
                "partialpayment":{
                required: true,
                number: true,
                minlength:2
                },
                "invoice":{
                required: true,
                minlength:2
                }
            },
            // Specify the validation error messages
            messages: {
                partialpayment:{
                required: "Please provide the amount",
                minlength: "Your password must be at least 2 characters long",
                },
                invoice:{
                required: "Please provide a Invoice Number",
                minlength: "Your number must be at least 2 interger long"
                }
            },
            submitHandler: function(form) {
                $('#create-payment').modal('hide');
                $('.paymentinformation').fadeIn("fast");
                $(".payment-section").html($("[name=partialpayment]").val());
                $(".payment-invoice").html($("[name=invoice]").val());
                return false;
            } });

            $('#validatepasword').change(function() {
                $("#loader").fadeIn("slow");
              $.ajax({
                    type: 'POST',
                    url: '../ajax/validatepassword.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        password: $("[name=password]").val()
                    },
                    success: function(e){
                            
                        if(e == "success"){
                            $(".password_notifiear").fadeIn("slow");
                            $(".hidefirst").fadeIn("slow");
                            $(".password-container").fadeOut("slow");
                            $(".password_wrong").fadeOut('fast');
                            $(".showhide").removeClass('showhide');
                        }else {
                            $('.password_wrong').fadeIn('slow');
                        }
                    }
                });
            });

            //WHEN YOU CHOOSE RECORD
            $(document).on('click', ".clickable", function() {
            $(".clickable").removeClass("selected");
            $(this).addClass("selected");
            ID = $(this).attr("id");
            console.log(ID);
            });
            
            $( "input[name='q']" ).change(function() {
            console.log($(this).val());
            });
            
            $("#editjoborder").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
            "eisjbitem":{
            required: true,
            },
            "ename":{
            required: true,
            minlength:2
            },
            "enumber":{
            required: true,
            number: true,
            minlength:8
            },
            "eemail":{
            required: true,
            email: true
            },
            "ecustomertype":{
            required: true
            },
            "eeaddress":{
            required: true,
            minlength:2
            },
            "eitemname":{
            required: true,
            minlength:2
            },
            "ediagnosis":{
            required: true,
            minlength:1
            },
            "emaincategory":{
            required: true,
            minlength:1
            },
            "eremarks":{
            required: true,
            minlength:1
            },
            "estatus":{
            required: true
            },
            "ereferenceno":{
            required: true
            },
            "eservicefee":{
            required: true,
            number: true
            }
            },
            // Specify the validation error messages
            messages: {
            eisjbitem:{
            required: "Please select a JB Item",
            },
            ename:{
            required: "Please provide a Name",
            minlength: "Your password must be at least 2 characters long",
            },
            enumber:{
            required: "Please provide a number",
            minlength: "Your number must be at least 8 interger long"
            },
            eemail:{
            required: "Please provide a Email Address",
            minlength: "Your number must be at least 4 interger long"
            },
            eaddress:{
            required: "Please provide a Address"
            },
            ecustomertype:{
            required: "Please make a selection from the list. type"
            },
            eitemname:{
            required: "Please provide a Item Name"
            },
            ediagnosis:{
            required: "Please provide a Diagnosis"
            },
            emaincategory:{
            required: "Please select Main Category."
            },
            eremarks:{
            required: "Please provide a Remarks"
            },
            ereferenceno:{
            required: "Please provide a Reference No. "
            },
            eservicefee:{
            required: "Please provide a Service fee"
            }
            },
            submitHandler: function(form) {
                $('.modald').fadeIn('fast');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/editjob.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        name: $("[name=ename]").val(),
                        number: $("[name=enumber]").val(),
                        email: $("[name=eemail]").val(),
                        address: $("[name=eaddress]").val(),
                        customertype: $("[name=ecustomertype]").val(),
                        isunder_warranty : $("[name=eisjbitem]").val(),
                        warranty_date: $("[name=ewarranty_date]").val(),
                        maincategory: $("[name=emaincategory]").val(),
                        itemname: $("[name=eitemname]").val(),
                        diagnosis: $("[name=ediagnosis]").val(),
                        remarks: $("[name=eremarks]").val(),
                        referenceno: $("[name=ereferenceno]").val(),
                        servicefee: $("[name=eservicefee]").val(),
                        customerID: customerID,
                        joborderid: joborderid,
                        branchid: "<?php echo $_SESSION['Branchid']; ?>"
                    },
                    success: function(e){
                            
                        $('.modald').fadeOut('fast');
                        if(e == "success"){
                            location.reload();
                        }
                    }
                });
                return false;
            }
        });;

            $("#createjob").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
            "isjbitem":{
            required: true,
            },
            "name":{
            required: true,
            minlength:2
            },
            "number":{
            required: true,
            number: true,
            minlength:11
            },
            "email":{
            required: true,
            email:true
            },
            "customertype":{
            required: true
            },
            "address":{
            required: true,
            minlength:2
            },
            "itemname":{
            required: true,
            minlength:2
            },
            "diagnosis":{
            required: true,
            minlength:1
            },
            "maincategory":{
            required: true,
            minlength:1
            },
            "remarks":{
            required: true,
            minlength:1
            },
            "status":{
            required: true
            },
            "referenceno":{
            required: true
            },
            "servicefee":{
            required: true,
            number: true
            }
            },
            // Specify the validation error messages
            messages: {
            isjbitem:{
            required: "Please select a JB Item",
            },
            name:{
            required: "Please provide a Name",
            minlength: "Your name must be at least 2 characters long",
            },
            number:{
            required: "Please provide a Number",
            minlength: "Your number must be at least 11 interger long"
            },
            email:{
            required: "Please provide a Email Address",
            minlength: "Your email must be at least 11 characters long"
            },
            address:{
            required: "Please provide a Address",
            minlength: "Your address must be at least 11 characters long"
            },
            customertype:{
            required: "Please make a selection from the list. type"
            },
            itemname:{
            required: "Please provide a Item Name",
            minlength: "Your item name must be at least 2 characters long"
            },
            diagnosis:{
            required: "Please provide a Diagnosis",
            minlength: "Your diagnosis must be at least 1 characters long"
            },
            maincategory:{
            required: "Please select Main Category."
            },
            // warranty_date:{
            // required: "Please select Purchase Date."
            // },
            remarks:{
            required: "Please provide a Remarks",
            minlength: "Your number must be at least 1 characters long"
            },
            referenceno:{
            required: "Please provide a Reference No. "
            },
            servicefee:{
            required: "Please provide a Service fee"
            }
            },
            submitHandler: function(form) {
                $('.modald').fadeIn('fast');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/createjob.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        name: $("[name=name]").val(),
                        number: $("[name=number]").val(),
                        email: $("[name=email]").val(),
                        address: $("[name=address]").val(),
                        customertype: $("[name=customertype]").val(),
                        maincategory: $("[name=maincategory]").val(),
                        isunder_warranty : $("[name='isjbitem']").val(),
                        warranty_date: $("[name=warranty_date]").val(),
                        itemname: $("[name=itemname]").val(),
                        diagnosis: $("[name=diagnosis]").val(),
                        remarks: $("[name=remarks]").val(),
                        referenceno: $("[name=referenceno]").val(),
                        servicefee: $("[name=servicefee]").val(),
                        isExisting: $('#existingc').val(),
                        idSelectedCustomer: idSelectedCustomer,
                        branchid: "<?php echo $_SESSION['Branchid']; ?>"
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            location.reload();
                        }
                    }
                });
                return false;
            }
        });

        $('.discard').click( function(){
            $('input, textarea').val('');
            $('select option').removeAttr('selected');
            $('p.error').remove();
        });

    });
    </script>
    <?php
    htmlFooter('dashboard');
    ?>