<?php
    include '../../include.php';
    include '../ui_main.php';
    htmlHeader('dashboard');
    global $url;
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

        // $updatenotif = "UPDATE `notitemp` SET `isViewed`='1'";
        // $udpatejobnow= $db->ExecuteQuery($updatenotif);

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
              <?php breadcrumps('Job Order Notifications'); ?>

              <script type="text/javascript">
                $(function(){
                    $(".edit").css('display','none');
                    $(".add").css('display','none');
                    $(".delete").css('display','none');
                    $(".view").css('display','none');
                });
                </script>

                <!-- Main content -->
                <section class="content">
                     <ul class="timeline mctimeline">
            <?php 

                $sql = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
                $query = $db->ReadData($sql);
                $datetemp = "sdfsdf";
                foreach ($query as $key => $value) {
                    if(substr($datetemp, 0, 10) != substr($value['created_at'], 0, 10)) {
                        ?>
                        <li class="time-label">
                            <span class="bg-green">
                                <?php echo substr($value['created_at'], 0, 10);?>
                            </span>
                        </li>
                    <?php
                    }else{

                    }
                $datetemp = $value['created_at'];
                $datetemp = substr($datetemp, 0, 10);
            
            ?>
            
            <!-- timeline item -->
            <li>
                <!-- timeline icon -->
                <i class="fa fa-envelope bg-grey"></i>
                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> <?php echo substr($value['created_at'], -8)?></span>
                    <h3 id="<?php echo $value['jobid'];?>" data-subid="<?php echo  $value['notif_id'];?>"  class="clickToView" ><a href="#"><?php echo ucwords($value['user']);?>  <?php echo ucwords($value['status_type']);?></a> </h3>
                    <div class="timeline-body">
                        Job Order ID: <?php echo $value['jobid'];?>
                    </div>
                </div>
            </li>
            <?php
        }
    ?>
</ul>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <script type="text/javascript">
        $(function(){
            $(".clickToView").on('click',function(){
                var id = $(this).attr('id');
                var subid = $(this).attr('data-subid');
                window.location.href = '<?php echo SITE_URL;?>head_office/notifview.php?jobid='+ id + '&&subid=' + subid;
            });
        });
        </script>

<?php
    htmlFooter('dashboard');
?>