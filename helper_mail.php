<?php

// mail routine
require("lib/class.phpmailer.php");

//define('MMV_MAIL_HOST', '192.168.200.206');
define('MAIL_HOST', 'mail.mudbrickmotionmedia.com');
//define('MMV_MAIL_HOST', 'mail.fosko.net');
//define('MMV_MAIL_HOST', 'mail.cassiopeiaproject.com');
define('EMAIL_FROM_ADDRESS', 'motionmedia@mudbrickmotionmedia.com');
define('EMAIL_FROM_ADDRESS_NAME', 'MudBrick Media Contact');
define('EMAIL_SUBJECT', 'MudBrick Email Contact Form');
define('CONTACT_US_MAIL_ADDRESS', 'contact@mudbrickmedia.com');
/* ------------------------------------------------------------------------------------------ */
/*                    FUNCTIONS
/* ------------------------------------------------------------------------------------------ */
function mail_sender($mail_body, $mail_to="bolzinski@fosko.net", $mail_reply_to=EMAIL_FROM_ADDRESS,$mail_host=MAIL_HOST,  $mail_from=EMAIL_FROM_ADDRESS, $mail_from_name=EMAIL_FROM_ADDRESS_NAME, $mail_subject=EMAIL_SUBJECT) {
	
	$mail = new PHPMailer();

	$mail->IsMail(); 
	$mail->Host = $mail_host;
	//$mail->Host = "mail.cassiopeiaproject.com";
	//$mail->Username = "bolzinski@fosko.net";
	//$mail->Password = "";
	$mail->From = $mail_from;
	$mail->FromName = $mail_from_name;
	//$mail->AddBCC("mark@markerwin.com");
	//$mail->AddBCC("bolzinski@fosko.net");
	$mail->AddReplyTo($mail_reply_to);
	$mail->AddAddress($mail_to);
					
	//$mail->AddAddress("bolzinski@fosko.net");
	$mail->AddBCC("einstein@fosko.net");
	$mail->AddBCC("brian.olzinski@mudbrickmedia.com");
	$mail->WordWrap = 50;                          
	$mail->IsHTML(true);                  
	
	$mail->Subject = $mail_subject;
	$mail->Body    = $mail_body;
	$mail->AltBody = "";
	//echo "<BR>ABOUT TO SEND MAIL FROM: " .$mail->Host. "<BR>";
	//echo "about to mail!<BR>";
	if($mail->Send()) {
		//echo "MAIL SUCCESS!<BR>";
		return 1; /* mail sent! */
	 }
	 else {
	 	//echo "MAIL ERROR<BR>";
	 	return 0; /* mail error */
	} /* end if */

} /* end function */


?>