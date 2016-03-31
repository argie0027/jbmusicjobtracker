<?php
    include '../../include.php';
    include '../ui_main.php';
    htmlHeader('dashboard');
    global $url;
    $queryforexcel = "";
?>
<!-- header logo: style can be found in header.less -->
<?php 
	$name = $_SESSION['Branchid'];

	if ($_SESSION['position'] == 0 || $_SESSION['position'] == -1) {
		$name = "JB Main Office";    
	} else {
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

<div class="modald"><img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif"></div>

<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <?php sidebarHeader(); ?>
		
			<?php 
	            $qu = "";
	            $headertitle = "";

			    if(isset($_GET['daterange'])){
                    $bydate = split ("to", $_GET['daterange']);
                    $qu = "SELECT * FROM `jb_history` WHERE  created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' ORDER BY created_at DESC";
                    $query = $db->ReadData($qu);
                    sidebarMenu($db->GetNumberOfRows());
                    $headertitle = "Job Order";
			    }else{

		    		$qu = "SELECT * FROM `jb_history` ORDER BY created_at DESC";
					$query = $db->ReadData($qu);
					sidebarMenu($db->GetNumberOfRows());
					$headertitle = "Job Order";
		        }
		        $queryforexcel = $qu;
		        $queryforexcel = str_replace("+", "~~", $queryforexcel);
			?>
        </section>
        <!-- /.sidebar -->
    </aside>
    <script type="text/javascript">
    $(function(){
        $(".edit").css('display','none');
        $(".add").css('display','none');
        $(".delete").css('display','none');
    });
    </script>
    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
      <?php breadcrumps('History'); ?>
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
                                        <th>Info</th>
                                        <th>Description</th>
                                        <th>Branch</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($query as $key => $value) {

                                        $return = "<tr id='".$value['id']."' class='clickable'>
                                       			 	<td>".$value['jobnumber']."</td>
                                                    <td>".$value['description']."</td>
                                                    <td>".$value['branch']."</td>
                                                    <td>".$value['name']."</td>
                                                    <td>".date_format(date_create($value['created_at']), "F j, Y")."</td>";
                                        $return = $return . "</tr>";
                                        echo $return;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->

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
                                <i class="fa fa-globe"></i> Job Order  #<span class="idhere"></span>
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
                                <strong>Is Under Warranty: </strong><span class="isunder_warranty"></span><br>

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
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Branch</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="span-description"></span></td>
                                        <td><span class="span-branch"></span></td>
                                        <td><span class="span-name"></span></td>
                                        <td><span class="span-date"></span></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                  
                    <div class="row no-print">
                        <div class="col-xs-12">
                            
                        </div>
                    </div>
                </section><!-- /.content -->

                         <button type="button" class="btn  cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Close </button>  
                         
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

        <?php 
            modald();
        ?>
        <script type="text/javascript">
              $(function() {    
                var ID = "";
                var parts;
                var partscount = 0;
                var techname;
                var partsString;
                var partsStringdummy;

                var partsID;
                var techID;
                
                var totalpartscost, servicesharges, totalcharges, lessdeposit, lessdiscount;
                var what = "";
                var partcost;
                var partslisttemp = "";
                var emailaddress  = "";

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
                    <?php 

                        if(isset($_GET['daterange'])){
                            ?>
                                var daterange = getUrlParameter('daterange').split('to');
                                var filter = $('#example1_filter label input').val();

                                if ( filter.length ) {
                                    var query = "SELECT * FROM `jb_history` WHERE jobnumber LIKE '%"+filter+"%' OR description LIKE '%"+filter+"%' OR branch LIKE '%"+filter+"%' OR name LIKE '%"+filter+"%' AND created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }
                                
                                query = query.replace(/%/g,"percentage");
                                var page = '../ajax/generateexcelhistory.php?querytogenerate='+query+"&&filename=joborderhistory_excel";
                                window.location = page;// you can use window.open also

                            <?php

                        }else{

                            ?>
                                var filter = $('#example1_filter label input').val();

                                if ( filter.length ) {
                                    var query = "SELECT * FROM `jb_history` WHERE jobnumber LIKE '%"+filter+"%' OR description LIKE '%"+filter+"%' OR branch LIKE '%"+filter+"%' OR name LIKE '%"+filter+"%' ORDER BY created_at DESC";
                                } else {
                                    var query = "<?php echo $queryforexcel; ?>";
                                }

                                query = query.replace(/%/g,"percentage");
                                var page = '../ajax/generateexcelhistory.php?querytogenerate='+query+"&&filename=joborderhistory_excel";
                                window.location = page;// you can use window.open also

                            <?php

                        }
                    ?>
                    
                });



                function myFunction(a, b, c, d, e) {
                    a = parseFloat(a);
                    b = parseFloat(b);
                    c = parseFloat(c);
                    d = parseFloat(d);
                    e = parseFloat(e);
                    $('.balancecharge').html((a + b + c)-(d + e));
                }

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
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/history.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/history.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59'));
                                console.log(start.format('YYYY-MM-D 00:00:00'), end.format('YYYY-MM-D 23:59:59'));
                                <?php 
                            }
                            ?>
                            <?php
                        }
                    ?>
                   
            }
            );

                //WHEN YOU CHOOSE RECORD
                $(document).on('click', ".clickable", function() {
                    $(".clickable").removeClass("selected");
                    $(this).addClass("selected");
                    ID = $(this).attr("id");
                    console.log(ID);
                });

                $('.view').on('click',function(){

                    $('.ctypehere').html('');
                    $('.branchnamehere').html('');
                    $('.branchaddresshere').html('');
                    $('.branchcontacthere').html('');
                    $('.branchphonehere').html('');

                    if(ID){
                    $('.modald').fadeIn('fast');
                    $("#view-modal2").modal("show");
                       $.ajax({
                        type: 'POST',
                        url: '../ajax/viewjobhistory.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            id: ID
                        },
                        success: function(e){
                            

                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);

                            var now = moment(obj.response2[0].created_at);
                            $('.idhere').html(obj.response[0].jobid);
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
                            $('.branchcontacthere').html(obj.response[0].contact_person);
                            $('.branchphonehere').html(obj.response[0].branch_number);

                            $('.span-description').html(obj.response2[0].description);
                            $('.span-branch').html(obj.response2[0].branch);
                            $('.span-name').html(obj.response2[0].name);
                            $('.span-date').html(now.format("MMMM D, YYYY"));

                        }

                    });
                    }else{
                        $('#selecrecord-modal').modal('show');
                    }
                });
        
            });
        </script>

<?php
    htmlFooter('dashboard');
?>