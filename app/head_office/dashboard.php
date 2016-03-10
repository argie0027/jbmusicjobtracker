<?php
    include '../../include.php';
    include '../ui_main.php';

    htmlHeader('dashboard');
    global $url;
    
    $notif = split(',', NOTIF);
    // echo $notif[3];
?>
<!-- header logo: style can be found in header.less -->
       <?php 
        $name = $_SESSION['Branchid'];

        if($_SESSION['position'] == 0 || $_SESSION['position'] == -1) {
            $name = "JB Main Office";    
        }else {

        $sql = "SELECT branch_name FROM jb_branch WHERE branch_id = '" .$name. "'";
        $query = $db->ReadData($sql);
        $name =  $query[0]['branch_name'];
            // $name = $query['branch_name'];
        }
           $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
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
                    <?php 
                        $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.isdeleted = '0'  ORDER BY created_at DESC";
                        $query = $db->ReadData($qu);  
                        $_SESSION['jobcount'] = $db->GetNumberOfRows();  
                        sidebarMenu($_SESSION['jobcount']); ?>
                    <?php
                        // $user = new user();
                        // echo $user->get_current_user("mac");
                    ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                
                <?php breadcrumps('Dashboard'); ?>
                <!-- Main content -->
                <section class="content">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                            echo $_SESSION['jobcount'];
                                        ?>
                                    </h3>
                                    <p>
                                        Total Job Order 
                                        
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Total-Job-Order.png">
                                </div>
                                <a href="joborders.php?type=all" class="small-box-footer mgray">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- ./col --><div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                            $qu2 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE  (a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id)   AND a.isdeleted = '0'  AND a.date_delivery = '".date("y-m-d")."' ORDER BY created_at DESC";

                                            $query = $db->ReadData($qu2);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?> 
                                    </h3>
                                    <p>
                                        Job Order Arriving Today
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Job-Order-Arriving-Today.png">
                                </div>
                                <a href="joborders.php?type=today" class="small-box-footer mlightred">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->

                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                             $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1  AND a.repair_status = 'Waiting List'   AND a.isdeleted = '0'  ORDER BY created_at DESC";
                                              $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                        <sup style="font-size: 20px"></sup>
                                    </h3>
                                    <p>
                                        Waiting List
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/waiting.png">
                                </div>
                                <a href="joborders.php?type=waiting_list" class="small-box-footer morange">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>



                        
                    
                         <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                             $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1  AND a.repair_status = 'Waiting for SOA Approval'   AND a.isdeleted = '0'  ORDER BY created_at DESC";
                                              $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                        <sup style="font-size: 20px"></sup>
                                    </h3>
                                    <p>
                                        Waiting for Customer Approval
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Waiting-for-SOA-Approval.png">
                                </div>
                                <a href="joborders.php?type=waiting_for_approval" class="small-box-footer mrorange">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>


                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                           $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1  AND a.repair_status = 'Approved'   AND a.isdeleted = '0'  ORDER BY created_at DESC";
                                            $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?> 
                                    </h3>
                                    <p>
                                        Approved Job Order
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Approved.png">
                                </div>
                                <a href="soa.php?type=approved" class="small-box-footer approvedme">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->




                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                             $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1   AND a.isdeleted = '0'  AND a.repair_status = 'Ongoing Repair' ORDER BY created_at DESC";
                                              $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                        <sup style="font-size: 20px"></sup>
                                    </h3>
                                    <p>
                                        Ongoing Repair
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Ongoing-Repair.png">
                                </div>
                                <a href="soa.php?type=ongoing_repair" class="small-box-footer bg-teal">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->

                        <!-- ./col --><div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                             $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.status_id = 1 AND a.isdeleted = '0'  AND a.repair_status = 'Done-Ready for Delivery'  ORDER BY created_at DESC";
                                              $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                        <sup style="font-size: 20px"></sup>
                                    </h3>
                                    <p>
                                        Ready for Pickup
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Done---Delivered---Copy.png">
                                </div>
                                <a href="soa.php?type=ready_for_delivery" class="small-box-footer mredilive">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col --> <!-- ./col --><div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                            
                                             $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'  AND a.repair_status = 'Ready for Claiming' ORDER BY created_at DESC";
                                             $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                        <sup style="font-size: 20px"></sup>
                                    </h3>
                                    <p>
                                       Delivered
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Done---Delivered.png">
                                </div>
                                <a href="soa.php?type=ready_for_claiming" class="small-box-footer mdone">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->

                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                             $qu3 = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id  AND a.isdeleted = '0'  AND a.repair_status = 'Ready for Claiming' ORDER BY created_at DESC";
                                             $query = $db->ReadData($qu3);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                    </h3>
                                    <p>
                                        Unclaimed
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Unclaimed.png">
                                </div>
                                <a href="soa.php?type=unclaimed" class="small-box-footer mred">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-grey">
                                <div class="inner">
                                    <h3>
                                        <?php 
                                             $sql = "SELECT a.soa_id, a.jobid, a.customerid, a.branchid, a.technicianid, a.cost_id, a.status, a.conforme, a.created_at, a.updated_at, c.name, b.diagnosis, b.item, b.customerid, b.remarks, b.branchid, b.repair_status, b.parts FROM jb_soa a, jb_joborder b , jb_customer c WHERE  a.jobid =  b.jobid AND b.customerid = c.customerid AND b.repair_status = 'Claimed' ORDER BY a.created_at DESC";
                                            $query = $db->ReadData($sql);  
                                            echo  $db->GetNumberOfRows(); 
                                        ?>
                                    </h3>
                                    <p>
                                        Claimed
                                    </p>
                                </div>
                                <div class="icon icon-mc">
                                    <img src="<?php echo SITE_IMAGES_DIR; ?>s/Claimed.png">
                                </div>
                                <a href="soa.php?type=Claimed" class="small-box-footer bg-green">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                    </div><!-- /.row -->

                    <!-- top row -->
                    <div class="row">
                        <div class="col-xs-12 connectedSortable">
                            
                        </div><!-- /.col -->
                    </div>
                    <!-- /.row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        
<?php
    htmlFooter('dashboard');
?>