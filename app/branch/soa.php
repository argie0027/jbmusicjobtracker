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

            if($value['name'] == 'statements_of_account') {
                $soa = true;
            }
        }

        if(!isset($soa)) {
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
            // $name = $query['branch_name'];
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
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                   <?php sidebarMenu(); ?>
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
              <?php breadcrumps('Statements of Account'); ?>
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
                                                <th>Item</th>
                                                <th>Customer Name</th>
                                                <th>Parts</th>
                                                <th>Diagnosis</th>
                                                <th>Cost</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php 
                                             if(isset($_GET['daterange'])){
                                                 $bydate = split ("to", $_GET['daterange']); 
                                                    if(!isset($_GET['type'])){
                                                     $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid = b.jobid AND b.customerid = c.customerid AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                   }else{
                                                        $type = $_GET['type'];
                                                        if($type == "waiting_for_soa_approval") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Waiting for SOA Approval' AND b.isdeleted != 1 AND (a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."') ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_delivery") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ready for Delivery' AND isdeleted != 1 AND (a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."') ORDER BY a.created_at DESC";
                                                        }else if($type == "ongoing_repair") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 AND (a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."') ORDER BY a.created_at DESC";
                                                        }else if($type == "today") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.done_date_delivery = '".date("y-m-d")."' AND b.isdeleted != 1 AND (a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."') ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_claiming") {
                                                            $sql = "SELECT c.name, b.diagnosis, b.item,b.jobid, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem FROM  jb_joborder b , jb_customer c, jb_diagnosis d WHERE  b.diagnosis = d.id AND  b.branchid  ='".$_SESSION['Branchid']. "' AND b.customerid = c.customerid AND b.repair_status = 'Ready for Claiming' ORDER BY b.created_at DESC";
                                                        }else if($type == "Claimed") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Claimed' AND b.isdeleted != 1 AND (a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."') ORDER BY a.created_at DESC";
                                                        }else if($type == "approved") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Approved' AND b.isdeleted != 1 AND (a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."') ORDER BY a.created_at DESC";}
                                                        }
                                             }else{
                                                    if(!isset($_GET['type'])){
                                                     $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                   }else{
                                                        $type = $_GET['type'];
                                                        if($type == "waiting_for_soa_approval") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Waiting for SOA Approval' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_delivery") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ready for Delivery' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "ongoing_repair") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "today") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.done_date_delivery = '".date("y-m-d")."' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_claiming") {
                                                            $sql = "SELECT c.name, b.diagnosis, b.item,b.jobid, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem FROM  jb_joborder b , jb_customer c, jb_diagnosis d WHERE  b.diagnosis = d.id AND  b.branchid  ='".$_SESSION['Branchid']. "' AND b.customerid = c.customerid AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 ORDER BY b.created_at DESC";
                                                        }else if($type == "Claimed") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Claimed' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "approved") {
                                                             $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost + e.service_charges+ e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid ='".$_SESSION['Branchid']. "' AND b.branchid  ='".$_SESSION['Branchid']. "' AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Approved' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }
                                                   }
                                             }
                                                $queryforexcel = $sql;
                                                $queryforexcel = str_replace("+", "~~", $queryforexcel);
                                                $query =$db->ReadData($sql); 

                                                foreach ($query as $key => $value) {
                                                    ?>
                                                        <tr id="<?php echo $value['jobid']; ?>" class="clickable">
                                                            <td><?php echo $value['jobid'];?></td>
                                                            <td><?php echo $value['item']; ?></td>
                                                            <td><?php echo $value['name']; ?></td>
                                                            <td><?php echo str_replace("&lt;br&gt;","<br>",$value['parts']); ?></td>
                                                            <td><?php echo $value['diagnosisitem']; ?></td>
                                                             <td>
                                                            <?php
                                                            
                                                            if(isset($_GET['type'])){
                                                                if($type == "ready_for_claiming" OR $type == "ready_for_claiming" ){
                                                                    $quercost = "SELECT (totalpartscost + service_charges+ total_charges) as total FROM jb_cost WHERE  jobid = '".$value['jobid']."'";
                                                                    $quercost =$db->ReadData($quercost);
                                                                    if($quercost){
                                                                        echo "<b>P</b> ".number_format($quercost[0]['total'],2);
                                                                    } 
                                                                }else{
                                                                    echo "<b>P</b> ".number_format($value['totalcost'],2);   
                                                                }
                                                            }else{
                                                                echo "<b>P</b> ".number_format($value['totalcost'],2);
                                                            }
                                                            
                                                            ?></td>
                                                            <?php
                                                                $return = " ";
                                                                    if($value['repair_status'] == "Ready for Delivery") {
                                                                        $return = $return . "<td><small class=\"badge col-centered mlightred\">".$value['repair_status']."</small></td>";
                                                                    }else if($value['repair_status'] == "Waiting for SOA Approval") {
                                                                        $return = $return . "<td><small class=\"badge col-centered mrorange\">Waiting for Customer Approval</small></td>";
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
                                                                    echo $return;
                                                            ?>
                                                        </tr>
                                                    <?php 
                                                }
                                            ?>
                                        </tbody>
                                        
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
       <div class="modal fade" id="viewsoa" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> Jor Order  #<span class="idhere"></span>
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
                        <div class="col-xs-12 tddd table-responsive">
                            <table id="togenerate" class="table table-bordered table-hover">
                                <thead>
                                    <tr height="20">
                                        <th>Item</th>
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
                                        <td><span class="span-parts"></span></td>
                                        <td><span class="span-tech"></span></td>
                                        <td><span class="span-remarks"></span></td>
                                        <td><span class="span-status"></span></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="col-sm-4 invoice-col">
                            
                            <address>
                                <strong>Total Parts Cost: </strong><span class="totalpartcost"></span><br>
                                <strong>Service Charges : </strong><span class="servicescharge"></span><br>
                                <strong>Total Charges: </strong><span class="totalcharges"></span><br>
                                <strong>Less Deposit: </strong><span class="lessdeposit"></span><br>
                                <strong>Less Discount: </strong><span class="lessdiscount"></span><br>
                                <strong>Balance: </strong><span class="balance"></span><br>
                            </address>

                        </div><!-- /.col -->
                        <div class="col-sm-3 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-5 invoice-col">
                            <strong>Computed By: </strong><span class="computedby"></span><br>
                                <strong>Accepted By : </strong><span class="acceptedby"></span><br>
                                <strong class=" pull-left ">Conforme: </strong>
                                <small  class="waitingview badge pull-left mrorange"> <i class="fa fa-check"> </i>  Waiting for Approval </small>
                                <small  class="approvedview badge pull-left approvedme"> <i class="fa fa-check"> </i> Approved  </small>
                                <small  class="disapprovedview badge pull-left mred"> <i class="fa fa-times"> </i> Disapproved </small>
                                <small  class="cantrepairview badge pull-left bg-grey"> <i class="fa fa-times"> </i> Cant Repair </small>
                                <button id="approvejob" class=" approvedview2 btn bg-green  margin"> <i class="fa fa-check"> </i>  Approve </button>
                                <button id="cantapprove" class=" approvedview2 btn bg-red  margin"> <i class="fa fa-check"> </i>  Disapprove </button>
                                
                                <div class="ongoingrepairhideshow2">
                                 <br>
                                        <label>Delivery Date:</label>
                                        <input type="text" name="datedelivery" class="form-control" placeholder="Estimated Finish Date">
                                        <button id="save_donedate" class=" approvedview2 btn bg-green margin"><i class="fa fa-check"> </i> Save Delivery Date</button>
                                </div>

                                <button id="claimedjoborder" class=" approvedview3 btn bg-green  margin" style="display: inline;"> <i class="fa fa-check"> </i>  Item Claimed </button>
                                
                                <div class="form-group datelivery2">
                                <br>
                                <br>
                                <br>
                                    <b>Delivery Date:</b><br>
                                        <div class="table-responsive">
                                            <b>Date:</b> <span class="setdatedelivery"></span>
                                        </div>
                                        <br>
                                        <div class="table-responsive">
                                           <button id="setReadyForClaiming" class="btn approvedview2 btn-success"><i class="fa fa-check"></i> Job Order Arrived( Main )</button>
                                        </div>
                                    </div>
                        </div><!-- /.row -->
                </section><!-- /.content -->
                            <button id="cmd" class="btn btn-primary" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>
                         <button type="button" class="btn btnmc  cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                <div class="modal fade" id="approve-alert" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Job Approval</h4>
                        </div>
                        <div class="modal-body">

      
                        <div class="alert alert-info alert-dismissable">
                            <i class="fa fa-info"></i>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            Please make sure that the Customer pays the amount listed here. Service Team will not proceed with the repair unless this is fully settled. Plase input the Reference No. below
                        </div>

                        <div class="clear"></div>
                            
                            <address>
                                <strong>Total Parts Cost: </strong><span class="totalpartcost">300</span><br>
                                <strong>Service Charges : </strong><span class="servicescharge">800.00</span><br>
                                <strong>Total Charges: </strong><span class="totalcharges">1500</span><br>
                                <strong>Less Deposit: </strong><span class="lessdeposit">200</span><br>
                                <strong>Less Discount: </strong><span class="lessdiscount">0.00</span><br>
                                <strong>Balance: </strong><span class="balance">2400</span><br>
                            </address>
     
                        <div class="clear"></div>

                            <form id="approvejobid" name="approvejobid" method="post" role="form">
                                <label>Reference Number:</label>
                                <input type="text" name="referencenumberfinal" class="form-control" placeholder="Reference ">
                                <br>
                                <!-- <button type="submit"class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> Approve Now! </button> -->
                                <div class="text-right">
                                    <button type="submit" id="cancel" class="btn btnmc"  data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>
                                    <button class="btn btn-success cancel-delet">Approve Now</button>
                                </div>
                             </form>
                        <div class="clear"></div>
                        </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                </div><!-- /.modal -->

                 <div class="modal fade" id="disapprove-alert" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Are you sure?</h4>
                        </div>
                        <div class="modal-body">
                             <button type="submit" id="cancel" class="btn btnmc"  data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>
                             <button type="submit" id="disapproves" class="btn btn-danger cancel-delet"  data-dismiss="modal"><i class="fa fa-check"></i> Disapprove Now! </button>
                        <div class="clear"></div>
                        </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                </div><!-- /.modal -->

                <div class="modal fade" id="readyforclaiming" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="fa  fa-exclamation-check"></i> Item arrived. Customer will be notified. </h4>
                        </div>
                        <div class="modal-body text-right">
                             <button type="submit" id="123" class="btn btnmc"  data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>
                             <button type="submit" id="yestoclaiminng" class="btn btn-success cancel-delet"  data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
                        <div class="clear"></div>
                        </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                </div><!-- /.modal -->

                <div class="modal fade" id="jobrepairstatus-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Job Order No. <span class="idhere"></span> is now ready for claming? <br>Would you like to notify the client?</h4>
                            </div>
                            <div class="modal-body text-right">
                                <button type="submit" class="btn btnmc btn-dismiss"  data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>
                                <button type="submit" id="setDoneJob" class="btn btn-success cancel-delet"  data-dismiss="modal"><i class="fa fa-check"></i> Yes </button> 
                                <div class="clear"></div>
                            </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                <div class="modal fade" id="claimjobordermodal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Are you sure the item is already claimed by the customer?</h4>

                            </div>
                            <div class="modal-body text-right">
                                <button type="submit" class="btn btn-dismiss btnmc"  data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>
                                <button type="submit" id="setClaimedjob" class="btn btn-success cancel-delet"  data-dismiss="modal"><i class="fa fa-check"></i> Yes </button>
                                <div class="clear"></div>
                            </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->
                 <div class="modald">
                    <img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif">
                </div>

                    <script src="<?php echo SITE_JS_DIR ?>pdf/jquery-1.11.0.min.js"></script>
                    <script src='<?php echo SITE_JS_DIR ?>/pdfmake.min.js'></script>
                    <script src='<?php echo SITE_JS_DIR ?>/vfs_fonts.js'></script>
                    <div class="test">

                    </div>
                <div class="modal fade" id="selecrecord-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> <span class="errormessage">Please make a selection from the list.</span> </h4>
                </div>
                <div class="modal-body">
                     <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
                <div class="clear"></div>
                </div><!-- /.modal-content --> 
                </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
        </div><!-- /.modal -->
        <script type="text/javascript">
            $(function() { 
                var ID = "";
               
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
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } else { ?>

                            <?php $type = $_GET['type'];
                            if($type == "waiting_for_soa_approval") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Waiting for SOA Approval' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ready_for_delivery") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.sopluaa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ready for Delivery' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ongoing_repair"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "today"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.done_date_delivery = '<?php echo date('y-m-d') ?>' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ready_for_claiming"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT c.name, b.diagnosis, b.item,b.jobid, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem FROM  jb_joborder b , jb_customer c, jb_diagnosis d WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND  b.diagnosis = d.id AND  b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND b.customerid = c.customerid AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY b.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "Claimed"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Claimed' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "approved"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Approved' AND b.isdeleted != 1 AND (a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"') ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php } ?>

                        <?php } ?>

                        query = query.replace(/plus/g,"~~");
                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcelbranch.php?querytogenerate='+query+"&&type=soa2&&filename=soabranchexcel";
                        window.location = page;// you can use window.open also

                    <?php } else { ?>
                        var filter = $('#example1_filter label input').val();

                        <?php if(!isset($_GET['type'])) { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } else { ?>

                            <?php $type = $_GET['type'];
                            if($type == "waiting_for_soa_approval") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Waiting for SOA Approval' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ready_for_delivery") { ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ready for Delivery' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ongoing_repair"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "today"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.done_date_delivery = '<?php echo date('y-m-d') ?>' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "ready_for_claiming"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT c.name, b.diagnosis, b.item,b.jobid, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem FROM  jb_joborder b , jb_customer c, jb_diagnosis d WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND  b.diagnosis = d.id AND  b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND b.customerid = c.customerid AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 ORDER BY b.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "Claimed"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Claimed' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php }else if($type == "approved"){ ?>
                                if ( filter.length ) {
                                    var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts, d.diagnosis as diagnosisitem, (e.totalpartscost plus e.service_charges plus e.total_charges) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%') AND a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.branchid = <?php echo $_SESSION['Branchid'] ?> AND b.branchid  = <?php echo $_SESSION['Branchid'] ?> AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Approved' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                            <?php } ?>

                        <?php } ?>

                        query = query.replace(/plus/g,"~~");
                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcelbranch.php?querytogenerate='+query+"&&type=soa2&&filename=soabranchexcel";
                        window.location = page;// you can use window.open also

                    <?php } ?>

                });


               $("#approvejobid").validate({
                errorElement: 'p',
                rules: {
                "referencenumberfinal":{
                    required: true,
                    minlength:2
                }
                },
                // Specify the validation error messages
                messages: {
                referencenumberfinal:{
                    required: "Please provide a Reference",
                    minlength: "Your password must be at least 2 characters long",
                }
                },
                submitHandler: function(form) {
                        $('.modald').fadeIn('fast');
                         $.ajax({
                            type: 'POST',
                            url: '../ajax/approvejob.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                jobid: ID,
                                reference: $("[name=referencenumberfinal]").val(),
                                parts: $('.span-parts').text(),
                                conforme: 'Approved'
                        },
                        success: function(e){
                            $('.modald').fadeOut('fast');
                            $('#approve-alert').modal('hide');
                            location.reload();
                                if(e == "success"){
                                    location.reload();
                                }
                            }
                        });
                        return false;
                    }
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
                                window.location.assign("" + "<?php echo SITE_URL;?>branch/soa.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>branch/soa.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }
                            ?>
                            <?php
                        }
                    ?>
            }
            );

                $('.add').css('display','none');
                $('.edit').css('display','none');
                $('.delete').css('display','none');

                $('#cantapprove').on('click',function(){
                    $('#disapprove-alert').modal('show');
                    $("#viewsoa").modal("hide");
                });

                $(document).on('click', ".clickable", function() {
                    $(".clickable").removeClass("selected");
                    $(this).addClass("selected");
                    ID = $(this).attr("id");
                    console.log(ID);
                });

                $("#setReadyForClaiming").on('click',function(){
                    $("#readyforclaiming").modal('show');
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
                
                $("#disapproves").on('click',function(){
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/setdisapprove.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            id: ID,
                            conforme: 'Disapproved'
                        },
                        success: function(e){
                            
                            $('.modald').fadeOut('fast');
                            if(e == "success"){
                                location.reload();
                            }
                        }
                    });
                });

                $("#claimedjoborder").on('click',function(){
                    $("#claimjobordermodal").modal('show');
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

                $(document).on('click','#save_donedate',function(){
                    $("#save_donedate").html('<i class="fa fa-check"> </i> Savin.');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/setdelivery.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            id: ID,
                            datedelivery: $("[name=datedelivery]").val()
                        },
                        success: function(e){
                            
                            if(e == "success"){
                                $("#save_donedate").html('<i class="fa fa-check"> </i> Delivery Date Saved');
                            }
                        }
                    });
                });

                // $('#approvenow').on('click',function(){
                //     $('.modald').fadeIn('fast');
                //         $.ajax({
                //         type: 'POST',
                //         url: '../ajax/approvejob.php',
                //         data: {
                //             action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                //             jobid: ID,
                //             parts: $('.span-parts').text()
                //         },
                //         success: function(e){
                //             
                //         $('.modald').fadeOut('fast');
                //             if(e == "success"){
                //               location.reload();
                //             }
                //         }
                //     });
                // });

                $("[name=datedelivery]").on('change',function(){
                    $("#save_donedate").html('<i class="fa fa-check"></i> Save Delivery Date');
                });

                $('#approvejob').on('click',function(){
                    $("[name=referencenumberfinal]").val("");
                    $("#approve-alert").modal('show');
                    $("#viewsoa").modal("hide");
                });

                $('.view').on('click',function(){
                    $('.ongoingrepairhideshow2').slideUp('fast');
                    $('.datelivery2').slideUp('fast');
                    $('#claimedjoborder').slideUp('fast');
                    $('#approvejob').slideUp('fast');
                    $('#disapprove').slideUp('fast');
                    $('#cantapprove').slideUp('fast');

                    if(ID){
                        
                    $('.modald').fadeIn('fast');
                    $("#viewsoa").modal("show");
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
                            $('.span-diagnosis').html(obj.response[0].diagnosisitem);
                            
                            console.log(obj.response[0].parts);
                            var removebr = obj.response[0].parts.split("&lt;br&gt;");
                            var tempremover  = "";

                            for (var i = 0; i < removebr.length; i++) {
                                tempremover = tempremover + removebr[i] + "<br>";
                            }; 

                            var dat = obj.response[0].date_delivery.split("-");

                            $('input[name="datedelivery"]').val(dat[0] + "-" + dat[1] + "-"+ dat[2].substring(0,2));

                            $('.span-parts').html(tempremover);
                            $('.span-tech').html(obj.response[0].technam);
                            $('.span-remarks').html(obj.response[0].remarks);

                            if(obj.response[0].repair_status == 'Ready for Claiming'){
                                $('#claimedjoborder').slideDown('fast');
                                $('.span-status').html('<small class="badge col-centered mdone">'+obj.response[0].repair_status+'</small>');
                            }

                            if(obj.response[0].repair_status == 'Done-Ready for Delivery'){
                                $('.datelivery2').slideDown('fast');
                                if(obj.response[0].done_date_delivery == "0000-00-00"){
                                    $('.setdatedelivery').html("Delivery date is not available.");
                                    $("#setReadyForClaiming").slideUp('fast');
                                } else {
                                    $("#setReadyForClaiming").slideDown('fast');
                                    $('.setdatedelivery').html(obj.response[0].done_date_delivery);
                                }
                                $('.span-status').html('<small class="badge col-centered bg-blue">'+obj.response[0].repair_status+'</small>');
                            }

                            if(obj.response[0].repair_status == 'Waiting for SOA Approval'){
                                $('.span-status').html('<small class="badge col-centered mrorange">Waiting for Customer Approval</small>');
                                $('#approvejob').slideDown('fast');
                                $('#cantapprove').slideDown('fast');
                            }

                            if(obj.response[0].repair_status == 'Claimed'){
                                $('.span-status').html('<small class="badge col-centered bg-green">'+obj.response[0].repair_status+'</small>');
                            }

                            if(obj.response[0].repair_status == 'Approved'){
                                $('.span-status').html('<small class="badge col-centered approvedme">'+obj.response[0].repair_status+'</small>');
                            }

                            if(obj.response[0].repair_status == 'Ongoing Repair'){
                                $('.span-status').html('<small class="badge col-centered bg-teal">'+obj.response[0].repair_status+'</small>');
                            }

                            $(".waitingview").css('display','none');
                            $(".approvedview").css('display','none');
                            $(".disapprovedview").css('display','none');
                            $(".cantrepairview").css('display','none');

                            // Conforme Status
                            if(obj.response[0].conforme == 'Waiting for Approval') {
                                $(".waitingview").css('display','inline');
                            }
                            if(obj.response[0].conforme == 'Approved') {
                                $(".approvedview").css('display','inline');
                            }
                            if(obj.response[0].conforme == 'Disapproved') {
                                $(".disapprovedview").css('display','inline');
                            }
                            if(obj.response[0].conforme == 'Cant Repair') {
                                $(".cantrepairview").css('display','inline');
                            }

                            $('.totalpartcost').html("<b>P </b>"+formatNumber(obj.response3[0].totalpartscost));
                            $('.servicescharge').html("<b>P </b>"+formatNumber(obj.response3[0].service_charges));
                            $('.totalcharges').html("<b>P </b>"+formatNumber(obj.response3[0].total_charges));
                            $('.lessdeposit').html("<b>P </b>"+formatNumber(obj.response3[0].less_deposit));
                            $('.lessdiscount').html("<b>P </b>"+formatNumber(obj.response3[0].less_discount));
                            $('.balance').html("<b>P </b>"+formatNumber(obj.response3[0].balance));
                            $('.span-cost').html("<b>P </b>" + formatNumber(obj.response3[0].balance));
                            
                            $('.computedby').html(obj.response3[0].computed_by);
                            $('.acceptedby').html(obj.response3[0].accepted_by);

                            

                        }
                    });
                    
                }else {
                    $("#selecrecord-modal").modal("show");
                }
                });

                 
                  
            $('#cmd').click(function () {
                var jobOrder = {
                      content: [
                        { text: 'Job Order No.' + ID, style: 'header' },
                        { text: 'Date : ' + $('#viewsoa .datehere').text(), style: 'date' },
                        {columns: [
                            {
                                width: 'auto',
                                      bold: true,
                                text: 'Customer Name: \nAddress :\nContact Number :\nEmail Address: \n Customer Type:\nIs Under Warranty:'
                            },
                            {
                                width: 'auto',
                                text: $('#viewsoa .namehere').text() + "\n" + $('#viewsoa .addresshere').text() + "\n" + $('#viewsoa .contacthere').text() + "\n" + $('#viewsoa .emailhere').text() + "\n" + $('#viewsoa .ctypehere').text() + "\n" + $('#viewsoa .isunder_warranty').text()
                            },
                            {
                                width: 'auto',
                                marginLeft: 85,
                                bold: true,
                                text: 'Branch Name: \nBranch Address :\nContact Person: \n Phone number: \n'
                            },
                            {
                                width: 'auto',
                          alignment: 'right',
                                text: $('#viewsoa .branchnamehere').text() + "\n" + $('#viewsoa .branchaddresshere').text() + "\n" + $('#viewsoa .branchcontacthere').text() + "\n" + $('#viewsoa .branchphonehere').text()
                            },
                        ]
                    },{
                        style: 'tableExample',
                        table: {
                                widths: ['*', '*', 110, '*',110,'*'],
                                body: [
                                        [ 'Item', 'Diagnosis', 'Parts', 'Technician', 'Remarks', 'Status'],
                                        [ $('#viewsoa .span-item').text(), $('#viewsoa .span-diagnosis').text(), $('#viewsoa .span-parts').text(), $('#viewsoa .span-tech').text(), { text: $('#viewsoa .span-remarks').text(), italics: true, color: 'gray' }, $('#viewsoa .span-status').text() ]
                                ]
                        }
                    },{columns: [
                            {
                                width: 'auto',
                                      bold: true,
                                text: 'Total Parts Cost: :\nTotal Charges :\nLess Deposit: \nLess Discount:\nBalance:\nSubjob Total:\nTotal Balance:\n\nComputed By:\nAccepted By :'
                            },
                            {
                                width: 'auto',
                                marginLeft: 10,
                                // text: totalpz + "\n"  + totalcz + "\n" + lessdz + "\n" +  $('#viewsoa .lessdiscount').text() + "\n" + $('#viewsoa .balance').text() + "\n" + $('#viewsoa .balancef').text() + "\n" + $('#viewsoa .balanceff').text() + "\n\n" + $('#viewsoa .computedby').text() + "\n" + $('#viewsoa .acceptedby').text()
                                text: $('#viewsoa .totalpartcost').text()  + "\n" + $('#viewsoa .totalcharges').text() + "\n" + $('#viewsoa .lessdeposit').text() + "\n" + $('#viewsoa .lessdiscount').text() + "\n" + $('#viewsoa .balance').text()+ "\n" + $('#viewsoa .balancef').text() + "\n" + $('#viewsoa .balanceff').text() + "\n\n" + $('#viewsoa .computedby').text() + "\n" + $('#viewsoa .acceptedby').text()
                            }
                        ]
                    },
                      ],info: {
    title: 'Job Order -- ' + ID,
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
                        },
                        date: {
                          fontSize: 12,
                          bold: true,
                          marginTop: -15,
                          marginBottom: 8,
                          alignment: 'right'
                        }
                      }
                    };
                pdfMake.createPdf(jobOrder).open();
                pdfMake.createPdf(jobOrder).download('JB Job Order No. ' + ID + ".pdf");
            });

         });
        </script>
<?php
    htmlFooter('dashboard');
?>