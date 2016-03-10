<?php
    include '../../include.php';
    include '../ui_branch.php';
    htmlHeader('dashboard');
    $queryforexcel = "";
    global $url;

    # Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

    if($_SESSION['position'] != -1) {
        foreach ($permission as $key => $value) {

            if($value['name'] == 'sales_report') {
                $sales = true;
            }
        }

        if(!isset($sales)) {
            echo '<script>window.location = "dashboard.php";</script>';
            exit();
        }
    }

     $id = $_SESSION['Branchid'];
     if(isset($_GET['daterange'])){
         $month = $_GET['daterange'];
         $bydate = split ("to", $_GET['daterange']);

         $yearOne = explode("-",$bydate[0]);
         $yearTwo = explode("-",$bydate[1]);

         $currtYear = $yearOne[0];
     }else{
        $month = "0";
        $bydate = split ("to", "to");

        //current year
        $currtYear = date('Y');
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
           $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
        $query2 = $db->ReadData($sql2);

        $counterviewed = "SELECT * FROM notitemp WHERE  branch_id <> '0' AND isViewed <> '1' ORDER BY `created_at` DESC";
        $counterviewed = $db->ReadData($counterviewed);

        headerDashboard($name, $query2, count($counterviewed)); ?>

        <div class="modal fade" id="selecrecord-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content" style="width: 446px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Please make a selection within the same year.</h4>
                    </div>
                    <div class="modal-body">
                         <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
                    <div class="clear"></div>
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- /.modal -->

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
            <?php
                $checker = "SELECT * FROM `jb_user` WHERE branch_id = '".$id."' AND position = '2'";
                $query  = $db->ReadData($checker);
                $qu = "SELECT * FROM `jb_branch` WHERE branch_id = '".$id."' ";
                $queryin  = $db->ReadData($qu);
                breadcrumps("Sales Report");
            ?>

                <!-- Main content -->
                <section class="content">

                    <div class="col-md-12"><div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Branch Name: </strong><span class="ebranchname"><?php echo $queryin[0]['branch_name'];?></span><br>
                                <strong>Contact number : </strong><span class="enumber"><?php echo $queryin[0]['number'];?></span><br>
                                <strong>Email Address: </strong><span class="eemail"><?php echo $queryin[0]['email'];?></span><br>
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                                <strong>Email Address: </strong><span class="eemailaddress"><?php echo $query[0]['email'];?></span><br>
                                <strong>Contact Person: </strong><span class="efullname"><?php echo $query[0]['name'];?></span><br>
                                <strong>Contact Number: </strong><span class="econtact"><?php echo $query[0]['contact_number'];?></span><br>
                                <strong>Address: </strong><span class="ebranchaddress"><?php echo $query[0]['address'];?></span><br>
                        </div>
                        <?php 

                            $range = '';
                            if(isset($_GET['daterange'])){
                                $range = " AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
                            }

                            $getalljobforbranch2 = "SELECT * FROM jb_joborder WHERE  jobclear = 0 AND branchid = '".$id."' AND YEAR(created_at) = '".$currtYear."' ".$range."";
                            $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
                            $jobcounter = $db->GetNumberOfRows();

                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."'  AND repair_status = 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range."";

                            $ongoingquery  = $db->ReadData($ongoing);
                            $ongoingre = $db->GetNumberOfRows();

                            $pending = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."'  AND repair_status <> 'Claimed'  AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair' AND YEAR(created_at) = '".$currtYear."' ".$range."";
                            $getalljobfosdfrbranch  = $db->ReadData($pending);
                            $pendingre = $db->GetNumberOfRows();

                            $unclaim = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."'  AND repair_status = 'Ready for Claiming' AND YEAR(created_at) = '".$currtYear."' ".$range."";
                            $getalljobforsdfbranch  = $db->ReadData($unclaim);
                            $unclaimre = $db->GetNumberOfRows();

                            $claimed = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."'  AND repair_status = 'Claimed' AND YEAR(created_at) = '".$currtYear."' ".$range."";
                            $getallsdfjobforbranch  = $db->ReadData($claimed);
                            $claimedre = $db->GetNumberOfRows();
                        ?>
                        <div class="col-sm-4 invoice-col">

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
                        </div> 
                    </div><!-- /.row -->
                    </div>

                    <div class="col-md-6">
                     <h3 class="box-title">Pie Chart</h3>

                    <div id="chartjs-tooltip"></div>
                     <div id="canvas-holder">
                        <canvas id="chart-area2" width="280" height="280" />
                    </div>

    <div class="form-group col-md-12">
    <div class="form-group col-md-3"></div>
<!--     <div class="form-group col-md-6"><label>Select Month</label>
    <select name="selectMonth" class="form-control">
        <option value="0">None..</option>
        <option value="01">Janaury</option>
        <option value="02">February</option>
        <option value="03">March</option>
        <option value="04">April</option>
        <option value="05">May</option>
        <option value="06">June</option>
        <option value="07">July</option>
        <option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select></div> -->
    <div class="form-group col-md-3"></div>
    </div>
    </div>
    <div class="col-md-6"> <div style="width: 100%">
    <h3 class="box-title"><?php if(isset($_GET['daterange'])) {
                    ?>
                 <h3 class="box-title">Monthly Summary <span> From <?php echo date("F d, Y", strtotime($bydate[0])); ?> to <?php echo date("F d, Y", strtotime($bydate[1])); ?> of <?php echo $currtYear; ?></span></h3>
                    <?php 
                    }else{
                    ?>
                 <h3 class="box-title">Monthly Summary of <?php echo $currtYear; ?></h3>
                    <?php 
                }?></h3>

                        <canvas id="canvas" height="400" width="700"></canvas>
                    </div>
                            <?php
                                $range = '';
                                if(isset($_GET['daterange'])){
                                    $range = " AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
                                }

                                $jan = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $jan  = $db->ReadData($jan);
                                $jan = $db->GetNumberOfRows();
                                $feb = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $feb  = $db->ReadData($feb);
                                $feb = $db->GetNumberOfRows();
                                $mar = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $mar  = $db->ReadData($mar);
                                $mar = $db->GetNumberOfRows();
                                $apr = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $apr  = $db->ReadData($apr);
                                $apr = $db->GetNumberOfRows();
                                $may = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $may  = $db->ReadData($may);
                                $may = $db->GetNumberOfRows();
                                $jun = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $jun  = $db->ReadData($jun);
                                $jun = $db->GetNumberOfRows();
                                $jul = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $jul  = $db->ReadData($jul);
                                $jul = $db->GetNumberOfRows();
                                $aug = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $aug  = $db->ReadData($aug);
                                $aug = $db->GetNumberOfRows();
                                $sep = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $sep  = $db->ReadData($sep);
                                $sep = $db->GetNumberOfRows();
                                $oct = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $oct  = $db->ReadData($oct);
                                $oct = $db->GetNumberOfRows();
                                $nov = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $nov  = $db->ReadData($nov);
                                $nov = $db->GetNumberOfRows();
                                $dev = "SELECT * FROM jb_joborder  WHERE jobclear = 0 AND branchid  = '".$id."' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range."";
                                $dev  = $db->ReadData($dev);
                                $dev = $db->GetNumberOfRows();
                            ?>
      
        <script type="text/javascript">
                        var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
                        var barChartData = {
                        labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
                        datasets : [
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,0.8)",
                                highlightFill : "rgba(151,187,205,0.75)",
                                highlightStroke : "rgba(151,187,205,1)",
                                data : [<?php echo $jan;?>,<?php echo $feb;?>,<?php echo $mar;?>,<?php echo $apr;?>,<?php echo $may;?>,<?php echo $jun;?>,<?php echo $jul;?>,<?php echo $aug;?>,<?php echo $sep;?>,<?php echo $oct;?>,<?php echo $nov;?>,<?php echo $dev;?>]
                            }
                        ]
                    }
                    var ctx = document.getElementById("canvas").getContext("2d");
                    window.myBar = new Chart(ctx).Bar(barChartData, {
                        responsive : true
                    });

                    $(function(){

                        $('#createexcel').on('click', function(){
                            <?php
                                $range = '';
                                if(isset($_GET['daterange'])){
                                    $range = '&&daterange='.$_GET['daterange'];
                                }
                            ?>

                            var page = "../ajax/generateexcelbranch.php?querytogenerate=0&&type=salesreportbranch&&filename=salesreportbranch&&id=0&&id=<?php echo $id; ?>&&year=<?php echo $currtYear; ?><?php echo $range;?>"
                            window.location = page;// you can use window.open also
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
                                                if( start.format('YYYY') == end.format('YYYY')) {
                                                    window.location.assign("" + newu + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                                } else {
                                                    $("#selecrecord-modal").modal('show');
                                                }
                                            <?php
                                        }else{
                                            if(isset($_GET['type'])){
                                                ?>
                                                if( start.format('YYYY') == end.format('YYYY')) {
                                                    window.location.assign("" + "<?php echo SITE_URL;?>branch/salesreport.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                                } else {
                                                    $("#selecrecord-modal").modal('show');
                                                }
                                                <?php 
                                            }else{
                                                ?>
                                                if( start.format('YYYY') == end.format('YYYY')) {
                                                    window.location.assign("" + "<?php echo SITE_URL;?>branch/salesreport.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                                } else {
                                                    $("#selecrecord-modal").modal('show');
                                                }

                                                <?php 
                                            }
                                            ?>
                                            <?php
                                        }
                                    ?>
                                   
                            }
                            );
                    });
        </script>

        </div>


                  <script>  
                            Chart.defaults.global.customTooltips = function(tooltip) {

                                // Tooltip Element
                                var tooltipEl = $('#chartjs-tooltip');

                                // Hide if no tooltip
                                if (!tooltip) {
                                    tooltipEl.css({
                                        opacity: 0
                                    });
                                    return;
                                }

                                // Set caret Position
                                tooltipEl.removeClass('above below');
                                tooltipEl.addClass(tooltip.yAlign);

                                // Set Text
                                tooltipEl.html(tooltip.text);

                                // Find Y Location on page
                                var top;
                                if (tooltip.yAlign == 'above') {
                                    top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
                                } else {
                                    top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
                                }

                                // Display, position, and set styles for font
                                tooltipEl.css({
                                    opacity: 1,
                                    left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
                                    top: tooltip.chart.canvas.offsetTop + top + 'px',
                                    fontFamily: tooltip.fontFamily,
                                    fontSize: tooltip.fontSize,
                                    fontStyle: tooltip.fontStyle,
                                });
                            };
                            
                            var pieData = [{
                                value: <?php echo $pendingre;?>,
                                color: "#F7464A",
                                highlight: "#FF5A5E",
                                label: "Pending"
                            }, {
                                value: <?php echo $claimedre;?>,
                                color: "#46BFBD",
                                highlight: "#5AD3D1",
                                label: "Claimed"
                            }, {
                                value: <?php echo $unclaimre;?>,
                                color: "#FDB45C",
                                highlight: "#FFC870",
                                label: "Unclaim"
                            }, {
                                value: <?php echo $ongoingre;?>,
                                color: "#949FB1",
                                highlight: "#A8B3C5",
                                label: "Ongoing Repair"
                            }];

                            window.onload = function() {
                                var ctx2 = document.getElementById("chart-area2").getContext("2d");
                                window.myPie = new Chart(ctx2).Pie(pieData);
                            };

                            
                            </script>

<div class="col-md-12">

            <div class="bodx">
                <?php if(isset($_GET['daterange'])) {
                    ?>
                <h3 class="box-title">Sales Summary <span> From <?php echo date("F d, Y", strtotime($bydate[0])); ?> to <?php echo date("F d, Y", strtotime($bydate[1])); ?> of <?php echo $currtYear; ?></span></h3>
                <?php 
                }else{
                ?>
                 <h3 class="box-title">Sales Summary of <?php echo $currtYear; ?> </h3>
                    <?php 
                }?>
                <div class="box-body no-padding">
                    <table class="table table-condensed">
                        <tr>
                            <th>Total Job Order</th>
                            <th>Revenue</th>
                            <th>Ongoing Job order</th>
                            <th>Unclaimed Job order</th>
                            <th>Claimed Job order</th>
                        </tr>
                        <tr>
                            <td><?php echo $jobcounter;?></td>
                            <td>
                              <?php 
                                $range = '';
                                if(isset($_GET['daterange'])){
                                    $range = " AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
                                }
                                 $selecttechvalue = "SELECT SUM(a.totalpartscost + a.service_charges + a.total_charges) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND branchid  = '".$id."' AND a.jobid = b.jobid AND YEAR(b.created_at) = '".$currtYear."' ".$range."";

                                $totald = $db->ReadData($selecttechvalue);
                                echo "<b>P </b> ".number_format($totald[0]['total'],2);
                              ?>
                            </td>
                            <td><?php echo $ongoingre;?></td>
                            <td><?php echo $unclaimre;?></td>
                            <td> <?php echo $claimedre;?></td>
                        </tr>
                        
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
                            
            <div class="box">
            <div class="box-header">
                <?php if(isset($_GET['daterange'])) {
                    ?>
                 <h3 class="box-title">Monthly Summary <span> From <?php echo date("F d, Y", strtotime($bydate[0])); ?> to <?php echo date("F d, Y", strtotime($bydate[1])); ?> of <?php echo $currtYear; ?></span></h3>
                    <?php 
                    }else{
                    ?>
                 <h3 class="box-title">Monthly Summary of <?php echo $currtYear; ?></h3>
                    <?php 
                }?></h3>

            </div><!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-condensed">
                    <?php
                        $range = '';
                        $range2 = '';
                        if(isset($_GET['daterange'])){
                            $range = " AND b.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
                            $range2 = " AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'";
                        }
                    ?>
                    <tr>
                        <th style="width: 10px">Months</th>
                        <th>Total Job Order</th>
                        <th>Monthly Revenue</th>
                        <th>Pending Job Orders</th>
                        <th>Ongoing Job Order</th>
                        <th>Unclaimed Item</th>
                        <th>Claimed Item</th>
                    </tr>
                    <tr>
                        <td>January</td>
                        <td><?php echo $jan;?></td>
                        <td>
                            <?php 
                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND branchid  = '".$id."' AND a.jobid = b.jobid AND  MONTH(b.created_at) = 01 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                                $jant  = $db->ReadData($jant);
                                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr>
                    <tr>
                        <td>February</td>
                        <td><?php echo $feb;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 02 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                       <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 02 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr>
                    <tr>
                        <td>March</td>
                        <td><?php echo $mar;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 03 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 03 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr>
                    <tr>
                        <td>April</td>
                        <td><?php echo $apr;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 04 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 04 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>May</td>
                        <td><?php echo $may;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 05 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php

                                $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                                $ongoingquery  = $db->ReadData($ongoing);
                                echo $db->GetNumberOfRows();

                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 05 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>June</td>
                        <td><?php echo $jun;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 06 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 06 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>July</td>
                        <td><?php echo $jul;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 07 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 07 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>August</td>
                        <td><?php echo $aug;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 08 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 08 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>September</td>
                        <td><?php echo $sep;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 09 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 09 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>October</td>
                        <td><?php echo $oct;?></td>
                        <td>
                            <?php 
                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 10 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                                $jant  = $db->ReadData($jant);
                                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 10 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>November</td>
                        <td><?php echo $nov;?></td>
                        <td>
                            <?php 
                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 11 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                                $jant  = $db->ReadData($jant);
                                if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 11 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr><tr>
                        <td>December</td>
                        <td><?php echo $dev;?></td>
                        <td>
                            <?php 
                            $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND  MONTH(b.created_at) = 12 AND YEAR(b.created_at) = '".$currtYear."' ".$range."";
                            $jant  = $db->ReadData($jant);
                            if($jant[0]['total'] == NULL){echo "<b>P</b> "." 0";}else{ echo "<b>P</b> ". number_format($jant[0]['total'],2);};
                            ?>
                        </td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Waiting List' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td><?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                        <td> <?php 
                            $ongoing = "SELECT *  from jb_joborder WHERE jobclear = 0 AND branchid  = '".$id."' AND repair_status = 'Claimed' AND MONTH(created_at) = 12 AND YEAR(created_at) = '".$currtYear."' ".$range2."";
                            $ongoingquery  = $db->ReadData($ongoing);
                            echo $db->GetNumberOfRows();
                            ?></td>
                    </tr>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->


                    </div>
       
        </div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
<?php
    htmlFooter('dashboard');
?>