<?php
    include '../../include.php';
    include '../ui_main.php';
    htmlHeader('dashboard');
    global $url;
     $id = $_GET['id'];
     $month = $_GET['month'];
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
                breadcrumps($queryin[0]['branch_name']);



                

              ?>
              <script type="text/javascript">
                $(function(){
                    $('.add').css('display','none');
                    $('.delete').css('display','none');
                    $('.edit').css('display','none');
                    $('.view').css('display','none');
                });
              </script>

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
                           <strong>Username: </strong><span class="eusername"><?php echo $query[0]['username'];?></span><br>
                                <strong>Email Address: </strong><span class="eemailaddress"><?php echo $query[0]['email'];?></span><br>
                                <strong>Contact Person: </strong><span class="efullname"><?php echo $query[0]['name'];?></span><br>
                                <strong>Contact Number: </strong><span class="econtact"><?php echo $query[0]['contact_number'];?></span><br>
                                <strong>Address: </strong><span class="ebranchaddress"><?php echo $query[0]['address'];?></span><br>
                            
                        </div>

                        <!-- /.col -->
<!--                          <div class="form-group">
                            <label>Date range button:</label>
                            <div class="input-group">
                                <button class="btn btn-default pull-right" id="daterange-btn">
                                    <i class="fa fa-calendar"></i> Date range picker
                                    <i class="fa fa-caret-down"></i>
                                </button>
                            </div>
                        </div><!-- /.form group --> 
                                    
                        <?php 

                       if($month=='0'){
                            $getalljobforbranch2 = "SELECT * FROM jb_joborder WHERE  branchid = '".$id."'";
                            $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
                            $jobcounter = $db->GetNumberOfRows();
                            $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'    AND repair_status = 'Ongoing Repair' ";

                            $ongoingquery  = $db->ReadData($ongoing);
                            $ongoingre = $db->GetNumberOfRows();

                            $pending = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status <> 'Claimed'  AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair'";
                            $getalljobfosdfrbranch  = $db->ReadData($pending);
                            $pendingre = $db->GetNumberOfRows();

                            $unclaim = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status = 'Ready for Claiming'";
                            $getalljobforsdfbranch  = $db->ReadData($unclaim);
                            $unclaimre = $db->GetNumberOfRows();

                            $claimed = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status = 'Claimed'";
                            $getallsdfjobforbranch  = $db->ReadData($claimed);
                            $claimedre = $db->GetNumberOfRows();

                       }else if(!isset($_GET['month'])){

                            $getalljobforbranch2 = "SELECT * FROM jb_joborder WHERE  branchid = '".$id."'";
                            $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
                            $jobcounter = $db->GetNumberOfRows();

                            $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status = 'Ongoing Repair' ";

                            $ongoingquery  = $db->ReadData($ongoing);
                            $ongoingre = $db->GetNumberOfRows();

                            $pending = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status <> 'Claimed'  AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair'";
                            $getalljobfosdfrbranch  = $db->ReadData($pending);
                            $pendingre = $db->GetNumberOfRows();

                            $unclaim = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status = 'Ready for Claiming'";
                            $getalljobforsdfbranch  = $db->ReadData($unclaim);
                            $unclaimre = $db->GetNumberOfRows();

                            $claimed = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND repair_status = 'Claimed'";
                            $getallsdfjobforbranch  = $db->ReadData($claimed);
                            $claimedre = $db->GetNumberOfRows();

                       }else{
                        $getalljobforbranch2 = "SELECT * FROM jb_joborder WHERE  branchid = '".$id."' AND MONTH(created_at) = ".$month."";
                        $getalljobforbranch  = $db->ReadData($getalljobforbranch2);
                        $jobcounter = $db->GetNumberOfRows();

                         $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$id."'  AND MONTH(created_at) = ".$month."  AND repair_status = 'Ongoing Repair' ";
                       
                        $ongoingquery  = $db->ReadData($ongoing);
                        $ongoingre = $db->GetNumberOfRows();

                        $pending = "SELECT *  from jb_joborder WHERE branchid  = '".$id."' AND MONTH(created_at) = ".$month." AND repair_status <> 'Claimed'  AND repair_status <> 'Ready for Claiming' AND repair_status <> 'Ongoing Repair'";
                        $getalljobfosdfrbranch  = $db->ReadData($pending);
                        $pendingre = $db->GetNumberOfRows();

                        $unclaim = "SELECT *  from jb_joborder WHERE branchid  = '".$id."' AND MONTH(created_at) = ".$month." AND repair_status = 'Ready for Claiming'";
                        $getalljobforsdfbranch  = $db->ReadData($unclaim);
                        $unclaimre = $db->GetNumberOfRows();

                        $claimed = "SELECT *  from jb_joborder WHERE branchid  = '".$id."' AND MONTH(created_at) = ".$month." AND repair_status = 'Claimed'";
                        $getallsdfjobforbranch  = $db->ReadData($claimed);
                        $claimedre = $db->GetNumberOfRows();
                       }

                        ?>
                        <div class="col-sm-4 invoice-col">
                           <strong>Total Job Order: </strong><span class="eusername"><?php echo $jobcounter;?></span><br>
                                <strong>Ongoing Repair: </strong><span class="eemailaddress"><?php echo $ongoingre;?></span><br>
                                <strong>Pending: </strong><span class="eemailaddress"><?php echo $pendingre;?></span><br>
                                <strong>Unclaim: </strong><span class="efullname"><?php echo $unclaimre;?></span><br>
                                <strong>Claimed: </strong><span class="efullname"><?php echo $claimedre;?></span><br>
                        </div><!-- /.row -->
                    </div><!-- /.row -->
                    </div>
                    <div class="col-md-6"> <div style="width: 100%">
                        <canvas id="canvas" height="400" width="600"></canvas>
                    </div>
                            <?php 
                                $jan = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 01";
                                $jan  = $db->ReadData($jan);
                                $jan = $db->GetNumberOfRows();
                                $feb = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 02";
                                $feb  = $db->ReadData($feb);
                                $feb = $db->GetNumberOfRows();
                                $mar = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 03";
                                $mar  = $db->ReadData($mar);
                                $mar = $db->GetNumberOfRows();
                                $apr = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 04";
                                $apr  = $db->ReadData($apr);
                                $apr = $db->GetNumberOfRows();
                                $may = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 05";
                                $may  = $db->ReadData($may);
                                $may = $db->GetNumberOfRows();
                                $jun = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 06";
                                $jun  = $db->ReadData($jun);
                                $jun = $db->GetNumberOfRows();
                                $jul = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 07";
                                $jul  = $db->ReadData($jul);
                                $jul = $db->GetNumberOfRows();
                                $aug = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 08";
                                $aug  = $db->ReadData($aug);
                                $aug = $db->GetNumberOfRows();
                                $sep = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 09";
                                $sep  = $db->ReadData($sep);
                                $sep = $db->GetNumberOfRows();
                                $oct = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 10";
                                $oct  = $db->ReadData($oct);
                                $oct = $db->GetNumberOfRows();
                                $nov = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 11";
                                $nov  = $db->ReadData($nov);
                                $nov = $db->GetNumberOfRows();
                                $dev = "SELECT * FROM jb_joborder  WHERE branchid  = '".$id."' AND MONTH(created_at) = 12";
                                $dev  = $db->ReadData($dev);
                                $dev = $db->GetNumberOfRows();
                            ?>
      
        <script type="text/javascript">
       var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
                        var barChartData = {

                        labels : ["January","February","March","April","May","June","July","August","September","october","November","December"],
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

</script></div>
                    <div class="col-md-6">
                         <div id="canvas-holder" style="display: none;">
                            <canvas id="chart-area1" width="50" height="50" />
                        </div>
    <div id="canvas-holder">
        <canvas id="chart-area2" width="300" height="300" />

    </div>

    <div class="form-group col-md-12">
    <div class="form-group col-md-3"></div>
    <div class="form-group col-md-6"><label>Select Month</label>
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
    </select></div>
    <div class="form-group col-md-3"></div>
    </div>

    <div id="chartjs-tooltip"></div>
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

                            $(function(){
                                $("[name=selectMonth]").val("<?php echo $_GET['month'];?>");
                               $("[name=selectMonth]").on('change',function(){
                                    window.location.href = "<?php echo SITE_URL; ?>head_office/revenueview.php?id=<?php echo $id; ?>&&month=" + $("[name=selectMonth]").val();
                                });

                                $("[name=selectMonth]").val("<?php echo $_GET['month'];?>");
                            });
                            </script>


                    </div>
        <div class="col-md-12">
            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Monthly Summary</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th style="width: 10px">Months</th>
                                            <th>Total Job Order</th>
                                            <th>Monthly Revenew</th>
                                            <th>Ongoing Job order</th>
                                            <th>Unclaimed Job order</th>
                                            <th>Claimed Job order</th>
                                        </tr>
                                        <tr>
                                            <td>January</td>
                                            <td><?php echo $jan;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 01";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <td>February</td>
                                            <td><?php echo $feb;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 04";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                           <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <td>March</td>
                                            <td><?php echo $mar;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 05";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr>
                                        <tr>
                                            <td>April</td>
                                            <td><?php echo $apr;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 04";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>May</td>
                                            <td><?php echo $may;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 05";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>June</td>
                                            <td><?php echo $jun;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 06";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>July</td>
                                            <td><?php echo $jul;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 07";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>August</td>
                                            <td><?php echo $aug;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 08";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>September</td>
                                            <td><?php echo $sep;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 09";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 09";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 09";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 09";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>October</td>
                                            <td><?php echo $oct;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 10";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>November</td>
                                            <td><?php echo $nov;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 11";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr><tr>
                                            <td>December</td>
                                            <td><?php echo $dev;?></td>
                                            <td>
                                                <?php 
                                                $jant = "SELECT  (SUM(a.totalpartscost) + SUM(a.service_charges)+ SUM(a.total_charges)) as total FROM jb_cost a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid  = '".$_GET['id']."' AND MONTH(b.created_at) = 12";
                                                $jant  = $db->ReadData($jant);
                                                if($jant[0]['total'] == NULL){echo "0";}else{echo $jant[0]['total'];};
                                                ?>
                                            </td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ongoing Repair' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td><?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Ready for Claiming' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                            <td> <?php 
                                                $ongoing = "SELECT *  from jb_joborder WHERE branchid  = '".$_GET['id']."' AND repair_status = 'Claimed' AND MONTH(created_at) = 01";
                                                $ongoingquery  = $db->ReadData($ongoing);
                                                echo $db->GetNumberOfRows();
                                                ?></td>
                                        </tr>
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col -->
        </div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
<?php
    htmlFooter('dashboard');
?>