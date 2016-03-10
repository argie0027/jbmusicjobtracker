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

            if($value['name'] == 'customers') {
                $customers = true;
            }
        }

        if(!isset($customers)) {
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
        }
          $sql2 = "SELECT * FROM notitemp WHERE  branch_id <> '0'  ORDER BY `created_at` DESC";
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
                
              <?php breadcrumps('Customers'); ?>

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
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer Name</th>
                                                <th>Phone Number</th>
                                                <th>Email</th>
                                                <th>Branch</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            if(isset($_GET['daterange'])){
                                                $bydate = split ("to", $_GET['daterange']);
                                                $sql = "SELECT c.*, b.branch_name FROM jb_customer c, jb_branch b WHERE b.branch_id = c.branchid AND c.created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at ASC";
                                            }else{
                                                $sql = "SELECT c.*, b.branch_name FROM jb_customer c, jb_branch b WHERE b.branch_id = c.branchid ORDER BY c.created_at ASC";
                                            }

                                            $queryforexcel = $sql;
                                                $query =$db->ReadData($sql); 
                                                $m = 0;
                                                 foreach ($query as $key => $value) {

                                                    $getStatus = "SELECT repair_status FROM jb_joborder WHERE customerid = '".$value['customerid']."' ORDER BY created_at DESC LIMIT 1";
                                                    $getStatusQuery = $db->ReadData($getStatus); 

                                                    $m++;
                                                    ?>
                                                        <tr id="<?php echo $value['customerid']; ?>" class="clickable">
                                                            <td><?php echo $m; ?></td>
                                                            <td><?php echo $value['name']; ?></td>
                                                            <td><?php echo $value['number']; ?></td>
                                                            <td><?php echo $value['email']; ?></td>
                                                            <td><?php echo $value['branch_name']; ?></td>
                                                            <td><?php if($getStatusQuery) { echo $getStatusQuery[0]['repair_status']; } ?></td>
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

        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete <span id="cusname"></span> as Customer?</h4>
            </div>
            <div class="modal-body text-right">
                 <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                 <button type="submit" id="deleteitem" class="btn btn-danger cancel-delet"><i class="fa fa-plus"></i> Delete </button>
            </div><!-- /.modal-content --> 
            </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- /.modal -->


            <div class="modal fade" id="edit-customer" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Update Customer Information</h4>
                    </div>
                    <div class="modal-body">
                       <form id="editjoborder" class="change_to_edit" name="editjoborder" method="post" role="form">
                        <div class="box-body box-success">
                            <div class="box-header">
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
                                <input type="email" name="eemail" class="form-control" placeholder="Email Address ">
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
                                <div class="clear"></div>

                        </div>
                         <div class="modal-footer clearfix">
                            <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                            <button type="submit" id="savejob" class="btn btn-primary "><i class="fa fa-plus"></i> Submit </button>
                        </div>

                </form>
                    </div>
                </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Create New Customer</h4>
                </div>
                <div class="modal-body">
                   <div class="box-header">
                    </div>
                    <form id="createcustomer" name="createcustomer" method="post" role="form">
                    <div class="form-group col-xs-6">
                        <label>Customer Name:</label>
                        <input type="text" name="name" class="form-control" placeholder="Name ">
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Phone Number:</label>
                        <input type="number" name="number" class="form-control" placeholder="Phone number ">
                    </div>
            <div class="clear"></div>
                    <div class="form-group col-xs-6">
                        <label>Email Address:</label>
                        <input type="email" name="email" class="form-control" placeholder="Email Address ">
                    </div>
                    <div class="form-group col-xs-6">
                        <label>Address:</label>
                        <input type="text" name="address" class="form-control" placeholder="Address ">
                    </div>
            <div class="clear"></div>
                    <div class="form-group col-xs-6">
                        <div class="form-group">
                            <label>Customer Type</label>
                            <select class="form-control" name="customertype">
                                <option></option>
                                <option value="1">Customer Unit</option>
                                <option value="1">Dealers Unit</option>
                                <option value="1">Branch Unit</option>
                            </select>
                        </div>
                    </div>
            <div class="clear"></div>

                    <div class="modal-footer clearfix">
                    <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                    <button type="submit" class="btn btn-primary "><i class="fa fa-plus"></i> Submit </button>
                </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

             <div class="modal fade" id="view-customer" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> <span class="namehere"></span>
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
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Diagnosis</th>
                                        <th>Item</th>
                                        <th>Technician</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                    </tr>                                    
                                </thead>
                                <tbody class="joborders">
                                   
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12">
                           <!--  <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button> -->
                        </div>
                    </div>
                </section><!-- /.content -->
                        <div class="text-right">
                         <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                         </div>
                         <!-- <button type="submit" class="btn btn-success "><i class="fa fa-plus"></i> OK </button> -->
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
                $('.add').remove();

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

                //WHEN YOU CHOOSE RECORD
                $(document).on('click', ".clickable", function() {
                    $(".clickable").removeClass("selected");
                    $(this).addClass("selected");
                    ID = $(this).attr("id");
                    console.log(ID);
                });

                $('#createexcel').on('click', function(){

                    <?php if(isset($_GET['daterange'])) { ?>
                        var daterange = getUrlParameter('daterange').split('to');
                        var filter = $('#example1_filter label input').val();
 
                        if ( filter.length ) {
                            var query = "SELECT c.*, b.branch_name FROM jb_customer c, jb_branch b WHERE ( c.name LIKE '%"+filter+"%' OR c.number LIKE '%"+filter+"%' OR c.email LIKE '%"+filter+"%' OR b.branch_name LIKE '%"+filter+"%' ) AND b.branch_id = c.branchid AND c.created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at ASC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }
     

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=customer&&filename=customer_excel";
                        window.location = page;// you can use window.open also

                    <?php } else { ?>
                        var filter = $('#example1_filter label input').val();
                        
                        if ( filter.length ) {
                            var query = "SELECT c.*, b.branch_name FROM jb_customer c, jb_branch b WHERE ( c.name LIKE '%"+filter+"%' OR c.number LIKE '%"+filter+"%' OR c.email LIKE '%"+filter+"%' OR b.branch_name LIKE '%"+filter+"%' ) AND b.branch_id = c.branchid ORDER BY c.created_at ASC";
                        } else {
                            var query = "<?php echo $queryforexcel; ?>";
                        }

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=customer&&filename=customer_excel";
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
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/customers.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/customers.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );

                                <?php 
                            }
                            ?>
                            <?php
                        }
                    ?>
                   
            }
            );



                $(".add").on('click',function(){
                     $("#add-customer").modal('show');
                });

                $('.view').on('click',function(){
                    if(ID){
                        $(".joborders").html("");
                        $('.modald').fadeIn('fast');
                     $("#view-customer").modal('show');
                    $("#idhere").html(ID);
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewcustomer.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $('.idhere').html(obj.response[0].jobid);
                            var now = moment(obj.response[0].created_at);
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
                            $('.branchcontacthere').html(obj.response[0].contactperson);
                            $('.branchphonehere').html(obj.response[0].branch_number);
                            var s = "";
                            for (var i = 0; i < obj.response2.length; i++) {
                                $(".joborders").append("<tr><td>"+obj.response2[i].jobid+"</td><td>"+obj.response2[i].diagnosis+"</td><td>"+obj.response2[i].item+"</td><td>"+obj.response2[i].name+"</td><td>"+obj.response2[i].remarks+"</td><td>"+obj.response2[i].repair_status+"</td></tr>");
                            };
                        }
                    });
                    
                }else {
                    $("#selecrecord-modal").modal("show");
                }
                   
                });

                $('.edit').on('click',function(){
                    if(ID){
                        $('.modald').fadeIn('fast');
                        $("#edit-customer").modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewcustomer.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $('input[name="name"]').val(obj.response[0].jobid);
                            $('input[name="name"]').attr('data-customer-id', obj.response[0].customerid);
                            // $('input[name="name"]').val(obj.response[0].created_at);
                            $('input[name="ename"]').val(obj.response[0].name);
                            $('input[name="eaddress"]').val(obj.response[0].address);
                            $('input[name="enumber"]').val(obj.response[0].number);
                            $('input[name="eemail"]').val(obj.response[0].email);
                            $('select[name="ecustomertype"]').val(obj.response[0].customer_type_id);
                             customerID  = obj.response[0].customerid;
                             joborderid  = obj.response[0].jobid;
                            
                        }
                    });
                }else {
                    $("#selecrecord-modal").modal("show");
                }
                });

                $('.delete').on('click',function(){
                    if(ID) {
                         $("#delete-modal").modal("show"); 
                         $('#cusname').html($('#example1 #'+ID+' td:nth-child(2)').text());
                    }else {
                        $("#selecrecord-modal").modal("show");
                    }
                });

                $("#deleteitem").on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletecustomer.php',
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

            $("#createcustomer").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                "name":{
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
                "customertype":{
                required: true
                },
                "address":{
                required: true,
                minlength:2
                }
                },
                // Specify the validation error messages
                messages: {
                name:{
                required: "Please provide a Name",
                minlength: "Your password must be at least 2 characters long",
                },
                number:{
                required: "Please provide a number",
                minlength: "Your number must be at least 11 interger long"
                },
                email:{
                required: "Please provide a Email Address",
                minlength: "Your number must be at least 11 interger long"
                },
                address:{
                required: "Please provide a Address",
                minlength: "Your number must be at least 11 interger long"
                },
                customertype:{
                required: "Please make a selection from the list. type"
                }
                },
                submitHandler: function(form) {
                    $('.modald').fadeIn('falst');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/createcustomer.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            name: $("[name=name]").val(),
                            number: $("[name=number]").val(),
                            email: $("[name=email]").val(),
                            address: $("[name=address]").val(),
                            customertype: $("[name=customertype]").val(),
                            branchid: "<?php echo $_SESSION['Branchid']; ?>"
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
            $("#editjoborder").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                "ename":{
                required: true,
                minlength:2
                },
                "enumber":{
                required: true,
                number: true,
                minlength:11
                },
                "eemail":{
                required: true,
                minlength:4
                },
                "ecustomertype":{
                required: true
                },
                "eeaddress":{
                required: true,
                minlength:2
                }
                },
                // Specify the validation error messages
                messages: {
                ename:{
                required: "Please provide a Name",
                minlength: "Your password must be at least 2 characters long",
                },
                enumber:{
                required: "Please provide a number",
                minlength: "Your number must be at least 11 interger long"
                },
                eemail:{
                required: "Please provide a Email Address",
                minlength: "Your number must be at least 11 interger long"
                },
                eaddress:{
                required: "Please provide a Address",
                minlength: "Your number must be at least 11 interger long"
                },
                ecustomertype:{
                required: "Please make a selection from the list. type"
                }
                },
                submitHandler: function(form) {
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/editcustmer.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            name: $("[name=ename]").val(),
                            number: $("[name=enumber]").val(),
                            email: $("[name=eemail]").val(),
                            address: $("[name=eaddress]").val(),
                            customertype: $("[name=ecustomertype]").val(),
                            customerID: customerID,
                            branchid: "<?php echo $_SESSION['Branchid']; ?>"
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