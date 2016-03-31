<?php
    include '../../include.php';
    include '../ui_main.php';
    htmlHeader('dashboard');
    global $url;
    $queryforexcel = "";

    # Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

    if($_SESSION['position'] != -1) {
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
    if($_SESSION['position'] == -1 || $_SESSION['position'] == 0) {
        $name = "JB Main Office";    
    }else {
        $sql = "SELECT branch_name FROM jb_branch WHERE branch_id = '" .$name. "'";
         $query = $db->ReadData($sql);
         $name =  $query[0]['branch_name'];
    }
    
    $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
    $query2 = $db->ReadData($sql2);

    $counterviewed = "SELECT * FROM notitemp WHERE  branch_id <> '0' AND isViewed <> '1' ORDER BY `created_at` DESC";
    $counterviewed = $db->ReadData($counterviewed);

    headerDashboard($name, $query2, count($counterviewed)); 

    ?>
    <div class="modald">
         <img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif">
    </div>

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
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                            $query = $db->ReadData($qu);
                            $_SESSION['jobcount'] = $db->GetNumberOfRows();
                            sidebarMenu($db->GetNumberOfRows());
                            $headertitle = "Job Order";
                    }else {
                        $type = $_GET['type'];
                        if($type == "today") {
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id)   AND a.isdeleted = '0'  AND a.date_delivery = '".date("y-m-d")."'  AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                            $headertitle = "Job Order Arriving Today";
                        }else if($type == "waiting_for_approval"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting for Soa Approval'AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'ORDER BY created_at DESC";
                             $headertitle = "Waiting for Soa Approval";
                        }else if($type == "waiting_list"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting List' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                             $headertitle = "Waiting List";
                        }else if($type == "ongoing_repair"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'  AND a.repair_status = 'Ongoing Repair'  AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                            $headertitle = "Pending Job Order";
                        }else if($type == "unclaimed"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 3   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                            $headertitle = "Unclaimed Job Order";
                            
                        }else if($type == "Claimed"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 4   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                            $headertitle = "Claimed Job Order";
                            
                        }else if($type == "unpaid"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 5  AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                            $headertitle = "Unpaid Job Order";
                            
                        }else if($type == "finish"){
                            $headertitle = "Job Order";
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                        }else {
                            $headertitle = "Job Order";
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0' AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'  ORDER BY created_at DESC";
                        }
                        $query = $db->ReadData($qu);
                        sidebarMenu($db->GetNumberOfRows());
                    }
            }else{
                    if(!isset($_GET['type'])){
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.isdeleted = '0' ORDER BY created_at DESC";
                            $query = $db->ReadData($qu);
                            $_SESSION['jobcount'] = $db->GetNumberOfRows();
                            sidebarMenu($db->GetNumberOfRows());
                            $headertitle = "Job Order";
                    }else {
                        $type = $_GET['type'];
                        if($type == "today") {
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id)   AND a.isdeleted = '0'  AND a.date_delivery = '".date("y-m-d")."'  ORDER BY created_at DESC";
                            $headertitle = "Job Order Arriving Today";
                        }else if($type == "waiting_for_approval"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting for Soa Approval' ORDER BY created_at DESC";
                             $headertitle = "Waiting for Soa Approval";
                        }else if($type == "waiting_list"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting List' ORDER BY created_at DESC";
                             $headertitle = "Waiting List";
                        }else if($type == "ongoing_repair"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'  AND a.repair_status = 'Ongoing Repair'  ORDER BY created_at DESC";
                            $headertitle = "Pending Job Order";
                        }else if($type == "unclaimed"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 3   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' ORDER BY created_at DESC";
                            $headertitle = "Unclaimed Job Order";
                        }else if($type == "Claimed"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 4   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' ORDER BY created_at DESC";
                            $headertitle = "Claimed Job Order";
                        }else if($type == "unpaid"){ 
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 5  AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' ORDER BY created_at DESC";
                            $headertitle = "Unpaid Job Order";
                        }else if($type == "finish"){
                            $headertitle = "Job Order";
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                        }else {
                            $headertitle = "Job Order";
                            $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status,a.jobclear, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'   ORDER BY created_at DESC";
                        } 

                        $query = $db->ReadData($qu);
                        sidebarMenu($db->GetNumberOfRows());
                    }
                }
                $queryforexcel = $qu;
                $queryforexcel = str_replace("+", "~~", $queryforexcel);
             ?>
                </section>
                <!-- /.sidebar -->
            </aside>
            <script type="text/javascript">
            $(function(){
                $(".edit, .add, .delete").remove()
            });
            </script>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
              <?php breadcrumps('Job Orders'); ?>
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
                                                <th>Assign Tech</th>
                                                <th>Remarks</th>
                                                <th>Repair Status</th>
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
                                                            $return = $return . "<td><small class=\"badge col-centered mlightred\">Job Order Arriving Today</small></td>";   
                                                        }else if($value['repair_status'] == "Waiting for SOA Approval") {
                                                            $return = $return . "<td><small class=\"badge col-centered mrorange\">Waiting for Customer Approval</small></td>";
                                                        }else if($value['repair_status'] == "Waiting List") {
                                                            $return = $return . "<td><small class=\"badge col-centered morange\">".$value['repair_status']."</small></td>";
                                                        }else if($value['repair_status'] == "Ongoing Repair") {
                                                            $return = $return . "<td><small class=\"badge col-centered bg-teal\">".$value['repair_status']."</small></td>";
                                                        }else if($value['repair_status'] == "Done-Ready for Delivery" && $value['jobclear'] == 0) {
                                                            $return = $return . "<td><small class=\"badge col-centered mredilive\">Ready for Delivery</small></td>";
                                                        }else if($value['repair_status'] == "Done-Ready for Delivery" && $value['jobclear'] == 1){
                                                            $return = $return . "<td><small class=\"badge col-centered mredilive\">Ready for Pickup</small></td>";
                                                        }else if($value['repair_status'] == "Claimed") {
                                                            $return = $return . "<td><small class=\"badge col-centered bg-green\">Claimed</small></td>";
                                                        }else if($value['repair_status'] == "Ready for Claiming") {
                                                            $return = $return . "<td><small class=\"badge col-centered mdone\">Ready for Claiming</small></td>";
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


         <div class="modal fade" id="addremark" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                               <!-- Main content -->
                        <div class="form-group col-xs-12">
                           <div class="form-group">
                                <textarea name="otheremarks" id="email_message" class="form-control" placeholder="Remarks" style="height: 120px;"></textarea>
                            </div>
                        </div>
                    <div class="clear"></div>
                     <div class="form-group col-xs-12">
                         <button type="button" class="btn btnmc  cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         <button type="submit" id="cantrepairremarks" class="btn btn-success pull-left "><i class="fa fa-plus"></i> Can't Repair Remarks </button>
                    </div><!-- /.modal-content --> 
                    <div class="clear"></div>
                    </div><!-- /.modal-content --> 
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- /.modal -->

        <div class="modal fade getdata" id="view-modal2" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> Job Order  #<span class="idhere"></span>
                                <small class="pull-right">Date: <span class="datehere"></span></small>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Customer Name: </strong><span class="namehere"></span><br>
                                <strong>Address : </strong><span class="addresshere"></span><br>
                                <strong>Contact Number: </strong><span class="contacthere"></span><br>
                                <strong>Email Address: </strong><span class="emailhere"></span><br>
                                <strong>Customer Type: </strong><span class="ctypehere"></span><br>
                                <strong>Main Category: </strong><span class="maincategoryhere"></span><br>

                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <strong>Branch Name: </strong><span class="branchnamehere"></span><br>
                                <strong>Branch Address : </strong><span class="branchaddresshere"></span><br>
                                <strong>Contact Person: </strong><span class="branchcontacthere"></span><br>
                                <strong>Phone number: </strong><span class="branchphonehere"></span><br>
                                <strong>Warranty Card: </strong>
                                <div class="table-responsive">
                                    <table id="tableinfocard" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Sub Category</th>
                                            <th>Parts Free</th>
                                            <th>Diagnostic Free</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Diagnosis</th>
                                        <th>Parts <button class="btn btn-success addparts"><i class="fa fa-plus"></i></button></th>
                                        <th>Technician <button class="btn btn-success addtech"><i class="fa fa-plus"></i></button></th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="span-item"></span></td>
                                        <td><span class="span-diagnosis"></span></td>
                                         <td><span class="span-parts partlistplea"></span></td>
                                        <td><span class="span-tech"></span></td>
                                        <td><span class="span-remarks"></span></td>
                                        <td><span class="span-status"></span></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="row billingstatment">
                         <div class="form-group col-xs-6 removethisongoing">
                         <p class="lead">Charges</p>
                         <div class="col-xs-12">
                            <label>Services Charges:</label>
                            <input type="number" name="servicescharge"  class="form-control"  data-inputmask="" data-mask="">
                         </div>
                         <div class="col-xs-12">
                            <label>Total Charges:</label>
                            <input type="number" name="chargetotal" class="form-control" placeholder="0.0">
                         </div>
                             <div class=" col-xs-12">
                            <label>Less Deposit:</label>
                            <input type="number" name="lessdeposit" class="form-control" placeholder="0.0">
                             </div>
                             <div class="col-xs-12">
                            <label>Less Discount:</label>
                            <input type="number" name="lessdiscount" class="form-control" placeholder="0.0">
                            </div>
                        </div>
                        <div class="form-group col-xs-6 hidethiss">
                            <p class="lead">Amount Due</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody><tr>
                                        <th style="width:50%">Total Parts Cost:</th>
                                        <td><strong>P</strong> <span class="partcost">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>Service Charges</th>
                                        <td><strong>P</strong> <span class="servicescharge">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>Total Charges:</th>
                                        <td><strong>P</strong> <span class="chargetotal">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>Less Deposit</th>
                                        <td><strong>P</strong> <span class="lessdeposit">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>Less Discount</th>
                                        <td><strong>P</strong> <span class="lessdiscount">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <th>Balance</th>
                                        <td><strong>P</strong> <span class="balancecharge">0.00</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group col-xs-6 datelivery">
                            <p class="lead">Delivery Date</p>
                            <div class="table-responsive">
                                <b>Date:</b> <span class="setdatedelivery"></span>
                            </div>
                            <br>
                            <div class="table-responsive">
                               <button id="setOngoingRepair" class="btn btn-success"><i class="fa fa-check"></i> Job Order Arrived</button>
                               
                            </div>
                        </div>
                        <div class="form-group col-xs-6 dateliveryformmain">
                            <div class="col-xs-12">
                            <form id="setdeliverydateformmain" class="change_to_edit" name="createjob" method="post" role="form">
                                <div class="form-group ">
                                    <label>Set Delivery Date:</label>
                                <input type="text" name="datedeliverymain" placeholder="Date Delivery.." class="form-control datedelivery">

                                <br>
                                <button type="submit" id="savejob" class="btn btn-success pull-left savesetdatemain "><i class="fa fa-plus"></i>  Save Delivery Date </button>
                            </form>
                            </div>
                        </div>
                    </div>
                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
                        </div>
                    </div>
                </section><!-- /.content -->
                        <button id="cmd" class="btn btn-primary" style="margin-left: 18px;"><i class="fa fa-download"></i> Generate PDF </button> 
                        <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Close </button>  
                        <button type="button" id="applychanges" class="btn btn-success pull-left savesetdate"><i class="fa fa-plus"></i> Generate SOA </button>
                        <button id="cantrepair" class="btn  btnmc pull-left" style="margin-right: 5px;margin-left: 18px;"><i class="fa fa-download"></i> Can't Repair </button>
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                <script src='<?php echo SITE_JS_DIR ?>/pdfmake.min.js'></script>
                <script src='<?php echo SITE_JS_DIR ?>/vfs_fonts.js'></script>

                <div class="modal fade" id="addtech" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-body">
                                       <!-- Main content -->
                                <div class="form-group col-xs-12">
                                <div class="input-group">
                                    <input type="text" class="form-control"  id="search_tech" placeholder="Search Technician Name">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                </div>
                                </div>
                                <div class="form-group search-list-result-tech col-xs-12">
                                    <select multiple class="form-control search-list-tech" name="search-list-tech">
                                    </select>
                                </div>
                            <div class="clear"></div>
                             <div class="form-group col-xs-12">
                                 <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                                 <button type="submit" id="assignedtech" class="btn btn-success pull-left "><i class="fa fa-plus"></i> Assign Tech </button>
                            </div><!-- /.modal-content --> 
                            <div class="clear"></div>
                            </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                



                <div class="modal fade" id="addpart" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-body">
                                       <!-- Main content -->
                                <div class="form-group col-xs-12">
                                <div class="input-group">
                                    <input type="text" class="form-control"  id="search_part" placeholder="Search Part Name">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                </div>
                                </div>
                                <div class="form-group search-list-result-part col-xs-12">
                                    <select multiple class="form-control search-list-part" name="search-list-part">
                                    </select>
                                </div>
                                <ul class="col-xs-12 listofparts-beforeadded col-xs-6">
                                </ul>
                                    <!-- <div class="partquantity form-group col-xs-6">
                                    <div class="form-group">
                                            <label>Quantity:</label>
                                            <input type="number" name="partsquantity" class="form-control" placeholder="">
                                        </div>
                                    </div> 
                                    <div class="partquantity form-group col-xs-6">
                                        <div class="form-group">
                                            <label>Price:</label>
                                            <p class="partprices">0.00</p>
                                        </div>
                                    </div>
                                     -->
                                     
                                <div class="partquantity form-group col-xs-6">
                                    <div class="form-group">
                                        <label>Total Parts Price:</label>
                                        <p class="totalpartcost">0.00</p>
                                    </div>
                                </div>

                            <div class="clear"></div>
                             <div class="form-group col-xs-12">
                                 <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                                 <button type="submit" id="addparttojoborder" class="btn btn-success pull-left "><i class="fa fa-plus"></i> Add Part </button>
                            </div><!-- /.modal-content --> 
                            <div class="clear"></div>
                            </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

             <div class="modal fade" id="ongoingrepairarrive" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> [JO# <span class="idhere"></span>]: Item Arrived, set to Waiting List?</h4>
                        </div>
                        <div class="modal-body">
                            <center ><button type="submit" id="ongoingrepa" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-check"></i> Item Arrived </button><button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  </center>
                            <div class="clear"></div>
                        </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div><!-- /.modal -->
               
        <div class="modal fade" id="selecrecord-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Please make a selection from the list.</h4>
                </div>
                <div class="modal-body">
                     <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
                <div class="clear"></div>
                </div><!-- /.modal-content --> 
                </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div><!-- /.modal -->
            
        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title text-red" ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete Job order No. <span id="idhere2"></span>?</h4>
                    </div>
                    <div class="modal-body">
                         <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         <button type="submit" id="deleteitem" class="btn btn-success  pull-left "><i class="fa fa-plus"></i> Delete </button>
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->
      
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
        <div class="modal fade" id="main-create-joborder" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Create Job Order</h4>
                    </div>
                    <div class="modal-body">

                            <div class="form-group col-xs-12">
                            <div class="input-group">
                                <input type="text" class="form-control"  id="search_customers" placeholder="Search Technician">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                            </div>
                            <div class="form-group search-list-result col-xs-12">
                                <select multiple class="form-control search-list" name="search-list">
                                </select>
                            </div>
                        <div class="clear"></div>

                        <section class="content invoice invoice-hishow">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> Job Order  #<span class="idhere"></span>
                                <small class="pull-right">Date: <span class="datehere"></small>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Customer Name: </strong><span class="namehere"></span><br>
                                <strong>Address : </strong><span class="addresshere"></span><br>
                                <strong>Contact Number: </strong><span class="contacthere"></span><br>
                                <strong>Email Address: </strong><span class="emailhere"></span><br>
                                <strong>Customer Type: </strong><span class="ctypehere"></span><br>
                                <strong>Is Under Warranty: </strong><span class="isunder_warranty"></span><br>

                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <strong>Branch Name: </strong><span class="branchnamehere"></span><br>
                                <strong>Branch Address : </strong><span class="branchaddresshere"></span><br>
                                <strong>Contact Person: </strong><span class="branchcontacthere"></span><br>
                                <strong>Phone number: </strong><span class="branchphonehere"></span><br>
                    </div><!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Diagnosis</th>
                                        <th>Parts</th>
                                        <th>Technician</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="span-item"></span></td>
                                        <td><span class="span-diagnosis"></span></td>
                                        <td><span class="span-parts"></span><button class="btn btn-success addparts"><i class="fa fa-plus"></i> Add</button></td>
                                        <td><span class="span-tech"></span> <button class="btn btn-success addtech"><i class="fa fa-download"></i> Add</button></td>
                                        <td><span class="span-remarks"></span></td>
                                         <td><span class="span-status"></span></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                            <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
                        </div>
                    </div>
                </section><!-- /.content -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <?php 
            modald();
        ?>
        <script type="text/javascript">
              $(function() {    
                var ID = "";
                var parts;
                var partscount = 0;
                var techname;
                var partsString;
                var partsStringdummy;

                var partsID;
                var techID;
                
                var totalpartscost, servicesharges, totalcharges, lessdeposit, lessdiscount;
                var what = "";
                var partcost;
                var partslisttemp = "";
                var emailaddress  = "";

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
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.isdeleted = '0' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } else { ?>

                            <?php $type = $_GET['type'];
                            if($type == "today") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id)   AND a.isdeleted = '0'  AND a.date_delivery = <?php echo date('y-m-d') ?>  AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "waiting_for_approval") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting for Soa Approval'AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"'ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "waiting_list"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting List' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "unpaid"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 5  AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ongoing_repair"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'  AND a.repair_status = 'Ongoing Repair'  AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "unclaimed"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 3   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "Claimed"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 4   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "finish"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0  AND a.isdeleted = '0' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php } else { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0' AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"'  ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php } ?>

                        <?php } ?>

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=joborder&&filename=joborder_excel";
                        window.location = page;// you can use window.open also

                    <?php } else { ?>
                        var filter = $('#example1_filter label input').val();
                        <?php if(!isset($_GET['type'])) { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.isdeleted = '0' ORDER BY created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } else { ?>
                            <?php $type = $_GET['type']; 
                            if($type == "today") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id)   AND a.isdeleted = '0'  AND a.date_delivery = <?php echo date('y-m-d') ?> ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "waiting_for_approval") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting for Soa Approval' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "waiting_list"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Waiting List' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "unpaid"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 5  AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ongoing_repair"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'  AND a.repair_status = 'Ongoing Repair'  ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "unclaimed"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 3   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "Claimed"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 4   AND a.isdeleted = '0'  AND a.repair_status <> 'Ongoing Repair' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "finish"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.status_id = 0  AND a.isdeleted = '0'  ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php } else { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE ( a.jobid LIKE '%"+filter+"%' OR a.item LIKE '%"+filter+"%' OR a.remarks LIKE '%"+filter+"%' OR a.repair_status LIKE '%"+filter+"%' OR b.name LIKE '%"+filter+"%' OR c.branch_name LIKE '%"+filter+"%' OR d.name LIKE '%"+filter+"%' ) AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'   ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php } ?>

                        <?php } ?>

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=joborder&&filename=joborder_excel";
                        window.location = page;// you can use window.open also

                    <?php } ?>
  
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
                        mainoffice: "1",
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



                function myFunction(a, b, c, d, e) {
                    a = parseFloat(a);
                    b = parseFloat(b);
                    c = parseFloat(c);
                    d = parseFloat(d);
                    e = parseFloat(e);
                    $('.balancecharge').html((a + b + c)-(d + e));
                }

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
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/joborders.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/joborders.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59'));

                                <?php 
                            }
                            ?>
                            <?php
                        }
                    ?>
                   
            }
            );

                function clearallfield(){
                    $('.servicescharge').html("800.00");
                    $('.lessdeposit').html("0.00");     
                    $('.lessdiscount').html("0.00"); 
                    $('.chargetotal').html("0.00");  
                    $("[name=servicescharge]").val("800.00");
                    $("[name=lessdeposit]").val("0.00");
                    $("[name=lessdiscount]").val("0.00");
                    $("[name=chargetotal]").val("0.00");
                }

                $(document).on('click','li .removeParts',function(){
                    console.log($(this).attr('id'));
                    $('li#' + $(this).attr('data-id')).remove(); 
                    var m = 0;
                    var c = 0;
                    var total = 0;

                    $( ".listofparts-beforeadded" ).each(function( index ) {
                      console.log( index + ": " + $( this ).text() );
                      console.log( index + ": " + $( ".listofparts-beforeadded li .s" ).text() );
                      var d =  $( ".listofparts-beforeadded li .s" ).text().split(")");
                      for (var i = 0; i < d.length -1; i++) {
                      console.log("data test" + d[i]);
                      
                      var dd =  d[i].split("*");
                            console.log("data test" + (parseInt(dd[0]) * parseInt(dd[1])));
                            total =  parseInt(total) + (parseInt(dd[0]) * parseInt(dd[1]));
                      };    
                    });

                      console.log("data part ids" + partsID);

                        $('.totalpartcost').html(total);
                }); 
                $('[name="servicescharge"]').on('keyup',function(){
                    if($("[name=servicescharge]").val() == ""){
                        $('.servicescharge').html("800.00");
                    }else{
                        $('.servicescharge').html($("[name=servicescharge]").val());
                    }
                });

                $('[name="lessdeposit"]').on('keyup',function(){
                    if($("[name=lessdeposit]").val() == ""){
                        $('.lessdeposit').html("0.00");
                    }else{
                        $('.lessdeposit').html($("[name=lessdeposit]").val());
                    }
                });

                $('[name="lessdiscount"]').on('keyup',function(){
                   if($("[name=lessdiscount]").val() == ""){
                        $('.lessdiscount').html("0.00");
                    }else{
                        $('.lessdiscount').html($("[name=lessdiscount]").val());
                    }
                });

                $('[name="chargetotal"]').on('keyup',function(){
                     if($("[name=chargetotal]").val() == ""){
                        $('.chargetotal').html("0.00");
                    }else{
                        $('.chargetotal').html($("[name=chargetotal]").val());
                    }
                });

                $("[name=datedelivery]").on('change',function(){
                    $("#save_donedate").html('<i class="fa fa-check"></i> Save Delivery Date');
                });



                $('.partcost').bind("DOMSubtreeModified",function(){
                    var a = $('.partcost').text() , b = $('.servicescharge').text(), c = $('.chargetotal').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
                    myFunction(a, b, c, d, e);
                });

                $('.servicescharge').bind("DOMSubtreeModified",function(){
                     var a = $('.partcost').text() , b = $('.servicescharge').text(), c = $('.chargetotal').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
                    
                    myFunction(a, b, c, d, e);
                });

                $('.chargetotal').bind("DOMSubtreeModified",function(){
                    var a = $('.partcost').text() , b = $('.servicescharge').text(), c = $('.chargetotal').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
                    myFunction(a, b, c, d, e);
                });
                
                $('.lessdeposit').bind("DOMSubtreeModified",function(){
                    var a = $('.partcost').text() , b = $('.servicescharge').text(), c = $('.chargetotal').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
                    myFunction(a, b, c, d, e);
                });
                
                $('.lessdiscount').bind("DOMSubtreeModified",function(){
                    var a = $('.partcost').text() , b = $('.servicescharge').text(), c = $('.chargetotal').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
                    myFunction(a, b, c, d, e);
                });


                $(document).on('click','#setOngoingRepair', function(){
                    $("#ongoingrepairarrive").modal('show');
                   
                });

                $("#ongoingrepa").on('click',function(){

                $('.modald').fadeIn('fast');
                      $.ajax({
                            type: 'POST',
                            url: '../ajax/setwaiting.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                jobid: ID
                            },
                            success: function(e){

                            $('.modald').fadeOut('fast');
                                
                                if(e == "success"){
                                    location.reload();
                                }
                            }
                        });
                });


                $(document).on('click','#setDoneJob', function(){

                $('.modald').fadeIn('fast');
                    $.ajax({
                            type: 'POST',
                            url: '../ajax/setdonerepair.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                jobid: ID
                            },
                            success: function(e){

                $('.modald').fadeOut('fast');
                                
                                if(e == "success"){
                                    location.reload();
                                }
                            }
                        });
                });

                $('#applychanges').on('click', function(){
                    if($('.span-parts').html() == "<br>"){
                        alert("Please Select Part.");
                    }else{
                        console.log("parts " + $('.span-parts').html());
                        console.log("tech " + techID);
                        var partsid = $('.partlistplea').text().split(')');
                        var partsIDD = "";
                        for (var i = partsid.length - 1; i >= 0; i--) {
                            var partspliter = partsid[i].split('-');
                            console.log("test" + partspliter[0]);
                            partsIDD = partsIDD + partspliter[0]+ ",";
                            // console.log("partsID " + partspliter[0].replace('#').replace(',undefined'));
                        };
                        
                        console.log("partsID " + partsIDD);
                        console.log("partsID " + partsID);

                     if($('.span-parts').html() == "<br>"){
                         alert("Please select Part");
                     }else{
                         if(techID == "1"){
                         alert("Please Select Technician.");
                         }else{
                                $('.modald').fadeIn('fast');
                             $.ajax({
                                type: 'POST',
                                url: '../ajax/updatetech_parts.php',
                                data: {
                                    action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                    jobid: ID,
                                    techID: techID,
                                    parts: $('.span-parts').html(),
                                    partsID: partsIDD,
                                    partcost: $('.partcost').text(),
                                    servicescharge: $('.servicescharge').text(),
                                    chargetotal: $('.chargetotal').text(),
                                    lessdeposit: $('.lessdeposit').text(),
                                    lessdiscount: $('.lessdiscount').text(),
                                    balancecharge: $('.balancecharge').text(),
                                    email: emailaddress,
                                    conforme: 'Waiting for Approval'
                                },
                                success: function(e){
                                    
                                 $('.modald').fadeOut('fast');
                                    if(e == "success"){
                                        location.reload();
                                    }
                                }
                            });
                         }
                     }
                    }
       //          	
                });

                $('#assignedtech').on('click',function(){
                    if(techname){
                         $('.span-tech').html(techname);
                        $('#addtech').modal('hide');
                    }else{
                        alert("Please select Technician");
                    }
                   $('#search_tech').val("");
                });

                $('[name=partsquantity]').on('keyup', function(){
                    var first = $('[name=partsquantity]').val();
                    var second = $('.partprices').text();
                    console.log(first + " sdfsf"  + second) ;
                        $('.totalpartcost').html(first * second);
                });

                $('#addparttojoborder').on('click',function(){
                    
                    $('.partcost').html($('.totalpartcost').text());
                    $('.billingstatment').slideDown('fast');
                    $('.hidethiss').slideDown('fast');
                    $('.span-parts').html("");
                     var dsd = "";

                     $( ".listofparts-beforeadded" ).each(function( index ) {
                          console.log( index + ": " + $( this ).text() );
                          parts = dsd +  $( this ).text();
                    });

                    if(parts){
                        partsString =  parts;
                        var co = parts.split('-');
                        console.log();
                        var tempparts = "";
                        for (var i = 1; i < co.length; i++) {
                            var remopar = co[i].split(")");
                            // tempparts = tempparts + remopar[0] + "),";
                            // tempparts = tempparts + co[i];
                        };

                        // $('.span-parts').html(tempparts.substr(0, tempparts.length - 1));
                        var temppartst = "# ";
                        var splitter =  parts.split(")");
                        for (var i = 0; i < splitter.length -1; i++) {
                            temppartst = temppartst + splitter[i] + ")<br>#";
                            
                        };
                        // var co2 = parts.replace(")", ")<br>");


                        $('.span-parts').html(temppartst.substr(0, temppartst.length -1));
                        $('#addpart').modal('hide');

                    }else{
                        alert("Please select part.");
                    }

                    $("#search_part").val("");
                    $('[name=partsquantity]').val("");
                    $('.partprices').html("");
                    $('.totalpartcost').html("");
                    $('.partquantity ').slideUp('fast');

                });

                //WHEN YOU CHOOSE RECORD
                $(document).on('click', ".clickable", function() {
                    $(".clickable").removeClass("selected");
                    $(this).addClass("selected");
                    ID = $(this).attr("id");
                    console.log(ID);
                });

                $('.delete').on('click',function(){
                    if(ID) {
                        $("#delete-modal").modal('show');
                        $("#idhere2").html(ID);
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }
                }); 

                $('.addtech').on('click',function(){
                    $("#addtech").modal("show");
                });

                $('.addparts').click(function(){

                    $('.listofparts-beforeadded').html("");
                    var tempdatapop = $('.partlistplea').text().replace(")",")&lt;br&gt;");

                    var looopparts =  $('.partlistplea').text().split(')');
                    var partloophandler = "";
                    for (var i = 0; i < looopparts.length - 1; i++) {
                        partloophandler = partloophandler + looopparts[i] + ")&lt;br&gt;";
                    };
                    console.log("test flight" + partloophandler);


                    if(partloophandler != ""){
                        console.log(partsStringdummy);
                        $('.listofparts-beforeadded').slideDown('fast');
                        $('.partquantity ').slideDown('fast');
                    }
                    $("#addpart").modal("show");

                    console.log("this is a dummy test" + $('.partlistplea').text());
                    console.log("this is a dummy test2 a" + partsStringdummy);

                   if(partloophandler != ")"){
                        var activeparts = partloophandler.split("&lt;br&gt;");
                        if(activeparts.length > 0){
                            for (var i = 0; i < activeparts.length-1; i++) {
                                var idmo =  activeparts[i].replace("#",  "").split("-");
                                console.log("what?" + activeparts[i]);
                                var partsname =  idmo[1].split("(");
                                    var idniya = idmo[0].replace(" ", "");
                                    var pricepart = partsname[1].split("*");
                                    // console.log.(pricepart[1]);
                                $( ".listofparts-beforeadded" ).append('<li id="'+idniya+'"><span class="idmo">'+idniya+'-</span>'+partsname[0]+'(<b class="s" data-did="'+idniya+'">'+pricepart[0]+'*<b class="quantitycounter">'+pricepart[1].replace(")", "")+'</b>)</b> <span data-id="'+idniya+'" class="pull-right removeParts"><i class="fa fa-times"></i></span></b></b></li>');
                            };
                        }
                    }

                     var m = 0;
                    var c = 0;
                    var total = 0;
                    $( ".listofparts-beforeadded" ).each(function( index ) {
                        console.log($( this ).text());
                        var stringtotaltime = $( this ).text().split("(");
                        
                        for (var i =  1; i < stringtotaltime.length; i++) {
                            var remover  = stringtotaltime[i].split(")");
                            var calcumul =  remover[0].split("*");

                                // console.log("total = " + calcumul[0] + " " + calcumul[1]  +" -=-=-=- "+ (calcumul[0] * calcumul[1]));
                                total = total + parseFloat((calcumul[0] * calcumul[1]));
                        };
                        $('.totalpartcost').html(total);

                    });



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
                           $("#" + ID).remove();
                           ID = "";
                        }else {

                        }
                    }
                });
            });

                $('#cantrepair').on('click',function(){
                    $('#addremark').modal('show');
                    $("#view-modal2").modal("hide");
                });
                
                $('#cantrepairremarks').on('click',function(){
                    if($("[name=otheremarks]").val() == ""){
                        alert("Please add remarks.");
                    }else{
                        $('.modald').fadeIn('fast');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/cantrepairremarks.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                id: ID,
                                type: 'waiting_list',
                                otherremarks: $("[name=otheremarks]").val(),
                                conforme: 'Cant Repair'
                            },
                            success: function(e){
                                
                                $('.modald').fadeOut('fast');
                                if(e == "success"){
                                    location.reload();
                                }
                            }
                        });
                    }
                });



                $('.view').on('click',function(){
                    //clearallfield();
                    $('.savesetdate').slideDown('fast');
                    $('.savesetdate').attr("id", "applychanges");
                    $('#setOngoingRepair').html('<i class="fa fa-plus"></i> Generate SOA ');
                    $('.removethisongoing').slideDown('fast');
                    $('.datelivery').slideUp('fast');
                    // $('.billingstatment').slideUp('fast');
                    $('#cantrepair').slideUp('fast');
                    $('#cantrepair2').slideUp('fast');


                    if(ID){
                    $('.modald').fadeIn('fast');
                    $("#view-modal2").modal("show");
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
                            $('[name=servicescharge]').val(obj.response[0].servicefee);
                            
                            var now = moment(obj.response[0].dateadded);
                            $('.datehere').html(now.format("MMMM D, YYYY"));
                            
                            $('.namehere').html(obj.response[0].name);
                            $('.addresshere').html(obj.response[0].address);
                            $('.contacthere').html(obj.response[0].number);
                            $('.emailhere').html(obj.response[0].email);
                            emailaddress = obj.response[0].email;

                            $('.maincategoryhere').html(obj.response[0].category);
                            $('.maincategoryhere').attr("data-mcat", obj.response[0].cat_id);
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

                            console.log(obj.response[0].parts);
                            var breakspliter = obj.response[0].parts.split("&lt;br&gt;");
                            var tempbrekaderpliter = "";

                            for (var i = 0; i < breakspliter.length; i++) {
                                tempbrekaderpliter = tempbrekaderpliter + breakspliter[i] + "<br>";
                            };

                            $('.span-parts').html(tempbrekaderpliter);

                            partsStringdummy = obj.response[0].parts;
                            console.log(obj.response[0].parts);
                            
                            if(obj.response[0].parts == "") {

                            }else{
                                $('.billingstatment').slideDown('fast');
                            }

                            $('.span-tech').html(obj.response[0].technam);
                            $('.span-remarks').html(obj.response[0].remarks);

                           if(obj.response[0].repair_status == 'Ready for Delivery'){
                                $('.removethisongoing').slideUp('fast');
                                $('.datelivery').slideDown('fast');
                                $('.savesetdate').slideUp('fast');
                                $('#setOngoingRepair').html('<i class="fa fa-check"></i>  Job Order Arrived');
                                if(obj.response[0].date_delivery == "0000-00-00"){
                                    $('.setdatedelivery').html("Delivery date is not available.");
                                    $("#setOngoingRepair").slideUp('fast');
                                }else{
                                    $('.setdatedelivery').html(obj.response[0].date_delivery);
                                }
                                $('.billingstatment').slideDown('fast');

                                $('.span-status').html('<small class="badge col-centered mlightred">Job Order Arriving Today</small>');
                            }else if(obj.response[0].repair_status == 'Waiting for SOA Approval'){
                                $('.savesetdate').html("<i class='fa fa-check'></i> Update SOA");
                                $('.span-status').html('<small class="badge col-centered mrorange">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Ongoing Repair'){
                                $('.savesetdate').slideUp('fast');
                                $('.removethisongoing').slideUp('fast');
                                $('#cantrepair').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered bg-teal">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Done-Delivered'){
                                $('.savesetdate').slideUp('fast');
                                $('.removethisongoing').slideUp('fast');
                                $('.span-status').html('<small class="badge col-centered bg-lime">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Unclaimed'){
                                $('.savesetdate').slideUp('fast');
                                $('.removethisongoing').slideUp('fast');
                                $('.span-status').html('<small class="badge col-centered bg-red">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Ready for Claiming'){
                                if(obj.response[0].technam == ""){
                                $('.savesetdate').slideUp('fast');
                                $('#cantrepair').slideUp('fast');
                                $('.removethisongoing').slideUp('fast');
                                $('.span-status').html('<small class="badge col-centered mdone">'+obj.response[0].repair_status+'</small>');
                                }else{

                                $('.savesetdate').slideUp('fast');
                                $('.removethisongoing').slideUp('fast');
                                $('.span-status').html('<small class="badge col-centered mdone">'+obj.response[0].repair_status+'</small>');
                                }
                            }else if(obj.response[0].repair_status == 'Claimed'){
                                $('.savesetdate').slideUp('fast');
                                $('.removethisongoing').slideUp('fast');
                                $('.span-status').html('<small class="badge col-centered bg-green">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Waiting List'){
                                $('#cantrepair').fadeIn('fast');
                                $('.span-status').html('<small class="badge col-centered morange">'+obj.response[0].repair_status+'</small>');
                            }else if( obj.response[0].repair_status == 'Done-Ready for Delivery'){
                                $('.span-status').html('<small class="badge col-centered mredilive">'+obj.response[0].repair_status+'</small>');
                            }else if(obj.response[0].repair_status == 'Approved'){
                                $('.span-status').html('<small class="badge col-centered approvedme">'+obj.response[0].repair_status+'</small>');
                            }

                            //clear set date field if JO is not disapproved and cant repair
                            if(obj.response[0].jobclear == '1') {
                                $('.billingstatment, .dateliveryformmain').show();
                                $('.removethisongoing, .hidethiss').hide();

                                if(obj.response[0].repair_status == 'Done-Ready for Delivery' ) {
                                    $('.servicescharge').text(obj.response[0].servicefee);
                                    $('.span-status small').text('Ready for Pickup');
                                    $('[name=datedeliverymain]').val(obj.response[0].done_date_delivery);
                                }else if(obj.response[0].repair_status == 'Ready for Claiming' ) {
                                    $('.dateliveryformmain').hide();
                                }else if(obj.response[0].repair_status == 'Claimed') {
                                    $('.dateliveryformmain').hide();
                                }
                            } else {
                                $('.dateliveryformmain').hide();
                            }
                            //
                            
                            $('.hidethiss').fadeOut('fast');

                            if(obj.response[0].repair_status != 'Waiting List'){
                                $('.addparts').fadeOut('fast');
                                $('.addtech').fadeOut('fast');
                                $('#applychanges').fadeOut('fast');
                                $('.cancel-delet').css( "margin-left", "8px");
                                $('.hidethiss').fadeIn('fast');
                            }

                            partsID = obj.response[0].partsid;
                            techID = obj.response[0].tech_id;
                            var d = obj.response[0].partsid.split(',');

                            $('.partcost').html(formatNumber(obj.response3[0].totalpartscost));
                            $('.servicescharge').html(formatNumber(obj.response3[0].service_charges));
                            $('.chargetotal').html(formatNumber(obj.response3[0].total_charges));
                            $('.lessdeposit').html(formatNumber(obj.response3[0].less_deposit));
                            $('.lessdiscount').html(formatNumber(obj.response3[0].less_discount));
                            $('.balancecharge').html(formatNumber(parseFloat(obj.response3[0].totalpartscost) + parseFloat(obj.response3[0].service_charges) + parseFloat(obj.response3[0].total_charges) - parseFloat(obj.response3[0].less_deposit) - parseFloat(obj.response3[0].less_discount)) );

                            if(obj.response3[0].service_charges == "0.00") {
                                $('[name="servicescharge"]').val("800");
                            }else{
                                $('[name="servicescharge"]').val(obj.response3[0].service_charges);
                            }

                            $('[name="chargetotal"]').val(obj.response3[0].total_charges);
                            $('[name="lessdeposit"]').val(obj.response3[0].less_deposit);
                            $('[name="lessdiscount"]').val(obj.response3[0].less_discount);

                        }

                    });
                    }else{
                        $('#selecrecord-modal').modal('show');
                    }
                });

                // main office set delivery
                $("#setdeliverydateformmain").validate({
                    errorElement: 'p',
                    // Specify the validation rules
                    rules: {
                        "datedeliverymain":{
                            required: true,
                            minlength:1
                        }
                    },
                    // Specify the validation error messages
                    messages: {
                        datedeliverymain:{
                        required: "Please provide a date",
                        minlength: "Your date must be at least 2 characters long",
                        }
                    },
                    submitHandler: function(form) {
                        $(".savesetdatemain").html('<i class="fa fa-plus"></i> Saving..');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/setdatedone.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                id: ID,
                                datedelivery: $("[name=datedeliverymain]").val()
                            },
                            success: function(e){
                                var obj = jQuery.parseJSON(e);

                                $('.modald').fadeIn('slow');

                                if(obj.status == 200){
                                    $('.modald').fadeOut('slow');
                                    $(".savesetdatemain").html('<i class="fa fa-plus"></i> Delivery Date Saved');
                                } else {
                                    $('.modald').fadeOut('slow');
                                    if(obj.status == 101) {
                                        if( $.type(obj.date_delivery) != 'undefined' && obj.date_delivery == true ) {
                                            $('input[name="datedeliverymain"]').parent().find('p.error').remove();
                                            $('input[name="datedeliverymain"]').parent().append('<p for="datedelivery" generated="true" class="error" style="position: absolute;top: 58px;">Date is already set.</p>');
                                        }

                                        $(".savesetdatemain").html('<i class="fa fa-plus"></i>  Save Delivery Date');
                                    }
                                }
          
                            }
                        });
                        return false;
                    }
                    });

                $('.delete').on('click',function(){
                    if(ID){

                    }else{
                        $('#selecrecord-modal').modal('show');
                    }
                });

                $('.add').on('click',function(){
                    $("#create-joborder").modal('show');
                    $('input[name="name"]').val("");
                });

                $("#search_part").keyup(function(){
                        var toSearch = $("#search_part").val();
                        $('.search-list-result-part').slideDown('fast');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/search_part.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                toSearch: toSearch,
                                categoryid : $('.maincategoryhere').attr("data-mcat"),
                                jobid : ID
                            },
                            success: function(e){
                                    $('.search-list-part').html(''); 
                                    if(e != 'error'){
                                        var obj = jQuery.parseJSON(e);
                                        var data = "";
                                        for (var i = 0; i < obj.response.length; i++) {
                                            $('.search-list-part').append("<option value='"+obj.response[i].name+"~"+obj.response[i].part_id+"~"+obj.response2[i].parts_free+"~"+obj.response2[i].diagnostic_free+"'>" +obj.response[i].name+"</option>");
                                        };
                                    }
                            }
                        });
                });

                $('.search-list-part').change(function(){
                    
                    $("#search_part").value = $(this).val();
                     // $('.search-list').fadeOut('fast');
                     var m = $(this).val();
                     var re  = m.toString().split('~');
                         // $("#search_part").val(re[0]);

                        partscount++;
                        $('.partprices').html(re[2]);

                        if(partscount > 1){
                            partsID = partsID + "," +re[1];
                        }else{
                            partsID = re[1];
                        }

                        $('.search-list-result-part').slideUp('fast');
                        $('.partquantity ').slideDown('fast');

                        // Set service charge
                        $('[name="servicescharge"]').val(re[3]);
                        $('.servicescharge').text(re[3]);

                         $.ajax({
                            type: 'POST',
                            url: '../ajax/viewjoborder.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                jobid: re[1]
                            },
                            success: function(e){
                                $("#search_part").val("");
                                var itemcount = parseInt($(".listofparts-beforeadded  li").length) - 1;

                                if(itemcount == -1){
                                    $( ".listofparts-beforeadded" ).append('<li id="'+re[1]+'"><span class="idmo">'+re[1]+'-</span>'+re[0]+' (<b class="s" data-did="'+re[1]+'">'+re[2]+'*<b class="quantitycounter">1</b>)</b> <span data-id="'+re[1]+'" class="pull-right removeParts"><i class="fa fa-times"></i></span></b></b></li>');
                                }else{
                                     $( ".listofparts-beforeadded" ).each(function( index ) {
                                        var ids;
                                        var cc =  $(".listofparts-beforeadded li .idmo" ).text().split("-");
                                         for (var i = 0; i < cc.length - 1; i++) { 
                                            if(cc[i] == re[1]) {
                                                ids = "true";
                                                break;
                                            }else{
                                                ids = "false";
                                            }
                                         }
                                         if(ids != "true") {
                                            if(itemcount != -1){ 
                                                $( ".listofparts-beforeadded li:eq("+itemcount+")" ).after('<li id="'+re[1]+'"><span class="idmo">'+re[1]+'-</span>'+re[0]+' (<b  class="s" data-did="'+re[1]+'">'+re[2]+'*<b class="quantitycounter">1</b>)</b> <span data-id="'+re[1]+'" class="pull-right removeParts"><i class="fa fa-times"></i></span></b></b></li>');
                                            }
                                         }else{
                                            var getquantity = $("#" + re[1] + " .quantitycounter").text();
                                            $("#" + re[1] + " .quantitycounter").html(parseInt(getquantity) + 1);
                                         }
                                    });
                                }

                                var m = 0;
                                var c = 0;
                                var total = 0;
                                $( ".listofparts-beforeadded" ).each(function( index ) {
                                    console.log($( this ).text());
                                    var stringtotaltime = $( this ).text().split("(");
                                    
                                    for (var i =  1; i < stringtotaltime.length; i++) {
                                        var remover  = stringtotaltime[i].split(")");
                                        var calcumul =  remover[0].split("*");

                                            // console.log("total = " + calcumul[0] + " " + calcumul[1]  +" -=-=-=- "+ (calcumul[0] * calcumul[1]));
                                            total = total + parseFloat((calcumul[0] * calcumul[1]));
                                    };
                                    $('.totalpartcost').html(total);

                                });

                            }
                        });

                        
                    });

                 $("#search_tech").keyup(function(){
                    var toSearch = $("#search_tech").val();
                    $('.search-list-tech').html("");
                    $('.search-list-result-tech').slideDown('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/search_tech.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            toSearch: toSearch
                        },
                        success: function(e){
                            
                            if(e != 'error'){
                                var obj = jQuery.parseJSON(e);
                                var data = "";
                                for (var i = 0; i < obj.response.length; i++) {
                                    $('.search-list-tech').append("<option value='"+obj.response[i].name+"~"+obj.response[i].tech_id+"'>" +obj.response[i].name+"</option>");
                                };
                            }
                        }
                    });
                });

                $('.search-list-tech').change(function(){
                console.log($(this).val());
                $("#search_tech").value =$(this).val();
                 var m = $(this).val();
                 var re  = m.toString().split('~');
                     $("#search_tech").val(re[0]);
                     techname = re[0];
                     techID = re[1];
                     $('.search-list-result-tech').slideUp('fast');

                     $.ajax({
                        type: 'POST',
                        url: '../ajax/viewjoborder.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: re[0]
                        },
                        success: function(e){
                            
                            $('.invoice-hishow').slideDown('fast');
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
                            $('.span-status').html(obj.response[0].status);
                        }
                    });
            });

    $("select[name='warranty'], select[name='ewarranty']").change(function(){
            if($(this).val() == 1) {
                console.log("yes");
                $('.hideshow').fadeIn('fast');
            }else {
                console.log("no");
                $('.hideshow').fadeOut('fast');
            }
    });

$("#createjob").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
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
            "date":{
            required: true
            },
            "diagnosis":{
            required: true,
            minlength:1
            },
            "warranty":{
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
            name:{
            required: "Please provide a Name",
            minlength: "Your password must be at least 2 characters long",
            },
            number:{
            required: "Please provide a number",
            minlength: "Your number must be at least 11 interger long"
            },
            email:{
            required: "Please provide a Email Address",
            minlength: "Your number must be at least 11 interger long"
            },
            address:{
            required: "Please provide a Address",
            minlength: "Your number must be at least 11 interger long"
            },
            customertype:{
            required: "Please make a selection from the list. type"
            },
            itemname:{
            required: "Please provide a Item Name",
            minlength: "Your number must be at least 11 interger long"
            },
            date:{
            required: "Please provide a Date",
            minlength: "Your number must be at least 11 interger long"
            },
            diagnosis:{
            required: "Please provide a Diagnosis",
            minlength: "Your number must be at least 11 interger long"
            },
            warranty:{
            required: "Please select Warranty status."
            },
            remarks:{
            required: "Please provide a Remarks",
            minlength: "Your number must be at least 11 interger long"
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
                        warranty: $("[name=warranty]").val(),
                        warranty_date: $("[name=warranty_date]").val(),
                        warranty_type: $("[name=warranty_type]").val(),
                        itemname: $("[name=itemname]").val(),
                        diagnosis: $("[name=diagnosis]").val(),
                        remarks: $("[name=remarks]").val(),
                        date: $("[name=date]").val(),
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
                            $('select[name="ewarranty"]').val(obj.response[0].isunder_warranty);
                            console.log(obj.response[0].remarks);
                            console.log(obj.response[0].diagnosis);
                             customerID  = obj.response[0].customerid;
                             joborderid  = obj.response[0].jobid;
                            $('input[name="eitemname"]').val(obj.response[0].item);
                            $('#eremarks').val(obj.response[0].remarks);
                            $('select[name="ediagnosis"]').val(obj.response[0].diagnosis);
                            var dat2 = obj.response[0].estimated_finish_date.split("-");
                            $('input[name="edate"]').val(dat2[0] + "-" + dat2[1] + "-"+ dat2[2]);

                            if(obj.response[0].isunder_warranty == 1) {
                                $('.hideshow').fadeIn('fast');
                                var dat = obj.response[0].warranty_date.split("-");
                                $('input[name="ewarranty_date"]').val(dat[0] + "-" + dat[1] + "-"+ dat[2].substring(0,2));
                                $('select[name="ewarranty_type"]').val(obj.response[0].warranty_type);
                            }

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

        $("#editjoborder").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
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
            "edate":{
            required: true
            },
            "ediagnosis":{
            required: true,
            minlength:1
            },
            "ewarranty":{
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
            edate:{
            required: "Please provide a Date"
            },
            ediagnosis:{
            required: "Please provide a Diagnosis"
            },
            ewarranty:{
            required: "Please select Warranty status."
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
                        warranty: $("[name=ewarranty]").val(),
                        warranty_date: $("[name=ewarranty_date]").val(),
                        warranty_type: $("[name=ewarranty_type]").val(),
                        itemname: $("[name=eitemname]").val(),
                        diagnosis: $("[name=ediagnosis]").val(),
                        remarks: $("[name=eremarks]").val(),
                        date: $("[name=edate]").val(),
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
        });

            $('.search-list').change(function(){
                console.log($(this).val());
                $("#search_customers").value =$(this).val();
                 // $('.search-list').fadeOut('fast');
                 var m = $(this).val();
                 var re  = m.toString().split('~');
                     $("#search_customers").val(re[1]);
                     $('.search-list-result').slideUp('fast');

                     $.ajax({
                        type: 'POST',
                        url: '../ajax/viewjoborder.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: re[0]
                        },
                        success: function(e){
                            
                            $('.invoice-hishow').slideDown('fast');
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
                            $('.span-status').html(obj.response[0].status);
                        }
                    });
            });

                $('#cmd').click(function () {

                    var jobOrder = {
                          content: [
                            { text: 'Job Order No.' + ID, style: 'header' },
                            { text: 'Date : ' + $('.getdata .datehere').text(), style: 'date' },
                            {   table: {
                                    widths: [ '*', 220, '*', '*' ],
                                    body: [
                                        [{ text: 'Customer Name', style: 'title' }, { text: $('.getdata .namehere').text(), style: 'data' },{ text: 'Branch Name', style: 'title' }, { text: $('.getdata .branchnamehere').text(), style: 'data' }],

                                        [{ text: 'Address', style: 'title' }, { text: $('.getdata .addresshere').text(), style: 'data' }, { text: 'Address', style: 'title' }, { text: $('.getdata .addresshere').text(), style: 'data' }],

                                        [{ text: 'Email Address', style: 'title' }, { text: $('.getdata .emailhere').text(), style: 'data' },{ text: 'Branch Address', style: 'title' }, { text: $('.getdata .branchaddresshere').text(), style: 'data' }],

                                        [{ text: 'Customer Type', style: 'title' }, { text: $('.getdata .ctypehere').text(), style: 'data' }, { text: 'Phone number', style: 'title' }, { text: $('.getdata .branchphonehere').text(), style: 'data' }],

                                        [{ text: 'Main Category', style: 'title' }, { text: $('.getdata .maincategoryhere').text(), style: '' },{ text:''}, { text:'' }]
                                    ]
                                },
                                layout: 'noBorders'
                            },
                            


                            { style: 'tableExample',
                                table: {
                                    widths: ['*', '*', 110, '*',110,'*'],
                                    body: [

                                        [ 'Item', 'Diagnosis', 'Parts', 'Technician', 'Remarks', 'Status'],
                                        [ $('.getdata .span-item').text(), $('.getdata .span-diagnosis').text(), $('.getdata .span-parts').text(), $('.getdata .span-tech').text(), { text: $('.getdata .span-remarks').text(), italics: true, color: 'gray' }, $('.getdata .span-status').text() ]
                                    ]
                                }
                            },

                            { columns: [
                                    {
                                        width: 'auto',
                                        bold: true,
                                        text: 'Total Parts Cost: :\nService Charges :\nTotal Charges: \nLess Deposit:\nLess Discount:\nBalance:'
                                    },
                                    {
                                        width: 'auto',
                                        marginLeft: 10,
                                        text: $('.getdata .partcost').text()+' \n '+$('.getdata .servicescharge').text()+' \n '+$('.getdata .chargetotal').text()+' \n '+$('.getdata .lessdeposit').text()+' \n '+$('.getdata .lessdiscount').text()+' \n '+$('.getdata .balancecharge').text()+' \n '
                                    }
                                ]
                            },
                        ],

                        info: {
                            title: 'Job Order No. -- ' + ID,
                            author: 'JB Sports & Music',
                            subject: 'Job Order Info',
                        },


                        pageSize: 'A5',  
                        pageOrientation: 'landscape',
                          styles: {
                                header: {
                                    fontSize: 16,
                                    bold: true
                                },
                                date: {
                                    fontSize: 12,
                                    bold: true,
                                    marginTop: -15,
                                    marginBottom: 8,
                                    alignment: 'right'
                                },
                                title: {
                                    fontSize: 11,
                                    bold: true
                                },
                                data: {
                                    fontSize: 11,
                                },
                                invoiceinfo: {
                                    fontSize: 11
                                },
                                invoiceinfo2: {
                                    fontSize: 11,
                                    marginTop: -80,
                                    alignment: 'right'
                                },
                                tableExample: {
                                    margin: [0, 30, 0, 15],
                                }
                            }
                    };

                    pdfMake.createPdf(jobOrder).open();
                    pdfMake.createPdf(jobOrder).download('Job Order No. ' + ID + ".pdf");
                });

//                 $('#cmd').click(function () {
// var docDefinition = {
//     content: [
//                 { text: 'Tables', style: 'header' },
//                 'Official documentation is in progress, this document is just a glimpse of what is possible with pdfmake and its layout engine.',
//                 { text: 'A simple table (no headers, no width specified, no spans, no styling)', style: 'subheader' },
//                 'The following table has nothing more than a body array',
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 body: [
//                                         ['Column 1', 'Column 2', 'Column 3'],
//                                         ['One value goes here', 'Another one here', 'OK?']
//                                 ]
//                         }
//                 },
//                 { text: 'A simple table with nested elements', style: 'subheader' },
//                 'It is of course possible to nest any other type of nodes available in pdfmake inside table cells',
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 body: [
//                                         ['Column 1', 'Column 2', 'Column 3'],
//                                         [
//                                                 {
//                                                         stack: [
//                                                                 'Let\'s try an unordered list',
//                                                                 {
//                                                                         ul: [
//                                                                                 'item 1',
//                                                                                 'item 2'
//                                                                         ]
//                                                                 }
//                                                         ]
//                                                 },
//                                                 /* a nested table will appear here as soon as I fix a bug */
//                                                 [
//                                                     'or a nested table',
//                                                     {
//                                                         table: {
//                                                             body: [
//                                                                 [ 'Col1', 'Col2', 'Col3'],
//                                                                 [ '1', '2', '3'],
//                                                                 [ '1', '2', '3']
//                                                             ]
//                                                         },
//                                                     }
//                                                 ],
//                                                 { text: [
//                                                         'Inlines can be ',
//                                                         { text: 'styled\n', italics: true },
//                                                         { text: 'easily as everywhere else', fontSize: 10 } ]
//                                                 }
//                                         ]
//                                 ]
//                         }
//                 },
//                 { text: 'Defining column widths', style: 'subheader' },
//                 'Tables support the same width definitions as standard columns:',
//                 {
//                         bold: true,
//                         ul: [
//                                 'auto',
//                                 'star',
//                                 'fixed value'
//                         ]
//                 },
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 widths: [100, '*', 200, '*'],
//                                 body: [
//                                         [ 'width=100', 'star-sized', 'width=200', 'star-sized'],
//                                         [ 'fixed-width cells have exactly the specified width', { text: 'nothing interesting here', italics: true, color: 'gray' }, { text: 'nothing interesting here', italics: true, color: 'gray' }, { text: 'nothing interesting here', italics: true, color: 'gray' }]
//                                 ]
//                         }
//                 },
//                 { text: 'Headers', style: 'subheader' },
//                 'You can declare how many rows should be treated as a header. Headers are automatically repeated on the following pages',
//                 { text: [ 'It is also possible to set keepWithHeaderRows to make sure there will be no page-break between the header and these rows. Take a look at the document-definition and play with it. If you set it to one, the following table will automatically start on the next page, since there\'s not enough space for the first row to be rendered here' ], color: 'gray', italics: true },
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 headerRows: 1,
//                                 // dontBreakRows: true,
//                                 // keepWithHeaderRows: 1,
//                                 body: [
//                                         [{ text: 'Header 1', style: 'tableHeader' }, { text: 'Header 2', style: 'tableHeader' }, { text: 'Header 3', style: 'tableHeader' }],
//                                         [
//                                                 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
//                                         ]
//                                 ]
//                         }
//                 },
//                 { text: 'Column/row spans', style: 'subheader' },
//                 'Each cell-element can set a rowSpan or colSpan',
//                 {
//                         style: 'tableExample',
//                         color: '#444',
//                         table: {
//                                 widths: [ 200, 'auto', 'auto' ],
//                                 headerRows: 2,
//                                 // keepWithHeaderRows: 1,
//                                 body: [
//                                         [{ text: 'Header with Colspan = 2', style: 'tableHeader', colSpan: 2, alignment: 'center' }, {}, { text: 'Header 3', style: 'tableHeader', alignment: 'center' }],
//                                         [{ text: 'Header 1', style: 'tableHeader', alignment: 'center' }, { text: 'Header 2', style: 'tableHeader', alignment: 'center' }, { text: 'Header 3', style: 'tableHeader', alignment: 'center' }],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ { rowSpan: 3, text: 'rowSpan set to 3\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor' }, 'Sample value 2', 'Sample value 3' ],
//                                         [ '', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', { colSpan: 2, rowSpan: 2, text: 'Both:\nrowSpan and colSpan\ncan be defined at the same time' }, '' ],
//                                         [ 'Sample value 1', '', '' ],
//                                 ]
//                         }
//                 },
//                 { text: 'Styling tables', style: 'subheader' },
//                 'You can provide a custom styler for the table. Currently it supports:',
//                 {
//                         ul: [
//                                 'line widths',
//                                 'line colors',
//                                 'cell paddings',
//                         ]
//                 },
//                 'with more options coming soon...\n\npdfmake currently has a few predefined styles (see them on the next page)',
//                 { text: 'noBorders:', fontSize: 14, bold: true, pageBreak: 'before', margin: [0, 0, 0, 8] },
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 headerRows: 1,
//                                 body: [
//                                         [{ text: 'Header 1', style: 'tableHeader' }, { text: 'Header 2', style: 'tableHeader'}, { text: 'Header 3', style: 'tableHeader' }],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                 ]
//                         },
//                         layout: 'noBorders'
//                 },
//                 { text: 'headerLineOnly:', fontSize: 14, bold: true, margin: [0, 20, 0, 8] },
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 headerRows: 1,
//                                 body: [
//                                         [{ text: 'Header 1', style: 'tableHeader' }, { text: 'Header 2', style: 'tableHeader'}, { text: 'Header 3', style: 'tableHeader' }],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                 ]
//                         },
//                         layout: 'headerLineOnly'
//                 },
//                 { text: 'lightHorizontalLines:', fontSize: 14, bold: true, margin: [0, 20, 0, 8] },
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 headerRows: 1,
//                                 body: [
//                                         [{ text: 'Header 1', style: 'tableHeader' }, { text: 'Header 2', style: 'tableHeader'}, { text: 'Header 3', style: 'tableHeader' }],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                 ]
//                         },
//                         layout: 'lightHorizontalLines'
//                 },
//                 { text: 'but you can provide a custom styler as well', margin: [0, 20, 0, 8] },
//                 {
//                         style: 'tableExample',
//                         table: {
//                                 headerRows: 1,
//                                 body: [
//                                         [{ text: 'Header 1', style: 'tableHeader' }, { text: 'Header 2', style: 'tableHeader'}, { text: 'Header 3', style: 'tableHeader' }],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                         [ 'Sample value 1', 'Sample value 2', 'Sample value 3' ],
//                                 ]
//                         },
//                         layout: {
//                             hLineWidth: function(i, node) {
//                                 return (i === 0 || i === node.table.body.length) ? 2 : 1;
//                             },
//                             vLineWidth: function(i, node) {
//                                 return (i === 0 || i === node.table.widths.length) ? 2 : 1;
//                             },
//                             hLineColor: function(i, node) {
//                                 return (i === 0 || i === node.table.body.length) ? 'black' : 'gray';
//                             },
//                             vLineColor: function(i, node) {
//                                 return (i === 0 || i === node.table.widths.length) ? 'black' : 'gray';
//                             },
//                             // paddingLeft: function(i, node) { return 4; },
//                             // paddingRight: function(i, node) { return 4; },
//                             // paddingTop: function(i, node) { return 2; },
//                             // paddingBottom: function(i, node) { return 2; }
//                         }
//                 }
//     ],
//     styles: {
//         header: {
//             fontSize: 18,
//             bold: true,
//             margin: [0, 0, 0, 10]
//         },
//         subheader: {
//             fontSize: 16,
//             bold: true,
//             margin: [0, 10, 0, 5]
//         },
//         tableExample: {
//             margin: [0, 5, 0, 15]
//         },
//         tableHeader: {
//             bold: true,
//             fontSize: 13,
//             color: 'black'
//         }
//     },
//     defaultStyle: {
//         // alignment: 'justify'
//     }
// };

// pdfMake.createPdf(docDefinition).open();
// pdfMake.createPdf(docDefinition).download('Job Order No. ' + ID + ".pdf");

//                 });
        
            });
        </script>
<?php
    htmlFooter('dashboard');
?>