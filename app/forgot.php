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
        <form id="forgot" name="login" method="post" role="form">
            <div class="body bg-gray-login">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon txtstyle"><i class="fa fa-envelope opacity-icon"></i></span>
                        <input type="email" class="form-control txtstyle" name="email" autofocus  placeholder="Email Address">
                    </div>     
                </div>    
            </div>
            <div class="footer">                                                               
                <button type="submit" class="btn bg-blue btn-block">Forgot Password</button>
            </div>
        </form>
    </div>
<div class="modald">
    <img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif">
</div>

<div class="modal fade" id="invalidemail-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Email address cannot be found.</h4>
        </div>
        <div class="modal-body">
         <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
        <div class="clear"></div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successmessage-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa  fa-check"></i> Success!. Please check your mail.</h4>
        </div>
        <div class="modal-body">
         <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
        <div class="clear"></div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tryagain-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa  fa-exclamation-triangle"></i> Error!. Please try again.</h4>
        </div>
        <div class="modal-body">
         <center><button type="submit" id="savejob" class="btn btn-success"  data-dismiss="modal"><i class="fa fa-eraser"></i> OK </button></center>
        <div class="clear"></div>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){  

    function login(e){
        $('.modald').fadeOut('slow');
    }

    function toasthide() {
        $('.toast-message').fadeOut('slow');
    }

    function success(){
        $('.modald').fadeOut('fast');
        $('.modald').fadeOut('slow');  
        $('input[name="email"]').val("").focus();
        $('.toast-message').fadeIn('medium');
        setTimeout(toasthide,1000);
        $('#successmessage-modal').modal('show');
    }
    
    function errorEmail(){
        $('.modald').fadeOut('fast');
        $('.modald').fadeOut('slow');  
        $('input[name="email"]').val("").focus();
        $('.toast-message').fadeIn('medium');
        setTimeout(toasthide,1000);
        $('#invalidemail-modal').modal('show');
    }

    function tryAgain(){
        $('.modald').fadeOut('fast');
        $('.modald').fadeOut('slow');  
        $('input[name="email"]').val("").focus();
        $('.toast-message').fadeIn('medium');
        setTimeout(toasthide,1000);
        $('#tryagain-modal').modal('show');
    }

    $("#forgot").validate({
        errorElement: 'div',
        rules: {
            "email":{
                email: true,
                required: true
            }
        },
        messages: {
            email:{
                email: "Email is invalid..",
                required: "Please provide a email address."
            }
        },
        submitHandler: function(form) {
            $('.modald').fadeIn('slow');
            $.ajax({
                type: 'POST',
                url: 'ajax/forgot.php',
                data: {
                    action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
                    email: $("[name=email]").val()
                },
                success: function(e){
                    var obj = jQuery.parseJSON(e);
                    if( obj.status == 200 ) {
                        setTimeout(success,2000);
                    } else if ( obj.status == 404 ) {
                        setTimeout(errorEmail,2000);
                    } else {
                        setTimeout(tryAgain,2000);
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