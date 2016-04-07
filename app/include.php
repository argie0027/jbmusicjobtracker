<?php 

include '../PHPMailer-master/PHPMailerAutoload.php';

function sendMail($email, $subject, $body) {

	$config = require dirname(__FILE__) .'/../config/mailer.php';

	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = $config['host'];
	$mail->Port = $config['port'];
	$mail->Username = $config['username'];
	$mail->Password = $config['password'];  

	$mail->setFrom($config['sender'], $config['sender_name']);
	$mail->addReplyTo($config['username'], $config['replyto_name']);

	$mail->addAddress($email);

	$mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $body;   

	$mail->addCustomHeader('Organization', $config['organization']);
	$mail->Priority = $config['priority'];
	$mail->XMailer = 'PHP' .phpversion();

	if(!$mail->send()) {
	    return $mail->ErrorInfo;
	}
	else { 
	    return true;
	}
}

?>