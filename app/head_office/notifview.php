<?php
    include '../../include.php';
    include '../ui_main.php';
    htmlHeader('dashboard');
    global $url;

    $jobid = $_GET['jobid'];
    $subid = $_GET['subid'];

    $checker = "SELECT * FROM jb_warranty WHERE jobid = '".$jobid."'";
    $query_checker = $db->ReadData($checker);

    if($query_checker){
        $sql = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.parts, a.technicianid, a.repair_status, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted,a.date_delivery, a.done_date_delivery,a.isunder_warranty,a.estimated_finish_date, b.*, c.branch_id, c.branch_name, c.address as branch_address, c.contactperson as contact_person, c.email as contact_email, c.number as branch_number, d.tech_id, d.name as technam, e.warranty_type, e.warranty_date, e.jobid as e_jobID, f.diagnosis as diagnosisitem FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d, jb_warranty e, jb_diagnosis f WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND  a.jobid = e.jobid  AND a.diagnosis = f.id AND  a.jobid = '" .$jobid. "'";
    }else {
        $sql = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.parts, a.technicianid, a.repair_status, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted,a.date_delivery,a.done_date_delivery, a.isunder_warranty,a.estimated_finish_date, b.*, c.branch_id, c.branch_name, c.address as branch_address, c.contactperson as contact_person, c.email as contact_email, c.number as branch_number, d.tech_id, d.name as technam, f.diagnosis as diagnosisitem FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d, jb_diagnosis f WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.diagnosis = f.id AND a.jobid = '" .$jobid. "'";
    }   

    $getjoball = $db->ReadData($sql);


    $qu = "SELECT * FROM jb_soa WHERE jobid = '".$jobid."'";
    $queryin  = $db->ReadData($qu);

    $cost = "SELECT * FROM `jb_cost` WHERE  jobid = '".$jobid."'";
    $charges  = $db->ReadData($cost);

    $updatenotif = "UPDATE `notitemp` SET `isViewed`='1' WHERE notif_id = '".$subid."'";
     $udpatejobnow= $db->ExecuteQuery($updatenotif);

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
              <?php breadcrumps('Job Order View'); ?>

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
                <?php 
                    // var_dump($charges);
                ?>
                    <div class="modal-body">
                                   <!-- Main content -->
                <section class="content invodice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> Jor Order  #<span class="idhere"><?php echo $jobid;?></span>
                                <small class="pull-right">Date: <span class="datehere"><?php 
                                $dt = new DateTime($getjoball[0]['created_at']);
                                echo $dt->format('M j Y');
                                ?></small>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Customer Name: </strong><span class="namehere"><?php echo $getjoball[0]['name'];?></span><br>
                                <strong>Address : </strong><span class="addresshere"><?php echo $getjoball[0]['address'];?></span><br>
                                <strong>Contact Number: </strong><span class="contacthere"><?php echo $getjoball[0]['number'];?></span><br>
                                <strong>Email Address: </strong><span class="emailhere"><?php echo $getjoball[0]['email'];?></span><br>
                                <strong>Customer Type: </strong><span class="ctypehere"><?php echo $getjoball[0]['customer_type_id'];?></span><br>
                                <strong>Is Under Warranty: </strong><span class="isunder_warranty">
                                    <?php 
                                        if($getjoball[0]['isunder_warranty'] == '1'){
                                            echo "Yes";
                                        }else{
                                            echo "No";
                                        }
                                    ?>
                                </span><br>

                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <strong>Branch Name: </strong><span class="branchnamehere"><?php echo $getjoball[0]['branch_name'];?></span><br>
                                <strong>Branch Address : </strong><span class="branchaddresshere"><?php echo $getjoball[0]['branch_address'];?></span><br>
                                <strong>Contact Person: </strong><span class="branchcontacthere"><?php echo $getjoball[0]['contact_person'];?></span><br>
                                <strong>Phone number: </strong><span class="branchphonehere"><?php echo $getjoball[0]['branch_number'];?></span><br>
                        </div><!-- /.row -->
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Diagnosis</th>
                                        <th>Parts</th>
                                        <th>Technician</th>
                                        <th>Cost(Balance)</th>
                                        <th>Remarks</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="span-item"><?php echo $getjoball[0]['item'];?></span></td>
                                        <td><span class="span-diagnosis"><?php echo $getjoball[0]['diagnosisitem'];?></span></td>
                                        <td><span class="span-parts"><?php echo str_replace("br", "", $getjoball[0]['parts']);?></span></td>
                                        <td><span id="tec" class="span-tech"><?php echo $getjoball[0]['technam'];?></span></td>
                                        <td><span class="span-cost"><?php echo $getjoball[0]['name'];?></span></td>
                                        <td><span class="span-remarks"><?php echo $getjoball[0]['remarks'];?></span></td>
                                    </tr>
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <?php 
                        if(count($charges) != '0'){
                            ?>
                            <div class="col-sm-4 invoice-col">
                            <address>
                                <strong>Total Parts Cost: </strong><span class="totalpartcost"><?php echo $charges[0]['totalpartscost'];?></span><br>
                                <strong>Service Charges : </strong><span class="servicescharge"><?php echo $charges[0]['service_charges'];?></span><br>
                                <strong>Total Charges: </strong><span class="totalcharges"><?php echo $charges[0]['total_charges'];?></span><br>
                                <strong>Less Deposit: </strong><span class="lessdeposit"><?php echo $charges[0]['less_deposit'];?></span><br>
                                <strong>Less Discount: </strong><span class="lessdiscount"><?php echo $charges[0]['less_discount'];?></span><br>
                                <strong>Balance: </strong><span class="balance"><?php echo $charges[0]['balance'];?></span><br>
                            </address>
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <strong>Computed By: </strong><span class="computedby"><?php echo $charges[0]['computed_by'];?></span><br>
                                <strong>Accepted By : </strong><span class="acceptedby"><?php echo $charges[0]['accepted_by'];?></span><br>
                                <div class="ongoingrepairhideshow">
                                    <button id="ongoingrepair" class=" approvedview2 btn bg-green margin">Done Repair</button>
                                    <button id="cantrepairs" class=" approvedview2 btn bg-red margin">Can't Repair</button>
                                </div>
            
                                 <div class="ongoingrepairhideshow2">
                                 <br>
                                        <label>Delivery date for ready for pickup:</label>
                                        <input type="date" name="datedelivery" class="form-control" placeholder="Estimated Finish Date ">
                                    <button id="save_donedate" class=" approvedview2 btn bg-green margin"><i class="fa fa-check"> </i> Save Delivery Date</button>
                                </div>
                                <?php
                        }else{

                        }
                    ?>
                    
                </section><!-- /.content -->

                         <!-- <button id="cmd" class="btn btn-primary" style="margin-left: 18px;"><i class="fa fa-download"></i> Generate PDF </button>  -->
                        <!--  <button type="submit" id="savejob" class="btn btn-success pull-left "><i class="fa fa-plus"></i> OK </button> -->
                    </div><!-- /.modal-content --> 
                    </div><!-- /.modal-dialog -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <script type="text/javascript">
        $(function(){
            var ID = <?php echo $jobid; ?>;

            $('#cmd').click(function () {
                var jobOrder = {
                      content: [
                        { text: 'Job Order No.' + ID, style: 'header' },
                        { text: 'Date : ' + $('.datehere').text(), style: 'date' },
                        {columns: [
                            {
                                width: 'auto',
                                      bold: true,
                                text: 'Customer Name: \nAddress :\nContact Number :\nEmail Address: \n Customer Type:\nIs Under Warranty:'
                            },
                            {
                                width: 'auto',
                                text: $('.namehere').text() + "\n" + $('.addresshere').text() + "\n" + $('.contacthere').text() + "\n" + $('.emailhere').text() + "\n" + $('.ctypehere').text() + "\n" + $('.isunder_warranty').text()
                            },
                            {
                                width: 'auto',
                                marginLeft: 85,
                                bold: true,
                                text: 'Branch Name: \nBranch Address :\nContact Person: \n Phone number: \n'
                            },
                            {
                                width: 'auto',
                          alignment: 'right',
                                text: $('.branchnamehere').text() + "\n" + $('.branchaddresshere').text() + "\n" + $('.branchcontacthere').text() + "\n" + $('.branchphonehere').text()
                            },
                        ]
                    },{
                        style: 'tableExample',
                        table: {
                                widths: ['*', '*', 110, '*',110,'*'],
                                body: [
                                        [ 'Item', 'Diagnosis', 'Parts', 'Technician', 'Remarks', 'Status'],
                                        [ $('.span-item').text(), $('.span-diagnosis').text(), $('.span-parts').text(), $('.span-tech').text(), { text: $('.span-remarks').text(), italics: true, color: 'gray' }, $('.span-status').text() ]
                                ]
                        }
                    },{columns: [
                            {
                                width: 'auto',
                                      bold: true,
                                text: 'Total Parts Cost: \nService Charges :\nTotal Charges :\nLess Deposit: \nLess Discount:\nBalance:\n\nComputed By:\nAccepted By :'
                            },
                            {
                                width: 'auto',
                                marginLeft: 10,
                                text: $('.totalpartcost').text() + "\n" + $('.servicescharge').text() + "\n" + $('.totalcharges').text() + "\n" + $('.lessdeposit').text() + "\n" + $('.lessdiscount').text() + "\n" + $('.balance').text()+ "\n\n" + $('.computedby').text() + "\n" + $('.acceptedby').text()
                            }
                        ]
                    },
                      ],info: {
    title: 'Job Order -- ' + ID,
    author: 'JB Sports & Music',
    subject: 'Job Order Info',
  },
                      pageSize: 'A5',  
                    pageOrientation: 'landscape',
                      styles: {
                        header: {
                          fontSize: 16,
                          bold: true
                        },
                        invoiceinfo: {
                          fontSize: 11
                        },
                        invoiceinfo2: {
                          fontSize: 11,
                          marginTop: -80,
                          alignment: 'right'
                        },
                        tableExample: {
                            margin: [0, 30, 0, 15],
                        },
                        date: {
                          fontSize: 12,
                          bold: true,
                          marginTop: -15,
                          marginBottom: 8,
                          alignment: 'right'
                        }
                      }
                    };
                pdfMake.createPdf(jobOrder).open();
                pdfMake.createPdf(jobOrder).download('JB Job Order No. ' + ID + ".pdf");
            });
        });
        </script>

<?php
    htmlFooter('dashboard');
?>