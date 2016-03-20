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
    <?php 
        $name = $_SESSION['Branchid'];

        if($_SESSION['position'] == 0 || $_SESSION['position'] == -1) {
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
                <script type="text/javascript">
                $(function(){
                    // $(".edit").css('display','none');
                    $(".add").css('display','none');
                    $(".delete").css('display','none');
                });
                </script>
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
                                                <th>Diagnosis</th>
                                                <th>Parts</th>
                                                <th>Total Cost</th>
                                                <th>Repair Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php 
                                                if(isset($_GET['daterange'])){
                                                    $bydate = split ("to", $_GET['daterange']);

                                                    if(!isset($_GET['type'])){
                                                        $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                    }else{
                                                    $type = $_GET['type'];
                                                        if($type == "waiting_for_soa_approval") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_delivery") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Done-Ready for Delivery' AND b. isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' GROUP BY a.jobid ORDER BY a.created_at DESC";
                                                        }else if($type == "ongoing_repair") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                        }else if($type == "today") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_claiming") {
                                                            $sql = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY b.created_at DESC";
                                                        }else if($type == "unclaimed") {
                                                            $sql = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY b.created_at DESC";
                                                        }else if($type == "Claimed") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Claimed' AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                        }else if($type == "approved") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Approved' AND b.isdeleted != 1 AND a.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY a.created_at DESC";
                                                        }
                                                    }
                                                }else {
                                                    if(!isset($_GET['type'])){
                                                        $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                    }else{
                                                    $type = $_GET['type'];
                                                        if($type == "waiting_for_soa_approval") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_delivery") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.cost_id = e.cost_id OR b.jobclear = 1) AND b.diagnosis = d.id AND a.jobid = b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Done-Ready for Delivery' AND b.isdeleted != 1 GROUP BY a.jobid ORDER BY a.created_at DESC";
                                                        }else if($type == "ongoing_repair") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1  ORDER BY a.created_at DESC";
                                                        }else if($type == "today") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "ready_for_claiming") {
                                                            $sql = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1  ORDER BY b.created_at DESC";
                                                        }else if($type == "unclaimed") {
                                                            $sql = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 ORDER BY b.created_at DESC";
                                                        }else if($type == "Claimed") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Claimed' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }else if($type == "approved") {
                                                            $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') +  REPLACE(e.total_charges,',','')) - (REPLACE(e.less_deposit,',','') + REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Approved' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                                                        }
                                                    }
                                                }

                                                $queryforexcel = $sql;
                                                $queryforexcel = str_replace("+", "~~", $queryforexcel);
                                                $query =$db->ReadData($sql); 

                                                 foreach ($query as $key => $value) {
                                                    ?>
                                                        <tr id="<?php echo $value['jobid']; ?>" class="clickable">
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

                                                                    $quercost2 = "SELECT sum(subcost) as total FROM subjoborder WHERE  mainjob = '".$value['jobid']."'";
                                                                    $quercost2 =$db->ReadData($quercost2);
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
                                <i class="fa fa-globe"></i> SOA ID :<span class="idhere"></span>
                                <small class="pull-right"><b>Job Order ID :</b><span class="idjobhere"></small>
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
                                <strong>Date: </strong><span class="datehere"></span><br>
                            <strong>Branch Name: </strong><span class="branchnamehere"></span><br>
                                <strong>Branch Address : </strong><span class="branchaddresshere"></span><br>
                                <strong>Contact Person: </strong><span class="branchcontacthere"></span><br>
                                <strong>Phone number: </strong><span class="branchphonehere"></span><br>
                        </div><!-- /.row -->
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Diagnosis</th>
                                        <th>Parts</th>
                                        <th>Technician</th>
                                        <th>Part Cost</th>
                                        <th>Remarks</th>
                                    </tr>                                    
                                </thead>
                                <tbody class="soaappendhere2">
                                    <tr>
                                        <td><span class="span-item"></span></td>
                                        <td><span class="span-diagnosis"></span></td>
                                        <td><span class="span-parts"></span></td>
                                        <td><span id="tec" class="span-tech"></span></td>
                                        <td><span class="span-cost"></span></td>
                                        <td><span class="span-remarks"></span></td>
                                    </tr>
<!--                                     <tr class="buttononli">
                                        <td colspan="6" style="text-align: rigth">+</td>
                                    </tr> -->

                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Total Parts Cost: </strong><span class="totalpartcost"></span><br>
                                <!-- <strong>Service Charges : </strong><span class="servicescharge"></span><br> -->
                                <strong>Total Charges: </strong><span class="totalcharges"></span><br>
                                <strong>Less Deposit: </strong><span class="lessdeposit"></span><br>
                                <strong>Less Discount: </strong><span class="lessdiscount"></span><br>
                                <strong>Initial Balance: </strong><span class="balance"></span><br>
                                <strong>Subjob Total: </strong><span class="balancef"></span><br>
                                <strong>Total Balance: </strong><span class="balanceff"></span><br>
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-3 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-5 invoice-col">
                            <strong>Computed By: </strong><span class="computedby"></span><br>
                                <strong>Accepted By : </strong><span class="acceptedby"></span><br>
                                <strong class=" pull-left ">Conforme Status: </strong class="buttonstatus">
                                <small  class="waitingview badge pull-left mrorange"> <i class="fa fa-check"> </i>  Waiting for Approval </small>
                                <small  class="approvedview badge pull-left approvedme"> <i class="fa fa-check"> </i> Approved  </small>
                                <small  class="disapprovedview badge pull-left mred"> <i class="fa fa-times"> </i> Disapproved </small>
                                <small  class="cantrepairview badge pull-left bg-grey"> <i class="fa fa-times"> </i> Cant Repair </small>

                                <div class="ongoingrepairhideshow">
                                    <button id="ongoingrepair" class=" approvedview2 btn bg-green margin">Done Repair</button>
                                    <button id="cantrepairs" class=" approvedview2 btn bg-red margin">Can't Repair</button>
                                </div>
            
                                 <div class="ongoingrepairhideshow2">
                                 <br>

                                 <form id="setdeliverydate" class="change_to_edit" name="createjob" method="post" role="form">
                                    <div class="form-group" style="position:relative;">
                                        <label>Delivery date for ready for pickup:</label>
                                        <input type="text" name="datedelivery" placeholder="Date Delivery.." class="form-control datedelivery">

                                    <br>
                                 <button type="submit" id="savejob" class="btn btn-success savesetdate "><i class="fa fa-plus"></i> Save Delivery Date </button>
                                 </form>
                                </div>
                        </div><!-- /.row -->
                </section><!-- /.content -->

                         <button id="setStartrepairing" class="btn btn-success" style="margin-left: 18px; display: nonel"><i class="fa fa-download"></i> Start Repairing </button> 
                         <button id="cmd" class="btn btn-primary" style="margin-left: 18px;"><i class="fa fa-download"></i> Generate PDF </button> 
                         <button type="button" class="btn btnmc  cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button> 
                        <!--  <button type="submit" id="savejob" class="btn btn-success pull-left "><i class="fa fa-plus"></i> OK </button> -->
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->


          <div class="modal fade" id="editsoa" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> SOA ID :<span class="idhere"></span>
                                <small class="pull-right"><b>Job Order ID :</b><span class="idjobhere"></small>
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
                                <strong>Date: </strong><span class="datehere"></span><br>
                            <strong>Branch Name: </strong><span class="branchnamehere"></span><br>
                                <strong>Branch Address : </strong><span class="branchaddresshere"></span><br>
                                <strong>Contact Person: </strong><span class="branchcontacthere"></span><br>
                                <strong>Phone number: </strong><span class="branchphonehere"></span><br>
                        </div><!-- /.row -->
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Diagnosis</th>
                                        <th>Parts</th>
                                        <th>Technician</th>
                                        <th>Part Cost</th>
                                        <th>Remarks</th>
                                    </tr>                                    
                                </thead>
                                <tbody class="soaappendhere">
                                    <tr>
                                        <td>

                                            <span class="span-item"></span>
                                        </td>
                                        <td><span class="span-diagnosis"></span></td>
                                        <td><span class="span-parts"></span></td>
                                        <td><span id="tec" class="span-tech"></span></td>
                                        <td><span class="span-cost"></span></td>
                                        <td><span class="span-remarks"></span></td>
                                    </tr>
                                    <tr class="buttononli">
                                        <td colspan="6" style="text-align: rigth">+</td>
                                    </tr>

                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Total Parts Cost: </strong><span class="t1 totalpartcost"></span><br>
                                <!-- <strong>Service Charges : </strong><span class="t2 servicescharge"></span><br> -->
                                <strong>Total Charges: </strong><input  data-id="" type="number" name="totalcharges"><br>
                                <strong>Less Deposit: </strong>P <input data-id="" type="number" name="lessdeposit"><br>
                                <strong>Less Discount: </strong>P <input data-id="" type="number" name="lessdiscount"><br>
                                <strong>Balance: </strong><span class="t3 balance"></span><br>
                                <!-- <strong>Subjob Total: </strong><span class="balancef"></span><br>
                                <strong>Total Balance: </strong><span class="balanceff"></span><br> -->
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-3 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-5 invoice-col">
                            <strong>Computed By: </strong><span class="computedby"></span><br>
                                <strong>Accepted By : </strong><span class="acceptedby"></span><br>
                                <strong class=" pull-left ">Conforme Status: </strong class="buttonstatus">
                                <small  class="waitingview badge pull-left mrorange"> <i class="fa fa-check"> </i>  Waiting for Approval </small>
                                <small  class="approvedview badge pull-left approvedme"> <i class="fa fa-check"> </i> Approved  </small>
                                <small  class="disapprovedview badge pull-left mred"> <i class="fa fa-times"> </i> Disapproved </small>
                                <small  class="cantrepairview badge pull-left bg-grey"> <i class="fa fa-times"> </i> Cant Repair </small>

                                <!-- <div class="ongoingrepairhideshow">
                                    <button id="ongoingrepair" class=" approvedview2 btn bg-green margin">Done Repair</button>
                                    <button id="cantrepairs" class=" approvedview2 btn bg-red margin">Can't Repair</button>
                                </div>
            
                                 <div class="ongoingrepairhideshow2">
                                 <br>
                                        <label>Delivery date for ready for pickup:</label>
                                        <input type="date" name="datedelivery" class="form-control" placeholder="Estimated Finish Date ">
                                    <button id="save_donedate" class=" approvedview2 btn bg-green margin"><i class="fa fa-check"> </i> Save Delivery Date</button>
                                </div> -->
                        </div><!-- /.row -->
                </section><!-- /.content -->
                        <button id="updatesoa" class="btn btn-primary" data-dismiss="modal" style="margin-left: 18px;"><i class="fa fa-check"></i> Notify Customer </button> 
                        <button type="button" class="btn btnmc  cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button> 
                        <!--  <button type="submit" id="savejob" class="btn btn-success pull-left "><i class="fa fa-plus"></i> OK </button> -->
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

                <div class="modal fade" id="jobrepairstatus-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Are you done repairing this item? </h4>
                            </div>
                            <div class="modal-body text-right">
                                <button type="submit" class="btn btn-dismiss btnmc"  data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>
                                <button type="submit" id="setDoneJob" class="btn btn-success cancel-delet"  data-dismiss="modal"><i class="fa fa-check"></i> Done repair </button>
                                <div class="clear"></div>
                            </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                <script src="<?php echo SITE_JS_DIR ?>pdf/jquery-1.11.0.min.js"></script>
                <script src='<?php echo SITE_JS_DIR ?>/pdfmake.min.js'></script>
                <script src='<?php echo SITE_JS_DIR ?>/vfs_fonts.js'></script>
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

        <div class="modal fade" id="viewDiagnosis" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                       <div class="form-group col-xs-6">
                            <label>Diagnosis: </label>
                             <select class="form-control" name="diagnosis">
                                    <option></option>
                                    <?php

                                        $diagnosis = "SELECT * FROM `jb_diagnosis`";
                                        $diagnosisquery = $db->ReadData($diagnosis);
                                        $diag = "";
                                        foreach ($diagnosisquery as $key => $value) {
                                            $diag = $diag . "<option value='".$value['id']."'>".$value['diagnosis']."</option>";
                                        }
                                        echo $diag;
                                    ?>
                                </select>
                            <!-- <textarea class="form-control" name="diagnosis" rows="3" placeholder="Diagnosis "></textarea> -->
                        </div>
                    <div class="clear"></div>
                     <div class="form-group col-xs-12">
                         <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         <button type="submit" id="submitdiagnosis" class="btn btn-success pull-left "><i class="fa fa-plus"></i> Submit </button>
                    </div><!-- /.modal-content --> 
                    <div class="clear"></div>
                    </div><!-- /.modal-content --> 
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- /.modal -->



                <div class="modal fade" id="editpart" tabindex="-1" role="dialog" aria-hidden="true">
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
                                 <button type="submit" id="addparttojoborder" class="btn btn-success pull-left "><i class="fa fa-plus"></i> Submit </button>
                            </div><!-- /.modal-content --> 
                            <div class="clear"></div>
                            </div><!-- /.modal-content --> 
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->

                <div class="modal fade" id="edittech-form" tabindex="-1" role="dialog" aria-hidden="true">
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



<script type="text/javascript">
$(function(){
    var emailaddress = "";
    var branchname = "";
    var ID;
    var countersubjob = 1;

    var oldtot = 0;
    var oldtot2 = 0;
    var totalpz = "";
    var servicecharz = "";
    var totalcz = "";
    var lessdz = "";
    var lessdiz = "";
    var balancez = "";
    var techname = "";
    var partsStringdummy;
    var partscount = 0;

    var parts;
    var partscount = 0;
    var partsID;
    var partslisttemp = "";
    var subitemtotal = 0;

      var datatoedit = "";
      var datatype = "";

      function makeid()
        {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for( var i=0; i < 5; i++ )
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }


      $(document).on('click',".dremovedianosis",function(){
        datatoedit =  $(this).attr("data-id");
        datatype =  $(this).attr("data-type");
        $.ajax({
                type: 'POST',   
                url: '../ajax/updatediagnosissoasub.php',
                data: {
                    action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                    dataid: datatoedit,
                    typetoedit:  "remove_diagnosis"
                },
                success: function(e){
                    
                    if(e == "success"){
                        $("tr[data-id="+datatoedit+"] .span-subdiagnosis").html("");
                    }else {
                    }
                }
            });
      });

      $("#submitdiagnosis").on('click',function(){
        if(datatype == "subitem"){
            var diagnosisid = "";
            if($('select[name="diagnosis"]').val() === ""){
                alert("Please Select Diagnosis");
            }else{
                // console.log(datatoedit);
                // console.log($('select[name="diagnosis"]').val());
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoasub.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: datatoedit,
                        selectedItem:  $('select[name="diagnosis"] :selected').text(),
                        typetoedit:  "diagnosis"
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            $("tr[data-id="+datatoedit+"] .span-subdiagnosis").html($('select[name="diagnosis"] :selected').text());
                            $('#viewDiagnosis').modal("hide");
                        }else {
                            $('#viewDiagnosis').modal("hide");
                        }
                    }
                });
            }

        }else{

            var diagnosisid = "";
            if($('select[name="diagnosis"]').val() === ""){
                alert("Please Select Diagnosis");
            }else{
                // console.log(datatoedit);
                // console.log($('select[name="diagnosis"]').val());
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: datatoedit,
                        selectedItem:  $('select[name="diagnosis"]').val(),
                        typetoedit:  "diagnosis"
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            $("#" + datatoedit + " .span-diagnosis ").html($('select[name="diagnosis"] :selected').text());
                            $('#viewDiagnosis').modal("hide");
                        }else {
                            $('#viewDiagnosis').modal("hide");
                        }
                    }
                });
            }

        }
        
      });

    $('input[name="totalcharges"]').on('keyup',function(){
        myFunction3($('.t1').text(), $(this).val(), $('input[name="lessdeposit"]').val(), $('input[name="lessdiscount"]').val());
    })
    $('input[name="lessdeposit"]').on('keyup',function(){
        myFunction3($('.t1').text(), $('input[name="totalcharges"]').val(), $(this).val(), $('input[name="lessdiscount"]').val());
    })

    $('input[name="lessdiscount"]').on('keyup',function(){
        myFunction3($('.t1').text(), $('input[name="totalcharges"]').val(), $('input[name="lessdeposit"]').val(), $(this).val());
    })

        $(document).on('focusout','textarea[name="span-remarkssub"]',function(){
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoasub.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: $(this).attr('data-id'),
                        itemvalue:  $(this).val(),
                        typetoedit:  "remarks"
                    },
                    success: function(e){
                        
                        if(e == "success"){
                        }else {
                        }
                    }
                });
        });


                $(document).on('focusout','input[name="span-item"]',function(){
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: $(this).attr('data-id'),
                        itemvalue:  $(this).val(),
                        typetoedit:  "item"
                    },
                    success: function(e){
                        
                        if(e == "success"){
                        }else {
                        }
                    }
                });
        });



         $(document).on('focusout','input[name="totalcharges"]',function(){
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: $(this).attr('data-id'),
                        itemvalue:  $(this).val(),
                        typetoedit:  "totalcharges"
                    },
                    success: function(e){
                        
                    }
                });
        });

         $(document).on('focusout','input[name="lessdeposit"]',function(){
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: $(this).attr('data-id'),
                        itemvalue:  $(this).val(),
                        typetoedit:  "lessdeposit"
                    },
                    success: function(e){
                        
                    }
                });
        });

        $(document).on('focusout','input[name="lessdiscount"]',function(){
            $.ajax({
                type: 'POST',   
                url: '../ajax/updatediagnosissoa.php',
                data: {
                    action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                    dataid: $(this).attr('data-id'),
                    itemvalue:  $(this).val(),
                    typetoedit:  "lessdiscount"
                },
                success: function(e){
                    
                }
            });
        });

            $(document).on('focusout','textarea[name="span-remarks"]',function(){
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: $(this).attr('data-id'),
                        itemvalue:  $(this).val(),
                        typetoedit:  "remarks"
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            // $("#" + datatoedit + " .span-diagnosis ").html($('select[name="diagnosis"] :selected').text());
                            // $('#viewDiagnosis').modal("hide");
                        }else {
                            // $('#viewDiagnosis').modal("hide");
                        }
                    }
                });
            });

            $('#updatesoa').click(function(){
                $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: 'default-123456',
                        partname: $('[name="span-item"]').val(),
                        partcost: $('.t1.totalpartcost').text(),
                        totalcharge: $('[name="totalcharges"]').val(),
                        lessdeposit: $('[name="lessdeposit"]').val(),
                        lessdiscount: $('[name="lessdiscount"]').val(),
                        email: $('.emailhere').text(),
                        typetoedit:  "notify"
                    },
                    success: function(e){

                        $('.modald').fadeIn('slow');
                        
                        if(e == "success"){
                            location.reload();
                        }else {
                            alert('Warning: Internal Server Error!');
                        }
                    }
                });

                return false;
            });

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
                            var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }
                    <?php } else { ?>

                        <?php $type = $_GET['type'];
                        if($type == "waiting_for_soa_approval") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ready_for_delivery") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'DoneminusReady for Delivery' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' GROUP BY a.jobid ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ongoing_repair"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "today"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ready_for_claiming"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE (b.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 AND b.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY b.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "unclaimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE (b.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 AND b.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY b.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "Claimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Claimed' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "approved"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Approved' AND b.isdeleted != 1 AND a.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } ?>

                    <?php } ?>

                    query = query.replace(/plus/g,"~~");
                    query = query.replace(/minus/g,"--");
                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=soa&&filename=soaexcel";
                    window.location = page;// you can use window.open also

                <?php } else { ?>
                    var filter = $('#example1_filter label input').val();

                    <?php if(!isset($_GET['type'])) { ?>
                        if ( filter.length ) {
                            var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }
                    <?php } else { ?>

                        <?php $type = $_GET['type'];
                        if($type == "waiting_for_soa_approval") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ready_for_delivery") { ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'DoneminusReady for Delivery' AND b.isdeleted != 1 GROUP BY a.jobid ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ongoing_repair"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Ongoing Repair' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "today"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status <> 'Waiting for SOA Approval' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "ready_for_claiming"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE (b.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 ORDER BY b.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "unclaimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT b.*, d.diagnosis as diagnosisitem, c.name FROM jb_joborder b , jb_customer c, jb_diagnosis d WHERE (b.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  b.diagnosis = d.id AND b.customerid = c.customerid  AND b.repair_status = 'Ready for Claiming' AND b.isdeleted != 1 ORDER BY b.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "Claimed"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Claimed' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php }else if($type == "approved"){ ?>
                            if ( filter.length ) {
                                var query = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.*, d.diagnosis as diagnosisitem, ((REPLACE(e.totalpartscost,',','') plus  REPLACE(e.total_charges,',','')) minus (REPLACE(e.less_deposit,',','') plus REPLACE(e.less_discount,',',''))) as totalcost FROM jb_soa a, jb_joborder b , jb_customer c, jb_diagnosis d, jb_cost e WHERE (a.jobid LIKE '%"+filter+"%' OR c.name LIKE '%"+filter+"%' OR d.diagnosis LIKE '%"+filter+"%' OR b.item LIKE '%"+filter+"%' OR b.repair_status LIKE '%"+filter+"%') AND  a.cost_id = e.cost_id AND b.diagnosis = d.id AND a.jobid =  b.jobid AND b.customerid = c.customerid  AND b.repair_status = 'Approved' AND b.isdeleted != 1 ORDER BY a.created_at DESC";
                            } else {
                                var query = "<?php echo $queryforexcel; ?>";
                            }
                        <?php } ?>

                    <?php } ?>

                    query = query.replace(/plus/g,"~~");
                    query = query.replace(/minus/g,"--");
                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=soa&&filename=soaexcel";
                    window.location = page;// you can use window.open also

                <?php } ?>

            });

            $(document).on('click','.buttononli',function(){
                var idgenereated = makeid();
                $.ajax({
                    type: 'POST',
                    url: '../ajax/createsubjob.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        jobid: ID,
                        subjobid: ID+"-"+countersubjob+""+idgenereated
                    },
                    success: function(e){
                        if(e == "success"){
                            $('.soaappendhere').append("<tr data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" class=\"zz-"+ID+"-"+countersubjob+""+idgenereated+"\"><td><span></span></td><td><span class=\"span-subdiagnosis\" ></span><small id=\"editdiagnosis\" data-type=\"subitem\" data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"> </i></small><small  data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" class=\"badge bg-grey dremovedianosis\"> <i class=\"fa fa-times\"> </i></small></td><td><span class=\"span-subparts\"></span><small data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" data-type=\"sub\" id=\"editpartbtnsub\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span id=\"tec\" class=\"span-subtech\"></span><small id=\"edittech\" data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" data-type=\"subitem\"  class=\"badge bg-green\"> <i class=\"fa fa-edit\"></i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span class=\"span-subcost\"></span></td><td><span class=\"span-subremarks\"><textarea  name=\"span-remarkssub\" data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" data-type=\"subitem\" rows=\"3\"></textarea></span></td></tr><tr class=\""+ID+"-"+countersubjob+""+idgenereated+"\"><td  class=\"buttononli2\" data-id=\""+ID+"-"+countersubjob+""+idgenereated+"\" colspan=\"6\" style=\"text-align: rigth\">-</td></tr>");
                            // $('.soaappendhere').append("<tr class=\"zz-"+ID+"-"+countersubjob+"\"><td><span></span></td><td><span class=\"span-subdiagnosis\"></span><small id=\"editdiagnosis\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span class=\"span-subparts\"></span><small id=\"editpartbtn\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span id=\"tec\" class=\"span-subtech\"></span><small id=\"edittech\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"></i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span class=\"span-subcost\"></span></td><td><span class=\"span-subremarks\"><textarea name=\"span-remarkssub\" rows=\"3\"></textarea></span></td></tr><tr class=\""+ID+"-"+countersubjob+"\"><td  class=\"buttononli2\" data-id=\""+ID+"-"+countersubjob+"\" colspan=\"6\" style=\"text-align: rigth\">-</td></tr>");
                            // $('.soaappendhere').append("<tr id=\""+ID+"-"+countersubjob+"\"><td><span class=\"span-item\"></span></td><td><span class=\"span-diagnosis\"><small id=\"adddiagnosisd\" class=\"badge bg-green\"> <i class=\"fa fa-plus\"> </i></small></span></td><td><span class=\"span-parts\"><small id=\"adddiagnosis\" class=\"badge bg-green\"> <i class=\"fa fa-plus\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></span></td><td><span id=\"tec\" class=\"span-tech\"><small id=\"addpartsbtn\" class=\"badge bg-green\"> <i class=\"fa fa-plus\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></span></td><td><span class=\"span-cost\"><small id=\"addtechbtn\" class=\"badge bg-green\"> <i class=\"fa fa-plus\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></span></td><td><span class=\"span-remarks\"><textarea name=\"remarks\"></textarea></span></td></tr><tr class=\""+ID+"-"+countersubjob+"\"><td  class=\"buttononli2\" data-id=\""+ID+"-"+countersubjob+"\" colspan=\"6\" style=\"text-align: rigth\">-</td></tr>");
                            countersubjob++;
                        }
                    }
                });
            });

            $(document).on('click','.buttononli2',function(){
                var idselectedtodelete = $(this).attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletesubjob.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        subjobid: idselectedtodelete
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            $(".zz-"+ idselectedtodelete).remove();
                            $("."+ idselectedtodelete).remove();
                        }
                    }
                });

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
                                    window.location.assign("" + "<?php echo SITE_URL;?>head_office/soa.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                    <?php 
                                }else{
                                    ?>
                                    window.location.assign("" + "<?php echo SITE_URL;?>head_office/soa.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );

                                    <?php 
                                }
                                ?>
                                <?php
                            }
                        ?>
                       
                }
            );


    $(document).on('click', ".clickable", function() {
        $(".clickable").removeClass("selected");
        $(this).addClass("selected");
        ID = $(this).attr("id");
        console.log(ID);
    });

    $("#cantrepairs").on('click', function(){
        $("#addremark").modal('show');
    });
 
    $("#setStartrepairing").on('click',function(){
        $('.modald').fadeIn('fast');
        $.ajax({
            type: 'POST',
            url: '../ajax/setongoing.php',
            data: {
                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                jobid: ID
            },
            success: function(e){
                
                if(e == "success"){
                    location.reload();
                }
            }
        });
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
            $(".savesetdate").html('<i class="fa fa-plus"></i> Saving..');
            $.ajax({
                type: 'POST',
                url: '../ajax/setdatedone.php',
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
                        $(".savesetdate").html('<i class="fa fa-plus"></i> Delivery Date Saved');
                    } else {
                        $('.modald').fadeOut('slow');
                        if(obj.status == 101) {
                            if( $.type(obj.date_delivery) != 'undefined' && obj.date_delivery == true ) {
                                $('input[name="datedelivery"]').parent().find('p.error').remove();
                                $('input[name="datedelivery"]').parent().append('<p for="datedelivery" generated="true" class="error">Date is already set.</p>');
                            }

                            $(".savesetdate").html('<i class="fa fa-plus"></i>  Save Delivery Date');
                        }
                    }

                }
            });
            return false;
        }
    });

    $('#setDoneJob').on('click',function(){
        $('.modald').fadeIn('fast');
        $.ajax({
            type: 'POST',
            url: '../ajax/setdonerepair.php',
            data: {
                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                jobid: ID,
                techid: $('.span-tech').attr('id'),
                email: emailaddress,
                branch: branchname
            },
            success: function(e){
                
                $('.modald').fadeOut('fast');
                if(e == "success"){
                    location.reload();
                }
            }
        });
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
                    type: 'ongoing',
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

    $("[name=datedelivery]").on('change',function(){
        $("#save_donedate").html('<i class="fa fa-check"></i> Save Delivery Date');
    });

    $("#ongoingrepair").on('click',function(){
        $("#viewsoa").modal("hide");
        $("#jobrepairstatus-modal").modal('show');
    });

    $('.add').on('click',function(){
        $('#create-branch').modal('show');
    });

     $('.delete').on('click',function(){
        if(ID) {
            $("#delete-modal").modal('show');
            $("#idhere2").html(ID);
        }else {
            $("#selecrecord-modal").modal("show");
        }
    }); 

    function formatNumber (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

     function myFunction(a, b, c, d, e) {
        a = parseFloat(a);
        b = parseFloat(b);
        c = parseFloat(c);
        d = parseFloat(d);
        e = parseFloat(e);
        $('.balance').html((a + b + c)-(d + e));
    }

         function myFunction2(a, b, c, d, e) {


            if(a === ""){
                a = 0;
            }
            if(b === ""){
                b = 0;
            }
            if(c === ""){
                c = 0;
            }
            if(d === ""){
                d = 0;
            }
            if(e === ""){
                e = 0;
            }
        a = parseFloat(a);
        b = parseFloat(b);
        c = parseFloat(c);
        d = parseFloat(d);
        e = parseFloat(e);
        // alert(a + " -=- " + b + " -=- " +c + " -=- " +d + " -=- " + e);
        $('.balance').html((a + b + c)-(d + e));
    }

    function myFunction3(a, c, d, e) {

        console.log(a + " " + c + " " + d + " " + e);

        if(a === ""){
            a = 0;
        }
        if(c === ""){
            c = 0;
        }
        if(d === ""){
            d = 0;
        }
        if(e === ""){
            e = 0;
        }
        a = a.replace(",","");
        c = c.replace(",","");
        d = d.replace(",","");
        e = e.replace(",","");

        a = parseFloat(a);
        c = parseFloat(c);
        d = parseFloat(d);
        e = parseFloat(e);

        var total  = (a + c) - (d + e);
        $('.balance').html(total);
    }

    function myFunction4(a, c, d, e) {

        console.log(a + " " + c + " " + d + " " + e);

        if(a === ""){
            a = 0;
        }
        if(c === ""){
            c = 0;
        }
        if(d === ""){
            d = 0;
        }
        if(e === ""){
            e = 0;
        }
        a = a.replace(",","");
        c = c.replace(",","");
        d = d.replace(",","");
        e = e.replace(",","");

        a = parseFloat(a);
        c = parseFloat(c);
        d = parseFloat(d);
        e = parseFloat(e);

        var total  = (a + c) - (d + e);
        $('.balances').html(total);
    }



    $('.totalpartcost').bind("DOMSubtreeModified",function(){
        var a = $('.totalpartcost').text() , b = $('.servicescharge').text(), c = $('.totalcharges').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
        myFunction(a, b, c, d, e);
    });

    $('.servicescharge').bind("DOMSubtreeModified",function(){
     var a = $('.totalpartcost').text() , b = $('.servicescharge').text(), c = $('.totalcharges').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
    myFunction(a, b, c, d, e);
    });

    $('.totalcharges').bind("DOMSubtreeModified",function(){
    var a = $('.totalpartcost').text() , b = $('.servicescharge').text(), c = $('.totalcharges').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
    myFunction(a, b, c, d, e);
    });

    $('.lessdeposit').bind("DOMSubtreeModified",function(){
        var a = $('.totalpartcost').text() , b = $('.servicescharge').text(), c = $('.totalcharges').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
        myFunction(a, b, c, d, e);
    });

    $('.lessdiscount').bind("DOMSubtreeModified",function(){
        var a = $('.totalpartcost').text() , b = $('.servicescharge').text(), c = $('.totalcharges').text(), d = $('.lessdeposit').text(), e = $('.lessdiscount').text();
        myFunction(a, b, c, d, e);
    });

     $('.view').on('click',function(){
        subitemtotal = 0;
    if(ID){
        $('.modald').fadeIn('fast');
        $("#viewsoa").modal("show");
        $('.ongoingrepairhideshow').slideUp('fast');
        $('.ongoingrepairhideshow2').slideUp('fast');
    $("#idhere").html(ID);
    $('.soaappendhere2').html("<tr><td><span class=\"span-item\"></span></td><td><span class=\"span-diagnosis\"></span></td><td><span class=\"span-parts\"></span></td><td><span id=\"tec\" class=\"span-tech\"></span></td><td><span class=\"span-cost\"></span></td><td><span class=\"span-remarks\"></span></td></tr>");
    $.ajax({
        type: 'POST',
        url: '../ajax/viewjoborder.php',
        data: {
            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
            jobid: ID
        },
        success: function(e){
            var obj = jQuery.parseJSON(e);

            $(".waitingview").css('display','none');
            $(".approvedview").css('display','none');
            $(".disapprovedview").css('display','none');
            $(".cantrepairview").css('display','none');

            $('#setStartrepairing').slideUp('fast');
            $('.ongoingrepairhideshow').slideUp('fast');
            $('.approvedview2').slideUp('fast');

            if(obj.response[0].repair_status == "Ongoing Repair"){
                $('.ongoingrepairhideshow').slideDown('fast');
                $('.approvedview2').slideDown('fast');
            } 
            if(obj.response[0].repair_status == "Approved") {
                $('#setStartrepairing').slideDown('fast');
            }
            if(obj.response[0].repair_status == "Done-Ready for Delivery"){
                $('.ongoingrepairhideshow2').slideDown('fast');
            }

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
            
            $('.modald').fadeOut('fast');
            $('.idhere').html(obj.response2[0].soa_id);
            $('.idjobhere').html(obj.response[0].jobid);
            var now = moment(obj.response[0].dateadded);
            $('.datehere').html(now.format("MMMM D, YYYY"));
            $('.namehere').html(obj.response[0].name);
            $('.addresshere').html(obj.response[0].address);
            $('.contacthere').html(obj.response[0].number);
            $('.emailhere').html(obj.response[0].email);
            emailaddress = obj.response[0].email;

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
            branchname  = obj.response[0].branch_name;
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

            for (var i = 0; i < obj.response4.length; i++) {
                subitemtotal = subitemtotal + parseInt(obj.response4[i].subcost);
                $('.soaappendhere2').append("<tr><td><span ></span></td><td><span class=\"span-subdiagnosis\">"+obj.response4[i].subdiagnosis+"</span></td><td><span class=\"span-subparts\">"+obj.response4[i].subparts+"</span></td><td><span id=\"tec\" class=\"span-subtech\">"+obj.response4[i].subtech+"</span></td><td><span class=\"span-subcost\">"+obj.response4[i].subcost+"</span></td><td><span class=\"span-subremarks\">"+obj.response4[i].subremarks+"</span></td>");
                countersubjob++;
            };

            $('.span-parts').html(tempremover);
            $('.span-tech').html(obj.response[0].technam);
            $('.span-tech').attr('id', obj.response[0].technicianid);
            $('.span-remarks').html(obj.response[0].remarks);
            $('.span-status').html('<small class="badge col-centered bg-yellow">'+obj.response[0].repair_status+'</small>');
            
            $('.totalpartcost').html("<b>P </b>"+formatNumber(obj.response3[0].totalpartscost));
            $('.servicescharge').html("<b>P </b>"+formatNumber(obj.response3[0].service_charges));
            $('.totalcharges').html("<b>P </b>"+formatNumber(obj.response3[0].total_charges));
            $('.lessdeposit').html("<b>P </b>"+formatNumber(obj.response3[0].less_deposit));
            $('.lessdiscount').html("<b>P </b>"+formatNumber(obj.response3[0].less_discount));
            $('.balance').html("<b>P </b>"+formatNumber(obj.response3[0].balance));

            totalpz = "P "+formatNumber(obj.response3[0].totalpartscost);
            servicecharz = "P "+formatNumber(obj.response3[0].service_charges);
            totalcz = "P "+formatNumber(obj.response3[0].total_charges);
            lessdz = "P "+formatNumber(obj.response3[0].less_deposit);
            balancez = "P "+formatNumber(obj.response3[0].balance);

            $('.span-cost').html("<b>P </b>" + formatNumber(obj.response3[0].totalpartscost));
            $('.computedby').html(obj.response3[0].computed_by);
            $('.acceptedby').html(obj.response3[0].accepted_by);
            var dat = obj.response[0].done_date_delivery.split("-");

            $('input[name="datedelivery"]').val(dat[0] + "-" + dat[1] + "-"+ dat[2].substring(0,2));

            myFunction3(obj.response3[0].totalpartscost, obj.response3[0].total_charges, obj.response3[0].less_deposit, obj.response3[0].less_discount);
            $('.balancef').html(subitemtotal);
            $('.balanceff').html(parseFloat($('.t3').text()) + subitemtotal);
            
            console.log(obj.response3[0].total_charges);
        }
    });
    
}else {
    $("#selecrecord-modal").modal("show");
}
});

    $(document).on('click','#editdiagnosis',function(){
        $('#viewDiagnosis').modal('show');
        datatoedit =  $(this).attr("data-id");
        datatype =  $(this).attr("data-type");
    });

$(document).on('click','#editpartbtnsub',function(){
    datatoedit =  $(this).attr("data-id");
    datatype =  $(this).attr("data-type");

    $('.listofparts-beforeadded').html("");

     // $("tr[data-id='+datatoedit+']" + ".span-subparts').text().replace(")",")&lt;br&gt;");

    var tempdatapop = $("tr[data-id="+datatoedit+"] .span-subparts").text().replace(")",")&lt;br&gt;");
    var looopparts =  $("tr[data-id="+datatoedit+"] .span-subparts").text().split(')');

    var partloophandler = "";
    for (var i = 0; i < looopparts.length - 1; i++) {
        partloophandler = partloophandler + looopparts[i] + ")&lt;br&gt;";
    };

    if(partloophandler != ""){
        console.log(partsStringdummy);
        $('.listofparts-beforeadded').slideDown('fast');
        $('.partquantity ').slideDown('fast');
    }

    $("#editpart").modal("show");

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
                total = total + parseInt((calcumul[0] * calcumul[1]));
        };
    });
    oldtot = parseInt($('#editsoa .balanceff').text()) - parseInt(total);
    oldtot2 = parseInt($('#editsoa .balancef').text()) - parseInt(total);
    $('.totalpartcost').html(total);
    });

    $(document).on('click','#editpartbtn',function(){
    datatoedit =  $(this).attr("data-id");
        datatype =  $(this).attr("data-type");
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
    $("#editpart").modal("show");

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
                total = total + parseInt((calcumul[0] * calcumul[1]));
        };
    });
    });


    $(document).on('click','#edittech',function(){
        $('#edittech-form').modal('show');
        datatoedit =  $(this).attr("data-id");
        datatype =  $(this).attr("data-type");
    });

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
    $('.span-cost').html(total);
}); 


$('#addparttojoborder').on('click',function(){

    if(datatype == "sub"){
    $('.partcost').html($('.totalpartcost').text());
    $('.billingstatment').slideDown('fast');
    $("tr[data-id='+datatoedit+']" + ".span-subparts").html("");
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
        };

         $("tr[data-id="+datatoedit+"] .span-subparts").html(tempparts.substr(0, tempparts.length - 1));
        var temppartst = "# ";
        var splitter =  parts.split(")");
        for (var i = 0; i < splitter.length -1; i++) {
            temppartst = temppartst + splitter[i] + ")<br>#";
        };

        // var co2 = parts.replace(")", ")<br>");
        // alert(datatoedit+ " -- " + temppartst.substr(0, temppartst.length -1) + "--" + temppartst);

        $("tr[data-id="+datatoedit+"] .span-subparts").html(temppartst.substr(0, temppartst.length -1));
        $('#editpart').modal('hide');

        var partsid = $("tr[data-id="+datatoedit+"] .span-subparts").text().split(')');
        var partsIDD = "";
        
        for (var i = partsid.length - 1; i >= 0; i--) {
            var partspliter = partsid[i].split('-');
            console.log("test" + partspliter[0]);
            partsIDD = partsIDD + partspliter[0]+ ",";
        };

        var a = $('.t1').text(), b = $('address .servicescharge').text(), c = $('input[name="totalcharges"]').val(), d = $('input[name="lessdeposit"]').val(), e = $('input[name="lessdiscount"]').val();
        myFunction4(a, c, d, e);
        var priced =  $("tr[data-id="+datatoedit+"] .span-subcost").text().replace("P", "");
        var priced2 =  $(".totalpartcost").text().replace("P", "");
        // alert(datatoedit +"---" + priced + "------" + $("tr[data-id="+datatoedit+"] .span-subparts").text());

        $.ajax({
            type: 'POST',   
            url: '../ajax/updatediagnosissoasub.php',
            data: {
                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                dataid: datatoedit,
                partprice: priced,
                typetoedit:  "updateparts",
                parts:  $("tr[data-id="+datatoedit+"] .span-subparts").text()
            },
            success: function(e){
                
                if(e == "success"){
                }else {

                }
            }
        });

    }else{
        alert("Please select part.");
    }
    $('.balanceff').html(oldtot + parseInt(priced));
    $('.balancef').html(oldtot2 + parseInt(priced));
    // $("#search_part").val("");
    // $('[name=partsquantity]').val("");
    // $('.partprices').html("");
    // // $('.totalpartcost').html("");
    // $('.partquantity ').slideUp('fast');

    // siparator
    }else{

    $('.partcost').html($('.totalpartcost').text());
    $('.billingstatment').slideDown('fast');
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
        };

        $('.span-parts').html(tempparts.substr(0, tempparts.length - 1));
        var temppartst = "# ";
        var splitter =  parts.split(")");
        for (var i = 0; i < splitter.length -1; i++) {
            temppartst = temppartst + splitter[i] + ")<br>#";
        };
        // var co2 = parts.replace(")", ")<br>");
        $('.span-parts').html(temppartst.substr(0, temppartst.length -1));
        $('#editpart').modal('hide');

        var partsid = $('.partlistplea').text().split(')');
        var partsIDD = "";
        for (var i = partsid.length - 1; i >= 0; i--) {
            var partspliter = partsid[i].split('-');
            console.log("test" + partspliter[0]);
            partsIDD = partsIDD + partspliter[0]+ ",";
        };

        var a = $('.t1').text(), b = $('address .servicescharge').text(), c = $('input[name="totalcharges"]').val(), d = $('input[name="lessdeposit"]').val(), e = $('input[name="lessdiscount"]').val();
        myFunction3(a, c, d, e);
        var priced =  $('.soaappendhere .span-cost').text().replace("P", "");
        $.ajax({
            type: 'POST',   
            url: '../ajax/updatediagnosissoa.php',
            data: {
                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                dataid: datatoedit,
                partprice: priced,
                total_charges: $('input[name="totalcharges"]').val(),
                items:  $('.partlistplea').text(),
                product_id:  partsIDD,
                balance:  $('.t3').text(),
                typetoedit:  "updateparts"
            },
            success: function(e){
                
                if(e == "success"){
                }else {
                }
            }
        });


    }else{
        alert("Please select part.");
    }

    $("#search_part").val("");
    $('[name=partsquantity]').val("");
    $('.partprices').html("");
    // $('.totalpartcost').html("");
    $('.partquantity ').slideUp('fast');
    }
});

    $('#assignedtech').on('click',function(){
        if(datatype == "subitem"){
            if(techname){
            $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoasub.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: datatoedit,
                        itemvalue:  techID,
                        itemvalue2:  techname,
                        typetoedit:  "tech"
                    },
                    success: function(e){
                        if(e == "success"){
                            $("tr[data-id="+datatoedit+"] .span-subtech").html(techname);
                            $('#edittech-form').modal('hide');
                        }else {
                            $('#edittech-form').modal('hide');
                        }
                    }
                });
        }else{
            alert("Please select Technician");
        }
       $('#search_tech').val("");
        }else{
            if(techname){
            $.ajax({
                    type: 'POST',   
                    url: '../ajax/updatediagnosissoa.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        dataid: datatoedit,
                        itemvalue:  techID,
                        typetoedit:  "tech",
                        jobid: ID
                    },
                    success: function(e){
                        
                        if(e == "success"){
                            // alert(datatoedit);
                            $('#' + datatoedit + ' .span-tech').html(techname);
                            $('#edittech-form').modal('hide');
                        }else {
                            $('#edittech-form').modal('hide');
                        }
                    }
                });
        }else{
            alert("Please select Technician");
        }
       $('#search_tech').val("");
        }
        
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
        // $('.search-list').fadeOut('fast');
        var m = $(this).val();
        var re  = m.toString().split('~');
         $("#search_tech").val(re[0]);
         techname = re[0];
         techID = re[1];
         $('.search-list-result-tech').slideUp('fast');
    });
                
    $("#search_part").keyup(function(){
        var toSearch = $("#search_part").val();
        $('.search-list-part').html("");
        $('.search-list-result-part').slideDown('fast');
        $.ajax({
            type: 'POST',
            url: '../ajax/search_part.php',
            data: {
                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                toSearch: toSearch
            },
            success: function(e){
                    // 
                    if(e != 'error'){
                        var obj = jQuery.parseJSON(e);
                        var data = "";
                        for (var i = 0; i < obj.response.length; i++) {
                            $('.search-list-part').append("<option value='"+obj.response[i].name+"~"+obj.response[i].part_id+"~"+obj.response[i].cost+"'>" +obj.response[i].name+"</option>");
                        };
                    }
            }
        });
    });

                $('.search-list-part').change(function(){
                    if(datatype == "sub"){
                    $("#search_part").value =$(this).val();
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
                                            total = total + parseInt((calcumul[0] * calcumul[1]));
                                    };
                                    $('#editpart .totalpartcost').html(total);
                                    $('.span-subcost').html(total);
                                });
                            }
                        });
                    }else{
                        $("#search_part").value =$(this).val();
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
                                            total = total + parseInt((calcumul[0] * calcumul[1]));
                                    };
                                    $('.totalpartcost').html(total);
                                    $('.span-cost').html(total);

                                });
                            }
                        });
                    }
                    });

    $('.edit').on('click',function(){
        
        subitemtotal = 0;
    if(ID){
    $('.modald').fadeIn('fast');
    $("#editsoa").modal("show");
    $('.ongoingrepairhideshow').slideUp('fast');
    $('.ongoingrepairhideshow2').slideUp('fast');
    $("#idhere").html(ID);

    $('.soaappendhere').html("<tr id=\"default-"+ID+"\"><td><input data-id=\"default-"+ID+"\" tpye=\"text\" name=\"span-item\"></td><td><span class=\"span-diagnosis\"></span><small id=\"editdiagnosis\" data-id=\"default-"+ID+"\"  class=\"badge bg-blue\"> <i class=\"fa fa-edit\"> </i></small></td><td><span class=\"span-parts partlistplea\"></span><small data-type=\"main\" data-id=\"default-"+ID+"\" id=\"editpartbtn\" class=\"badge  bg-blue\"> <i class=\"fa fa-edit\"> </i></small></td><td><span id=\"tec\" class=\"span-tech\"></span><small id=\"edittech\" data-id=\"default-"+ID+"\" class=\"badge bg-blue\"> <i class=\"fa fa-edit\"> </i></small></td><td><span class=\"span-cost\"></span></td><td><textarea name=\"span-remarks\" data-id=\"default-"+ID+"\"  rows=\"3\"></textarea></td></tr><tr class=\"buttononli\"><td colspan=\"6\" style=\"text-align: rigth\">+</td></tr>");
    $.ajax({
        type: 'POST',
        url: '../ajax/viewjoborder.php',
        data: {
            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
            jobid: ID
        },
        success: function(e){
            var obj = jQuery.parseJSON(e);

            $(".waitingview").css('display','none');
            $(".approvedview").css('display','none');
            $(".disapprovedview").css('display','none');
            $(".cantrepairview").css('display','none');

            $(".waitingview").css('display','none');
            $(".ongoindview").css('display','none');
            $(".approvedview").css('display','none');
            $(".donepickupview").css('display', 'none');
            $(".doneview").css('display','none');
            $(".claimedview").css('display','none');
            $(".unclaimedview ").css('display','none');

            if(obj.response[0].repair_status == "Ongoing Repair"){
                $('.ongoingrepairhideshow').slideDown('fast');
                $('.approvedview2').slideDown('fast');
            } 
            if(obj.response[0].repair_status == "Approved") {
                $('#setStartrepairing').slideDown('fast');
            }
            if(obj.response[0].repair_status == "Done-Ready for Delivery"){
                $('.ongoingrepairhideshow2').slideDown('fast');
                $(".donepickupview").css('display', 'inline');
            }

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

            $('.modald').fadeOut('fast');
            $('.idhere').html(obj.response2[0].soa_id);
            $('.idjobhere').html(obj.response[0].jobid);
            var now = moment(obj.response[0].dateadded);
            $('.datehere').html(now.format("MMMM D, YYYY"));
            $('.namehere').html(obj.response[0].name);
            $('.addresshere').html(obj.response[0].address);
            $('.contacthere').html(obj.response[0].number);
            $('.emailhere').html(obj.response[0].email);
            emailaddress = obj.response[0].email;

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
            branchname  = obj.response[0].branch_name;
            $('.branchaddresshere').html(obj.response[0].branch_address);
            $('.branchcontacthere').html(obj.response[0].contact_person);
            $('.branchphonehere').html(obj.response[0].branch_number);

            // $('.span-item').html(obj.response[0].item);
            $('input[name="span-item"]').val(obj.response[0].item);

            $('.span-diagnosis').html(obj.response[0].diagnosisitem);

            console.log(obj.response[0].parts);
            var removebr = obj.response[0].parts.split("&lt;br&gt;");
            var tempremover  = "";

            for (var i = 0; i < removebr.length; i++) {
                tempremover = tempremover + removebr[i] + "<br>";
            };

            for (var i = 0; i < obj.response4.length; i++) {
                subitemtotal = subitemtotal + parseInt(obj.response4[i].subcost);
                $('.soaappendhere').append("<tr data-id=\""+obj.response4[i].subjobid+"\" class=\"zz-"+obj.response4[i].subjobid+"\"><td><span></span></td><td><span class=\"span-subdiagnosis\" >"+obj.response4[i].subdiagnosis+"</span><small id=\"editdiagnosis\" data-type=\"subitem\" data-id=\""+obj.response4[i].subjobid+"\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"> </i></small><small data-id=\""+obj.response4[i].subjobid+"\" class=\"badge bg-grey dremovedianosis\"> <i class=\"fa fa-times\"> </i></small></td><td><span class=\"span-subparts\">"+obj.response4[i].subparts+"</span><small  data-type=\"sub\" data-id=\""+obj.response4[i].subjobid+"\" id=\"editpartbtnsub\" class=\"badge bg-green\"> <i class=\"fa fa-edit\"> </i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span id=\"tec\" class=\"span-subtech\">"+obj.response4[i].subtech+"</span><small id=\"edittech\" data-id=\""+obj.response4[i].subjobid+"\" data-type=\"subitem\"  class=\"badge bg-green\"> <i class=\"fa fa-edit\"></i></small><small id=\"adddiagnosis\" class=\"badge bg-grey\"> <i class=\"fa fa-times\"> </i></small></td><td><span class=\"span-subcost\">"+obj.response4[i].subcost+"</span></td><td><span class=\"span-subremarks\"><textarea name=\"span-remarkssub\" data-id=\""+obj.response4[i].subjobid+"\" data-type=\"subitem\" rows=\"3\">"+obj.response4[i].subremarks+"</textarea></span></td></tr><tr class=\""+obj.response4[i].subjobid+"\"><td  class=\"buttononli2\" data-id=\""+obj.response4[i].subjobid+"\" colspan=\"6\" style=\"text-align: rigth\">-</td></tr>");
                countersubjob++;
            }
            
            $('.span-parts').html(tempremover);
            $('.span-tech').html(obj.response[0].technam);
            $('.span-tech').attr('id', obj.response[0].technicianid);
            // $('.span-remarks').html(obj.response[0].remarks);
            $('textarea[name="span-remarks"]').val(obj.response[0].remarks);
            $('.span-status').html('<small class="badge col-centered bg-yellow">'+obj.response[0].repair_status+'</small>');
            
            $('.totalpartcost').html(obj.response3[0].totalpartscost);
            $('.servicescharge').html(obj.response3[0].service_charges);

            $('input[name="totalcharges"]').attr("data-id","default-" + ID);
            $('input[name="lessdeposit"]').attr("data-id","default-" + ID);
            $('input[name="lessdiscount"]').attr("data-id","default-" + ID);

            $('input[name="totalcharges"]').val(obj.response3[0].total_charges);

            $('input[name="lessdeposit"]').val(obj.response3[0].less_deposit);
            $('input[name="lessdiscount"]').val(obj.response3[0].less_discount);

            myFunction3(obj.response3[0].totalpartscost, obj.response3[0].total_charges, obj.response3[0].less_deposit, obj.response3[0].less_discount);
            // $('.balance').html(obj.response3[0].balance);
            $('.span-cost').html("<b>P </b>" + formatNumber(obj.response3[0].totalpartscost));
            $('.computedby').html(obj.response3[0].computed_by);
            $('.acceptedby').html(obj.response3[0].accepted_by);

            var dat = obj.response[0].done_date_delivery.split("-");
            $('input[name="datedelivery"]').val(dat[0] + "-" + dat[1] + "-"+ dat[2].substring(0,2));

            $('.balancef').html(subitemtotal);
            $('.balanceff').html(parseInt($('.t3').text()) + subitemtotal);

        }
    });
    
}else {
    $("#selecrecord-modal").modal("show");
}
    });

            $('#cmd').click(function () {

                var jobOrder = {
                      content: [
                        { text: 'SOA No.' + ID, style: 'header' },
                        { text: 'Date : ' + $('#viewsoa .datehere').text(), style: 'date' },
                        {columns: [
                            {
                                width: 'auto',
                                      bold: true,
                                text: 'Customer Name: \nAddress :\nContact Number :\nEmail Address: \n Customer Type:\n Is Under Warranty:'
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
                                text: totalpz + "\n"  + totalcz + "\n" + lessdz + "\n" +  $('#viewsoa .lessdiscount').text() + "\n" + $('#viewsoa .balance').text() + "\n" + $('#viewsoa .balancef').text() + "\n" + $('#viewsoa .balanceff').text() + "\n\n" + $('#viewsoa .computedby').text() + "\n" + $('#viewsoa .acceptedby').text()
                            }
                        ]
                    },
                      ],info: {
                    title: 'SOA NO -- ' + ID,
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
                pdfMake.createPdf(jobOrder).download('SOA No. ' + ID + ".pdf");
            });
});
</script>
<?php
    htmlFooter('dashboard');
?>