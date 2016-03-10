<?php

function htmlHeader($dashboard = false)
{
    global $url;
    if($dashboard == "login") {
        $dashboard = "login-bg";
    }
    if($dashboard == "dashboard") {

        if(empty($_SESSION['username'])) {
            header('location: ../');
            exit;
        }else {

            if($_SESSION['position'] == 2){
                header('location: ../branch/dashboard.php');
                exit;
            }else if($_SESSION['position'] == 2) {
                
            }
        }
    }
?>
<!DOCTYPE html>
<html class="<?php echo  $dashboard; ?>">
    <head>
        <meta charset="UTF-8">
           <title><?php echo SITE_NAME . ($url[0] != "home" && $url[0] != "" ? " - " . ucwords(str_replace("-", " ", $url[0])) : ""); ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        
        <link rel="icon" type="image/png" href="<?php echo SITE_IMAGES_DIR ?>favi.png" />
        <link href="<?php echo SITE_CSS_DIR ?>style.css" rel="stylesheet">
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo SITE_CSS_DIR ?>bootstrap.min.css" rel="stylesheet" data-noprefix>
        <!-- font Awesome -->
        <link href="<?php echo SITE_CSS_DIR ?>font-awesome.min.css" rel="stylesheet" type="text/css" data-noprefix/>
        <!-- Theme style -->
        <?php 
            if($dashboard == "dashboard") {
        ?>
         <!-- Ionicons -->
        <link href="<?php echo SITE_CSS_DIR ?>ionicons.min.css" rel="stylesheet" type="text/css" data-noprefix/>
        <!-- Morris chart -->
        <link href="<?php echo SITE_CSS_DIR ?>morris/morris.css" rel="stylesheet" type="text/css" data-noprefix/>
        <!-- jvectormap -->
        <link href="<?php echo SITE_CSS_DIR ?>jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="<?php echo SITE_CSS_DIR ?>fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="<?php echo SITE_CSS_DIR ?>daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="<?php echo SITE_CSS_DIR ?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
       <link href="<?php echo SITE_CSS_DIR ?>datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <?php } ?>

        <link href="<?php echo SITE_CSS_DIR ?>AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
        <!-- jQuery 2.0.2 -->
        <script src="<?php echo SITE_JS_DIR ?>jquery.min.js"></script>
        <script src="<?php echo SITE_JS_DIR ?>jquery.number.min.js"></script>
        <script src="https://js.pusher.com/3.0/pusher.min.js"></script>
          <script>

            var notiftimer = 8000;
            // Enable pusher logging - don't include this in production
            Pusher.log = function(message) {
              if (window.console && window.console.log) {
                window.console.log(message);
              }
            };

            var pusher = new Pusher('abd83e79f848c8679917', {
              encrypted: true
            });

            var channel = pusher.subscribe('test_channel');
            channel.bind('my_event', function(data) {
              // alert(data.message);
                if(data.kanino == '1'){
                    $('.notification').fadeIn('slow');
                    $('.name_notification').html(data.name);
                    $('.message_notification').html(data.message);

                     $.ajax({
                        type: 'POST',
                        url: '../ajax/notifchanger.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        },
                        success: function(e){
                            
                            var obj = jQuery.parseJSON(e);
                            var d = "";

                            $('.reload_notification').html("");
                            $('.counternotifs').html(obj.counter);
                            for (var i = 0; i < obj.nofitication.length; i++) {
                                var not = "";

                                if(obj.nofitication[i].isViewed == "0"){
                                    not  = "notyetview";
                                }else{
                                    not  = "viewednotif";
                                }

                                d  = d  + "<li id='"+obj.nofitication[i].jobid+"' data-subid ='"+obj.nofitication[i].notif_id+"' class=\"clicknotif "+not +"\"><a href=\"#\"><i class=\"iconnotif glyphicon glyphicon-envelope \"></i><b>"+obj.nofitication[i].user+"</b> "+obj.nofitication[i].status_type+"</a></li>";
                            
                            };

                            $('.reload_notification').append(d);

                            }
                    });

                    setTimeout(toasthide,notiftimer);
                }
            });
            function toasthide() {
                $('.notification').fadeOut('slow');
            }
          </script>
        <script src="<?php echo SITE_JS_DIR ?>AdminLTE/Chart.min.js" type="text/javascript"></script>
    </head>
    <body class="<?php echo  $dashboard; ?> skin-blue pace-done fixed">
    <div class="notification">
       <div class="wrapper-notif">
            <b  class="name_notification">Name</b><br>
            <p class="message_notification">message here</p> 
            <span class="closebutton">x</span>
       </div>
    </div>
    <script type="text/javascript">
    $(function(){
        $('.closebutton').on('click',function(){
            $('.notification').fadeOut('slow');
        });
    });
    </script>
    <?php	
}
function htmlFooter($dashboard = false)
{
	?>
    <?php 
        if($dashboard == "dashboard") {
    ?>
        <!-- jQuery UI 1.10.3 -->
        <script src="<?php echo SITE_JS_DIR ?>jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <script src="<?php echo SITE_JS_DIR ?>modernizr.2.8.3.min.js" type="text/javascript"></script>
        
    <?php } ?>
        <!-- Bootstrap -->
        <script src="<?php echo SITE_JS_DIR ?>bootstrap.min.js"></script>   
    <?php 
        if($dashboard == "dashboard") {
    ?>
         <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="<?php echo SITE_JS_DIR ?>plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="<?php echo SITE_JS_DIR ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

        <!-- AdminLTE App -->
        
        <!-- DATA TABES SCRIPT -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="<?php echo SITE_JS_DIR ?>plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="<?php echo SITE_JS_DIR ?>AdminLTE/app.js" type="text/javascript"></script>
        <!-- AdminLTE App
        <script src="<?php echo SITE_JS_DIR ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>

        <script src="<?php echo SITE_JS_DIR ?>/AdminLTE/dashboard.js" type="text/javascript"></script>    -->

        <script src="<?php echo SITE_JS_DIR ?>/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <script src="<?php echo SITE_JS_DIR ?>/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
        <script src="<?php echo SITE_JS_DIR ?>/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>     
        
        
        <script src="<?php echo SITE_JS_DIR ?>/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- bootstrap color picker -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
        <!-- bootstrap time picker -->
        <script src="<?php echo SITE_JS_DIR ?>plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>

        <!-- page script -->
        <script type="text/javascript">
             $(function() {
                //$("#tablecategory").dataTable();
                $('#example1, #tablediagnosis, #tablebrands, #tablemodels, #tablecategory').dataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "order": false,
                    "aaSorting":[],
                    "bAutoWidth": false
                });

                // $("body").on("contextmenu",function(e){
                //     return false;
                // });
            });
        </script>



    <?php } ?>

        <script src="<?php echo SITE_JS_DIR ?>jquery.validate.min.js" type="text/javascript"></script>  
        <script src="<?php echo SITE_JS_DIR ?>custom-script.js" type="text/javascript"></script> 
        <script src="<?php echo SITE_JS_DIR ?>mfupload.js" type="text/javascript"></script>

    <?php
        if($dashboard == "login") { 
            loginscript(); 
        }
    ?>

    </body>
    </html>
</html>
    <?php	
}
function breadcrumps($header = false){
    global $db;
    # Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

?>
    <section class="content-header">
        <h1>
            <?php 
            echo $header; 
                if($header == "Dashboard"){
            ?>
            <small>Control panel</small>
            <?php 
                }
            ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

            <li class="active"><?php echo $header; ?></li>
        </ol>
        <div class="clear"></div>
        <?php
           if($header != "Dashboard"){
        ?>
            <?php if($_SESSION['position'] == -1): ?>
            <ol class="contextual_menu">
                <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                <li class="add" ><i class="fa  fa-plus"> </i></li>
                <li class="edit"><i class="fa  fa-pencil"></i></li>
                <li class="delete"><i class="fa  fa-times"></i></li>
            </ol>
            <?php else: ?>
            <ol class="contextual_menu">
                <?php foreach($permission AS $value): ?>
                    <?php if($header == "Job Orders") : ?>
                        <?php if ($value['name'] == 'job_orders' && $value['view_status'] == 'yes'): ?>
                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'job_orders' && $value['add_status'] == 'yes'): ?>
                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'job_orders' && $value['edit_status'] == 'yes'): ?>
                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'job_orders' && $value['delete_status'] == 'yes'): ?>
                            <li class="delete"><i class="fa  fa-times"></i></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($header == "Statements of Account"): ?>
                        <?php if ($value['name'] == 'statements_of_account' && $value['view_status'] == 'yes'): ?>
                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'statements_of_account' && $value['add_status'] == 'yes'): ?>
                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'statements_of_account' && $value['edit_status'] == 'yes'): ?>
                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'statements_of_account' && $value['delete_status'] == 'yes'): ?>
                            <li class="delete"><i class="fa  fa-times"></i></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($header == "Branch"): ?>
                        <?php if ($value['name'] == 'branch' && $value['view_status'] == 'yes'): ?>
                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'branch' && $value['add_status'] == 'yes'): ?>
                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'branch' && $value['edit_status'] == 'yes'): ?>
                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'branch' && $value['delete_status'] == 'yes'): ?>
                            <li class="delete"><i class="fa  fa-times"></i></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($header == "Customers"): ?>
                        <?php if ($value['name'] == 'customers' && $value['view_status'] == 'yes'): ?>
                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'customers' && $value['add_status'] == 'yes'): ?>
                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'customers' && $value['edit_status'] == 'yes'): ?>
                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'customers' && $value['delete_status'] == 'yes'): ?>
                            <li class="delete"><i class="fa  fa-times"></i></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($header == "Technicians"): ?>
                        <?php if ($value['name'] == 'technicians' && $value['view_status'] == 'yes'): ?>
                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'technicians' && $value['add_status'] == 'yes'): ?>
                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'technicians' && $value['edit_status'] == 'yes'): ?>
                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'technicians' && $value['delete_status'] == 'yes'): ?>
                            <li class="delete"><i class="fa  fa-times"></i></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($header == "Parts"): ?>
                        <?php if ($value['name'] == 'parts' && $value['view_status'] == 'yes'): ?>
                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'parts' && $value['add_status'] == 'yes'): ?>
                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'parts' && $value['edit_status'] == 'yes'): ?>
                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                        <?php endif; ?>
                        <?php if ($value['name'] == 'parts' && $value['delete_status'] == 'yes'): ?>
                            <li class="delete"><i class="fa  fa-times"></i></li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach;?>
            </ol>  
            <?php endif; ?>
        <?php 
            }
        ?>


    </section>
<?php
}

function headerDashboard($type = false, $query2 = false, $counter = false){
    global $db;
    $sql = "SELECT * FROM jb_user WHERE id = '".$_SESSION['id']."'";
    $query = $db->ReadData($sql); 
    $image = SITE_IMAGES_DIR.'profile_pic/'.$query[0]['image'];
    ?>
         <header class="header">
            <a href="dashboard.php" class="logo">
                <img class="jblogoadminlogo" src="<?php echo SITE_IMAGES_DIR ?>logo2.png">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <span class="branchnamehere2">
                <?php 
                    if($type) {
                        echo $type;
                    }else {
                        echo "JB Head Office";
                    }
                ?>
                </span>

            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">                 
                        <!-- User Account: style can be found in dropdown.less -->

                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-warning"><span class="counternotifs"><?php echo $counter; ?></span></span>
                            </a>
                            <ul class="dropdown-menu">

                                <li class="header">You have <span class="counternotifs"><?php echo $counter; ?></span> notifications</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu reload_notification">
                                    <?php 
                                   // $sql2 = "SELECT * FROM notitemp ORDER BY `created_at` DESC";
                                   //  $query2 = $db->ReadData($sql2);
                                        foreach ($query2 as $key => $value) {
                                            $classview = "";
                                            if($value['isViewed'] == '0'){
                                                $classview  = "notyetview";
                                            }else{
                                                $classview  = "viewednotif";
                                            }
                                            ?>
                                                 <li id="<?php echo  $value['jobid'];?>" data-subid="<?php echo  $value['notif_id'];?>" class="clicknotif <?php echo $classview;?>">
                                                    <a href="#">
                                                        <i class="iconnotif glyphicon glyphicon-envelope "></i><b><?php echo  $value['user'] . " </b>"  . $value['status_type'] ;?>
                                                    </a>
                                                </li>
                                            <?php 
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <li class="footer"><a href="<?php echo SITE_URL;?>head_office/notification.php">View all</a></li>
                            </ul>
                        </li>
                        <script type="text/javascript">
                        $(function(){
                            $(document).on('click','.clicknotif',function(){
                                var id = $(this).attr('id');
                                var subid = $(this).attr('data-subid');
                                window.location.href = '<?php echo SITE_URL;?>head_office/notifview.php?jobid='+ id + '&&subid=' + subid;
                            });
                        });
                        </script>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php if($query[0]['image']): ?><img src="<?php echo $image ?>" class="img-circle profilethumb"><?php endif;?>
                                <?php if(!$query[0]['image']): ?><i class="glyphicon glyphicon-user"></i><?php endif;?>
                                <span><?php echo $_SESSION['nicknake']; ?>  <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img class="jblogoadmin" src="<?php echo SITE_IMAGES_DIR ?>logo2.png">
                                    <p>
                                       <?php echo $_SESSION['name']; ?><p>
                                        <small>Member since 
                                        <small><?php 
                                         echo $_SESSION['created_at'];
                                          ?></small></small>
                                    </p>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <!-- <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div> -->
                                    <div class="pull-right">
                                        <a href="<?php echo SITE_URL?>head_office/profile.php" class="btn btn-default btn-flat">Profile</a>
                                        <a href="../ajax/logout.php" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
    <?php
}

function sidebarHeader(){
    ?><!-- 
        <div class="user-panel company">
            <div class="companylogo">
                <img src="http://localhost/site/resources/img/logo2.png">
            </div>
        </div> -->
        <div class="user-panel">
            <div class="pull-left image">
            </div>
            <div class="pull-left info">
                <p>Hello, <?php echo $_SESSION['nicknake']; ?></p>

                <a href="#"><i class="fa fa-star text-success"></i><?php echo $_SESSION['job_title']; ?></a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="ssdorm">
            <div class="input-group">
                <br>
            </div>
        </form>
    <?php 
}
function sidebarMenu(){
     global $db;
    # Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

    ?>
        <ul class="sidebar-menu">
            <?php if($_SESSION['position'] == -1): ?>
                <li class="active">
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="joborders.php">
                        <i class="fa fa-th"></i> <span>Job Orders</span> 
                        <small class="badge pull-right bg-green">
                            <?php echo $_SESSION['jobcount']; ?>
                        </small>
                    </a>
                </li>
                <li>
                    <a href="soa.php">
                        <i class="fa fa-edit"></i> <span>Statements of Account</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="branch.php">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Branch</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="branch.php"><i class="fa fa-angle-double-right"></i>Branch</a></li>
                        <li><a href="revenue.php"><i class="fa fa-angle-double-right"></i>Branch Revenue</a></li>
                    </ul>
                </li>
                <li>
                    <a href="customers.php">
                        <i class="fa fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a href="technicians.php">
                        <i class="fa fa-laptop"></i>
                        <span>Technicians</span>
                    </a>
                </li>
                <li>
                    <a href="parts.php">
                        <i class="fa fa-table"></i> <span>Parts</span>
                    </a>
                </li>
                <li>
                    <a href="salesreport.php">
                        <i class="fa fa-rouble"></i>
                        <span>Sales Report</span>
                    </a>
                </li>
                
                <li>
                    <a href="settings.php">
                        <i class="fa fa-cog"></i> <span>Settings</span>
                    </a>
                   
                </li>
                <li>
                    <a href="history.php">
                        <i class="fa fa-info-circle"></i> <span>History</span>
                    </a>
                   
                </li>
            <?php else: ?>
                <?php foreach($permission AS $value): ?>
                <?
                    if($value['name'] == 'job_orders') {
                        $job_orders = true;
                    }

                    if($value['name'] == 'statements_of_account') {
                        $soa = true;
                    }

                    if($value['name'] == 'branch') {
                        $branch = true;
                    }

                    if($value['name'] == 'customers') {
                        $customers = true;
                    }

                    if($value['name'] == 'technicians') {
                        $tech = true;
                    }

                    if($value['name'] == 'parts') {
                        $parts = true;
                    }

                    if($value['name'] == 'sales_report') {
                        $sales = true;
                    }
                ?>
                <?php endforeach; ?>
                <li class="active">
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <?php if (isset($job_orders)): ?>
                <li>
                    <a href="joborders.php">
                        <i class="fa fa-th"></i> <span>Job Orders</span> 
                        <small class="badge pull-right bg-green">
                            <?php echo $_SESSION['jobcount']; ?>
                        </small>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($soa)): ?>
                <li>
                    <a href="soa.php">
                        <i class="fa fa-edit"></i> <span>Statements of Account</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($branch)): ?>
                <li class="treeview">
                    <a href="branch.php">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Branch</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="branch.php"><i class="fa fa-angle-double-right"></i>Branch</a></li>
                        <li><a href="revenue.php"><i class="fa fa-angle-double-right"></i>Branch Revenue</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (isset($customers)): ?>
                <li>
                    <a href="customers.php">
                        <i class="fa fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($tech)): ?>
                <li>
                    <a href="technicians.php">
                        <i class="fa fa-laptop"></i>
                        <span>Technicians</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($parts)): ?>
                <li>
                    <a href="parts.php">
                        <i class="fa fa-table"></i> <span>Parts</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($sales)): ?>
                <li>
                    <a href="salesreport.php">
                        <i class="fa fa-rouble"></i>
                        <span>Sales Report</span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="settings.php">
                        <i class="fa fa-cog"></i> <span>Settings</span>
                    </a>
                   
                </li>
                <li>
                    <a href="history.php">
                        <i class="fa fa-info-circle"></i> <span>History</span>
                    </a>
                   
                </li>
            <?php endif; ?>
        </ul>
    <?php
}

function loginscript(){
    global $url;
    ?>
    <?php
}

function modald(){
        global $url;
        ?>
           <div class="modal fade" id="view-modal2" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> Jor Ordersss  #<span class="idhere"></span>
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
                            <strong>Warranty Card:</strong>
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
                                        <td><span class="span-parts"></span></td>
                                        <td><span class="span-tech"></span></td>
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

                         <button type="button" class="btn  cancel-delet btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         <button type="submit" id="savejob" class="btn btn-success pull-left "><i class="fa fa-plus"></i> OK </button>
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->
        <?php
    }

function viewSOA(){
        global $url;
        ?>
           
        <?php
    }

function createJobForsadm(){
    if($_SESSION['position'] == "1") {
        ?>
        <div class="input-group searchbox ">
            <input type="text"  name="q" class="form-control" placeholder="Search Job ID..."/>
            <span class="input-group-btn">
                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
        </div>
    <div class="createjob">

             <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <td>123432</td>
                            <td>Update software</td>
                            <td><span class="badge bg-green"> . </span></td>
                        </tr>
                        <tr>
                            <td>123432</td>
                            <td>Clean database</td>
                            <td><span class="badge bg-blue"> . </span></td>
                          
                        </tr>
                        <tr>
                            <td>123432</td>
                            <td>Cron job running</td>
                            <td><span class="badge bg-yellow"> . </span></td>
                          
                        </tr>
                        <tr>
                            <td>123432</td>
                            <td>Fix and squish bugs</td>
                            <td><span class="badge bg-red"> . </span></td>
                          
                        </tr>
                    </table>
                </div><!-- /.box-body -->
    </div>

    <?php
    }else {
        ?>
        <form id="createjob" name="createjob" method="post" role="form">
        <div class="box-body box-success">
            <div class="box-header">
                <h3 class="box-title">Personal Informations</h3>
            </div>
            <div class="form-group col-xs-6">
                <label>Customer Name:</label>
                <input type="text" name="name" class="form-control" placeholder="Name ...">
            </div>
            <div class="form-group col-xs-6">
                <label>Phone Number:</label>
                <input type="number" name="number" class="form-control" placeholder="Phone number ...">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="email" name="email" class="form-control" placeholder="Email Address ...">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <input type="text" name="address" class="form-control" placeholder="Address ...">
            </div>
            <div class="box-header">
                <h3 class="box-title">Job Order Informations</h3>
            </div>
            <div class="form-group col-xs-6">
                <label>Item Name:</label>
                <input type="text" name="itemname" class="form-control" placeholder="Item Name ...">
            </div>
            <div class="form-group col-xs-6">
                 <div class="form-group">
                    <label>Date:</label> <input type="date" name="date" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask/>
                </div><!-- /.form group -->
            </div>
            <div class="form-group col-xs-6">
                <label>Diagnosis: </label>
                <textarea class="form-control" name="diagnosis" rows="3" placeholder="Diagnosis ..."></textarea>
            </div>
            <div class="form-group col-xs-6">
                <label>Remarks:</label>
               <textarea class="form-control" name="remarks" rows="3" placeholder="Remarks ..."></textarea>
            </div>
            <div class="form-group col-xs-6">
                <label>Status:</label>
                <input type="text" class="form-control" name="status" placeholder="Status ...">
            </div>

                <div class="clear"></div>

        </div>
         <div class="modal-footer clearfix">
            <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
            <button type="submit" id="savejob" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Submit </button>
        </div>
</form>
        <?php
    }
    
    function selectrecord($message){
        global $url;
        if (!isset($message)) {
            $message = "Please make a selection from the list.";
        }
        ?>
           <div class="modal fade" id="selecrecord-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> <?php echo $message; ?></h4>
                    </div>
                    <div class="modal-body">
                         <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
                    <div class="clear"></div>
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div><!-- /.modal -->
        <?php
    }
    ?>
    
<?php
}

function selectrecord(){
        global $url;
        ?>
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
        <?php
    }
?>

<?php
function createJobForm($option = false, $diagnosis = false){
        ?>
   
        <form id="createjob" class="change_to_edit" name="createjob" method="post" role="form">
        <div class="box-body box-success">
            <div class="form-group col-xs-6">
                <label>Customer:</label>
                <select class="form-control" id="existingc">
                        <option></option>
                        <option value="1">Existing Customer</option>
                        <option value="0">New Customer</option>
                </select>

            </div> 
            <div class="form-group col-xs-6">

            <div class="hide_existing_first">
                <div class="form-group col-xs-12">
                    <label>Search Customer</label>
                  <div class="input-group">
                        <input type="text" class="form-control"  id="search_customers" placeholder="Search Customer Name">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                </div>
                <div class="form-group search-list-result col-xs-12">
                <select multiple class="form-control search-list" name="search-list">
                </select>
            </div>
            </div>
            </div>

            <div class="clear"></div>

            <!-- <div class="hide_existing_first">
                <div class="form-group col-xs-12">
                    <label>Search Customer</label>
                   <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search_customers" placeholder="Search Customer Name">
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-flat" type="button"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group search-list-result col-xs-12">
                <select multiple class="form-control search-list" name="search-list">
                </select>
            </div>
            </div> -->
            <div class="hide_existing_second">

            <div class="box-header">
            </div>
            
            <div class="form-group col-xs-6">
                <label>Customer Name:</label>
                <input type="text" name="name" class="form-control" placeholder="Name ">
            </div>
            <div class="form-group col-xs-6">
                <label>Phone Number:</label>
                <input type="number" name="number" class="form-control" placeholder="Phone number ">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="text" name="email" class="form-control" placeholder="Email Address ">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <input type="text" name="address" class="form-control" placeholder="Address ">
            </div>
            <div class="form-group col-xs-6">
                <div class="form-group">
                    <label>Customer Type</label>
                    <select class="form-control" name="customertype">
                        <option></option>
                        <option value="1">Customer Unit</option>
                        <option value="2">Dealers Unit</option>
                        <option value="3">Branch Unit</option>
                    </select>
                </div>
            </div>

             <div class="form-group col-xs-6">
                <div class="form-group">
                    <label>Under Warranty?</label>
                    <select class="form-control" name="warranty" id="warranty">
                        <option></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="clear"></div>

            <div class="hideshow">
                <div class="form-group col-xs-6">
                    <div class="form-group">
                        <label>Warranty Date</label>
                         <input type="date" name="warranty_date" class="form-control" placeholder="Estimated Finish Date ">
                    </div>
                </div>

                 <div class="form-group col-xs-6">
                    <div class="form-group">
                        <label>Warranty Type</label>
                        <select class="form-control" name="warranty_type">
                            <option></option>
                            <option value="Parts Only">Parts Only</option>
                            <option value="Service Only">Service Only</option>
                            <option value="Parts & Service">Parts & Service</option>
                        </select>
                    </div>
                </div>
            </div>


                <div class="clear"></div>
            <div class="box-header">
                <h3 class="box-title">Job Order Informations</h3>
            </div>
            <div class="form-group col-xs-6">
                <label>Item Name:</label>
                <input type="text" name="itemname" class="form-control" placeholder="Item Name ">
            </div>
            <!-- <div class="form-group col-xs-6"><br>
                <a data-toggle="modal" data-target="#create-payment" class="btn btn-primary" href="#"><i class="fa fa-credit-card"> </i> Add Partial Payment<a/>
            </div> -->
                <div class="clear"></div>
            <div class="form-group col-xs-6">
                <label>Diagnosis: </label>
                 <select class="form-control" name="diagnosis">
                        <option></option>
                        <?php 
                            echo $diagnosis;
                        ?>
                    </select>
                <!-- <textarea class="form-control" name="diagnosis" rows="3" placeholder="Diagnosis "></textarea> -->
            </div>
            <div class="form-group col-xs-6">
                    <label>Estimated Finish Date:</label>
                <input type="date" name="date" class="form-control" placeholder="Estimated Finish Date ">
            </div>
            <div class="form-group col-xs-12">
                <label>Remarks:</label>
               <textarea class="form-control" name="remarks" rows="6" placeholder="Remarks "></textarea>
            </div>

            <div class="form-group  col-xs-6">
                <label>Reference No.:</label>
                <input type="text" name="referenceno" class="form-control" placeholder="Reference No">
            </div>
            <div class="form-group  col-xs-6">
                <label>Service Fee:</label>
                <input type="number" name="servicefee" class="form-control" value="800.00" placeholder="Service Fee">
            </div>
            </div>

        <div class="clear"></div>

        </div>
        <div class="callout callout-info paymentinformation">
            <h4> <i class="fa  fa-credit-card pull-left"></i> Partial Payment Informations</h4>
            <p><b>Partial Payment :</b> <span class="payment-section">123412321</span></p>
            <p><b>Invoice Number :</b> <span class="payment-invoice">SDFJK234234</span></p>
        </div>
         <div class="modal-footer clearfix">
            <button type="submit" id="savejob" class="btn btn-primary pull-right" style="margin-left: 18px;"><i class="fa fa-plus"></i> Submit </button>
            <button type="button" class="btn btnmc pull-right" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
        </div>

</form>

<?php
}

function editjoborderform($option = false, $diagnosis = false){
        ?>
   
        <form id="editjoborder" class="change_to_edit" name="editjoborder" method="post" role="form">
        <div class="box-body box-success">
            <div class="box-header">
                <h3 class="box-title">Personal Informations</h3>
            </div>
            <div class="form-group col-xs-6">
                <label>Customer Name:</label>
                <input type="text" name="ename" data-customer-id="" class="form-control" placeholder="Name ">
            </div>
            <div class="form-group col-xs-6">
                <label>Phone Number:</label>
                <input type="number" name="enumber" class="form-control" placeholder="Phone number ">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="text" name="eemail" class="form-control" placeholder="Email Address ">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <input type="text" name="eaddress" class="form-control" placeholder="Address ">
            </div>
            <div class="form-group col-xs-6">
                <div class="form-group">
                    <label>Customer Type</label>
                    <select class="form-control" name="ecustomertype">
                        <option></option>
                        <option value="1">Customer Unit</option>
                        <option value="2">Dealers Unit</option>
                        <option value="3">Branch Unit</option>
                    </select>
                </div>
            </div>

             <div class="form-group col-xs-6">
                <div class="form-group">
                    <label>Under Warranty?</label>
                    <select class="form-control" name="ewarranty">
                        <option></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="clear"></div>

            <div class="hideshow">
                <div class="form-group col-xs-6">
                    <div class="form-group">
                        <label>Warranty Date</label>
                         <input type="date" name="ewarranty_date" class="form-control" placeholder="Estimated Finish Date ">
                    </div>
                </div>

                 <div class="form-group col-xs-6">
                    <div class="form-group">
                        <label>Warranty Type</label>
                        <select class="form-control" name="ewarranty_type">
                            <option></option>
                            <option value="Parts Only">Parts Only</option>
                            <option value="Service Only">Service Only</option>
                            <option value="Parts and Service">Parts & Service</option>
                        </select>
                    </div>
                </div>
            </div>

                <div class="clear"></div>
            <div class="box-header">
                <h3 class="box-title">Job Order Informations</h3>
            </div>
            <div class="form-group col-xs-6">
                <label>Item Name:</label>
                <input type="text" name="eitemname" class="form-control" placeholder="Item Name ">
            </div>
            <!-- <div class="form-group col-xs-6"><br>
                <a data-toggle="modal" data-target="#create-payment" class="btn btn-primary" href="#"><i class="fa fa-credit-card"> </i> Add Partial Payment<a/>
            </div> -->
                <div class="clear"></div>
            <div class="form-group col-xs-6">
                <label>Diagnosis: </label>
                 <select class="form-control" name="ediagnosis">
                        <option></option>
                        <?php 
                            echo $diagnosis;
                        ?>
                    </select>
            </div>
            <div class="form-group col-xs-6">
                    <label>Estimated Finish Date:</label>
                <input type="date" name="edate" class="form-control" placeholder="Estimated Finish Date ">
            </div>
            <div class="form-group col-xs-12">
                <label>Remarks:</label>
               <textarea class="form-control" name="eremarks" id="eremarks" rows="6" placeholder="Remarks "></textarea>
            </div>


            <div class="form-group  col-xs-6">
                <label>Reference No.:</label>
                <input type="text" name="ereferenceno" class="form-control" placeholder="Reference No">
            </div>
            <div class="form-group  col-xs-6">
                <label>Service Fee:</label>
                <input type="number" name="eservicefee" class="form-control" value="800.00" placeholder="Service Fee">
            </div>


<!--             <div class="form-group col-xs-6">
                <label>Status:</label>
                <input type="text" class="form-control" name="status" placeholder="Status ">
            </div> -->

                <div class="clear"></div>

        </div>
        <div class="callout callout-info paymentinformation">
            <h4> <i class="fa  fa-credit-card pull-left"></i> Partial Payment Informations</h4>
            <p><b>Partial Payment :</b> <span class="payment-section">123412321</span></p>
            <p><b>Invoice Number :</b> <span class="payment-invoice">SDFJK234234</span></p>
        </div>
         <div class="modal-footer clearfix">
            <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
            <button type="submit" id="savejob" class="btn btn-primary pull-left"><i class="fa fa-plus"></i> Submit </button>
        </div>
</form>
<?php } ?>