<?

require_once("helper_mail.php");

$date = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"),  date("Y")));

$ce_name = "Bowl Bonanza Robot"; 
$ce_email = "brian.olzinski@mudbrickmedia.com";
$ce_email = "bolzinski@fosko.net";
$ce_subject = "Bowl Bonanza Entry from ". $participant_name;
$query_data = array('fp_name'=>$participant_name,'fp_email'=>$participant_email);
$query_vars = http_build_query($query_data);
$ce_link = "http://" . $_SERVER["SERVER_NAME"] . "/view_picks_process.php?$query_vars";
$ce_details = "Bowl Bonanza Entry Details:<br />Name: $participant_name<br />EMail: $participant_email<br />Date: $date<br />View Picks Link: $ce_link";


// mail routine
$tx = "";

$mail_from = $ce_email;
$mail_from_name = $ce_name;

$mail_to=$participant_email;

$mail_reply_to=$ce_email;

$mail_subject = $ce_subject;
$mail_body = $ce_details;

// ADD THIS SO EMAILS ARE NOT SENT DURING TESTING		
if ($_SERVER["SERVER_NAME"] != "bb2.olzinski.com") {
  if (!mail_sender($mail_body, $mail_to, $mail_reply_to, MAIL_HOST, $mail_from, $mail_from_name, $mail_subject)) {
	/* echo "";
	 echo "Mailer Error: " . $mail->ErrorInfo;*/
	  $error_message="Error:  There was a problem sending email * Mailer Error: " .$mail->ErrorInfo;
  ?>
	  <div class="spacer10 clear"></div>
  <? 	if ( $p_error_number > 0) { ?>
		 <span class="form_error_msg"><? echo ($error_message); ?></span>
  <? 	} 
  } // end mail routine
}

?>

