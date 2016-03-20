<?php
    include '../../include.php';
    include '../ui_main.php';
    htmlHeader('dashboard');
    global $url;

    # Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

    if($_SESSION['position'] != -1) {
        foreach ($permission as $key => $value) {

            if($value['name'] == 'branch') {
                $branch = true;
            }

        }

        if(!isset($branch)) {
            echo '<script>window.location = "dashboard.php";</script>';
            exit();
        }
    }

?>
<!-- header logo: style can be found in header.less -->
       <?php 
 $name = $_SESSION['Branchid'];
        if($_SESSION['position'] == 0 || $_SESSION['position'] == -1) {
            $name = "JB Main Office";    
        }else {
            $sql = "SELECT branch_name FROM jb_branch WHERE branch_id = '" .$name. "' ";
             $query = $db->ReadData($sql);
             $name =  $query[0]['branch_name'];
            // $name = $query['branch_name'];
        }    $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
        $query2 = $db->ReadData($sql2);

        $counterviewed = "SELECT * FROM notitemp WHERE  branch_id <> '0' AND isViewed <> '1' ORDER BY `created_at` DESC";
        $counterviewed = $db->ReadData($counterviewed);

        headerDashboard($name, $query2, count($counterviewed)); ?>

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

              <?php breadcrumps('Branch'); ?>
              
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
                                                <th>Branch ID</th>
                                                <th>Branch Name</th>
                                                <th>Total Jobs</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                                if(isset($_GET['daterange'])){
                                                    $bydate = split ("to", $_GET['daterange']);
                                                    $sql = "SELECT * FROM `jb_branch` WHERE isdeleted = '0' AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'  ORDER BY created_at ASC";
                                                }else{
                                                    $sql = "SELECT * FROM `jb_branch` WHERE isdeleted = '0'";
                                                }

                                                $queryforexcel = $sql;

                                                $query =$db->ReadData($sql); 
                                                foreach ($query as $key => $value) {

                                                    $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.jobclear = 0 AND a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.branchid = '".$value['branch_id']."' AND a.isdeleted = '0'  ORDER BY created_at DESC";
                                                    $getcountjob = $db->ReadData($qu);
                                                    $jobcount = $db->GetNumberOfRows();

                                                    $selecttechvalue = "SELECT SUM(a.totalpartscost + a.service_charges + a.total_charges) as total FROM jb_cost a, jb_joborder b WHERE b.jobclear = 0 AND a.jobid = b.jobid AND b.repair_status <> 'Ready for Delivery' AND b.repair_status <> 'Waiting for SOA Approval' AND b.repair_status <> 'Waiting List' AND b.branchid = '".$value['branch_id']."'";;
                                                    $totald =$db->ReadData($selecttechvalue);


                                                    ?>
                                                        <tr id="<?php echo $value['branch_id']; ?>" data-name = "<?php echo $value['branch_name']; ?>" class="clickable">
                                                            <td><?php echo $value['branch_id']; ?></td>
                                                            <td><?php echo $value['branch_name']; ?></td>
                                                            <td><?php echo $jobcount; ?></td>
                                                            <td><?php echo "<b>P </b>" . number_format($totald[0]['total'],2);?></td>
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

    <div class="modal fade" id="create-branch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Create Branch</h4>
        </div>
        <div class="modal-body">
         <form id="createbranch" name="createbranch" method="post" role="form">

            <div class="form-group col-xs-12 form-section">
                <h4 class="title">Profile</h4>
                <div class="clear"></div>

                <div class="form-group col-xs-5">
                    <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                </div>
                <div class="form-group col-xs-5">
                    <input type="text" name="firstname" class="form-control" placeholder="First Name">
                </div>
                <div class="form-group col-xs-2">
                    <input type="text" name="midname" maxlength="1" class="form-control" placeholder="MI">
                </div>
                <div class="form-group col-xs-4">
                    <input type="text" name="nickname" class="form-control" placeholder="Nick Name">
                </div>
                <div class="form-group col-xs-4">
                    <input type="number" name="contact" maxlength="11" class="form-control" placeholder="Contact Number">
                </div>
                <div class="form-group col-xs-4">
                    <input type="email" name="emailaddress" class="form-control" placeholder="Email Address">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text" name="address" class="form-control" placeholder="Address">
                </div>
            </div>

            <div class="form-group col-xs-12 form-section">
                <h4 class="title">Account</h4>
                <div class="clear"></div>

                <div class="form-group col-xs-12">
                    <input type="text" name="username" data-customer-id="" class="form-control" placeholder="Username">
                </div>
                <div class="form-group col-xs-12">
                    <input type="password" name="password" data-customer-id="" class="form-control" placeholder="Password">
                </div>
                <div class="form-group col-xs-12">
                    <input type="password" name="confirmpassword" data-customer-id="" class="form-control" placeholder="Confirm Password">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text" name="jobtitle" class="form-control" placeholder="Job Title">
                </div>
            </div>

            <div class="form-group col-xs-12 form-section">
                <h4 class="title">Branch Info</h4>
                <div class="clear"></div>

                <div class="form-group col-xs-12">
                    <input type="text" name="branchname" class="form-control" placeholder="Branch Name">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text" name="branchaddress" class="form-control" placeholder="Branch Address">
                </div>
                <div class="form-group col-xs-6">
                    <input type="number" name="number" class="form-control" placeholder="Phone Number ">
                </div>
                <div class="form-group col-xs-6">
                    <input type="text" name="email" class="form-control" placeholder="Email Address ">
                </div>
            </div>

            <div class="clear"></div>
            <div class="modal-footer clearfix">
                <button type="button"  class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                <button type="submit" id="savejob" class="btn btn-primary "><i class="fa fa-plus"></i> Submit </button>
            </div>
        </form>
        </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


<div class="modal fade" id="edit-branch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Edit Branch</h4>
        </div>
        <div class="modal-body">
         <form id="editbranch" name="eeditbranch" method="post" role="form">

            <div class="form-group col-xs-12 form-section">
                <h4 class="title">Profile</h4>
                <div class="clear"></div>

                <div class="form-group col-xs-5">
                    <input type="text" name="elastname" class="form-control" placeholder="Last Name">
                </div>
                <div class="form-group col-xs-5">
                    <input type="text" name="efirstname" class="form-control" placeholder="First Name">
                </div>
                <div class="form-group col-xs-2">
                    <input type="text" name="emidname" maxlength="1" class="form-control" placeholder="MI">
                </div>
                <div class="form-group col-xs-4">
                    <input type="text" name="enickname" class="form-control" placeholder="Nick Name">
                </div>
                <div class="form-group col-xs-4">
                    <input type="number" name="econtact" maxlength="11" class="form-control" placeholder="Contact Number">
                </div>
                <div class="form-group col-xs-4">
                    <input type="email" name="eemailaddress" class="form-control" placeholder="Email Address">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text" name="eaddress" class="form-control" placeholder="Address">
                </div>
            </div>

            <div class="form-group col-xs-12 form-section">
                <h4 class="title">Account</h4>
                <div class="clear"></div>

                <div class="form-group col-xs-12">
                    <input type="password" name="enewpassword" data-customer-id="" class="form-control" placeholder="New Password">
                </div>
                <div class="form-group col-xs-12">
                    <input type="password" name="econfirmpassword" data-customer-id="" class="form-control" placeholder="Confirm Password">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text" name="ejobtitle" class="form-control" placeholder="Job Title">
                </div>
            </div>

            <div class="form-group col-xs-12 form-section">
                <h4 class="title">Branch Info</h4>
                <div class="clear"></div>

                <div class="form-group col-xs-12">
                    <input type="text" name="ebranchname" class="form-control" placeholder="Branch Name">
                </div>
                <div class="form-group col-xs-12">
                    <input type="text" name="ebranchaddress" class="form-control" placeholder="Branch Address">
                </div>
                <div class="form-group col-xs-6">
                    <input type="number" name="enumber" class="form-control" placeholder="Phone Number ">
                </div>
                <div class="form-group col-xs-6">
                    <input type="text" name="eemail" class="form-control" placeholder="Email Address ">
                </div>
            </div>

            <div class="clear"></div>
            <div class="modal-footer clearfix">
                <button type="button"  class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard </button>
                <button type="submit" id="savejob" class="btn btn-primary "><i class="fa fa-plus"></i> Submit </button>
            </div>
        </form>
        </div>
    </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="sdf text" ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete <span id="branchnames"></span> as Branch?</h4>
            </div>
            <div class="modal-body text-right">
                 <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button> 
                 <button type="submit" id="deleteitem" class="btn btn-danger cancel-delet "><i class="fa fa-plus"></i> Delete </button>
                 <div class="clear"></div>
            </div><!-- /.modal-content --> 
            </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- /.modal -->
      

      <div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i><span class="branchname"></span>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>

                     <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Branch Name: </strong><span class="ebranchname"></span><br>
                                <strong>Contact number : </strong><span class="enumber"></span><br>
                                <strong>Email Address: </strong><span class="eemail"></span><br>
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            

                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                           <strong>Username: </strong><span class="eusername"></span><br>
                                <strong>Email Address: </strong><span class="eemailaddress"></span><br>
                                <strong>Contact Person: </strong><span class="efullname"></span><br>
                                <strong>Contact Number: </strong><span class="econtact"></span><br>
                                <strong>Address: </strong><span class="ebranchaddress"></span><br>
                    </div><!-- /.row -->
                    </div><!-- /.row -->

                </section><!-- /.content -->
                <section>
                    <div style="width: 100%">
                        <canvas id="canvas" height="250" width="600"></canvas>
                    </div>
                    <script type="text/javascript">
                    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
                        var barChartData = {
                        labels : ["January","February","March","April","May","June","July"],
                        datasets : [
                            {
                                fillColor : "rgba(220,220,220,0.5)",
                                strokeColor : "rgba(220,220,220,0.8)",
                                highlightFill: "rgba(220,220,220,0.75)",
                                highlightStroke: "rgba(220,220,220,1)",
                                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                            }
                        ]
                    }
                    var ctx = document.getElementById("canvas").getContext("2d");
                    window.myBar = new Chart(ctx).Bar(barChartData, {
                        responsive : true
                    });
                    </script>
                </section>
                         <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         <button type="submit" id="savejob" class="btn btn-success  "><i class="fa fa-plus"></i> OK </button>
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

                    <div class="modal fade" id="dubplicate-modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog ">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Username is already existing.</h4>
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
                var name = "";
                //WHEN YOU CHOOSE RECORD
                $(document).on('click', ".clickable", function() {
                    $(".clickable").removeClass("selected");
                    $(this).addClass("selected");
                    ID = $(this).attr("id");
                    name = $(this).attr("data-name");
                    console.log(ID);
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

                        if ( filter.length ) {
                            var query = "SELECT * FROM `jb_branch` WHERE branch_name LIKE '%"+filter+"%' AND created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"'  ORDER BY created_at DESC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }
     
                        query = query.replace(/%/g,"percentage");

                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=branch&&filename=branch_excel";
                        window.location = page;// you can use window.open also

                    <?php } else { ?>
                        var filter = $('#example1_filter label input').val();
                        
                        if ( filter.length ) {
                            var query = "SELECT * FROM `jb_branch` WHERE branch_name LIKE '%"+filter+"%' ORDER BY created_at DESC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }

                        query = query.replace(/%/g,"percentage");
  
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=branch&&filename=branch_excel";
                        window.location = page;// you can use window.open also

                    <?php } ?>
                });

                $('#daterange-btn').daterangepicker({
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
                                        window.location.assign("" + "<?php echo SITE_URL;?>head_office/branch.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                        <?php 
                                    }else{
                                        ?>
                                        window.location.assign("" + "<?php echo SITE_URL;?>head_office/branch.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59'));

                                        <?php 
                                    }
                                    ?>
                                    <?php
                                }
                            ?>
                           
                });

                $('.add').on('click',function(){
                    $('#create-branch').modal('show');

                    $("[name=branchname]").val(""); 
                    $("[name=number]").val(""); 
                    $("[name=email]").val(""); 
                    $("[name=username]").val(""); 
                    $("[name=emailaddress]").val(""); 
                    $("[name=fullname]").val(""); 
                    $("[name=nickname]").val(""); 
                    $("[name=contact]").val(""); 
                    $("[name=jobtitle]").val(""); 
                    $("[name=branchaddress]").val(""); 
                    $("[name=fullname]").val(""); 

                });

                 $('.delete').on('click',function(){
                    if(ID) {
                        $("#delete-modal").modal('show');
                        $("#branchnames").html(name);
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }
                }); 

                  $('.view').on('click',function(){
                     if(ID) {
                        location.href="revenueview.php?id=" + ID + "&&month=0";
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }
                }); 

                $('.edit').on('click',function(){
                if(ID){
                    $('.modald').fadeIn('fast');
                    $('#edit-branch').modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewuserbranch.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $("[name=ebranchname]").val(obj.response2[0].branch_name);
                            $("[name=ebranchaddress]").val(obj.response2[0].address);
                            $("[name=enumber]").val(obj.response2[0].number);
                            $("[name=eemail]").val(obj.response2[0].email);
                            $("[name=efirstname]").val(obj.response[0].firstname);
                            $("[name=emidname]").val(obj.response[0].midname);
                            $("[name=elastname]").val(obj.response[0].lastname);
                            $("[name=enickname]").val(obj.response[0].nicknake);
                            $("[name=econtact]").val(obj.response[0].contact_number);
                            $("[name=eemailaddress]").val(obj.response[0].email);
                            $("[name=eaddress]").val(obj.response[0].address);
                            $("[name=ejobtitle]").val(obj.response[0].job_title);
                        }
                    });
                }else {
                    $("#selecrecord-modal").modal("show");
                }
                });




                $("#deleteitem").on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletebranch.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        jobid: ID
                    },
                        success: function(e){
                                
                            if(e == "success"){
                                $("#delete-modal").modal('hide');
                               $("#" + ID).remove();
                               ID = "";
                               location.reload();
                            }else {

                            }
                        }
                    });
                });




            $("#editbranch").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                    "ejobtitle":{
                        required: true,
                        minlength:5
                    },
                    "efirstname":{
                        required: true,
                        minlength:2
                    },
                    "elastname":{
                        required: true,
                        minlength:2
                    },
                    "emidname":{
                        required: true,
                        minlength:1
                    },
                    "enickname":{
                        required: true,
                        minlength:2
                    },
                    "econtact":{
                        required: true,
                        number: true,
                        minlength:7,
                        maxlength: 11
                    },
                    "eemailaddress":{
                        email: true,
                        required: true,
                        minlength:4
                    },
                    "eaddress":{
                        required: true,
                        minlength:2
                    },
                    "ebranchname":{
                        required: true,
                        minlength:2
                    },
                    "eemail":{
                        email: true,
                        required: true,
                        minlength: 4
                    },
                    "enumber":{
                        required: true,
                        number: true,
                        minlength:7,
                        maxlength: 11
                    },
                    "ebranchaddress":{
                        required: true,
                        minlength:2
                    }
                },
                // Specify the validation error messages
                messages: {
                    ejobtitle:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a job title.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your job title must be at least 5 characters long."
                    },
                    efirstname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a firstname.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your firstname must be at least 2 characters long.",
                    },
                    elastname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a lastname.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your lastname must be at least 2 characters long.",
                    },
                    emidname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a MI."
                    },
                    enickname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a nickname.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your nickname must be at least 2 characters long."
                    },
                    eemail:{
                        email: "<i class='fa fa-warning opacity-icon'></i> Error: Email is invalid.",
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a email address",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your email address must be at least 4 characters long"
                    },
                    econtact:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a number.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your number must be at least 11 interger long."
                    },
                    eaddress:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a address.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your address must be at least 2 characters long."
                    },
                    ebranchname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a branch name",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your branch name must be at least 2 characters long",
                    },
                    eemailaddress:{
                        email: "<i class='fa fa-warning opacity-icon'></i> Error: Email is invalid.",
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a email address.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your email must be at least 4 characters long."
                    },
                    enumber:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a number.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your number must be at least 11 interger long."
                    },
                    ebranchaddress:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a address.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your address must be at least 2 characters long."
                    }
                },
                submitHandler: function(form) {
                    var password = $('input[name="enewpassword"]').val();
                    var confirmpassword = $('input[name="econfirmpassword"]').val();
                    var error = 0;

                    if( password.length ) {
                        if ( password.length < 8 ) {
                            $('input[name="enewpassword"]').parent().find('p.error').remove();
                            $('input[name="enewpassword"]').parent().append('<p for="enewpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Your new passsword must be at least 8 characters long.</p>');

                            error = 1;
                        }

                        if( confirmpassword.length < 8 ) {
                            $('input[name="econfirmpassword"]').parent().find('p.error').remove();
                            $('input[name="econfirmpassword"]').parent().append('<p for="econfirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Your confirm passsword must be at least 8 characters long.</p>');

                            error = 1;
                        }
                    }

                    if ( password.length >= 8 && confirmpassword.length >= 8 ) {

                        if ( password.indexOf(' ') >= 0 ) {
                            $('input[name="enewpassword"]').parent().find('p.error').remove();
                            $('input[name="enewpassword"]').parent().append('<p for="enewpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Password must not contain spaces.</p>');
                            error = 1;
                        }

                        if ( confirmpassword.indexOf(' ') >= 0 ) {
                            $('input[name="econfirmpassword"]').parent().find('p.error').remove();
                            $('input[name="econfirmpassword"]').parent().append('<p for="econfirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Confirm password must not contain spaces.</p>');
                            error = 1;
                        }

                        if( password != confirmpassword ) {
                            $('input[name="econfirmpassword"]').parent().find('p.error').remove();
                            $('input[name="econfirmpassword"]').parent().append('<p for="econfirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Confirm password is incorrect.</p>');
                            error = 1;
                        }
                    }

                    if( error == 0 ) {

                        $.ajax({
                            type: 'POST',
                            url: '../ajax/editbranch.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                branchname: $("[name=ebranchname]").val(),
                                number: $("[name=enumber]").val(),
                                email: $("[name=eemail]").val(),
                                branchaddress: $("[name=ebranchaddress]").val(),
                                firstname: $("[name=efirstname]").val(),
                                lastname: $("[name=elastname]").val(),
                                midname: $("[name=emidname]").val(),
                                nickname: $("[name=enickname]").val(),
                                contact: $("[name=econtact]").val(),
                                emailaddress: $("[name=eemailaddress]").val(),
                                address: $("[name=eaddress]").val(),
                                password: $("[name=enewpassword]").val(),
                                jobtitle: $("[name=ejobtitle]").val(),
                                id: ID
                            },
                            success: function(e){
                                var obj = jQuery.parseJSON(e);
                                $('.modald').fadeIn('slow');

                                if(obj.status == 200){
                                    location.reload();
                                } else {
                                    if(obj.status == 101) {
                                        $('.modald').fadeOut('slow');

                                        if( $.type(obj.firstname) != 'undefined' && obj.firstname == true ) {
                                            $('input[name="efirstname"]').parent().find('p.error').remove();
                                            $('input[name="efirstname"]').parent().append('<p for="efirstname" generated="true" class="error">First Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.lastname) != 'undefined' && obj.lastname == true ) {
                                            $('input[name="elastname"]').parent().find('p.error').remove();
                                            $('input[name="elastname"]').parent().append('<p for="elastname" generated="true" class="error">Last Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.midname) != 'undefined' && obj.midname == true ) {
                                            $('input[name="emidname"]').parent().find('p.error').remove();
                                            $('input[name="emidname"]').parent().append('<p for="emidname" generated="true" class="error">Mid Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.nickname) != 'undefined' && obj.nickname == true ) {
                                            $('input[name="enickname"]').parent().find('p.error').remove();
                                            $('input[name="enickname"]').parent().append('<p for="enickname" generated="true" class="error">Nick Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.branchname) != 'undefined' && obj.branchname == true ) {
                                            $('input[name="ebranchname"]').parent().find('p.error').remove();
                                            $('input[name="ebranchname"]').parent().append('<p for="branchname" generated="true" class="error">Branch name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.emailaddress) != 'undefined' && obj.emailaddress == true ) {
                                            $('input[name="eemailaddress"]').parent().find('p.error').remove();
                                            $('input[name="eemailaddress"]').parent().append('<p for="eemailaddress" generated="true" class="error">Email address is not unique</p>');
                                        }
                                        
                                    }
                                }
                        }
                        });    
                        return false;
                    }
                }
            });



            $("#createbranch").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                    "username":{
                        required: true,
                        minlength:5,
                        maxlength:16
                    },
                    "password":{
                        required: true,
                        minlength:8
                    },
                    "confirmpassword":{
                        required: true,
                        minlength:8
                    },
                    "jobtitle":{
                        required: true,
                        minlength:5
                    },
                    "firstname":{
                        required: true,
                        minlength:2
                    },
                    "lastname":{
                        required: true,
                        minlength:2
                    },
                    "midname":{
                        required: true,
                        minlength:1
                    },
                    "nickname":{
                        required: true,
                        minlength:2
                    },
                    "contact":{
                        required: true,
                        number: true,
                        minlength:7,
                        maxlength: 11
                    },
                    "emailaddress":{
                        email: true,
                        required: true,
                        minlength:4
                    },
                    "address":{
                        required: true,
                        minlength:2
                    },
                    "branchname":{
                        required: true,
                        minlength:2
                    },
                    "email":{
                        email: true,
                        required: true,
                        minlength: 4
                    },
                    "number":{
                        required: true,
                        number: true,
                        minlength:7,
                        maxlength: 11
                    },
                    "branchaddress":{
                        required: true,
                        minlength:2
                    }
                },
                // Specify the validation error messages
                messages: {
                    username:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a username",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your username must be at least 5 characters long.",
                        maxlength: "<i class='fa fa-warning opacity-icon'></i> Error: Username should not exceed 16 characters.",
                    },
                    password:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a password.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your password must be at least 8 characters long.",
                    },
                    confirmpassword:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a confirm password",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your confirm password must be at least 8 characters long",
                    },
                    jobtitle:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a job title.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your job title must be at least 5 characters long."
                    },
                    firstname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a firstname.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your firstname must be at least 2 characters long.",
                    },
                    lastname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a lastname.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your lastname must be at least 2 characters long.",
                    },
                    midname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a MI."
                    },
                    nickname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a nickname.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your nickname must be at least 2 characters long."
                    },
                    email:{
                        email: "<i class='fa fa-warning opacity-icon'></i> Error: Email is invalid.",
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a email address",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your email address must be at least 4 characters long"
                    },
                    contact:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a number.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your number must be at least 11 interger long."
                    },
                    address:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a address.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your address must be at least 2 characters long."
                    },
                    branchname:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a branch name",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your branch name must be at least 2 characters long",
                    },
                    emailaddress:{
                        email: "<i class='fa fa-warning opacity-icon'></i> Error: Email is invalid.",
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a email address.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your email must be at least 4 characters long."
                    },
                    number:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a number.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your number must be at least 11 interger long."
                    },
                    branchaddress:{
                        required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a address.",
                        minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your address must be at least 2 characters long."
                    }
                },
                submitHandler: function(form) {
                    var username = $('input[name="username"]').val();
                    var password = $('input[name="password"]').val();
                    var repeatpasswords = $('input[name="confirmpassword"]').val();
                    var error = 0;

                    if ( password.indexOf(' ') >= 0 ) {
                        $('input[name="password"]').parent().find('p.error').remove();
                        $('input[name="password"]').parent().append('<p for="password" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Password must not contain spaces.</p>');
                        error = 1;
                    }

                    if ( repeatpasswords.indexOf(' ') >= 0 ) {
                        $('input[name="confirmpassword"]').parent().find('p.error').remove();
                        $('input[name="confirmpassword"]').parent().append('<p for="confirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Repeat password must not contain spaces.</p>');
                        error = 1;
                    }

                    if( password != repeatpasswords ) {
                        $('input[name="confirmpassword"]').parent().find('p.error').remove();
                        $('input[name="confirmpassword"]').parent().append('<p for="confirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Confirm Password is incorrect.</p>');
                        error = 1;
                    }

                    if( username.indexOf(' ') >= 0 ) {
                        $('input[name="username"]').parent().find('p.error').remove();
                        $('input[name="username"]').parent().append('<p for="username" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Username must not contain spaces.</p>');
                        error = 1;
                    }

                    if ( error == 0 ) {
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/createbranch.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                branchname: $("[name=branchname]").val(),
                                branchaddress: $("[name=branchaddress]").val(),
                                number: $("[name=number]").val(),
                                email: $("[name=email]").val(),
                                username: $("[name=username]").val(),
                                password: $("[name=password]").val(),
                                jobtitle: $("[name=jobtitle]").val(),
                                firstname: $("[name=firstname]").val(),
                                lastname: $("[name=lastname]").val(),
                                midname: $("[name=midname]").val(),
                                nickname: $("[name=nickname]").val(),
                                emailaddress: $("[name=emailaddress]").val(),
                                contact: $("[name=contact]").val(),
                                address: $("[name=branchaddress]").val()
                            },
                            success: function(e){
                                var obj = jQuery.parseJSON(e);
                                $('.modald').fadeIn('slow');

                                if(obj.status == 200){
                                    location.reload();
                                } else {
                                    if(obj.status == 101) {
                                        $('.modald').fadeOut('slow');

                                        if( $.type(obj.username) != 'undefined' && obj.username == true ) {
                                            $('input[name="username"]').parent().find('p.error').remove();
                                            $('input[name="username"]').parent().append('<p for="username" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Username is not unique.</p>');
                                        }

                                        if( $.type(obj.branchname) != 'undefined' && obj.branchname == true ) {
                                            $('input[name="branchname"]').parent().find('p.error').remove();
                                            $('input[name="branchname"]').parent().append('<p for="branchname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Branch name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.firstname) != 'undefined' && obj.firstname == true ) {
                                            $('input[name="firstname"]').parent().find('p.error').remove();
                                            $('input[name="firstname"]').parent().append('<p for="firstname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: First Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.lastname) != 'undefined' && obj.lastname == true ) {
                                            $('input[name="lastname"]').parent().find('p.error').remove();
                                            $('input[name="lastname"]').parent().append('<p for="lastname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Last Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.midname) != 'undefined' && obj.midname == true ) {
                                            $('input[name="midname"]').parent().find('p.error').remove();
                                            $('input[name="midname"]').parent().append('<p for="midname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Mid Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.nickname) != 'undefined' && obj.nickname == true ) {
                                            $('input[name="nickname"]').parent().find('p.error').remove();
                                            $('input[name="nickname"]').parent().append('<p for="nickname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Nick Name must not contain special characters</p>');
                                        }

                                        if( $.type(obj.emailaddress) != 'undefined' && obj.emailaddress == true ) {
                                            $('input[name="emailaddress"]').parent().find('p.error').remove();
                                            $('input[name="emailaddress"]').parent().append('<p for="emailaddress" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Email address is not unique.</p>');
                                        }
                                        
                                    }
                                }

                            }
                        });
                    }
                    return false;
                }
            });
            
            $('.discard').click( function(){
                $('input').val('');
                $('p.error').remove();
            });

            });
        </script>
<?php
    htmlFooter('dashboard');
?>