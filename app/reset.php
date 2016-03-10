<?php
include '../include.php';
include 'ui_branch.php';

$checker = "SELECT * FROM jb_user WHERE forgot_code='".$_GET["code"]."'";
$checkerQuery = $db->ReadData($checker);

if ( sha1($checkerQuery[0]['email']) == $_GET["member"] && $_GET["email"] == 'true' ) { 
	htmlHeader('login');
	?>
<div class="form-box form-box-color" id="login-box">
        <div class="jb-logo"><img src="<?php echo SITE_IMAGES_DIR ?>logo2.png"></div>
        <form id="reset" name="login" method="post" role="form">
            <div class="body bg-gray-login">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon txtstyle"><i class="fa fa-lock opacity-icon"></i></span>
                        <input type="password" class="form-control txtstyle" name="newpassword" autofocus  placeholder="New Password">
                    </div>    
                </div> 
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon txtstyle"><i class="fa fa-lock opacity-icon"></i></span>
                        <input type="password" class="form-control txtstyle" name="confirmpassword" autofocus  placeholder="Confirm Password">
                    </div>    
                </div>  
            </div>
            <div class="footer">                                                               
                <button type="submit" class="btn bg-blue btn-block">Reset Password</button>
            </div>
        </form>
    </div>
<div class="modald">
    <img src="<?php echo SITE_IMAGES_DIR; ?>ajax.gif">
</div>

<div class="modal fade" id="successmessage-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa  fa-check"></i> Success!. Please proceed to signin.</h4>
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
        $('input').val("").focus();
        $('.toast-message').fadeIn('medium');
        setTimeout(toasthide,1000);
        $('#successmessage-modal').modal('show');
    }

    function tryAgain(){
        $('.modald').fadeOut('fast');
        $('.modald').fadeOut('slow');  
        $('input').val("").focus();
        $('.toast-message').fadeIn('medium');
        setTimeout(toasthide,1000);
        $('#tryagain-modal').modal('show');
    }

    $("#reset").validate({
        errorElement: 'div',
        rules: {
            "newpassword":{
                required: true,
                minlength: 8
            },
            "confirmpassword":{
                required: true,
                minlength: 8
            }
        },
        messages: {
            newpassword:{
                required: "Please provide a new password.",
                minlength: "Your password must be at least 8 characters long.",
            },
            confirmpassword:{
                required: "Please provide a confirm password",
                minlength: "Your password must be at least 8 characters long",
            }
        },
        submitHandler: function(form) {
        	var newpassword = $('input[name="newpassword"]').val();
            var confirmpassword = $('input[name="confirmpassword"]').val();
            var error = 0;

            if( newpassword != confirmpassword ) {
                $('input[name="confirmpassword"]').parent().find('div.error').remove();
                $('input[name="confirmpassword"]').parent().append('<div for="confirmpassword" generated="true" class="error">Confirm Password is incorrect.</div>');
                error = 1;
            }

            if ( newpassword.indexOf(' ') >= 0 ) {
                $('input[name="newpassword"]').parent().find('div.error').remove();
                $('input[name="newpassword"]').parent().append('<div for="newpassword" generated="true" class="error">Password must not contain spaces.</div>');
                error = 1;
            }

            if ( confirmpassword.indexOf(' ') >= 0 ) {
                $('input[name="confirmpassword"]').parent().find('div.error').remove();
                $('input[name="confirmpassword"]').parent().append('<div for="confirmpassword" generated="true" class="error">Confirm password must not contain spaces.</div>');
                error = 1;
            }

            if ( error == 0 ) {
	            $.ajax({
	                type: 'POST',
	                url: 'ajax/reset.php',
	                data: {
	                    action: 'MC4yMTQyNzkwMCAxNDI3NzgxMDE1LTgtVlVrNTRZWXpTY240MlE5dXY0ZE1GaTFFNkJyV0o4a2Q=',
	                    password: $("[name=newpassword]").val(),
                        member: '<?php echo $_GET["member"]; ?>',
	                    reset: '<?php echo $_GET["code"]; ?>'
	                },
	                success: function(e){
	                	$('.modald').fadeIn('slow');
	                	var obj = jQuery.parseJSON(e);
	                	if(obj.status == 200){
                            setTimeout(success,2000);
                        } else {
                        	setTimeout(tryAgain,2000);
                        }

	                }
	            });
        	}
            return false;
        }
        });
});
</script>
<?php
htmlFooter('login');
?>
<?php
} else {
	header('location: ./');
	exit();
}
?>