<?php
	include '../../include.php';
	include '../ui_main.php';

	htmlHeader('dashboard');
	global $url;

    $notif = split(',', NOTIF);
    // echo $notif[3];
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
                    <?php 
                        $qu = "SELECT a.jobid, a.soaid, a.customerid, a.branchid, a.partsid, a.technicianid, a.item, a.diagnosis, a.remarks, a.status_id,a.created_at,a.isdeleted, a.repair_status, b.customerid, b.name, c.branch_id, c.branch_name, d.tech_id, d.name as technam FROM jb_joborder a, jb_customer b, jb_branch c, jb_technicians d WHERE a.customerid = b.customerid AND a.branchid = c.branch_id AND a.technicianid = d.tech_id AND a.isdeleted = '0'  ORDER BY created_at DESC";
                        $query = $db->ReadData($qu);  
                        $_SESSION['jobcount'] = $db->GetNumberOfRows();  
                        sidebarMenu($_SESSION['jobcount']); ?>
                    <?php 
                        // $user = new user();
                        // echo $user->get_current_user("mac");
                    ?>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                
                <?php breadcrumps('Profile'); ?>

                <!-- Main content -->
                <section class="content">

                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                                $sql = "SELECT * FROM jb_user WHERE id = '".$_SESSION['id']."'";
                                $query = $db->ReadData($sql);
                            ?>
                            <form id="editprofile" class="change_to_edit" name="profile" method="post" role="form">
                                <div class="form-group col-sm-12">
                                    <table class="table-profile col-xs-4">
                                        <tr>
                                            <td class="text-right"><label>Last Name</label></td>
                                            <td><div class="form-group">
                                                    <input type="text" name="lastname" class="form-control" placeholder="Last Name" value="<?php echo $query[0]['lastname']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>First Name</label></td>
                                            <td><div class="form-group">
                                                    <input type="text" name="firstname" class="form-control" placeholder="First Name" value="<?php echo $query[0]['firstname']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Middle Initial</label></td>
                                            <td><div class="form-group">
                                                    <input type="text" name="midname" class="form-control" maxlength="1" placeholder="Middle Initial" value="<?php echo $query[0]['midname']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Nick Name</label></td>
                                            <td><div class="form-group">
                                                    <input type="text" name="nickname" class="form-control" placeholder="Nick Name" value="<?php echo $query[0]['nicknake']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Contact</label></td>
                                            <td><div class="form-group">
                                                    <input type="number" name="contact" class="form-control" placeholder="Contact" value="<?php echo $query[0]['contact_number']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Email Address</label></td>
                                            <td><div class="form-group">
                                                    <input type="text" name="email" class="form-control" placeholder="Email Address" value="<?php echo $query[0]['email']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Address</label></td>
                                            <td><div class="form-group">
                                                    <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo $query[0]['address']; ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Current Password</label></td>
                                            <td><div class="form-group">
                                                    <input type="password" name="currentpassword" class="form-control" placeholder="Current Password">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>New Password</label></td>
                                            <td><div class="form-group">
                                                    <input type="password" name="newpassword" class="form-control" placeholder="New Password">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><label>Confirm Password</label></td>
                                            <td><div class="form-group">
                                                    <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="text-right"><button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Profile </button></td>
                                        </tr>
                                    </table>
                                    <div class="col-xs-6">
                                        <!-- <div class="btn btn-success imgInp-mac btn-file">
                                            <i class="fa fa-paperclip"></i> Attachment 2x2
                                            <input type="file" name="image" id="imgInp">
                                            <input type="hidden" name="image">
                                        </div> -->
                                        <?php
                                            $image = ($query[0]['image']) ? SITE_IMAGES_DIR.'profile_pic/'.$query[0]['image'] : SITE_IMAGES_DIR.'avatar3.png';
                                        ?>
                                        <div class="profile-wrap" style="background-image: url('<?php echo $image; ?>');">
                                            <input type="hidden" name="image" <?php if($query[0]['image']):?>value="<?php echo $query[0]['image']; ?>"<?php endif; ?>>
                                            <div id="uploaded"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.row -->

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <script type="text/javascript">
        $(document).ready(function() {
            $('.contextual_menu').remove();

            $("#editprofile").validate({
                errorElement: 'p',
                // Specify the validation rules
                rules: {
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

                    var currentpassword = $('input[name="currentpassword"]').val();
                    var newpassword = $('input[name="newpassword"]').val();
                    var confirmpassword = $('input[name="confirmpassword"]').val();
                    var error = 0;

                    if( currentpassword.length ) {
                        if ( currentpassword.length < 8 ) {
                            $('input[name="currentpassword"]').parent().find('p.error').remove();
                            $('input[name="currentpassword"]').parent().append('<p for="currentpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Your currentpassword must be at least 8 characters long.</p>');

                            error = 1;
                        }

                        if( newpassword.length < 8 ) {
                            $('input[name="newpassword"]').parent().find('p.error').remove();
                            $('input[name="newpassword"]').parent().append('<p for="newpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Your new passsword must be at least 8 characters long.</p>');

                            error = 1;
                        }  

                        if( confirmpassword.length < 8 ) {
                            $('input[name="confirmpassword"]').parent().find('p.error').remove();
                            $('input[name="confirmpassword"]').parent().append('<p for="confirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Your confirm passsword must be at least 8 characters long.</p>');

                            error = 1;
                        }
                     }

                    if ( currentpassword.length >= 8 && newpassword.length >= 8 && confirmpassword.length >= 8 ) {

                        if ( currentpassword.indexOf(' ') >= 0 ) {
                            $('input[name="currentpassword"]').parent().find('p.error').remove();
                            $('input[name="currentpassword"]').parent().append('<p for="currentpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Current password must not contain spaces.</p>');
                            error = 1;
                        }

                        if ( newpassword.indexOf(' ') >= 0 ) {
                            $('input[name="newpassword"]').parent().find('p.error').remove();
                            $('input[name="newpassword"]').parent().append('<p for="newpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: New password must not contain spaces.</p>');
                            error = 1;
                        }

                        if ( confirmpassword.indexOf(' ') >= 0 ) {
                            $('input[name="confirmpassword"]').parent().find('p.error').remove();
                            $('input[name="confirmpassword"]').parent().append('<p for="confirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Confirm password must not contain spaces.</p>');
                            error = 1;
                        }

                        if( newpassword != confirmpassword ) {
                            $('input[name="confirmpassword"]').parent().find('p.error').remove();
                            $('input[name="confirmpassword"]').parent().append('<p for="confirmpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Confirm password is incorrect.</p>');
                            error = 1;
                        }
                    }

                    if ( error == 0 ) {
                        
                        $.ajax({
                        type: 'POST',
                        url: '../ajax/updateprofile.php',
                        data: {
                            action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                            currentpassword: $("[name=currentpassword]").val(),
                            newpassword: $("[name=newpassword]").val(),
                            firstname: $("[name=firstname]").val(),
                            lastname: $("[name=lastname]").val(),
                            midname: $("[name=midname]").val(),
                            nickname: $("[name=nickname]").val(),
                            contact: $("[name=contact]").val(),
                            email: $("[name=email]").val(),
                            address: $("[name=address]").val(),
                            image: $("[name=image]").val(),
                            id: '<?php echo $_SESSION['id']; ?>'
                        },
                        success: function(e){
                            var obj = jQuery.parseJSON(e);
                            $('.modald').fadeIn('slow');
                            if(obj.status == 200){
                                location.reload();
                            } else {
                                if(obj.status == 101) {
                                    $('.modald').fadeOut('slow');

                                    if( $.type(obj.currentpassword) != 'undefined' && obj.currentpassword == true ) {
                                        $('input[name="currentpassword"]').parent().find('p.error').remove();
                                        $('input[name="currentpassword"]').parent().append('<p for="currentpassword" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Invalid current password.</p>');
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
                                        $('input[name="email"]').parent().find('p.error').remove();
                                        $('input[name="email"]').parent().append('<p for="email" generated="true" class="error"><i class="fa fa-warning opacity-icon"></i> Error: Email address is not unique</p>');
                                    }
                                    
                                }
                            }

                        }
                        });
                    }

                    return false;
                }
            });

            $('.profile-wrap').mfupload({
        
                type        : 'jpg,jpeg,png',    //all types
                maxsize     : 2,
                post_upload : "../ajax/upload.php",
                folder      : "../resources/img/profile_pic/",
                ini_text    : "",
                over_text   : "",
                over_col    : '',
                over_bkcol  : '',
                
                init        : function(){     
                    $("#uploaded").empty();  
                },
                
                start       : function(result){     
                    $("#uploaded").append("<div id='PRO"+result.fileno+"' class='progressbar'></div>");
                },

                loaded      : function(result){
                    $('.profile-wrap').css('background-image','url('+result.path+''+result.filename+')');
                    $('[name="image"]').val(result.filename);
                },

                progress    : function(result){
                    $("#PRO"+result.fileno).css("width", result.perc+"%");
                    if( result.perc == 100 ) {
                        $('.progressbar').remove();
                    }
                },

                error       : function(error){
                    errors += error.filename+": "+error.err_des+"\n";
                },

                completed   : function(errors){}
            });

        });

        </script>
<?php
	htmlFooter('dashboard');
?>