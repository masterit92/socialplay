<?php //if ( ! defined('ABSPATH')) exit('restricted access');
	require('../../../../../wp-includes/class-phpmailer.php');

//require_once ABSPATH . WPINC . '/class-smtp.php';
$mail             = new PHPMailer();
 
/*$body             = file_get_contents('contents.html');
$body             = eregi_replace("[\]",'',$body);*/
 
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mail.extracoding.net"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "mail.extracoding.net"; // sets the SMTP server
$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
$mail->Username   = "noreply@extracoding.net"; // SMTP account username
$mail->Password   = "u8KW&(oMi9zo";        // SMTP account password
 
$mail->SetFrom('shadab@extra.com', 'First Last');
 
//$mail->AddReplyTo("shadab@fourthwavetech.com","First Last");
 
$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";
 
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
 
$mail->MsgHTML("I am testing here");
 
$address = "shadab@fourthwavetech.com";
$mail->AddAddress($address, "Test");
 


	if(!$mail->Send()) {	
	echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
	


