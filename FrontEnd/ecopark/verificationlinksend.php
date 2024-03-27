<?php
//Use the following exact namespaces no matter which directory your phpmailer files are in.

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Now include the followingfiles based on the correct file path. 
// SMTP.php is required to enable SMTP.
//
// Uses the QUB O365 SMTP Mail Servers
// You must use your studnet account to authenticate
// For Multi-factor Authenication setup and use an O365 App Password
// Replace
// STUDENTNUMBER with your qub student number to use your emaila ccount
// OFFICE365APPPASSWORD replace with an O365 App Password

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true); 

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.office365.com';  				  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = '40238855@ads.qub.ac.uk';      // SMTP username
$mail->Password = 'nmbtrqygvcnkvbcy';             // SMTP password - use Office365 App Password here
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

//FROM
$mail->From = '40238855@ads.qub.ac.uk';
$mail->FromName = 'Ecopark';

// RECIPIENTS
$mail->addAddress($email, $fname);       // Add a recipient - use your address to test

// MESSAGE DETAILS
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$verificationLink = "https://rjohnston80.webhosting3.eeecs.qub.ac.uk/registerapi/?function=verify&verify=" . $verification . "&id=" . $userNo;

$mail->Subject = 'Ecopark - Welcome ' . $fname . '!';
$mail->Body    = "Thank you for registering with Ecopark " . $fname . "! Your username is " . $userNo . ". In order to login you will need to first verify your account, you can do this by following the link below.\n" . $verificationLink;
$mail->AltBody = 'Please confirm your registration via the link below:';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}