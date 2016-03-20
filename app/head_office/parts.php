<?php
include '../../include.php';
include '../ui_main.php';
htmlHeader('dashboard');
global $url;
$queryforexcel ="";

# Permission
    $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
    $permission = $db->ReadData($permission);

    if($_SESSION['position'] != -1) {
        foreach ($permission as $key => $value) {

            if($value['name'] == 'parts') {
                $parts = true;
            }

        }

        if(!isset($parts)) {
            echo '<script>window.location = "dashboard.php";</script>';
            exit();
        }
    }
?>
    <!-- header logo: style can be found in header.less -->
<?php
$name = $_SESSION['Branchid'];
if ($_SESSION['position'] == 0 || $_SESSION['position'] == -1) {
    $name = "JB Main Office";
} else {
    $sql = "SELECT branch_name FROM jb_branch WHERE branch_id = '" . $name . "'";

    $query = $db->ReadData($sql);
    $name = $query[0]['branch_name'];
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

            <?php breadcrumps('Parts'); ?>

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
                                <th>Stock #</th>
                                <th>Part Name</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Brand</th>
                                <th>Main Category</th>
                                <th>Subcategory</th>
                                <th>Stocks</th>
                                <th>Cost</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if(isset($_GET['daterange'])){
                                $bydate = split ("to", $_GET['daterange']);
                                $sql = "SELECT * FROM `jb_part` WHERE isdeleted <> 1 AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."' group by part_id order by created_at ASC";
                            }else{
                                $sql = "SELECT * FROM `jb_part` WHERE isdeleted <> 1 group by part_id order by created_at ASC";
                            }

                            $queryforexcel = $sql;
                            $query = $db->ReadData($sql);
                            foreach ($query as $key => $value) {
                                $sqlmodel = "SELECT m.modelname, m.description, b.brandname, c.category, s.subcategory FROM jb_models m, jb_brands b, jb_partscat c, jb_partssubcat s WHERE m.brandid = b.brandid AND m.cat_id = c.cat_id AND m.sub_catid = s.subcat_id AND modelid = '".$value['modelid']."'";
                                $querymodel = $db->ReadData($sqlmodel);

                                ?>
                                <tr id="<?php echo $value['part_id']; ?>" class="clickable">
                                    <td><?php echo $value['stocknumber']; ?></td>
                                    <td><?php echo $value['name']; ?></td>
                                    <td>
                                        <?php 
                                        if ($querymodel) {
                                            echo $querymodel[0]['modelname'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($querymodel) {
                                            echo $querymodel[0]['description'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($querymodel) {
                                            echo $querymodel[0]['brandname'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($querymodel) {
                                            echo $querymodel[0]['category'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($querymodel) {
                                            echo $querymodel[0]['subcategory'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $sql = "SELECT sum(quantity) as quantity FROM jb_part WHERE part_id = '".$value['part_id']."'";
                                        $queryd = $db->ReadData($sql);
                                        if ($queryd[0]['quantity'] < 5) {
                                            ?>
                                            <small
                                                class="badge col-centered bg-red"><?php
                                                 echo $queryd[0]['quantity']; 
                                                 ?></small>
                                            <?php
                                        } else if ($queryd[0]['quantity'] < 21) {
                                            ?>
                                            <small
                                                class="badge col-centered bg-yellow"><?php
                                                 echo $queryd[0]['quantity']; 
                                                 ?></small>
                                            <?php
                                        } else {
                                            ?>
                                            <small
                                                class="badge col-centered bg-green">
                                                <?php
                                                    echo $queryd[0]['quantity']; 
                                                ?>
                                            </small>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo "<b>P </b>".number_format($value['cost'],2); ?></td>
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

    <div class="modal fade" id="create-parts" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Add Stocks</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-xs-6">
                        <label>Part type:</label>
                        <select class="form-control" name="parttype">
                            <option value=""></option>
                            <option value="1">Existing</option>
                            <option value="2">New Part</option>
                        </select>
                    </div>

                    <div class="clear"></div>
                    <form id="createpart" name="createpart" class=" showifhechoose" method="post" role="form">
                            <div class="form-group col-xs-12">
                                <label>Stock Number:</label>
                                <input type="text" name="stocknumber" class="form-control" placeholder="Stock Number">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Part Name:</label>
                                <input type="text" name="partname" class="form-control" placeholder="Part Name">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Model Name:</label>
                                <select class="form-control" name="models">
                                    <option></option>
                                    <?php
                                    $qu = "SELECT * FROM `jb_models` ORDER BY created_at ASC";
                                    $query = $db->ReadData($qu);
                                    foreach ($query as $key => $value) {
                                        echo "<option value=\"" . $value['modelid'] . "\">" . $value['modelname'] . "</option> ";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="hideshow modeldetails">
                                <div class="form-group col-xs-12">
                                    <label>Description:</label>
                                    <p class="modeldescriptionhere"></p>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label>Brand:</label>
                                    <p class="modelbrandhere"></p>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label>Category:</label>
                                    <p class="modelcategoryhere"></p>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label>Subcategory:</label>
                                    <p class="modelsubcategoryhere"></p>
                                </div>
                            </div>

                            <div class="form-group col-xs-6">
                                <label>Quantity:</label>
                                <input type="number" name="quantity" class="form-control" placeholder="Quantity ">
                            </div>
                            <div class="form-group col-xs-6">
                                <label>Cost:</label>
                                <input type="text" name="cost" class="form-control" placeholder="Cost ">
                            </div>

                        <div class="clear"></div>
                        <div class="modal-footer clearfix">
                            <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                            <button type="submit" id="savejob" class="btn btn-primary "><i class="fa fa-plus"></i> Submit</button>
                        </div>

                    </form>

                    <form id="addexistingproduct" name="addexistingproduct" class="showifhechoose3" method="post" role="form">
                        <div class="form-group col-xs-6 showifhechoose2">
                            <label>Search Part Name:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search_part" placeholder="Search Part Name">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                            <div class="search-list-result-part ">
                                <select multiple class="form-control search-list-part" name="search-list-part">
                                </select>
                            </div>
                            <ul class="col-xs-12 listofparts-beforeadded col-xs-6">
                            </ul>
                        </div>
                        <div class="form-group col-xs-6 showifselected">
                            <label>Quantity:</label>
                            <input type="text" name="quantity2" class="form-control" placeholder="Quantity ">
                        </div>
                        <div class="clear"></div>
                        <div class="modal-footer clearfix">
                            <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i>
                                Discard
                            </button>
                            <button type="submit" id="savejob232" class="btn btn-primary "><i class="fa fa-plus"></i>
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="edit-part" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Edit Stock</h4>
                </div>
                <div class="modal-body">
                    <form id="editpart" name="editpart" method="post" role="form">
                        <div class="form-group col-xs-12">
                            <label>Stock Number:</label>
                            <input type="text" name="estocknumber" class="form-control" placeholder="Stock Number">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Part Name:</label>
                            <input type="text" name="epartname" class="form-control" placeholder="Part Name">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Model Name:</label>

                            <select class="form-control" name="emodels">
                                <option></option>
                                <?php
                                $qu = "SELECT * FROM `jb_models` ORDER BY created_at DESC";
                                $query = $db->ReadData($qu);
                                foreach ($query as $key => $value) {
                                    echo "<option value=\"" . $value['modelid'] . "\">" . $value['modelname'] . "</option> ";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="hideshow emodeldetails">
                            <div class="form-group col-xs-12">
                                <label>Description:</label>
                                <p class="emodeldescriptionhere"></p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label>Brand:</label>
                                <p class="emodelbrandhere"></p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label>Category:</label>
                                <p class="emodelcategoryhere"></p>
                            </div>
                            <div class="form-group col-xs-4">
                                <label>Subcategory:</label>
                                <p class="emodelsubcategoryhere"></p>
                            </div>
                        </div>

                        <div class="form-group col-xs-6">
                            <label>Cost:</label>
                            <input type="text" name="ecost" class="form-control" placeholder="Cost ">
                        </div>
                        <div class="clear"></div>
                        <div class="modal-footer clearfix">
                            <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i>
                                Discard
                            </button>
                            <button type="submit" id="savejob232" class="btn btn-primary "><i class="fa fa-plus"></i>
                                Submit
                            </button>
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
                    <h4 class="modal-title"><i class="fa  fa-times-circle"> </i> Are you sure you want to
                        delete <span id="partname"></span>?</h4>
                </div>
                <div class="modal-body text-right">
                    <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                    <button type="submit" id="deleteitem" class="btn btn-danger cancel-delet"><i class="fa fa-plus"></i> Delete</button>
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
                                    <i class="fa fa-globe"></i> <span class="vpartname"></span>
                                </h2>
                            </div><!-- /.col -->
                        </div>

                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Stocknumber : </strong><span class="vstocknumber"></span><br>
                                    <strong>Model : </strong><span class="vmodel"></span><br>
                                    <strong>Brand : </strong><span class="vbrand"></span><br>
                                </address>
                            </div><!-- /.col -->

                            <div class="col-sm-4 invoice-col"></div>

                            <div class="col-sm-4 invoice-col">
                                <strong>Main Category: </strong><span class="vcategory"></span><br>
                                <strong>Subcategory : </strong><span class="vsubcategory"></span><br>
                            </div><!-- /.row -->
                        </div>

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <table class="table table-striped vparts">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Batch Date</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Under Warranty</th>
                                            <th class="text-center">Batch Quantity</th>
                                            <th class="text-center">Cost</th>
                                            <th class="text-center">Total Price</th>
                                            <th style="width:70px" class="text-center"></th>
                                        </tr>                                    
                                    </thead>
                                    <tbody></tbody>
                                </table>                            
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                    </section><!-- /.content -->
                </div><!-- /.modal-content -->
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-primary generatebatchpart"><i class="fa fa-plus"></i> Generate Excel</button>
                    <button type="submit" class="btn btn-success cancel-delet" data-dismiss="modal"><i class="fa fa-plus"></i> OK</button>
                </div>
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
                    <center>
                        <button type="submit" id="savejob" class="btn btn-success" data-dismiss="modal"><i
                                class="fa fa-eraser"></i> OK
                        </button>
                    </center>
                    <div class="clear"></div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.modal -->

    <div class="modal fade" id="batchquantity" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body">
                <form id="editpartbatch" method="post" role="form">
                   <div class="form-group">
                        <div class="form-group col-xs-6">
                            <label>Batch Quantity:</label>
                            <input type="number" name="ebbatchquantity" class="form-control" placeholder="Batch Quantity">
                        </div>
                    </div>
                <div class="clear"></div>
                 <div class="form-group col-xs-12">
                     <button type="submit" id="submitdiagnosis" class="btn btn-success pull-left "><i class="fa fa-plus"></i> Submit </button>
                     <button type="button" class="btn btnmc cancel-delet" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                </div><!-- /.modal-content --> 
                <div class="clear"></div>
                </div><!-- /.modal-content --> 
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.modal -->

    <script type="text/javascript">
        $(function () {
            var ID = "";
            var partID = "";
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

              $('#createexcel').on('click', function(){

                <?php if(isset($_GET['daterange'])) { ?>
                    var daterange = getUrlParameter('daterange').split('to');
                    var filter = $('#example1_filter label input').val();

                    if ( filter.length ) {
                        var query = "SELECT* FROM `jb_part` WHERE ( name LIKE '%"+filter+"%' OR stocknumber LIKE '%"+filter+"%' ) AND isdeleted <> 1 AND updated_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' group by part_id";
                    } else {
                        var query = "<?php echo $queryforexcel; ?>";
                    }


                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=parts&&filename=parts_excel";
                    window.location = page;// you can use window.open also

                <?php } else { ?>
                    var filter = $('#example1_filter label input').val();
                    
                    if ( filter.length ) {
                        var query = "SELECT* FROM `jb_part` WHERE ( name LIKE '%"+filter+"%' OR stocknumber LIKE '%"+filter+"%' ) AND isdeleted <> 1 group by part_id";
                    } else {
                        var query = "<?php echo $queryforexcel; ?>";
                    }

                    query = query.replace(/%/g,"percentage");
                    var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=parts&&filename=parts_excel";
                    window.location = page;// you can use window.open also

                <?php } ?>

            });

            function formatNumber (num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
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
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/parts.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                                <?php 
                            }else{
                                ?>
                                window.location.assign("" + "<?php echo SITE_URL;?>head_office/parts.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );

                                <?php 
                            }
                            ?>
                            <?php
                        }
                    ?>
            }
            );

            $(document).on('click', ".clickable", function () {
                $(".clickable").removeClass("selected");
                $(this).addClass("selected");
                ID = $(this).attr("id");
                console.log(ID);
            });

            $('.add').on('click', function () {
                $('#create-parts').modal('show');
            });

            $('[name="models"]').on('change', function(){
                if( $(this).val() != 0 ) {
                    $('.hideshow.modeldetails').fadeIn("fast");
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewmodel.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            modelid: $(this).val(),
                            all : 'all'
                        },
                        success: function (e) {
                            var obj = jQuery.parseJSON(e);
                            $('.modeldescriptionhere').html(obj.response[0].description);
                            $('.modelbrandhere').html(obj.response[0].brandname);
                            $('.modelcategoryhere').html(obj.response[0].category);
                            $('.modelsubcategoryhere').html(obj.response[0].subcategory);
                        }
                    });
                } else {
                    $('.hideshow.modeldetails').fadeOut("fast");
                }
            });

            $("[name=parttype]").on('change', function () {
                if ($(this).val() == 1) {

                    $('.showifhechoose3').fadeIn("fast");
                    $('.showifhechoose2').fadeIn("fast");
                    $('.showifhechoose').fadeOut("fast");

                } else if ($(this).val() == 2) {

                    $('.showifhechoose3').fadeOut("fast");
                    $('.showifhechoose').fadeIn("fast");

                } else {
                    $('.showifhechoose').fadeOut("fast");
                    $('.showifhechoose3').fadeOut("fast");
                }
            });

            $('.delete').on('click', function () {
                if (ID) {
                    $("#delete-modal").modal('show');
                    $("#partname").html($('#example1 #'+ID+' td:nth-child(2)').text());
                } else {
                    $("#selecrecord-modal").modal("show");
                }
            });

            $("#search_part").keyup(function () {
                var toSearch = $("#search_part").val();
                $('.search-list-part').html("");
                $('.search-list-result-part').slideDown('fast');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/search_part.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        toSearch: toSearch
                    },
                    success: function (e) {
                        // 
                        if (e != 'error') {
                            var obj = jQuery.parseJSON(e);
                            var data = "";
                            for (var i = 0; i < obj.response.length; i++) {
                                $('.search-list-part').append("<option value='" + obj.response[i].name + "~" + obj.response[i].part_id + "~" + obj.response[i].cost + "'>" + obj.response[i].name + "</option>");
                            }
                        }
                    }
                });
            });

            $('.search-list-part').change(function () {
                $('.selectbacthes').slideDown('fast');
                $('.search-list-result-part').slideUp('fast');
                var m = $(this).val();
                var re = m.toString().split('~');
                partID = re[1];
                $("#search_part").val(re[0]);
                $('.showifselected').fadeIn("fast");
            });

            $('.view').on('click', function () {
                if (ID) {
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewpart.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function (e) {
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);

                            $('.vstocknumber').html(obj.response[0].stocknumber);
                            $('.vpartname').html(obj.response[0].name);
                            $(".vmodel").html(obj.response2[0].modelname);
                            $(".vbrand").html(obj.response2[0].brandname);
                            $(".vcategory").html(obj.response2[0].category);
                            $(".vsubcategory").html(obj.response2[0].subcategory);

                            $('.vparts tbody').html('');
                            var tr = "";
                            $.each(obj.response, function(key, value){
                                tr += '<tr class="text-center"><td>'+value.date+'</td><td>'+value.quantity+'</td><td>'+value.quantityfree+'</td><td>'+value.bacth_quantity+'</td><td>'+value.cost+'</td><td> <strong>P</strong> <span class="price">'+value.totalprice+'</td></span><td><button class="btn btn-warning batch-edit" data-id="'+value.id+'"><i class="fa fa-pencil"></i></button></td></tr>';
                            });
                            
                            $('.vparts tbody').append(tr);
                            $('.price').number( true, 2 );

                            //Edit Batch
                            $('.batch-edit').click(function(){
                                //console.log(ID, $(this).attr('data-id'));
                                $('.batch-edit').removeClass('selected');
                                $(this).addClass('selected');
                                $('#batchquantity').modal('show');

                                $.ajax({
                                    type: 'POST',
                                    url: '../ajax/viewpartbatch.php',
                                    data: {
                                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                                        id: $('.batch-edit.selected').attr('data-id'),
                                        partid: ID
                                    },
                                    success: function (e) {
                                        var obj = jQuery.parseJSON(e);
                                        if (e == "error") {
                                            alert('Warning: Internal Server Error!');
                                        } else {
                                            $("[name=ebbatchquantity]").val(obj.response[0].bacth_quantity);
                                        }
                                    }
                                });
                            });
                        }
                    });

                    $("#view-modal").modal("show");
                } else {
                    $("#selecrecord-modal").modal("show");
                }
            });


            $("#editpartbatch").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                    "ebbatchquantity": {
                        required: true,
                        number: true
                    }
                },
                submitHandler: function (form) {
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/editpartbatch.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            quantity: $("[name=ebbatchquantity]").val(),
                            id: $('.batch-edit.selected').attr('data-id'),
                            partid: ID
                        },
                        success: function (e) {
                            if (e == "success") {
                                location.reload();
                            } else {
                                alert('Warning: '+e);
                                $('.modald').fadeOut('fast');
                            }
                        }
                    });
                    return false;
                }
            });

            $('.generatebatchpart').on('click', function() {
                query = "SELECT *, ( (bacth_quantity-quantity-quantityfree)*cost ) AS totalprice FROM `jb_part` WHERE  part_id = '"+ID+"'";
                var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=batchpart&&filename=batchpartexcel";
                window.location = page;// you can use window.open also
            });


            $('.edit').on('click', function () {
                if (ID) {
                    $('.modald').fadeIn('fast');
                    $('#edit-part').modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewpart.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function (e) {
                            
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $("[name=estocknumber]").val(obj.response[0].stocknumber);
                            $("[name=epartname]").val(obj.response[0].name);
                            $("[name=emodels] option[value='"+obj.response[0].modelid+"']").attr("selected", "selected");
                            $("[name=ecost]").val(obj.response[0].cost);

                            if ( $("[name=emodels]").val() != 0) {
                                $('.hideshow.emodeldetails').fadeIn("fast");
                                $('.emodeldescriptionhere').html(obj.response2[0].description);
                                $('.emodelbrandhere').html(obj.response2[0].brandname);
                                $('.emodelcategoryhere').html(obj.response2[0].category);
                                $('.emodelsubcategoryhere').html(obj.response2[0].subcategory);
                            }
                        }
                    });
                } else {
                    $("#selecrecord-modal").modal("show");
                }
            });

            $('[name="emodels"]').on('change', function(){
                if( $(this).val() != 0 ) {
                    $('.hideshow.emodeldetails').fadeIn("fast");
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewmodel.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            modelid: $(this).val(),
                            all : 'all'
                        },
                        success: function (e) {
                            var obj = jQuery.parseJSON(e);
                            $('.emodeldescriptionhere').html(obj.response[0].description);
                            $('.emodelbrandhere').html(obj.response[0].brandname);
                            $('.emodelcategoryhere').html(obj.response[0].category);
                            $('.emodelsubcategoryhere').html(obj.response[0].subcategory);
                        }
                    });
                } else {
                    $('.hideshow.emodeldetails').fadeOut("fast");
                }
            });


            $("#deleteitem").on('click', function () {
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletepart.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        id: ID
                    },
                    success: function (e) {
                        
                        if (e == "success") {
                            $("#delete-modal").modal('hide');
                            $("#" + ID).remove();
                            ID = "";
                            location.reload();
                        } else {

                        }
                    }
                });
            });

            $("#createpart").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                    "stocknumber": {
                        required: true
                    },
                    "partname": {
                        required: true
                    },
                    "models": {
                        required: true
                    },
                    "quantity": {
                        required: true,
                        number: true
                    },
                    "cost": {
                        required: true,
                        number: true
                    }
                },
                // Specify the validation error messages
                messages: {
                    stocknumber: {
                        required: "Please provide a Stock Number."
                    },
                    partname: {
                        required: "Please provide a Part Name."
                    },
                    models: {
                        required: "Please select a Type of Model."
                    },
                    quantity: {
                        required: "Please provide a Number."
                    },
                    cost: {
                        required: "Please provide a Cost."
                    }
                },
                submitHandler: function (form) {
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/createpart.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            stocknumber: $("[name=stocknumber]").val(),
                            partname: $("[name=partname]").val(),
                            modelid: $("[name=models]").val(),
                            quantity: $("[name=quantity]").val(),
                            cost: $("[name=cost]").val(),
                            isExisting: '0'
                        },
                        success: function (e) {
                            
                            if (e == "success") {
                                location.reload();
                            }
                        }
                    });
                    return false;
                }
            });

            $("#addexistingproduct").validate({
                errorElement: 'p',
                rules: {
                    "quantity2": {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    quantity2: {
                        required: "Please provide a number"
                    }
                },
                submitHandler: function (form) {
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/createpart.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            quantity: $("[name=quantity2]").val(),
                            idpart: partID,
                            isExisting: '1'
                        },
                        success: function (e) {
                            
                            if (e == "success") {
                                location.reload();
                            }
                        }
                    });
                    return false;
                }
            });


            $("#editpart").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                    "estocknumber": {
                        required: true
                    },
                    "epartname": {
                        required: true
                    },
                    "emodels": {
                        required: true
                    },
                    "ecost": {
                        required: true,
                        number: true
                    }
                },
                // Specify the validation error messages
                messages: {
                    estocknumber: {
                        required: "Please provide a Stock Number"
                    },
                    epartname: {
                        required: "Please provide a Part Name"
                    },
                    emodels: {
                        required: "Please select a Type of Model"
                    },
                    ecost: {
                        required: "Please provide a Cost"
                    }
                },
                submitHandler: function (form) {
                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/editpart.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            partid: ID,
                            stocknumber: $("[name=estocknumber]").val(),
                            partname: $("[name=epartname]").val(),
                            modelid: $("[name=emodels]").val(),
                            cost: $("[name=ecost]").val()
                        },
                        success: function (e) {
                            
                            if (e == "success") {
                                location.reload();
                            }
                        }
                    });
                    return false;
                }
            });

            $('.discard').click( function(){
                $('input, textarea').val('');
                $('select option').removeAttr('selected');
                $('p.error').remove();
            });

        });

    </script>
<?php
htmlFooter('dashboard');
?>

id
part_id
category

