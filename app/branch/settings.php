<?php
    include '../../include.php';
    include '../ui_branch.php';
    htmlHeader('dashboard');
    global $url;

    $queryforexcelstafflists = '';
    $queryforexceldiagnosis = '';
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
        // exit();
                $sql2 = "SELECT a.*, b.jobid as joborderid, b.branchid as branchidmo FROM notitemp a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid = '".$_SESSION['Branchid']."' AND a.branch_id = '0' ORDER BY `created_at` DESC";
        $query2 = $db->ReadData($sql2);

        $counterviewed = "SELECT a.*, b.jobid as joborderid, b.branchid as branchidmo FROM notitemp a, jb_joborder b WHERE a.jobid = b.jobid AND b.branchid = '".$_SESSION['Branchid']."' AND a.branch_id = '0' AND isViewed <> '1' ORDER BY `created_at` DESC ";
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
                
              <?php breadcrumps('Settings'); ?>

                <!-- Main content -->
                <section class="content">

                        <div class='col-xs-12'>
                            <div class="form-group pull-right exportoexcel">
                                <div class="input-group">
                                    <button class="createexcel btn btn-default pull-right" id="stafflistsexcel">
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
                        </div>

                        <div class='col-xs-12'>
                            <?php
                                # Permission
                                $permission = "SELECT t.name, p.add_status, p.edit_status, p.delete_status, p.view_status FROM jb_user u, jb_permission p, jb_permission_type t WHERE u.id = p.user_id AND p.permission_type_id = t.id AND u.id='".$_SESSION['id']."'";
                                $permission = $db->ReadData($permission);
                            ?>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#stafflists" data-toggle="tab">Staff Settings</a></li>
                                    <li><a href="#diagnosis" data-toggle="tab">Diagnosis</a></li>  
                                    <li class=""><a href="#emailsettings" data-toggle="tab">Email Settings</a></li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Font Awesome icons -->

                                            <div class="tab-pane" id="diagnosis">
                                                <section id="new">
                                                    <div class="form-group col-xs-12">
                                                    <?php if($_SESSION['position'] == 2): ?>
                                                        <ol class="contextual_menu2 contextualmenudi" style="margin-top: -17px;">
                                                            <li class="addd"><i class="fa  fa-plus"> </i></li>
                                                            <li class="editd" ><i class="fa  fa-pencil"></i></li>
                                                            <li class="deleted"><i class="fa  fa-times"></i></li>
                                                        </ol>
                                                    <?php else: ?>
                                                        <ol class="contextual_menu2" style="margin-top: -17px;">
                                                        <?php foreach($permission AS $value): ?>
                                                            <?php if ($value['name'] == 'diagnosis' && $value['add_status'] == 'yes'): ?>
                                                                <li class="addd" ><i class="fa  fa-plus"> </i></li>
                                                            <?php endif; ?>
                                                            <?php if ($value['name'] == 'diagnosis' && $value['edit_status'] == 'yes'): ?>
                                                                <li class="editd"><i class="fa  fa-pencil"></i></li>
                                                            <?php endif; ?>
                                                            <?php if ($value['name'] == 'diagnosis' && $value['delete_status'] == 'yes'): ?>
                                                                <li class="deleted"><i class="fa  fa-times"></i></li>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                        </ol>
                                                    <?php endif; ?>
                                                    <h4 class="page-header">Diagnosis Lists</h4>

                                                        <div class="box">

                                                            <div class="box-body table-responsive">
                                                                <table id="tablediagnosis" class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th style="width: 50px">ID #</th>
                                                                    <th>Diagnosis</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php

                                                                        if(isset($_GET['daterange'])){
                                                                            $bydate = split ("to", $_GET['daterange']);
                                                                            $qu = "SELECT * FROM `jb_diagnosis` WHERE created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'  ORDER BY created_at ASC";
                                                                        }else{
                                                                            $qu = "SELECT * FROM `jb_diagnosis` ORDER BY created_at ASC";
                                                                        }

                                                                        $queryforexceldiagnosis = $qu;
                                                                        $query = $db->ReadData($qu);

                                                                        $counter = 0;
                                                                        foreach ($query as $key => $value) {
                                                                            $counter++;
                                                                            $return = "<tr class='clickable' id='".$value['id']."' class='clickable'>
                                                                                <td>".$counter."</td>
                                                                                <td class='dia'>".$value['diagnosis']."</td>
                                                                            </tr>";
                                                                            echo $return;
                                                                        }
                                                                    ?>
                                                                </tbody>
                                                                </table>
                                                            </div><!-- /.box-body -->
                                                        </div><!-- /.box -->

                                                    </div>
                                                    <div class="clear"></div>
                                                </section>
                                                
                                            </div><!-- /#ion-icons -->

                                    

                                    <div class="tab-pane active" id="stafflists" >
                                        <section id="new">
                                            <div class="col-md-12">
                                                <?php if($_SESSION['position'] == 2): ?>
                                                    <ol class="contextual_menu2" style="margin-top: -17px;">
                                                        <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                                                        <li class="add"><i class="fa  fa-plus"> </i></li>
                                                        <li class="edit" ><i class="fa  fa-pencil"></i></li>
                                                        <li class="delete"><i class="fa  fa-times"></i></li>
                                                        <li class="permission"><i class="fa  fa-lock"></i></li>
                                                    </ol>
                                                <?php else: ?>
                                                    <ol class="contextual_menu2" style="margin-top: -17px;">
                                                    <?php foreach($permission AS $value): ?>
                                                        <?php if ($value['name'] == 'staff' && $value['view_status'] == 'yes'): ?>
                                                            <li class="view" ><i class="fa  fa-folder-open"> </i></li>
                                                        <?php endif; ?>
                                                        <?php if ($value['name'] == 'staff' && $value['add_status'] == 'yes'): ?>
                                                            <li class="add" ><i class="fa  fa-plus"> </i></li>
                                                        <?php endif; ?>
                                                        <?php if ($value['name'] == 'staff' && $value['edit_status'] == 'yes'): ?>
                                                            <li class="edit"><i class="fa  fa-pencil"></i></li>
                                                        <?php endif; ?>
                                                        <?php if ($value['name'] == 'staff' && $value['delete_status'] == 'yes'): ?>
                                                            <li class="delete"><i class="fa  fa-times"></i></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    </ol>
                                                <?php endif; ?>
                                                <h4 class="page-header">Staff List</h4>
                                                <div class="box">
                                                     <div class="box-body table-responsive">
                                                    <table id="example1" class="table table-bordered ">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Last Name</th>
                                                                <th>First Name</th>
                                                                <th>Email </th>
                                                                <th>Contact Number</th>
                                                                <th>Job Title</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php

                                                            if(isset($_GET['daterange'])){
                                                                $bydate = split ("to", $_GET['daterange']);
                                                                $qu = "SELECT * FROM `jb_user` WHERE branch_id = '".$_SESSION['Branchid']."' AND level = '3' AND created_at BETWEEN '".$bydate[0]."' AND '".$bydate[1]."'  ORDER BY lastname ASC";
                                                            }else{
                                                                $qu = "SELECT * FROM `jb_user` WHERE branch_id = '".$_SESSION['Branchid']."' AND level = '3' ORDER BY lastname ASC";
                                                            }

                                                            $queryforexcelstafflists = $qu;

                                                            $query = $db->ReadData($qu);
                                                            $counter = 0;

                                                            foreach ($query as $key => $value) {
                                                                $counter++;
                                                                $return = "<tr class='clickable' id='".$value['id']."' class='clickable'>
                                                                    <td>".$counter."</td>
                                                                    <td>".$value['lastname']."</td>
                                                                    <td>".$value['firstname']."</td>
                                                                    <td>".$value['email']."</td>
                                                                    <td>".$value['contact_number']."</td>
                                                                    <td>".$value['job_title']."</td>
                                                                    <td>".ucfirst($value['status'])."</td>
                                                                </tr>";
                                                                echo $return;
                                                            }
                                                            ?>
                                                        </tbody>
                                                        </table>
                                                    </div><!-- /.box-body -->
                                                    <div class="clear"></div>
                                                </div>
                                            </section>
                                            <div class="clear"></div>
                                        </div>

                                    


                                    <div class="tab-pane " id="emailsettings">
                                        <section id="new">
                                            <div class="col-md-12">
                                                <h4 class="page-header">Email Settings</h4>
                                                <?php 
                                                    $feedback = "";
                                                    $admin = "";
                                                    $qu = "SELECT * FROM `jb_email` WHERE isbranch = 1";
                                                    $query = $db->ReadData($qu);
                                                    if($query){
                                                        $feedback = $query[0]['feedback'];
                                                        $admin = $query[0]['admin'];
                                                    }
                                                ?>
                                                <div class="form-group col-xs-5">
                                                    <form id="saveemails" name="saveemails" method="post" role="form">
                                                        <div class="form-group">
                                                        <label>Email for Customer Feedback</label>
                                                            <input type="text" name="feedback" class="form-control" value="<?php echo $feedback;?>" placeholder="Feedback Email">
                                                        </div>
                                                        <div class="form-group">
                                                        <label>Admin Email</label>
                                                            <input type="text"  name="adminemail" class="form-control" value="<?php echo $admin;?>" placeholder="Admin Email">
                                                        </div>
                                                        <button class="btn btn-success">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </section>
                                    </div><!-- /#ion-icons -->
                                </div><!-- /.tab-content -->
                            </div><!-- /.nav-tabs-custom -->
                        </div><!-- /.col -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <div class="modal fade" id="create-staff" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Register a Staff</h4>
                    </div>
                    <div class="modal-body">
                        <form id="createstaff" class="change_to_edit" name="createstaff" method="post" role="form">

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
                                <input type="email" name="email" class="form-control" placeholder="Email Address">
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
                
                        <div class="clear"></div>
                        <div class="modal-footer clearfix">
                            <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                            <button type="submit" id="savejob" class="btn btn-primary "><i class="fa fa-plus"></i> Submit </button>
                        </div>
                    </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div class="modal fade" id="edit-staff" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"> Edit Staff Information</h4>
                    </div>
                    <div class="modal-body">
                        <form id="editstaff" class="change_to_edit" name="createstaff" method="post" role="form">

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
                                <input type="email" name="eemail" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="form-group col-xs-12">
                                <input type="text" name="eaddress" class="form-control" placeholder="Address">
                            </div>
                        </div>

                        <div class="form-group col-xs-12 form-section">
                            <h4 class="title">Account</h4>
                            <div class="clear"></div>

                            <div class="form-group col-xs-12">
                                <input type="password" name="enewpassword" data-customer-id="" class="form-control" placeholder="Password">
                            </div>
                            <div class="form-group col-xs-12">
                                <input type="password" name="econfirmpassword" data-customer-id="" class="form-control" placeholder="Confirm Password">
                            </div>
                            <div class="form-group col-xs-12">
                                <input type="text" name="ejobtitle" class="form-control" placeholder="Job Title">
                            </div>

                        </div>

                        <div class="clear"></div>
                        <div class="modal-footer clearfix">
                            <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                            <button type="submit" id="savejob" class="btn btn-primary "><i class="fa fa-plus"></i> Submit </button>
                        </div>

                    </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div class="modal fade" id="view-staff" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><span class="lastname"></span>, <span class="firstname"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body infor">
                            <dl>
                                <dt>Nickname</dt>
                                <dd><span class="nickname"></span></dd>
                                <dt>Address</dt>
                                <dd><span class="address"></span></dd>
                                <dt>Contact</dt>
                                <dd><span class="contact"></span></dd>
                                <dt>Email</dt>
                                <dd><span class="email"></span></dt>
                                <dt>Job Title</dt>
                                <dd><span class="jobtitle"></span></dt>
                                <dt>Date Added</dt>
                                <dd><span class="dateadd"></span></dt>
                                <dt>Status</dt>
                                <dd><span class="status"></span></dt>    
                            </dl>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
            
             <div class="modal fade" id="create-diagnosis" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <form id="creatediagnosis" class="change_to_edit" name="createstaff" method="post" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-plus-circle"></i> Create Diagnosis</h4>
                    </div>
                    <div class="modal-body">
                            <div class="form-group col-xs-12">
                                <label>Diagnosis:</label>
                                <input type="text" name="diagnosis" data-customer-id="" class="form-control" placeholder="Diagnosis">
                            </div>
                    </div>

                        <div class="form-group col-xs-12">
                            <div class="modal-footer clearfix">
                                <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                                <button type="submit" id="createdianogis" class="btn btn-primary"><i class="fa fa-plus"></i> Create Diagnosis </button>
                            </div>
                        </div>
                        <div class="clear"></div>

                        </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    <div class="modal fade" id="edit-diagnosis" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <form id="editdiagnosis" class="change_to_edit" name="createstaff" method="post" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><i class="fa  fa-pencil"></i> Edit Diagnosis</h4>
                    </div>
                    <div class="modal-body">
                            <div class="form-group col-xs-12">
                                <label>Diagnosis:</label>
                                <input type="text" name="ediagnosis" data-customer-id="" class="form-control" placeholder="Diagnosis">
                            </div>
                    </div>

                        <div class="form-group col-xs-12">
                            <div class="modal-footer clearfix">
                                <button type="button" class="btn btnmc discard" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                                <button type="submit" id="ecreatedianogis" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit Diagnosis </button>
                            </div>
                        </div>
                        <div class="clear"></div>

                        </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
<div class="modal fade" id="delete-modal2" tabindex="-1" role="dialog" aria-hidden="true">


        <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete this <span id="diagnosisname"></span> as Diagnosis?</h4>
            </div>
            <div class="modal-body text-right">
                 <button type="button" class="btn cancel-delet  btnmc " data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button> 
                 <button type="submit" id="deletedis" class="btn btn-danger cancel-delet"><i class="fa fa-plus"></i> Delete </button>
            </div><!-- /.modal-content --> 
            </div><!-- /.modal-dialog -->
            <div class="clear"></div>
            </div><!-- /.modal -->
        </div><!-- /.modal -->

        <?php if($_SESSION['position'] == 2 ): ?>
        <div class="modal fade" id="permission" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"> Permissions For: <strong><span class="firstname"></span> <span class="lastname"></span></strong></h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body infor">
                            <h4>Status: <input type="checkbox" name="status" value="active"> <small>Active</small></h4>
                            <table class="table table-bordered ">
                                <?php 
                                    $permissiontype = "SELECT * FROM jb_permission_type"; 
                                    $permissionQuery = $db->ReadData($permissiontype);
                                ?>
                                <tr>
                                    <th style="width:35px;"></th>
                                    <th>Modules</th>
                                    <th class="text-center">View</th>
                                    <th class="text-center">Add</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                                <?php foreach($permissionQuery AS $value): ?>
                                    <?php if($value['name'] == 'job_orders' || $value['name'] == 'statements_of_account' || $value['name'] == 'customers' || $value['name'] == 'staff' || $value['name'] == 'diagnosis' || $value['name'] == 'sales_report' ): ?>
                                        <tr class="<?php echo $value['name']; ?>">
                                            <td><input type="checkbox" name="<?php echo $value['name']; ?>" class="modulename" value="<?php echo $value['id']?>"></td>
                                            <td><?php echo ucwords(str_replace("_", " ", $value['name'])); ?></td>
                                            <td class="checkboxsmall">
                                                <?php if($value['name'] != 'diagnosis' && $value['name'] != 'sales_report'):?>
                                                    <input type="checkbox" name="view" class="modulestatus_<?php echo clearspaces($value['name']); ?>" value="view">
                                                <?php else: ?>
                                                    <small class="na fa fa-ban"></small>
                                                <?php endif;?>
                                            </td>
                                            <td class="checkboxsmall">
                                                <?php if( $value['name'] != 'statements_of_account' && $value['name'] != 'sales_report'):?>
                                                    <input type="checkbox" name="add" class="modulestatus_<?php echo clearspaces($value['name']); ?>" value="add">
                                                <?php else: ?>
                                                    <small class="na fa fa-ban"></small>
                                                <?php endif;?>
                                            </td>
                                            <td class="checkboxsmall">
                                                <?php if($value['name'] != 'statements_of_account' && $value['name'] != 'sales_report'):?>
                                                    <input type="checkbox" name="edit" class="modulestatus_<?php echo clearspaces($value['name']); ?>" value="edit">
                                                <?php else: ?>
                                                    <small class="na fa fa-ban"></small>
                                                <?php endif;?> 
                                            </td>
                                            <td class="checkboxsmall">
                                                <?php if($value['name'] != 'statements_of_account' && $value['name'] != 'sales_report'):?>
                                                    <input type="checkbox" name="delete" class="modulestatus_<?php echo clearspaces($value['name']); ?>" value="delete">
                                                <?php else: ?>
                                                    <small class="na fa fa-ban"></small>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                
                            </table>
                            <div class="text-right">
                                <button type="submit" id="savepermission" class="btn btn-success"><i class="fa fa-check"></i> Save </button>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php endif;?>

        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title " ><i class="fa  fa-times-circle"> </i> Are you sure you want to delete this <span id="fname"></span> <span id="lname"></span> as Staff?</h4>
            </div>
            <div class="modal-body text-right">
                 <button type="button" class="btn btnmc" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>  
                 <button type="submit" id="deleteitem" class="btn btn-danger cancel-delet "><i class="fa fa-plus"></i> Delete </button>
            </div><!-- /.modal-content --> 
            </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div><!-- /.modal -->
        <div class="modal fade" id="selecrecord-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> <span class="errormessage">Please make a selection from the list.</span> </h4>
                </div>
                <div class="modal-body">
                     <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
                <div class="clear"></div>
                </div><!-- /.modal-content --> 
                </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
        </div><!-- /.modal -->
        <script type="text/javascript">
        $(function(){
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
                            window.location.assign("" + "<?php echo SITE_URL;?>branch/settings.php?type=<?php echo $_GET['type']; ?>" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59') );
                            <?php 
                        }else{
                            ?>
                            window.location.assign("" + "<?php echo SITE_URL;?>branch/settings.php?" + "&daterange=" + start.format('YYYY-MM-D 00:00:00') + 'to' + end.format('YYYY-MM-D 23:59:59'));

                            <?php 
                        }
                        ?>
                        <?php
                    }
                ?>
            });

            // Navigate Export Excel
            initExportExcel();

            $('.nav-tabs li').click( function(){
                ID = "";
                $('tr').removeClass('selected');
                var id = $(this).find('a').attr('href').replace(/#/g,"");

                $('.exportoexcel .input-group').html('<button class="createexcel btn btn-default pull-right" id="'+id+'excel"><i class="fa fa-file-text-o"></i> Export to Excel</button>');

                if( $(this).find('a').attr('href') == '#emailsettings' ) {
                    $('.createexcel').removeAttr('id');
                    $('.exportoexcel').parent().css('visibility', 'hidden');
                } else {
                    $('.exportoexcel').parent().css('visibility', 'visible');
                }

                initExportExcel();
            });

            // Export Files
            function initExportExcel() {
                $('#stafflistsexcel').click( function(){
                    <?php if(isset($_GET['daterange'])) { ?>
                        var daterange = getUrlParameter('daterange').split('to');
                        var filter = $('#example1_filter label input').val();

                        if ( filter.length ) {
                            var query = "SELECT * FROM `jb_user` WHERE ( lastname LIKE '%"+filter+"%' OR firstname LIKE '%"+filter+"%' OR email LIKE '%"+filter+"%' OR contact_number LIKE '%"+filter+"%' OR job_title LIKE '%"+filter+"%' OR status LIKE '%"+filter+"%') AND branch_id = '<?php echo $_SESSION['Branchid']; ?>' AND level = '3' AND created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"'  ORDER BY lastname ASC";
                        } else {
                            var query = "<?php echo $queryforexcelstafflists; ?>";
                        }

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=stafflists&&filename=staff_lists_excel";
                        window.location = page;// you can use window.open also

                    <?php } else { ?>
                        var filter = $('#example1_filter label input').val();
                        
                        if ( filter.length ) {
                            var query = "SELECT * FROM `jb_user` WHERE ( lastname LIKE '%"+filter+"%' OR firstname LIKE '%"+filter+"%' OR email LIKE '%"+filter+"%' OR contact_number LIKE '%"+filter+"%' OR job_title LIKE '%"+filter+"%' OR status LIKE '%"+filter+"%' ) AND branch_id = '<?php echo $_SESSION['Branchid']; ?>' AND level = '3' ORDER BY lastname ASC";
                        } else {
                            var query = "<?php echo $queryforexcelstafflists; ?>";
                        }

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=stafflists&&filename=staff_lists_excel";
                        window.location = page;// you can use window.open also

                    <?php } ?>
                });

                $('#diagnosisexcel').click( function(){
                    <?php if(isset($_GET['daterange'])) { ?>
                        var daterange = getUrlParameter('daterange').split('to');
                        var filter = $('#tablediagnosis_filter label input').val();

                        if ( filter.length ) {
                            var query = "SELECT * FROM `jb_diagnosis` WHERE diagnosis LIKE '%"+filter+"%' AND created_at BETWEEN '"+daterange[0]+"' AND '"+daterange[1]+"' ORDER BY created_at ASC";
                        } else {
                            var query = "<?php echo $queryforexceldiagnosis; ?>";
                        }

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=diagnosis&&filename=diagnosis_lists_excel";
                        window.location = page;// you can use window.open also

                    <?php } else { ?>
                        var filter = $('#tablediagnosis_filter label input').val();
                        
                        if ( filter.length ) {
                            var query = "SELECT * FROM `jb_diagnosis` WHERE diagnosis LIKE '%"+filter+"%' ORDER BY created_at ASC";
                        } else {
                            var query = "<?php echo $queryforexceldiagnosis; ?>";
                        }

                        query = query.replace(/%/g,"percentage");
                        var page = '../ajax/generateexcel.php?querytogenerate='+query+"&&type=diagnosis&&filename=diagnosis_lists_excel";
                        window.location = page;// you can use window.open also

                    <?php } ?>
                });
            }
            // End of export

            $('.add').on('click',function(){    
                $('#create-staff').modal('show');
            });

            $(document).on('click', ".clickable", function() {
            $(".clickable").removeClass("selected");
            $(this).addClass("selected");
            ID = $(this).attr("id");
            console.log(ID);
            });


             $('.delete').on('click',function(){
                if(ID) {
                    $("#delete-modal").modal('show');
                    $("#fname").html($('#example1 #'+ID+' td:nth-child(3)').text());
                    $("#lname").html($('#example1 #'+ID+' td:nth-child(2)').text());
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });

            <?php if($_SESSION['position'] == 2): ?>
            $('.permission').on('click',function(){
                
                $('#permission [type="checkbox"]').parent().removeClass('checked');
                $('#permission [type="checkbox"]').removeAttr('checked');

                if(ID) {
                    $("#permission").modal("show");
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewstaff.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID,
                            permission: true
                        },
                        success: function(e){
                            var obj = jQuery.parseJSON(e);
                            $('.firstname').text(obj.response.firstname);
                            $('.lastname').text(obj.response.lastname);

                            if(obj.response.status == 'active') {
                                $('#permission [name="status"]').parent().addClass('checked');
                                $('#permission [name="status"]').attr('checked', 'checked');
                            }

                            $(obj.response2).each(function(key, value){
                                //Job Orders
                                if(value.name == 'job_orders') {
                                    $('#permission .job_orders [name="job_orders"]').parent().addClass('checked');
                                    $('#permission .job_orders [name="job_orders"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .job_orders [name="add"]').parent().addClass('checked');
                                        $('#permission .job_orders [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .job_orders [name="edit"]').parent().addClass('checked');
                                        $('#permission .job_orders [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .job_orders [name="delete"]').parent().addClass('checked');
                                        $('#permission .job_orders [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .job_orders [name="view"]').parent().addClass('checked');
                                        $('#permission .job_orders [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Statements of Account
                                if(value.name == 'statements_of_account') {
                                    $('#permission .statements_of_account [name="statements_of_account"]').parent().addClass('checked');
                                    $('#permission .statements_of_account [name="statements_of_account"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .statements_of_account [name="add"]').parent().addClass('checked');
                                        $('#permission .statements_of_account [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .statements_of_account [name="edit"]').parent().addClass('checked');
                                        $('#permission .statements_of_account [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .statements_of_account [name="delete"]').parent().addClass('checked');
                                        $('#permission .statements_of_account [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .statements_of_account [name="view"]').parent().addClass('checked');
                                        $('#permission .statements_of_account [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Branch
                                if(value.name == 'branch') {
                                    $('#permission .branch [name="branch"]').parent().addClass('checked');
                                    $('#permission .branch [name="branch"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .branch [name="add"]').parent().addClass('checked');
                                        $('#permission .branch [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .branch [name="edit"]').parent().addClass('checked');
                                        $('#permission .branch [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .branch [name="delete"]').parent().addClass('checked');
                                        $('#permission .branch [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .branch [name="view"]').parent().addClass('checked');
                                        $('#permission .branch [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Customers
                                if(value.name == 'customers') {
                                    $('#permission .customers [name="customers"]').parent().addClass('checked');
                                    $('#permission .customers [name="customers"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .customers [name="add"]').parent().addClass('checked');
                                        $('#permission .customers [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .customers [name="edit"]').parent().addClass('checked');
                                        $('#permission .customers [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .customers [name="delete"]').parent().addClass('checked');
                                        $('#permission .customers [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .customers [name="view"]').parent().addClass('checked');
                                        $('#permission .customers [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Technicians
                                if(value.name == 'technicians') {
                                    $('#permission .technicians [name="technicians"]').parent().addClass('checked');
                                    $('#permission .technicians [name="technicians"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .technicians [name="add"]').parent().addClass('checked');
                                        $('#permission .technicians [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .technicians [name="edit"]').parent().addClass('checked');
                                        $('#permission .technicians [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .technicians [name="delete"]').parent().addClass('checked');
                                        $('#permission .technicians [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .technicians [name="view"]').parent().addClass('checked');
                                        $('#permission .technicians [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Parts
                                if(value.name == 'parts') {
                                    $('#permission .parts [name="parts"]').parent().addClass('checked');
                                    $('#permission .parts [name="parts"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .parts [name="add"]').parent().addClass('checked');
                                        $('#permission .parts [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .parts [name="edit"]').parent().addClass('checked');
                                        $('#permission .parts [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .parts [name="delete"]').parent().addClass('checked');
                                        $('#permission .parts [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .parts [name="view"]').parent().addClass('checked');
                                        $('#permission .parts [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Staff
                                if(value.name == 'staff') {
                                    $('#permission .staff [name="staff"]').parent().addClass('checked');
                                    $('#permission .staff [name="staff"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .staff [name="add"]').parent().addClass('checked');
                                        $('#permission .staff [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .staff [name="edit"]').parent().addClass('checked');
                                        $('#permission .staff [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .staff [name="delete"]').parent().addClass('checked');
                                        $('#permission .staff [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .staff [name="view"]').parent().addClass('checked');
                                        $('#permission .staff [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Diagnosis
                                if(value.name == 'diagnosis') {
                                    $('#permission .diagnosis [name="diagnosis"]').parent().addClass('checked');
                                    $('#permission .diagnosis [name="diagnosis"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .diagnosis [name="add"]').parent().addClass('checked');
                                        $('#permission .diagnosis [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .diagnosis [name="edit"]').parent().addClass('checked');
                                        $('#permission .diagnosis [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .diagnosis [name="delete"]').parent().addClass('checked');
                                        $('#permission .diagnosis [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .diagnosis [name="view"]').parent().addClass('checked');
                                        $('#permission .diagnosis [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Brands
                                if(value.name == 'brands') {
                                    $('#permission .brands [name="brands"]').parent().addClass('checked');
                                    $('#permission .brands [name="brands"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .brands [name="add"]').parent().addClass('checked');
                                        $('#permission .brands [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .brands [name="edit"]').parent().addClass('checked');
                                        $('#permission .brands [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .brands [name="delete"]').parent().addClass('checked');
                                        $('#permission .brands [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .brands [name="view"]').parent().addClass('checked');
                                        $('#permission .brands [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Main Category
                                if(value.name == 'main_category') {
                                    $('#permission .main_category [name="main_category"]').parent().addClass('checked');
                                    $('#permission .main_category [name="main_category"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .main_category [name="add"]').parent().addClass('checked');
                                        $('#permission .main_category [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .main_category [name="edit"]').parent().addClass('checked');
                                        $('#permission .main_category [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .main_category [name="delete"]').parent().addClass('checked');
                                        $('#permission .main_category [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .main_category [name="view"]').parent().addClass('checked');
                                        $('#permission .main_category [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Models
                                if(value.name == 'models') {
                                    $('#permission .models [name="models"]').parent().addClass('checked');
                                    $('#permission .models [name="models"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .models [name="add"]').parent().addClass('checked');
                                        $('#permission .models [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .models [name="edit"]').parent().addClass('checked');
                                        $('#permission .models [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .models [name="delete"]').parent().addClass('checked');
                                        $('#permission .models [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .models [name="view"]').parent().addClass('checked');
                                        $('#permission .models [name="view"]').attr('checked', 'checked');
                                    }
                                }

                                //Sales Report
                                if(value.name == 'sales_report') {
                                    $('#permission .sales_report [name="sales_report"]').parent().addClass('checked');
                                    $('#permission .sales_report [name="sales_report"]').attr('checked', 'checked');

                                    if(value.add_status == 'yes') {
                                        $('#permission .sales_report [name="add"]').parent().addClass('checked');
                                        $('#permission .sales_report [name="add"]').attr('checked', 'checked');
                                    }

                                    if(value.edit_status == 'yes') {
                                        $('#permission .sales_report [name="edit"]').parent().addClass('checked');
                                        $('#permission .sales_report [name="edit"]').attr('checked', 'checked');
                                    }

                                    if(value.delete_status == 'yes') {
                                        $('#permission .sales_report [name="delete"]').parent().addClass('checked');
                                        $('#permission .sales_report [name="delete"]').attr('checked', 'checked');
                                    }

                                    if(value.view_status == 'yes') {
                                        $('#permission .sales_report [name="view"]').parent().addClass('checked');
                                        $('#permission .sales_report [name="view"]').attr('checked', 'checked');
                                    }
                                }
                            });
                        }
                    });
                }else {
                    $('.errormessage').html("Please make a selection from the list.");
                    $("#selecrecord-modal").modal("show");
                }
            });

            $('#savepermission').click( function(){
                $('.modald').fadeIn('fast');

                var data = {};
                data['action'] = 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=';
                data['status'] = ( $('#permission input[name="status"]').is(':checked') ) ? $('#permission input[name="status"]:checked').val() : 'inactive';
                data['modulename'] = $('#permission .modulename:checked').map(function(){ return $(this).val(); }).get();
                data['id'] = ID;

                if( $('[name="job_orders"]').is(':checked') ) {
                    data['job_orders'] = $('#permission .modulestatus_job_orders:checked').map(function(){ return $(this).val(); }).get()
                } 
                if( $('[name="statements_of_account"]').is(':checked') ) {
                    data['statements_of_account'] = $('#permission .modulestatus_statements_of_account:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="branch"]').is(':checked') ) {
                    data['branch'] = $('#permission .modulestatus_branch:checked ').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="customers"]').is(':checked') ) {
                    data['customers'] = $('#permission .modulestatus_customers:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="technicians"]').is(':checked') ) {
                    data['technicians'] = $('#permission .modulestatus_technicians:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="parts"]').is(':checked') ) {
                    data['parts'] = $('#permission .modulestatus_parts:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="staff"]').is(':checked') ) {
                    data['staff'] = $('#permission .modulestatus_staff:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="diagnosis"]').is(':checked') ) {
                    data['diagnosis'] = $('#permission .modulestatus_diagnosis:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="brands"]').is(':checked') ) {
                    data['brands'] = $('#permission .modulestatus_brands:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="main_category"]').is(':checked') ) {
                    data['main_category'] = $('#permission .modulestatus_main_category:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="diagnosis"]').is(':checked') ) {
                    data['diagnosis'] = $('#permission .modulestatus_diagnosis:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="models"]').is(':checked') ) {
                    data['models'] = $('#permission .modulestatus_models:checked').map(function(){ return $(this).val(); }).get()
                }
                if( $('[name="sales_report"]').is(':checked') ) {
                    data['sales_report'] = $('#permission .modulestatus_sales_report:checked').map(function(){ return $(this).val(); }).get()
                }

                $.ajax({
                    type: 'POST',
                    url: '../ajax/savepermission.php',
                    data: data,
                    success: function(e){
                        if(e == 'success') {
                            location.reload();
                        }
                    }
                });

            });
            <?php endif;?>

             $('.edit').on('click',function(){
                if(ID){
                    $('.modald').fadeIn('fast');
                    $('#edit-staff').modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewstaff.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $('input[name="elastname"]').val(obj.response.lastname);
                            $('input[name="efirstname"]').val(obj.response.firstname);
                            $('input[name="emidname"]').val(obj.response.midname);
                            $('input[name="econtact"]').val(obj.response.contact_number);
                            $('input[name="eaddress"]').val(obj.response.address);
                            $('input[name="eemail"]').val(obj.response.email);
                            $('input[name="enickname"]').val(obj.response.nicknake);
                            $('input[name="ejobtitle"]').val(obj.response.job_title);
                        }
                    });
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });

            $('.editd').on('click',function(){
                if(ID){
                    $('#edit-diagnosis').modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewdiagnosis.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            id: ID
                        },
                        success: function(e){
                            
                            var obj = jQuery.parseJSON(e);
                            $('input[name="ediagnosis"]').val(obj.response[0].diagnosis);
                        }
                    });
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });


            $("#deletedis").on('click',function(){
                 $.ajax({
                    type: 'POST',
                    url: '../ajax/deletediagnosis.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        id: ID
                    },
                    success: function(e){
                            
                        if(e == "success"){
                            $("#delete-modal2").modal('hide');
                            $("#diagnosis tr#" + ID).remove();
                            ID = "";
                            location.reload();
                        }else {

                        }
                    }
                });
            });

            $('.deleted').on('click',function(){
                 if(ID) {
                    $("#delete-modal2").modal('show');
                    $("#diagnosisname").html($('#tablediagnosis #'+ID+' td:nth-child(2)').text());
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });
            $('.deletedd').on('click',function(){
                 if(ID) {
                    $("#delete-modal22").modal('show');
                    $("#idhere22").html(ID);
                }else {
                    $("#selecrecord-modal").modal("show");
                }
            });


            $('.addd').on('click',function(){
                $('#create-diagnosis').modal('show');
            });

            $("#creatediagnosis").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
                "diagnosis":{
                    required: true,
                    minlength:1
                }
            },
            // Specify the validation error messages
            messages: {
                diagnosis:{
                required: "Please provide a diagnosis",
                minlength: "Your diagnosis must be at least 2 characters long",
                }
            },
            submitHandler: function(form) {
                $('.modald').fadeIn('slow');
                $.ajax({
                    type: 'POST',
                    url: '../ajax/creatediagnosis.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        diagnosis: $("[name=diagnosis]").val()
                    },
                    success: function(e){
                            
                        if(e == "success"){
                            location.reload();
                            } else {
                                $('.modald').fadeOut('slow');
                                $('input[name="diagnosis"]').parent().find('p.error').remove();
                                $('input[name="diagnosis"]').parent().append('<p for="diagnosis" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Please choose a different diagnosis name.</p>');
                            }
                        }
                    });
                    return false;
                }
            });

            $("#editdiagnosis").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
                    "ediagnosis":{
                        required: true,
                        minlength:1
                    }
                },
                // Specify the validation error messages
                messages: {
                    ediagnosis:{
                    required: "Please provide a diagnosis",
                    minlength: "Your diagnosis must be at least 2 characters long",
                    }
                },
                submitHandler: function(form) {
                    $('.modald').fadeIn('slow');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/updatediagnosis.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            id: ID,
                            diagnosis: $("[name=ediagnosis]").val()
                        },
                        success: function(e){
                                
                                if(e == "success"){
                                    $('#' + ID + ' .dia').html($("[name=ediagnosis]").val());
                                    $('#edit-diagnosis').modal('hide');
                                    location.reload();
                                    } else {
                                        $('.modald').fadeOut('slow');
                                        $('input[name="ediagnosis"]').parent().find('p.error').remove();
                                        $('input[name="ediagnosis"]').parent().append('<p for="ediagnosis" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Please choose a different diagnosis name.</p>');
                                    }
                                }
                        });
                        return false;
                    }
                });

            $('.view').on('click',function(){    
                if(ID){
                    $('.modald').fadeIn('fast');
                    $("#view-staff").modal('show');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/viewstaff.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            jobid: ID
                        },
                        success: function(e){
                            
                            $('.modald').fadeOut('fast');
                            var obj = jQuery.parseJSON(e);
                            $('span.firstname').html(obj.response.firstname);
                            $('span.lastname').html(obj.response.lastname);
                            $('span.nickname').html(obj.response.nicknake);
                            $('span.contact').html(obj.response.contact_number);
                            $('span.address').html(obj.response.address);
                            $('span.email').html(obj.response.email);
                            $('span.jobtitle').html(obj.response.job_title);
                            $('span.dateadd').html(obj.response.created_at);
                            $('span.dateadd').html(obj.response.status);
                        }
                    });
                }else{
                    $('.errormessage').html("Please make a selection from the list.");
                    $("#selecrecord-modal").modal("show");
                }
            });

            $("#deleteitem").on('click',function(){
                $.ajax({
                    type: 'POST',
                    url: '../ajax/deletestaff.php',
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


            $('.contextual_menu').css('display','none');
            $("#editstaff").validate({
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
                "eemail":{
                    email: true,
                    required: true,
                    minlength:4
                },
                "eaddress":{
                    required: true,
                    minlength:2
                },
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
                econtact:{
                    required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a number.",
                    minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your number must be at least 11 interger long."
                },
                eemail:{
                    email: "<i class='fa fa-warning opacity-icon'></i> Error: Email is invalid.",
                    required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a email address.",
                    minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your email must be at least 4 characters long."
                },
                eaddress:{
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

                if ( error == 0 ) {

                    $('.modald').fadeIn('fast');
                    $.ajax({
                        type: 'POST',
                        url: '../ajax/editstaff.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            password: $("[name=enewpassword]").val(),
                            firstname: $("[name=efirstname]").val(),
                            lastname: $("[name=elastname]").val(),
                            midname: $("[name=emidname]").val(),
                            nickname: $("[name=enickname]").val(),
                            contact: $("[name=econtact]").val(),
                            email: $("[name=eemail]").val(),
                            address: $("[name=eaddress]").val(),
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
                                    $('input[name="efirstname"]').parent().append('<p for="efirstname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Name must not contain special characters.</p>');
                                }

                                if( $.type(obj.lastname) != 'undefined' && obj.lastname == true ) {
                                    $('input[name="elastname"]').parent().find('p.error').remove();
                                    $('input[name="elastname"]').parent().append('<p for="elastname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Name must not contain special characters.</p>');
                                }

                                if( $.type(obj.midname) != 'undefined' && obj.midname == true ) {
                                    $('input[name="emidname"]').parent().find('p.error').remove();
                                    $('input[name="emidname"]').parent().append('<p for="emidname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Name must not contain special characters.</p>');
                                }

                                if( $.type(obj.nickname) != 'undefined' && obj.nickname == true ) {
                                    $('input[name="enickname"]').parent().find('p.error').remove();
                                    $('input[name="enickname"]').parent().append('<p for="enickname" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Nick Name must not contain special characters.</p>');
                                }

                                if( $.type(obj.emailaddress) != 'undefined' && obj.emailaddress == true ) {
                                    $('input[name="eemail"]').parent().find('p.error').remove();
                                    $('input[name="eemail"]').parent().append('<p for="eemail" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Email address is not unique.</p>');
                                }
                            }
                        }

                    }

                    });

                }
                return false;
            }
            });

        $("#saveemails").validate({
            errorElement: 'p',
            // Specify the validation rules
            rules: {
            "feedback":{
                required: true,
                minlength:2,
                email: true
            },
            "adminemail":{
                required: true,
                minlength:2,
                email: true
            }
            },
            // Specify the validation error messages
            messages: {
            feedback:{
                required: "Please provide a Feedback Email.",
                minlength: "Your password must be at least 2 characters long.",
            },
            adminemail:{
                required: "Please provide a Admin Email",
                minlength: "Your password must be at least 2 characters long.",
            }
            },
            submitHandler: function(form) {
                    $('.modald').fadeIn('fast');
                     $.ajax({
                    type: 'POST',
                    url: '../ajax/createemail.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        feedback: $("[name=feedback]").val(),
                        adminemail: $("[name=adminemail]").val(),
                        branchid: "<?php echo $_SESSION['Branchid']; ?>",
                        type: '1'
                    },
                    success: function(e){
                            
                            $('.modald').fadeOut('fast');
                        if(e == "success"){
                            location.reload();
                        }
                        }
                    });
                    return false;
                }
            });



            $("#createstaff").validate({
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
                "email":{
                    email: true,
                    required: true,
                    minlength:4
                },
                "address":{
                    required: true,
                    minlength:2
                },
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
                contact:{
                    required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a number.",
                    minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your number must be at least 11 interger long."
                },
                email:{
                    email: "<i class='fa fa-warning opacity-icon'></i> Error: Email is invalid.",
                    required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a email address.",
                    minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your email must be at least 4 characters long."
                },
                address:{
                    required: "<i class='fa fa-warning opacity-icon'></i> Error: Please provide a address.",
                    minlength: "<i class='fa fa-warning opacity-icon'></i> Error: Your address must be at least 2 characters long."
                }  
            },
            submitHandler: function(form) {
                var username = $('input[name="username"]').val();
                var password = $('input[name="password"]').val();
                var confirmpassword = $('input[name="confirmpassword"]').val();
                var error = 0;

                if ( password.indexOf(' ') >= 0 ) {
                    $('input[name="password"]').parent().find('p.error').remove();
                    $('input[name="password"]').parent().append('<p for="password" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Password must not contain spaces.</p>');
                    error = 1;
                }

                if ( confirmpassword.indexOf(' ') >= 0 ) {
                    $('input[name="confirmpassword"]').parent().find('p.error').remove();
                    $('input[name="confirmpassword"]').parent().append('<p for="confirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Confirm password must not contain spaces.</p>');
                    error = 1;
                }

                if( password != confirmpassword ) {
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
                    url: '../ajax/createstaff.php',
                    data: {
                        action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                        username: $("[name=username]").val(),
                        password: $("[name=password]").val(),
                        firstname: $("[name=firstname]").val(),
                        lastname: $("[name=lastname]").val(),
                        midname: $("[name=midname]").val(),
                        nickname: $("[name=nickname]").val(),
                        contact: $("[name=contact]").val(),
                        email: $("[name=email]").val(),
                        address: $("[name=address]").val(),
                        jobtitle: $("[name=jobtitle]").val(),
                        branchid: "<?php echo $_SESSION['Branchid']; ?>"
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
                                    $('input[name="emailaddress"]').parent().append('<p for="emailaddress" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Email address is not unique</p>');
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