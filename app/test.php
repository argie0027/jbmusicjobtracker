<?php
$name = "Mac";
$branch = "JB MUSIC & SPORTS";
$linkgen = "sdfsdf";
$email = "mcclynrey@gmail.com";
$partcost = "123";
$servicescharge  = "123";
$chargetotal = "123";
$subject = 'Repaired Item (JB MUSIC & SPORTS)';
$message = '<html>
                            <title>JB SPORTS & MUSIC</title>
                        <style type="text/css">
                            div, p, a, li, td {
                                -webkit-text-size-adjust: none;
                            }
                        </style>
                    </head>
                    <body bgcolor="#FFFFFF">
                        <!-- Table Wrap  -->
                        <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="600">

                            <!-- Auto Preview Container -->
                            <thead>
                                <tr>
                                    <td align="center" valign="top" width="650" style="padding:0px">&nbsp;

                                    </td>
                                </tr>
                            </thead><!-- Auto Preview Container END -->

                            <!-- Content Container -->
                            <tbody>
                                <tr>
                                    <td align="center" valign="top" width="650" style="padding:0px">

                                        <!-- Table for Banner -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="middle" height="100" bgcolor="#3e4095" width="100%" style="padding-top: 15px; padding-right: 10px; padding-bottom: 10px; text-align: center; padding-left: 10px;">
                                                        <img src="http://clientportal.jbmusicjobtracker.com/resources/img/logo2.png" width="400">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table><!-- Table for Banner END -->

                                        <!-- Table for One Column -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="center" valign="middle" width="540" bgcolor="#3e4094" style="padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; border-top: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 16px; color: #FFFFFF;" >

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table><!-- Table for One Column END -->

                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding: 20px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" >
                                                        <table border="0" cellpadding="0" cellspacing="0" width="500">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="left" valign="top" width="540" colspan="2" bgcolor="#FFFFFF" style="padding-bottom:5px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" >
                                                                        <strong style="color: #222222;"><br>Message:</strong>
                                                                        <p>

                                                                        Good News!<br><br>

                                                                        Your item is ready for show off! Visit us and claim your repaired item at our store.<br><br>

                                                                        Store Location is: '.$branch.'<br><br>

                                                                        You can view your billing statement here: <a href="http://clientportal.jbmusicjobtracker.com/">Client Portal<a><br><br>

                                                                        Hope to see you soon!


                                                                        <br><br>
                                                                        Regards,<br>
                                                                        JB Music and Sports Repair Service Team
                                                                        </p>
                                                                        </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top" width="540" bgcolor="#FFFFFF" style="padding-bottom:14px; font-family: Arial, sans-serif; font-size: 14px; line-height: 18px; color: #444444;" >
                                                                        </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <br>
                                        <table width="540" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td width="540" align="left" valign="middle" bgcolor="#3e4094 " style="padding-top: 15px; padding-right: 10px; padding-bottom: 15px; padding-left: 20px; border-bottom: solid 5px #fff212; font-family: Arial, sans-serif; font-size: 12px; line-height: 14px; color: #FFFFFF;" >
                                                    This e-mail was sent as a notication for your Job Order Status with  <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody><!-- Content Container END -->
                            <!-- Bottom Spacer -->
                            <tfoot>
                                <tr>
                                    <td align="center" valign="top" width="650" height="5" style="padding:0px">&nbsp;
                                    </td>
                                </tr>
                            </tfoot><!-- Bottom Spacer END -->
                        </table><!-- Table Wrap END -->
                    </body>
                    </html>';

$headers = "From: JB MUSIC and SPORTS\r\n";
$headers .= "Reply-To: ". "mcclynreyarboleda@gmail.com" . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$retval =  mail($email, 'Job Done, Ready for Claiming', $message, $headers);

if($retval){
    echo "success";
}else{
    echo "false";
}
?>