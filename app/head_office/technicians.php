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

            if($value['name'] == 'technicians') {
                $tech = true;
            }
        }

        if(!isset($tech)) {
            echo '<script>window.location = "dashboard.php";</script>';
            exit();
        }
    }
    $queryforexcel = "";
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
            }   $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
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
              <?php breadcrumps('Technicians'); ?>
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
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Current Tasks</th>
                                                <th>Total Earnings</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if(isset($_GET['daterange'])){
                                                    $bydate = split ("to", $_GET['daterange']);
                                                    $sql = "SELECT * FROM `jb_technicians` WHERE created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' AND tech_id <> 1 AND isdeleted <> 1";
                                                }else{
                                                    $sql = "SELECT * FROM `jb_technicians` WHERE tech_id <> 1 AND isdeleted <> 1";
                                                }
                                                $queryforexcel = $sql;
                                                $query =$db->ReadData($sql); 
                                                 foreach ($query as $key => $value) {

                                                    $query  ="SELECT * FROM jb_joborder WHERE technicianid = '".$value['tech_id']."' ORDER BY created_at DESC";
                                                    $query =$db->ReadData($query);
       
                                                    $currenttast = "";

                                                    if($query) {
                                                        if($query[0]['repair_status'] != 'Ongoing Repair'){
                                                            $currenttast = "-";
                                                        } else {
                                                            $currenttast =  $query[0]['jobid'] . " (". $query[0]['item'] . ")"; 
                                                        }
                                                    } else {
                                                        $currenttast = "-";
                                                    }

                                                    $selecttechvalue = "SELECT SUM(a.service_charges + a.totalpartscost + a.total_charges - a.less_deposit - a.less_discount ) as total, b.repair_status FROM jb_cost a, jb_joborder b WHERE b.isdeleted = 0 AND b.jobclear = 0 AND a.jobid = b.jobid AND b.technicianid = '".$value['tech_id']."' AND b.repair_status != 'Waiting for SOA Approval' AND b.repair_status != 'Approved' ";
                                                    $totald =$db->ReadData($selecttechvalue);

                                                    ?>
                                                        <tr id="<?php echo $value['tech_id']; ?>" class="clickable">
                                                            <td><?php echo $value['tech_id']; ?></td>
                                                            <td><?php echo $value['name']; ?></td>
                                                            <td><?php echo $currenttast; ?> </td>
                                                            <td><?php echo "<b>P </b>" . number_format($totald[0]['total'],2);?></td>
                                                            <td>
                                                                <?php
                                                                    if($value['status'] == 1) {
                                                                        ?>   <small class="badge col-centered bg-yellow">Not Available</small>
                                                                        <?php 
                                                                    }else{
                                                                        ?>  <small class="badge col-centered bg-green">Available</small>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php 
                                                }
                                            ?>
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
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Register a Technician</h4>
        </div>
        <div class="modal-body">
         <form id="createtech" name="createtech" method="post" role="form">

            <div class="form-group col-xs-12">
                <label>Technician Name:</label>
                <input type="text" name="techname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="text" name="email" class="form-control" placeholder="Email Address ">
            </div>
            <div class="form-group col-xs-6">
                <label>Contact Number:</label>
                <input type="number" name="number" class="form-control" placeholder="Contact Number ">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <input type="text" name="address" class="form-control" placeholder="Address ">
            </div>
            <div class="form-group col-xs-6">
                <label>Nickname:</label>
                <input type="text" name="nickname" class="form-control" placeholder="Nickname ">
            </div>
            <div class="form-group col-xs-6">
                <label>Date Hired:</label>
                <input type="text" name="datehired" class="form-control sandboxdate" placeholder="Date Hired">
            </div>
            <div class="form-group col-xs-6">
                <label>Technician Status:</label>
                <select class="form-control" name="techstatus">
                    <option></option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
                <div class="clear"></div>
           <div class="modal-footer clearfix">
                <button type="button" class="btn btnmc " data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                <button type="submit" id="savejob" class="btn btn-primary"><i class="fa fa-plus"></i> Submit </button>
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
            <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Edit Technician</h4>
        </div>
        <div class="modal-body">
         <form id="edittech" name="edittech" method="post" role="form">
            <div class="form-group col-xs-12">
                <label>Technician Name:</label>
                <input type="text" name="etechname" class="form-control" placeholder="">
            </div>
            <div class="form-group col-xs-6">
                <label>Email Address:</label>
                <input type="text" name="eemail" class="form-control" placeholder="Email Address ">
            </div>
            <div class="form-group col-xs-6">
                <label>Contact Number:</label>
                <input type="number" name="enumber" class="form-control" placeholder="Contact Number ">
            </div>
            <div class="form-group col-xs-6">
                <label>Address:</label>
                <input type="text" name="eaddress" class="form-control" placeholder="Address ">
            </div>
            <div class="form-group col-xs-6">
                <label>Nickname:</label>
                <input type="text" name="enickname" class="form-control" placeholder="Nickname ">
            </div>
            <div class="form-group col-xs-6">
                <label>Date Hired:</label>
                <input type="text" name="edatehired" class="form-control sandboxdate" placeholder="Date Hired">
            </div>
            <div class="form-group col-xs-6">
                <label>Technician Status:</label>
                <select class="form-control" name="etechstatus">
                    <option></option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
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
            <h4 class="modal-title" ><i class="fa fa-times-circle"> </i> Are you sure you want to delete <span id="techname"></span> as Technician?</h4>
        </div>
        <div class="modal-body text-right">
             <button type="button" class="btn  btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
             <button type="submit" id="deleteitem" class="btn btn-danger cancel-delet"><i class="fa fa-plus"></i> Delete </button>
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
                                <i class="fa fa-globe"></i> Technician View
                            </h2>                            
                        </div><!-- /.col -->
                    </div>

                     <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Technician Name: </strong><span class="etechname"></span><br>
                                <strong>Contact number : </strong><span class="enumber"></span><br>
                                <strong>Email Address: </strong><span class="eemail"></span><br>
                                <strong>Address: </strong><span class="eaddress"></span><br>
                                <strong>Nickname: </strong><span class="enick"></span><br>
                                <strong>Date Hired: </strong><span class="edatehired"></span><br>
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">

                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                           <strong>Status: </strong><span class="estatus"></span><br>
                            <strong>Current Task(Job ID): </strong><span class="ecurrenttasks"></span><br><br>
                            <!-- <strong>Total Earnings: </strong><span class="eearnings">11,520</span><br> -->
                            <table style="font-size: 12px; width: 100%;">
                                <tr>
                                    <th><strong>Summary Total: </strong></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>Earnings:</td>
                                    <td class="text-right"><span class="eearnings">11,520</span></td>
                                </tr>
                                <tr>
                                    <td>Job Orders: </td>
                                    <td class="text-right"><span class="ejoborders">11,520</span></td>
                                </tr>
                                <tr>
                                    <td>Successfully Repaired: </td>
                                    <td class="text-right"><span class="erepaired">11,520</span></td>
                                </tr>
                                <tr>
                                    <td>Can’t Repair </td>
                                    <td class="text-right"><span class="ecantrepair">11,520</span></td>
                                </tr>
                            </table>
                    </div><!-- /.row -->

                </section><!-- /.content -->
                <section>
                     <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">Job Order History</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <table class="table table-bordered tasklist">
                                        
                                    </table>
                                </div><!-- /.box-body -->
                                <!-- <div class="box-footer clearfix">
                                    <ul class="pagination pagination-sm no-margin pull-right">
                                        <li><a href="#">&laquo;</a></li>
                                        <li><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">&raquo;</a></li>
                                    </ul>
                                </div> -->
                            </div><!-- /.box -->
                        
                </section>
                        <div class="text-right">
                         <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Close </button>
                         <button id="generateExcelTechIndivid" class="btn btn-primary cancel-delet"><i class="fa fa-download"></i> Generate Excel </button>
                        </div>
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

             $('#generateExcelTechIndivid').click( function(){
                var query = "SELECT * FROM `jb_technicians` WHERE tech_id = '"+ID+"'";
                query = query.replace(/%/g,"percentage");
                var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=techindividual&&filename=tech_excel";
                window.location = page;// you can use window.open also
             });

             $('#createexcel').on('click', function(){

                <?php if(isset($_GET['daterange'])) { ?>
                    var daterange = getUrlParameter('daterange').split('to');
                    var filter = $('#example1_filter label input').val();

                    if ( filter.length ) {
                        var query = "SELECT * FROM `jb_technicians` WHERE name LIKE '%"+filter+"%' AND created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' AND tech_id <> 1 AND isdeleted <> 1";
                    } else {
                        var query = "<?php echo $queryforexcel; ?>";
                    }
 
                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=tech&&filename=tech_excel";
                    window.location = page;// you can use window.open also

                <?php } else { ?>
                    var filter = $('#example1_filter label input').val();
                    
                    if ( filter.length ) {
                        var query = "SELECT * FROM `jb_technicians` WHERE name LIKE '%"+filter+"%' AND tech_id <> 1 AND isdeleted <> 1";
                    } else {
                        var query = "<?php echo $queryforexcel; ?>";
                    }

                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=tech&&filename=tech_excel";
                    window.location = page;// you can use window.open also

                <?php } ?>

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
                                    window.location.assign("" + "<?php echo SITE_URL;?>head_office/technicians.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                    window.location.assign("" + "<?php echo SITE_URL;?>head_office/technicians.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59'));
                                <?php
                            }
                            ?>
                            <?php
                        }
                    ?>
            }
            );

                function formatNumber (num) {
                    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                }

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
                        $("#techname").html($('#'+ID+' td:nth-child(2)').text());
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }
                }); 

                  $('.view').on('click',function(){

                    if(ID) {
                        
                        $('.tasklist').html('<tr><th style="width: 60px">Job ID</th><th>Item</th><th>Start Repair</th><th>Done Repair</th><th>Cost</th><th style="width: 90px">Cant Repair</th><th style="width: 40px">Status</th></tr>');
                         $.ajax({
                        type: 'POST',
                        url: '../ajax/viewtechfull.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            techid: ID
                        },
                        success: function(e){
                            
                            $('.eearnings,.ejoborders,.erepaired,.ecantrepair').html("0");

                            var obj = jQuery.parseJSON(e);
                            $('.etechname').html(" " + obj.response[0].name);
                            $('.enumber').html(" " + obj.response[0].number);
                            $('.eemail').html(" " + obj.response[0].email);
                            $('.eaddress').html(" " + obj.response[0].address);
                            $('.enick').html(" " + obj.response[0].nickname);
                            $('.edatehired').html(" " + obj.response[0].date_hired);
                            if(obj.response[0].status == '1'){
                                $('.estatus').html('<small class="badge col-centered bg-yellow">Not Available</small>');
                            }else{
                                $('.estatus').html('<small class="badge col-centered bg-green">Available</small>');
                            }
                            $('.ecurrenttasks').html(" " + obj.response2.jobid);
                            var totalearnings = 0;
                             
                            for (var i = 0; i < obj.response3.length; i++) {
                                var total = parseFloat(obj.response3[i].totalpartscost) + parseFloat(obj.response3[i].service_charges) + parseFloat(obj.response3[i].total_charges) - parseFloat(obj.response3[i].less_deposit) - parseFloat(obj.response3[i].less_discount);
                                totalearnings = (obj.response3[i].jobclear == 0) ? totalearnings + total : totalearnings; 

                                var cantRep = (obj.response3[i].jobclear == 0) ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
                                var dateStart = (obj.response3[i].date_start != null) ? obj.response3[i].date_start : '-';
                                var dateDone = (obj.response3[i].date_done != null) ? obj.response3[i].date_done : '-';

                                var colorcode = '';
                                var status = '';
                                var cantrepair = '';
                                var repaired = '';

                                if(obj.response3[i].repair_status == "Ongoing Repair"){
                                    colorcode = 'bg-teal';
                                    status = obj.response3[i].repair_status;
                                } 
                                if(obj.response3[i].repair_status == "Claimed") {
                                    colorcode = 'bg-green';
                                    status = obj.response3[i].repair_status;
                                }
                                if(obj.response3[i].repair_status == "Done-Ready for Delivery"){
                                    colorcode = 'mredilive';
                                    status = 'Ready for Pickup';
                                }
                                if(obj.response3[i].repair_status == "Ready for Claiming"){
                                    colorcode = 'mdone';
                                    status = obj.response3[i].repair_status;
                                }

                                $('.tasklist').append('<tr><td>'+obj.response3[i].jobid+'</td><td>'+obj.response3[i].item+'</td><td>'+dateStart+'</td><td>'+dateDone+'</td><td><b>P </b><span class="number">'+ total +'</span></td><td class="text-center">'+cantRep+'</td><td><span class="badge '+colorcode+'">'+status+'</span></td></tr>');
                            };

                            $('.eearnings').html("<b>P </b> <span class='number'>"+totalearnings+"</span>");
                            $('.number').number( true, 2 );

                            $('.ejoborders').text(obj.response3.length);
                            $('.erepaired').text(obj.response3[0].repaired);
                            $('.ecantrepair').text(obj.response3[0].cantrepair);
                        }
                    });

                        $("#view-modal").modal("show");
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }

                     $('.eearnings').html(totalearnings);
                }); 



                $('.edit').on('click',function(){
                if(ID){
                    $('#edit-branch').modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewtech.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            var obj = jQuery.parseJSON(e);
                            $("[name=etechname]").val(obj.response[0].name);
                            $("[name=eemail]").val(obj.response[0].email);
                            $("[name=enumber]").val(obj.response[0].number);
                            $("[name=eaddress]").val(obj.response[0].address);
                            $("[name=enickname]").val(obj.response[0].nickname);
                            $("[name=edatehired]").val(obj.response[0].date_hired);
                            $("[name=etechstatus] option[value='"+obj.response[0].tech_status+"']").attr('selected', 'selected');
                            console.log(obj.response[0].date_hired);
                        }
                    });
                }else {
                    $("#selecrecord-modal").modal("show");
                }
                });

                $("#deleteitem").on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletetech.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        id: ID
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

                 $("#createtech").validate({
                    errorElement: 'p',
                    // Specify the validation rules
                    rules: {
                        "techname":{
                        required: true
                        },
                        "email":{
                        required: true,
                        email: true
                        },
                        "number":{
                        required: true,
                        number: true,
                        minlength:7,
                        maxlength: 11
                        },
                        "address":{
                        required: true
                        },
                        "nickname":{
                        required: true
                        },
                        "datehired":{
                        required: true
                        },
                        "techstatus":{
                        required: true
                        }
                    },
                    // Specify the validation error messages
                    messages: {
                    techname:{
                    required: "Please provide a technician name"
                    },
                    email:{
                    required: "Please provide a email"
                    },
                    number:{
                    required: "Please provide a number",
                    minlength: "Your number must be at least 7 interger long."
                    },
                    address:{
                    required: "Please provide a address"
                    },
                    nickname:{
                    required: "Please provide nickname"
                    },
                    datehired:{
                    required: "Please provide date hired"
                    },
                    techstatus:{
                    required: "Please select technician status"
                    }
                    },
                    submitHandler: function(form) {
                        $('.modald').fadeIn('slow');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/createtech.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                techname: $("[name=techname]").val(),
                                email: $("[name=email]").val(),
                                number: $("[name=number]").val(),
                                address: $("[name=address]").val(),
                                nickname: $("[name=nickname]").val(),
                                datehired: $("[name=datehired]").val(),
                                techstatus: $("[name=techstatus]").val()
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



                $("#edittech").validate({
                    errorElement: 'p',
                    // Specify the validation rules
                    rules: {
                        "etechname":{
                        required: true
                        },
                        "eemail":{
                        required: true,
                        email: true
                        },
                        "enumber":{
                        required: true,
                        number: true,
                        minlength: 7,
                        maxlength: 11
                        },
                        "eaddress":{
                        required: true
                        },
                        "enickname":{
                        required: true
                        },
                        "edatehired":{
                        required: true
                        },
                        "etechstatus":{
                        required: true
                        }
                    },
                    // Specify the validation error messages
                    messages: {
                    etechname:{
                    required: "Please provide a technician name"
                    },
                    eemail:{
                    required: "Please provide a email"
                    },
                    enumber:{
                    required: "Please provide a number",
                    minlength: "Your number must be at least 7 interger long."
                    },
                    eaddress:{
                    required: "Please provide a address"
                    },
                    enickname:{
                    required: "Please select nickname"
                    },
                    edatehired:{
                    required: "Please provide date hired"
                    },
                    etechstatus:{
                    required: "Please select technician status"
                    }
                    },
                    submitHandler: function(form) {
                        $('.modald').fadeIn('slow');
                        $.ajax({
                            type: 'POST',
                            url: '../ajax/edittech.php',
                            data: {
                                action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                techname: $("[name=etechname]").val(),
                                email: $("[name=eemail]").val(),
                                number: $("[name=enumber]").val(),
                                address: $("[name=eaddress]").val(),
                                nickname: $("[name=enickname]").val(),
                                datehired: $("[name=edatehired]").val(),
                                techstatus: $("[name=etechstatus]").val(),
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



            });
            </script>
<?php
    htmlFooter('dashboard');
?>