<?php
include '../include.php';
include 'ui_branch.php';
if(!isset($_SESSION['position'])){

}else if($_SESSION['position'] == 0) {
    header('location: head_office/dashboard.php');
    exit;
}else{
    header('location: branch/dashboard.php');
    exit;
}

global $url;

htmlHeader('login');
?>
    <div class="form-box form-box-color" id="login-box">
        <div class="jb-logo"><img src="<?php echo SITE_IMAGES_DIR ?>logo2.png"></div>
        <form id="login" name="login" method="post" role="form">
            <div class="body bg-gray-login">
                
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon txtstyle"><i class="fa  fa-user opacity-icon"></i></span>
                        <input type="text" class="form-control txtstyle" name="username" autofocus=""  placeholder="Username">
                    </div>     
                </div> 
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon txtstyle"><i class="fa fa-lock opacity-icon"></i></span>
                        <input type="password" class="form-control txtstyle" name="password"  placeholder="Password">
                    </div>     
                </div>    
            </div>
            <div class="footer">                                                               
                <button type="submit" class="btn bg-blue btn-block">Sign in</button>
                <a href="<?php echo SITE_URL; ?>forgot.php" class="btn btn-warning btn-block">Forgot Password</a>
            </div>
        </form>

        <div class="margin text-center">
            <br/>
            <a href="https://www.facebook.com/JBMusicPh?fref=ts"><button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button></a>
            <a href="https://twitter.com/jbmusic_ph"><button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button></a>

        </div>
    </div>
<div class="modald">
    <img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif">
</div>

 <div class="modal fade" id="invalidpassword-modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Invalid username or password.</h4>
        </div>
        <div class="modal-body">
         <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
        <div class="clear"></div>
        </div><!-- /.modal-content --> 
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.modal -->

<script type="text/javascript">
$(document).ready(function(){  

    function login(e){
        $('.modald').fadeOut('slow');
    }

    function toasthide() {
        $('.toast-message').fadeOut('slow');
    }
    
    function errorlogin(){
        $('.modald').fadeOut('fast');
        $('.modald').fadeOut('slow');  
        $('input[name="password"]').val("").focus();
        $('.toast-message').fadeIn('medium');
        setTimeout(toasthide,1000);
        $('#invalidpassword-modal').modal('show');
    }
        $("#login").validate({
        errorElement: 'div',
        // Specify the validation rules
        rules: {
            "username":{
            required: true,     
            minlength:3
            },
            "password":{
            required: true,     
            minlength:8
            }
        },

        // Specify the validation error messages
        messages: {
            username:{
                 required: "Please provide a username",
                 minlength: "Your password must be at least 3 characters long",
            },
            password:{
                 required: "Please provide a password",
                 minlength: "Your password must be at least 8 characters long"
            }
        },

        submitHandler: function(form) {
            $('.modald').fadeIn('slow');
            $.ajax({
                type: 'POST',
                url: 'ajax/login.php',
                data: {
                    action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                    username: $("[name=username]").val(),
                    password: $("[name=password]").val()
                },
                success: function(e){
                    
                    if(e == "true1"){
                        window.location = "branch/dashboard.php";
                    }else if(e == "true") {
                        window.location = "head_office/dashboard.php";
                    }else {
                        setTimeout(errorlogin,2000);
                    }

                }
            });
            return false;
        }
        });
});
</script>
<?php
htmlFooter('login');
?>