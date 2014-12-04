<?php

require_once("helper_error.php");
require_once("helper_mail.php");
require_once("lib/participant_class.php");
require_once("lib/entry_class.php");
require_once("lib/validate_class.php");
require_once("lib/validator_helper_class.php");

define('BB_HOME_PAGE', 'home.php');
define('NUMBER_OF_BOWL_GAMES', 35);


//$default_name = DEFAULT_NAME . mt_rand(1, 500);
// Create new validator object
//$validator = new Validate();

// PHP validation is performed by the ValidatePHP method, which returns
// the page the visitor should be redirected to (which is this page if
// all the data is valid, or back to order_form.php if not)
//if ($validator->ValidatePHP_login() == 1) {
//	return_to_form("", 222201, LOGIN_FORM_PAGE);
//}

//2013-12-23: added check for empty fields, had multiple entries submitted with empty fields, coujldn't find BUG but expect this check should help prevent
$participant_email = trim($_POST['fp_email']);

if (!(isset($participant_email))) {
	return_to_form("Email empty - ", 222210, BB_HOME_PAGE);
}

$participant_name = trim($_POST['fp_name']);

if (!(isset($participant_name))) {
	return_to_form("Name empty - ", 222210, BB_HOME_PAGE);
}
$entry_tiebreaker = trim($_POST['fp_tie']);
if ($entry_tiebreaker == "") {
	return_to_form("Tie-breaker empty - ", 222210, BB_HOME_PAGE);
}

//2013-12-20: Denny asked to remove default "team 1" selections, added this check to make sure ALL games had a winner picked, also added client-side validation
for ( $i=1; $i<(NUMBER_OF_BOWL_GAMES + 1); $i++) {
  $rb_index = str_pad((int) $i,2,"0",STR_PAD_LEFT);
  if (isset($_POST[("fp_".$rb_index)])) {
	  continue;
  }
  else
  {
	  return_to_form("", 222203, BB_HOME_PAGE);
  }
}
//print "tie-breaker: " . $entry_tiebreaker;
//exit;

$p_participant = new participant();
	
$p_participant->new_participant($participant_name, $participant_email);
		
switch ($p_participant->sp_return_code) {
//switch (1) {
	case 1: // login successful!
//		print "message: " . $p_participant->msg;
		$p_participant->get_participant($participant_name, $participant_email);
		if ($p_participant->sp_return_code == 0) {
		  $row_count = sizeof($p_participant->result_list);
		  //echo "ROW COUNT: " . $row_count . "<BR>";
		  for ( $i=0; $i<$row_count; $i++) {  	
			  $participant_id = $p_participant->result_list[$i]['participant_id'];
		  } /* end FOR */
		  for ( $i=1; $i<(NUMBER_OF_BOWL_GAMES + 1); $i++) {  
			  $rb_index = str_pad((int) $i,2,"0",STR_PAD_LEFT); //pad 0 left single digit bowl id to access corresponding radio button selection post var
			  //print $rb_index . " : " . $_POST[$rb_index] . "<br>";
			  $bowl_id = $rb_index; 
			  $team_selected = $_POST["fp_".$rb_index];
			  $p_participant->new_entry($participant_id, $participant_email, $bowl_id, $team_selected, $entry_tiebreaker);
			  switch ($p_participant->sp_return_code) {
				case 0:
				case 1:
					//print "message: " . $p_participant->msg;
					break ;
				case 106201:
					return_to_form("", 222210, BB_HOME_PAGE);
					break ;
				case 106202:
					return_to_form("", 222211, BB_HOME_PAGE);
					break ;
				case 106203:
					return_to_form("", 222212, BB_HOME_PAGE);
					//print "message: " . $p_participant->msg . "<br>";
					break ;
				default:
					return_to_form("", 444401, BB_HOME_PAGE);
					//print "message: " . $p_participant->msg . "<br>";
					break;
			  } /* end switch */
		  } /* end for each */
		} /* end if */
		else {
			print "error getting participant ID, message: " . $p_participant->msg . "<br>";
		}
		break ;
	case -99:
		return_to_form("", 222201, BB_HOME_PAGE);
		//unset($_SESSION['valid_user']);
		//session_destroy();
		//return_to_form($p_participant->msg, 222202, LOGIN_FORM_PAGE); /* user or password incorrect */
		break ;
	default:
		return_to_form("", 222202, BB_HOME_PAGE);
		//return_to_form($p_participant->msg, 222202, LOGIN_FORM_PAGE); /* unexpected SQL error */
		break;
} /* end switch */

?>

<?php
  	require_once("email_picks.php");
	require_once("view_picks_sub.php");
?>