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
        if($_SESSION['position'] == 0 || $_SESSION['position'] == -1 ) {
            $name = "JB Main Office";    
        }else {
            $sql = "SELECT branch_name FROM jb_branch WHERE branch_id = '" .$name. "' ";
             $query = $db->ReadData($sql);
             $name =  $query[0]['branch_name'];
            // $name = $query['branch_name'];
        }   $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
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

              <?php breadcrumps('Branch'); ?>
              <script type="text/javascript">
                $(function(){
                    $('.add').css('display','none');
                    $('.delete').css('display','none');
                    $('.edit').css('display','none');
                });
              </script>
                <!-- Main content -->
                <section class="content">
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
                                                $sql = "SELECT * FROM `jb_branch` WHERE isdeleted = '0' ";
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

            <div class="form-group col-xs-12">
                <label>Branch Name:</label>
                <input type="text" name="branchname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Phone Number:</label>
                <input type="number" name="number" class="form-control" placeholder="Phone number ">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="email" name="email" class="form-control" placeholder="Email Address ">
            </div>
                <div class="clear"></div>

            <h3>Adminitrator Information</h3>

            <div class="form-group col-xs-12">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-12">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="text" name="emailaddress" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Full Name:</label>
                <input type="text" name="fullname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>NickName:</label>
                <input type="text" name="nickname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Contact:</label>
                <input type="text" name="contact" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Job Title:</label>
                <input type="text" name="jobtitle" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <textarea rows="3" name="branchaddress" class="form-control"></textarea>
            </div>

                <div class="clear"></div>


           <div class="modal-footer clearfix">
                <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
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
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Create Branch</h4>
        </div>
        <div class="modal-body">
         <form id="editbranch" name="eeditbranch" method="post" role="form">

            <div class="form-group col-xs-12">
                <label>Branch Name:</label>
                <input type="text" name="ebranchname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Phone Number:</label>
                <input type="number" name="enumber" class="form-control" placeholder="Phone number ">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="email" name="eemail" class="form-control" placeholder="Email Address ">
            </div>
                <div class="clear"></div>

            <h3>Adminitrator Information</h3>

            <div class="form-group col-xs-12">
                <label>Username:</label>
                <input type="text" name="eusername" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-12">
                <label>Password:</label>
                <input type="password" name="epassword" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="text" name="eemailaddress" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Full Name:</label>
                <input type="text" name="efullname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>NickName:</label>
                <input type="text" name="enickname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Contact:</label>
                <input type="text" name="econtact" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Job Title:</label>
                <input type="text" name="ejobtitle" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <textarea rows="3" name="ebranchaddress" class="form-control"></textarea>
            </div>

                <div class="clear"></div>


           <div class="modal-footer clearfix">
                <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                <button type="submit" id="savejob" class="btn btn-primary"><i class="fa fa-plus"></i> Submit </button>
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
                <h4 class="modal-title text-red" ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete Job order No. <span id="idhere2"></span>?</h4>
            </div>
            <div class="modal-body">
                 <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                 <button type="submit" id="deleteitem" class="btn btn-success   "><i class="fa fa-plus"></i> Delete </button>
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
                        <canvas id="canvas" height="450" width="600"></canvas>
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
                            },
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,0.8)",
                                highlightFill : "rgba(151,187,205,0.75)",
                                highlightStroke : "rgba(151,187,205,1)",
                                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                            },
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,0.8)",
                                highlightFill : "rgba(151,187,205,0.75)",
                                highlightStroke : "rgba(151,187,205,1)",
                                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                            },
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,0.8)",
                                highlightFill : "rgba(151,187,205,0.75)",
                                highlightStroke : "rgba(151,187,205,1)",
                                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                            },
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,0.8)",
                                highlightFill : "rgba(151,187,205,0.75)",
                                highlightStroke : "rgba(151,187,205,1)",
                                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
                            },
                            {
                                fillColor : "rgba(151,187,205,0.5)",
                                strokeColor : "rgba(151,187,205,0.8)",
                                highlightFill : "rgba(151,187,205,0.75)",
                                highlightStroke : "rgba(151,187,205,1)",
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
                         <button type="submit" id="savejob" class="btn btn-success pull-left "><i class="fa fa-plus"></i> OK </button>
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
        <script type="text/javascript">
            $(function() { 
                var ID = "";
                //WHEN YOU CHOOSE RECORD
                $(document).on('click', ".clickable", function() {
                    $(".clickable").removeClass("selected");
                    $(this).addClass("selected");
                    ID = $(this).attr("id");
                    console.log(ID);
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

                  $('.view').on('click',function(){

                    if(ID) {
                        location.href="revenueview.php?id=" + ID + "&&month=0";
                        // location.href="testbranchrevenvue.php?id=" + ID + "&&month=0";
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }

                      

                    // if(ID) {
                    //      $.ajax({
                    //     type: 'POST',
                    //     url: '../ajax/viewuserbranch.php',
                    //     data: {
                    //         action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                    //         jobid: ID
                    //     },
                    //     success: function(e){
                    //         
                    //         var obj = jQuery.parseJSON(e);
                    //         $('.branchname').html(" " + obj.response2[0].branch_name);
                    //         $(".ebranchname").html(obj.response2[0].branch_name);
                    //         $(".enumber").html(obj.response2[0].number);
                    //         $(".eemail").html(obj.response2[0].email);
                    //         $(".eusername").html(obj.response[0].username);
                    //         $(".eemailaddress").html(obj.response[0].email);
                    //         $(".efullname").html(obj.response2[0].contactperson);
                    //         $(".enickname").html(obj.response[0].nicknake);
                    //         $(".econtact").html(obj.response2[0].number);
                    //         $(".ejobtitle").html(obj.response[0].job_title);
                    //         $(".ebranchaddress").html(obj.response[0].address);
                    //         $(".name").html(obj.response[0].contactperson);
                    //     }
                    // });

                    //     $("#view-modal").modal("show");
                    // }else {
                    //     $("#selecrecord-modal").modal("show");
                    // }
                }); 



                $('.edit').on('click',function(){
                if(ID){
                    $('#edit-branch').modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewuserbranch.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            var obj = jQuery.parseJSON(e);
                             $("[name=ebranchname]").val(obj.response2[0].branch_name);
                            $("[name=enumber]").val(obj.response2[0].number);
                            $("[name=eemail]").val(obj.response2[0].email);
                            $("[name=eusername]").val(obj.response[0].username);
                            $("[name=eemailaddress]").val(obj.response[0].email);
                            $("[name=efullname]").val(obj.response[0].contactperson);
                            $("[name=enickname]").val(obj.response[0].nicknake);
                            $("[name=econtact]").val(obj.response[0].contact_number);
                            $("[name=ejobtitle]").val(obj.response[0].job_title);
                            $("[name=ebranchaddress]").val(obj.response[0].address);
                            $("[name=efullname]").val(obj.response[0].name);
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
                            }else {

                            }
                        }
                    });
                });




            $("#editbranch").validate({
                    errorElement: 'p',
                    // Specify the validation rules
                    rules: {
                    "ebranchname":{
                    required: true,
                    minlength:2
                    },
                    "enumber":{
                    required: true,
                    number: true,
                    minlength:7
                    },
                    "eemail":{
                    required: true,
                    minlength:4
                    },
                    "ebranchaddress":{
                    required: true
                    },
                    "eusername":{
                    required: true,
                    minlength:1
                    },
                    "epassword":{
                    minlength:1
                    },
                    "eemailaddress":{
                    required: true
                    },
                    "efullname":{
                    required: true,
                    minlength:1
                    },
                    "enickname":{
                    required: true,
                    minlength:1
                    },
                    "econtact":{
                    required: true,
                    minlength:1
                    },
                    "ejobtitle":{
                    required: true,
                    minlength:1
                    }
                    },
                    // Specify the validation error messages
                    messages: {
                    ebranchname:{
                    required: "Please provide a Branch Name",
                    minlength: "Your password must be at least 2 characters long",
                    },
                    enumber:{
                    required: "Please provide a number",
                    minlength: "Your number must be at least 7 interger long"
                    },
                    eemail:{
                    required: "Please provide a Email Address",
                    minlength: "Your number must be at least 4 interger long"
                    },
                    ebranchaddress:{
                    required: "Please provide a Address",
                    minlength: "Your number must be at least 1 interger long"
                    },
                    eusername:{
                    required: "Please make a selection from the list. type"
                    },
                    epassword:{
                    required: "Please provide a password",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    eemailaddress:{
                    required: "Please provide a Date",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    efullname:{
                    required: "Please provide a Diagnosis",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    enickname:{
                    required: "Please select Warranty status."
                    },
                    econtact:{
                    required: "Please provide a Remarks",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    ejobtitle:{
                    required: "Please provide a Remarks",
                    minlength: "Your number must be at least 11 interger long"
                    }
                    },
                    submitHandler: function(form) {
                        $('.modald').fadeIn('slow');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/editbranch.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                branchname: $("[name=ebranchname]").val(),
                                number: $("[name=enumber]").val(),
                                eemail: $("[name=eemail]").val(),
                                branchaddress: $("[name=ebranchaddress]").val(),
                                username: $("[name=eusername]").val(),
                                password: $("[name=epassword]").val(),
                                emailaddress: $("[name=eemailaddress]").val(),
                                fullname: $("[name=efullname]").val(),
                                nickname: $("[name=enickname]").val(),
                                contact: $("[name=econtact]").val(),
                                jobtitle: $("[name=ejobtitle]").val(),
                                id: ID
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



            $("#createbranch").validate({
                    errorElement: 'p',
                    // Specify the validation rules
                    rules: {
                    "branchname":{
                    required: true,
                    minlength:2
                    },
                    "number":{
                    required: true,
                    number: true,
                    minlength:7
                    },
                    "email":{
                    required: true,
                    minlength:4
                    },
                    "branchaddress":{
                    required: true
                    },
                    "username":{
                    required: true,
                    minlength:1
                    },
                    "password":{
                    required: true,
                    minlength:1
                    },
                    "emailaddress":{
                    required: true
                    },
                    "fullname":{
                    required: true,
                    minlength:1
                    },
                    "nickname":{
                    required: true,
                    minlength:1
                    },
                    "contact":{
                    required: true,
                    minlength:1
                    },
                    "jobtitle":{
                    required: true,
                    minlength:1
                    }
                    },
                    // Specify the validation error messages
                    messages: {
                    branchname:{
                    required: "Please provide a Branch Name",
                    minlength: "Your password must be at least 2 characters long",
                    },
                    number:{
                    required: "Please provide a number",
                    minlength: "Your number must be at least 7 interger long"
                    },
                    email:{
                    required: "Please provide a Email Address",
                    minlength: "Your number must be at least 4 interger long"
                    },
                    branchaddress:{
                    required: "Please provide a Address",
                    minlength: "Your number must be at least 1 interger long"
                    },
                    username:{
                    required: "Please make a selection from the list. type"
                    },
                    password:{
                    required: "Please provide a Item Name",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    emailaddress:{
                    required: "Please provide a Date",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    fullname:{
                    required: "Please provide a Diagnosis",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    nickname:{
                    required: "Please select Warranty status."
                    },
                    contact:{
                    required: "Please provide a Remarks",
                    minlength: "Your number must be at least 11 interger long"
                    },
                    jobtitle:{
                    required: "Please provide a Remarks",
                    minlength: "Your number must be at least 11 interger long"
                    }
                    },
                    submitHandler: function(form) {
                        $('.modald').fadeIn('slow');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/createbranch.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                branchname: $("[name=branchname]").val(),
                                number: $("[name=number]").val(),
                                email: $("[name=email]").val(),
                                branchaddress: $("[name=branchaddress]").val(),
                                username: $("[name=username]").val(),
                                password: $("[name=password]").val(),
                                emailaddress: $("[name=emailaddress]").val(),
                                fullname: $("[name=fullname]").val(),
                                nickname: $("[name=nickname]").val(),
                                contact: $("[name=contact]").val(),
                                jobtitle: $("[name=jobtitle]").val()
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


            });
        </script>
<?php
    htmlFooter('dashboard');
?>