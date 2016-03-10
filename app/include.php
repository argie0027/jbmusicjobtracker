<?php 
include '../PHPMailer-master/PHPMailerAutoload.php';

function sendMail($email, $subject, $body) {
	$mail = new PHPMailer;

	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465;
	$mail->Username = 'jbmusicjobtracker@gmail.com';
	$mail->Password = 'xe43gd40wg';  

	$mail->setFrom('system@jbmusicjobtracker.com', 'JB MUSIC & SPORTS');
	$mail->addAddress($email);
	$mail->addReplyTo('jbmusicjobtracker@gmail.com', 'Information');

	$mail->isHTML(true);

	$mail->Subject = $subject;
	$mail->Body    = $body;

	if(!$mail->send()) {
	    return $mail->ErrorInfo;
	} else {
	    return true;
	}

}
?>