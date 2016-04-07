<?php
include '../../include.php';
include '../include.php';

if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
  $eol="\r\n"; 
} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
  $eol="\r"; 
} else { 
  $eol="\n"; 
}

$subject = 'JB MUSIC & SPORTS';
$message = '<html>
                    <head>
                        <title>JB MUSIC & SPORTS</title>
                        <style type="text/css">
                            div, p, a, li, td {
                                -webkit-text-size-adjust: none;
                            }
                        </style>
                    </head>
                    <body bgcolor="#FFFFFF">
                        <!-- Table Wrap  -->
                        <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="600">
                            <!-- Content Container -->
                            <tbody>
                               <tr>
                                    <td align="center" valign="top" width="650" style="padding:0px">

                                        <!-- Table for Banner -->
                                        <table border="0" cellpadding="0" cellspacing="0" width="540">
                                            <tbody>
                                                <tr>
                                                    <td align="left" valign="middle" height="100" bgcolor="#3e4095" width="100%" style="padding-top: 15px; padding-right: 10px; padding-bottom: 10px; text-align: center; padding-left: 10px;">
                                                        <img src="'.SITE_IMAGES_DIR.'logo2.png" width="400">
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
                                                                        <strong style="color: #222222;"><br>Hi Test!</strong>
                                                                        <p>
                                                                            Your profile has been successfully updated!
                                                                        </p>
                                                                        <p>
                                                                            <br>
                                                                            <br>

                                                                            Have a great day.
                                                                            <br>
                                                                            All the best,<br>
                                                                            JB Music and Sports Team
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
                                                    This e-mail was sent as a updated for your account with  <a href="#" target="_blank" style="text-decoration:none; color: #FFFFFF;"><strong> JB Music & Sports</strong></a>
                                                    </td>
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
// $headers = "From: JB MUSIC & SPORTS <system@jbmusicjobtracker.com>\r\n";
// $headers .= "Reply-To: ". "jbmusicjobtracker@gmail.com" . "\r\n";
// $headers .= "MIME-Version: 1.0\r\n";
// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$headers = 'From: Jb Team <system@jbmusicjobtracker.com>'.$eol; 
$headers .= 'Reply-To: Jb Team <jbmusicjobtracker@gmail.com>'.$eol; 
$headers .= 'Return-Path: Jb Team <jbmusicjobtracker@gmail.com>'.$eol;     // these two to set reply address 
$headers .= "Message-ID:< TheSystem@".$_SERVER['SERVER_NAME'].">".$eol; 
$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters 
# Boundry for marking the split & Multitype Headers 
$mime_boundary=md5(time()); 
$headers .= 'MIME-Version: 1.0'.$eol; 
$headers .= "Content-Type: text/html; boundary=\"".$mime_boundary."\"".$eol; 

// var_dump(sendMail('argie.navora@gmail.com', $subject, $message));
var_dump(mail('argie.navora@gmail.com', $subject, $message, $headers));